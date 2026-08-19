
<div class="modal-header">
        <h5 class="modal-title">Add Cruise Ocean</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('cruise/add-cruise-ocean'); ?>" method="post" tts-form="true"

          name="add_car_city">

        <div class="modal-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group form-mb-20">

                        <label>Ocean Name *</label>

                        <input class="form-control" type="text" name="ocean_name">

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

            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>

        </div>

    </form>
