<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\DookEnquiry;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Destination;
use App\Models\Country;
use DB;
use Mail;
use Storage;
use Image;

class InquiryController extends Controller
{
  public function store(Request $request)
    { 
        // dd($request->all());      
       $email = $request->email;
        $name = $request->name;

        $blockedNames = ['BTdPXxZN', 'sayedtanveer@gmail.com', 'seventhsky@gmail.com'];

        if (in_array($email, $blockedNames) || in_array($name, $blockedNames)) {
            return; 
        }

        // if ($name == 'BTdPXxZN') {
        //     return;
        // }

        // end block ip for 10 min
         $forwardedFor = request()->header('X-Forwarded-For');
        $ip1 = $forwardedFor ? explode(',', $forwardedFor)[0] : request()->ip(); 
        $fullUrl = url()->full();
        $utmSource = $fullUrl; 
        $cacheKey = 'inquiry_data_' . md5($ip1 . $fullUrl);
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            $geoData = $this->getGeoDataFromIp($ip1);
            $cachedData = [
                'ip' => $ip1,
                'country' => $geoData['country'],
                'city' => $geoData['city'],
                'region' => $geoData['region'],
                'utm_source' => $utmSource
            ]; 
            Cache::put($cacheKey, $cachedData, now()->addMinutes(30));
        }

        // Prepare common variables
        $country1 = $cachedData['country'];
        $region1 = $cachedData['region'];
        $city1 = $cachedData['city'];
        $origin1 = $city1 . ', ' . $region1 . ', ' . $country1;
  

         
        $email = $request->email;
        $mob_no = $request->mobile;
      
        $experience = $request->experince_name;
        $no_of_traveler = $request->no_of_traveler;

        if ($experience == 'MICE' && $no_of_traveler == null) {
            $no_of_traveler = 20;
        }

        $type = $request->type;
        $form_type = $request->form_type;
        $url = $request->url;
        $fullurl = $request->fullurl;
        $duration = (isset($request->duration)?$request->duration:'');
        $destination_json = (isset($request->destinations)?$request->destinations:'');
        $destinations_name = (isset($request->destinations_name)?$request->destinations_name:null);
        $min_country_data = $request->min_country_data;
        $fixed_departure = $request->fixed_departure;
        $company = ($request->company_name)? $request->company_name:null;
        // from ip
        $browserName = $request->browserName;
        $country = $country1;
        $region = $region1;
        $city = $city1;
        $ip = $ip1;
        $origin = $origin1;
        //ip related end
        $bnpl = ($request->bnpl == true)?'Yes':'No';
        $is_curl = true;
        $campaign_id = "";
        $inquiry = new DookEnquiry;
        if($type == "APL"){
            $pg_type = "CommonEnquiry";
            // $travel_date = $request->travel_date;
            $travel_date = $request->travel_date ?? date('Y-m-d');
            $no_of_traveler = $request->no_of_traveler;
            $comment = $request->comment;
            $pkg_id = $request->pkg_id;
            $pkgN_Id = "";
            if ($request->pkg_id != "") {
            $pkg_id = $request->pkg_id;
            // Try to get the package details by pkg_id
            $pkg_name = DB::table('departures')
                            ->where('dep_dook_ref_id', $pkg_id)
                            ->select('id', 'title', 'dep_dook_ref_id')
                            ->first();

            // Check if package is found, otherwise set a default destination
            if ($pkg_name) {
                $destination_id = DB::table('departure_destinations')
                                    ->where('departure_id', $pkg_name->id)
                                    ->distinct()
                                    ->pluck('destination_id')
                                    ->toArray();

            // Get destination name based on the destination IDs
            $destination = Destination::whereIn('id', $destination_id)
                                      ->value('dest_name');

            $pkgN_Id = $pkg_name->title . '-' . $pkg_name->dep_dook_ref_id;
            } else {
                // If the pkg_id does not match anything in the DB, set a default destination
                $destination = isset($destinations_name) ? $destinations_name : "Default Destination";
                $pkgN_Id = "Dook Group Tours - " . $destination;
            }
            } elseif ($request->pg_region != "") {
                $destination = $request->pg_region;
                $pkgN_Id = "Dook Region - " . $destination;
            } elseif ($request->destination != "") {
                $destination = $request->destination;
                $pkgN_Id = "Dook Destination - " . $destination;
            } elseif ($request->pg_country != "") {
                $destination = $request->pg_country;
                $pkgN_Id = "Dook Country - " . $destination;
            } else {
                $destination = "General";
                $pkgN_Id = "Dook - " . $pg_type;
            }

            $inquiry->name = $name;
            $inquiry->email = $email;
            $inquiry->mob_no = ($country == 'India')?'+91 '.$mob_no:$mob_no;
            $inquiry->origin = $origin;
            $inquiry->country = $country;
            $inquiry->travel_date = $travel_date;
            // $inquiry->comment = $comment;
            $inquiry->full_url = $fullurl;
            $inquiry->company_name = $company;
            $inquiry->no_of_traveler = $no_of_traveler;
            $inquiry->url = $url;
            // $inquiry->destination = $destination;
            $inquiry->destination = isset($destinations_name)?$destinations_name:$destination;
            $inquiry->ip = $ip;
            $inquiry->browser = $browserName;
            $inquiry->type = $pg_type;
            $inquiry->status = 1;
            $inquiry->pay_later = ($request->bnpl == true)?1:0;
            $inquiry->save(); 
            $campaign_id = $pkgN_Id;

            $email_data = [
                'name'=> $name,
                'email'=> $email,
                'mob_no'=> ($country == 'India')?'+91 '.$mob_no:$mob_no,
                'origin'=> $origin,
                'travel_date'=> $travel_date,
                'no_of_traveler'=> $no_of_traveler,
                'url'=> $url,
                'country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'ip'=> $ip,
                // 'destinations'=> $destination,
                'destinations'=> isset($destinations_name)?$destinations_name:$destination,
                'comp_name'=> $company,
                'bnpl'=>$bnpl,
            ];
        } 
        elseif($type == "cu"){
            // $travel_date = $request->travel_date;
            $travel_date = $request->travel_date ?? date('Y-m-d');
            $no_of_traveler = $request->no_of_traveler;
            $destination = $request->destination;
            $inquiry->name = $name;
            $inquiry->email = $email;
            $inquiry->mob_no = $mob_no;
            $inquiry->origin = $origin;
            $inquiry->country = $country;
            $inquiry->travel_date = $travel_date;
            $inquiry->no_of_traveler = $no_of_traveler;
            $inquiry->url = $url;
            $inquiry->destination = $destination;
            $inquiry->ip = $ip;
            $inquiry->browser = $browserName;
            $inquiry->type = "ContactUs";
            $inquiry->status = 3;
            $inquiry->pay_later = ($request->bnpl == true)?1:0;
            $inquiry->save();
            $campaign_id = "ContactUs Enquiry";
            $email_data = [
                'name'=> $name,
                'email'=> $email,
                'mob_no'=> $mob_no,
                'origin'=> $origin,
                'travel_date'=> $travel_date,
                'no_of_traveler'=> $no_of_traveler,
                'url'=> $url,
                'country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'ip'=> $ip,
                'destinations'=> $destination,
                'bnpl'=>$bnpl,
            ];
        }
        else if($type == "visa"){
            $request->name = $request->name ? $request->name : 'Visa Enquiry';
            $name = $name ? $name : 'Visa Enquiry';
            // $travel_date = $request->travel_date;
            $travel_date = $request->travel_date ?? date('Y-m-d');
            $no_of_traveler = $request->no_of_traveler;
            $destination = $request->destination;
            $inquiry->name = $name;
            $inquiry->email = $email;
            $inquiry->mob_no = $mob_no;
            $inquiry->origin = $origin;
            $inquiry->country = $country;
            $inquiry->travel_date = $travel_date;
            $inquiry->no_of_traveler = $no_of_traveler;
            $inquiry->url = $url;
            $inquiry->destination = $destination;
            $inquiry->ip = $ip;
            $inquiry->browser = $browserName;
            $inquiry->type = "Visa";
            $inquiry->status = 1;
            $inquiry->pay_later = ($request->bnpl == true)?1:0;
            $inquiry->save();
            $campaign_id = "V Consultation Enquiry";
            $email_data = [
                'name'=> $name,
                'email'=> $email,
                'mob_no'=> $mob_no,
                'origin'=> $origin,
                'travel_date'=> $travel_date,
                'no_of_traveler'=> $no_of_traveler,
                'url'=> $url,
                'country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'ip'=> $ip,
                'destinations'=> $destination,
                'bnpl'=>$bnpl,
            ];
        }
        else if($type == "flight"){
            $request->name = $request->name ? $request->name : 'Flight Enquiry ' . $mob_no;
            $name = $name ? $name : 'Flight Enquiry';
            // $travel_date = $request->travel_date;
            $travel_date = $request->travel_date ?? date('Y-m-d');
            // $travel_date = date('Y-m-d', strtotime($request->travel_date));
            $no_of_traveler = $request->no_of_traveler;
            $destination = $request->destination;
            $inquiry->name = $name ? $name : 'Flight Enquiry';
            $inquiry->email = $email;
            $inquiry->mob_no = $mob_no;
            $inquiry->origin = $request->origin;
            $inquiry->country = $country;
            $inquiry->travel_date = $travel_date;
            $inquiry->no_of_traveler = $no_of_traveler;
            $inquiry->url = $url;
            $inquiry->destination = $destination;
            $inquiry->ip = $ip;
            $inquiry->browser = $browserName;
            $inquiry->type = "Flight";
            $inquiry->status = 18;
            $inquiry->pay_later = ($request->bnpl == true)?1:0;
            $inquiry->save();
            $campaign_id = "Air Deals Enquiry";
            $email_data = [
                'name'=> $name,
                'email'=> $email,
                'mob_no'=> $mob_no,
                'origin'=> $request->origin,
                'travel_date'=> $travel_date,
                'no_of_traveler'=> $no_of_traveler,
                'url'=> $url,
                'country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'ip'=> $ip,
                'destinations'=> $destination,
                'bnpl'=>$bnpl,
            ];
        }
        else if($type == "dook-departure"){
            $no_of_traveler = $request->no_of_pax;
            $company = ($request->company_name)? $request->company_name:'';
            $dep_id = $request->dep_id;
            $dep_title = $request->dep_title;
            $travel_date = $request->dep_date;
            // $ip = $request->ip();
            $destination = '';
        
            $inquiry->name = $name;
            $inquiry->email = $email;
            $inquiry->mob_no = $mob_no;
            $inquiry->origin = $origin;
            $inquiry->country = $country;
            $inquiry->travel_date = $travel_date;
            $inquiry->no_of_traveler = $no_of_traveler;
            $inquiry->url = $url;
            $inquiry->company_name = $company;
            $inquiry->ip = $ip;
            $inquiry->status = 1;
            $inquiry->type = "dook-departure";
            $inquiry->save();
            $email_data = [
                'name'=> $name,
                'email'=> $email,
                'mob_no'=> $mob_no,
                'departure_title'=> $dep_title,
                'travel_date'=> $travel_date,
                'no_of_traveler'=> $no_of_traveler,
                'country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'comp_name'=> $company,
                'ip'=> $ip,
                'url'=> $url,
            ];
        }
        else{
            
        }
;
        $last_inquiry_id = $inquiry->id;
        if($form_type === "Get a Call Back form"){
            $is_curl = true;
            $lead_id = '';
            $ref_idd = "DOOK-".$last_inquiry_id;
            $update = DookEnquiry::find($last_inquiry_id);
            $update->ref_id = $ref_idd;
            $update->source = $form_type;
            $update->full_url = $fullurl;
            $update->save();
        }elseif($fixed_departure === 'yes'){
            $update = DookEnquiry::find($last_inquiry_id);
            $update->is_fixed_departure = 1;
            $update->save();
        }else{

        }

         if ($is_curl == true) {
            $source = "";
            $source1 = "";

            // Check if utmSource is present in the request
            if (isset($request->utmSource)) {
                $source = $request->utmSource;
                $source1 = $source;  
            }

            // Check if source1 has specific values and update accordingly
            if ($source1 == "fb" || $source1 == "facebook" || $source1 == "an") {
                $source1 = "Facebook";
            } elseif ($source1 == "ig" || $source1 == "instagram") {
                $source1 = "Instagram";
            } elseif ($source1 == "google") {
                $source1 = "Google";
            } else {
                $source1 = "";
            }

            // Check for the UTM source in the full URL query parameters
            $url_components = parse_url($fullurl); 
            if (isset($url_components['query'])) {
                parse_str($url_components['query'], $params);
                $source = isset($params['utm_source']) ? $params['utm_source'] : "";
            }

            // If source is empty, set it to null
            if (empty($source)) {
                $source = null;
            }

            // Fallback to form_type if provided
            $source1 = ($form_type) ? $form_type : $source1;

            // Process and save data
            $last_id = $inquiry->id;
            $ref_id = "DOOK-" . $last_id;

            // Find and update the record in the database
            $update = DookEnquiry::find($last_id);
            $update->ref_id = $ref_id;
            $update->source = ($form_type) ? $form_type : $source; // Save form_type or source
            $update->full_url = $fullurl;

            // If source is not empty, append it to the campaign_id
            if ($source !== null) {
                $campaign_id = $campaign_id . ' - ' . $source;
            }

            // Save the updated information
            $update->save();



            $curl_data = json_encode(array(
                "token"=> "b107b0af85f13787939057e6d09249606bc117a853b8b2bb04c79d1928754dd2",
                "lead"=>array(
                    "first_name"=>"", 
                    "last_name"=>$name,
                    "city"=>$city,
                    'region'=> $region,
                    "country"=>$country,
                    "email"=>$email, 
                    "mobile"=>$mob_no, 
                    "phone"=>$mob_no, 
                    "company"=>$company,
                    "no_of_nights"=>$duration,
                    "url"=>$url,
                    "destination_json"=>$destination_json,
                    "destinations_name"=>$destinations_name,
                    "min_country_data"=>$min_country_data,
                    "fixed_departure"=>$fixed_departure,
                    "form_type"=>$form_type,
                    "experience"=>$experience, 
                    "website"=>"",
                    "campaign_name"=>$campaign_id,
                    "source"=>'web',
                    "source_medium"=>$source1,
                    "ref_id"=>$ref_id,
                    "no_of_pax"=>$no_of_traveler,
                    "ip"=>$ip,
                    "travel_date"=>$travel_date,
                    "dook_enquiry_id"=>$last_id,
                   "departure_id" => "",

                    "custom_fields"=>array(
                        "segment"=>"", 
                        "destination"=>$destination, 
                        "description"=>"", 
                        "no_of_passengers"=>$no_of_traveler,
                        "date_of_travel"=>$travel_date,
                        "bnpl"=>$bnpl,
                        "campaign_url"=>$url,
                        "experience"=>$experience
                    )
                )
            )); 

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://dooktravels.tutterflycrm.com/tfc/api/capture_lead');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($curl_data))                                                                       
            );

            // =================================================

            // code with lead id

            $result = curl_exec($ch);
            
            if ($result === false) {
                $curl_error = curl_error($ch);

                Mail::raw($curl_error, function ($message) use($fullurl){
                    $message->to('anubhavi.sharma@dooktravels.com');
                    $message->subject('Curl error - '.$fullurl);
                });
              
            } else {
                $jsonResponse = json_decode($result, true);

                if ($jsonResponse === null) {
                    $curl_error = json_last_error_msg();

                    Mail::raw($curl_error, function ($message) use($fullurl){
                        $message->to('anubhavi.sharma@dooktravels.com');
                        $message->subject('Json error - '.$fullurl);
                    });

                    $lead_id = '';
                } else {
                    $lead_id = $jsonResponse['lead_id'];
                    $update = DookEnquiry::find($last_id);
                    if ($update) {
                        $update->tfc_lead_id = $lead_id;
                        $update->save();
                    } else {
                       
                    }
                }
            }


            curl_close($ch);
        }
       
        // //////////// lds curl ////////////////
        if($country == "India"){

        }
        elseif($country == "" || $country == null){
            
        }
        else{
            if($request->pkg_id != ""){
                $pkg_id = DB::table('departures')->where('dep_dook_ref_id',$request->pkg_id)->select('id','no_of_nights','no_of_days')->first();
                $pkg_days = $pkg_id->no_of_days;
                $pkg_nights = $pkg_id->no_of_nights;
                $country_id = DB::table('country_departures')
                    ->where('departure_id',$pkg_id->id)
                    ->value('country_id');
                $dest_country = DB::table('countries')->where('id', $country_id)
                        ->value('country_name');
            }elseif($request->pg_country  != ""){
                $dest_country = $request->pg_country;
                $pkg_days = '';
                $pkg_nights = '';
            }elseif($request->destination  != ""){
                $dest_country = DB::table('destinations')->where('dest_name',$request->destination)->value('country_name');
                $pkg_days = '';
                $pkg_nights = '';
            }elseif($request->pg_region  != ""){
                $region_id = DB::table('regions')->where('region_name',$request->pg_region)->value('id');
                $dest_country = DB::table('region_countries')->where('region_id',$region_id)->value('country_name');
                $pkg_days = '';
                $pkg_nights = '';
            }else{
                $dest_country = "";
                $pkg_days = '';
                $pkg_nights = '';
            }
            // leads curl starts
            $ldsArray = array(
                'name' => $name,
                'mobile' => $mob_no,
                'email' => $email,
                'dest_country' => $dest_country,
                'no_of_pax' => $no_of_traveler,
                'date_of_travel' => $travel_date,
                'url' => $url,
                'source_country'=> $country,
                'region'=> $region,
                'city'=> $city,
                'ip'=> $ip,
                'days'=>$pkg_days,
                'nights'=>$pkg_nights,
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://leads.dookinternational.com/api/leads_store');
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $ldsArray);
            $response = curl_exec($curl);
            $data = json_decode($response);

        }

        $b2c_domain = ['gmail','yahoo','hotmail','rediffmail','aol','outlook','zoho','icloud','proton','rediff','gmx','mail2world','indiatimes','juno','neo','yandex','net'];

        $email_parts = explode('@', $email);
        if (count($email_parts) == 2) {
            $email_parts = explode('.', $email_parts[1]);
            $domain = strtolower($email_parts[0]);
            if (in_array($domain, $b2c_domain)) {
                $domain_type = 'B2C';
            } else {
                $domain_type = 'B2B';
            }
        } else {
            $domain_type = '';
        }

        $mail_sent = false;

       if (env('EMAIL_ENABLED', false)) {
            try {
                Mail::send('mail-templates.enquiry', $email_data, function($mail) use($request, $domain_type, $form_type, $no_of_traveler, $destination, $experience) {
                    $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                    $to_address = null;

        

                    if (isset($domain_type) && $domain_type == 'B2C' && $no_of_traveler < 10) {
                        if ((isset($destination) && $destination == 'General') || (isset($experience) && $experience == 'MICE')) {
                            $to_address = 'sales@dooktravels.com';
                        }
                    } 
                    
                    // Fallback if $to_address is still null
                    if ($to_address === null) {
                        $to_address = 'sales@dooktravels.com';
                    }

                    $mail->to($to_address);
                    $mail->bcc('no-reply@dookinternational.com');

                    if (isset($form_type) && $form_type == 'emt') {
                        $mail->subject('Dook International (EMT)-lead# '.$domain_type.' Web-Enquiry ' .$request->name.' !');
                    } elseif (isset($form_type) && $form_type === "Get a Call Back form") {
                        $mail->subject('Dook International-lead# Get a Call Back-Enquiry ' .$request->name.' !');
                    } elseif (isset($experience) && $experience == "MICE") {
                        $mail->subject('Dook International (MICE)-lead# '.$domain_type.' Web-Enquiry ' .$request->name.' !');
                    } elseif (isset($destination) && $destination == "General") {
                        $mail->subject('Dook International (General)-lead# '.$domain_type.' Web-Enquiry ' .$request->name.' !');
                    } else {
                        $mail->subject('Dook International-lead# '.$domain_type.' Web-Enquiry ' .$request->name.' !');
                    }
                });
                $mail_sent = true; 
            } catch (\Exception $e) {     
                Log::error('Mail sending failed', [
                    'exception' => $e->getMessage(),
                    'request' => $request->all(),
                    'email_data' => $email_data,
                    'domain_type' => $domain_type,
                    'form_type' => $form_type,
                    'no_of_traveler' => $no_of_traveler,
                    'destination' => $destination,
                    'experience' => $experience,
                ]);
            }
        }

        if ($mail_sent) {
            $inquiry->mail_status = 1;
            $inquiry->save();
        }
        // =================================================

        $status = array(
            'error' => false,
            'message' => "Thank you for showing your interest in traveling with us, one of our travel consultants will contact you soon..!"
        );
        if($type == "dook-departure"){
            return redirect()->route('thankyou'); 
        }else{
            return response()->json($status, 200);
        }
    }
    private function getGeoDataFromIp($ip1)
{
    // $url = "http://www.geoplugin.net/json.gp?ip=" . $ip1;
    $url = "http://ip-api.com/json/" . $ip1;
    $response = Http::get($url);
    $geoData = $response->json();

    return [
        'country' => $geoData['country'] ?? 'Unknown',
        'city' => $geoData['city'] ?? 'Unknown',
        'region' => $geoData['regionName'] ?? 'Unknown',
    ];
}

 public function thankYou()
    {
        return view('frontend.common.thankyou');
    }


}
