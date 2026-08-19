<div class="modal-header">
        <h5 class="modal-title">Edit <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('feedback/edit-feedback/' . dev_encode($id)); ?>" method="post"
          onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Name </label>
                        <input class="form-control" type="text" value="<?php echo $details['name']?>" name="name" placeholder="name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Email * </label>
                        <input class="form-control" type="email" value="<?php echo $details['email']?>" name="email" placeholder="Email">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Mobile No * </label>
                        <input class="form-control" type="text" value="<?php echo $details['phone']?>" name="phone" placeholder="Mobile No">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> User Image * </label>
                        <input class="form-control" type="file"  name="image">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Feedback Date * </label>
                        <input class="form-control" type="text" nolim-calendor="true" value="<?php echo $details['feedback_date']?>" name="feedback_date" placeholder="Feedback Date">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Feedback Status *</label>
                        <select class="form-select" name="status" placeholder="Feedback Status">
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
                    <div class="form-group form-mb-20">
                        <label> Feedback  Description *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="description" rows="2" placeholder="Feedback Description"><?php echo $details['description']?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
