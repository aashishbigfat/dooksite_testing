<div class="content">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-lg-0">
                        <h5 class="m0"> <?php echo $title; ?> List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <?php if (permission_access("Setting", "add_offline_supplier")) { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-href="<?php echo site_url('offline-issue-supplier/add-supplier-template') ?>"><i class="fa fa-add "></i> Add Offline Supplier </button>
                        <?php } ?>
                        <?php if (permission_access("Setting", "change_offline_supplier_status")) { ?>
                            <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <!----------Start Search Bar ----------------->
                <form action="<?php echo site_url('offline-issue-supplier'); ?>" method="GET" class="tts-dis-content" name="offline-provider-search" onsubmit="return searchvalidateForm()">
                    <div class="row ">


                        <div class="col-md-2">
                            <div class="form-group form-mb-20 ">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'offline-provider-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="supplier_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'supplier_name') {
                                                                        echo "selected";
                                                                    } ?>>Supplier Name
                                    </option>
                                    <option value="email" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'email') {
                                                                echo "selected";
                                                            } ?>>Email
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
                        <div class="col-md-2">
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

                        <? if (isset($search_bar_data['key'])) : ?>
                            <div class="col-md-2 align-self-center">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('offline-issue-supplier'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>

                    </div>
                </form>

                <!----------End Search Bar ----------------->


                <?php

                $trash_uri = "offline-issue-supplier/";
                ?>

                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php if (permission_access("Setting", "change_offline_supplier_status")) { ?>
                                        <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                                    <?php } ?>
                                    <th>Service</th>
                                    <th>Supplier Name</th>
                                    <th>Email Id</th>
                                    <th>Mobile Number</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <?php if (permission_access("Setting", "edit_offline_supplier")) { ?>
                                        <th>Action</th>
                                    <?php } ?>
                                </tr>
                            </thead>



                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {
                                    foreach ($list as $data) { ?>       
                                        <tr>
                                            <?php if (permission_access("Setting", "change_offline_supplier_status")) { ?>
                                                <td>
                                                    <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <?php
                                                $status_class = ($data['status'] == 'active') ? 'active-status' : 'inactive-status';
                                                $services = [];
                                                $serviceTypes = array('flight', 'hotel', 'bus', 'holiday', 'visa', 'cruise', 'car', 'activities');

                                                foreach ($serviceTypes as $serviceType) {
                                                    if (isset($data[$serviceType . '_service']) && $data[$serviceType . '_service'] == 'active') {
                                                        $services[] = ucfirst($serviceType);
                                                    }
                                                }

                                                echo implode(', ', $services);
                                                ?>  
                                            </td>
                                            <td><?php echo htmlspecialchars($data['supplier_name']); ?></td>
                                            <td><?php echo htmlspecialchars($data['email']); ?></td>
                                            <td><?php echo htmlspecialchars($data['mobile_no']); ?></td>
                                            <td>
                                                <span class="<?php echo $status_class ?>">
                                                    <?php echo ucfirst($data['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date_created_format($data['created']); ?></td>
                                            <td><?php echo date_created_format($data['modified']); ?></td>
                                            <?php if (permission_access("Setting", "edit_offline_supplier")) { ?>
                                                <td>
                                                    <a href="javascript:void(0);" view-data-modal="true" data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('offline-issue-supplier/edit-supplier-template/') . dev_encode($data['id']); ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='9' class='text-center'><b>No Data Found</b></td></tr>";
                                } ?>
                            </tbody>


                        </table>
                    </div>
                </form>

                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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

    <!-- status status change content -->
    <div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="close" data-bs-toggle="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo site_url('offline-issue-supplier/status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>