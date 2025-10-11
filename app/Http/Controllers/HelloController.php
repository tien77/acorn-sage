<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class HelloController extends Controller
{
    public function index()
    {
        return view('pages.hello', [
            'message' => 'Xin chào từ Sage + Acorn Route với Middleware!',
        ]);
    }
}
