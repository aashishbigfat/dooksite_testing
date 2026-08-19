<div class="modal-header bg-transparent">
    <h5 class="modal-title fs-5" id="common_modalLabel">Edit Payment Gateway</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('payment-gateway/update-gateway/' . dev_encode($id)); ?>" method="post"
    tts-form="true" name="add_blogs">

    <div class="modal-body text-start">

        <div class="row gy-2">

            <div class="col-md-6">
                <label for="status" class="form-label">Payment Gateway</label>
                <select class="form-control form-select" name="payment_gateway" id="payment_gateway"
                    aria-describedby="validationServer04Feedback">
                    <option value="">Select *</option>
                    <?php foreach ($payment_gateway as $gateway): ?>
                        <option value="<?= $gateway; ?>" <?= $detail['payment_gateway'] === $gateway ? 'selected' : '' ?>>
                            <?= $gateway; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- another col  -->
            <div class="col-md-6">
                <label for="status" class="form-label">Payment Mode</label>
                <select class="form-control form-select select_search" name="payment_mode[]" id="payment_mode"
                    multiple="mutiple">
                    <!-- <option value="">Select *</option> -->
                    <option value="upi" <?= in_array('upi', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        UPI
                    </option>
                    <option value="credit_card" <?= in_array('credit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Credit Card</option>
                    <option value="rupay_credit_card" <?= in_array('rupay_credit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Rupay Credit Card</option>
                    <option value="visa_credit_card" <?= in_array('visa_credit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Visa Credit Card</option>
                    <option value="mastercard_credit_card" <?= in_array('mastercard_credit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Mastercard Credit Card</option>
                    <option value="american_express_credit_card" <?= in_array('american_express_credit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        American Express Credit Card</option>
                    <option value="debit_card" <?= in_array('debit_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Debit Card</option>
                    <option value="net_banking" <?= in_array('net_banking', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>Net
                        Banking</option>
                    <option value="cash_card" <?= in_array('cash_card', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>Cash
                    </option>
                    <option value="mobile_wallet" <?= in_array('mobile_wallet', explode(",", $detail['payment_mode'])) ? 'selected' : '' ?>>
                        Mobile Wallet</option>
                </select>
            </div>
            <!-- another col ends here  -->

            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-control form-select" name="status" id="status">
                    <option value="active" <?= $detail['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $detail['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-8">
                <label for="remarks" class="form-label">Remarks</label>
                <input class="form-control" type="text" name="remarks" id="remarks" value="<?= $detail['remarks']; ?>">
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>

</form>
<script>
    $('.select_search').multiselect({
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        buttonContainer: '<div class="boot-multiselect btn-group w-100" />',

        buttonText: function (options, select) {
            if (options.length === 0) {
                return 'No option selected ...';
            } else if (options.length > 15) {
                return 'More than 3 options selected!';
            } else {
                var labels = [];
                options.each(function () {
                    if ($(this).attr('label') !== undefined) {
                        labels.push($(this).attr('label'));
                    } else {
                        labels.push($(this).html());
                    }
                });
                return labels.join(', ');
            }
        }
    });
</script>