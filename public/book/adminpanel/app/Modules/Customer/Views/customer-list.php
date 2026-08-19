<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> Customer List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">

                        <?php if (permission_access("Customer", "add_customer")) { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-controller='customer'
                                    data-href="<?php echo site_url('customer/add-customer-template') ?>"><i
                                        class="fa-solid fa-add"></i> Add Customer
                            </button>
                        <?php } ?>
                        <?php if (permission_access("Customer", "customer_status")) { ?>
                            <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                        class="fa-solid fa-exchange"></i> Change Status
                            </button>
                        <?php } ?>
                        <?php if (permission_access("Customer", "customer_export")) { ?>
                            <?php if ($pager->getTotal() != 0) : ?>
                                <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"><i
                                            class="fa-solid fa-download"></i> Export
                                </button>
                            <?php endif ?>
                        <?php } ?>
                        <?php if (permission_access("Customer", "delete_customer")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                    onclick="confirm_delete('formcustomerlist')"><i class="fa-solid fa-trash"></i> Delete
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                
                    <!----------Start Search Bar ----------------->
                    <form class="row g-3 mb-3" action="<?php echo site_url('customer'); ?>" method="GET" class="tts-dis-content"
                          name="customer-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'customer-search')"
                                        tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                        echo "selected";
                                    } ?>>First Name
                                    </option>
                                    <option value="email_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'email_id') {
                                        echo "selected";
                                    } ?> >Email ID
                                    </option>
                                    <option value="mobile_no" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'mobile_no') {
                                        echo "selected";
                                    } ?> >Mobile No
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
                            <div class="form-group">
                                <label class="form-label"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                        echo $search_bar_data['key-text'] . " *";
                                    } else {
                                        echo "Value";
                                    } ?> </label>
                                <input type="text" name="value" placeholder="Value"
                                       value="<?php if (isset($search_bar_data['value'])) {
                                           echo $search_bar_data['value'];
                                       } ?>"
                                       class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                    echo "disabled";
                                } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                } else {
                                    echo 'tts-validatation="Required"';
                                } ?> tts-error-msg="Please enter value"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">From Date</label>
                                <input type="text" data-searchbar-from="true" name="from_date"
                                       value="<?php if (isset($search_bar_data['from_date'])) {
                                           echo $search_bar_data['from_date'];
                                       } ?>" placeholder="Select From Date" class="form-control"
                                       tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">To Date</label>
                                <input type="text" data-searchbar-to="true" name="to_date"
                                       value="<?php if (isset($search_bar_data['to_date'])) {
                                           echo $search_bar_data['to_date'];
                                       } ?>" placeholder="Select To Date" class="form-control"
                                       tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group">
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa-solid fa-search"></i></button>
                            </div>
                        </div>
                        <? if (isset($search_bar_data['key'])): ?>
                            <div class="col-md-2">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('customer'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
               

                <!----------End Search Bar ----------------->

                <div class="table-responsive">
                    <?php $trash_uri = "customer/remove-customer"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formcustomerlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if (permission_access("Customer", "delete_customer") || permission_access("Customer", "customer_status")) { ?>
                                    <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                    </th>
                                <?php } ?>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Balance</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                                <th>Action</th>
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

                                    if ($data['email_verify'] == 1) {
                                        $email_class = 'fa-check text-success';
                                        $email_verify = "Email Verified";
                                    } else {
                                        $email_class = 'fa-xmark text-danger';
                                        $email_verify = "Email Not Verified";
                                    }

                                    if ($data['mobile_verify'] == 1) {
                                        $mobile_class = 'fa-check text-success';
                                        $mobile_verify = "Mobile Verified";
                                    } else {
                                        $mobile_class = 'fa-xmark text-danger';
                                        $mobile_verify = "Mobile Not Verified";
                                    }
                                    ?>

                                    <tr>
                                        <?php if (permission_access("Customer", "delete_customer") || permission_access("Customer", "customer_status")) { ?>
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                              value="<?php echo $data['id']; ?>"/></label>
                                            </td>
                                        <?php } ?>

                                        <td> <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                                               data-controller='customer'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('customer/customer-details/') . dev_encode($data['id']); ?>"><?php echo $data['customer_id']; ?> </a>
                                            
                                        </td>


                                        




                                        <td>
                                        <?php
                                            $title = '';
                                            $first_name = '';
                                            $last_name = '';
                                           

                                            if (isset($details['title']) && !empty($details['title']) && $details['title'] !== '') {
                                                $title = ucfirst($details['title']);
                                            }
                        
                                            if (isset($details['first_name']) && !empty($details['first_name']) && $details['first_name'] !== '') {
                                                $first_name = ucfirst($details['first_name']);
                                            }
                        
                                            if (isset($details['last_name']) && !empty($details['last_name']) && $details['last_name'] !== '') {
                                                $last_name = ucfirst($details['last_name']);
                                            }
 
                                            ?> 

                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                                               data-controller='customer'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('customer/customer-details/') . dev_encode($data['id']); ?>"><?php echo $title . ' ' . $first_name . ' ' . $last_name; ?></a>
                                        </td>
                                        <td>
                                        
                                            <?php echo $data['email_id']; ?>
                                                <? if($data['email_id']):?>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $email_verify; ?>"><i class="fa-solid <?php echo $email_class; ?>"></i></span>
                                            <? endif ?>    
                                        </td>

                                        <td>
                                            <?php echo $data['mobile_no']; ?>
                                            <? if($data['mobile_no']):?>
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $mobile_verify; ?>"><i class="fa-solid <?php echo $mobile_class; ?>"></i></span>
                                            <? endif ?>                                                
                                        </td>

                                        <td>
                                            <span class="<?php echo $class ?>">
                                            <?php echo (isset($data['status']) && !empty($data['status'])) ? ucfirst($data['status']) : '';  ?>
                                            </span>
                                        </td>
                                        <td><?php echo custom_money_format($data['balance']); ?></td>
                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <td><?php
                                            if (isset($data['modified'])) {
                                                echo date_created_format($data['modified']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>

                                                <button class="actbtn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                                <div class="dropdown-menu">
                                                    <?php if (permission_access("Customer", "edit_customer")) { ?>
                                                        <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                                           data-controller='customer'
                                                           data-id="<?php echo dev_encode($data['id']); ?>"
                                                           data-href="<?php echo site_url('/customer/edit-customer-template/') . dev_encode($data['id']); ?>">Edit
                                                            Customer</i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if (permission_access("Customer", "virtual_topup")) { ?>
                                                        <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                                           data-controller='customer' data-id="<?php echo dev_encode($data['id']); ?>"
                                                           data-href="<?php echo site_url('/customer/virtual-topup-template/') . dev_encode($data['id']);?>">Virtual
                                                            Top Up 
                                                            </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if (permission_access("Customer", "virtual_deduct")) { ?>
                                                        <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                                           data-controller='customer'
                                                           data-id="<?php echo dev_encode($data['id']); ?>"
                                                           data-href="<?php echo site_url('/customer/virtual-debit-template/') . dev_encode($data['id']);?>">Virtual
                                                            Deduct
                                                            </i>
                                                        </a>
                                                    <?php } ?>
                                                   
                                                   <?php if (permission_access("Customer", "account_logs")) { ?>
                                                        <a class="dropdown-item" href="<?php echo site_url('/customer/customer-account-logs/') . dev_encode($data['id']); ?>">Account
                                                            Logs</a>
                                                    <?php } ?>
                                                    <?php if (permission_access("Customer", "change_password")) { ?>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="change_password_modal( '<?php echo dev_encode($data['id']) ?>', '<?php echo ucfirst(str_replace(' ', '-', $data['first_name'] ?? '')) ?>' )">Change Password</a>
                                                    <?php } ?>

                                                  
                                                    <?php if (permission_access("Customer", "access_account")) { ?>
                                                        <a class="dropdown-item" href="<?php echo root_url . 'access-account/' . dev_encode($data['email_id'] . '-' . $data['id'] . '-' . $UserIp); ?>"
                                                        target="_blank">Access Account</a>
                                                    <?php } ?>
                                                </div>
                                            

                                        </td>

                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Customer Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>
                </div>
                
                        <div class="row pagiantion_row align-items-center">
                            <div class="col-md-6">
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
</div>
</div>


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('customer/customer-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

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


<!-- Show  status Modal content -->


<!-- status password change  change content -->
<div id="password_change" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('customer/change-customer-password'); ?>" method="post" tts-form="true" name="form_password_change">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Customer Name : <strong class="company_name tts_agent_company"></strong></p>
                            </div>
                            <div class="form-group">
                                <label>New Password*</label>
                                <input class="form-control" type="text" name="password" placeholder="Password">
                                <button class="badge badge-wt mt-1" type="button"
                                        onclick=generatePassword(10,'form_password_change');>Generate Password
                                </button>
                            </div>
                            <input type="hidden" name="customer_id" class="tts_agent_id">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="send" name="send_email" id="customer-send-email">
                                <label class="form-check-label" for="customer-send-email">
                                Send account details on customer email id
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- start export modal content -->
<div id="tts_export_modal" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('customer/export-customer'); ?>" method="post" tts-form="true">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Date *</label>
                                <input class="form-control" type="text" name="from_date" data-export-from="true"
                                       placeholder="Select From Date" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To Date *</label>
                                <input class="form-control borl0" type="text" name="to_date" data-export-to="true"
                                       placeholder="Select To Date" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end export modal content -->

