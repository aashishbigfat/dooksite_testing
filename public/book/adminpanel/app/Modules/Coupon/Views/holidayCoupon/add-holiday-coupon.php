<div class="modal-header">
    <h5 class="modal-title">Add <?php echo 'Holiday Coupon '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('coupon/add-coupon-holiday'); ?>" method="post" tts-form="true" name="add_visa_markup">

    <div class="modal-body">
        <div class="row">
           
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>From Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_from" placeholder="From Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>To Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_to" placeholder="To Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Type *</label>
                  <select class="form-select" name="coupon_type" placeholder="Coupon Type">
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
                    <label>Code </label>
                    <input class="form-control" type="text" name="code" placeholder="Coupon Code" value="<?php echo 'CODE'. substr(uniqid(), -8) . substr(time(), -1); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Use Limit </label>
                    <input class="form-control" type="text" name="use_limit" placeholder="Use Limit">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Coupon Desc </label>
                    <input class="form-control" type="text" name="coupon_desc" placeholder="Coupon Desc">
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Show On List*</label>
                  <select class="form-select" name="coupon_visible" placeholder="Show Coupan Code">
                     <option value="0" selected>No</option>
                     <option value="1"> Yes</option>
                  </select>
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Valid From Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="valid_from" placeholder="Valid From Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Valid To Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="valid_to" placeholder="Valid To Date">
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
        <div class="row">
            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Holiday Destination *</label>
                    <select name="destination_name[]" id="destination-name" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_destination as $key => $destination) : ?>
                            <option value="<?= $destination['id'] . '_' . $destination['destination_name'] ?>"><?= $destination['destination_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="destination-name" name="destination_select" value="ANY" class="Lead"> Any Destination Name
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Holiday Theme *</label>
                    <select name="theme_name[]" id="theme-name" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_theme as $key => $theme) : ?>
                            <option value="<?= $theme['id'] . '_' . $theme['theme_name'] ?>"><?= $theme['theme_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="theme-name" name="theme_select" value="ANY" class="Lead" > Any Holiday Theme
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Package Name *</label>
                    <select name="holiday_package[]" id="holiday-package" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_package as $key => $package) : ?>
                            <option value="<?= $package['id'] . '_' . $package['holiday_name'] ?>"><?= $package['holiday_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="holiday-package" name="package_select" value="ANY" class="Lead"> Any Holiday Package Name
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>