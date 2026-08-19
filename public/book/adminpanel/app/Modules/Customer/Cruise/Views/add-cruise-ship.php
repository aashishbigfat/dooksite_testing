
<div class="modal-header">
        <h5 class="modal-title">Add Cruise</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('cruise/add-cruise-ship'); ?>" method="post" tts-form="true" name="add_cruise"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Ship Name *</label>
                        <input class="form-control" type="text" name="ship_name" placeholder="Ship Name" onblur='tts_slug_url(this.value,"cruise-ship-slug")'>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Slug *</label>
                        <input class="form-control" type="text" name="ship_name_slug" placeholder="Slug" id="cruise-ship-slug">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Cruise Line *</label>
                        <select class="form-control" name="cruise_line_id" placeholder="Cruise Line">
                            <option value='' selected>Select Cruise Line</option>
                            <?php if ($cruise_line) {
                                foreach ($cruise_line as $data) {
                                    ?>
                                    <option value="<?php echo $data['id']?>"><?php echo $data['cruise_line_name']?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Image * </label>
                        <input class="form-control" type="file" name="ship_image" placeholder=" Image">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label>Ship Description *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="ship_description" rows="3" placeholder="Ship Description"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label>Cancellation Policy *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="cancellation_policy" rows="3" placeholder="Cancellation Policy"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label>Payment Policy *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="payment_policy" rows="3" placeholder="Payment Policy"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>

