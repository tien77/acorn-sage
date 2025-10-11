<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Jwt as JwtSupport;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

class VerifyJwt
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->headers->get('Authorization', '');
        if (!preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
            return $this->unauthorized('Missing Bearer token', 'token_missing');
        }
        $token = trim($m[1]);

        try {
            $claims = JwtSupport::decodeClaims($token);

            if (isset($claims['uid'])) {
                $request->attributes->set('jwt_user_id', (int) $claims['uid']);
            }
            $request->attributes->set('jwt_payload', $claims);

            return $next($request);

        } catch (ExpiredException $e) {
            return $this->unauthorized('Token expired', 'token_expired');
        } catch (SignatureInvalidException|BeforeValidException $e) {
            return $this->unauthorized('Invalid token', 'token_invalid');
        } catch (\UnexpectedValueException $e) {
            // từ validate issuer
            return $this->unauthorized('Invalid token issuer', 'token_invalid_iss');
        } catch (\Throwable $e) {
            return $this->unauthorized('Invalid token', 'token_invalid');
        }
    }

    private function unauthorized(string $message, string $code): Response
    {
        return response()->json(['message' => $message, 'code' => $code], 401);
    }
}
