<div class="modal-body login-popup p-0">
   <div class="row gx-lg-0">
      <div class="col-md-5">
         <div class="modal-offer-banner d-flex flex-column align-items-center justify-content-center">
            <div class="info-item">
               <i class="fa-solid fa-envelope"></i>
               <div class="info-item-content">
                  <h4>Email:</h4>
                  <p><?php echo web_partner_details['support_email'] ?></p>
               </div>
            </div>
            <div class="info-item">
               <i class="fa-solid fa-phone"></i>
               <div class="info-item-content">
                  <h4>Call:</h4>
                  <p><?php echo web_partner_details['support_no'] ?></p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-7">
         <div class="formmodalbox">
            <div class="login-header">
               <h5 class="modal-title" id="login-modal-b5Label">Login</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('login/user-login')?>" method="post" praveen-login-form="true" autocomplete="off">
               <div class="mb-3 mt-3">
                  <label for="user_password" class="form-label">Login Password</label>
                  <input type="password" name="user_password" class="form-control" id="user_password" autocomplete="off"/>
                  <input type="hidden" name="email_id" value="<?php echo $email_id;?>">
               </div>
               <div class="mb-3 ">
                  <div class="form-bottom"> By proceeding you agree to <?php echo web_partner_details['company_name'].' ' ?><a target="_blank" href="/privacy-policy">Privacy
                     Policy </a> and <a target="_blank" href="<?php echo site_url(); ?>terms-conditions">T&amp;Cs.</a>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Continue</button>
            </form>
         </div>
      </div>
   </div>
</div>