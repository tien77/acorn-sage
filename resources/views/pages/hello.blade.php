@extends('layouts.app')

@section('content')
  <div class="prose mx-auto py-10">
    <h1>{{ $message }}</h1>
    <p>Middleware <code>LogRequestMiddleware</code> đã ghi log URL của bạn.</p>

    <p><a wire:navigate href="{{ url('/') }}">Về trang chủ</a></p>
  </div>
@endsection
