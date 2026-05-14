<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiKeys = ApiKey::paginate(10);
        return view('api-management.index', compact('apiKeys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('api-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ApiKey::create([
            'user_id' => Auth::id(),
            'api_key' => 'api_' . Str::random(32),
            'name' => $request->name,
            'is_active' => true
        ]);

        return redirect()->route('api-management.index')->with('success', 'API Key created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $apiKey = ApiKey::where('user_id', Auth::id())->findOrFail($id);
        return view('api-management.show', compact('apiKey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $apiKey = ApiKey::findOrFail($id);
        return view('api-management.edit', compact('apiKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $apiKey = ApiKey::where('user_id', Auth::id())->findOrFail($id);
        $apiKey->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? $request->is_active : $apiKey->is_active
        ]);

        return redirect()->route('api-management.index')->with('success', 'API Key updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $apiKey = ApiKey::where('user_id', Auth::id())->findOrFail($id);
        $apiKey->delete();

        return redirect()->route('api-management.index')->with('success', 'API Key deleted successfully');
    }
}