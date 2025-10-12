<?php

namespace App\Livewire;

use Livewire\Component;

class AuthStatus extends Component
{
    // Chỉ lưu trữ dữ liệu primitive, không lưu object WP_User
    public $isLoggedIn = false;
    public $userName = '';
    public $userEmail = '';
    public $userDisplayName = '';
    public $userAvatar = '';
    public $userId = 0;

    public function mount()
    {
        $this->checkAuthStatus();
    }

    public function checkAuthStatus()
    {
        $this->isLoggedIn = is_user_logged_in();
        
        if ($this->isLoggedIn) {
            $user = wp_get_current_user();
            
            // Lưu từng thuộc tính riêng lẻ thay vì object
            $this->userId = $user->ID;
            $this->userName = $user->user_login;
            $this->userEmail = $user->user_email;
            $this->userDisplayName = $user->display_name;
            $this->userAvatar = get_avatar_url($user->ID, 32);
        } else {
            // Reset tất cả về mặc định
            $this->userId = 0;
            $this->userName = '';
            $this->userEmail = '';
            $this->userDisplayName = '';
            $this->userAvatar = '';
        }
    }

    public function logout()
    {
        wp_logout();
        $this->checkAuthStatus();
        
        // Redirect về trang chủ
        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công!');
    }

    public function render()
    {
        return view('livewire.auth-status');
    }
}
