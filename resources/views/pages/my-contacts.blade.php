@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-bold">Liên hệ của tôi</h1>

	<a wire:navigate href="{{ home_url('contact-livewire') }}" class="text-blue-600 hover:text-blue-800">Liên hệ</a>.
	{{-- Danh sách liên hệ --}}
	<hr>

    <div class="space-y-3">
      @forelse ($contacts as $c)
        <div class="border rounded p-4">
          <div class="text-sm text-gray-500">
            #{{ $c->id }} • {{ $c->created_at?->format('Y-m-d H:i') }}
          </div>
          <div class="font-semibold">{{ $c->name }} &lt;{{ $c->email }}&gt;</div>
          <div class="mt-2 whitespace-pre-line">{{ $c->message }}</div>
        </div>
      @empty
        <p>Chưa có liên hệ nào.</p>
      @endforelse
    </div>

	<div class="mt-6">
	{{ $contacts->links('vendor.pagination.wp-tailwind') }}
	</div>

  </div>
@endsection
