<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function blog(Request $request)
    {
        $current_page = $request->get('page', 1);
        $keyword = $request->get('post_keyword', null);
        if ($keyword) {
            $keyword = rtrim($keyword, '/');
            $postData = [
                'keyword' => $keyword,
            ];
            $cacheKey = 'blog_search_' . md5($keyword . $current_page);
            $posts = Cache::remember($cacheKey, now()->addMinutes(10), function() use ($postData) {
                return Http::post('https://blog.dookinternational.com/api/blog-search', $postData)->json();
            });          
        } else { 
            $post_urls = "https://blog.dookinternational.com/api/all-posts?page=" . $current_page;

            $cacheKey = 'all_blog_posts_' . $current_page;
            $posts = Cache::remember($cacheKey, now()->addMinutes(10), function() use ($post_urls) {
                return Http::get($post_urls)->json();
            });
        } 
        if (is_null($posts) || empty($posts)) {
            return redirect('/404'); 
        }
        $recentPost = $posts['recentPost'] ?? [];
        $categories = $posts['categories'] ?? [];
        $total = $keyword ? 0 : ($posts['total'] ?? 0);

        return view('frontend.blog.index', compact('posts', 'total', 'recentPost', 'categories'));   
    }

    public function blogdetail(Request $request, $slug)
    {
        $cacheKey = 'blog_details_' . $slug;
        $posts = Cache::remember($cacheKey, 60, function() use ($slug) {
            $response = Http::post('https://blog.dookinternational.com/api/post_details', [
                'slug' => $slug
            ]);
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        });
        if (is_null($posts) || empty($posts)) {
            return redirect('/404');
        }
        $post_detail = $posts['blog_details'] ?? null;
        $categories = $posts['categories'] ?? [];
        $recentPost = $posts['recentPost'] ?? [];
        $post_bannerimg = url('images') . '/Blog-Banner-Images.jpg';

        return view('frontend.blog.blog_detail', compact('post_detail', 'categories', 'recentPost', 'post_bannerimg'));
    }
}
