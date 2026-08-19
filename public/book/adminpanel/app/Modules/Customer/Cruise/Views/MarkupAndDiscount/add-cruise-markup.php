<div class="modal-header">
    <h5 class="modal-title">Add <?php echo 'Cruise Markup '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>



    <form action="<?php echo site_url('cruise/add-cruise-markup'); ?>" method="post" tts-form="true"
          name="add_cruise_markup">

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
                        <select class="form-select select_search" name="agent_class[]" placeholder="Agent Class" multiple="multiple"> 
                            <?php foreach ($agent_class as $key => $data) {  ?>
                                <option value="<?php echo $data['id'] ?>"><?php echo $data['class_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Select Cruise Departure Port *</label>
                        <select class="form-select select_search" name="departure_port_id">
                            <option value=""  selected>Select Departure Port</option>
                            <option value="ANY">ANY Departure Port</option>
                            <?php if ($cruise_port) {
                                foreach ($cruise_port as $data) { ?>
                                    <option value="<?php echo $data['id'] ?>">
                                        <?php echo ucfirst($data['port_name']); ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Select Cruise Line *</label>
                        <select class="form-select select_search" tts-method-name="cruise/get-cruise-ship-id-select" name="cruise_line_id" tts-call-select="true">
                            <option value="" selected>Select Cruise Line</option>
                            <option value="ANY">ANY Cruise Line</option>
                            <?php if ($cruise_line) {
                                foreach ($cruise_line as $data) { ?>
                                    <option value="<?php echo $data['id'] ?>">
                                        <?php echo $data['cruise_line_name']; ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Select Cruise Ship *</label>
                        <select class="form-select select_search" name="cruise_ship_id" tts-call-put-html="true" tts-method-name="cruise/get-cruise-cabin-id-select" tts-call-select="true">
                            <option value="" selected>Select Cruise Ship</option>
                            <option value="ANY">ANY Cruise Ship</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Select Cruise Cabin *</label>
                        <select class="form-select select_search" name="cabin_id" tts-call-cruise-cabin-put-html="true">
                            <option value="" selected>Select Cruise Cabin</option>
                            <option value="ANY">ANY Cruise Cabin</option>
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
                        <label>Markup Type *</label>
                        <select class="form-select" name="markup_type" placeholder="Markup Type">
                            <option value="fixed" selected>Fixed</option>
                            <option value="percent">Percent</option>
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
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>

