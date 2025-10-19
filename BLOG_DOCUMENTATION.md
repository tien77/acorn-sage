# Blog System Documentation

## Tổng quan

Hệ thống blog được xây dựng trên WordPress theme Sage với Laravel Acorn, sử dụng Eloquent ORM để tương tác với database WordPress.

## Cấu trúc Files

### 1. Model
- **File**: `app/Models/Post.php`
- **Chức năng**: Eloquent model để quản lý posts WordPress
- **Features**:
  - Scope `published()` - chỉ lấy posts đã publish
  - Scope `post()` - chỉ lấy post type là 'post'
  - Accessor `url` - lấy permalink
  - Accessor `featured_image` - lấy featured image
  - Accessor `excerpt` - lấy excerpt hoặc tự tạo từ content
  - Accessor `time_ago` - hiển thị thời gian human-readable
  - Methods `getCategories()`, `getTags()` - lấy categories và tags

### 2. Controller
- **File**: `app/Http/Controllers/BlogController.php`
- **Methods**:
  - `index()` - Hiển thị danh sách blog posts với pagination
  - `show($slug)` - Hiển thị chi tiết một post
  - `apiIndex()` - API endpoint cho AJAX requests
  - `getCategories()` - Lấy danh sách categories

### 3. Routes
- **File**: `routes/web.php`
- **Endpoints**:
  - `GET /blog` - Danh sách blog posts
  - `GET /blog/{slug}` - Chi tiết một post
  - `GET /api/blog` - API endpoint cho posts
  - `GET /api/blog/categories` - API endpoint cho categories

### 4. Views
- **File**: `resources/views/pages/blog.blade.php`
  - Hiển thị grid layout 3 cột với pagination
  - Hỗ trợ `wire:navigate` cho SPA experience
  - Responsive design với TailwindCSS

- **File**: `resources/views/pages/single-post.blade.php`
  - Layout chi tiết cho single post
  - Breadcrumb navigation
  - Meta information (author, date, comments, reading time)
  - Share buttons (Facebook, Twitter)
  - Related posts section
  - Tags display

## Cách sử dụng

### 1. Truy cập Blog
```
http://your-site.com/blog
```

### 2. Chi tiết Post
```
http://your-site.com/blog/post-slug
```

### 3. Trong Code
```php
// Lấy tất cả posts published
$posts = Post::published()->post()->paginate(9);

// Lấy một post theo slug
$post = Post::published()->post()->where('post_name', $slug)->firstOrFail();

// Lấy posts theo category (cần implement thêm)
$posts = Post::published()->post()->whereHas('categories', function($q) {
    $q->where('slug', 'category-slug');
})->get();
```

## Features

### 1. Pagination
- Tự động phân trang với Laravel pagination
- Compatible với WordPress theme

### 2. SPA Navigation
- Sử dụng `wire:navigate` cho trải nghiệm SPA
- Không reload trang khi điều hướng

### 3. SEO Friendly
- URL structure: `/blog/post-slug`
- Meta tags support
- Breadcrumb navigation

### 4. Responsive Design
- Mobile-first design với TailwindCSS
- Grid layout tự động điều chỉnh theo screen size

### 5. WordPress Integration
- Sử dụng WordPress database structure
- Tương thích với WordPress functions
- Support categories, tags, comments, featured images

## Testing

### 1. Chạy test command
```bash
wp acorn blog:test
```

### 2. Check routes
```bash
wp acorn route:list
```

## Customization

### 1. Thay đổi số posts per page
Trong `BlogController::index()`:
```php
$posts = $query->paginate(9); // Thay 9 thành số mong muốn
```

### 2. Thêm custom fields
Trong `Post` model:
```php
public function getCustomFieldAttribute()
{
    return get_post_meta($this->ID, 'custom_field_key', true);
}
```

### 3. Thêm search functionality
Đã có sẵn trong controller, chỉ cần thêm search form trong view:
```html
<form method="GET">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts...">
    <button type="submit">Search</button>
</form>
```

### 4. Custom pagination view
Tạo file `resources/views/vendor/pagination/custom.blade.php` và sử dụng:
```php
{{ $posts->links('vendor.pagination.custom') }}
```

## Performance Tips

1. **Eager Loading**: Luôn load relationship khi cần:
```php
$posts = Post::published()->post()->with(['author'])->paginate(9);
```

2. **Caching**: Implement cache cho heavy queries:
```php
$posts = Cache::remember('blog_posts', 3600, function() {
    return Post::published()->post()->paginate(9);
});
```

3. **Image Optimization**: Sử dụng WordPress image sizes:
```php
get_the_post_thumbnail_url($post->ID, 'medium');
```

## Troubleshooting

### 1. Database Connection Issues
- Kiểm tra `config/database.php`
- Đảm bảo WordPress constants được load
- Check `.env` file

### 2. WordPress Functions Not Available
- WordPress functions chỉ available khi theme được load
- Sử dụng `function_exists()` để check
- Alternative methods đã được implement trong model

### 3. Pagination Issues
- Đảm bảo pagination view được publish
- Check routes conflicts với WordPress

## Next Steps

1. **Comment System**: Implement comment display và form
2. **Search**: Enhance search với advanced filters
3. **Categories/Tags Pages**: Tạo pages riêng cho categories và tags
4. **RSS Feed**: Generate RSS feed cho blog
5. **Related Posts**: Improve algorithm cho related posts
6. **SEO**: Thêm meta tags, structured data
7. **Social Sharing**: Enhance social sharing options