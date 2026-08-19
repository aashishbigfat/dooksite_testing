<div class="content ">
    <div class="page-content">
        <?php echo view('\Modules\Cruise\Views\menu-bar'); ?>
        <div class="page-content-area">
            <div class="card-body">
                <div class="">
                    <div class="col-md-12 settings-panel">
                        <div class="page-actions-panel">
                            <div class="row align-items-center">
                                <div class="col-md-4 ">
                                    <h5 class="m0">Cruise Cabin List</h5>
                                </div>
                                <div class="col-md-8 text-md-end">
                                    <?php if (permission_access("Cruise", "add_cruise_cabin")) { ?>

                                        <button class="badge badge-wt" view-data-modal="true" data-controller='cruise' data-href="<?php echo site_url('cruise/add-cruise-cabin-template') ?>">
                                            <i class="fa-solid fa-add"></i> Add Cruise Cabin
                                        </button>
                                    <?php } ?>

                                    <?php if (permission_access("Cruise", "cruise_cabin_status")) { ?>
                                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                                            <i class="fa-solid fa-exchange"></i> Change Status
                                        </button>
                                    <?php } ?>

                                    <?php if (permission_access("Cruise", "delete_cruise_cabin")) { ?>
                                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formgallerylist')"><i class="fa-solid fa-trash "></i> Delete
                                        </button>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row setting-content">
                                <!----------Start Search Bar ----------------->
                                <form action="<?php echo site_url('cruise/cruise-cabin-list'); ?>" method="GET" name="cruise-cabin-search" onsubmit="return searchvalidateForm()">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group form-mb-20">
                                                <label>Select key to search by *</label>
                                                <select name="key" class="form-control" onchange="tts_searchkey(this,'cruise-cabin-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                                    <option value="">Please select</option>
                                                    <option value="cabin_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'cabin_name') {
                                                                                    echo "selected";
                                                                                } ?>>Cruise Cabin
                                                    </option>
                                                    <option value="ship_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ship_name') {
                                                                                    echo "selected";
                                                                                } ?>>Cruise Ship
                                                    </option>
                                                    <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                    echo "selected";
                                                                                } ?>>Date Range
                                                    </option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                                            echo trim($search_bar_data['key-text']);
                                                                                        } ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group form-mb-20">
                                                <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                                            echo $search_bar_data['key-text'] . " *";
                                                        } else {
                                                            echo "Value";
                                                        } ?> </label>
                                                <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                                                                                                                echo $search_bar_data['value'];
                                                                                                            } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                    echo "disabled";
                                                                                } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                    } else {
                                                        echo 'tts-validatation="Required"';
                                                    } ?> tts-error-msg="Please enter value" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group form-mb-20">
                                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                                    echo $search_bar_data['from_date'];
                                                                                                                                                } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group form-mb-20">
                                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                                        } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-2 align-self-end">
                                            <div class="form-group form-mb-20">

                                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                                            </div>
                                        </div>

                                        <?php if (isset($search_bar_data['key'])) : ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="search-reset-btn">
                                                    <a href="<?php echo site_url('cruise/cruise-cabin-list'); ?>">Reset Search</a>
                                                </div>
                                            </div>
                                        <?php endif ?>

                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="setting-content">
                            <div class="col-md-12">


                                <?php $trash_uri = "cruise/remove-cruise-cabin"; ?>
                                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formgallerylist">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-active">
                                                <tr>

                                                    <?php if (permission_access("Cruise", "delete_cruise_cabin") || permission_access("Cruise", "cruise_cabin_status")) { ?>
                                                        <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                                        </th>
                                                    <?php } ?>


                                                    <th>Cabin Name</th>
                                                    <th>Slug</th>
                                                    <th>Ship</th>
                                                    <th>Status</th>
                                                    <th>Created Date</th>
                                                    <th>Modified Date</th>
                                                    <?php if (permission_access("Cruise", "edit_cruise_cabin")) { ?>
                                                        <th>Action</th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if (!empty($list) && is_array($list)) {
                                                    foreach ($list as $data) {

                                                        if ($data['status'] == 'active') {
                                                            $status_class = 'active-status';
                                                        } else {
                                                            $status_class = 'inactive-status';
                                                        }
                                                ?>

                                                        <tr>
                                                            <?php if (permission_access("Cruise", "delete_cruise_cabin") || permission_access("Cruise", "cruise_cabin_status")) { ?>

                                                                <td>
                                                                    <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                                                </td>
                                                            <?php } ?>

                                                            <td>
                                                                <?php echo $data['cabin_name']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $data['cabin_slug'] ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $data['ship_name']; ?>
                                                            </td>

                                                            <td>

                                                                <span class="<?php echo $status_class ?>">
                                                                    <?php echo ucfirst($data['status']); ?>
                                                                </span>

                                                            </td>

                                                            <td><?php echo date_created_format($data['created']); ?></td>
                                                            <td><?php
                                                                if (isset($data['modified'])) {
                                                                    echo date_created_format($data['modified']);
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>

                                                            <?php if (permission_access("Cruise", "edit_cruise_cabin")) { ?>
                                                                <td>
                                                                    <a href="javascript:void(0);" view-data-modal="true" data-controller='cruise' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/cruise/edit-cruise-cabin-template/') . dev_encode($data['id']); ?>"><i class="fa fa-edit"></i></a>
                                                                </td>
                                                            <?php } ?>

                                                        </tr>
                                                <?php }
                                                } else {
                                                    echo "<tr> <td colspan='11' class='text-center'><b>No Cruise Cabin Found </b></td></tr>";
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>



                                <div class="row pagiantion_row align-items-center">
                                    <div class="col-md-6 mb-3 mb-lg-0">
                                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                            of <?= $pager->getPageCount() ?>,
                                            total <?= $pager->getTotal() ?> records found </p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if ($pager) : ?>
                                            <?= $pager->links() ?>
                                        <?php endif ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

 
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('cruise/cruise-cabin-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">

                                <select class="form-control" name="status">
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
                    <button class="btb btn-primary" type="submit">Save</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>