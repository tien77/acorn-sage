@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-5 sm:p-10 md:p-16">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" wire:navigate class="hover:text-indigo-600">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('blog.index') }}" wire:navigate class="hover:text-indigo-600">Blog</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800">{{ $post->post_title }}</li>
        </ol>
    </nav>

    <!-- Main Article -->
    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Featured Image -->
        @if($post->featured_image)
            <div class="w-full h-64 md:h-96 overflow-hidden">
                <img class="w-full h-full object-cover" 
                     src="{{ $post->featured_image }}" 
                     alt="{{ $post->post_title }}">
            </div>
        @endif

        <div class="p-6 md:p-8">
            <!-- Categories -->
            @php $categories = $post->getCategories(); @endphp
            @if($categories && count($categories) > 0)
                <div class="mb-4">
                    @foreach($categories as $category)
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full mr-2">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ $post->post_title }}
            </h1>

            <!-- Meta Information -->
            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-6 border-b border-gray-200 pb-4">
                <!-- Author -->
                @if($post->author)
                    <div class="flex items-center mr-6 mb-2">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $post->author->display_name ?? 'Unknown Author' }}</span>
                    </div>
                @endif

                <!-- Date -->
                <div class="flex items-center mr-6 mb-2">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $post->post_date->format('F d, Y') }}</span>
                </div>

                <!-- Comments Count -->
                <div class="flex items-center mr-6 mb-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <span>{{ $post->comment_count }} Comments</span>
                </div>

                <!-- Reading Time (estimated) -->
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    @php
                        $wordCount = str_word_count(strip_tags($post->post_content));
                        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
                    @endphp
                    <span>{{ $readingTime }} min read</span>
                </div>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                {!! $post->formatted_content !!}
            </div>

            <!-- Tags -->
            @php $tags = $post->getTags(); @endphp
            @if($tags && count($tags) > 0)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Tags:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <span class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-md">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Share Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Share this article:</h4>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" rel="noopener"
                       class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </a>
                    
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->post_title) }}" 
                       target="_blank" rel="noopener"
                       class="flex items-center px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </a>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $relatedPost)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <a href="{{ route('blog.show', $relatedPost->post_name) }}" wire:navigate>
                            @if($relatedPost->featured_image)
                                <img class="w-full h-40 object-cover" 
                                     src="{{ $relatedPost->featured_image }}" 
                                     alt="{{ $relatedPost->post_title }}">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('blog.show', $relatedPost->post_name) }}" wire:navigate
                                   class="hover:text-indigo-600 transition">
                                    {{ $relatedPost->post_title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ $relatedPost->excerpt }}
                            </p>
                            <div class="text-xs text-gray-500">
                                {{ $relatedPost->time_ago }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Back to Blog -->
    <div class="mt-12 text-center">
        <a href="{{ route('blog.index') }}" wire:navigate
           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Blog
        </a>
    </div>
</div>

@endsection