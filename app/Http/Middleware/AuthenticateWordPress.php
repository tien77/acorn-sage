<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateWordPress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // Kiểm tra xem user có đăng nhập WordPress hay không
        if (!is_user_logged_in()) {
            // Nếu là AJAX request thì trả về JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Redirect về trang login với redirect_to parameter
            $redirectTo = urlencode($request->fullUrl());
            
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để truy cập trang này.')
                ->withInput(['redirect_to' => $redirectTo]);
        }

        return $next($request);
    }
}