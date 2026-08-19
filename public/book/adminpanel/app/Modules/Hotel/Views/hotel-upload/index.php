<div class="content">
    <div class="page-content">
        <div class="page-content-area">
            <div class="card">
                <div class="card-header text-white">Hotel Ticket Upload</div>
                <div class="card-body">
                    <form name="hotel-upload" tts-form="true" action="<?php echo site_url('hotel-upload/hotel-info-save') ?>" method="POST" id="hotel-upload">
                        <?php if (isset($_GET['id']) && $_GET['id'] != "") { ?>
                            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                        <?php } ?>
                        <div class="view_head">
                            <div class="row">
                                <div class="col-md-12"><span>Basic Information</span></div>
                            </div>
                        </div>
                        <div class="row">

                            <?php $markup_used_for = get_active_whitelable_business();  ?>
                            <?php if ($markup_used_for) : ?>
                                <div class="col-md-3">
                                    <div class="form-group form-mb-20">
                                        <label>Business Type *</label>
                                        <select class="form-control" agent-customer="true" name="bussiness_type">
                                            <?php
                                            $LoopOutSite = array(); // Initialize
                                            foreach ($markup_used_for as $key => $data) {
                                                $LoopOutSite[] = $key; ?>
                                                <option value="<?php echo $key ?>" <?php if ($hotel_detail) {
                                                                                        if ($hotel_detail['bussiness_type'] == $key) {
                                                                                            echo "selected";
                                                                                        }
                                                                                    } ?>><?php echo $key ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php
                            if ($hotel_detail) {
                                $LoopOutSite[0] = $hotel_detail['bussiness_type'];
                            }
                            if (isset($LoopOutSite)) : ?>
                                <div class="col-md-3" agent-customer-show<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? '' : '=""' ?>>
                                    <div class="form-group form-mb-20">
                                        <label><?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'Agent' : 'Customer' ?> Name *</label>
                                        <input type="text" class="form-control" name="<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info" value="<?php if ($hotel_detail) {
                                                                                                                                                                                            if ($hotel_detail['bussiness_type'] == "B2B") {
                                                                                                                                                                                                echo $hotel_detail['agent_info'];
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo $hotel_detail['customer_info'];
                                                                                                                                                                                            }
                                                                                                                                                                                        } ?>" tts-get-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info="true" tts-error-msg="Please enter search type" placeholder="<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'Agent' : 'Customer' ?> Name" autocomplete="off">
                                        <input type="hidden" name="tts_<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info_id" tts-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info-id="true" value="<?php if ($hotel_detail) {
                                                                                                                                                                                                                                                                                    if ($hotel_detail['bussiness_type'] == "B2B") {
                                                                                                                                                                                                                                                                                        echo $hotel_detail['tts_agent_info_id'];
                                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                                        echo $hotel_detail['tts_customer_info_id'];
                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                } ?>">
                                        <input type="hidden" name="tts_<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info" tts-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info="true" value="<?php if ($hotel_detail) {
                                                                                                                                                                                                                                                                                if ($hotel_detail['bussiness_type'] == "B2B") {
                                                                                                                                                                                                                                                                                    echo $hotel_detail['tts_agent_info'];
                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                    echo $hotel_detail['tts_customer_info'];
                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                            } ?>">
                                        <span class="success" <?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>info="true">
                                            <?php if ($hotel_detail) {
                                                if ($hotel_detail['bussiness_type'] == "B2B") {
                                                    echo $hotel_detail['tts_agent_info'];
                                                } else {
                                                    echo $hotel_detail['tts_customer_info'];
                                                }
                                            } ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Issue Supplier *</label>
                                    <select class="form-control" name="supplier">
                                        <option value="" selected="">Select</option>
                                        <?php foreach ($offline_supplier as $item) { ?>
                                            <option value="<?php echo $item['id']; ?>#<?php echo $item['supplier_name']; ?>" <?php if ($hotel_detail) {
                                                                                                                                    if ($hotel_detail['supplier'] == $item['id'] . '#' . $item['supplier_name']) {
                                                                                                                                        echo "selected";
                                                                                                                                    }
                                                                                                                                } ?>><?php echo $item['supplier_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="view_head">
                            <div class="row">
                                <div class="col-md-12"><span>Hotel Information</span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Hotel City *</label>
                                    <input type="text" class="form-control" name="hotel_city" placeholder="Hotel City" value="<?php if ($hotel_detail) {
                                                                                                                                    echo $hotel_detail['hotel_city'];
                                                                                                                                } ?>" tts-get-city />
                                    <input type="hidden" name="hotel_city_id" value="<?php if ($hotel_detail) {
                                                                                            echo $hotel_detail['hotel_city_id'];
                                                                                        } ?>" tts-city-id>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Hotel Name *</label>
                                    <input type="text" class="form-control" name="hotel_name" placeholder="Hotel Name" value="<?php if ($hotel_detail) {
                                                                                                                                    echo $hotel_detail['hotel_name'];
                                                                                                                                } ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-mb-20">
                                    <label> Address *</label>
                                    <input type="text" class="form-control" name="hotel_address" placeholder="Address" value="<?php if ($hotel_detail) {
                                                                                                                                    echo $hotel_detail['hotel_address'];
                                                                                                                                } ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Star Rating * </label>
                                    <select class="form-control select_search" name="hotel_star_rating">
                                        <option value="">Select Star Rating</option>
                                        <option value="1" <?php if ($hotel_detail) {
                                                                if ($hotel_detail['hotel_star_rating'] == '1') {
                                                                    echo "selected";
                                                                }
                                                            } ?>>1 Star</option>
                                        <option value="2" <?php if ($hotel_detail) {
                                                                if ($hotel_detail['hotel_star_rating'] == '2') {
                                                                    echo "selected";
                                                                }
                                                            } ?>>2 Star</option>
                                        <option value="3" <?php if ($hotel_detail) {
                                                                if ($hotel_detail['hotel_star_rating'] == '3') {
                                                                    echo "selected";
                                                                }
                                                            } ?>>3 Star</option>
                                        <option value="4" <?php if ($hotel_detail) {
                                                                if ($hotel_detail['hotel_star_rating'] == '4') {
                                                                    echo "selected";
                                                                }
                                                            } ?>>4 Star</option>
                                        <option value="5" <?php if ($hotel_detail) {
                                                                if ($hotel_detail['hotel_star_rating'] == '5') {
                                                                    echo "selected";
                                                                }
                                                            } ?>>5 Star</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Check-in *</label>
                                    <input type="text" class="form-control" name="check_in_date" placeholder="Check-in" value="<?php if ($hotel_detail) {
                                                                                                                                    echo $hotel_detail['check_in_date'];
                                                                                                                                } ?>" start-date />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Check-out *</label>
                                    <input type="text" class="form-control" name="check_out_date" placeholder="Check-out" value="<?php if ($hotel_detail) {
                                                                                                                                        echo $hotel_detail['check_out_date'];
                                                                                                                                    } ?>" end-date />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label class="mt20">
                                        <input type="checkbox" name="pan_required" value="1" <?php if (isset($hotel_detail['pan_required'])) {
                                                                                                    echo "checked";
                                                                                                } ?>> PAN Card Require
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label class="mt20">
                                        <input type="checkbox" name="passport_required" value="1" <?php if (isset($hotel_detail['passport_required'])) {
                                                                                                        echo "checked";
                                                                                                    } ?>> Passport Require
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-mb-20">
                                    <label>Hotel Policy * </label>
                                    <textarea name="hotel_policy" class="tts-editornote" rows="3"><?php if ($hotel_detail) {
                                                                                                        echo $hotel_detail['hotel_policy'];
                                                                                                    } ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="view_head">
                            <div class="row">
                                <div class="col-md-12"><span>Passenger Contact Information</span></div>
                            </div>
                        </div>

                        <div class="row">

                            <?php $country_code = get_countary_code();  ?>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Dial Code *</label>
                                    <select name="dial_code" class="form-select">
                                        <?php foreach ($country_code as $country_codes) {
                                            if ($country_codes['dialcode'] == 91) {
                                                echo "<option value=" . $country_codes['dialcode']  . " selected>" . $country_codes['countryname'] . "  (" . $country_codes['dialcode'] . " )</option>";
                                            } else {
                                                echo "<option value=" . $country_codes['dialcode'] . ">" . $country_codes['countryname'] . "(" . $country_codes['dialcode'] . " )</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Contact Number *</label>
                                    <input type="text" class="form-control" name="contact_number" placeholder="Contact Number" value="<?php if ($hotel_detail) {
                                                                                                                                            echo $hotel_detail['contact_number'];
                                                                                                                                        } ?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label> Email Id *</label>
                                    <input type="text" class="form-control" name="email_id" placeholder="Email Id" value="<?php if ($hotel_detail) {
                                                                                                                                echo $hotel_detail['email_id'];
                                                                                                                            } ?>" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-primary" type="submit">Save & Continue</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        setTimeout(() => {
            $('.note-editable').height(120);
        }, 50);
    });
</script>