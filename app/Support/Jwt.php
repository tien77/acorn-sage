<?php

namespace App\Support;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class Jwt
{
    public static function secret(): string
    {
        $secret = env('JWT_SECRET');
        return str_starts_with($secret, 'base64:')
            ? base64_decode(substr($secret, 7))
            : $secret;
    }

    public static function makeAccessToken(int $userId): string
    {
        $ttl = (int) env('JWT_TTL', 900); // seconds
        $now = time();

        $payload = [
            'iss' => rtrim(home_url('/'), '/'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
            'uid' => $userId,
        ];

        return FirebaseJWT::encode($payload, self::secret(), 'HS256');
    }

    /** Decode + validate (leeway, issuer). Trả về mảng claims. */
    public static function decodeClaims(string $jwt): array
    {
        FirebaseJWT::$leeway = (int) env('JWT_LEEWAY', 5); // vài giây lệch giờ
        $payload = FirebaseJWT::decode($jwt, new Key(self::secret(), 'HS256'));
        $claims  = (array) $payload;

        // Validate issuer để tránh token ngoại lai
        $expected = rtrim(home_url('/'), '/');
        if (isset($claims['iss']) && rtrim((string) $claims['iss'], '/') !== $expected) {
            // ném UnexpectedValueException để middleware phân loại thành token_invalid_iss
            throw new \UnexpectedValueException('Invalid issuer');
        }

        return $claims;
    }
}
