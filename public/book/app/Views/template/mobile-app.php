<?php if (web_partner_details['ios_app_url'] != NULL || web_partner_details['android_app_url'] != "") : ?>
   <!------------mobile----app-------start------
   <section class="mobile-app ">
      <div class="container">
         <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
               <div class="mobileapp-content ">
                  <h3>Get more out of <?php echo web_partner_details['company_name']; ?> with our mobile app</h3>
                  <p> Download the <?php echo web_partner_details['company_name']; ?> mobile app for one-touch access to your next travel adventure. With the <?php echo web_partner_details['company_name']; ?> mobile app you’ll get access to hidden features and special offers. </p>
                  <ul>
                     <li><i class="fa-solid fa-circle-check"></i> Download boarding passes</li>
                     <li><i class="fa-solid fa-circle-check"></i> Get exclusive offers and prices</li>
                     <li><i class="fa-solid fa-circle-check"></i> One click bookings</li>
                     <li><i class="fa-solid fa-circle-check"></i> Trip notifications</li>
                  </ul>
                  <div class="AppButton">
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
            <div class="col-lg-6 d-lg-flex d-none align-items-center mt-3 mt-lg-0">
               <div class="mobileapp-item">
                  <div class="mobileapp-img"><img src="<?php echo site_url('webroot/img/mobile-app (1).png') ?>" alt="mobile-app.png" class="img-fluid"></div>
                  <div class="scnercode">
                     <img src="webroot/img/getCode.svg" alt="mobile-app.png" class="img-fluid scnrqr">
                     <p>Scan to download</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   ----------mobile----app--------end------->
   <section class="download-area">
      <div class="container">
         <div class="row align-items-center justify-content-between">
            <div class="col-lg-5">
               <div class="download-img">
                  <img src="<?php echo site_url('webroot/img/app-img.svg') ?>" alt="app-img">
               </div>
            </div>
            <div class="col-lg-6">
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