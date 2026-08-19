<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class AboutUsController extends Controller
{
    public function aboutUs(Request $request)
    {
        $about_header = DB::table('abouts')
            ->select('banner_title','banner_sub_title','heading','sub_heading','description','banner_image','box1_title','box1_description','box2_title','box2_description','box3_title','box3_description','box4_title','box4_description','image','meta_title','meta_keywords','meta_description')
           ->first();

        if($about_header){ 
            $about_header->image = generateSignedUrl('about/'.$about_header->image);
            if($about_header->banner_image != "" || $about_header->banner_image != null){
                $about_header->banner_image = generateSignedUrl('about/'.$about_header->banner_image);
            }else{
                $about_header->banner_image = url('images').'/destination_banner_no_img.jpg';
            }
        }
        return view('frontend/about/about_us', compact('about_header'));
    }
}
