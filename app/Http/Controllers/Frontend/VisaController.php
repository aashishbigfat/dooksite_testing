<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use App\Models\AllCountry;
use App\Models\Destination;
use App\Models\Country;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function visaindex(Request $request)
    {
         $data = Visa::distinct()
                ->select('country_of_residence as country_name','residence_iso3 as iso_3','residence_iso2 as iso_2','ph_country_url as country_slug')
                ->orderBy('country_of_residence','ASC')
                ->get();
         $residence_country = json_decode($data);
        $v_destinations =Destination::where('visa_popular_destination',1)
                ->select('dest_name','image','country_name as country','country_iso_2 as iso2','country_id')
                ->get();
        foreach ($v_destinations as $values) {
            $flag = Country::where('id', $values->country_id)->select('flag_old')->first();
            $values->flag = 'https://adm.dookinternational.com/country/flag/' . $flag->flag_old; 
            $arrH = explode(' ',$values->country);
            $strH = implode("-", $arrH);
            $aa = str_replace(',', '', $strH);
            $urlH = strtolower($aa);
            $values->visa_url = '-to-'.$urlH.'-visa';
        }
        $myItem = '';

        if(isset($_COOKIE['countryCodeIp'])) {
            $myItem = $_COOKIE['countryCodeIp']; 
            $myItem = json_decode($myItem);
            if($myItem){
                $country_codeV = $myItem->country_code;
                $locationDataCountry = $myItem->country;
            }else{
                $country_codeV = "IN";
                $locationDataCountry = "India";
            }
        }else{
            $country_codeV = "IN";
            $locationDataCountry = "India";
        }
        $form_type = 'visa';
          $arrR = explode(' ',$locationDataCountry);
          $strR = implode("-", $arrR);
          $aaR = str_replace(',', '', $strR);
          $selected_country_slug = strtolower($aaR);

        return view('frontend.visa.visa',compact('residence_country','v_destinations','locationDataCountry','country_codeV','form_type','selected_country_slug'));
    }
    public function getVisaDetails(Request $request, $slug)
    {
        $response = Visa::distinct()
                ->select('country_of_residence as country_name','residence_iso3 as iso_3','residence_iso2 as iso_2','ph_country_url as country_slug')
                ->orderBy('country_of_residence','ASC')
                ->get();
        $residence_country = json_decode($response);

        $post = array(
            'visa_url' => $slug
        );
        $slug = $post;
        $details = Visa::where(['slug_url' => $slug, 'status' => 1])
                ->select('id','passport_holder_country as phCountry','residence_iso2 as phCountryIso2','country_of_residence as residence_country','visiting_country','visiting_iso2 as vCountryIso2','visa_type','visa_category','processing_time','stay_period','validity','fees','ph_country_url','v_country_url','slug_url','visa_required','visa_arrival')
                ->get();
        foreach ($details as $key => $value) {
            if($value->fees ==""){
                $value->fees = "--";
            }
            if($value->processing_time ==""){
                $value->processing_time = "--";
            }
            if($value->stay_period ==""){
                $value->stay_period = "--";
            }
            if($value->validity ==""){
                $value->validity = "--";
            }
            if($value->visa_type ==""){
                $value->visa_type = "No";
            }
        }
        $description = Visa::where(['slug_url' => $slug, 'status' => 1])
            ->select('required_documents','eligibility_criteria','exemptions','general_information','visa_application_process as visa_process','visiting_country','passport_holder_country as phCountry','additional_info as faqs','meta_title','meta_description','meta_keywords','visiting_iso2 as vCountryIso2','residence_iso2 as phCountryIso2','slug_url')
            ->first();

        if($description->meta_title == "" || $description->meta_title == null){
            $description->meta_title = $description->visiting_country." Visa from ".$description->phCountry.', '.$description->visiting_country." Tourist Visa";
        }
        if($description->meta_description == "" || $description->meta_description == null){
            $description->meta_description = $description->visiting_country." Tourist Visa: Looking for ".$description->visiting_country." Visa from ".$description->phCountry."? Check out ".$description->visiting_country." Visa Requirements. Apply for ".$description->visiting_country." Visa Online!";
        }
        if($description->meta_keywords == "" || $description->meta_keywords == null){
            $description->meta_keywords = $description->visiting_country." Visa, ".$description->visiting_country." Visa from ".$description->phCountry.", ".$description->visiting_country." Tourist Visa, ".$description->visiting_country." Visa Requirements, ".$description->visiting_country." Visa Online";
        }
        $response = array(
            'visa_details' => $details,
            'visa_description' => $description
        );

        $visas = $response['visa_details'];
        $visaDes = $response['visa_description'];
        if(count($visas)>0 && $visaDes)
        {
            return view('frontend.visa.visa_detail', compact('residence_country','visas','visaDes'));
        }else{
            return redirect('/');
        }
    }
       public function visaDependencyCountryList(Request $request)
       { 
        $post = array(
            'iso_2' => $request->iso_2
        );
        $data_string = json_encode($post);
        $iso2 = $request->iso_2;
        $response = Visa::where('residence_iso2', $iso2)
            ->distinct()
            ->select('visiting_country as country_name','visiting_iso3 as iso_3','visiting_iso2 as iso_2','v_country_url as country_slug','slug_url')
            ->orderBy('visiting_country','ASC')
            ->get();
        $countryISO = json_decode($response);
        return response()->json($countryISO, 200);
    }
}
