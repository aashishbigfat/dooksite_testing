
<div class="modal-header">
        <h5 class="modal-title">Add Cruise Port</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>


    <form action="<?php echo site_url('cruise/add-cruise-port'); ?>" method="post" tts-form="true"

          name="add_car_city">

        <div class="modal-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Select Cruise Ocean *</label>

                        <select class="form-control select_search"  name="cruise_ocean_id" >

                            <option value="" selected>Select Cruise Ocean</option>

                            <?php if ($cruise_ocean) {

                                foreach ($cruise_ocean as $data) { ?>

                                    <option value="<?php echo $data['id'] ?>">

                                        <?php echo $data['ocean_name']; ?>

                                    </option>

                                <?php }

                            } ?>

                        </select>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Port Name *</label>

                        <input class="form-control" type="text" name="port_name">

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
                 <button class="btn btn-primary" type="submit">Save</button>

            </div>
    </form>

