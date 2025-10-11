<div class="max-w-xl mx-auto py-10">
  <h1 class="text-3xl font-bold mb-6">Liên hệ với chúng tôi</h1>

  @if ($success)
    <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
      {{ $success }}
    </div>
  @endif

  <div class="mb-6">
    <a wire:navigate href="{{ home_url('my-contacts') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
      </svg>
      Xem danh sách liên hệ của tôi
    </a>
  </div>

  <div class="mb-6">
    <a wire:navigate href="{{ route('contact.alpine') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
      </svg>
      Đi tới form liên hệ với Alpine validation
    </a>
  </div>

  {{-- Form liên hệ --}}
  <form wire:submit.prevent="submit" class="space-y-5">
    {{-- Họ tên --}}
    <div>
      <label for="name" class="block font-medium text-gray-700">Họ tên</label>
      <input type="text" id="name" wire:model.live.debounce.400ms="name"
             class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror">
      @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Email --}}
    <div>
      <label for="email" class="block font-medium text-gray-700">Email</label>
      <input type="email" id="email" wire:model.live.debounce.400ms="email"
             class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror">
      @error('email')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Nội dung --}}
    <div>
      <label for="message" class="block font-medium text-gray-700">Nội dung</label>
      <textarea id="message" rows="5" wire:model.live.debounce.400ms="message"
                class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('message') border-red-500 @enderror"></textarea>
      @error('message')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
      Gửi liên hệ
    </button>
  </form>
</div>
