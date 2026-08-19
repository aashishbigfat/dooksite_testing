
<div class="modal-header">
    <h5 class="modal-title">Add Cruise</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('cruise/add-cruise-ship-gallery'); ?>" method="post" tts-form="true" name="add_cruise"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Image Title *</label>
                        <input class="form-control" type="text" name="image_title" placeholder="Image Title">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Image * </label>
                        <input class="form-control" type="file" name="images" placeholder=" Image">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Cruise Ship *</label>
                        <select class="form-control" name="cruise_ship_id" placeholder="Cruise Line">
                            <option value='' selected>Select Cruise Ship</option>
                            <?php if ($cruise_ship) {
                                foreach ($cruise_ship as $data) {
                                    ?>
                                    <option value="<?php echo $data['id']?>"><?php echo $data['ship_name']?></option>
                                <?php }
                            } ?>
                        </select>
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

            </div>
        </div>
        <div class="modal-footer">
             <button class="btn  btn-primary" type="submit">Save</button>
        </div>
    </form>


