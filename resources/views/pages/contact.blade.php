@extends('layouts.app')

@section('content')
  <div class="max-w-xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6">Liên hệ với chúng tôi</h1>

    {{-- Success message --}}
    @if (session('success'))
      <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
        {{ session('success') }}
      </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
      {{-- @csrf --}}

      {{-- Họ tên --}}
      <div>
        <label for="name" class="block font-medium text-gray-700">Họ tên</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}"
               class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror">
        @error('name')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label for="email" class="block font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}"
               class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror">
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Nội dung --}}
      <div>
        <label for="message" class="block font-medium text-gray-700">Nội dung</label>
        <textarea name="message" id="message" rows="5"
                  class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
        @error('message')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <button type="submit"
              class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
        Gửi liên hệ
      </button>
    </form>
  </div>
@endsection
