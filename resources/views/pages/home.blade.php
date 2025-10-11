@extends('layouts.app')

@section('content')
	<div class="prose mx-auto py-10">
	<h1>Chào mừng bạn đến với trang chủ!</h1>
	<p>Đây là ví dụ trang chủ sử dụng Sage + Laravel + Livewire.</p>

	<p class="mt-6">
	  <a wire:navigate href="{{ route('hello') }}">→ Đi đến trang Hello</a>
	</p>
	<p class="mt-2">
	  <a wire:navigate href="{{ home_url('my-contacts') }}">→ Xem liên hệ của tôi</a>
	</p>
	<p class="mt-2">
	  <a wire:navigate href="{{ home_url('contact-livewire') }}">→ Gửi liên hệ mới</a>
	</p>
	<p class="mt-2">
	  <a wire:navigate href="{{ home_url('blog/spa') }}">→ Xem blog (SPA)</a>
	</p>
  </div>
@endsection
