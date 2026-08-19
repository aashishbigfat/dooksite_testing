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
if ($controller == "VisaBooking") {
    $controller = "Visa";
}
if ($controller == "CarBooking") {
    $controller = "Car";
}
if ($controller == "BikeTour") {
    $controller = "Biketour";
}
if ($controller == "HajjBooking") {
    $controller = "Hajj";
}
if ($controller == "UmrahBooking") {
    $controller = "Umrah";
}



?>

<style>
    .headingblock {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 10px;
    }

    .headingblock .wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .headingblock .heading-one {
        font-size: 25px;
        font-weight: 100;
        color: #000;
        margin: 0px;
        text-align: center;
    }


    .headingblock .wrap .nav-tabs {
        border: none;
        gap: 10px;
    }

    .headingblock .wrap .nav-tabs .nav-item .nav-link {
        font-size: 15px;
        padding: 4px 0;
        font-weight: 500;
        text-transform: capitalize;
        color: #151515;
        border-bottom: 2px solid transparent;
        border-color: transparent;
        transition: all .5s ease;
    }

    .headingblock .wrap .nav-tabs .nav-item .nav-link.active,
    .headingblock .wrap .nav-tabs .nav-item .nav-link:hover {
        color: var(--tts-buttton-bg);
        border-bottom: 2px solid var(--tts-buttton-bg);
        background-color: transparent;
    }

    .headingblock .right {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .headingblock .custom-nav {
        margin-left: 30px;
    }

    .headingblock .link {
        font-size: 15px;
        color: var(--tts-buttton-bg);
        text-decoration: underline;
        font-weight: 500;
        white-space: nowrap;
    }


    .offer-slider.owl-carousel.owl-drag .owl-item {
        height: 250px;
        margin: 10px 0;
    }

    .item-box {
        background: #fff;
        border-radius: 7px;
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, .1);
        height: 250px;
        transition: all .5s ease-in;
        width: calc(100% - 45px);
        margin-left: 45px;
        border: 1px solid rgba(156, 170, 179, .28);
        position: relative;
    }

    .item-box .offer-box:hover .imgsection img {
        transform: scale(1.2);
    }

    .item-box .offer-box {
        height: 100%;
        display: flex;
        align-items: center;
        cursor: pointer;
    }


    .item-box .offer-box .imgsection {
        height: 220px;
        width: 125px;
        min-width: 125px;
        overflow: hidden;
        pointer-events: all !important;
        margin: 0px 0 0 -45px;
        border-radius: 7px;
        border: 1px solid #d6dfe4;
    }

    .item-box .offer-box .imgsection img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1);
        transition: all .5s linear;
        background: #ddd;
        display: block;
    }

    .item-box .offer-box .offer-content {
        padding: 25px 15px;
        width: calc(100% - 80px);
        max-width: 100%;
        height: 100%;
    }

    .item-box .offer-box .offer-content .offer-label {
        display: flex;
        align-items: center;
        justify-content: end;
        margin-bottom: 20px;
    }

    .item-box .offer-box .offer-content .offer-label p {
        color: #6d6c6c;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
        background: #f5f6f8;
        border: 1px solid #e4e4e4;
        border-radius: 5px;
        padding: 0 10px;
        height: 20px;
        line-height: 20px;
        margin: 0;
    }

    .item-box .offer-box .offer-content .offer-height {
        height: calc(100% - 0px);
        display: flex;
        align-items: start;
        justify-content: space-between;
        flex-direction: column;
    }


    .item-box .offer-box .offer-content h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 10px;
        color: #000;
        width: 100%;
    }

    .item-box .offer-box .offer-content p {
        font-size: 13px;
        color: #525252;
        font-weight: 500;
        line-height: 20px;
    }

    .item-box .offer-box .offer-content .bookingsection {
        display: flex;
        align-items: center;
        justify-content: end;
        width: 100%;
    }

    .item-box .offer-box .offer-content .bookingsection button {
        background: var(--tts-buttton-bg);
        border: none;
        padding: 8px 15px;
        color: var(--tts-buttton-txt);
        font-size: 14px;
        border-radius: 5px;

    }

    .headingblock .right .custom-nav button {
        background: none;
        border: none;
        cursor: pointer;
        width: 25px;
        height: 25px;
        background: var(--tts-buttton-bg);
        color: var(--tts-buttton-txt);
        border-radius: 50%;
    }

    @media (max-width: 991px) {

        .offer_page .headingblock,
        .offer_page .headingblock .wrap {
            display: block;
        }
    }
</style>
<!------Deal and offer----->

<?php if (isset($offers_list) && !empty($offers_list)) {

    // if ($controller !== "Home") {

    //     $offers_listData =  $offers_list;

    //     unset($offers_list);

    //     $offers_list = array();

    //     $offers_list[strtolower($controller)] = isset($offers_listData[strtolower($controller)]) ? $offers_listData[strtolower($controller)] : array();

    //     if (empty($offers_list[strtolower($controller)])) {

    //         $offers_list = array();
    //     }
    // } 
?>

    <!-- <?php if (isset($offers_list) && !empty($offers_list)) {  ?>


         <section class="offer-area">

             <div class="container">

                 <div class="section-title d-flex algn-items-center justify-content-between">

                     <h2 class="m-0">Deals and Offers </h2>

                     <a href="<?php echo  site_url('offers-list'); ?>">View All</a>

                 </div>

                 <div class="offer-slider owl-carousel owl-theme">

                     <?php $count  =  1;

                        foreach ($offers_list as $servicekey => $offers_data) {   ?>

                         <?php

                            foreach ($offers_data as $offer) {

                            ?>

                             <div class="item-box">

                                 <div class="offer-box">

                                     <div class="imgsection">

                                         <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" alt=" <?php echo $offer['title'] ?>" class="img-fluid">

                                     </div>

                                     <div class="offer-content">

                                         <div class="offer-label">

                                           <p>hotdeal</p>

                                        </div> 

                                         <div class="offer-height">

                                             <div class="offeritemDesc">

                                                 <h3> <?php echo $offer['title'] ?> </h3>

                                                 <p><?php echo $offer['description'] ?></p>

                                             </div>

                                             <div class="bookingsection">

                                                 <a href="<?php echo  $offer['url'] != null && $offer['url'] != '' ? $offer['url'] : 'javascript:void(0);'; ?>" class="offer-box"> Book Now</a>

                                             </div>

                                         </div>

                                     </div>

                                 </div>

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

     <?php } ?> -->
    <section class="offer_page">
        <div class="container">
            <div class="headingblock">
                <div class="wrap">
                    <h2 class="heading-one">Deals and <span> Offers</span></h2>
                    <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                        <?php foreach ($offers_list as $key => $offers) { ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $controller == ucfirst($key) ? 'show active' : '' ?>"
                                    id="pills-<?= $key ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?= $key ?>" type="button"
                                    role="tab" aria-controls="pills-<?= $key ?>" aria-selected="true">
                                    <?= ($key == 'home') ? "Best offer" : ucfirst($key); ?>
                                </button>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="right">
                    <a class="link dealssection" href="<?= site_url('offers-list'); ?>" target="_blank">View All
                        Deals</a>
                    <div class="custom-nav">
                        <button class="custom-prev"><i class="fas fa-chevron-left"></i></button>
                        <button class="custom-next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <?php foreach ($offers_list as $key => $offers) { ?>
                    <div class="tab-pane fade <?= ($controller == ucfirst($key)) ? 'show active' : '' ?>" id="pills-<?= $key ?>"
                        role="tabpanel" aria-labelledby="pills-bestoffer-tab" tabindex="0">
                        <div class="offer-slider owl-carousel owl-theme">
                            <?php foreach ($offers as $offer) { ?>
                                <a target="_blank" href="<?= $offer['url']; ?>">
                                    <div class="item-box">
                                        <div class="offer-box">
                                            <div class="imgsection">
                                                <img src="<?= root_url . "uploads/offers/" . $offer['image'] ?>" alt="1710484620_asia1.jpg"
                                                    class="card-img">
                                            </div>
                                            <div class="offer-content">
                                                <!-- <div class="offer-label">
                           <p>hotdeal</p>
                           </div> -->
                                                <div class="offer-height">
                                                    <div class="offeritemDesc">
                                                        <h3><?= $offer['title'] ?></h3>
                                                        <p><?= $offer['description'] ?></p>
                                                    </div>
                                                    <div class="bookingsection">
                                                        <button type="button" target="_blank">Book Now</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>




<?php } ?>

<!------Deal and offer end----->



<!------Flight Routes start------->

<?php if (whitelabel['flight_module'] == 'active') { ?>

    <?php if (isset($top_routes_list) && is_array($top_routes_list) && !empty($top_routes_list) && count($top_routes_list) > 0) : ?>

        <section class="popular_destination sections-bg">

            <div class="container">

                <div class="section-title">

                    <h2>Popular Flight Routes</h2>

                </div>

                <div class="owl-carousel owl-theme top_routes">

                    <?php foreach ($top_routes_list as $routes_list) :

                        $uri = site_url('flight/search') . '?' . http_build_query($routes_list['url']);

                    ?>

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

                                        <span class="rs"> <?= $routes_list['CurrencySymbol'] . " " ?> </span> <?php echo $routes_list['price']; ?>

                                    </p>

                                </div>

                            <?php endif ?>

                        </a>



                    <?php endforeach; ?>

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

                <div class="section-title">
                    <h2>Trending Hotels</h2>

                </div>

            </div>

            <div class="container">

                <div class="owl-carousel owl-theme hotel-slider">

                    <?php foreach ($trending_hotel as $hotel) : ?>
                        <?php
                        $hotelurl = site_url('hotel/hotel-result') . '?' . $hotel['url'];
                        // pr($hotelurl);
                        // die;
                        ?>
                        <div href="<?php echo $hotelurl ?>" target="_blank" class="hotel-item">

                            <div class="hotel-img">

                                <img src="<?php echo root_url . 'uploads/hotel/thumbnail/' . $hotel['hotel_images'] ?>" alt="<?= $hotel['hotel_name'] ?>">

                                <!-- <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a> -->

                            </div>

                            <div class="hotel-content">

                                <h4 class="hotel-title"><a href="<?php echo $hotelurl ?>"><?= $hotel['hotel_name'] ?></a></h4>

                                <!-- <p><i class="fa-solid fa-location-dot"></i> 25/B Milford Road, New York</p> -->

                                <div class="hotel-rate">

                                    <span class="badge">

                                        <?php for ($i = 0; $i < $hotel['hotel_star_rating']; $i++) : ?>

                                            <i class="fa-solid fa-star"></i>

                                        <?php endfor ?>

                                    </span>

                                    <span class="hotel-rate-review"><?= $hotel['review_rating'] ?>/5</span>

                                    <span><?= $hotel['review_rating'] ?> ratings</span>

                                    <span class="hotel-rate-type">Excellent</span>

                                </div>

                                <a href="<?php echo $hotelurl ?>" class="stretched-link"></a>

                                <!-- <div class="hotel-bottom">

                                    <div class="hotel-price">

                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per Night</span></span>

                                    </div>

                                    <div class="hotel-text-btn">

                                        <a href="<?php echo site_url('/'); ?>">See Details <i class="fas fa-arrow-right"></i></a>

                                    </div>

                                </div> -->

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

    <?php endif ?>

<?php } ?>

<!-- Trending Hotels End -->


<!------Our happy clients Start----->

<?php if (isset($feedbac_list) && is_array($feedbac_list) && !empty($feedbac_list) &&  count($feedbac_list) > 0) : ?>

    <section class="testimonials">

        <div class="container">

            <div class="section-title d-flex align-items-center justify-content-between">

                <h2>Our happy clients </h2>

                <a href="<?php echo site_url('feedback'); ?>">View All</a>

            </div>

            <div class="owl-carousel owl-theme testimonial-slider">

                <?php foreach ($feedbac_list as $dataItem) : ?>



                    <?php $filename =  root_url . 'uploads/feedback/' . $dataItem['image']; ?>

                    <div class="testimonial-wrap">

                        <div class="testimonial-item">

                            <p>

                                <i class="fa-solid fa-quote-left"></i>

                                <?php echo (strlen(strip_tags($dataItem['description'])) > 200) ? substr(strip_tags($dataItem['description']), 0, 200) . '....' : strip_tags($dataItem['description']) ?>

                                <i class="fa-solid fa-quote-right"></i>

                            </p>

                            <div class="d-md-flex align-items-center justify-content-between">

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



                            </div>

                        </div>

                    </div>



                <?php endforeach ?>

            </div>

        </div>

    </section>

<?php endif ?>

<!------Our happy clients end------->

<!-------why choose us ------->

<section class="feature-area">

    <div class="container">

        <div class="feature-wrapper">

            <div class="row">

                <div class="col-md-6 col-lg-3">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <img src="<?php echo site_url('webroot/img/Easy-booking.png'); ?>" alt="Easy booking" class="img-fluid">

                        </div>

                        <h4 class="feature-title">Easy Booking</h4>

                        <p>We offer easy and convenient flight bookings with attractive offers</p>

                    </div>

                </div>

                <div class="col-md-6 col-lg-3">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <img src="<?php echo site_url('webroot/img/low-price.png'); ?>" alt="low price" class="img-fluid">

                        </div>

                        <h4 class="feature-title">Lowest Price</h4>

                        <p>We ensure low rates and hotel reservation, holidays packages and on flight tickets</p>

                    </div>

                </div>

                <div class="col-md-6 col-lg-3">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <img src="<?php echo site_url('webroot/img/Exciting-Deals.png'); ?>" alt="Exciting Deals" class="img-fluid">

                        </div>

                        <h4 class="feature-title">Exciting Deals</h4>

                        <p>Enjoy exciting deals on flights, hotels, car rental and tour packges</p>

                    </div>

                </div>

                <div class="col-md-6 col-lg-3">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <img src="<?php echo site_url('webroot/img/24support.png'); ?>" alt="24support" class="img-fluid">

                        </div>

                        <h4 class="feature-title">24/7 Support</h4>

                        <p>Get assistence 24/7 on way kind of travel related quary. we are happy to assist you.</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-------why choose us end------->



<!---------clients start------->

<?php if (0) { ?>

    <section class="clients sections-bg">

        <div class="container">

            <div class="section-title">

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

            <div class="section-title d-flex align-items-center justify-content-between">

                <div>

                    <h2>Recent Blog Post </h2>

                </div>

                <a href="<?php echo site_url('blog'); ?>">View All</a>

            </div>

            <div class="row gy-4">

                <?php foreach ($blog_list as $blog) { ?>

                    <div class="col-lg-4 col-md-6 col-12">

                        <div class="post-item position-relative h-100">

                            <div class="post-img position-relative overflow-hidden">

                                <img src="<?php echo root_url . 'uploads/blog/thumbnail/' . $blog['post_images'] ?>" alt="<?= $blog['post_title'] ?>" class="img-fluid">

                                <span class="post-date"><?= date('d M Y', $blog['created']) ?></span>

                            </div>

                            <div class="post-content d-flex flex-column">

                                <h3 class="post-title"><?= $blog['post_title'] ?></h3>

                                <div class="meta d-flex align-items-center">

                                    <div class="d-flex align-items-center">

                                        <i class="fa-solid fa-person"></i> <span class="ps-2"><?= $blog['posted_by'] ?></span>

                                    </div>

                                    <span class="px-3 text-black-50">/</span>

                                    <div class="d-flex align-items-center">

                                        <i class="fa-solid fa-calendar-days"></i> <span class="ps-2"><?= date('d M Y', $blog['created']) ?></span>

                                    </div>

                                </div>

                                <hr>

                                <p>

                                    <?php echo (strlen(strip_tags($blog['post_desc'])) > 160) ? substr(strip_tags($blog['post_desc']), 0, 160) . '....' : strip_tags($blog['post_desc']) ?>

                                </p>

                                <a class="readmore stretched-link" href="<?php echo site_url('blog'); ?>/<?= $blog['post_slug']; ?>"><span>Read More</span><i class="fa-solid fa-right-long"></i></a>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

    </section>

<?php endif ?>

<!------Blog List End----->

<!---mobile app--->

<?php if (web_partner_details['ios_app_url'] != NULL || web_partner_details['android_app_url'] != "") : ?>

    <section class="download-area">

        <div class="container">

            <div class="row align-items-center justify-content-between">

                <div class="col-lg-5 col-md-5">

                    <div class="download-img">

                        <img src="<?php echo site_url('webroot/img/app-img.svg') ?>" alt="app-img">

                    </div>

                </div>

                <div class="col-lg-6 col-md-7">

                    <div class="download-content">

                        <div class="section-title pb-0">

                            <span class="site-title-tagline">Download</span>

                            <h2 class="site-title">Get more out of <?php echo web_partner_details['company_name']; ?> with our mobile app</h2>

                            <p> Download the <?php echo web_partner_details['company_name']; ?> mobile app for one-touch access to your next travel adventure. With the <?php echo web_partner_details['company_name']; ?> mobile app you’ll get access to hidden features and special offers. </p>

                            <ul class="download-feature">

                                <li><i class="fa-solid fa-check"></i> Download boarding passes</li>

                                <li><i class="fa-solid fa-check"></i> Get exclusive offers and prices</li>

                                <li><i class="fa-solid fa-check"></i> One click bookings</li>

                                <li><i class="fa-solid fa-check"></i> Trip notifications</li>

                            </ul>

                            <div class="download-link">

                                <? if (web_partner_details['ios_app_url']) : ?>

                                    <a href="<?php echo web_partner_details['ios_app_url']; ?>" target="blank">

                                        <img src="<?php echo site_url('webroot/img/AppStoreButton.webp'); ?>" alt="App Store" class="img-fluid">

                                    </a>

                                <? endif; ?>

                                <? if (web_partner_details['android_app_url']) : ?>

                                    <a href="<?php echo web_partner_details['android_app_url']; ?>" class="ms-3" target="blank">

                                        <img src="<?php echo site_url('webroot/img/GooglePlayButton.webp'); ?>" alt="Google Play" class="img-fluid">

                                    </a>

                                <? endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

<?php endif ?>