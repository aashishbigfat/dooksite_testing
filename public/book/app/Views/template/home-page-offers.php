<?php
$router = service('router');
$class_name = $router->controllerName();
$methodName = $router->methodName();
$classparm = explode("\\", $class_name);
$controller = end($classparm);

if ($controller == "HolidayBooking") {
    $controller = "Holiday";
}
if ($controller == "CruiseBooking") {
    $controller = "Cruise";
}


?>




<!------Deal and offer----->
<?php if (isset($offers_list) && !empty($offers_list)) {
    if ($controller !== "Home") {
        $offers_listData =  $offers_list;
        unset($offers_list);
        $offers_list = array();
        $offers_list[strtolower($controller)] = isset($offers_listData[strtolower($controller)]) ? $offers_listData[strtolower($controller)] : array();
        if (empty($offers_list[strtolower($controller)])) {
            $offers_list = array();
        }
    } ?>
    <?php if (isset($offers_list) && !empty($offers_list)) {  ?>
        <section class="offer_page">
            <div class="container">
                <div class="home_heading">
                    <h2>Deals and Offers <span class="float-end"><a href="<?php echo  site_url('offers-list'); ?>">View
                                All</a></span>
                    </h2>
                </div>
                <div class="row gy-4">
                    <?php $count  =  1;
                    foreach ($offers_list as $servicekey => $offers_data) {   ?>
                        <?php
                        foreach ($offers_data as $offer) {
                        ?>
                            <div class="col-lg-3 col-md-6 col-12">
                                <a href="<?php echo  $offer['url'] != null && $offer['url'] != '' ? $offer['url'] : 'javascript:void(0);'; ?>">
                                    <div class="card">
                                        <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" alt=" <?php echo $offer['title'] ?>" class="card-img">
                                        <!-- <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" alt=" <?php echo $offer['title'] ?>" class="image2"> -->

                                        <div class="card-img-overlay text-white d-flex flex-column justify-content-center">
                                            <h4 class="card-title"> <?php echo $offer['title'] ?> </h4>
                                            <p class="card-text"><?php echo $offer['description'] ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php
                            $count = $count + 1;
                            if ($count > 4) {
                                break;
                            }
                        }
                        if ($count > 4) {
                            break;
                        }
                    } ?>
                </div>
            </div>
        </section>
    <?php } ?>
<?php } ?>
<!------Deal and offer end----->
<!------Flight Routes start------->
<?php if (whitelabel['flight_module'] == 'active') { ?>
    <?php if (isset($top_routes_list) && is_array($top_routes_list) && !empty($top_routes_list) && count($top_routes_list) > 0) : ?>
        <section class="popular_destination sections-bg">
            <div class="container">
                <div class="home_heading">
                    <h2>Popular Flight Routes</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="routebox">
                            <div class="row gy-3">
                                <?php foreach ($top_routes_list as $routes_list) :
                                    $uri = site_url('flight/search') . '?' . http_build_query($routes_list['url']);
                                ?>
                                    <div class="col-lg-2 col-md-6 col-6">
                                        <a href="<?= $uri ?>" class="routes_box">
                                            <div class="origin_destination">
                                                <div class="elip"><?= $routes_list['city_origin'] ?> (<?= $routes_list['OriginCode'] ?>)</div>
                                                <div class="time"><?= $routes_list['url']['departdate'] ?></div>
                                                <?php if ($routes_list['url']['returndate']) : ?>
                                                    <div class="time"><i class="fa-solid fa-plane-arrival"></i> <?= $routes_list['url']['returndate'] ?></div>
                                                <?php endif ?>
                                                <div class="elip"><?= $routes_list['city_destination'] ?> (<?= $routes_list['DestinationCode'] ?>)</div>
                                            </div>
                                            <?php if ($routes_list['price']) : ?>
                                                <div class="price_go dest">
                                                    <p class="low-price">
                                                        <span>Starting From</span>
                                                        <span class="rs"> <i class="fa-solid fa-inr"></i> </span> <?php echo $routes_list['price']; ?>
                                                    </p>
                                                </div>
                                            <?php endif ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php } ?>
<!------Flight Routes End------->
<!-- Trending Hotels Start -->
<?php if (whitelabel['hotel_module'] == 'active' && whitelabel['hotel_extranet_module'] == 'active') { ?>
    <?php if (isset($trending_hotel) && is_array($trending_hotel) && !empty($trending_hotel) && count($trending_hotel) > 0) : ?>
        <section class="trending-hotels ">
            <div class="container">
                <div class="home_heading">
                    <div class="flex-grow-1">
                        <h2 class="mb-0">Trending Hotels</h2>
                    </div>
                </div>
            </div>
            <div id="owl-carousel" class="owl-carousel owl-theme">
                <?php foreach ($trending_hotel as $hotel) : ?>
                    <div class="item">
                        <a href="#" class="url-box">
                            <figure class="newsCard news-Slide-up ">
                                <img src="<?php echo root_url . 'uploads/hotel/thumbnail/' . $hotel['hotel_images'] ?>" alt="<?= $hotel['hotel_name'] ?>">
                                <div class="newsCaption">
                                    <div class="cnt-title">
                                        <h5 class="newsCaption-title"><?= $hotel['hotel_name'] ?>
                                            <span class="text-primary pull-right"><?= explode('-', $hotel['hotel_city'])[0] ?></span>
                                        </h5>
                                        <div class="rating">
                                            <?php for ($i = 0; $i < $hotel['hotel_star_rating']; $i++) : ?>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            <?php endfor ?>
                                        </div>
                                    </div>
                                    <div class="newsCaption-content d-flex ">
                                        <span class="rating-no"><?= $hotel['review_rating'] ?>/5</span>
                                        <div class="rating ms-2">
                                            <span><?= $hotel['review_rating'] ?> ratings</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="overlay"></span>
                            </figure>
                        </a>
                        <div class="card d-none">
                            <div class="hotel_img">
                                <img src="<?php echo root_url . 'uploads/hotel/thumbnail/' . $hotel['hotel_images'] ?>" alt="<?= $hotel['hotel_name'] ?>" class="card-img-top">
                            </div>
                            <div class="card-img-overlay text-white d-flex flex-column justify-content-center">
                                <div class="d-flex align-item-center justify-content-between">
                                    <h3 class="card-title"><?= $hotel['hotel_name'] ?>
                                        <span class="text-primary pull-right"><?= explode('-', $hotel['hotel_city'])[0] ?></span>
                                    </h3>

                                </div>
                                <div class="dlab-info-has-text">
                                    <div class="desc d-flex align-items-center">
                                        <span class="rating-no"><?= $hotel['review_rating'] ?>/5</span>
                                        <div class="rating ms-2">
                                            <span><?= $hotel['review_rating'] ?> ratings</span>
                                        </div>
                                    </div>
                                    <a href="packages.html" class="site-button-link">View All Tours</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        </section>
    <?php endif ?>
<?php } ?>
<!-- Trending Hotels End -->
<!------Holiday theme list Start----->
<?php if (whitelabel['holiday_module'] == 'active') { ?>
    <?php if (isset($holiday_themes_list) && is_array($holiday_themes_list) && !empty($holiday_themes_list) && count($holiday_themes_list) > 0) : ?>
        <section class="holidays_theme">
            <div class="container">
                <div class="home_heading">
                    <h2>Holiday theme</h2>
                </div>
                <div class="holiday_slider owl-carousel owl-theme">
                    <?php foreach ($holiday_themes_list as $key => $theme) {  ?>
                        <a href="<?php echo site_url('holiday/themes/') . $theme['Slug']; ?>">
                            <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $theme['Image']; ?>
                            <div class="card-group">
                                <div class="card text-white">
                                    <img src="<?php echo $filename; ?>" class="img-fluid" alt="<?php echo $theme['Name'] ?>">
                                    <?php $filenameicon =  root_url . 'uploads/holiday/thumbnail/' . $theme['Icon']; ?>
                                    <div class="card-img-overlay holidayimg_overlay text-center d-flex flex-column justify-content-center text-white">
                                        <?php if ($theme['Icon']) { ?>
                                            <img src="<?php echo $filenameicon; ?>" class="img-fluid mx-auto">
                                        <?php } ?>
                                        <h5 class=""><?php echo $theme['Name'] ?></h5>
                                        <h6>
                                            <span class=""><?php echo $theme['TotalPackages'] ?>
                                                Trips</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php endif ?>
<?php } ?>
<!------Holiday theme list end----->
<!------Holidays Destinations Start------->
<?php if (whitelabel['holiday_module'] == 'active') { ?>
    <?php if (isset($DomesticHolidayDestinations)  && is_array($DomesticHolidayDestinations) && !empty($DomesticHolidayDestinations) && count($DomesticHolidayDestinations) > 0) : ?>
        <section class="destinations">
            <div class="container">
                <div class="home_heading ">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2>Trending Indian Holidays Destinations</h2>
                        <span><a href="<?php echo site_url('trending-Indian-holidays-destinations') ?>">View All</a></span>
                    </div>
                    <p>
                        An international holiday not only offers a breath of fresh air but is also a great way to explore
                        different parts of the world and get a new…
                    </p>
                </div>
                <div class="tranding-destinations destination-slider1 owl-carousel owl-theme">
                    <?php foreach ($DomesticHolidayDestinations as $DomesticHolidayData) :  ?>
                        <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $DomesticHolidayData['destination_image']; ?>
                        <div class="destination-box">
                            <a href="<?php echo site_url('holiday/destinations/') . $DomesticHolidayData['destination_slug']; ?>">
                                <div class="destination-image">
                                    <?php if (UR_exists($filename)) : ?>
                                        <img src="<?php echo $filename; ?>" alt="<?= ucwords($DomesticHolidayData['destination_name']) ?>" class="img-fluid">
                                    <?php else : ?>
                                        <img class="img-fluid" src="<?php echo site_url('webroot/img/user.png') ?>" />
                                    <?php endif ?>
                                </div>
                                <div class="destination-overlay">
                                    <div class="destination-text">
                                        <h3><?= ucwords($DomesticHolidayData['destination_name']); ?></h3>
                                        <div>
                                            <h5><span>Starting Price</span>₹ <?= $DomesticHolidayData['starting_price'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>
    <?php endif ?>
<?php } ?>
<!------Holidays Destinations End------->
<!------International destinations------->
<?php if (whitelabel['holiday_module'] == 'active') { ?>
    <?php if (isset($InternationalHolidayDestinations) && is_array($InternationalHolidayDestinations) && !empty($InternationalHolidayDestinations) && count($InternationalHolidayDestinations) > 0) : ?>
        <section class="destinations">
            <div class="container">
                <div class="home_heading">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2>Trending International Holidays Destinations</h2>
                        <span><a href="<?php echo site_url('trending-International-holidays-destinations') ?>">View
                                All</a></span>
                    </div>
                    <p>
                        An international holiday not only offers a breath of fresh air but is also a great way to explore
                        different parts of the world and get a new…
                    </p>
                </div>
                <div class="tranding-destinations destination-slider owl-carousel owl-theme">
                    <?php foreach ($InternationalHolidayDestinations as $InternationalHolidayData) :  ?>
                        <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $InternationalHolidayData['destination_image']; ?>
                        <div class="destination-box">
                            <a href="<?php echo site_url('holiday/destinations/') . $InternationalHolidayData['destination_slug']; ?>">
                                <div class="destination-image">
                                    <?php if (UR_exists($filename)) : ?>
                                        <img src="<?php echo $filename; ?>" alt="<?= ucwords($InternationalHolidayData['destination_name']); ?>" class="img-fluid">
                                    <?php else : ?>
                                        <img class="img-fluid" src="<?php echo site_url('webroot/img/user.png') ?>" />
                                    <?php endif ?>
                                </div>
                                <div class="destination-overlay">
                                    <div class="destination-text">
                                        <h3><?= ucwords($InternationalHolidayData['destination_name']); ?></h3>
                                        <div>
                                            <h5><span>Starting Price</span>₹ <?= $InternationalHolidayData['starting_price'] ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>
    <?php endif ?>
<?php } ?>
<!------ International destinations end------->
<!------Trending Holidays Start----->
<?php if (whitelabel['holiday_module'] == 'active') { ?>
    <?php if (isset($holiday_list) && is_array($holiday_list) && !empty($holiday_list) && count($holiday_list) > 0) : ?>
        <section class="holidayTrendsWrapper sections-bg">
            <div class="container">
                <div class="home_heading">
                    <h2>Trending Holidays</h2>
                </div>
                <div class="row">
                    <?php foreach ($holiday_list as $super_key => $holiday) :
                        $itinerary = $holiday['itinerary'] ?>
                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                            <div class="holidayCardWrapper_box">
                                <div class="holidayCard-body">
                                    <h5 class="card-title">
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo ucwords($holiday['Name']); ?>"><?php echo ucwords($holiday['Name']); ?></a>
                                    </h5>
                                    <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $holiday['Image']; ?>
                                    <div class="makeRelative specialImage">
                                        <a href="<?php echo site_url('holiday/') . $holiday['Slug']; ?>">
                                            <div class="img-wrapper">
                                                <img src="<?php echo $filename ?>" alt="<?php echo ucwords($holiday['Name']); ?>" title="<?php echo ucwords($holiday['Name']); ?>" class="img-fluid">
                                                <?php if (in_array('Flight', $holiday['Includes'])) { ?> <?php } ?>
                                            </div>
                                            <span class="daysbadge" data-testid="days-badge"><?php echo count($itinerary) - 1; ?>N /
                                                <?php echo count($itinerary); ?>D</span>
                                        </a>
                                    </div>
                                    <div class="smileychoice">
                                        <div class="recommended under-checkbox">Recommended <span></span></div>
                                    </div>
                                </div>
                                <?php //pr($holiday['Includes']); 
                                ?>
                                <div class="holidayCarditem">
                                    <?php if ($holiday['Includes']) : ?>
                                        <ul class="holidayItineraryWrapper makeFlex spaceBetween itineraryList ">
                                            <?php foreach ($holiday['Includes'] as $include) : ?>
                                                <?php if ($include == 'Flight') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-plane-departure"></span>
                                                        <p>Flights</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Hotel') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-hotel"></span>
                                                        <p>Hotel</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Activities') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-binoculars"></span>
                                                        <p>Activities</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Bus') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-bus"></span>
                                                        <p>Bus</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Meal') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-cutlery"></span>
                                                        <p>Meal</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Transfer') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-car"></span>
                                                        <p>Transfer</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Cruise') { ?>
                                                    <li class="includesWrapper ">
                                                        <span class="fa-solid fa-ship"></span>
                                                        <p>Cruise</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Tourguide') { ?>
                                                    <li class="includesWrapper ">
                                                        <span>
                                                            <svg width="22px" height="22px" viewBox="0 -2 24 24" id="meteor-icon-kit__regular-guide" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 0C16.4477 0 16 0.44772 16 1V19C16 19.5523 16.4477 20 17 20C17.5523 20 18 19.5523 18 19V8.7808L23.2425 7.47014C23.6877 7.35885 24 6.95887 24 6.5V1C24 0.44772 23.5523 0 23 0H17zM22 5.71922L18 6.71922V2H22V5.71922z" fill="#000000"></path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 11C9.20914 11 11 9.2091 11 7C11 4.79086 9.20914 3 7 3C4.79086 3 3 4.79086 3 7C3 9.2091 4.79086 11 7 11zM7 9C8.10457 9 9 8.1046 9 7C9 5.89543 8.10457 5 7 5C5.89543 5 5 5.89543 5 7C5 8.1046 5.89543 9 7 9z" fill="#000000"></path>
                                                                <path d="M5 14C3.34315 14 2 15.3431 2 17V19C2 19.5523 1.55228 20 1 20C0.447715 20 0 19.5523 0 19V17C0 14.2386 2.23858 12 5 12H9C11.7614 12 14 14.2386 14 17V19C14 19.5523 13.5523 20 13 20C12.4477 20 12 19.5523 12 19V17C12 15.3431 10.6569 14 9 14H5z" fill="#000000"></path>
                                                            </svg>
                                                        </span>
                                                        <p>Tourguide</p>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($include == 'Sightseeing') { ?>
                                                    <li class="includesWrapper ">
                                                        <i class='fas'>&#xf59f;</i>
                                                        <p>Sightseeing</p>
                                                    </li>
                                                <?php } ?>
                                            <?php endforeach ?>
                                        </ul>
                                    <?php endif ?>
                                    <div class="destinationWrapper <?php echo "tts-holiday-" . $super_key ?> " data-testid="package-destination-wrapper">
                                        <?php if ($itinerary) :
                                            $cities = array_column($itinerary, 'city');
                                            $cityInc = holiday_city_iternary_stay($cities);
                                        ?>
                                            <?php foreach ($cityInc as $key => $item) : ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="redText"><?php echo isset($item['value']) ? $item['value'] : 1; ?>D</span>
                                                    <div class="destinationName"><span><?php echo $item['name'] ?></span><span>|</span>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="footer-new slashed-price">
                                    <h3 class="priceAmount">
                                        <?php if ($holiday['PriceOnCall'] == 1) { ?>
                                            Starting Price
                                            <span class="slashed"> ₹ <?php echo $holiday['ShowPrice']; ?></span>
                                            <span>Per Person</span>
                                        <?php } else { ?>
                                            <span>Price on request</span>
                                        <?php } ?>
                                    </h3>
                                    <div class="priceIcon">
                                        <div></div>
                                        <a href="<?php echo site_url('holiday/') . $holiday['Slug']; ?>" class="btn priceBtn">Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>
    <?php endif ?>
<?php } ?>
<!------Trending Holidays end----->

<!-----Visa Country Start----->
<?php if (whitelabel['visa_module'] == 'active') { ?>
    <?php if (isset($VisaCountryList) && is_array($VisaCountryList) && !empty($VisaCountryList) && count($VisaCountryList) > 0) : ?>
        <section class="countries-visa">
            <div class="container">
                <div class="home_heading">
                    <h2>Our Top Visa Destinations</h2>
                </div>
                <div id="visa-slider" class="owl-carousel owl-theme ">
                    <?php foreach ($VisaCountryList as $VisaCountryData) :
                        /*    pr($VisaCountryData); */
                    ?>
                        <?php $filename =  root_url . 'uploads/visa_documents/thumbnail/' . $VisaCountryData['country_image']; ?>
                        <div class="country-block">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
                                        <a href="<?php echo site_url('visa/search') . "?country-id=" . $VisaCountryData['id'] . "&visa-type-id=" . $VisaCountryData['VisaTypeId'] . "_" . $VisaCountryData['VisaTitle'] . "_" . $VisaCountryData['id'] . "&travellers=1&travel-date=" . date('d-M-Y'); ?>" class="lightbox-image">
                                            <?php if (UR_exists($filename)) : ?>
                                                <img src="<?php echo $filename; ?>" alt="<?php echo $VisaCountryData['country_name']; ?>" title="<?php echo $VisaCountryData['country_name']; ?>" class="img-fluid">
                                            <?php else : ?>
                                                <img class="img-fluid" src="<?php echo site_url('webroot/img/testi-bg.jpg') ?>" />
                                            <?php endif ?>
                                        </a>
                                    </figure>
                                </div>
                                <div class="content-box">
                                    <h5 class="title">
                                        <a href="<?php echo site_url('visa/search') . "?country-id=" . $VisaCountryData['id'] . "&visa-type-id=" . $VisaCountryData['VisaTypeId'] . "_" . $VisaCountryData['VisaTitle'] . "_" . $VisaCountryData['id'] . "&travellers=1&travel-date=" . date('d-M-Y'); ?>"><?= ucwords($VisaCountryData['country_name']); ?></a>
                                    </h5>
                                    <div class="text">
                                        <span class="d-block"><?php echo $VisaCountryData['processing_time']; ?> Working Days</span>
                                        <h6 class="d-block price"><span class="d-block fw-normal">Par Person</span> ₹ <?php echo $VisaCountryData['starting_price']; ?> </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div id="visa-slider" class="owl-carousel owl-theme d-none">
                    <?php foreach ($VisaCountryList as $VisaCountryData) :
                        /*    pr($VisaCountryData); */
                    ?>
                        <?php $filename =  root_url . 'uploads/visa_documents/thumbnail/' . $VisaCountryData['country_image']; ?>
                        <article>
                            <div class="post-img">
                                <a href="<?php echo site_url('visa/search') . "?country-id=" . $VisaCountryData['id'] . "&visa-type-id=" . $VisaCountryData['VisaTypeId'] . "_" . $VisaCountryData['VisaTitle'] . "_" . $VisaCountryData['id'] . "&travellers=1&travel-date=" . date('d-M-Y'); ?>">
                                    <?php if (UR_exists($filename)) : ?>
                                        <img src="<?php echo $filename; ?>" alt="<?php echo $VisaCountryData['country_name']; ?>" title="<?php echo $VisaCountryData['country_name']; ?>" class="img-fluid">
                                    <?php else : ?>
                                        <img class="img-fluid" src="<?php echo site_url('webroot/img/user.png') ?>" />
                                    <?php endif ?>
                                </a>
                            </div>
                            <h6 class="title"> <a href="<?php echo site_url('visa/search') . "?country-id=" . $VisaCountryData['id'] . "&visa-type-id=" . $VisaCountryData['VisaTypeId'] . "_" . $VisaCountryData['VisaTitle'] . "_" . $VisaCountryData['id'] . "&travellers=1&travel-date=" . date('d-M-Y'); ?>"><?= ucwords($VisaCountryData['country_name']); ?></a>
                            </h6>
                            <p class="timetext">Visa Type : <?php echo $VisaCountryData['VisaTitle'] ?></p>
                            <p class="daytext"><?php echo $VisaCountryData['processing_time']; ?> Working Days</p>
                            <p class="pricetext">₹ <?php echo $VisaCountryData['starting_price']; ?> <span>Par Person</span></p>
                        </article>
                    <?php endforeach ?>
                </div>
            </div>
        </section>
    <?php endif ?>
<?php } ?>
<!-----Visa Country End----->
<!-------why choose us ------->
<section class="why-choose-us">
    <div class="container">
        <div class="home_heading">
            <h2>Why Choose Us</h2>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="whychooseus-box">
                    <div class="icon">
                        <img src="<?php echo site_url('webroot/img/Easy-booking.png'); ?>" alt="Easy booking" class="img-fluid">
                    </div>
                    <h3>Easy Booking</h3>
                    <p>We offer easy and convenient flight bookings with attractive offers</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="whychooseus-box">
                    <div class="icon icon2">
                        <img src="<?php echo site_url('webroot/img/low-price.png'); ?>" alt="low price" class="img-fluid">
                    </div>
                    <h3>Lowest Price</h3>
                    <p>We ensure low rates and hotel reservation, holidays packages and on flight tickets</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="whychooseus-box">
                    <div class="icon icon3">
                        <img src="<?php echo site_url('webroot/img/Exciting-Deals.png'); ?>" alt="Exciting Deals" class="img-fluid">
                    </div>
                    <h3>Exciting Deals</h3>
                    <p>Enjoy exciting deals on flights, hotels, car rental and tour packges</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="whychooseus-box">
                    <div class="icon icon4">
                        <img src="<?php echo site_url('webroot/img/24support.png'); ?>" alt="24support" class="img-fluid">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Get assistence 24/7 on way kind of travel related quary. we are happy to assist you.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-------why choose us end------->

<!------Our happy clients Start----->
<?php if (isset($feedbac_list) && is_array($feedbac_list) && !empty($feedbac_list) &&  count($feedbac_list) > 0) : ?>
    <section class="testimonials">
        <div class="container">
            <div class="home_heading">
                <h2>Our happy clients <span class="float-end"><a href="<?php echo site_url('feedback'); ?>">View All</a></span></h2>
            </div>
            <div class="row gy-4">
                <?php foreach ($feedbac_list as $dataItem) : ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <?php $filename =  root_url . 'uploads/feedback/' . $dataItem['image']; ?>
                        <div class="testimonial-wrap">
                            <div class="testimonial-item">
                                <div class="d-flex align-items-center">
                                    <?php if (UR_exists($filename)) : ?>
                                        <img class="testimonial-img flex-shrink-0" alt="<?php echo $dataItem['name'] ?>" src="<?php echo $filename; ?>" />
                                    <?php else : ?>
                                        <img class="testimonial-img flex-shrink-0" src="<?php echo site_url('webroot/img/review_clients.jpeg') ?>" />
                                    <?php endif ?>
                                    <div>
                                        <h3><?php echo $dataItem['name'] ?></h3>
                                        <div class="stars">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <p>
                                    <i class="fa-solid fa-quote-left"></i>
                                    <?php echo (strlen(strip_tags($dataItem['description'])) > 200) ? substr(strip_tags($dataItem['description']), 0, 200) . '....' : strip_tags($dataItem['description']) ?>
                                    <i class="fa-solid fa-quote-right"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </section>
<?php endif ?>
<!------Our happy clients end------->
<!---------clients start------->
<?php if (0) { ?>
    <section class="clients sections-bg">
        <div class="container">
            <div class="home_heading">
                <h2>Trusted By over 1000+ Companies</h2>
            </div>
            <div class="clients-slider owl-carousel owl-theme">
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/1.png'); ?>" class="img-fluid" alt="1"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/2.png'); ?>" class="img-fluid" alt="2"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/3.png'); ?>" class="img-fluid" alt="3"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/4.png'); ?>" class="img-fluid" alt="4"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/5.png'); ?>" class="img-fluid" alt="5"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/6.png'); ?>" class="img-fluid" alt="6"> </div>
                <div class="item"> <img src="<?php echo site_url('webroot/img/clients/7.png'); ?>" class="img-fluid" alt="7"> </div>
            </div>
        </div>
    </section>
<?php  } ?>
<!---------clients end------->
<!------Blog List Start----->
<?php if (isset($blog_list) && is_array($blog_list) && !empty($blog_list) && count($blog_list) > 0) : ?>
    <section class="recent-posts">
        <div class="container">
            <div class="home_heading">
                <div class="d-flex align-items-center justify-content-between">
                    <h2>Recent Blog Post </h2>
                    <span><a href="<?php echo site_url('blog'); ?>">View All</a> </span>
                </div>
            </div>
            <div class="row gy-4">
                <?php foreach ($blog_list as $blog) { ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <a href="<?php echo site_url('blog'); ?>/<?= $blog['post_slug']; ?>">
                            <article>
                                <div class="post-img">
                                    <img src="<?php echo root_url . 'uploads/blog/thumbnail/' . $blog['post_images'] ?>" alt="<?= $blog['post_title'] ?>" class="img-fluid">
                                </div>
                                <h2 class="title">
                                    <a href="<?php echo site_url('blog'); ?>/<?= $blog['post_slug']; ?>"><?= $blog['post_title'] ?></a>
                                </h2>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="post-meta">
                                        <p class="post-date"> <span><i class="fa fa-calendar"></i>
                                                <?= date('d M Y', $blog['created']) ?></span> <span class="post-author-img ms-2"> <i class="fa fa-user"></i>
                                                <?= $blog['posted_by'] ?></span></p>
                                    </div>
                                </div>
                                <div class="desc">
                                    <?php echo (strlen(strip_tags($blog['post_desc'])) > 160) ? substr(strip_tags($blog['post_desc']), 0, 160) . '....' : strip_tags($blog['post_desc']) ?>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php endif ?>
<!------Blog List End----->



<?php echo view('Views/template/mobile-app'); ?>