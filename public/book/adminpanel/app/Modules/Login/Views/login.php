<!DOCTYPE html>
<html>
   <head>
      <title><?= $title ?> </title>
      <link rel="shortcut icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="alternate" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" hreflang="en" />
      <link rel="icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="apple-touch-icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>">

      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/custom.css">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.min.js'); ?>"></script>

      <style>
        input#loginButton {
            background: #009dae;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 0.375rem;
            width: 100%;
            border: 1px solid #009dae;
            transition: all 0.5s ease;
            border: 1px solid #009dae;
         }
        
         input#verify_otp {
            margin-top:10px;
            background: #009dae;
            color: #fff;
            font-weight: 500;
            padding: 8px 20px;
            border: 1px solid #009dae;
            transition: all 0.5s ease;
            border: 1px solid #009dae;
         }
         a#resend_otp {
         margin-top: 10px;
         background: #009dae;
         color: #fff;
         font-weight: 500;
         padding: 11px 20px;
         border: 1px solid #009dae;
         transition: all 0.5s ease;
         border: 1px solid #009dae;
         text-decoration: none;
      }
      .verify_otp {
         display: flex;
         justify-content: space-around;
         align-items: center;
      }
      </style>
   </head>
   <body>
      <div>
         <div class="starsec"></div>
         <div class="starthird"></div>
         <div class="starfourth"></div>
         <div class="starfifth"></div>
      </div>
      <?php if(! empty(session()->get("Message"))) :  $message = session()->get("Message"); ?>
      <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');"><?php echo $message['Message']; ?></div>
      <?php endif ?>
      <section  class="login">

         <div class="container-fluid">
            <div class="login_box" tts-login-form="true">
               <form action="<?php echo site_url('login'); ?>" method="POST" class="contact100-form validate-form" id="loginForm">
                  <div class="login_form ">
                     <div class="login_form_content">
                        <h3 class="login-login-title">Admin Login</h3>
                        <p class="login-form-text">Sign in to continue..</p>
                     </div>
                     <?= csrf_field() ?>
                     <div class="form-group position-relative mb-3">
                        <label class="form-label">Email Id</label>
                        <input class="form-control" type="text" name="user_email" placeholder="Email Id" value="<?php echo set_value('user_email'); ?>">
                           <?php if ($validation->getError('user_email')): ?>
                           <span class="error-message"> <?= $validation->getError('user_email') ?></span>
                           <?php endif; ?>
                     </div>
                     <input type="hidden" id="deviceId" name="deviceId">

                     <div class="form-group position-relative mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="user_password" placeholder="Password" value="<?php echo set_value('user_password'); ?>">
                           <?php if ($validation->getError('user_password')): ?>
                           <span class="error-message"> <?= $validation->getError('user_password') ?></span>
                           <?php endif; ?>



                         <div class="foreget-password" id="forget-password">
                           <p class="text-end mt-2 d-flex align-items-center justify-content-between" id="forgetPassword">Forgot your password?
                              <a href="javascript:void(0);" title="forget password " class="forgot_passwd text-muted" id="forgotpaasword" tts-forgot-password="true">
                              Reset Here
                              <i class="fa fa-lock" aria-hidden="true"></i>
                              </a>
                           </p> 
                         </div>


                     </div>

                     <div class="">
                        <!-- <button class="loginformbtn" type="submit" id="loginButton">
                           <span>Submit <i class="fa fa-arrow-right"></i></span>
                        </button> -->
                        <div class="col-12 col-md-12" id="loginButtonContainer">
                           <!-- <button class="loginbtn w-100" type="submit">Log in</button> -->
                           <input class="loginbtn w-100" type="button" value="Log in" id="loginButton" />
                        </div>
                     </div>
                  </div>
               </form>

                 <!-- //new code  -->
                           <!-- style="display: none"; -->
                           <div class="card-body" id="cardds" style="display: none";>
                              <form action="<?php echo site_url("login/verify-otp-password"); ?>" method="POST">
                                 <div class="col-md-12">
                                 <div id="otpId" class="form-group position-relative" >
                                     <label class="form-label">OTP</label>
                                    <input class="form-control" type="text" name="OTP">
                                    <input type="hidden" name="insertId" id="insertId">
                                    <?php if ($validation->getError('OTP')): ?>
                                    <span class="error-message"> <?= $validation->getError('OTP') ?></span>
                                    <?php endif; ?>
                                 </div>

                                 <!-- checkbox for trustable or not  -->
                                    <div class="form-box trustable-device-checkbox">
                                       <input type="checkbox" id="trustable" name="trustable" value="1">
                                       <label for="trustable">OTP don't ask again on this device</label>
                                    </div>
                                 <!-- checkbox for trustable or not  -->

                                 <div class="otp-box">
                                 <span class="meesage_text" id="otp-message">  </span>
                                 </div>
                                 <div class="verify_otp" >
                                       <input class="loginbtn" type="submit" value="Verify OTP" id="verify_otp" disabled/>
                                       <a href="<?php echo site_url("login"); ?>" id="resend_otp" hidden>Resend OTP</a>
                                 </div>
                                 <!-- //new input for otp here  -->
                                 </div>
                              </form>
                           </div>
                           <!-- //new code  -->              

            </div>
            <div class="hide" forgot-password-form="true">
               <div class="login_box">
                  <form action="<?php echo site_url('login/forgot-password'); ?>" method="POST"
                     tts-forgot-form="true" name="forgot_password">
                     <?= csrf_field() ?>
                     <div class="login_form">
                        <div class="login_form_content">
                           <h3 class="login-login-title">Forgot password</h3>
                           <p class="text-muted">Reset password with your Email </p>
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">Email Id</label>
                           <input class="form-control" type="email" name="registered_email" placeholder="Enter your email" value="<?php echo set_value('registered_email'); ?>">
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <input class="loginformbtn" type="submit" value="Get OTP"/>
                           </div>
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <p>Login here Your account
                                 <a href="javascript:void(0);" title="login here" class="forgot_passwd text-muted" id="forgotpaasword" tts-login="true"> login
                                  <i class="fa fa-lock" aria-hidden="true"></i>
                                 </a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="hide" forgot-password-otp-form="true">
               <div class="login_box">
                  <form action="<?php echo site_url('login/validate-otp-password'); ?>" method="POST"
                     tts-forgot-form="true" name="otp_validate">
                     <?= csrf_field() ?>
                     <div class="login_form">
                        <div class="login_form_content">
                           <h3 class="login-login-title">Reset Password</h3>
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">Enter OTP</label>
                           <input class="form-control" type="text" name="OTP" autocomplete="off"  placeholder="Enter OTP">
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">New Password</label>
                           <input class="form-control" type="password" name="new_password"
                              placeholder="Enter New Password">
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <input class="loginbtn loginformbtn" type="submit" value="Reset Password"/>
                           </div>
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <p>Login here Your account
                                 <a href="javascript:void(0);" title="login here"
                                    class="forgot_passwd text-muted" id="forgotpaasword" tts-login="true">
                                 login
                                 <i class="fa fa-lock" aria-hidden="true"></i>
                                 </a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
      </section>
      <script>
         $(document).on("submit", "[tts-forgot-form='true']", function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.attr('method');
            var name = form.attr('name');
            $("[data-message]").removeClass().html("");
            $(".error-message").removeClass().html("");
            var buttontxt;
            if ($("input[type=submit]", form).attr('value')) {
                buttontxt = $("input[type=submit]", form).attr('value');
                $("input[type=submit]", form).attr('disabled', true).val('Loading...');
            } else {
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Loading...');
            }
            $("span.error-message", form).replaceWith("");
         
            $.ajax({
                url: url,
                method: method,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (resp) {
                    if ($("input[type=submit]", form).attr('value')) {
                        $("input[type=submit]", form).attr('disabled', false).val(buttontxt);
                    } else {
                        $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                    }
                    if (resp.StatusCode == 1) {

                        $.each(resp.ErrorMessage, function (key, val) {
                            $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                        });
                    } else if (resp.StatusCode == 0) {
                        if (resp.Reload && resp.Reload == 'true') {
                            window.location.reload();
                        } else {
                            $("[forgot-password-form]").addClass('hide');
                            $("[forgot-password-otp-form]").removeClass('hide');
                            $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                        }
         
                    }
                },
                error: function (res) {
                    alert("Unexpected error! Try again.");
                    // location.reload();
                }
            });
         });
         $(document).on("click", ".error-message", function () {
            $(this).remove();
         });
         $(document).on("click", "[tts-login = 'true']", function () {
            $("[tts-login-form]").removeClass('hide');
            $("[forgot-password-form]").addClass('hide');
            $("[forgot-password-otp-form]").addClass('hide');
         });
         $(document).on("click", "[tts-forgot-password = 'true']", function () {
            $("[tts-login-form]").addClass('hide');
            $("[forgot-password-form]").removeClass('hide');
            $("[forgot-password-otp-form]").addClass('hide');
         });
      </script>




<!-- here is the login button  click event  -->
<script>
   $("#loginButton").click(function(e){
      e.preventDefault(); 
        var $errorElements = $("#loginForm").find('.error');
        if ($errorElements.length > 0) {
            $errorElements.remove();
        }
      
    var $button = $(this); 
    $button.prop('disabled', true);
    $button.val('Loading ...');
     //deleted code from here 

     
     var deviceId = localStorage.getItem('deviceId');
    if (!deviceId) {
      deviceId = '0'; 
      }
      document.getElementById("deviceId").value = deviceId;
     


     //deleted code from here 
      var form = $(this).closest('form'); 
      var url = form.attr('action'); 
      var method = form.attr('method'); 
      var formData = new FormData(form[0]); 
     
      var isEmpty = false;
         form.find('input[type="text"], input[type="password"]').each(function() {
            if ($(this).val().trim() === '') {
               console.log($(this).val().trim());
               isEmpty = true;
               $(this).after('<div class="error" style="color: red; font-weight: 600;">Please fill this field</div>');
            }
         });

         if (isEmpty) {
            $button.prop('disabled', false);
            $button.val('Login');
         } else{
            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (resp) {
                    if (resp.status_code === 111) {
                        

                     var otpDiv = document.getElementById('forget-password');
                     otpDiv.style.display = 'none'; 

                     setTimeout(function() {
                        $('a[href="<?php echo site_url("login"); ?>"]').removeAttr('hidden');
                     }, 30000);

                        var insertedId = resp.InsertedId;
                        document.getElementById("insertId").value = insertedId;

                        $('.login .login_box').css('min-height', '500px');
                        var otpDiv = document.getElementById('cardds');
                        var loginButtonHere = document.getElementById('loginButtonContainer');
                        otpDiv.style.display = 'block';
                        loginButtonHere.style.display = 'none';

                        //new code for timer starts here

                        // Create a div for displaying the timer message
                        var timerDiv = document.createElement('div');
                        timerDiv.id = 'resendTimer';
                        timerDiv.innerHTML = 'You can resend the link after <span id="secondsLeft" style="color: red;">30</span> seconds.';

                        // Insert the timer div after the div with class 'verify_otp'
                        var verifyOtpDiv = document.querySelector('.verify_otp');
                        // verifyOtpDiv.parentNode.insertBefore(timerDiv, verifyOtpDiv.nextSibling);
                        verifyOtpDiv.appendChild(timerDiv);


                        // Set a timer for 30 seconds
                        var secondsLeft = 30;
                        var timerInterval = setInterval(function() {
                           secondsLeft--;
                           if (secondsLeft <= 0) {
                                 clearInterval(timerInterval);
                                 timerDiv.innerHTML = ''; // Clear the timer message
                           } else {
                                 // timerDiv.innerHTML = 'You can resend the link after ' + secondsLeft + ' seconds.';
                                 document.getElementById('secondsLeft').innerText = secondsLeft;
                           }
                        }, 1000);

                        //new code for timmer ends here 

                        var message_container =document.getElementById('otp-message');
                        message_container.innerHTML = resp.otp_message;

                        var resend_link = document.querySelector('.verify_otp a');
                        resend_link.setAttribute('data-email', resp.email);
                        resend_link.setAttribute('data-password', resp.pwd);

                        var verify_otp_button = document.getElementById('verify_otp');
                        verify_otp_button.removeAttribute('disabled');

                        var resend_link = document.querySelector('a[data-email]');
                        resend_link.addEventListener('click', function(event) {
                            event.preventDefault();
            
                            if (resend_link.disabled) {
                            resend_link.textContent = "Resending...";
                            setTimeout(function() {
                                resend_link.textContent = "Resend OTP";
                                resend_link.setAttribute('hidden', 'true');
                            }, 3000);
                            return;
                        }

                        resend_link.disabled = true;
                        resend_link.textContent = "Resending...";


                        // Set a timeout to reset the button and hide it after 30 seconds
                        setTimeout(function() {
                            resend_link.disabled = false;
                            resend_link.textContent = "Resend OTP";
                            resend_link.setAttribute('hidden', 'true');
                        }, 300);

                            resendOtp(); 
                        });
                    }
                    if (resp.status_code === 1) {
                     $button.prop('disabled', false);
                     $button.val('Login');
                    let errorMessage = resp.message;
                    createPopup(errorMessage);
                     //new code
                    }
                    if (resp.status_code === 109) {
                        $button.prop('disabled', false);
                        $button.val('Login');
                        for (var key in resp.message) {
                                if (resp.message.hasOwnProperty(key)) {
                                    console.log(key + ': ' + resp.message[key]);
                                    var errorMessage = resp.message[key];
                                    $('[name="' + key + '"]').after('<div class="error" style="color: red; font-weight: 600;">' + errorMessage + '</div>');
                                }
                            }
                    }
                    if(resp.status_code === 101){
                     window.location.href = resp.message;
                    }
                    
                },
                error: function (res) {
                    console.error(res);
                    alert("Unexpected error! Try again.");
                }
            });
        }
        });
        
         // verify_otp button click is here 
         $("#verify_otp").click(function(e){
            e.preventDefault(); 
            var form = $(this).closest('form'); 
            var urls = form.attr('action'); 
            var methods = form.attr('method'); 
            var formDatas = new FormData(form[0]); 

         $.ajax({
               url: urls,
               method: methods,
               data: formDatas,
               contentType: false,
               cache: false,
               processData: false,
               success: function (resp) {
                  if (resp.status_code === 0) {
                        localStorage.setItem('deviceId', resp.deviceId); 
                        window.location.href = resp.message;
                     }
                     if (resp.status_code === 1) {
                     // let errorMessage = resp.message;
                     // alert(errorMessage);
                        let errorMessage = resp.message;
                        createPopup(errorMessage);
                        //new code
                     }
               },
               error: function (res) {
                  console.error(res);
                  alert("Unexpected error! Try again.");
               }
            });
         });

      </script>


<!-- //script to resend the otp -->
<script>
   function resendOtp() {
    var resend_link = document.querySelector('a[data-email]');
    var email = resend_link.getAttribute('data-email');
    var password = resend_link.getAttribute('data-password');
   
    $.ajax({
        url: resend_link.href,
        method: 'POST',
        data: {
            user_email: email,
            user_password: password
        },
        success: function (resp) {
            console.log(resp);
            if (resp.status_code === 111) {
                var message_container = document.getElementById('otp-message');
                message_container.innerHTML = resp.otp_message;

                var insertedId = resp.InsertedId;
                document.getElementById("insertId").value = insertedId;

                var resend_link = document.querySelector('.verify_otp a');
                resend_link.setAttribute('data-email', resp.email);
                resend_link.setAttribute('data-password', resp.pwd);

               var resend_link_hidden = document.querySelector('a[href="<?php echo site_url("login"); ?>"]');
               resend_link_hidden.setAttribute('hidden');

               // Set a timeout to hide the "Resend OTP" link again after 30 seconds
               setTimeout(function() {
                  resend_link_hidden.removeAttribute('hidden', 'true');
               }, 30000);
            }
            if (resp.status_code === 1) {
                let errorMessage = resp.message;
                createPopup(errorMessage);
            }
        },
        error: function (res) {
            console.error(res);
            alert("Unexpected error! Try again.");
        }
    });
}
</script>

<script>
   function createPopup(errorMessage) {
    var popup = document.createElement('div');
    popup.textContent = errorMessage;
    popup.style.position = 'fixed';
    popup.style.top = '50px';
    popup.style.right = '10px';
    popup.style.padding = '10px';
    popup.style.background = '#DC3545';
    popup.style.color = 'white';
    popup.style.borderRadius = '5px';
    popup.style.zIndex = '9999';
    popup.style.marginTop = '50px';
    document.body.appendChild(popup);
    setTimeout(function() {
        document.body.removeChild(popup);
    }, 3000);
}
</script>

   </body>
</html>
