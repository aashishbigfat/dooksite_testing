<div class="modal-header">
    <h5 class="modal-title">Add Career Category</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form name="web-partner" tts-form='true' action="<?php echo site_url('career/add-career-categories'); ?>" method="POST" id="web-partner">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Job Category Name *</label>
                    <input class="form-control" type="text" name="job_category" placeholder="Category Name" onblur='tts_slug_url(this.value,"slug-url")'>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Slug </label>
                    <input class="form-control" type="text" name="slug_url" id="slug-url" placeholder="Slug">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
                        <option value="active" selected>Active</option>
                        <option value="inactive"> Inactive</option>
                    </select>
                </div>
            </div>
            <div class="submit col-md-12">
                <input type="submit" class="btn btn-success  " value="Submit">
            </div>
        </div>
</form>