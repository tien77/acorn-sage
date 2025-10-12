@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="text-center">
      <h1 class="text-4xl md:text-6xl font-bold mb-6">
        Chào mừng đến với {{ get_bloginfo('name') }}
      </h1>
      <p class="text-xl md:text-2xl mb-8 text-blue-100">
        Hệ thống authentication với Sage + Laravel + Livewire
      </p>
      
      @if(!is_user_logged_in())
        <div class="space-x-4">
          <a 
            href="{{ route('login') }}" 
            wire:navigate
            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-blue-50 transition duration-150 ease-in-out"
          >
            Đăng nhập
          </a>
          
          @if(get_option('users_can_register'))
            <a 
              href="{{ wp_registration_url() }}" 
              class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-150 ease-in-out"
            >
              Đăng ký
            </a>
          @endif
        </div>
      @else
        <div class="space-x-4">
          <a 
            href="{{ route('dashboard') }}" 
            wire:navigate
            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-blue-50 transition duration-150 ease-in-out"
          >
            Vào Dashboard
          </a>
        </div>
      @endif
    </div>
  </div>
</div>

{{-- Features Section --}}
<div class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
        Tính năng nổi bật
      </h2>
      <p class="text-xl text-gray-600">
        Hệ thống được xây dựng với các công nghệ hiện đại
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      {{-- WordPress Authentication --}}
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">WordPress Auth</h3>
        <p class="text-gray-600">
          Tích hợp hoàn toàn với hệ thống user WordPress, sử dụng wp_signon, wp_logout
        </p>
      </div>

      {{-- Laravel Routes --}}
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Laravel Routes</h3>
        <p class="text-gray-600">
          Sử dụng Laravel routing với middleware, controller pattern chuẩn
        </p>
      </div>

      {{-- Livewire Components --}}
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-4">
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Livewire Reactive</h3>
        <p class="text-gray-600">
          Component reactive với Alpine.js, wire:navigate cho SPA experience
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Quick Links --}}
<div class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">
      Liên kết nhanh
    </h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <a 
        href="{{ route('gallery.index') }}" 
        wire:navigate
        class="block p-6 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-150 ease-in-out"
      >
        <h3 class="font-semibold text-purple-900 mb-2">Gallery</h3>
        <p class="text-sm text-purple-600">Bộ sưu tập hình ảnh đẹp</p>
      </a>

      <a 
        href="{{ route('contact.index') }}" 
        wire:navigate
        class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out"
      >
        <h3 class="font-semibold text-gray-900 mb-2">Contact Form</h3>
        <p class="text-sm text-gray-600">Form liên hệ thuần HTML</p>
      </a>
      
      <a 
        href="{{ route('contact.livewire') }}" 
        wire:navigate
        class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out"
      >
        <h3 class="font-semibold text-gray-900 mb-2">Livewire Form</h3>
        <p class="text-sm text-gray-600">Form Livewire reactive</p>
      </a>
      
      @auth
        <a 
          href="{{ route('contacts.mine') }}" 
          wire:navigate
          class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out"
        >
          <h3 class="font-semibold text-gray-900 mb-2">My Contacts</h3>
          <p class="text-sm text-gray-600">Danh sách liên hệ của tôi</p>
        </a>
        
        <a 
          href="{{ route('dashboard') }}" 
          wire:navigate
          class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out"
        >
          <h3 class="font-semibold text-gray-900 mb-2">Dashboard</h3>
          <p class="text-sm text-gray-600">Bảng điều khiển cá nhân</p>
        </a>
      @else
        <a 
          href="{{ route('login') }}" 
          wire:navigate
          class="block p-6 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-150 ease-in-out"
        >
          <h3 class="font-semibold text-blue-900 mb-2">Đăng nhập</h3>
          <p class="text-sm text-blue-600">Đăng nhập để xem thêm</p>
        </a>
        
        <a 
          href="{{ get_admin_url() }}" 
          class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out"
        >
          <h3 class="font-semibold text-gray-900 mb-2">WP Admin</h3>
          <p class="text-sm text-gray-600">WordPress Dashboard</p>
        </a>
      @endauth
    </div>
  </div>
</div>

@endsection
