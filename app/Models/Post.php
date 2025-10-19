<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'ID';
    public $timestamps = false; // WordPress sử dụng post_date thay vì created_at
    
    protected $fillable = [
        'post_title',
        'post_content',
        'post_excerpt',
        'post_status',
        'post_type',
        'post_name',
        'post_date',
        'post_author'
    ];

    protected $casts = [
        'post_date' => 'datetime',
        'post_modified' => 'datetime',
    ];

    /**
     * Scope để chỉ lấy các post đã publish
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('post_status', 'publish');
    }

    /**
     * Scope để chỉ lấy post type là 'post'
     */
    public function scopePost(Builder $query): Builder
    {
        return $query->where('post_type', 'post');
    }

    /**
     * Lấy URL của post
     */
    public function getUrlAttribute(): string
    {
        return function_exists('\get_permalink') ? \get_permalink($this->ID) : '';
    }

    /**
     * Lấy featured image
     */
    public function getFeaturedImageAttribute(): ?string
    {
        if (function_exists('\get_the_post_thumbnail_url')) {
            return \get_the_post_thumbnail_url($this->ID, 'large') ?: null;
        }
        return null;
    }

    /**
     * Lấy excerpt hoặc tự động tạo từ content
     */
    public function getExcerptAttribute(): string
    {
        if (!empty($this->post_excerpt)) {
            return $this->post_excerpt;
        }
        
        // Nếu không có excerpt, tạo từ content
        $content = function_exists('\wp_strip_all_tags') ? \wp_strip_all_tags($this->post_content) : strip_tags($this->post_content);
        if (function_exists('\wp_trim_words')) {
            return \wp_trim_words($content, 30, '...');
        }
        return substr($content, 0, 200) . '...';
    }

    /**
     * Lấy tác giả của post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'post_author', 'ID');
    }

    /**
     * Lấy số lượng comment
     */
    // public function getCommentCountAttribute(): int
    // {
    //     return (int) $this->comment_count;
    // }

    /**
     * Lấy thời gian đăng post dạng human readable
     */
    public function getTimeAgoAttribute(): string
    {
        if (function_exists('\human_time_diff') && function_exists('\current_time')) {
            return \human_time_diff(strtotime($this->post_date), \current_time('timestamp')) . ' ago';
        }
        return $this->post_date->diffForHumans();
    }

    /**
     * Lấy categories của post
     */
    public function getCategories()
    {
        return function_exists('\get_the_category') ? \get_the_category($this->ID) : [];
    }

    /**
     * Lấy tags của post
     */
    public function getTags()
    {
        return function_exists('\get_the_tags') ? \get_the_tags($this->ID) : [];
    }

    /**
     * Lấy content đã được format
     */
    public function getFormattedContentAttribute(): string
    {
        return function_exists('\apply_filters') ? \apply_filters('the_content', $this->post_content) : $this->post_content;
    }
}