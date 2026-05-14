<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\ApiData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function health()
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    }

    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|string', // base64
            'nama_sistem' => 'required|string|max:255',
            'id_tabel' => 'nullable|string|max:255',
            'tabel_name' => 'nullable|string|max:255',
            'apikey' => 'required|string|exists:api_keys,api_key',
            'extension' => 'nullable|string|in:jpg,jpeg,png,gif,webp,pdf,txt,csv,json,doc,docx,xls,xlsx,ppt,pptx,zip,rar'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verifikasi API key
        $apiKey = ApiKey::where('api_key', $request->apikey)->first();
        if (!$apiKey || !$apiKey->is_active) {
            return response()->json(['error' => 'Invalid or inactive API key'], 401);
        }

        // Decode base64 file
        $fileData = $request->file;

        // Cek apakah string adalah base64 valid
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $fileData)) {
            return response()->json(['error' => 'Invalid base64 format'], 400);
        }

        $fileContent = base64_decode($fileData);
        if ($fileContent === false) {
            return response()->json(['error' => 'Invalid base64 file'], 400);
        }

        // Deteksi tipe file dari header base64 jika ada
        $extension = 'bin'; // default extension
        $mimeType = '';

        // Cek apakah base64 memiliki header data URI
        if (preg_match('/^data:image\/(\w+);base64,/', $fileData, $matches)) {
            // Ekstrak tipe dari header data URI
            $imageType = $matches[1];
            switch ($imageType) {
                case 'jpeg':
                case 'jpg':
                    $extension = 'jpg';
                    $mimeType = 'image/jpeg';
                    break;
                case 'png':
                    $extension = 'png';
                    $mimeType = 'image/png';
                    break;
                case 'gif':
                    $extension = 'gif';
                    $mimeType = 'image/gif';
                    break;
                case 'webp':
                    $extension = 'webp';
                    $mimeType = 'image/webp';
                    break;
                default:
                    $extension = $imageType;
                    $mimeType = 'image/' . $imageType;
            }

            // Hapus header dari data base64
            $fileData = preg_replace('/^data:image\/\w+;base64,/', '', $fileData);
        } else {
            // Jika tidak ada header data URI, decode dan deteksi dari konten
            $fileContent = base64_decode($fileData);
            if ($fileContent !== false) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $fileContent);
                finfo_close($finfo);

                switch ($mimeType) {
                    // Gambar
                    case 'image/jpeg':
                        $extension = 'jpg';
                        break;
                    case 'image/png':
                        $extension = 'png';
                        break;
                    case 'image/gif':
                        $extension = 'gif';
                        break;
                    case 'image/webp':
                        $extension = 'webp';
                        break;

                    // Dokumen Office
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        $extension = 'docx';
                        break;
                    case 'application/msword':
                        $extension = 'doc';
                        break;
                    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                        $extension = 'xlsx';
                        break;
                    case 'application/vnd.ms-excel':
                        $extension = 'xls';
                        break;
                    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                        $extension = 'pptx';
                        break;
                    case 'application/vnd.ms-powerpoint':
                        $extension = 'ppt';
                        break;

                    // PDF dan teks
                    case 'application/pdf':
                        $extension = 'pdf';
                        break;
                    case 'text/plain':
                        $extension = 'txt';
                        break;
                    case 'text/csv':
                        $extension = 'csv';
                        break;
                    case 'application/json':
                        $extension = 'json';
                        break;
                    case 'application/zip':
                        $extension = 'zip';
                        break;
                    case 'application/x-rar-compressed':
                        $extension = 'rar';
                        break;
                    default:
                        // Coba deteksi dari magic bytes
                        $binary = substr($fileContent, 0, 8);
                        $hex = bin2hex($binary);

                        // JPEG
                        if (strpos($hex, 'ffd8ffe0') === 0 || strpos($hex, 'ffd8ffe1') === 0 || strpos($hex, 'ffd8ff') === 0) {
                            $extension = 'jpg';
                            $mimeType = 'image/jpeg';
                        }
                        // PNG
                        elseif (strpos($hex, '89504e470d0a1a0a') === 0) {
                            $extension = 'png';
                            $mimeType = 'image/png';
                        }
                        // GIF
                        elseif (strpos($hex, '474946383761') === 0 || strpos($hex, '474946383961') === 0) {
                            $extension = 'gif';
                            $mimeType = 'image/gif';
                        }
                        // PDF
                        elseif (strpos($hex, '25504446') === 0) {
                            $extension = 'pdf';
                            $mimeType = 'application/pdf';
                        }
                        // DOCX/XLSX/PPTX (ZIP-based formats)
                        elseif (strpos($hex, '504b0304') === 0) {
                            // Ini adalah file ZIP-based, bisa jadi DOCX/XLSX/PPTX
                            // Kita perlu cek lebih lanjut dengan membaca konten
                            $tempFilePath = storage_path('app/temp_' . Str::random(10) . '.tmp');
                            file_put_contents($tempFilePath, $fileContent);

                            $zip = new \ZipArchive();
                            if ($zip->open($tempFilePath) === TRUE) {
                                $contentType = $zip->getFromName('[Content_Types].xml');
                                if ($contentType !== false) {
                                    if (strpos($contentType, 'wordprocessingml.document.main+xml') !== false) {
                                        $extension = 'docx';
                                        $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                                    } elseif (strpos($contentType, 'spreadsheetml.sheet.main+xml') !== false) {
                                        $extension = 'xlsx';
                                        $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                                    } elseif (strpos($contentType, 'presentationml.presentation.main+xml') !== false) {
                                        $extension = 'pptx';
                                        $mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                                    }
                                }
                                $zip->close();
                            }

                            // Hapus file sementara
                            unlink($tempFilePath);
                        }
                        // DOC (older format)
                        elseif (strpos($hex, 'd0cf11e0a1b11ae1') === 0) {
                            $extension = 'doc';
                            $mimeType = 'application/msword';
                        }
                        break;
                }
            }
        }

        // Gunakan ekstensi dari request jika disediakan, jika tidak gunakan yang terdeteksi
        $extension = $request->extension ?? $extension;

        // Buat nama file yang aman
        $safeFileName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $request->nama_sistem);
        $fileName = $safeFileName . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        $filePath = 'uploads/' . date('Y/m');

        // Buat direktori jika belum ada
        if (!Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->makeDirectory($filePath, 0755, true);
        }

        $fullPath = $filePath . '/' . $fileName;

        // Simpan file dan cek apakah berhasil
        $result = Storage::disk('public')->put($fullPath, $fileContent);
        if (!$result) {
            return response()->json(['error' => 'Failed to save file'], 500);
        }

        // Buat URL publik
        $publicUrl = asset('storage/' . $fullPath);

        // Simpan data ke tabel api_data - id_tabel dan tabel_name opsional
        $apiData = ApiData::create([
            'api_id' => $apiKey->id,
            'nama_file' => $fileName,
            'ip_address' => $request->ip(),
            'file_path' => $fullPath,
            'url' => $publicUrl,
            'id_tabel' => $request->filled('id_tabel') ? $request->id_tabel : null,
            'tabel_name' => $request->filled('tabel_name') ? $request->tabel_name : null
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $apiData->id,
                'url' => $publicUrl,
                'file_info' => [
                    'original_name' => $request->nama_sistem,
                    'stored_name' => $fileName,
                    'size' => strlen($fileContent),
                    'mime_type' => $mimeType
                ]
            ]
        ], 201);
    }

    public function getData($id)
    {
        $apiData = ApiData::findOrFail($id);
        
        return response()->json([
            'id' => $apiData->id,
            'url' => $apiData->url,
            'nama_file' => $apiData->nama_file,
            'file_path' => $apiData->file_path,
            'id_tabel' => $apiData->id_tabel,
            'tabel_name' => $apiData->tabel_name,
            'created_at' => $apiData->created_at
        ]);
    }
}