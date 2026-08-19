<div class="modal-header">
    <h5 class="modal-title">Add Umrah Coupon</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?= site_url('coupon/add-coupon-umrah'); ?>" method="post" tts-form="true" name="add_visa_markup">

    <div class="modal-body">
        <div class="row">

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Travel Date From *</label>
                    <input class="form-control" type="text" package-from-date="true" name="travel_date_from"
                        placeholder="Travel Date From" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Travel Date To *</label>
                    <input class="form-control" type="text" package-from-date="true" name="travel_date_to"
                        placeholder="Travel Date To " readonly>
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
                    <label>Code *</label>
                    <input class="form-control" type="text" name="code" placeholder="Coupon Code" readonly
                        value="<?= 'CODE' . substr(uniqid(), -8) . substr(time(), -1); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Minm Order Value *</label>
                    <input class="form-control" type="text" name="minm_order" placeholder="Minm Order Value">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Maxm Order Value *</label>
                    <input class="form-control" type="text" name="maxm_order" placeholder="Maxm Order Value">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Minm Pax *</label>
                    <input class="form-control numeric" type="text" name="minm_pax" placeholder="Minm Pax">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Maxm Pax *</label>
                    <input class="form-control numeric" type="text" name="maxm_pax" placeholder="Maxm Pax">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Use Limit *</label>
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
                    <label>Show On List</label>
                    <select class="form-select" name="coupon_visible" placeholder="Show Coupan Code">
                        <option value="0" selected>No</option>
                        <option value="1"> Yes</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Booking From Date *</label>
                    <input class="form-control" type="text" package-from-date="true" name="valid_from"
                        placeholder="Booking From Date" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Booking To Date *</label>
                    <input class="form-control" type="text" package-from-date="true" name="valid_to"
                        placeholder="Booking To Date" readonly>
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

            <!-- <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Umrah Destination *</label>
                    <select name="destination_name[]" id="destination-name" class="form-select select_search"
                        multiple="multiple">
                        <?php foreach ($umrah_destination as $key => $destination): ?>
                            <option value="<?= $destination['id'] . '_' . $destination['destination_name'] ?>">
                                <?= $destination['destination_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="destination-name"
                            name="destination_select" value="ANY" class="Lead"> Any Destination Name
                    </label>
                </div>
            </div> -->


            <!-- <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Umrah Theme *</label>
                    <select name="theme_name[]" id="theme-name" class="form-select select_search" multiple="multiple">
                        <?php foreach ($umrah_theme as $key => $theme): ?>
                            <option value="<?= $theme['id'] . '_' . $theme['theme_name'] ?>"><?= $theme['theme_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="theme-name" name="theme_select"
                            value="ANY" class="Lead"> Any Holiday Theme
                    </label>
                </div>
            </div> -->

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Package Name *</label>
                    <select name="umrah_package[]" id="umrah-package" class="form-select select_search"
                        multiple="multiple">
                        <?php foreach ($umrah_package as $key => $package): ?>
                            <option value="<?= $package['id'] . '_' . $package['package_name'] ?>">
                                <?= $package['package_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="umrah-package" name="package_select"
                            value="ANY" class="Lead"> Any Umrah Package Name
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>