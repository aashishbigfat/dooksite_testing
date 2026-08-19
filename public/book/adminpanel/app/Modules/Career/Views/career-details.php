<?php
if ($details) { ?>


  
    <div class="modal-header">
        <h5 class="modal-title" ><? echo $title . ' '; ?>Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div id="short_description" class="tab-content current p0">
            <div class="col-md-12 ">
                <h6 class="viewld_h5"><? echo $title . ' '; ?> Details</h6>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered">
                <tbody class="lead_details">
                <tr>
                    <th scope="row"><span class=" item-text-head">Job Title</span></th>
                    <td><span class="item-text-value"><?php echo $details['job_title']; ?></span></td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Salary Offer</span></th>
                    <td><span class="item-text-value"><?php echo $details['offer_salary']; ?></span></td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Status</span></th>
                    <td><span class="item-text-value"> <?php echo ucfirst($details['status']); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class="item-text-head">Created Date</span></th>
                    <td> <span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
                </tr>
                <tr>
                    <th scope="row"><span class="item-text-head">Modified Date</span></th>
                    <td> <span class="item-text-value">
                            <?php
                            if(isset($data['modified'])){
                                echo date_created_format($data['modified']);
                            }else{
                                echo '-';
                            }
                            ?></span>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class="item-text-head">Short Description  </span></td>
                    <td><span class="item-text-value"><?php echo $details['short_description']; ?></span></td>
                </tr>
             
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <div class="modal-footer">
        
    </div>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>