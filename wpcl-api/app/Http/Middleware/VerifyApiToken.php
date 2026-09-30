<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-Token');
        $expected = (string) config('app.api_token');

        if ($expected === '' || !hash_equals($expected, (string) $token)) {
            return response()->json([
                'message' => 'Unauthorized. Token API tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}
