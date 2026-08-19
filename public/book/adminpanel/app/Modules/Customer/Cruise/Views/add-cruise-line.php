
<div class="modal-header">
        <h5 class="modal-title">Add Cruise Line</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('cruise/add-cruise-line'); ?>" method="post" tts-form="true" name="add_cruise_line" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Cruise Line Name *</label>
                        <input class="form-control" type="text" name="cruise_line_name" placeholder="Cruise Line Name" onblur='tts_slug_url(this.value,"cruise-line-slug")' >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Slug *</label>
                        <input class="form-control" type="text" name="cruise_line_name_slug" placeholder="Slug" id="cruise-line-slug">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>  Image * </label>
                        <input class="form-control" type="file" name="cruise_line_image" placeholder=" Image">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>


