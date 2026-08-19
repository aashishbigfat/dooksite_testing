<div class="modal-header bg-transparent">
    <h5 class="modal-title fs-5" id="common_modalLabel">Add Payment Gateway</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('payment-gateway/add-gateway') ?>" method="post" tts-form="true" name="add_blogs">
    <div class="modal-body text-start">
        <div class="row gy-2">
            <div class="col-md-6">
                <label for="status" class="form-label">Payment Getway</label>
                <select class="form-control form-select" name="payment_gateway" id="payment_gateway"
                    aria-describedby="validationServer04Feedback">
                    <option value="">Select *</option>
                    <?php foreach ($payment_gateway as $gateway): ?>
                        <option value="<?= $gateway; ?>"><?= $gateway; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback form-error" id="validationServer04Feedback">Please select your payment
                    Gateway</span>
            </div>

            <!-- another col  -->
            <div class="col-md-6">
                <label for="payment_mode" class="form-label">Payment Mode</label>
                <select class="form-control form-select select_search" name="payment_mode[]" id="payment_mode"
                    multiple="mutiple">
                    <!-- <option value="">Select *</option> -->
                    <option value="upi">UPI</option>
                    <option value="credit_card">Any Credit Card</option>
                    <option value="rupay_credit_card">Rupay Credit Card</option>
                    <option value="visa_credit_card">Visa Credit Card</option>
                    <option value="mastercard_credit_card">Mastercard Credit Card</option>
                    <option value="american_express_credit_card">American Express Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="net_banking">Net Banking</option>
                    <option value="cash_card">Cash</option>
                    <option value="mobile_wallet">Mobile Wallet</option>
                </select>
            </div>
            <!-- another col ends here  -->

            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-control form-select" name="status" id="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="col-md-8">
                <label for="remarks" class="form-label">Remarks</label>
                <input class="form-control" type="text" name="remarks" id="remarks">
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