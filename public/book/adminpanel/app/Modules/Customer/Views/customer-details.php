<?php
if ($details && isset($details['email_id'])) { ?>
    <div class="modal-header">
    <h5 class="modal-title"><? echo $title . ' '; ?>Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
    <div class="vewmodelhed">
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="vi_mod_dsc">

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

                    <span>Name</span>
                    <span class="primary"> <b><?php echo $title . ' ' . $first_name . ' ' . $last_name; ?></b> </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Email </span>
                    <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Mobile No</span>
                    <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                </div>
            </div>
        </div>
    
        <ul class="nav nav-tabs mb-3 " id="nav-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Customer Details</button>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
            <div class="col-md-12">
                <h6 class="viewld_h5"><?php echo 'Customer Information'; ?></h6>
            </div>
            <table class="table table-bordered">
                <tbody class="lead_details">
                <tr>
                    <td><span class=" item-text-head"><b>Customer ID</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['customer_id']; ?></span></td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Name</b></span></td>
                    <td><span class="item-text-value"><?php  echo $title . ' ' . $first_name . ' ' . $last_name; ?></span></td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['email_id']; ?></span></td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Mobile No</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['mobile_no']; ?></span></td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b> Balance</b></span></td>
                    <td>
                        <span class="item-text-value"><?php
                            if(isset( $details['balance'])) {
                                echo custom_money_format($details['balance']);
                            }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Status</b></span></td>
                    <td><span class="item-text-value"><?php echo $status; ?></span></td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Profile Pic</b></span></td>
                    <td>
                        <?php if($details['profile_pic']){?>
                            <span class="item-text-value"><a href="<?php echo root_url."uploads/customer/".$details['profile_pic'];  ?>" target="_blank"><?php echo 'View Profile Pic'; ?></a></span>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td><span class=" item-text-head"><b>Address</b></span></td>
                    <td><span class="item-text-value"><?php echo !empty($details['address']) ? ucfirst($details['address']) : ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>State</b> </span></td>
                    <td><span class="item-text-value"> <?php echo !empty($details['state']) ? ucfirst($details['state']) : ''; ?></span>
                    </td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>Country</b> </span></td>
                    <td><span class="item-text-value"><?php echo !empty($details['country']) ? ucfirst($details['country']) : ''; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>City</b> </span></td>
                    <td><span class="item-text-value"><?php echo !empty($details['city']) ? ucfirst($details['city']) : ''; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>Pin Code</b> </span></td>
                    <td><span class="item-text-value"><?php echo $details['pin_code']; ?></span></td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>Created</b> </span></td>
                    <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
                </tr>
                <tr>
                    <td><span class="item-text-head"><b>Modified</b> </span></td>
                    <td><span class="item-text-value"><?php
                            if (isset($details['modified'])) {
                                echo date_created_format($details['modified']);
                            } else {
                                echo '-';
                            }
                            ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
          </div>
          </div>
        </div>
      
    </div>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>