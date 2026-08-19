<div class="modal-header">
    <h5 class="modal-title">Edit Career Category </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form name="web-partner" tts-form='true' action="<?php echo site_url('career/edit-career-categories/' . dev_encode($id)); ?>" method="POST" id="web-partner">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job Category Name *</label>
                    <input class="form-control" type="text" value="<?php echo $details['job_category'] ?>" name="job_category" placeholder="Package Name" onblur='tts_slug_url(this.value,"slug")'>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Slug</label>
                    <input class="form-control" type="text" name="slug_url" value="<?php echo $details['slug_url'] ?>" id="slug" placeholder="Slug">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
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
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </div>
</form>