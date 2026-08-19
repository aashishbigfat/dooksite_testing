<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Car Coupon</h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php if (permission_access("Coupon", "add_coupon_car")) { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-controller='bus'
                                data-href="<?= site_url('coupon/car-coupon-view') ?>"><i class="fa fa-add"></i>
                                Add Car Coupon
                            </button>
                        <?php } ?>

                        <?php if (permission_access("Coupon", "car_coupon_status_change")) { ?>
                            <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="fa fa-exchange"></i> Change Status
                            </button>
                        <?php } ?>
                        <?php if (permission_access("Coupon", "remove_car_coupon")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formdiscountlist')"><i class="fa-solid fa-trash"></i> Delete
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?= site_url('coupon/car-coupon'); ?>" method="GET" class="tts-dis-content"
                        name="discount-search" onsubmit="return searchvalidateForm()">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-select"
                                        onchange="tts_searchkey(this, 'discount-search')" tts-validatation="Required"
                                        tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="code" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'code' ? 'selected' : '' ?>>Coupon Code</option>
                                        <option value="coupon_type" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'coupon_type' ? 'selected' : '' ?>>Coupon Type
                                        </option>
                                        <option value="date-range" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'date-range' ? 'selected' : '' ?>>Date Range
                                        </option>
                                    </select>
                                </div>
                                <input type="hidden" name="key-text"
                                    value="<?= !empty($search_bar_data['key-text']) ? trim($search_bar_data['key-text']) : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>
                                        <?= !empty($search_bar_data['key']) && $search_bar_data['key'] != 'date-range' ? $search_bar_data['key-text'] . " *" : "Value" ?>
                                    </label>
                                    <input type="text" name="value" placeholder="Value"
                                        value="<?= $search_bar_data['value'] ?? '' ?>" class="form-control"
                                        <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'date-range' ? 'disabled' : 'tts-validatation="Required"' ?>
                                        tts-error-msg="Please enter value" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>From Date</label>
                                    <input type="text" data-searchbar-from="true" name="from_date"
                                        value="<?= $search_bar_data['from_date'] ?? '' ?>"
                                        placeholder="Select From Date" class="form-control"
                                        tts-error-msg="Please select from date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>To Date</label>
                                    <input type="text" data-searchbar-to="true" name="to_date"
                                        value="<?= $search_bar_data['to_date'] ?? '' ?>" placeholder="Select To Date"
                                        class="form-control" tts-error-msg="Please select to date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group form-mb-20">
                                    <label></label><br />
                                    <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php if (!empty($search_bar_data['key'])): ?>
                                    <div class="search-reset-btn">
                                        <a href="<?= site_url('coupon/car-coupon'); ?>">Reset Search</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <!----------End Search Bar ----------------->
                </div>



                <?php
                $trash_uri = "coupon/remove-car-coupon";
                ?>
                <form action="<?= site_url($trash_uri); ?>" method="POST" tts-form="true" id="formdiscountlist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php if (permission_access("Coupon", "cruise_coupon_status_change") || permission_access("Coupon", "remove_car_coupon")) { ?>

                                        <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                        </th>
                                    <?php } ?>
                                    <th>Coupon Code</th>
                                    <th>Travel Date From</th>
                                    <th>Travel Date To</th>
                                    <th>Coupon Type</th>
                                    <th>value</th>
                                    <th>Minm Order Value</th>
                                    <th>Maxm Order Value</th>
                                    <th>Minm Cars</th>
                                    <th>Maxm Cars</th>
                                    <th>Max Limit</th>
                                    <th>Use Limit</th>
                                    <th>Expire Validity</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <?php if (permission_access("Coupon", "car_coupon_details_list")) { ?>
                                        <th>Action</th>

                                    <?php } ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {

                                    foreach ($list as $data) {

                                        if ($data['status'] == 'active') {
                                            $class = 'active-status';
                                        } else {
                                            $class = 'inactive-status';
                                        }

                                ?>
                                        <tr>
                                            <?php if (permission_access("Coupon", "car_coupon_status_change") || permission_access("Coupon", "remove_cruise_coupon")) { ?>

                                                <td>
                                                    <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                            value="<?= $data['id']; ?>" /></label>
                                                </td>
                                            <?php } ?>
                                            <td>


                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='coupon'
                                                    data-id="<?= dev_encode($data['id']); ?>"
                                                    data-href="<?= site_url('coupon/coupon-car-details/') . dev_encode($data['id']); ?>">
                                                    <?= ucfirst($data['code']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= date('d M,Y', $data['travel_date_from']); ?>
                                            </td>
                                            <td>
                                                <?= date('d M,Y', $data['travel_date_to']); ?>
                                            </td>
                                            <td>
                                                <?= ucfirst($data['coupon_type']); ?>
                                            </td>

                                            <td>
                                                <?= ucwords($data['value']); ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['minm_order']) ? $data['minm_order'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['maxm_order']) ? $data['maxm_order'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['minm_car']) ? $data['minm_car'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['maxm_car']) ? $data['maxm_car'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= ucfirst($data['max_limit']); ?>
                                            </td>
                                            <td>
                                                <?= $data['use_limit']; ?>
                                            </td>
                                            <td>
                                                <?= date('d M,Y', $data['valid_from']) . ' To ' . date('d M,Y', $data['valid_to']); ?>
                                            </td>
                                            <td>
                                                <span class="<?= $class ?>">
                                                    <?= ucfirst($data['status']); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?= date('d M,Y', $data['created']) ?>
                                            </td>
                                            <?php if (permission_access("Coupon", "car_coupon_details_list")) { ?>
                                                <td>
                                                    <a href="javascript:void(0);" view-data-modal="true" data-controller='coupon'
                                                        data-id="<?= dev_encode($data['id']); ?>"
                                                        data-href="<?= site_url('coupon/coupon-car-details/') . dev_encode($data['id']); ?>">View</a>
                                                </td>

                                            <?php } ?>

                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='12' class='text-center'><b>No Car Coupon Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </form>


                <div class="row pagiantion_row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page
                            <?= $pager->getCurrentPage() ?>
                            of
                            <?= $pager->getPageCount() ?>, total
                            <?= $pager->getTotal() ?> records found
                        </p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($pager): ?>
                            <?= $pager->links() ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('coupon/car-coupon-status-change'); ?>" method="post" tts-form="true"
                name="form_change_status">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">

                                <select class="form-select" name="status">
                                    <option value="" selected="selected">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <input type="hidden" name="checkedvalue">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Save</button>

                </div>
            </form>
        </div>
    </div>
</div>