<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-3">
                        <span> Hotel Booking List</span>
                    </div>
                    <div class="tts-col-9 text_right">

                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <div class="tts_row mb_10">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('hotel/bookings'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
                            <div class="tts-col-3">
                                <div class="form-group">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-control" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>

                                        <option value="lead_passenger_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'lead_passenger_name') {
                                                                        echo "selected";
                                                                    } ?>>First Name</option>
                                        <option value="lead_passenger_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'lead_passenger_name') {
                                                                        echo "selected";
                                                                    } ?>>Last Name</option>


                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                                                        echo "selected";
                                                                    } ?>>Booking Ref. No.</option>
                                        <!-- <option value="travel_operator_pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'travel_operator_pnr') {
                                                                                echo "selected";
                                                                            } ?>>PNR</option> -->

                                        <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                                                            echo "selected";
                                                                        } ?>>Booking Status</option>
                                        <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                                                            echo "selected";
                                                                        } ?>>Payment Status</option>

                                        <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                        echo "selected";
                                                                    } ?>>Date Range</option>
                                    </select>
                                </div>
                                <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                                echo trim($search_bar_data['key-text']);
                                                                            } ?>">
                            </div>
                            <div class="tts-col-3">
                                <div class="form-group">
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
                            <div class="tts-col-2">
                                <div class="form-group">
                                    <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                        echo $search_bar_data['from_date'];
                                                                                                                                    } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                                </div>
                            </div>
                            <div class="tts-col-2">
                                <div class="form-group">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                echo $search_bar_data['to_date'];
                                                                                                                            } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                                </div>
                            </div>
                            <div class="tts-col-1">
                                <div class="form-group">
                                    <label></label><br />
                                    <button type="submit" class="badge badge-md badge-primary">Search</button>
                                </div>
                            </div>
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="tts-col-1">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('hotel'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                        </form>
                    </div>


                    <!----------End Search Bar ----------------->

                    <div class="responcive_table table_box_shadow">
                        <table class="table-strip divName">
                            <thead>
                                <tr>
                                    <th>Reference Number</th>
                                    <th>Hotel Name</th>
                                    <th>Traveller Name</th>
                                    <th>Checkin Date</th>
                                    <th>Checkout Date</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Booking Status</th>
                                    <th>Confirmation Number</th>
                                    <th>City / Country</th>
                                    <th>No of Rooms </th>
                                    <th>Last Cancellation  Date </th>
                                    <th>Booking Channel </th>
                                    <th>Booked By </th>
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
                                            $payment_class   = 'tts-text-danger';
                                        }
                                ?>
                                        <tr>

                                            <td>   <a href="<?php echo site_url('/hotel/details/') . $data['booking_ref_number']; ?>"  target  =  "_blank"><?php echo $data['booking_ref_number']; ?></a></td>
                                            <td><?php echo ucfirst($data['hotel_name']); ?></td>
                                            <td><?php echo ucfirst($data['lead_passenger_name']); ?></td>
                                            <td><?php echo display_custom_date_format($data['check_in_date']); ?></td>
                                            <td><?php echo display_custom_date_format($data['check_out_date']); ?></td>
                                            <td> ₹ <?php echo $data['total_price']; ?></td>
                                            <td> <span class="<?php echo $payment_class ?>"><?php echo ucfirst($data['payment_status']); ?></span></td>
                                            <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['booking_status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $data['confirmation_no']; ?></td>

                                            <td><?php echo $data['city'] . ' / ' . $data['country_code']; ?></td>
                                            <td>
                                                    <?php echo $data['no_of_rooms']; ?>
                                            </td>
                                            <td>
                                                    <?php echo display_custom_date_format($data['last_cancellation_date'],true); ?>
                                            </td>
                                            <td>
                                                    <?php echo $data['booking_channel']; ?>
                                            </td>
                                            <td>
                                                    <?php echo $data['staff_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo date_created_format($data['created']); ?>
                                            </td>
                                                <td>
                                                    <a href="<?php echo site_url('/hotel/confirmation/') . $data['booking_ref_number']; ?>" target  =  "_blank"><i class="tts-icon eye"> View</i></a>
                                                </td>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='12' class='text_center'><b>No Booking Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>



                        <div class="d-flex justify-content-end">
                            <div class="tts_row">
                                <div class="tts-col-6">
                                    <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                        of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                                </div>
                                <div class="tts-col-6">
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