<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
		// nếu ip là 123.456.789.000 thì chặn request
		if ($request->ip() === '123.456.789.000') {
			// return response()->json(['error' => 'Unauthorized'], 403);
			abort(403, 'Unauthorized');
		}
        Log::info('Request URL: '.$request->fullUrl());
        return $next($request);
    }
}
