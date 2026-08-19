<div class="dashboard_banner"></div>
<div class="btrip-dashboard">
   <div class="container">
      <div class="row">
         <!--sidebar-->
         <?php echo view('\Modules\Dashboard\Views\side-bar'); ?>
         <div class="col-lg-9">
            <div class="dashboard_right">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="dashboard_right-content">
                     <h3>Profile</h3>
                     <p>Personal Information</p>
                  </div>
                  <button type="button" class="btn__dtailAdEdt" data-bs-toggle="modal"
                     data-bs-target="#profile-modal"><span class="fa fa-pencil"></span> Edit </button>
               </div>
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <td>Name</td>
                           <td>
                              <span><?php echo $customer['title'] . ' ' . $customer['first_name'] . ' ' . $customer['last_name'] ?></span>
                           </td>
                           <td></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Birthday</td>
                           <td><span><?php echo $customer['dob'] ?></span></td>
                           <td></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Email Id</td>
                           <td><span><?php echo $customer['email_id'] ?></span></td>
                           <td><span class="greenText">Verified</span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Mobile</td>
                           <td><span><?php echo $customer['mobile_no'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Address</td>
                           <td><span><?php echo $customer['address'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>City</td>
                           <td><span><?php echo $customer['city'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>State</td>
                           <td><span><?php echo $customer['state'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Country</td>
                           <td><span><?php echo $customer['country'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>Pin</td>
                           <td><span><?php echo $customer['pin_code'] ?></span></td>
                           <td><span class="greenText"></span></td>
                           <td></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="dashboard_right">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="dashboard_right-content">
                     <h3>Login Details</h3>
                     <p>Manage your email address mobile number and password</p>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <td>Email Id</td>
                           <td><span><?php echo $customer['email_id'] ?></span></td>
                           <td></td>
                           <td><span class="greenText">Verified</span></td>
                        </tr>
                        <tr>
                           <td>Password</td>
                           <td><span>**********</span></td>
                           <td></td>
                           <td><a data-bs-toggle="modal" data-bs-target="#change-password-modal"><span
                                    class="blueText">Change Password?</span></a></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!----------------edit---------modal-------------->
<div class="modal fade dashboard_modal" id="profile-modal" tabindex="-1" aria-labelledby="profile-modal-label"
   aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content w-100">
         <div class="modal-header bg-transparent border-0">
            <h1 class="modal-title fs-5 mt-0" id="profile-modal-label">Edit Profile</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body m-0">
            <form action="<?php echo site_url('dashboard/update-customer-profile/' . dev_encode($customer['id'])); ?>"
               method="post" tts-form="true" name="update_customer" enctype="multipart/form-data">
               <div class="row ">
                  <div class="col-lg-4 mb-3">
                     <label for="title" class="form-label">Title</label>
                     <select class="form-select" name="title" id="title">
                        <option value="Mr" <?php if ($customer['title'] == 'Mr') {
                           echo 'selected';
                        } ?>>Mr</option>
                        <option value="Mrs" <?php if ($customer['title'] == 'Mrs') {
                           echo 'selected';
                        } ?>>Mrs</option>
                        <option value="Ms" <?php if ($customer['title'] == 'Ms') {
                           echo 'selected';
                        } ?>>Ms</option>
                     </select>
                  </div>

                  <div class="col-lg-4 mb-3">
                     <label for="first-name" class="form-label">First name*</label>
                     <input type="text" class="form-control" name="first_name" id="first-name"
                        value="<?php echo $customer['first_name'] ?>" placeholder="Enter your first name">
                  </div>
                  <div class="col-lg-4 mb-3">
                     <label for="last-name" class="form-label">Last name</label>
                     <input type="text" name="last_name" class="form-control" id="last-name"
                        value="<?php echo $customer['last_name'] ?>" placeholder="Enter your last name">
                  </div>
                  <div class="col-lg-4 mb-3">
                     <label for="dob" class="form-label">Date of Birth</label>
                     <input type="text" placeholder="Enter your date of birth" dob-calendor="true" class="form-control" name="dob"
                        value="<?php echo $customer['dob'] ?>" id="dob" readonly>
                  </div>
                  <div class="col-lg-4 mb-3">
                     <label for="last-name" class="form-label">Mobile No*</label>
                     <input type="text" name="mobile_no" class="form-control" id="mobile_no"
                        value="<?php echo $customer['mobile_no'] ?>" placeholder="Enter your mobile no">
                  </div>
                  <div class="col-lg-4 mb-3">
                     <label for="marital_status" class="form-label">Marital Status</label>
                     <select class="form-select" name="marital_status" id="marital_status">
                        <option value="Single" <?php if ($customer['marital_status'] == 'Single') {
                           echo 'selected';
                        } ?>>
                           Single</option>
                        <option value="Married" <?php if ($customer['marital_status'] == 'Married') {
                           echo 'selected';
                        } ?>>
                           Married</option>
                     </select>
                  </div>
                  <?php //pr($customer);exit(); ?>
                  <div class="col-xl-4 mb-3">
                     <label for="profile_pic" class="form-label">Profile Pic</label>
                     <input type="file" class="form-control" value="<?php echo $customer['profile_pic'] ?>"
                        name="profile_pic" id="profile_pic" placeholder="Profile">
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-lg-12">
                     <div class="alert alert-primary p-0 m-0 bg-transparent rounded-0 border-0 text-dark" role="alert">
                        <h5 class="m-0">Address Information</h5>
                     </div>
                  </div>
               </div>

               <div class="row ">
                  <div class="col-xl-4 mb-3">
                     <label for="address" class="form-label">Address</label>
                     <input type="text" class="form-control" value="<?php echo $customer['address'] ?>" name="address"
                        id="address" placeholder="Enter your Address">
                  </div>

                  <div class="col-xl-4 mb-3">
                     <label for="country" class="form-label">Country</label>
                     <select class="form-select country_select_agent" name="country" id="country" placeholder="Country">
                        <option value="">Select country</option>
                        <?php if ($country_list) {
                           foreach ($country_list as $country) {
                              ?>
                              <option value="<?php echo $country['id'] ?>" <?php if ($country['name'] == $customer['country']) {
                                    echo "selected";
                                 } ?>><?php echo $country['name'] ?>
                              </option>
                           <?php }
                        } ?>
                     </select>
                  </div>
                  <div class="col-xl-4 mb-3">
                     <label for="state" class="form-label">State</label>
                     <select class="form-select state_select_agent" name="state" id="state" placeholder="State">
                        <option value="">Select state</option>
                        <option value="" <?php if(!empty($customer['state'])) { echo "selected";} ?>><?php echo $customer['state'] ?></option>
                     </select>
                  </div>
                  <div class="col-xl-4 mb-3">
                     <label for="city" class="form-label">City</label>
                     <select class="form-select city_select_agent" name="city" id="city" placeholder="City">
                        <option value="">Select city</option>
                        <option value="" <?php if(!empty($customer['city'])) { echo "selected";} ?>><?php echo $customer['city'] ?></option>
                     </select>
                  </div>
                  <div class="col-xl-4 mb-3">
                     <label for="pin_code" class="form-label">Pin Code </label>
                     <input type="text" class="form-control" name="pin_code" value="<?php echo $customer['pin_code'] ?>"
                        id="pin_code" placeholder="Enter your Pin Code">
                  </div>
               </div>
               <div class="updatebutton">
                  <button class="btn btn-primary" type="submit">Update</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!----------------edit---------modal-------------->
<!----------------cancel---------modal----------------->
<div class="modal fade dashboard_modal" id="change-password-modal" tabindex="-1"
   aria-labelledby="change-password-modal-label" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content w-100">
         <div class="modal-header bg-transparent border-0">
            <h1 class="modal-title fs-5 mt-0" id="change-password-modal">Change Password?</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body m-0">
            <form action="<?php echo site_url('dashboard/change-password/' . dev_encode($customer['id'])); ?>"
               method="post" tts-form="true" novalidate>
               <div class="row">
                  <div class="col-md-12 mb-3">
                     <label for="old_password" class="form-label">Current Password</label>
                     <input type="password" name="old_password" class="form-control" id="old_password"
                        placeholder="Enter Current Password" required>
                  </div>
                  <div class="col-md-12 mb-3">
                     <label for="password" class="form-label">New Password</label>
                     <div class="position-relative">
                        <input type="password" name="password" class="form-control" id="password"
                           placeholder="Enter New Password" required>
                        </span>
                        <span class="btn-show-pass position-absolute top-0 end-0 pe-2 h-100 d-flex align-items-center ">
                           <i class="fa-solid fa-eye" onclick="createpassword('password',this)"></i>
                        </span>
                     </div>
                  </div>
                  <div class="col-md-12 mb-3">
                     <label for="password" class="form-label">Confirm Password</label>
                     <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                        placeholder="Confirm Password" required>
                     <small id="password_match" class="text-danger d-none">Passwords do not match.</small>
                  </div>
                  <div class="col-12 d-flex align-items-center justify-content-end">
                     <button class="btn btn-primary" type="submit">Change Password</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!----------------cancel---------modal-----end--------->
<script>
   $('.country_select_agent').on('change', function () {
      var d = $('.country_select_agent').val();
      var hiturl = $("#site_url").val();
      $.ajax({
         url: "<?php echo site_url('dashboard/state') ?>",
         type: 'POST',
         data: {
            'id': d
         },
         success: function (data) {
            $('.state_select_agent').html("<option value=''>select state</option>" + data);
         }
      })

   })

   $('.state_select_agent').on('change', function () {
      var city = $('.state_select_agent').val();
      var hiturl = $("#site_url").val();
      $.ajax({
         url: "<?php echo site_url('dashboard/city') ?>",
         type: 'POST',
         data: {
            'id': city
         },
         success: function (data) {
            $('.city_select_agent').html("<option value=''>city</option>" + data);
         }
      })

   })
</script>

<script>
   // for show password 
   let createpassword = (type, ele) => {
      document.getElementById(type).type = document.getElementById(type).type == "password" ? "text" : "password"
      let icon = ele.childNodes[0].classList
      let stringIcon = icon.toString()
      if (stringIcon.includes("ri-eye-line")) {
         ele.childNodes[0].classList.remove("ri-eye-line")
         ele.childNodes[0].classList.add("ri-eye-off-line")
      }
      else {
         ele.childNodes[0].classList.add("ri-eye-line")
         ele.childNodes[0].classList.remove("ri-eye-off-line")
      }
   }
   document.getElementById("confirm_password").addEventListener("input", function () {
      var password = document.getElementById("password").value;
      var confirm_password = document.getElementById("confirm_password").value;
      var password_match = document.getElementById("password_match");

      if (password === confirm_password) {
         password_match.classList.add("d-none");
      } else {
         password_match.classList.remove("d-none");
      }
   });

</script>