<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * khái báo các middleware alias (tên gọi tắt)
     */
    protected $routeMiddleware = [
        'log' => \App\Http\Middleware\LogRequestMiddleware::class, // đăng ký middleware alias => 'log'
    ];
    
}
