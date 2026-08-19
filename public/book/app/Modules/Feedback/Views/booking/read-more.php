<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Feedback Form</h2>
         <ol>
            <li>
               <a href="<?php echo site_url('/');?>">Home</a>
            </li>
            <li>Feedback Form</li>
         </ol>
      </div>
   </div>
</section>
<section class="contact_client">
   <div class="container">
      <div class="row">
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="contact_detail_text">
               <h2>Feedback Form</h2>
               <p>
                  MMR Trip Holidays always values their Guests as well as their feedback. Your every comment will be taken in an optimistic way and will be implemented throughout our working system. So come share your fruitful experiences with us and we ll try our best to make your next tour Best of the Best!
               </p>
               <p>
                  Your comment and suggestions will allow us to improve our standards and also enable us to only meet, but exceed your expectation.

               </p>
            </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="contact-form"> 
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
                  <div class="text-end"><button type="submit">Send Message</button></div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>


<section>
   <div class="container">
      <div class="section-title">
         <h2>MMR Trip Client's Reviews -#1 Holiday Booking Service </h2>
      </div>

      <?php foreach ($feedbac_list as $key => $dataItem) : ?>
         <?php if ($key % 2 == 0) : ?>
            <div class="row gx-lg-0 mb-3 justify-content-center">
               <div class="col-md-2">
                  <?php $filename =  root_url . 'uploads/feedback/' . $dataItem['image']; ?>

                  <div class="feedback-img p-3  text-center">
                     <?php if (UR_exists($filename)) : ?>
                        <img src="<?php echo $filename; ?>" alt="<?php echo $dataItem['image']; ?>" class="img-fluid" style="max-height: 170px;">
                     <?php else : ?>
                        <img src="<?php echo site_url('webroot/img/user.png') ?>" alt="" class="img-fluid" style="max-height: 170px;">
                     <?php endif ?>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class=" bg_white main-wreap p-3">
                     <p>
                        Dear <?php echo $dataItem['name'] ?>,

                        <?php echo (strlen(strip_tags($dataItem['description'])) > 3000) ? substr(strip_tags($dataItem['description']), 0, 3000) . '....' : strip_tags($dataItem['description']) ?>
                     </p>
                     <h5 class="m-0"> <?php echo $dataItem['name'] ?>,<samp><?php echo $dataItem['feedback_title'] ?></samp></h5>
                  </div>
               </div>
            </div>
         <?php else : ?>
            <div class="row gx-lg-0 mb-3 justify-content-center">
               <div class="col-md-7">
                  <div class=" bg_white main-wreap p-3">
                     <p>
                        Dear <?php echo $dataItem['name'] ?>,
                        <?php echo (strlen(strip_tags($dataItem['description'])) > 3000) ? substr(strip_tags($dataItem['description']), 0, 3000) . '....' : strip_tags($dataItem['description']) ?>
                     </p>
                     <h5 class="m-0"> <?php echo $dataItem['name'] ?>,<samp><?php echo $dataItem['feedback_title'] ?></samp></h5>
                  </div>
               </div>
               <?php $filename =  root_url . 'uploads/feedback/' . $dataItem['image']; ?>
               <div class="col-md-2">
                  <div class="feedback-img p-3  text-center">
                     <?php if (UR_exists($filename)) : ?>
                        <img src="<?php echo $filename; ?>" alt="<?php echo $dataItem['image']; ?>" class="img-fluid" style="max-height: 170px;">
                     <?php else : ?>
                        <img src="<?php echo site_url('webroot/img/user.png') ?>" alt="" class="img-fluid" style="max-height: 170px;">
                     <?php endif ?>
                  </div>
               </div>
            </div>
         <?php endif ?>
      <?php endforeach; ?>

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