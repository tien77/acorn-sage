<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\WpUser;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLoginForm()
    {
        // Nếu đã đăng nhập thì redirect về trang chủ
        if (is_user_logged_in()) {
            return redirect()->route('home');
        }

        return view('pages.auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
		$request->merge([
			'remember' => in_array($request->input('remember'), [true, 'true', 1, '1', 'on'], true) ? 1 : ($request->has('remember') ? 0 : null)
		]);
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'remember'    => 'sometimes|nullable|boolean',
			'redirect_to' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $username = $request->input('username');
        $password = $request->input('password');
		$remember = $request->boolean('remember');// Ép kiểu checkbox -> bool (mặc định false nếu không có)

        // Sử dụng WordPress authentication
        $credentials = [
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember
        ];

        $user = wp_signon($credentials, false);

        if (is_wp_error($user)) {
            return back()
                ->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])
                ->withInput($request->except('password'));
        }

        // Đăng nhập thành công
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, $remember);

        // Redirect về trang được yêu cầu hoặc dashboard
        $redirectTo = $request->input('redirect_to', route('dashboard'));
        
        return redirect($redirectTo)->with('success', 'Đăng nhập thành công!');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        wp_logout();
        
        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công!');
    }

    /**
     * Hiển thị dashboard sau khi đăng nhập
     */
    public function dashboard()
    {
        if (!is_user_logged_in()) {
            return redirect()->route('login');
        }

        $user = wp_get_current_user();
        
        return view('pages.auth.dashboard', compact('user'));
    }
}