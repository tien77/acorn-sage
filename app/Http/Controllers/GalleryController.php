<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Hiển thị trang gallery
     */
    public function index()
    {
        // Danh sách hình ảnh demo từ Flowbite
        $images = [
            [
                'id' => 1,
                'title' => 'Gallery Image 1',
                'description' => 'Beautiful landscape photography',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image.jpg',
                'category' => 'landscape'
            ],
            [
                'id' => 2,
                'title' => 'Gallery Image 2',
                'description' => 'Stunning nature view',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-1.jpg',
                'category' => 'nature'
            ],
            [
                'id' => 3,
                'title' => 'Gallery Image 3',
                'description' => 'Urban architecture',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg',
                'category' => 'architecture'
            ],
            [
                'id' => 4,
                'title' => 'Gallery Image 4',
                'description' => 'City skyline at night',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-3.jpg',
                'category' => 'city'
            ],
            [
                'id' => 5,
                'title' => 'Gallery Image 5',
                'description' => 'Mountain peaks',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-4.jpg',
                'category' => 'mountain'
            ],
            [
                'id' => 6,
                'title' => 'Gallery Image 6',
                'description' => 'Ocean waves',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-5.jpg',
                'category' => 'ocean'
            ],
            [
                'id' => 7,
                'title' => 'Gallery Image 7',
                'description' => 'Forest path',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-6.jpg',
                'category' => 'forest'
            ],
            [
                'id' => 8,
                'title' => 'Gallery Image 8',
                'description' => 'Desert landscape',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-7.jpg',
                'category' => 'desert'
            ],
            [
                'id' => 9,
                'title' => 'Gallery Image 9',
                'description' => 'River valley',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-8.jpg',
                'category' => 'river'
            ],
            [
                'id' => 10,
                'title' => 'Gallery Image 10',
                'description' => 'Snow-covered peaks',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-9.jpg',
                'category' => 'snow'
            ],
            [
                'id' => 11,
                'title' => 'Gallery Image 11',
                'description' => 'Tropical beach',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-10.jpg',
                'category' => 'beach'
            ],
            [
                'id' => 12,
                'title' => 'Gallery Image 12',
                'description' => 'Sunset horizon',
                'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-11.jpg',
                'category' => 'sunset'
            ]
        ];

        // Lấy các category unique
        $categories = collect($images)->pluck('category')->unique()->sort();

        return view('pages.gallery', compact('images', 'categories'));
    }

    /**
     * Hiển thị chi tiết một hình ảnh
     */
    public function show($id)
    {
        // Tìm hình ảnh theo ID (demo data)
        $images = $this->getImages();
        $image = collect($images)->firstWhere('id', (int) $id);

        if (!$image) {
            abort(404, 'Image not found');
        }

        return view('pages.gallery-detail', compact('image'));
    }

    /**
     * API endpoint để lấy images theo category
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');
        $images = $this->getImages();

        if ($category && $category !== 'all') {
            $images = collect($images)->where('category', $category)->values();
        }

        return response()->json($images);
    }

    /**
     * Helper method để lấy danh sách images
     */
    private function getImages()
    {
        return [
            ['id' => 1, 'title' => 'Gallery Image 1', 'description' => 'Beautiful landscape photography', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image.jpg', 'category' => 'landscape'],
            ['id' => 2, 'title' => 'Gallery Image 2', 'description' => 'Stunning nature view', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-1.jpg', 'category' => 'nature'],
            ['id' => 3, 'title' => 'Gallery Image 3', 'description' => 'Urban architecture', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg', 'category' => 'architecture'],
            ['id' => 4, 'title' => 'Gallery Image 4', 'description' => 'City skyline at night', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-3.jpg', 'category' => 'city'],
            ['id' => 5, 'title' => 'Gallery Image 5', 'description' => 'Mountain peaks', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-4.jpg', 'category' => 'mountain'],
            ['id' => 6, 'title' => 'Gallery Image 6', 'description' => 'Ocean waves', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-5.jpg', 'category' => 'ocean'],
            ['id' => 7, 'title' => 'Gallery Image 7', 'description' => 'Forest path', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-6.jpg', 'category' => 'forest'],
            ['id' => 8, 'title' => 'Gallery Image 8', 'description' => 'Desert landscape', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-7.jpg', 'category' => 'desert'],
            ['id' => 9, 'title' => 'Gallery Image 9', 'description' => 'River valley', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-8.jpg', 'category' => 'river'],
            ['id' => 10, 'title' => 'Gallery Image 10', 'description' => 'Snow-covered peaks', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-9.jpg', 'category' => 'snow'],
            ['id' => 11, 'title' => 'Gallery Image 11', 'description' => 'Tropical beach', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-10.jpg', 'category' => 'beach'],
            ['id' => 12, 'title' => 'Gallery Image 12', 'description' => 'Sunset horizon', 'url' => 'https://flowbite.s3.amazonaws.com/docs/gallery/square/image-11.jpg', 'category' => 'sunset']
        ];
    }
}