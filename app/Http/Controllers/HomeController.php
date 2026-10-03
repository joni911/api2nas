<?php

namespace App\Http\Controllers;

use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::id();

        $apiKeys = ApiKey::where('user_id', $userId)->count();

        $fileQuery = fn () => ApiData::whereHas('apiKey', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        $files = $fileQuery()->count();

        $storageBytes = $fileQuery()->get()->sum(function (ApiData $data) {
            return Storage::disk('public')->exists($data->file_path)
                ? Storage::disk('public')->size($data->file_path)
                : 0;
        });

        $users = User::count();

        $recent = $fileQuery()->with('apiKey')->latest()->take(5)->get();

        return view('home', compact('apiKeys', 'files', 'storageBytes', 'users', 'recent'));
    }
}