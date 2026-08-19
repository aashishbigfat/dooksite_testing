<?php
if (!empty($details)) {
    ?>
    <div class="modal-header">
        <h5 class="modal-title">
            Umrah Coupon Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="vewmodelhed">
        <div class="modal-body">

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="col-md-12">
                        <h6 class="viewld_h5">

                        </h6>
                    </div>
                    <table class="table table-bordered ">
                        <tbody class="lead_details">

                         <?php
                           /*  <tr>
                                <td><span class="item-text-head"><b>Umrah Destination</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['destination_name']) ? ucfirst($details['destination_name']) : '-'; ?>
                                    </span>
                                </td>
                            </tr> */
                           
                          ?>
                           <?php
                            /* <tr>
                                <td><span class="item-text-head"><b>Umrah Theme</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['theme_name']) ? ucfirst($details['theme_name']) : '-'; ?>
                                    </span>
                                </td>
                            </tr> */
                            ?>
                            <tr>
                                <td><span class="item-text-head"><b>Package Name</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['umrah_package']) ? ucfirst($details['umrah_package']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Coupon Type</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['coupon_type']) ? ucfirst($details['coupon_type']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>


                            <tr>
                                <td><span class="item-text-head"><b>Value</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= $details['value'] ?? '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Code</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['code']) ? ucfirst($details['code']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Minm Order Value</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= $details['minm_order'] ?? '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Maxm Order Value</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= $details['maxm_order'] ?? '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Minm Pax</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= $details['minm_pax'] ?? '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Maxm Pax</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= $details['maxm_pax'] ?? '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Show On List</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= isset($details['coupon_visible']) && $details['coupon_visible'] == 1 ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Use Limit</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['use_limit']) ? ucfirst($details['use_limit']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Status</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['status']) ? ucfirst($details['status']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Booking Date From</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['valid_from']) ? date('d M, Y', (int) $details['valid_from']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Booking Date To</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['valid_to']) ? date('d M, Y', (int) $details['valid_to']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Travel Date From</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['travel_date_from']) ? date('d M, Y', (int) $details['travel_date_from']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Travel Date To</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['travel_date_to']) ? date('d M, Y', (int) $details['travel_date_to']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Created</b> </span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?= !empty($details['created']) ? date_created_format($details['created']) : '-'; ?>
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
} else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
}
?>