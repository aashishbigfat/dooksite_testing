<div class="modal-header">
    <h5 class="modal-title">Edit Cruise</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>


<form action="<?php echo site_url('cruise/edit-cruise/') . dev_encode($id); ?>" method="post" tts-form="true" name="add_cruise" enctype="multipart/form-data">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Select Cruise Ocean *</label>
                    <select class="form-control select_search" name="cruise_ocean_id" tts-method-name="cruise/get-cruise-list-select" tts-call-select="true">
                        <option value="" selected>Select Cruise Ocean</option>
                        <?php if ($cruise_ocean) {
                            foreach ($cruise_ocean as $data) { ?>
                                <option value="<?php echo $data['id'] ?>" <?php if ($details['cruise_ocean_id'] == $data['id']) {
                                                                                echo "selected";
                                                                            } ?>>
                                    <?php echo $data['ocean_name']; ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Select Departure Port *</label>
                    <select class="form-control select_search" name="departure_port_id" tts-call-put-html="true">
                        <option value='' selected="selected">Select Departure Port</option>
                        <?php
                        if ($cruise_port) {
                            foreach ($cruise_port as  $item) { ?>
                                <option value=<?php echo $item['id'] ?> <?php if ($details['departure_port_id'] == $item['id']) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $item['port_name'] ?></option>
                        <?php }
                        }
                        ?>
                    </select>
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
                                <option value="<?php echo $data['id'] ?>" <?php if ($details['cruise_line_id'] == $data['id']) {
                                                                                echo "selected";
                                                                            } ?>><?php echo $data['cruise_line_name'] ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Starting Price *</label>
                    <input class="form-control" type="text" name="starting_price" value="<?php echo $details['starting_price']; ?>" placeholder="Starting Price">
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-control" name="status" placeholder="Status">
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

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Cruise Ship *</label>
                    <select class="form-control" name="cruise_ship_id" placeholder="Cruise ship">
                        <option value='' selected>Select Cruise Ship</option>
                        <?php if ($cruise_ship) {
                            foreach ($cruise_ship as $data) {
                        ?>
                                <option value="<?php echo $data['id'] ?>" <?php if ($details['cruise_ship_id'] == $data['id']) {
                                                                                echo "selected";
                                                                            } ?>><?php echo $data['ship_name'] ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label class="mt20">
                        <input type="checkbox" name="adult_passport" value="1" class="Lead" <?php if ($details['adult_passport'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?>>Adult Passport Require
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label class="mt20">
                        <input type="checkbox" name="child_passport" value="1" class="Lead" <?php if ($details['child_passport'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?>>Child Passport Require
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label class="mt20">
                        <input type="checkbox" name="infant_passport" value="1" class="Lead" <?php if ($details['infant_passport'] == 1) {
                                                                                                    echo 'checked';
                                                                                                } ?>>Infant Passport Require
                    </label>
                </div>
            </div>
        </div>
        <div class="row mb-3 ">
            <div class="col-md-12 ">
            <div class="bg-light p-2 d-flex align-items-center justify-content-between">
                <h5 class="m-0">Cruise Itinerary </h5>
                    <div class="pull-right">
                        <button class="badge badge-wt" onclick="add_more_items(event,'edit-cruise-html',15)"><i class="tts-icon add "></i> Add Itinerary
                        </button>
                    </div>
            </div>
               
            </div>
        </div>
        <div style="display: contents;" id="edit-cruise-html">
            <?php if ($itinerary_count) { ?>
                <?php for ($i = 0; $i < $itinerary_count; $i++) { ?>
                    <div class="row tts-itinerary-row">

                        <div class="col-md-1">
                            <span class="text-bold count text_wrap" get-text="Day">Day <?php echo $i + 1; ?> </span>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group form-mb-20">
                                <label class="mt20">
                                    <input type="checkbox" name="cruise_itinerary[stopas][]" value="yes" class="Lead" tts-select-stop="true" <?php if ($itinerary[$i]['stopas'] == "yes") {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>>Stop
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>City Name *</label>
                                <input class="form-control" type="text" name="cruise_itinerary[city][]" value="<?php echo $itinerary[$i]['city'] ?>" placeholder="City Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Time / Duration *</label>
                                <input class="form-control" type="text" name="cruise_itinerary[time_duration][]" value="<?php echo $itinerary[$i]['time_duration'] ?>" placeholder="Time / Duration">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Description</label>
                                <textarea class="form-control" name="cruise_itinerary[description][]" rows="1"><?php echo $itinerary[$i]['description'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <span class="action fa fa-close" onclick="remove_more_items(this,'edit-cruise-html')"></span>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>