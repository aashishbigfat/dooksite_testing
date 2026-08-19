<div class="modal-header">
    <h5 class="modal-title">Add Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('customer/add-customer'); ?>" method="post" tts-form="true" name="add_customer" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="viewld_h5">Personal Details</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Title *</label>
                        <select class="form-select" name="title" placeholder="Title">
                            <option value="">Select Title</option>
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input class="form-control" type="text" name="first_name" placeholder="First Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email *</label>
                        <input class="form-control" type="email" name="email_id" placeholder="Email">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Verified" id="emailverify" name="email_verify">
                        <label class="form-check-label" for="emailverify"> Email Verified </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mobile No *</label>
                        <input class="form-control" type="text" name="mobile_no" placeholder="Mobile No">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Verified" id="mobileverify" name="mobile_verify">
                        <label class="form-check-label" for="mobileverify"> Mobile Verified </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" dob-calendor="true" name="dob" placeholder="DOB">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Marriage Status *</label>
                        <select class="form-select" name="marital_status" placeholder="Marriage Status">

                            <option value="Married" selected>Single</option>
                            <option value="Unmarried">Married</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password *</label>
                        <input class="form-control" type="text" name="password" placeholder="Password">
                        <button class="badge badge-wt mt-1" type="button" onclick=generatePassword(10,'add_customer');>Generate Password</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Profile Pic </label>
                        <input class="form-control" type="file" name="profile_pic" placeholder="Profile Pic">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <h6 class="viewld_h5">Customer Address</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address</label>
                        <input class="form-control" type="text" name="address" placeholder="Address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City</label>
                        <input class="form-control" type="text" name="city" placeholder="City">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>State</label>
                        <input class="form-control" type="text" name="state" placeholder="State">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Country</label>
                        <input class="form-control" type="text" name="country" placeholder="Country">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pin Code</label>
                        <input class="form-control" type="text" name="pin_code" placeholder="Pin Code">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="send" name="send_email" id="send-email">
                        <label class="form-check-label" for="send-email">
                            Send account details on customer email id
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>