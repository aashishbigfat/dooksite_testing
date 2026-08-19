<div class="modal-header">
    <h5 class="modal-title">Add <?php echo 'Tour Guide Coupon '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('coupon/add-coupon-tour-guide'); ?>" method="post" tts-form="true" name="add_visa_markup">

    <div class="modal-body">
        <div class="row">

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>From Date *</label>
                    <input class="form-control" type="text" nolim-calendor="true" name="tour_date_from" placeholder="From Date">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>To Date *</label>
                    <input class="form-control" type="text" nolim-calendor="true" name="tour_date_to" placeholder="To Date">
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
                    <input class="form-control" type="text" name="code" placeholder="Coupon Code" value="<?php echo 'CODE' . substr(uniqid(), -8) . substr(time(), -1); ?>">
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
                    <label>Coupon Desc * </label>
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

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label for="">City Name*</label>
                    <select class="form-select" name="city_id[]" tts-select2-ajax="true" tts-method-nam="tourguide/get-cities" placeholder="City" multiple="multiple">
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Tour Guide Name *</label>

                    <input class="form-control" type="text" name="tour_guide_name" tts-common-autocomplete="true" tts-method-name="<?php echo site_url("tourguide/get-tourguidename") ?>" placeholder="Tourguide Name">

                    <input type="hidden" name="tour_guide_id" tour-guide-id="true">
                </div>
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-4"></div>


            <div class="col-md-4 align-self-end" style="margin-top:-18px">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tour-guide-id" value="ANY" class="Lead"> Any Tour Guide Name
                    </label>
                </div>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>

<script>
    $(document).ready(function() {

        TTSGLOBAL.global.select2ajax();
    })
</script>