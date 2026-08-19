<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-4">
                        <span> Customer Account Logs</span>
                    </div>
                    <div class="col-8 text-end">
                        <a href="<?php echo site_url('customer') ?>" class="badge badge-wt"><i class="fa fa-list"></i> Customer List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                    <div class="row">
                    <?php
                        $title = '';
                        $first_name = '';
                        $last_name = '';
                        $status = '';

                        if (isset($details['title']) && !empty($details['title']) && $details['title'] !== '') {
                            $title = ucfirst($details['title']);
                        }
    
                        if (isset($details['first_name']) && !empty($details['first_name']) && $details['first_name'] !== '') {
                            $first_name = ucfirst($details['first_name']);
                        }
    
                        if (isset($details['last_name']) && !empty($details['last_name']) && $details['last_name'] !== '') {
                            $last_name = ucfirst($details['last_name']);
                        }

                        if (isset($details['status']) && !empty($details['status']) && $details['status'] !== '') {
                            $status = ucfirst($details['status']);
                        }
  
                    ?> 

                        <div class="col-3">
                            <div class="vi_mod_dsc">
                                <span>Name</span>
                                <span class="primary"> <b><?php echo $title . ' ' . $first_name . ' ' . $last_name; ?></b> </span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="vi_mod_dsc">
                                <span>Email </span>
                                <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="vi_mod_dsc">
                                <span>Mobile No</span>
                                <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="vi_mod_dsc">
                                <span>Status</span>
                                <span class="primary"> <b><?php echo $status; ?> </b> </span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="vi_mod_dsc">
                                <span>Available Balance</span>
                                <span class="primary">
                                    <b>
                                        <?php
                                        if(isset( $details['balance'])) {
                                            echo custom_money_format($details['balance']);
                                        } else {
                                            echo "0";
                                        }
                                        ?>
                                    </b>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('customer/customer-account-logs/' . dev_encode($details['id'])); ?>"
                    method="GET" class="tts-dis-content" name="customer-account-log-search"
                    onsubmit="return searchvalidateForm()">
                    <div class="row"> 
                    <div class="col-md-3">
                        <div class="form-group form-mb-20">
                            <label>Select key to search by </label>
                            <select name="key" class="form-select" onchange="tts_searchkey(this,'customer-account-log-search')"
                                    tts-error-msg="Please select search key">
                                <option value="">Please select</option>
                                <option value="credit" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'credit') {
                                    echo "selected";
                                } ?>>Credit
                                </option>
                                <option value="debit" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'debit') {
                                    echo "selected";
                                } ?> >Debit
                                </option>
                                <option value="balance" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'balance') {
                                    echo "selected";
                                } ?> >Balance
                                </option>
                                <option value="invoice_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'invoice_number') {
                                    echo "selected";
                                } ?> >Invoice Number
                                </option>

                                <option value="service" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'service') {
                                    echo "selected";
                                } ?> >Service
                                </option>

                                 <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                    echo "selected";
                                } ?>>Date Range</option>
                            </select>
                        </div>
                        <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                            echo trim($search_bar_data['key-text']);
                        } ?>">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-mb-20">
                            <label>
                                <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                    echo $search_bar_data['key-text'] . "";
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
                                     } else { /*  echo 'tts-validatation="Required"';  */
                                     } ?> tts-error-msg="Please enter value"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-mb-20">
                            <label>From Date</label>
                            <input type="text" data-searchbar-from="true" name="from_date"
                                   value="<?php if (isset($search_bar_data['from_date'])) {
                                       echo $search_bar_data['from_date'];
                                   } else {
                                       echo date('d M Y');
                                   } ?>" placeholder="Select From Date" class="form-control"
                                   tts-error-msg="Please select from date" readonly/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-mb-20">
                            <label>To Date</label>
                            <input type="hidden" name="export_excel" value="0">
                            <input type="text" data-searchbar-to="true" name="to_date"
                                   value="<?php if (isset($search_bar_data['to_date'])) {
                                       echo $search_bar_data['to_date'];
                                   } else {
                                       echo date('d M Y');
                                   } ?>" placeholder="Select To Date" class="form-control"
                                   tts-error-msg="Please select to date" readonly/>
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group form-mb-20">
                            
                            <button type="submit" class="badge badge-md badge-primary badge_search" onclick="noExportExcel()">
                                Search <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    <? if (isset($search_bar_data['key'])): ?>
                        
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('customer/customer-account-logs/' . dev_encode($details['id'])); ?>">Reset
                                        Search</a>
                                </div>
                      
                    <? endif ?>
                    </div>
                   
                    <? if (permission_access("Customer", "export_account_logs")): ?>
                    <div class="col-md-8 text-end align-self-end">
                        <div class="form-group form-mb-20">
                            <button type="submit" class="btn_excel"  onclick="exportExcel()">
                                <img src="<?php echo site_url('webroot/img/excel.svg'); ?>" class="img_fluid">
                            </button>
                        </div>
                    </div>
                    <? endif ?>

                    </div>
                </form>
               
                <div class="table-responsive">

                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <th>Sr.No.</th>
                                <th>Ref.No.</th>
                                <th>Invoice No.</th>
                                <th>Credit Note No.</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                                <th>Date</th>
                                <th>Payments Type</th>
                                <th>Staff Name</th>
                                <th style="white-space:unset">Summary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($account_logs) && is_array($account_logs)) {
                                foreach ($account_logs as $key => $data) {

                                    ?>
                            <tr>
                                <td>
                                    <?php echo $key + 1; ?>
                                </td>
                                <td>

                                </td>
                                <td>
                                    <?php echo $data['invoice_number']; ?>
                                </td>
                                <td>-</td>
                                <td>
                                    <?php echo custom_money_format($data['debit']); ?>
                                </td>
                                <td>
                                    <?php echo custom_money_format($data['credit']); ?>
                                </td>
                                <td>
                                    <?php echo custom_money_format(round_value($data['balance'])); ?>
                                </td>
                                <td>
                                    <?php
                                    echo date_created_format($data['created']);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $transaction_id = '';
                                    if (isset($data['transaction_id'])) {
                                        $transaction_id = '-' . $data['transaction_id'];
                                    }

                                    echo $data['payment_mode'] != "" ? "<b></b> " . str_replace("_", " ", $data['payment_mode']) . $transaction_id . "<br/>" : ""; ?>
                                </td>


                                <td>
                                    <?php echo !empty($data['web_partner_staff_name']) ? ucfirst($data['web_partner_staff_name']) : ''; ?>
                                </td>

                                <td style="white-space:unset">
                                    <a href="javascript:void(0);" view-data-modal="true" data-controller='customer'
                                        data-href="<?php echo site_url('/customer/view-remark/') . dev_encode($data['id']) ?>">View</a>

                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Account Logs Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>


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
