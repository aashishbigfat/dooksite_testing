
<div class="modal-header">
        <h5 class="modal-title">Add Cruise Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

    <form action="<?php echo site_url('cruise/add-cruise-price/'). dev_encode($cruise_list_id); ?>" method="post" tts-form="true" name="add_cruise"

          enctype="multipart/form-data">



        <div class="modal-body">

            <div class="row">



                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Selling Date *</label>

                        <input class="form-control" type="text" name="selling_date" nolim-calendor="true" placeholder="Selling Date">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Cruise Cabin *</label>

                        <select class="form-control" name="cruise_cabin_id" placeholder="Cruise Cabin">

                            <option value='' selected>Select Cruise Cabin</option>

                            <?php if ($cruise_cabin) {

                                foreach ($cruise_cabin as $data) {

                                    ?>

                                    <option value="<?php echo $data['id']?>"><?php echo $data['cabin_name']?></option>

                                <?php }

                            } ?>

                        </select>

                    </div>

                </div>



                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Single Pax Price *</label>

                        <input class="form-control" type="text" name="single_pax_price"  placeholder="Single Pax Price">

                    </div>

                </div>



                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Twin Pax Price *</label>

                        <input class="form-control" type="text" name="twin_pax_price"  placeholder="Twin Pax Price">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Port Charges *</label>

                        <input class="form-control" type="text" name="port_charges"  placeholder="Port Charges">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Available Cabin *</label>

                        <input class="form-control" type="text" name="available_cabin"  placeholder="Available Cabin">

                    </div>

                </div>



                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label>Max Pax Stay *</label>

                        <select class="form-control" name="max_pax_stay" placeholder="Max Pax Stay">

                            <option value="">Select Max Pax Stay</option>

                            <option value="1">1</option>

                            <option value="2">2</option>

                            <option value="3">3</option>

                            <option value="4">4</option>

                            <option value="5">5</option>

                            <option value="6">6</option>

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

                <div class="col-md-4">

                    <div class="form-group form-mb-20">

                        <label class="mt20">

                            <input type="checkbox" name="book_online" value="yes" class="Lead">Book Online

                        </label>

                    </div>

                </div>

            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>

    </form>





