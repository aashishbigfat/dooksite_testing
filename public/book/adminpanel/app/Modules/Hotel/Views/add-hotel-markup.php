
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Hotel Markup '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('hotel/add-hotel-markup'); ?>" method="post" tts-form="true"
          name="add_visa_markup">

        <div class="modal-body">
            <div class="row">

            <div class="col-md-4">
                    <div class="form-group">
                        <label>Markup For *</label>
                        <select class="form-select" name="markup_for" tts-markup-used-for="true">
                        <option value="">Please Select</option>
                            <?php $markup_used_for = get_active_whitelable_business();
                            foreach ($markup_used_for as $key => $data) {
                            ?>
                                <option value="<?php echo $key ?>"><?php echo $key ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4 d-none" tts-agent-class="true">
                    <div class="form-group">
                        <label>Agent Class *</label>
                        <select class="form-select select_search" name="agent_class[]" placeholder="Flight Type" multiple="multiple">
                            
                            <?php foreach ($agent_class as $key => $data) {  ?>
                                <option value="<?php echo $data['id'] ?>"><?php echo $data['class_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Region Type *</label>
                        <select class="form-select select_search" name="region_type[]" placeholder="Region Type" multiple="multiple">
                            <option value="domestic" selected>Domestic</option>
                            <option value="international">International</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Hotel Markup Type *</label>
                        <select class="form-select" name="hotel_markup_type" placeholder="Hotel Markup Type">
                            <option value="per_night" selected>Per Night</option>
                            <option value="per_room">Per Room</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Star Rating *</label>
                        <select class="form-select select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                            <option value="1">1 Star</option>
                            <option value="2">2 Star</option>
                            <option value="3">3 Star</option>
                            <option value="4">4 Star</option>
                            <option value="5">5 Star</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Display Markup *</label>
                        <select class="form-select" name="display_markup" placeholder="Display Markup">
                            <option value="in_tax" selected>In Tax</option>
                            <option value="in_service_charge">In Service Charge</option>
                        </select>
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Value *</label>
                        <input class="form-control" type="text" name="value" placeholder="Value">
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
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
</div>
