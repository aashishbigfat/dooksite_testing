<div class="modal-header">
    <h5 class="modal-title">Edit <?php echo ' ' . $title; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<form action="<?php echo site_url('career/edit-career/' . dev_encode($id)); ?>" method="post" tts-form="true" name="add_blogs" enctype="multipart/form-data">

    <div class="modal-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label> Job Title * </label>
                    <input class="form-control" type="text" value="<?php echo $details['job_title'] ?>" name="job_title" placeholder="Job Title" onblur='tts_slug_url(this.value,"career_slug_url")'>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Slug *</label>
                    <input class="form-control" type="text" name="slug_url" value="<?php echo $details['slug_url'] ?>" id="career_slug_url" placeholder="Slug">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Salary</label>
                    <input class="form-control" type="text" value="<?php echo $details['offer_salary'] ?>" name="offer_salary" placeholder="Salary">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Career Category Name *</label>
                    <select class="form-select" name="category_id">
                        <option value="" selected>Career Category</option>
                        <?php if ($categories_lists) {
                            foreach ($categories_lists as $data) {
                        ?>
                                <option value="<?php echo $data['id'] ?>" <?php if ($details['category_id'] == $data['id']) {
                                                                                echo "selected";
                                                                            } ?>>
                                    <?php echo $data['job_category']; ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job location</label>
                    <input class="form-control" type="text" value="<?php echo $details['location'] ?>" name="location" placeholder="Job location">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job Type</label>
                    <select class="form-control" name="job_type" placeholder="Job Type">
                        <option value="Full Time" <?php if ($details['job_type'] == "Full Time") {
                                                        echo "selected";
                                                    } ?>>Full Time
                        </option>
                        <option value="Part Time" <?php if ($details['job_type'] == "Part Time") {
                                                        echo "selected";
                                                    } ?>> Part Time
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Career Status *</label>
                    <select class="form-select" name="status" placeholder="Career Status">
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
                    <label> Short Description *</label>
                    <textarea class="form-control tts-editornote" type="textarea" name="short_description" rows="2" placeholder="Short Description"><?php echo $details['short_description'] ?></textarea>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>