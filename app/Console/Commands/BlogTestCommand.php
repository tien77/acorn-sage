<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class BlogTestCommand extends Command
{
    protected $signature = 'blog:test';
    protected $description = 'Test blog functionality';

    public function handle()
    {
        $this->info('Testing Blog System...');
        
        try {
            // Test get all published posts
            $posts = Post::published()->post()->take(5)->get();
            
            $this->info("Found {$posts->count()} published posts:");
            
            foreach ($posts as $post) {
                $this->line("- {$post->post_title} (ID: {$post->ID})");
                $this->line("  URL: {$post->url}");
                $this->line("  Date: {$post->post_date}");
                $this->line("  Excerpt: " . substr($post->excerpt, 0, 100) . "...");
                $this->line("");
            }
            
            if ($posts->count() > 0) {
                $firstPost = $posts->first();
                $this->info("Testing single post details for: {$firstPost->post_title}");
                $this->line("Categories: " . count($firstPost->getCategories()));
                $this->line("Tags: " . count($firstPost->getTags()));
                $this->line("Comment Count: {$firstPost->comment_count}");
                $this->line("Time Ago: {$firstPost->time_ago}");
            }
            
            $this->info('Blog system test completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error testing blog system: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}