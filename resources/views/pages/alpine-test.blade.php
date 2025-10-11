@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 space-y-6" x-data="{ count: 0 }">
  <h1 class="text-2xl font-bold">Trang test Alpine</h1>

  <div class="flex items-center gap-3">
    <button class="px-4 py-2 bg-green-600 text-white rounded" @click="count++">+1</button>
    <span class="font-mono text-lg" x-text="count"></span>
  </div>

  <p class="text-gray-600">
    Giá trị này sẽ reset về 0 khi bạn quay lại <strong>Liên hệ</strong> nếu Alpine hoạt động đúng với SPA navigate.
  </p>

  <div class="pt-6">
    <a wire:navigate href="{{ route('contact.index') }}" class="text-blue-600 underline">
      Quay lại form liên hệ
    </a>
  </div>
</div>



<div class="bg-gray-100 font-sans flex h-screen items-center justify-center">
    <div x-data="{ openTab: 1 }" class="p-8">
        <div class="max-w-md mx-auto">
            <div class="mb-4 flex space-x-4 p-2 bg-white rounded-lg shadow-md">
                <button x-on:click="openTab = 1" :class="{ 'bg-blue-600 text-white': openTab === 1 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 1</button>
                <button x-on:click="openTab = 2" :class="{ 'bg-blue-600 text-white': openTab === 2 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 2</button>
                <button x-on:click="openTab = 3" :class="{ 'bg-blue-600 text-white': openTab === 3 }" class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">Section 3</button>
            </div>

            <div x-show="openTab === 1" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 1 Content</h2>
                <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam justo nec justo lacinia, vel ullamcorper nibh tincidunt.</p>
            </div>

            <div x-show="openTab === 2" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 2 Content</h2>
                <p class="text-gray-700">Proin non velit ac purus malesuada venenatis sit amet eget lacus. Morbi quis purus id ipsum ultrices aliquet Morbi quis.</p>
            </div>

            <div x-show="openTab === 3" class="transition-all duration-300 bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600">
                <h2 class="text-2xl font-semibold mb-2 text-blue-600">Section 3 Content</h2>
                <p class="text-gray-700">Fusce hendrerit urna vel tortor luctus, nec tristique odio tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
            </div>
        </div>
    </div>
</div>

@endsection
