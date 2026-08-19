<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0">Hajj Coupon</h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php //if(permission_access("Coupon", "coupon_hajj_add")) { 
                        ?>

                        <button class="badge badge-wt" view-data-modal="true" data-controller='flight'
                            data-href="<?= site_url('coupon/hajj-coupon-view') ?>"><i class="fa-solid fa-add"></i> Add
                            Hajj Coupon
                        </button>
                        <?php //} 
                        ?>

                        <?php //if(permission_access("Coupon", "coupon_hajj_change_status")) { 
                        ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                class="fa-solid fa-exchange"></i> Change Status
                        </button>
                        <?php //} 
                        ?>
                        <?php //if(permission_access("Coupon", "coupon_hajj_delete")) { 
                        ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                            onclick="confirm_delete('hajjcouponlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>
                        <?php //} 
                        ?>

                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!----------Start Search Bar ----------------->
                    <form action="<?= site_url('coupon/hajj-coupon'); ?>" method="GET" class="tts-dis-content row mb-3"
                        name="markup-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')"
                                    tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="code" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'code' ? 'selected' : '' ?>>Code</option>
                                    <option value="date-range" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'date-range' ? 'selected' : '' ?>>Date Range</option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text"
                                value="<?= !empty($search_bar_data['key-text']) ? trim($search_bar_data['key-text']) : '' ?>">
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>
                                    <?= !empty($search_bar_data['key']) && $search_bar_data['key'] != 'date-range' ? ($search_bar_data['key-text'] . " *") : "Value" ?>
                                </label>
                                <input type="text" name="value" placeholder="Value"
                                    value="<?= !empty($search_bar_data['value']) ? $search_bar_data['value'] : '' ?>"
                                    class="form-control" <?= !empty($search_bar_data['key']) && $search_bar_data['key'] == 'date-range' ? 'disabled' : 'tts-validatation="Required"' ?> tts-error-msg="Please enter value" />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>From Date</label>
                                <input type="text" data-searchbar-from="true" name="from_date"
                                    value="<?= !empty($search_bar_data['from_date']) ? $search_bar_data['from_date'] : '' ?>"
                                    placeholder="Select From Date" class="form-control"
                                    tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label>
                                <input type="text" data-searchbar-to="true" name="to_date"
                                    value="<?= !empty($search_bar_data['to_date']) ? $search_bar_data['to_date'] : '' ?>"
                                    placeholder="Select To Date" class="form-control"
                                    tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>

                        <div class="col-md-2 align-self-end">
                            <div class="form-group form-mb-20">
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <?php if (!empty($search_bar_data['key'])): ?>
                                <div class="search-reset-btn">
                                    <a href="<?= site_url('coupon/hajj-coupon'); ?>">Reset Search</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>

                </div>

                <!----------End Search Bar ----------------->


                <?php
                $trash_uri = "coupon/remove-hajj-coupon";
                ?>
                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="hajjcouponlist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php //if (permission_access("Coupon", "coupon_hajj_change_status") || permission_access("Coupon", "coupon_hajj_delete")) { 
                                    ?>
                                    <th>
                                        <label>
                                            <input type="checkbox" name="check_all" id="selectall" />
                                        </label>
                                    </th>
                                    <?php //} 
                                    ?>

                                    <th>Coupon Code</th>
                                    <th>Travel Date From</th>
                                    <th>Travel Date To</th>
                                    <th>Coupon Type</th>
                                    <th>Value</th>
                                    <th>Minm Order Value</th>
                                    <th>Maxm Order Value</th>
                                    <th>Minm Pax</th>
                                    <th>Maxm Pax</th>
                                    <th>Max Limit</th>
                                    <!-- <th>Theme Name</th> -->
                                    <!-- <th>Destination Name</th> -->
                                    <th>Status</th>
                                    <th>Created</th>
                                    <?php //if(permission_access("Coupon", "coupon_hajj_detail_list")) { 
                                    ?>
                                    <th>Action</th>
                                    <?php //} 
                                    ?>



                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {
                                    foreach ($list as $data) {
                                        $class = $data['status'] == 'active' ? 'active-status' : 'inactive-status';
                                ?>
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="checklist[]" class="checkbox"
                                                        value="<?= $data['id']; ?>" />
                                                </label>
                                            </td>

                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='coupon'
                                                    data-id="<?= dev_encode($data['id']); ?>"
                                                    data-href="<?= site_url('coupon/coupon-hajj-details/') . dev_encode($data['id']); ?>">
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
                                                <?= $data['value']; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['minm_order']) ? $data['minm_order'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['maxm_order']) ? $data['maxm_order'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['minm_pax']) ? $data['minm_pax'] : '-'; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data['maxm_pax']) ? $data['maxm_pax'] : '-'; ?>
                                            </td>

                                            <td>
                                                <?= $data['max_limit']; ?>
                                            </td>

                                            <!-- <td>
                                                <?= $data['theme_name']; ?>
                                            </td> -->
                                            <?php
                                            /* <td>
                                                <?= $data['destination_name']; ?>
                                            </td> */
                                            ?>

                                            <td>
                                                <span class="<?= $class ?>">
                                                    <?= ucfirst($data['status']); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?= date_created_format($data['created']); ?>
                                            </td>

                                            <?php //if(permission_access("Coupon", "coupon_hajj_detail_list")) { 
                                            ?>
                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='coupon'
                                                    data-id="<?= dev_encode($data['id']); ?>"
                                                    data-href="<?= site_url('coupon/coupon-hajj-details/') . dev_encode($data['id']); ?>">
                                                    View
                                                </a>
                                            </td>

                                            <?php //} 
                                            ?>

                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='12' class='text-center'><b>No  Hajj Coupon Found</b></td></tr>";
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
            <form action="<?= site_url('coupon/hajj-coupon-status-change'); ?>" method="post" tts-form="true"
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
                    <button class="btn btn-primary" type="submit" value="Save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show  status Modal content -->

<?php ?>