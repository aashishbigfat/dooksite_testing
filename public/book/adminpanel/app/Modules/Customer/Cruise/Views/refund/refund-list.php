<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">  Refund List</h5>
                    </div>
                    <div class="col-md-8 text-md-right">
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('cruise/refunds'); ?>" method="GET" class="tts-dis-content"
                              name="flight-refunds-search" onsubmit="return searchvalidateForm()">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select key to search by </label>
                                    <select name="key" class="form-control"
                                            onchange="tts_searchkey(this,'flight-refunds-search')"
                                            tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                            echo "selected";
                                        } ?>>Booking Ref Number
                                        </option>
                                        <option value="refund_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'refund_status') {
                                            echo "selected";
                                        } ?>>Refund Status
                                        </option>

                                        <option value="id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'id') {
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
                            <div class="col-md-4">
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
                                        /*  echo 'tts-validatation="Required"'; */
                                    } ?> >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>From Date</label><input type="text" data-searchbar-from="true"
                                                                   name="from_date"
                                                                   value="<?php if (isset($search_bar_data['from_date'])) {
                                                                       echo $search_bar_data['from_date'];
                                                                   } else {
                                                                       echo date('d M Y');
                                                                   } ?>" placeholder="Select From Date"
                                                                   class="form-control"
                                                                   tts-error-msg="Please select from date" readonly/>
                                </div>
                            </div>
                            <input type="hidden" name="export_excel" value="0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                                 value="<?php if (isset($search_bar_data['to_date'])) {
                                                                     echo $search_bar_data['to_date'];
                                                                 } else {
                                                                     echo date('d M Y');
                                                                 } ?>" placeholder="Select To Date" class="form-control"
                                                                 tts-error-msg="Please select to date" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group">
                                    
                                    <button type="submit" class="badge badge-md badge-primary badge_search"
                                            onclick="noExportExcel()">Search <i class="fa-solid fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!--<div class="col-md-2">
                               <div class="form-group">
                                  <label></label><br />
                                  <button type="submit" class="badge badge-md badge-primary" onclick="exportExcel()">Export Excel</button>
                               </div>
                            </div>-->
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="col-md-2">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('cruise/refunds'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                        </form>
                    </div>
                    <!----------End Search Bar ----------------->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>

                                <th>Booking Ref. No.</th>

                                <th>Amendment Id</th>
                                <th>Refund Type</th>
                                <th>Refund Amount</th>
                                <th>Refund Status</th>
                                <th>Cruise Line</th>
                                <th>Ship Name</th>
                                <th>Travel Date</th>
                                <th>Web Partner</th>
                                <th>Admin Staff</th>
                                <th>Remark</th>
                                <th>Created</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if ($data['refund_status'] == 'Close') {
                                        $class = 'tts-text-success';
                                    } else {
                                        $class = 'tts-text-danger';
                                    }


                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo site_url('/cruise/cruise-booking-details/') . $data['booking_ref_number']; ?>"
                                               target="<?php echo target ?>"><?php echo $data['booking_ref_number']; ?></a>
                                        </td>


                                        <td>
                                            <a href="<?php echo site_url('/cruise/amendments-details/') . $ticketData = dev_encode($data['id']); ?>">
                                                <i class="fa fa-eye"> </i>  <?php echo $data['id']?>
                                            </a>
                                        </td>
                                        <td><?php echo ucfirst($data['amendment_type']); ?></td>
                                        <td> ₹ <?php echo ucfirst($data['refund_amount']); ?></td>
                                        <td>
                                            <span class="<?php echo $class; ?>"  <?php if ($data['refund_status'] == 'Open') { ?> onclick='flight_refund_close_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($data["company_name"]) ?>")' <?php } ?>> <?php echo ucfirst($data['refund_status']); ?></span>
                                        </td>
                                        <td><?php echo ucwords($data['cruise_line_name']); ?></td>
                                        <td><?php echo ucwords($data['ship_name']); ?></td>
                                        <td><?php echo ($data['sailing_date']); ?></td>
                                        <td><?php echo ucfirst($data['company_name']); ?>
                                            (<?php echo $data['company_id']; ?>)
                                        </td>
                                        <td><?php echo $data['super_admin_staff_name']; ?></td>
                                        <td><?php echo ucfirst($data['account_remark']); ?></td>

                                        <td><?php echo date_created_format($data['created']); ?>  </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='14' class='text_center'><b>No Refund Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="row pagiantion_row align-items-center">
                        <div class="col-md-6 mb-3 mb-lg-0">
                            <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
                            </p>
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
    <div id="flight_refund_close" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Close Refund Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo site_url('cruise/refund-close'); ?>" method="post" tts-form="true" name="flight_refund_close">
                   
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>Web Partner : <strong
                                                class="company_name tts_agent_company"></strong></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Refund Status</label>
                                        <select class="form-control" name="status">
                                            <option value="Close">Close</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="amendment_id" class="amendment_id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark*</label>
                                        <textarea class="form-control" name="account_remark" rows="3" cols="15"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                         <button class="btn btn-primary" type="submit">Refund</button>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>