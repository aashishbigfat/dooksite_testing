<?php
if ($details) { 
    
    ?>


<div class="modal-header">
    <h5 class="modal-title">
        Visa Coupon  Details
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
    <div class="modal-body">
       

        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <!-- <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                    type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Visa Coupon  Details</button> -->
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                tabindex="0">
                <div class="col-md-12">
                    <h6 class="viewld_h5">
                       
                    </h6>
                </div>
                <table class="table table-bordered ">
                    <tbody class="lead_details">

                        <tr>
                            <td><span class=" item-text-head"><b>Visa Title</b></span></td>
                            <td>
                                <span class="item-text-value">
                                 <?php   
                                $visa_type_id = explode(',', $details['visa_type_id']);

                                $visa_title = implode(', ', array_map('ucfirst', array_intersect_key($visa_type_list, array_flip($visa_type_id))));?>


                              <?php echo $visa_title; ?>
                                </span>
                            </td>
                        </tr>
                      
                        <tr>
                            <td><span class=" item-text-head"><b>Visa Country</b></span></td>
                            <td><span class="item-text-value">
                            <?php echo $details['country_name'];?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>Value</b></span></td>
                            <td><span class="item-text-value">
                            <?php echo $details['value']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Code</b></span></td>
                            <td><span class="item-text-value">
                            <?php echo ucfirst($details['code']); ?>
                                </span></td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>Coupon Type</b></span></td>
                            <td><span class="item-text-value">
                            <?php echo ucfirst($details['coupon_type']); ?>
                                </span></td>
                        </tr>


                        <tr>
                            <td><span class=" item-text-head"><b>Show On List</b></span></td>
                            <td><span class="item-text-value">
                            <?php echo ($details['coupon_visible']== 1) ? 'Yes': 'No'; ?>
                                </span></td>
                        </tr>



                        <tr>
                            <td><span class=" item-text-head"><b>Use Limit</b></span></td>
                            <td>
                                <span class="item-text-value">
                                <?php echo ucfirst($details['use_limit']); ?>
                                </span>
                            </td>
                        </tr>



                        <tr>
                            <td><span class=" item-text-head"><b>Status</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['status']); ?>
                                </span></td>
                        </tr>

                        
                        
                        <tr>
                            <td><span class="item-text-head"><b>Valid From</b></span></td>
                            <td>
                                <span class="item-text-value">
                                <?php echo date('d M, Y', (int)$details['valid_from']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Valid To</b></span></td>
                            <td>
                            <?php echo date('d M, Y', (int)$details['valid_to']); ?>
                            </td>
                        </tr>


                        <tr>
                            <td><span class="item-text-head"><b>Travel Date From</b></span></td>
                            <td>
                                <span class="item-text-value">
                                <?php echo date('d M, Y', (int)$details['travel_date_from']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Travel Date To</b></span></td>
                            <td>
                            <?php echo date('d M, Y', (int)$details['travel_date_to']); ?>
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