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
            'extension' => 'nullable|string|in:jpg,jpeg,png,gif,webp,pdf,txt,csv,json,xml,html,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z,gz,tar,bz2,xz,sql,db,apk,jar,log,svg,bmp,tiff,ico,bin'
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

        // Deteksi dan strip header data URI jika ada, sebelum validasi base64
        $detectedMime = null;
        if (preg_match('/^data:([\w\/\+]+);base64,/', $fileData, $matches)) {
            $detectedMime = $matches[1];
            $fileData = preg_replace('/^data:[\w\/\+]+;base64,/', '', $fileData);
        }

        // Cek apakah string adalah base64 valid
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $fileData)) {
            return response()->json(['error' => 'Invalid base64 format'], 400);
        }

        $fileContent = base64_decode($fileData);
        if ($fileContent === false) {
            return response()->json(['error' => 'Invalid base64 file'], 400);
        }

        // Deteksi tipe file dari header data URI atau dari konten
        $extension = 'bin'; // default extension
        $mimeType = '';

        if ($detectedMime !== null) {
            $mimeType = $detectedMime;
            $extension = $this->getExtensionFromMime($detectedMime);
        } else {
            // Deteksi menggunakan finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $fileContent);
            finfo_close($finfo);

            // Deteksi berdasarkan MIME type
            $extension = $this->getExtensionFromMime($mimeType);

            // Jika masih bin atau txt, coba deteksi lebih lanjut
            if ($extension === 'bin' || $extension === 'txt') {
                $extension = $this->detectExtensionFromContent($fileContent, $mimeType);
            }
        }

        // Gunakan ekstensi dari request jika disediakan, jika tidak gunakan yang terdeteksi
        $extension = $request->extension ?? $extension;

        // Buat nama file yang aman
        $safeFileName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $request->nama_sistem);
        $fileName = $safeFileName . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        
        // Struktur folder baru: api_name/year/month
        $apiKeyName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $apiKey->name);
        $filePath = $apiKeyName . '/' . date('Y/m');

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
            'status' => 'uploaded',
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

    private function getExtensionFromMime($mimeType)
    {
        $mimeMap = [
            // Images
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/bmp' => 'bmp',
            'image/tiff' => 'tiff',
            'image/x-icon' => 'ico',
            
            // Documents
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'text/csv' => 'csv',
            'text/html' => 'html',
            'text/xml' => 'xml',
            'application/json' => 'json',
            'application/xml' => 'xml',
            
            // Archives
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/x-7z-compressed' => '7z',
            'application/gzip' => 'gz',
            'application/x-gzip' => 'gz',
            'application/x-tar' => 'tar',
            'application/x-bzip2' => 'bz2',
            'application/x-xz' => 'xz',
            
            // Database
            'application/sql' => 'sql',
            'text/x-sql' => 'sql',
        ];

        return $mimeMap[$mimeType] ?? 'bin';
    }

    private function detectExtensionFromContent($fileContent, $mimeType)
    {
        if (empty($fileContent)) {
            return 'bin';
        }

        $binary = substr($fileContent, 0, 16);
        $hex = bin2hex($binary);

        // Magic bytes detection
        // Images
        if (str_starts_with($hex, 'ffd8ff')) {
            return 'jpg';
        }
        if (str_starts_with($hex, '89504e470d0a1a0a')) {
            return 'png';
        }
        if (str_starts_with($hex, '47494638')) {
            return 'gif';
        }
        if (str_starts_with($hex, '52494646') && strpos($fileContent, 'WEBP') !== false) {
            return 'webp';
        }
        if (str_starts_with($hex, '424d')) {
            return 'bmp';
        }
        if (str_starts_with($hex, '49492a00') || str_starts_with($hex, '4d4d002a')) {
            return 'tiff';
        }
        
        // PDF
        if (str_starts_with($hex, '25504446')) {
            return 'pdf';
        }
        
        // Archives
        // ZIP (also covers DOCX, XLSX, PPTX, JAR, APK, etc.)
        if (str_starts_with($hex, '504b0304') || str_starts_with($hex, '504b0506') || str_starts_with($hex, '504b0708')) {
            // Check if it's a specific ZIP-based format
            $tempFilePath = storage_path('app/temp_' . Str::random(10) . '.tmp');
            file_put_contents($tempFilePath, $fileContent);

            $zip = new \ZipArchive();
            if ($zip->open($tempFilePath) === TRUE) {
                // Check for Office documents
                $contentType = $zip->getFromName('[Content_Types].xml');
                if ($contentType !== false) {
                    if (strpos($contentType, 'wordprocessingml') !== false) {
                        $zip->close();
                        unlink($tempFilePath);
                        return 'docx';
                    }
                    if (strpos($contentType, 'spreadsheetml') !== false) {
                        $zip->close();
                        unlink($tempFilePath);
                        return 'xlsx';
                    }
                    if (strpos($contentType, 'presentationml') !== false) {
                        $zip->close();
                        unlink($tempFilePath);
                        return 'pptx';
                    }
                }
                
                // Check for APK
                if ($zip->getFromName('AndroidManifest.xml') !== false) {
                    $zip->close();
                    unlink($tempFilePath);
                    return 'apk';
                }
                
                // Check for JAR
                if ($zip->getFromName('META-INF/MANIFEST.MF') !== false) {
                    $zip->close();
                    unlink($tempFilePath);
                    return 'jar';
                }
                
                $zip->close();
            }
            
            // Hapus file sementara
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            
            return 'zip';
        }
        
        // RAR (old format)
        if (str_starts_with($hex, '526172211a0700')) {
            return 'rar';
        }
        // RAR (new format)
        if (str_starts_with($hex, '526172211a070100')) {
            return 'rar';
        }
        
        // 7z
        if (str_starts_with($hex, '377abcaf271c')) {
            return '7z';
        }
        
        // GZIP
        if (str_starts_with($hex, '1f8b')) {
            return 'gz';
        }
        
        // BZ2
        if (str_starts_with($hex, '425a68')) {
            return 'bz2';
        }
        
        // XZ
        if (str_starts_with($hex, 'fd377a585a00')) {
            return 'xz';
        }
        
        // TAR (ustar at offset 257)
        if (strlen($fileContent) > 263) {
            $ustar = substr($fileContent, 257, 5);
            if ($ustar === 'ustar') {
                return 'tar';
            }
        }
        
        // Old Office formats (OLE2)
        if (str_starts_with($hex, 'd0cf11e0a1b11ae1')) {
            // Could be DOC, XLS, PPT, MSG, etc.
            // Default to doc for OLE2
            return 'doc';
        }
        
        // SQLite database
        if (str_starts_with($fileContent, 'SQLite format 3')) {
            return 'db';
        }
        
        // Text-based detection
        if ($mimeType === 'text/plain' || $mimeType === 'application/octet-stream' || $mimeType === '') {
            $textContent = substr($fileContent, 0, 1000);
            
            // SQL detection
            $sqlPatterns = [
                '/^--\s/m',
                '/^CREATE\s+(TABLE|DATABASE|INDEX|VIEW|PROCEDURE|FUNCTION|TRIGGER)/im',
                '/^INSERT\s+INTO/im',
                '/^DROP\s+(TABLE|DATABASE|INDEX|VIEW)/im',
                '/^ALTER\s+TABLE/im',
                '/^USE\s+\w+/im',
                '/^SET\s+\w+/im',
                '/^LOCK\s+TABLES/im',
                '/^UNLOCK\s+TABLES/im',
                '/^DELIMITER/im',
                '/^\/\*!(\d+)/m',
            ];
            
            foreach ($sqlPatterns as $pattern) {
                if (preg_match($pattern, $textContent)) {
                    return 'sql';
                }
            }
            
            // XML detection
            if (preg_match('/^<\?xml\s+version/im', $textContent) || preg_match('/^<[a-zA-Z][\w:.-]*>/m', $textContent)) {
                return 'xml';
            }
            
            // HTML detection
            if (preg_match('/<!DOCTYPE\s+html/im', $textContent) || preg_match('/<html[\s>]/im', $textContent)) {
                return 'html';
            }
            
            // CSV detection
            if (preg_match('/^"[^"]*"(,[^,]*)*$/m', $textContent) || preg_match('/^[\w\s]+(,[\w\s]+)+$/m', $textContent)) {
                return 'csv';
            }
            
            // JSON detection
            $decoded = json_decode($textContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return 'json';
            }
            
            // Log files
            if (preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/m', $textContent)) {
                return 'log';
            }
        }
        
        return 'bin';
    }
}