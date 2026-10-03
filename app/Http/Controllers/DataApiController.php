<?php

namespace App\Http\Controllers;

use App\Models\ApiData;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan daftar data upload milik user yang login
        $user = Auth::user();
        $apiKeys = collect();
        if (!$user) {
            $apiDatas = collect();
        } else {
            $apiDatas = ApiData::whereHas('apiKey', function($query) {
                $query->where('user_id', Auth::id());
            })->with('apiKey')->paginate(10);

            $apiKeys = ApiKey::where('user_id', Auth::id())->orderBy('name')->get();
        }

        return view('data-api.index', compact('apiDatas', 'apiKeys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak perlu form create karena data dibuat lewat API
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Data tidak disimpan langsung dari sini, tapi dari endpoint API
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $apiData = ApiData::whereHas('apiKey', function($query) {
            $query->where('user_id', Auth::id());
        })->with('apiKey')->findOrFail($id);

        return view('data-api.show', compact('apiData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Data tidak diedit dari sini
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Data tidak diupdate dari sini
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $apiData = ApiData::whereHas('apiKey', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        // Hapus file dari storage jika ada
        if ($apiData->file_path) {
            \Storage::disk('public')->delete($apiData->file_path);
        }

        $apiData->delete();

        return redirect()->route('data-api.index')->with('success', 'Data deleted successfully');
    }
}