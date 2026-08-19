<?php echo view('Modules/Payment/Views\payment-loading.php'); ?>
<form name="payuForm" action="<?php echo $detail['action']; ?>" method="post">
    <input type="hidden" name="key" value="<?php echo $detail['key'] ?>" />
    <input type="hidden" name="hash" value="<?php echo $detail['hash']; ?>" />
    <input type="hidden" name="txnid" value="<?php echo $detail['txnid'] ?>" />
    <input type="hidden" name="amount"
        value="<?php echo (empty($detail['amount'])) ? '' : $detail['amount'] ?>" />
    <input type="hidden" name="firstname" id="firstname"
        value="<?php echo (empty($detail['firstname'])) ? '' : $detail['firstname']; ?>" />
    <input type="hidden" name="email" id="email"
        value="<?php echo (empty($detail['email'])) ? '' : $detail['email']; ?>" />
    <input type="hidden" name="phone"
        value="<?php echo (empty($detail['phone'])) ? '' : $detail['phone']; ?>" />
    <textarea style="display:none;"
        name="productinfo"><?php echo (empty($detail['productinfo'])) ? '' : $detail['productinfo'] ?></textarea>
    <input type="hidden" name="surl"
        value="<?php echo (empty($detail['surl'])) ? '' : $detail['surl'] ?>" size="64" />
    <input type="hidden" name="furl"
        value="<?php echo (empty($detail['furl'])) ? '' : $detail['furl'] ?>" size="64" />
    <input type="hidden" name="service_provider" value="<?php echo $detail['payuprovider']; ?>" size="64" />
    <input type="hidden" name="lastname"
        value="<?php echo (empty($detail['lastname'])) ? '' : $detail['lastname']; ?>" />
    <input type="hidden" name="curl"
        value="<?php echo (empty($detail['curl'])) ? '' : $detail['curl']; ?>" />
    <input type="hidden" name="address1"
        value="<?php echo (empty($detail['address1'])) ? '' : $detail['address1']; ?>" />
    <input type="hidden" name="address2"
        value="<?php echo (empty($detail['address2'])) ? '' : $detail['address2']; ?>" />
    <input type="hidden" name="city"
        value="<?php echo (empty($detail['city'])) ? '' : $detail['city']; ?>" />
    <input type="hidden" name="state"
        value="<?php echo (empty($detail['state'])) ? '' : $detail['state']; ?>" />
    <input type="hidden" name="country"
        value="<?php echo (empty($detail['country'])) ? '' : $detail['country']; ?>" />
    <input type="hidden" name="zipcode"
        value="<?php echo (empty($detail['zipcode'])) ? '' : $detail['zipcode']; ?>" />
    <input type="hidden" name="udf1"
        value="<?php echo (empty($detail['udf1'])) ? '' : $detail['udf1']; ?>" />
    <input type="hidden" name="udf2"
        value="<?php echo (empty($detail['udf2'])) ? '' : $detail['udf2']; ?>" />
    <input type="hidden" name="udf3"
        value="<?php echo (empty($detail['udf3'])) ? '' : $detail['udf3']; ?>" />
    <input type="hidden" name="udf4"
        value="<?php echo (empty($detail['udf4'])) ? '' : $detail['udf4']; ?>" />
    <input type="hidden" name="udf5"
        value="<?php echo (empty($detail['udf5'])) ? '' : $detail['udf5']; ?>" />
    <input type="hidden" name="pg"
        value="<?php echo (empty($detail['pg'])) ? '' : $detail['pg']; ?>" />
</form>
<script language='javascript'>
document.payuForm.submit();
</script>