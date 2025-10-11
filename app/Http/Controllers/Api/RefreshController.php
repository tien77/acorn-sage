<?php

namespace App\Http\Controllers\Api;

use App\Models\RefreshToken;
use App\Support\Jwt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RefreshController
{
    // POST /api/refresh (dùng cookie refresh_token)
    public function __invoke(Request $request)
    {
        $plain = $request->cookie('refresh_token');
        if (! $plain) {
            return response()->json(['message' => 'Missing refresh token'], 401);
        }

        // Tìm token trong DB bằng cách so sánh hash (an toàn)
        $candidate = RefreshToken::where('revoked', false)
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->get()
            ->first(function ($row) use ($plain) {
                return Hash::check($plain, $row->token_hash);
            });

        if (! $candidate) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        // ROTATE: tạo refresh token mới, revoke token cũ
        $newPlain  = Str::random(64);
        $newHash   = Hash::make($newPlain);
        $ttl       = (int) env('JWT_REFRESH_TTL', 60 * 60 * 24 * 30);
        $expiresAt = Carbon::now()->addSeconds($ttl);

        $new = RefreshToken::create([
            'user_id'    => $candidate->user_id,
            'token_hash' => $newHash,
            'user_agent' => (string) $request->userAgent(),
            'ip_address' => (string) $request->ip(),
            'expires_at' => $expiresAt,
            'revoked'    => false,
        ]);

        $candidate->revoked = true;
        $candidate->replaced_by_id = $new->id;
        $candidate->save();

        // cấp access token mới
        $access = Jwt::makeAccessToken((int) $candidate->user_id);

        // đặt cookie refresh token mới
        $cookie = cookie(
            name: 'refresh_token',
            value: $newPlain,
            minutes: $ttl / 60,
            path: '/api',
            domain: null,
            secure: !app()->environment('local'),
            httpOnly: true,
            raw: false,
            sameSite: 'Lax'
        );

        return response()->json([
            'access_token' => $access,
            'token_type'   => 'Bearer',
            'expires_in'   => (int) env('JWT_TTL', 900),
        ])->withCookie($cookie);
    }
}
