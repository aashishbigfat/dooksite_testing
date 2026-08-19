<div class="modal-body">
    <form action="#" method="post" tts-form="true" name="holiday_query" class="ng-pristine ng-valid">
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="form-group">
                    <label class="">Name:</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Name">
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="form-group">
                    <label class="">Contact Number:</label>
                    <input type="text" name="mobile" class="form-control" placeholder="Enter Contact Number">
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-12">
                <div class="form-group">
                    <label>Email Id:</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email Id">
                </div>
            </div>
            <input type="hidden" name="holiday_id" value="23" autocomplete="off">
            <input type="hidden" name="holiday_name" value="Explore Andaman " autocomplete="off">
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="form-group">
                    <label class="" for="select-menu">Travel Date</label>
                    <input type="text" class="form-control" name="travel_date" travel-date-calendor="true" readonly="">
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="form-group">
                    <label>Duration</label>
                    <select class="custom-select d-block form-select" name="no_of_nights">
                        <option value="1">1 Night</option>
                        <option value="2">2 Night</option>
                        <option value="3">3 Night</option>
                        <option value="4">4 Night</option>
                        <option value="5">5 Night</option>
                        <option value="6">6 Night</option>
                        <option value="7">7 Night</option>
                        <option value="8">8 Night</option>
                        <option value="9">9 Night</option>
                        <option value="10">10 Night</option>
                        <option value="11">11 Night</option>
                        <option value="12">12 Night</option>
                        <option value="13">13 Night</option>
                        <option value="14">14 Night</option>
                        <option value="15">15 Night</option>
                        <option value="16">16 Night</option>
                        <option value="17">17 Night</option>
                        <option value="18">18 Night</option>
                        <option value="19">19 Night</option>
                        <option value="20">20 Night</option>
                        <option value="21">21 Night</option>
                        <option value="22">22 Night</option>
                        <option value="23">23 Night</option>
                        <option value="24">24 Night</option>
                        <option value="25">25 Night</option>
                        <option value="26">26 Night</option>
                        <option value="27">27 Night</option>
                        <option value="28">28 Night</option>
                        <option value="29">29 Night</option>
                        <option value="30">30 Night</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-12">
                <div class="form-group">
                    <label>Person</label>
                    <select class="custom-select d-block form-select" name="no_of_person">
                        <option value="1">1 Persons</option>
                        <option value="2">2 Persons</option>
                        <option value="3">3 Persons</option>
                        <option value="4">4 Persons</option>
                        <option value="5">5 Persons</option>
                        <option value="6">6 Persons</option>
                        <option value="7">7 Persons</option>
                        <option value="8">8 Persons</option>
                        <option value="9">9 Persons</option>
                        <option value="10">10 Persons</option>
                        <option value="11">11 Persons</option>
                        <option value="12">12 Persons</option>
                        <option value="13">13 Persons</option>
                        <option value="14">14 Persons</option>
                        <option value="15">15 Persons</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-12">
                <div class="form-group">
                    <label for="Textarea2">Comments</label>
                    <textarea class="form-control" name="comment" placeholder="Leave a comment here" id="extarea2" style="height: 100px"></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12 text-center">
                    <button class="btn btn-danger" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>



<!-- <div class="modal-body">
   <div class="row gx-lg-0">
        
         <div class="formmodalbox">
            <div class="login-header">
               <h5 class="modal-title" id="login-modal-b5Label">Signup</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          
            <form action="<?php echo site_url('login/user-signup')?>" method="post" praveen-login-form="true" autocomplete="off">
               <div class="mb-3">
                  <label for="user_otp" class="form-label">Verify OTP</label>
                  <input type="text" name="otp" value="" class="form-control" id="user_otp" autocomplete="off" >
                  <a href="javascript:void(0)">Resend Password</a>
               </div>
               
               <div class="mb-3">
                  <label for="user_password" class="form-label">Set Login Password</label>
                  <input type="password" name="user_password" class="form-control" id="user_password" autocomplete="off">
                
               </div>
              
               <button type="submit" class="btn btn-primary">Continue</button>
            </form>
         </div> 
   </div>
</div> -->