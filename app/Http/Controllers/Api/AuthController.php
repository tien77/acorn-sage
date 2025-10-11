<?php

namespace App\Http\Controllers\Api;

use App\Models\RefreshToken;
use App\Support\Jwt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class AuthController
{
    public function login(Request $request)
    {


       $validator = Validator::make($request->all(), [
			'username' => 'required|string',
			'password' => 'required|string',
	   ], [
			'username.required' => 'Vui lòng nhập username.',
			'password.required' => 'Vui lòng nhập password.',
	   ]);

	   if ($validator->fails()) {
		   return response()->json(['message' => 'Invalid input', 'errors' => $validator->errors()], 422);
	   }

        // Xác thực bằng WP
        $wpUser = wp_authenticate($request->input('username'), $request->input('password'));
        if (is_wp_error($wpUser)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $userId = (int) $wpUser->ID;

        // 1) access token (JWT)
        $access = Jwt::makeAccessToken($userId);

        // 2) refresh token (random string, lưu hash vào DB)
        $plainRefresh = Str::random(64);
        $hash = Hash::make($plainRefresh);
        $ttl = (int) env('JWT_REFRESH_TTL', 60 * 60 * 24 * 30);
        $expiresAt = Carbon::now()->addSeconds($ttl);

        $row = RefreshToken::create([
            'user_id'    => $userId,
            'token_hash' => $hash,
            'user_agent' => (string) $request->userAgent(),
            'ip_address' => (string) $request->ip(),
            'expires_at' => $expiresAt,
            'revoked'    => false,
        ]);

        // set HttpOnly cookie cho refresh token (giữ client-side JS không đọc được)
        $cookie = cookie(
            name: 'refresh_token',
            value: $plainRefresh,
            minutes: $ttl / 60,
            path: '/api',         // chỉ gửi cho đường dẫn /api
            domain: null,
            secure: !app()->environment('local'), // true ở production
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

    // POST /api/logout
    public function logout(Request $request)
    {
        $plain = $request->cookie('refresh_token');
        if ($plain) {
            $this->revokeIfExists($plain);
        }

        // xoá cookie
        $forget = cookie()->forget('refresh_token', '/', null, !app()->environment('local'), true, false, 'Lax');

        return response()->json(['ok' => true])->withCookie($forget);
    }
    
    protected function revokeIfExists(string $plain): void
    {
        $tokens = RefreshToken::where('revoked', false)
            ->where('expires_at', '>', now())->get();

        foreach ($tokens as $t) {
            if (Hash::check($plain, $t->token_hash)) {
                $t->revoked = true;
                $t->save();
                break;
            }
        }
    }

}
