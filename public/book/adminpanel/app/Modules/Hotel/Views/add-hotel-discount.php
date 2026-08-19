
            <div class="modal-header">
                <h5 class="modal-title" >Add <?php echo 'Hotel Discount '; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


    <form action="<?php echo site_url('hotel/add-hotel-discount'); ?>" method="post" tts-form="true"
          name="add_visa_markup">

        <div class="modal-body">
            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label>Markup For *</label>
                        <select class="form-select" name="discount_for" tts-markup-used-for="true">
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
                        <label>Value *</label>
                        <input class="form-control" type="text" name="value" placeholder="Value">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Extra Discount *</label>
                        <input class="form-control" type="text" name="extra_discount" value="0" placeholder="Extra Discount">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Max Limit </label>
                        <input class="form-control" type="text" name="max_limit" placeholder="Max Limit">
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
            <button class="btn btn-primary" type="submit" >Save</button>
        </div>
    </form>

