<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Contacts</h2>
         <ol>
            <li>
               <a href="<?php echo site_url(); ?>">Home</a>
            </li>
            <li>Contacts</li>
         </ol>
      </div>
   </div>
</section>


<section class="contactus">
   <div class="container ">
      <div class="row g-4">
         <div class="col-lg-6">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p><?php //echo web_partner_details['address']; ?>
                  <?php //echo web_partner_details['city']; ?>
                  <?php //echo web_partner_details['state']; ?>
                  <?php //echo web_partner_details['pincode']; ?>
                  <?php //echo web_partner_details['country']; ?>
				  
				
                   Corporate Office: 304, World Trade Tower, Sec 16, Noida, <br>Uttar Pradesh - 201301
                            <br><br>
                    Reg Office: 44, 2nd Floor, Regal Building, Connaught Place, <br>New Delhi - 110001
				
				</p>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="info-box">
               <i class="fa fa-envelope"></i>
               <h3>Email Us</h3>
               <p><a class="nav-link" href="mailto:<?php echo web_partner_details['support_email'] ?>"><?php echo web_partner_details['support_email'] ?>
                  </a></p>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="info-box">
               <i class="fa fa-phone"></i>
               <h3>Call Us</h3>
               <p><a class="nav-link" aria-current="page" href="tel:<?php echo web_partner_details['support_no'] ?>"><?php echo web_partner_details['support_no'] ?>
                  </a> </p>
               <? if (web_partner_details['tollfree_no']) : ?>
                  <p>Toll Free:<?= web_partner_details['tollfree_no']; ?> </p>
               <? endif; ?>
            </div>
         </div>
         <div class="col-lg-6 ">
            <iframe src="<?php echo web_partner_details['google_map'] ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
         </div>
         <div class="col-lg-6">
            <form tts-action="tts__contact-us-save" method="post" role="form" tts-form="true" class="php-email-form">
               <div class="row">
                  <div class="col-md-6 form-group">
                     <input type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                     <input type="email" class="form-control" name="email" id="email" placeholder="Your Email">
                  </div>
               </div>
               <div class="form-group mt-3">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
               </div>
               <input type="hidden" name="type" value="contact_us">
               <div class="form-group mt-3">
                  <textarea class="form-control" name="message" rows="5" placeholder="Message"></textarea>
               </div>

               <div class="row">
                  <div class="col-md-6 form-group mt-3">
                     <input type="text" name="captchagenerate" class="form-control" id="captchagenerate" placeholder="Enter Captcha">
                  </div>
                  <div class="col-md-6 form-group mt-3">
                     <img class="img-fluid" src="<?= site_url('captchagenerate') . '?rand=' . rand() ?>" data-captcha-image="true" alt="CAPTCHA Image">
                  </div>
                  <div class="col-md-12 mt-3 text-center">
                     <label class="form-check-label text-muted fw-normal">
                        Unable to read the code? <a href="javascript: refreshCaptcha();"><strong class="text-primary">Click here </strong></a> to refresh.
                     </label>
                  </div>
               </div>  
               <div class="text-center mt-3"><button type="submit">Send Message</button></div>
            </form>
         </div>
      </div>
   </div>
</section>

<script> 
   function refreshCaptcha() {
    const captchaImage = document.querySelector('[data-captcha-image="true"]');
    if (captchaImage) {
        captchaImage.src = '<?= site_url('captchagenerate') ?>?rand=' + Math.random();
    }
}
</script>