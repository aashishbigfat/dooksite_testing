<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingDeparturePage;
use App\Models\ContactAddresse;
use App\Models\Contact;
use App\Models\Destination;
use App\Models\DookReview;
use App\Models\DookJob;
use App\Models\DookPresentation;
use App\Models\DookEnquiry;
use App\Models\Country;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Log;
class CommonController extends Controller
{
     public function faqs()
    {
        $faqs = LandingDeparturePage::where('type', 'landing_faqs')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description','description')
           ->first();
        if($faqs){ 
            if($faqs->banner_image != "" || $faqs->banner_image != null){
                $faqs->banner_image = generateSignedUrl('landing/'.$faqs->banner_image);
            }else{
                $faqs->banner_image = url('images').'/destination_banner_no_img.jpg';
            }
        }
        return view('frontend.footercommon.faqs',compact('faqs'));
    }
    public function thankYou()
    {
        // dd('hellooo');
        return view('/thankyou');
    }
    

    public function privacyPolicy(Request $request)
    {
        $privacy_header = LandingDeparturePage::where('type', 'landing_privacy')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description','description')
           ->first();
        if($privacy_header){ 
            if($privacy_header->banner_image != "" || $privacy_header->banner_image != null){
                $privacy_header->banner_image = generateSignedUrl('landing/'.$privacy_header->banner_image);
            }else{
                $privacy_header->banner_image = url('images').'/destination_banner_no_img.jpg';
            }
        }
        return view('frontend.footercommon.privacypolicy', compact('privacy_header'));
    }

    public function termsConditions(Request $request)
    {
        $terms_header = LandingDeparturePage::where('type', 'landing_terms_conditions')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description','description')
           ->first();
        if($terms_header){ 
            if($terms_header->banner_image != "" || $terms_header->banner_image != null){
                $terms_header->banner_image = generateSignedUrl('landing/'.$terms_header->banner_image);
            }else{
                $terms_header->banner_image = url('images').'/destination_banner_no_img.jpg';
            }
        }
        return view('frontend.footercommon.terms_conditions', compact('terms_header'));
    }

    public function presentations()
    {
        $presentations = DookPresentation::where('active_status',1)
            ->select('id', 'title', 'file')
            ->orderBy('id', 'DESC')
            ->get();
        $presentation_header = LandingDeparturePage::where('type', 'landing_presentation')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description')
           ->first();
        return view('frontend.footercommon.presentation', compact('presentations','presentation_header'));
    }

    public function reviews(Request $request)
    {
        $reviews = DookReview::where('active_status',1)
                ->select('id', 'name', 'rating', 'description')
                ->orderBy('id', 'DESC')
                ->paginate(10);
        foreach ($reviews as $key => $value) {
            $value->rating = ceil($value->rating);
        }
        $reviewTotal = DookReview::where('active_status',1)
                ->get();

        $review_header = LandingDeparturePage::where('type', 'landing_review')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description')
           ->first(); 
            if ($request->ajax()) {
            return response()->json([
                'reviews' => view('frontend.common.review_card', compact('reviews'))->render(),
                'hasMoreDepartures' => $reviews->hasMorePages(),
            ]);
        }         
        return view('frontend.footercommon.review', compact('reviews','review_header'));
    }

    public function reviewsStore(Request $request)
    {
        $review = new DookReview;
        $review->name = $request->name;
        $review->email = $request->email;
        $review->mobile = $request->mobile;
        $review->rating = $request->rating;
        $review->active_status = 0;
        $review->description = $request->description;
        $review->save();
        if($review->id){
            $data = array(
                'error' => false,
                'message' =>["Bingo! Review sent successfully."]
            );
        }
        return response()->json($data, 200);
    }


    public function dossier19_20M(){
        $meta_title = "Dook Dossier 2020: Our Discoveries, Focus Destination & Experience";
        $meta_keywords = "Dook Dossier, CIS Countries Dossier, Evolving Dook, CIS Tour 2020, CIS Tourism, CIS Tour Dossier, CIS Travel Dossier, Agent Connect, CIS Magazine, CIS Travel Book, Combo Tours, Special Interest Groups, Embassies Interviews";
        $meta_description = "Dook Dossier 2019-20: Know about Evolving Dook, Our Discoveries & Chicks, Focus Destination & Experience, Agent Connect, Combo Tours, Embassies Interviews.";
        return view('dossier.dossier-2019-2020',compact('meta_title','meta_keywords','meta_description'));
    }

    public function dossier18_19M(){
        $meta_title = "Dook Dossier 2019: Our Discoveries, Focus Destination & Experience";
        $meta_keywords = "Dook Dossier, CIS Countries Dossier, Evolving Dook, CIS Tour 2019, CIS Tourism, CIS Tour Dossier, CIS Travel Dossier, Agent Connect, CIS Magazine, CIS Travel Book, Combo Tours, Special Interest Groups, Embassies Interviews";
        $meta_description = "Dook Dossier 2018-19: Know about Evolving Dook, Our Discoveries & Chicks, Focus Destination & Experience, Agent Connect, Combo Tours, Embassies Interviews.";
        return view('dossier.dossier-2018-2019',compact('meta_title','meta_keywords','meta_description'));
    }

    public function dossier17_18M(){
        $meta_title = "Dook Dossier 2018: Our Discoveries, Focus Destination & Experience";
        $meta_keywords = "Dook Dossier, CIS Countries Dossier, Evolving Dook, CIS Tour 2018, CIS Tourism, CIS Tour Dossier, CIS Travel Dossier, Agent Connect, CIS Magazine, CIS Travel Book, Combo Tours, Special Interest Groups, Embassies Interviews";
        $meta_description = "Dook Dossier 2017-18: Know about Evolving Dook, Our Discoveries & Chicks, Focus Destination & Experience, Agent Connect, Combo Tours, Embassies Interviews.";
        return view('dossier.dossier-2017-2018',compact('meta_title','meta_keywords','meta_description'));
    }
     public function contactUs(Request $request)
    {
        $contact_header = Contact::select('header_title','header_subtitle','title','sub_title','email','whatsapp','phone','facebook','twitter','instagram','pinterest','banner_image','youtube','meta_title','meta_keywords','meta_description')
           ->first();

        if($contact_header){ 
            $phone = explode('-',$contact_header->phone);
            $phone = implode('', $phone);
            $contact_header->phoneNo = $phone;
            if($contact_header->banner_image != "" || $contact_header->banner_image != null){
                $contact_header->banner_image = env('Image_Urls').'/landing/'.$contact_header->banner_image;
            }else{
                $contact_header->banner_image = url('images').'/destination_banner_no_img.jpg';
            }
        }
        $address = ContactAddresse::get();
        $destinationsC = Destination::orderBy('dest_name','ASC')
            ->select('dest_name')
            ->get()->toArray();
        $countriesC = Country::orderBy('country_name','ASC')
                ->select('country_name as dest_name')
                ->get()->toArray();
        $destCountriesC = array_merge($destinationsC,$countriesC);
        Shuffle($destCountriesC);

        $locationData = [];
        return view('frontend.contactus.contact_us', compact('contact_header','address','destCountriesC','locationData'));
    }
    public function careerListing(Request $request)
    {
        $careers = DookJob::distinct()
            ->select('id','title','location','role','slug_url','position','exp')
            ->where(['status'=> 1, 'type'=> 'Dook'])
            ->orderBy('id', 'DESC')
            ->get();
        $career_header = LandingDeparturePage::where('type', 'landing_career')
            ->select('title','sub_title','banner_image','meta_title','meta_keywords','meta_description')
           ->first();

        return view('frontend.footercommon.careers', compact('careers','career_header'));
    }
    public function careersDetail(Request $request, $slug_url)
    {
        $career_detail = DookJob::where('slug_url', $slug_url)
            ->select('title','location','role','slug_url','position','exp','meta_title','meta_keywords','meta_description','description','type as banner_image','status')
            ->where('status', 1)
            ->first();
        
        if($career_detail){
            $career_img = LandingDeparturePage::where('type', 'landing_career')
                ->select('banner_image')
               ->first();
            $career_detail->banner_image = $career_img->banner_image;
            $locationData = [];
            return view('frontend.footercommon.career_detail', compact('career_detail','locationData'));
        }else{
            return redirect('/404');
        }
    }

     public function jobEnquiry(Request $request)
    {
        // Validate resume upload
        $validator = Validator::make($request->all(), [
            'resume' => 'required|mimes:docx,doc,pdf'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->all();
            $status = [
                'error' => true,
                'message' => $message[0]
            ];
            return response()->json($status, 200);
        }

            $ip = '182.77.59.241';
        // $ip = $request->ip(); 
        $geoUrl = "http://www.geoplugin.net/json.gp?ip=" . $ip;
        $response = Http::get($geoUrl);
        
        // Check if the response is valid and contains the expected keys
        $geoData = $response->json();

        if (!$geoData || !is_array($geoData)) {
            // Handle the case when the geolocation API response is null or invalid
            $geoCountry = 'Unknown';
            $geoCity = 'Unknown';
            $geoRegion = 'Unknown';
        } else {
            // Safely extract geolocation details
            $geoCountry = isset($geoData['geoplugin_countryName']) ? $geoData['geoplugin_countryName'] : 'Unknown';
            $geoCity = isset($geoData['geoplugin_city']) ? $geoData['geoplugin_city'] : 'Unknown';
            $geoRegion = isset($geoData['geoplugin_regionName']) ? $geoData['geoplugin_regionName'] : 'Unknown';
        }

        // Combine geo data to form the 'origin' field
        $origin = "$geoCity, $geoRegion, $geoCountry";

    // Extract form inputs
    $name = $request->name;
    $email = $request->email;
    $mob_no = $request->mobile;
    $url = $request->url;
    $country = $geoCountry;
    $region = $geoRegion;
    $city = $geoCity;
    $title = $request->title;
    $role = $request->role;

    // Create a new inquiry record
    $inquiry = new DookEnquiry;
    $inquiry->name = $name;
    $inquiry->email = $email;
    $inquiry->mob_no = $mob_no;
    $inquiry->origin = $origin;
    $inquiry->url = $url;
    $inquiry->ip = $ip;
    $inquiry->type = "JobPost";
    $inquiry->status = 7;
    $inquiry->job_title = $title;
    $inquiry->job_role = $role;

   $file = $request->file('resume');
    $file_url = null;

       if ($file && $file->isValid()) {
        $extension = $file->getClientOriginalExtension();
        $arr = explode(" ", $name); 
        $str = implode("-", $arr);
        $filename = $str . '-' . time() . '.' . $extension;

        // Move the uploaded file to the local folder
        $destinationPath = public_path('dook/images/career');
        $file->move($destinationPath, $filename);

        // Local file URL (relative or absolute as needed)
        $file_url = $destinationPath . '/' . $filename;
    } else {
        Log::info('File is either not uploaded or invalid.');
    }


        // Save the inquiry
        $inquiry->save();

        // Generate the file URL for email attachment
        // $file_url = $filename ? 'https://dooktravels.s3.ap-south-1.amazonaws.com' . '/careers/' . $filename : null;
          $file_url = public_path().'/dook/images/career/'.$filename;

        // Prepare the email data
        $email_data = [
            'name' => $name,
            'email' => $email,
            'mob_no' => $mob_no,
            'origin' => $origin,
            'url' => $url,
            'country' => $geoCountry,
            'region' => $geoRegion,
            'city' => $geoCity,
            'ip' => $ip,
           'resume' => $file ? $request->file('resume') : null,
            'title' => $title,
            'role' => $role,
        ]; Log::info('Email Data: ' . json_encode($email_data));


        $mail_sent = Mail::send('mail-templates.job_enquiry', $email_data, function ($mail) use ($file_url) {
        Log::info('Mail object: ' . json_encode($mail));  // Log the mail object to check its state
        $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->to('hr@dooktravels.com');
        // $mail->bcc('anubhavi.sharma@dooktravels.com');
        $mail->subject("Dook International- Job Request");

        if ($file_url) {
            $mail->attach($file_url);
        }
    });


    // Return the response
    $status = [
        'error' => false,
        'message' => "Thank you for your interest in this role. Our HR team will get in touch with you shortly..!"
    ];

    return response()->json($status, 200);
    }
    public function notFound()
    {
        return response()->view('errors.404', [], 404);
    }


}
