<?php

use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\DepertureController;
use App\Http\Controllers\Frontend\CountryController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\ExperienceController;
use App\Http\Controllers\Frontend\PoiController;
use App\Http\Controllers\Frontend\CommonSearchController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ActivityController;
use App\Http\Controllers\Frontend\RegionController;
use App\Http\Controllers\Frontend\VisaController;
use App\Http\Controllers\Frontend\InquiryController;
use App\Http\Controllers\Frontend\CommonController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\AboutUsController;
use App\Models\SlugMaster;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/route-clear', function () {
    Artisan::call('route:clear');
    return 'Route cache cleared!';
});

Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return 'View cache cleared!';
});

Route::get('/config-clear', function () {
    Artisan::call('config:clear');
    return 'Config cache cleared!';
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return 'Application cache cleared!';
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Optimizations cleared!';
});

Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return 'Routes cached!';
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'Config cached!';
});

Route::get('/404', [CommonController::class, 'notFound'])->name('custom_404');
Route::get('/group-tours/{slug}/{id}', [DepertureController::class, 'agentdeparture'])->name('agentdeparture');
Route::get('/blog/category/{cat_url}/', [BlogController::class,'postcategoryWise'])->name('post_category_wise');
Route::get('/poi/{slug}/{id}', [PoiController::class,'poi_details'])->name('poi_details');
Route::get('/', [HomePageController::class, 'index'])->name('index');
Route::get('/international-tour-packages', [DepertureController::class, 'internationalTourPackages'])->name('international-tour-packages');
Route::get('/domestic-tour-packages', [DepertureController::class, 'domesticTourPackages'])->name('domestic-tour-packages');
Route::get('/{country}/{slug}/{dook_ref_id}', [DepertureController::class, 'packageDetails'])->name('departure_details');

Route::post('/common_inquiry_store', [InquiryController::class, 'store'])->name('common_inquiry_store');
Route::get('/thank-you', [InquiryController::class, 'thankYou'])->name('thank-you');
 Route::get('/search', [CommonSearchController::class, 'commonSearch'])->name('common_search');
//group-tour 

Route::get('/group-tours', [DepertureController::class, 'grouppack'])->name('group-tours');

// country
Route::get('/countries',[CountryController::class, 'index'])->name('countries');
Route::get('/countries/paginate', [CountryController::class, 'paginateCountries'])->name('countries.paginate');
Route::get('/about/{slug_url}',[CountryController::class, 'countryAbout'])->name('about_country');

// destination
Route::get('/destinations', [DestinationController::class,'index'])->name('destinations');
Route::get('/destinations/{slug_url}', [DestinationController::class, 'destinationDetail'])->name('destination_detail'); 

// activity
Route::get('/activities', [ActivityController::class,'index'])->name('activities');
Route::get('/activities/{slug_url}', [ActivityController::class,'activityDetails'])->name('activity_detail');

// region
Route::get('/regions', [RegionController::class,'regions'])->name('regions');

// experience
Route::get('/experiences', [ExperienceController::class,'index'])->name('experiences');

// blog
Route::get('/blog/', [BlogController::class,'blog'])->name('blog');
Route::get('/blog/{post_slug}/', [BlogController::class,'blogdetail'])->name('blogdetail');


// visa
Route::get('/visa_dependency_country_list', [VisaController::class, 'visaDependencyCountryList'])->name('dependency_country_list');
Route::get('/visa-consultation-services', [VisaController::class, 'visaindex'])->name('visa-consultation-services');


 // privacy routes
Route::get('/privacy-policy', [CommonController::class,'privacyPolicy'])->name('privacy_policy');

// terms routes
Route::get('/terms-and-conditions', [CommonController::class,'termsConditions'])->name('terms_conditions');

// Careers routes
Route::get('/careers', [CommonController::class,'careerListing'])->name('careers');
Route::get('/careers/{slug_url}', [CommonController::class, 'careersDetail'])->name('careers_detail');
Route::post('/resume-store', [CommonController::class, 'jobEnquiry'])->name('resume_store');

//Contact routes
Route::get('/contact-us', [CommonController::class,'contactUs'])->name('contact_us');
Route::get('/faqs', [CommonController::class,'faqs'])->name('faqs');
Route::get('/dook-presentations', [CommonController::class,'presentations'])->name('presentations');
Route::get('/reviews', [CommonController::class,'reviews'])->name('reviews');
Route::post('/reviews-store', [CommonController::class,'reviewsStore'])->name('reviews_store');

 // About us routes
    Route::get('/about-us', [AboutUsController::class,'aboutUs'])->name('about_us');

// login register
Route::post('login_process',[BookingController::class,'login_process'])->name('login.login_process');
    Route::get('logout', function () {
    session()->forget('FRONT_USER_LOGIN');
    session()->forget('FRONT_USER_ID');
    session()->forget('FRONT_USER_NAME');
    session()->forget('USER_TEMP_ID');
    return redirect('/');
    });
    Route::post('forgot_password',[BookingController::class,'forgot_password']);
    Route::get('/forgot_password_change/{id}',[BookingController::class,'forgot_password_change']);
    Route::post('forgot_password_change_process',[BookingController::class,'forgot_password_change_process']);
    Route::get('/registration',[BookingController::class,'registration']);
    Route::post('/registration_process',[BookingController::class,'registration_process'])->name('registration.registration_process');
    Route::get('/verification/{id}',[BookingController::class,'email_verification']);

  // package_booking
    Route::post('/group-tour-booking', [BookingController::class, 'submitBooking'])->name('book_now_submit');
    Route::get('/book_now_page', [BookingController::class, 'bookNowPage'])->name('book_now_page');
    Route::post('/place-order',[BookingController::class,'place_order'])->name('place_order');
// payment
Route::post('/purchase', [BookingController::class, 'purchaseSubscription'])->name('purchase');
Route::post('/cc-response', [BookingController::class, 'ccResponse'])->name('cc-response');

 $routeN = url()->current();
  $basename = basename($routeN);
  $slugmaster = SlugMaster::where('slug_name',$basename)->pluck('module_name')->toArray();
    $slugmaster = array_unique($slugmaster);
     if (is_array($slugmaster) && count($slugmaster) > 0) {
        if (in_array('country_tour_page', $slugmaster) || 
            in_array('country_attraction_page', $slugmaster) || 
            in_array('country_experience_page', $slugmaster) || 
            in_array('country_group_page', $slugmaster)) {
            Route::get('/{slug}', [CountryController::class, 'countrySlug'])->name('country_slug');
        }
        elseif (in_array('experience_detail_page', $slugmaster)) {
            Route::get('/{slug}', [ExperienceController::class, 'experienceDetails'])->name('experience_details');
        }
        elseif (in_array('region_single_page', $slugmaster)) {
            Route::get('/{slug}', [RegionController::class, 'regionDetails'])->name('region_details');
        }
         elseif(in_array('country_wise_package',$slugmaster)) {
            Route::get('/{slug}', [DestinationController::class,'countrywisepackage'])->name('country-wise-packages');
        }
        else {
           // Route::get('/404', [CommonController::class, 'notFound']);
            Route::get('/', [HomePageController::class, 'index'])->name('index');
        }
    } else {
        Route::get('/{slug}', [VisaController::class, 'getVisaDetails'])->name('get_visa_details');
    }
   // Fallback: redirect to /404 instead of showing the error directly
Route::fallback(function () {
    return redirect()->route('frontend.index');
});

// ✅ Optional: Explicit error routes (if accessed directly)
Route::get('/error/400', function () {
    return redirect()->route('frontend.index');
})->name('error.400');

Route::get('/error/500', function () {
    return redirect()->route('frontend.index');
})->name('error.500');

// ✅ Catch-all route for 404, 400, 500, etc.
Route::any('{any}', function () {
    return redirect()->route('frontend.index');
})->where('any', '.*');
