{{-- tạo phân trang tùy chỉnh với Tailwind CSS --}}

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="mt-6">
        <ul class="inline-flex items-center gap-1">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="Previous">
                    <span class="px-3 py-2 text-gray-400 border rounded cursor-not-allowed">Prev</span>
                </li>
            @else
                <li>
                    <a wire:navigate href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous"
                       class="px-3 py-2 border rounded hover:bg-gray-50">Prev</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true"><span class="px-3 py-2 text-gray-500">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="px-3 py-2 border rounded bg-gray-900 text-white">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a wire:navigate href="{{ $url }}" aria-label="Go to page {{ $page }}"
                                   class="px-3 py-2 border rounded hover:bg-gray-50">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a wire:navigate href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next"
                       class="px-3 py-2 border rounded hover:bg-gray-50">Next</a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Next">
                    <span class="px-3 py-2 text-gray-400 border rounded cursor-not-allowed">Next</span>
                </li>
            @endif

        </ul>
    </nav>
@endif
