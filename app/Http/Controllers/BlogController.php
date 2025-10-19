<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class BlogController
{
    /**
     * Hiển thị danh sách blog posts
     */
    public function index(Request $request): View
    {
        $query = Post::published()->post()->with('author');
        
        // Lọc theo category nếu có
        if ($request->has('category') && $request->category) {
            // WordPress category query - sẽ implement sau nếu cần
        }
        
        // Lọc theo search nếu có
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('post_title', 'LIKE', "%{$search}%")
                  ->orWhere('post_content', 'LIKE', "%{$search}%");
            });
        }
        
        // Sắp xếp theo ngày mới nhất
        $query->orderBy('post_date', 'desc');
        
        // Phân trang
        $posts = $query->paginate(9); // 9 posts per page để match với grid 3x3
        
        return view('pages.blog', compact('posts'));
    }
    
    /**
     * Hiển thị chi tiết một blog post
     */
    public function show(Request $request, string $slug): View
    {
        $post = Post::published()
            ->post()
            ->with('author')
            ->where('post_name', $slug)
            ->firstOrFail();
            
        // Lấy related posts (cùng category hoặc cùng tag)
        $relatedPosts = $this->getRelatedPosts($post, 3);
        
        // Tăng view count nếu cần
        $this->incrementViewCount($post);
        
        return view('pages.single-post', compact('post', 'relatedPosts'));
    }
    
    /**
     * Lấy các bài viết liên quan
     */
    private function getRelatedPosts(Post $post, int $limit = 3)
    {
        // Lấy posts khác cùng category hoặc tag
        return Post::published()
            ->post()
            ->where('ID', '!=', $post->ID)
            ->orderBy('post_date', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Tăng view count cho post (tùy chọn)
     */
    private function incrementViewCount(Post $post): void
    {
        // Có thể implement view count tracking ở đây
        // Ví dụ: lưu vào meta table hoặc custom field
        if (function_exists('update_post_meta')) {
            $views = (int) \get_post_meta($post->ID, 'post_views', true);
            \update_post_meta($post->ID, 'post_views', $views + 1);
        }
    }
    
    /**
     * API endpoint để lấy posts (cho AJAX hoặc frontend)
     */
    public function apiIndex(Request $request)
    {
        $query = Post::published()->post()->with('author');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('post_title', 'LIKE', "%{$search}%")
                  ->orWhere('post_content', 'LIKE', "%{$search}%");
            });
        }
        
        $posts = $query->orderBy('post_date', 'desc')
                      ->paginate($request->get('per_page', 10));
        
        return response()->json([
            'data' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ]
        ]);
    }
    
    /**
     * Lấy categories cho filter
     */
    public function getCategories()
    {
        // WordPress categories
        if (function_exists('get_categories')) {
            return \get_categories([
                'hide_empty' => true,
                'number' => 20
            ]);
        }
        
        return [];
    }
}