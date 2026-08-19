<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0"> Amendments List</h5>
                    </div>
                    <div class="col-md-8 text-md-right">

                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <div class="row">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('cruise/amendments'); ?>" method="GET" id="sales-export"
                              class="col-md-12" name="markup-search" onsubmit="return searchvalidateForm()">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> Search by Web Partner / Company Name </label>
                                        <input type="text" class="form-control" name="search-text"
                                               value="<?php if (isset($search_bar_data['search-text'])) {
                                                   echo trim($search_bar_data['search-text']);
                                               } ?>" tts-get-web-partner-info="true"
                                               tts-error-msg="Please enter search type"
                                               placeholder="Search by /Companyid/Company Name/ Web Partner Name">
                                        <input type="hidden" name="tts_web_partner_info" tts-web-partner-info-id="true"
                                               value="<?php if (isset($search_bar_data['tts_web_partner_info'])) {
                                                   echo trim($search_bar_data['tts_web_partner_info']);
                                               } ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Select key to search by </label>
                                        <select name="key" class="form-control"
                                                onchange="tts_searchkey(this,'markup-search')"
                                                tts-error-msg="Please select search key">
                                            <option value="">Please select</option>

                                            <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                                echo "selected";
                                            } ?>>Booking Ref Number
                                            </option>


                                            <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                                echo "selected";
                                            } ?>>Booking Status
                                            </option>
                                            <option value="amendment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_status') {
                                                echo "selected";
                                            } ?>>Amendment Status
                                            </option>

                                            <option value="flight_amendment.id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'flight_amendment.id') {
                                                echo "selected";
                                            } ?>>Amendment Id
                                            </option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="key-text"
                                           value="<?php if (isset($search_bar_data['key-text'])) {
                                               echo trim($search_bar_data['key-text']);
                                           } ?>">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
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
                                        } else {
                                            /*   echo 'tts-validatation="Required"'; */
                                        } ?> tts-error-msg="Please enter value"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label><input type="text" data-searchbar-from="true"
                                                                       name="from_date"
                                                                       value="<?php if (isset($search_bar_data['from_date'])) {
                                                                           echo $search_bar_data['from_date'];
                                                                       } else {
                                                                           echo date('d M Y');
                                                                       } ?>" placeholder="Select From Date"
                                                                       class="form-control"
                                                                       tts-error-msg="Please select from date"
                                                                       readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                                     value="<?php if (isset($search_bar_data['to_date'])) {
                                                                         echo $search_bar_data['to_date'];
                                                                     } else {
                                                                         echo date('d M Y');
                                                                     } ?>" placeholder="Select To Date"
                                                                     class="form-control"
                                                                     tts-error-msg="Please select to date" readonly/>
                                    </div>
                                </div>



                                <div class="col-md-3 align-self-end">
                                    <div class="form-group">

                                        <button type="submit" data-url="<?php echo site_url('cruise/amendments'); ?>"
                                                praveen-method="get"
                                                class="badge badge-md badge-primary badge_search export-praveen">Search
                                            <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <? if (isset($search_bar_data['key'])) : ?>
                                        <div class="search-reset-btn">
                                            <a href="<?php echo site_url('cruise/amendments'); ?>">Reset
                                                Search</a>
                                        </div>
                                    <? endif ?>
                                </div>
                                <div class="col-md-5 text-md-right align-self-end">
                                    <div class="form-group form-mb-20">
                                        <button type="submit"
                                                data-url="<?php echo site_url('cruise/export-cruise-amendments'); ?>"
                                                praveen-method="post" id="export-data-btn"
                                                class="btn_excel export-praveen">
                                            <img src="<?php echo site_url('webroot/img/excel.svg'); ?>"
                                                 class="img_fluid">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <!----------End Search Bar ----------------->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>

                                <th>Booking Reference Number</th>
                                <th>Amendment Id</th>
                                <th>Amendment Type</th>
                                <th>Amendment Status</th>
                                <th>Country</th>
                                <th>Type</th>
                                <th>Travel Date</th>

                                <th>Booking Status</th>
                                <th> Remark</th>

                                <th>Generate By</th>
                                <th>Web Partner</th>
                                <th>Admin Staff</th>
                                <th>Created</th>
                                <th>Summary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if ($data['booking_status'] == 'Confirmed') {
                                        $class = 'tts-text-success';
                                    } else {
                                        $class = 'tts-text-danger';
                                    }

                                    if ($data['payment_status'] == 'Successful') {
                                        $payment_class = 'tts-text-success';
                                    } else {
                                        $payment_class = 'tts-text-danger';
                                    }
                                    if ($data['amendment_status'] == 'approved') {
                                        $amendment_status = 'tts-text-success';
                                    } else {
                                        $amendment_status = 'tts-text-danger';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url('/cruise/cruise-booking-details/') . $data['booking_ref_number']; ?>"
                                               target="<?php echo target ?>"><?php echo $data['booking_ref_number']; ?></a>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('/cruise/amendments-details/') . $ticketData = dev_encode($data['id']); ?>">
                                                <i class="fa fa-eye"> </i> <?php echo $data['id'] ?>
                                            </a>
                                        </td>
                                        <td><?php echo ucfirst($data['amendment_type']); ?></td>
                                        <td>
                                            <span class="<?php echo $amendment_status ?>"  <?php if ($data['amendment_status'] == 'requested' || $data['amendment_status'] == 'processing') { ?> onclick='amendment_status_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($data["company_name"]) ?>")' <?php } ?>> <?php echo ucfirst($data['amendment_status']); ?></span>
                                        </td>
                                        <td><?php echo ucwords($data['cruise_line_name']); ?></td>
                                        <td><?php echo ucwords($data['ship_name']); ?></td>
                                        <td><?php echo($data['sailing_date']); ?></td>

                                        <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['booking_status']); ?>
                                                </span>
                                        </td>

                                        <td><?php echo $data['remark_from_web_partner']; ?></td>

                                        <td><?php echo $data['staff_name']; ?></td>
                                        <td><?php echo ucfirst($data['company_name']); ?>
                                            (<?php echo $data['company_id']; ?>)
                                        </td>
                                        <td><?php echo $data['super_admin_staff_name']; ?></td>
                                        <td><?php echo date_created_format($data['created']); ?>  </td>
                                        <td>
                                            <a href="<?php echo site_url('/cruise/amendments-details/') . $ticketData = dev_encode($data['id']); ?>"
                                               target="<?php echo target ?>"><i class="tts-icon eye"> View</i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='14' class='text_center'><b>No Amendments Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row pagiantion_row align-items-center">
                        <div class="col-md-6 mb-3 mb-lg-0">
                            <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
                                found </p>
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
    <div id="amendment_status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Amendment Status Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo site_url('cruise/amendment-status-change'); ?>" method="post" tts-form="true"
                      name="form_password_change">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>Web Partner : <strong
                                                class="company_name tts_agent_company"></strong></p>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="form-control" name="status">
                                            <option value="" selected>Select Amendment Status</option>
                                            <option value="requested">Requested</option>
                                            <option value="processing">Processing</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>


                                <input type="hidden" name="amendment_id" class="amendment_id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark*</label>
                                        <textarea class="form-control" name="admin_remark" rows="3"
                                                  cols="15"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Change Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>