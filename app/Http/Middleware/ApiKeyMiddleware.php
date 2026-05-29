<?php
namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-API-Key') ?? $request->query('api_key');

        if (!$key) {
            return response()->json(['message' => 'API Key tidak ditemukan'], 401);
        }

        $apiKey = ApiKey::where('key', $key)
                        ->where('is_active', true)
                        ->first();

        if (!$apiKey) {
            return response()->json(['message' => 'API Key tidak valid'], 401);
        }

        // Cek expiry
        if ($apiKey->expires_at && now()->gt($apiKey->expires_at)) {
            return response()->json(['message' => 'API Key sudah expired'], 401);
        }

        // Inject user ke request
        $request->merge(['api_key_user' => $apiKey->user]);

        return $next($request);
    }
}