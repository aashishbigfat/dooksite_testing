<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Feedback</h2>
         <ol>
            <li>
               <a href="<?php echo site_url(); ?>">Home</a>
            </li>
            <li>Feedback</li>
         </ol>
      </div>
   </div>
</section>


<section class="feedback-section">
   <div class="container">
      <div class="row justify-content-end">
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="feedback-img">
               <img src="<?php echo site_url('webroot/img/feedback.svg'); ?>" class="img-fluid">
            </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="feedback-box">
               <form action="<?php echo site_url('feedback/add-feedback-data'); ?>" method="post" tts-form="true" role="form" class="contact-form-area" enctype='multipart/form-data'>
                  <h3 class="text-dark"> Feedback Form</h3>
                  <p class="text-dark">Share your feedback with us by simply filling up the below form and clicking on “Send Feedback” Option.</p>
                  <div class="row">
                     <div class="col-md-6 form-group">
                        <select name="feedback_title" class="form-select">
                           <option value="Feedback">Feedback</option>
                           <option value="Suggestion">Suggestion</option>
                           <option value="Compliment">Compliment</option>
                           <option value="Other">Other</option>
                        </select>
                     </div>
                     <div class="col-md-6 form-group mt-3 mt-md-0">
                        <input type="text" class="form-control" placeholder="Name Of Individual/Company:*" name="name" onkeypress="return isalpha(event)">
                     </div>
                     <div class="col-md-6 form-group mt-3 ">
                        <input type="email" class="form-control" placeholder="Email Address:*" name="email">
                     </div>
                     <div class="col-md-6 form-group mt-3 ">
                        <input type="email" class="form-control" placeholder="Other Email Address(If Any)" name="optional_email">
                     </div>
                     <div class="col-md-6 form-group mt-3 ">
                        <input type="number" class="form-control" data-validation="required" placeholder="Telephone Number:*" name="phone">
                     </div>
                     <div class="col-md-6 form-group mt-3 ">
                        <input type="file" class="form-control" data-validation="required" placeholder="Telephone Number:*" name="image">
                     </div>
                     <div class="form-group mt-3">
                        <textarea class="form-control" name="description" rows="5" placeholder="Message"></textarea>
                     </div>
                  </div>
                  <div class="text-end mt-3"><button type="submit" class="feedback-btn">Send Message</button></div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="testimonials">
   <div class="container">
      <div class="home_heading">
         <h2>Our happy clients <span class="float-end"><a href="<?php echo site_url('feedback'); ?>">View All</a></span></h2>
      </div>
      <div class="row gy-4">
         <?php foreach ($feedbac_list as $dataItem) : ?>
            <div class="col-lg-3 col-md-4 col-12">
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   function isalpha(event) {
      var inputValue = event.which;
      if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
         swal.fire({
            title: '<span style  =  "font-size:14px; color:red">Only Accept Letters And white Space</span>',
            type: 'info',
            animation: true,
            customClass: {
               popup: 'animated tada'
            }
         });
         return false;
      } else if ((inputValue == 91 && inputValue <= 96)) {

         swal.fire({
            title: '<span style  =  "font-size:14px; color:red">Only Accept Letters And white Space</span>',
            type: 'info',
            animation: true,
            customClass: {
               popup: 'animated tada'
            }
         });
         return false;
      }
      return true;
   }
</script>