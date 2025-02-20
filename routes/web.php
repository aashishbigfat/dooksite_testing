<?php

use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\DepertureController;
use App\Http\Controllers\Frontend\CountryController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\ExperienceController;
use App\Http\Controllers\Frontend\PoiController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ActivityController;
use App\Http\Controllers\Frontend\RegionController;
use App\Http\Controllers\Frontend\VisaController;
use App\Models\SlugMaster;
use Illuminate\Support\Facades\Route;

Route::get('/group-tours/{slug}/{id}', [DepertureController::class, 'agentdeparture'])->name('agentdeparture');
Route::get('/poi/{slug}/{id}', [PoiController::class,'poi_details'])->name('poi_details');
Route::get('/', [HomePageController::class, 'index'])->name('index');
Route::get('/international-tour-packages', [DepertureController::class, 'internationalTourPackages'])->name('international-tour-packages');
Route::get('/domestic-tour-packages', [DepertureController::class, 'domesticTourPackages'])->name('domestic-tour-packages');
Route::get('/{country}/{slug}/{dook_ref_id}', [DepertureController::class, 'packageDetails'])->name('departure_details');

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
Route::get('/visa-consultation-services', [VisaController::class, 'visaindex'])->name('visas');

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
        else {
            Route::get('/404', function () {
                return view('errors.404');
            });
        }
    } else {
        Route::get('/{slug}', [VisaController::class, 'getVisaDetails'])->name('get_visa_details');
    }
