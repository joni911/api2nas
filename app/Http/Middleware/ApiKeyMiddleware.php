<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key') ?: $request->query('apikey');

        if (!$apiKey) {
            return response()->json(['error' => 'API key is required'], 401);
        }

        $key = ApiKey::where('api_key', $apiKey)->first();

        if (!$key || !$key->is_active) {
            return response()->json(['error' => 'Invalid or inactive API key'], 401);
        }

        // Tambahkan API key ke request untuk digunakan di controller
        $request->merge(['authenticated_api_key' => $key]);

        return $next($request);
    }
}