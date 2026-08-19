<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2><?php echo ucfirst($title) .  " Review"; ?></h2>
         <ol>
            <li>
               <a href="<?php echo site_url(); ?>">Home</a>
            </li>
            <li><?php echo ucfirst($title) .  " Review"; ?></li>
         </ol>
      </div>
   </div>
</section>

<section class="contactus">
   <div class="container ">
      <div class="row g-4">
         <div class="col-lg-12">
            <form action="<?php echo site_url('reviewSave'); ?>" method="post" role="form" tts-form="true"
               class="php-email-form">
               <div class="row">
                  <div class="col-md-6 form-group">
                     <input type="text" name="bookingReferenceNumber" class="form-control" id="bookingReferenceNumber" placeholder="Your Booking Reference Number" value="<?= isset($details) ? $details[0] : "" ?>" readonly>
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                     <input type="text" class="form-control" name="service" id="service" placeholder="Service" value="<?= isset($details) ? ucfirst($details[1]) : "" ?>" readonly>
                  </div>
               </div>
               <div class="form-group mt-3">
                  <textarea class="form-control" name="message" rows="5" placeholder="Review"></textarea>
               </div>
               <div class="text-center mt-3"><button type="submit">Submit Review</button></div>
            </form>
         </div>
      </div>
   </div>
</section>