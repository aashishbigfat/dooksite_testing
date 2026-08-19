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
    $item_per_page = 21;
    $item_first_page = 21;
    
    // Sanitize and clean page parameter
    $current_page = (int) rtrim($request->page, '/'); // Remove any trailing slashes
    
    // Ensure $current_page is always at least 1
    if ($current_page < 1) {
        $current_page = 1;
    }

    // Determine offset based on the page number
    if ($current_page == 1) {
        $offset = 0; // For the first page, offset is 0
    } else {
        $offset = ($current_page - 1) * $item_per_page;
    }

    // Handle search functionality if keyword is provided
    if (!is_null($request->post_keyword)) {
        $keyword = rtrim($request->post_keyword, '/'); // Clean keyword input
        $post = [
            'keyword' => $keyword,
        ];
        $data_string = json_encode($post);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://blog.dookinternational.com/api/blog-search');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
    } else {
        $post_urls = "https://blog.dookinternational.com/api/all-posts?page=" . $current_page;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_urls);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
    }

    $response = curl_exec($ch);
    if ($response === false) {
        // Handle CURL error
        return response()->json(['error' => 'Failed to fetch blog posts'], 500);
    }

    // Decode the response
    $posts = json_decode($response);

    // Check if the response is valid
    if ($posts === null) {
        // Handle error in JSON decoding
        return response()->json(['error' => 'Invalid response from the API'], 500);
    }

    $recentPost = $posts->recentPost ?? [];
    $categories = $posts->categories ?? [];
    $total = $posts->total ?? 0;

    if ($current_page > 1 || !is_null($request->post_keyword)) {
        // Paginated data for more than the first page or when keyword search is applied
        $postData = $posts->posts ?? [];
        $postCount = $posts->postCount ?? 0;
        $view = view('frontend.common.blog', compact('postData'))->render();
        
        return response()->json([
            'view' => $view,
            'postCount' => $postCount
        ]);
    } else {
        // Initial load (first page or no keyword search)
        return view('frontend.blog.index', compact('posts', 'total', 'item_per_page', 'recentPost', 'categories'));
    }
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
            return redirect('/');
        }
        $post_detail = $posts['blog_details'] ?? null;
        $categories = $posts['categories'] ?? [];
        $recentPost = $posts['recentPost'] ?? [];
        $post_bannerimg = url('images') . '/Blog-Banner-Images.jpg';

        return view('frontend.blog.blog_detail', compact('post_detail', 'categories', 'recentPost', 'post_bannerimg'));
    }
     public function postcategoryWise(Request $request, $cat_url)
    {
        $post = array(
            'slug' => $cat_url
        );
        $data_string = json_encode($post);                                                                                
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://blog.dookinternational.com/api/category/posts');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                 
            'Content-Type: application/json',                   
            'Content-Length: ' . strlen($data_string))
        ); 
        $posts = curl_exec($ch);
        $posts = json_decode($posts);
        if($posts == null || $posts == ""){
            return redirect('/');
        }
        $recentPost = $posts->recentPost;
        $categories = $posts->categories;
        $currentCategory = collect($categories)->firstWhere('slug', $cat_url);
        $currentHeading = $currentCategory->heading ?? null;
        // if(!is_null($request->post_keyword)){
        //     $total = 0;
        // }else{
        //     $total = $posts->total;
        // }
        $total = $posts->total;
        $item_per_page = 0;
        if(count($posts->posts)>0){
            return view('frontend.blog.category',compact('posts','recentPost','categories','total','item_per_page','currentCategory','currentHeading'));
        }else{
            return redirect('/');
        }
    }

}
