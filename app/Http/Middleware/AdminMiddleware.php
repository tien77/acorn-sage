<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        $user = \wp_get_current_user();
        
        if (!$user || $user->ID === 0) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Kiểm tra quyền admin (administrator hoặc editor)
        if (!$user->has_cap('manage_options') && !$user->has_cap('edit_others_posts')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}