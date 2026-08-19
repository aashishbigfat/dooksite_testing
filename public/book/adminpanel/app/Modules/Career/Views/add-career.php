<div class="modal-header">
    <h5 class="modal-title">Add <?php echo ' ' . $title; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<form action="<?php echo site_url('career/add-career'); ?>" method="post" tts-form="true" name="add_career" enctype="multipart/form-data">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job Title *</label>
                    <input class="form-control" type="text" name="job_title" placeholder="Job Title" onblur='tts_slug_url(this.value,"career_slug_url")'>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Slug *</label>
                    <input class="form-control" type="text" name="slug_url" id="career_slug_url" placeholder="Slug">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Salary</label>
                    <input class="form-control" type="text" name="offer_salary" placeholder="Salary">
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
                                <option value="<?php echo $data['id'] ?>">
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
                    <input class="form-control" type="text" name="location" placeholder="Job location">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job Type</label>
                    <select class="form-select" name="job_type" placeholder="Job Type">
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
                        <option value="active"> Active</option>
                        <option value="inactive"> Inactive </option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group form-mb-20">
                    <label> Short Description *</label>
                    <textarea class="form-control tts-editornote" type="textarea" name="short_description" rows="2" placeholder="Short Description"></textarea>
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>