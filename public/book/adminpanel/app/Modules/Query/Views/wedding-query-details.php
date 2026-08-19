<?php
if ($details) { ?>
    <div class="modal-header">
        <h5 class="modal-title">
            <? echo $title . ' '; ?>Details
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
                            <?php echo 'Wedding Query Details'; ?>
                        </h6>
                    </div>
                    <table class="table table-bordered ">
                        <tbody class="lead_details">
                            <tr>
                                <td><span class=" item-text-head"><b>ID</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo ucfirst($details['id']); ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class=" item-text-head"><b>Name</b></span></td>
                                <td>
                                    <span class="item-text-value">
                                        <?php echo $details['name']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class=" item-text-head"><b>Email</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['email']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class=" item-text-head"><b>Mobile</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo '+' . $details['dial_code'] . ' ' . $details['mobile']; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class=" item-text-head"><b>Wedding Date</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['wedding_date']; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class=" item-text-head"><b>Wedding City</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['wedding_city']; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class=" item-text-head"><b>No Of Guests</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['no_of_guests']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class=" item-text-head"><b>No Of Days</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['no_of_days']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class=" item-text-head"><b>Comment</b></span></td>
                                <td><span class="item-text-value">
                                        <?php echo $details['comment']; ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="item-text-head"><b>Created</b> </span></td>
                                <td><span class="item-text-value">
                                        <?php echo date_created_format($details['created']); ?>
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>