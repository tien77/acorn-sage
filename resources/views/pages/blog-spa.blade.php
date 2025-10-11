@extends('layouts.app')

@section('content')
  <div class="prose mx-auto py-10">
    <h1>Blog (SPA)</h1>
    {{-- ví dụ component Livewire --}}
    <p class="mt-6">
      <a wire:navigate href="{{ route('hello') }}">← Về trang Hello</a>
    </p>
  </div>
@endsection
