<?php
if ($details) { ?>
    <div class="modal-header">
        <h5 class="modal-title"><?echo $title.' ';?>Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body m0">

        <div class="row mb-2">
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Name </span>
                    <span class="primary"> <b><?php echo $details['name']; ?></b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Email </span>
                    <span> <b><?php echo $details['email'] ?> </b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Mobile No</span>
                    <span class="primary"> <b><?php echo $details['phone']; ?> </b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Feedback Date</span>
                    <span class="primary"> <b><?php echo $details['feedback_date']; ?> </b> </span>
                </div>
            </div>
        </div>

        <ul class="tabs">
            <li class="tab-link current" data-tab="lead_details">Feedback Description</li>
        </ul>

        <!-- Start of Lead Details  Tab Content -->
        <div id="lead_details" class="tab-content current">
            <div class="col-12 p-2">
                <?php echo $details['description']; ?>
            </div>
        </div>
        <!-- End of Lead Details  Tab Content -->
    </div>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>