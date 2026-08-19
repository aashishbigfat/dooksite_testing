<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="m-0"> Hotel Amendments List</h5>
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">

                    <!----------Start Search Bar ----------------->
                    <form class="row g-3 mb-3" action="<?php echo site_url('hotel/hotel-amendments'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>

                                    <option value="lead_passenger_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'lead_passenger_name') {
                                                                            echo "selected";
                                                                        } ?>>First Name</option>
                                    <option value="lead_passenger_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'lead_passenger_name') {
                                                                            echo "selected";
                                                                        } ?>>Last Name</option>
                                    <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == '=booking_ref_number') {
                                                                            echo "selected";
                                                                        } ?>>Booking Ref Number
                                    </option>


                                    <option value="amendment_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_type') {
                                                                        echo "selected";
                                                                    } ?>>Amendment Type
                                    </option>


                                    <option value=" booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                                                        echo "selected";
                                                                    } ?>>Booking Status
                                    </option>
                                    <option value="amendment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_status') {
                                                                            echo "selected";
                                                                        } ?>>Amendment Status
                                    </option>

                                    <option value="id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'id') {
                                                            echo "selected";
                                                        } ?>>Amendment Id
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
                            <div class="form-group">
                                <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                                        echo $search_bar_data['from_date'];
                                                                                                                                                    } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                                echo $search_bar_data['to_date'];
                                                                                                                                            } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group">

                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa-solid fa-search"></i></button>
                            </div>
                        </div>
                        <? if (isset($search_bar_data['key'])) : ?>
                            <div class="col-md-2">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('hotel/hotel-amendments'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>



                    <!----------End Search Bar ----------------->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <th>Booking Reference Number</th>
                                    <th>Amendment Id</th>
                                    <th>Amendment Type</th>
                                    <th>Amendment Status</th>
                                    <th>Hotel Name</th>
                                    <th>Company Name / Customer</th>
                                    <th>Booking Source</th>
                                    <th>Traveller Name</th>
                                    <th>Checkin Date</th>
                                    <th>Checkout Date</th>
                                    <th>City/ Country</th>
                                    <th>Booking Status</th>
                                    <th>Admin Remark</th>
                                    <th>Generate By</th>
                                    <th>AMD Staff</th>
                                    <th>Created</th>
                                    <th>Summary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {
                                    foreach ($list as $data) {


                                        $class = getStatusClass($data['booking_status']);
                                        $amendment_status = getStatusClass($data['amendment_status']);

                                ?>
                                        <tr>

                                            <td>
                                                <a href="<?php echo site_url('/hotel/details/') . $data['booking_ref_number']; ?>" target="_blank"><?php echo $data['booking_ref_number']; ?></a>
                                            </td>
                                            <td><?php echo ucfirst($data['AmendmentId']); ?></td>
                                            <td>
                                                <?php
                                                if (isset($data['amendment_type'])) {
                                                    $amendmentType = ucwords(str_replace("_", " ", $data['amendment_type']));
                                                    echo htmlspecialchars($amendmentType, ENT_QUOTES, 'UTF-8');
                                                } else {
                                                    echo "Amendment type not available";
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <span class="<?php echo $amendment_status; ?>"
                                                    <?php if (isset($data['amendment_status']) && $data['amendment_status'] === 'requested') { ?>
                                                    onclick='amendment_status_modal("<?php echo dev_encode($data["AmendmentId"] ?? ''); ?>","<?php echo isset($data["company_name"]) ? ucfirst($data["company_name"]) : 'NA'; ?>")'
                                                    <?php } ?>>
                                                    <?php echo isset($data['amendment_status']) ? ucfirst($data['amendment_status']) : ''; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php echo isset($data['hotel_name']) && !empty($data['hotel_name']) ? ucfirst($data['hotel_name']) : 'NA'; ?>
                                            </td>

                                            <td>
                                                <?php
                                                if (isset($data['company_name']) && !empty($data['company_name'])) {
                                                    echo ucfirst($data['company_name']) . ' (' . ($data['company_id'] ?? 'NA') . ')';
                                                } else {
                                                    echo isset($data['customer_name']) ? ucfirst($data['customer_name']) : 'NA';
                                                }
                                                ?>
                                            </td>
                                            <td> <?php echo service_booking_source($data['booking_source'] ?? ""); ?> </td>
                                            <td><?php echo ucfirst($data['lead_passenger_name']); ?></td>
                                            <td><?php echo display_custom_date_format($data['check_in_date']); ?></td>
                                            <td> <?php echo display_custom_date_format($data['check_out_date']); ?></td>
                                            <td><?php echo $data['city'] . '/' . $data['country_code']; ?></td>
                                            <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['booking_status']); ?>
                                                </span>
                                            </td>

                                            <td><?php echo $data['remark_from_web_partner']; ?></td>

                                            <td><?php echo ($data['request_for'] == "Wl_B2B") ? $data['agent_name'] : $data['customer_name']; ?></td>
                                            <td><?php echo $data['staff_name']; ?></td>
                                            <td><?php echo date_created_format($data['created']); ?> </td>
                                            <td>
                                                <a href="<?php echo site_url('/hotel/amendments-details/') . $ticketData = dev_encode($data['AmendmentId']); ?>" target="_blank"><i class="tts-icon eye"> View</i></a>
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
    <div id="amendment_status_change" class="modal fade" tabindex="1">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Amendment Status Change</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo site_url('hotel/amendment-status-change'); ?>" method="post" tts-form="true" name="form_password_change">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>Company Name / Customer Name : <strong class="company_name tts_agent_company"></strong></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="form-select" name="status">
                                            <option value="" selected>Select Amendment Status</option>
                                            <option value="requested">Requested</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="amendment_id" class="amendment_id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark*</label>
                                        <textarea class="form-control" name="admin_remark" rows="3" cols="15"></textarea>
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