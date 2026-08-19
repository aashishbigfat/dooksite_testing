<script src="https://accounts.google.com/gsi/client" async></script>
<!-- <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script> -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
<style>
   .data {
      display: none;
   }

   .login-buttons {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
   }


   /* .login-social-login {
    text-align: center;
}

.login-buttons {
    display: flex; 
    justify-content: center;
    gap: 20px; 
    flex-direction: column;
}
.g_id_signin {
   display: flex;
    justify-content: center;
    align-items: center;
}
.login-button {
    flex: 1; 
    padding: 14px;
    justify-content: center;
    align-items: center;
    display: flex;
    border: 1px solid black;
}
.button#facebook-login-button {
   padding: 5px 53px;
} */
</style>
<div class="modal-body login-popup p-0">
   <div class="row gx-lg-0">
      <div class="col-md-5">
         <div class="modal-offer-banner d-md-flex d-none flex-column align-items-center justify-content-center">
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
               <h5 class="modal-title mt-0" id="login-modal-b5Label" praveen-login-header="true">
                  <?php if ($detail_page == 'detail-page') {
                     echo 'Login to continue';
                  } else {
                     echo 'Login/Signup';
                  } ?>
               </h5>
               <?php if ($detail_page != 'detail-page') { ?>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" praveen-login-header-close="true"></button>
               <?php } ?>
            </div>
            <form action="<?php echo site_url('login/check-user') ?>" method="post" praveen-login-form="true" autocomplete="off">
               <div class="mb-3">
                  <label for="login-email" class="form-label">Email address</label>
                  <input type="email" name="email_id" value="<?php echo $email_id; ?>" class="form-control" id="login-email" aria-describedby="emailHelp" autocomplete="off">
                  <input type="hidden" name="param" pass-param="true">
                  <div id="emailHelp"><a href="javascript:void(0)" forgot-password="true">Reset
                        Password</a>
                  </div>
               </div>
               <div class="mb-3 ">
                  <div class="form-bottom"> By proceeding you agree to <?php echo web_partner_details['company_name'] . ' ' ?>
                     <a target="_blank" href="<?php echo site_url('privacy-policy'); ?>">Privacy
                        Policy </a> and <a target="_blank" href="<?php echo site_url('terms'); ?>">T&amp;Cs.</a>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary" continue="true">Continue</button>
               <!-- <div class="googleLoginBar">
                  <?php if ($detail_page == 'detail-page') { ?>
                  <a href="javascript:void(0);" class="btn-agent" onclick="window.history.go(-1); return false;">
                  <span> go back</span>
                  <?php } else { ?>
                  <a href="<?php echo site_url('/agent') ?>" class="btn-agent">
                  <span> Agent Login</span>
                  <?php } ?>
                  </a>
               </div> -->
               <!-- <div class="social-login">
                  <span>--- Login With ---</span>
                  <div class="d-flex align-items-center justify-content-center">
                     <a href="#" class="login-social-item bg1" title="Facebook">
                     <i class="fa-brands fa-facebook-f"></i>
                     </a>
                    
                     <a href="#" class="login-social-item bg3" title="Google" >
                        <i class="fa-brands fa-google"></i>
                     </a>
                  </div>
               </div> -->
            </form>
            
            
            
            <div class="login-top mt-4">
               <div class="social-login">
                  <span>--- Login With ---</span>
                  <div class="d-flex align-items-center justify-content-center">
                    


                     <a href="<?= site_url('login/facebook-oauth'); ?>" class="login-social-item bg1" title="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                     </a>

                     <a href="<?= site_url('login/google-oauth'); ?>"  class="login-social-item bg3" title="Google">
                        <i class="fa-brands fa-google"></i>
                     </a>
                  </div>
               </div>

               

               <?php $FacebookLoginID = trim(whitelabel['facebook_login_auth_key']); ?>
               
            </div>
            







            <?php /*  
            
            <div class="login-top mt-4">
               <p class="w-100 text-center">— Or Sign In With —</p>
               <!-- <h5>Login to your account</h5>
               <p>Welcome back, Select a method to login</p> -->
               <?php $GoogleLoginID = trim(whitelabel['google_login_auth_key']); ?>
               <?php $FacebookLoginID = trim(whitelabel['facebook_login_auth_key']); ?>
               <div class="login-social-login">
                  <div class="login-buttons">
                     <?php if (!empty(whitelabel['google_login_auth_key'])) {   ?>
                        <div id="btnContainer" class="login-button">
                           <div id="g_id_onload" data-client_id="<?php echo $GoogleLoginID; ?>.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse" data-auto_prompt="false">
                           </div>

                           <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left">
                           </div>

                        </div>
                     <?php  }  ?>
                     <!-- <div class="login-button">
                                 <button id="facebook-login-button">Login with Facebook</button>
                              </div> -->
                     <?php if (!empty(whitelabel['facebook_login_auth_key'])) {   ?>
                        <div class="login-button">
                           <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                           </fb:login-button>

                           <div id="status">
                           </div>
                           <p id="profile"></p>
                        </div>
                     <?php  }  ?>
                  </div>

               </div>
               <!-- <div class="login-dividerd"><span>or sign in with email</span></div> -->
            </div>
            
            */ ?>
            
            
            
            
            
         </div>
      </div>
   </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- new code  -->
<script>
   function handleCredentialResponse(response) {
      fetch("/authinit", {
            method: "POST",
            headers: {
               "Content-Type": "application/json"
            },
            body: JSON.stringify({
               request_type: 'user_auth',
               credential: response.credential
            }),
         })
         .then(response => response.json())
         .then(data => {
            if (data.StatusCode == 0) {
               location.reload();
            }
         })
         .catch(console.error);
   }
</script>

<!-- new code ends here  -->

<!-- facebook starts here  -->




<script>
   function statusChangeCallback(response) {
      console.log('statusChangeCallback');
      console.log(response);
      if (response.status === 'connected') {
         testAPI();
      } else {
         document.getElementById('status').innerHTML = 'Please log ' +
            'into this webpage.';
      }
   }


   function checkLoginState() {
      FB.getLoginStatus(function(response) {
         statusChangeCallback(response);
      });
   }


   window.fbAsyncInit = function() {
      FB.init({
         appId: '<?php echo $FacebookLoginID; ?>',
         cookie: true,
         xfbml: true,
         version: 'v20.0'
      });


      FB.getLoginStatus(function(response) {
         statusChangeCallback(response);
      });
   };

   function testAPI() {
      console.log('Welcome!  Fetching your information.... ');
      FB.api('/me', function(response) {
         console.log('Successful login for: ' + response.name);
         document.getElementById('status').innerHTML =
            'Thanks for logging in, ' + response.name + '!';
      });
   }
</script>

<!-- facerbook ends here  -->