<div class="modal-header">
    <h5 class="modal-title">Edit Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('customer/edit-customer/' . dev_encode($id)); ?>" method="post" tts-form="true" name="edit_agent" enctype="multipart/form-data">

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
                            <option value="Mr" <?php if ($details['title'] == "Mr") {
                                                    echo "selected";
                                                } ?>>Mr
                            </option>
                            <option value="Ms" <?php if ($details['title'] == "Ms") {
                                                    echo "selected";
                                                } ?>>Ms
                            </option>
                            <option value="Mrs" <?php if ($details['title'] == "Mrs") {
                                                    echo "selected";
                                                } ?>>Mrs
                            </option>
                            <option value="Miss" <?php if ($details['title'] == "Miss") {
                                                        echo "selected";
                                                    } ?>>Miss
                            </option>

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input class="form-control" type="text" name="first_name" value="<?php echo $details['first_name'] ?>" placeholder="First Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input class="form-control" type="text" name="last_name" value="<?php echo $details['last_name'] ?>" placeholder="Last Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email *</label>
                        <input class="form-control" type="email" name="email_id" value="<?php echo $details['email_id'] ?>" placeholder="Email">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Verified" id="emailverify" name="email_verify" <?php if ($details['email_verify'] == 1) {
                                                                                                                                    echo 'checked';
                                                                                                                                } ?>>
                        <label class="form-check-label" for="emailverify"> Email Verified </label>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mobile No *</label>
                        <input class="form-control" type="text" name="mobile_no" value="<?php echo $details['mobile_no'] ?>" placeholder="Mobile No">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Verified" id="mobileverify" name="mobile_verify" <?php if ($details['mobile_verify'] == 1) {
                                                                                                                                    echo 'checked';
                                                                                                                                } ?>>
                        <label class="form-check-label" for="mobileverify"> Mobile Verified </label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" name="dob" dob-calendor="true" value="<?php echo $details['dob'] ?>" placeholder="DOB">
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
                        <label>Marriage Status *</label>
                        <select class="form-select" name="marital_status" placeholder="Marriage Status">
                            <option value="Married" <?php if ($details['marital_status'] == "Married") {
                                                        echo "selected";
                                                    } ?>>Single
                            </option>
                            <option value="Unmarried" <?php if ($details['marital_status'] == "Unmarried") {
                                                            echo "selected";
                                                        } ?>> Married
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" <?php if ($details['status'] == "active") {
                                                        echo "selected";
                                                    } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($details['status'] == "inactive") {
                                                            echo "selected";
                                                        } ?>> Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <h6 class="viewld_h5">Customer Address</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address</label>
                        <input class="form-control" type="text" name="address" value="<?php echo $details['address'] ?>" placeholder="Address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City</label>
                        <input class="form-control" type="text" value="<?php echo $details['city'] ?>" name="city" placeholder="City">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>State</label>
                        <input class="form-control" type="text" name="state" value="<?php echo $details['state'] ?>" placeholder="State">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Country</label>
                        <input class="form-control" type="text" value="<?php echo $details['country'] ?>" name="country" placeholder="Country">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pin Code</label>
                        <input class="form-control" type="text" value="<?php echo $details['pin_code'] ?>" name="pin_code" placeholder="Pin Code">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>