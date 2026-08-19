<div class="modal-header">
    <h5 class="modal-title">Add <?php echo ' Currency'; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('currency/add-currency'); ?>" method="post" onsubmit="return validateForm()"
      tts-form="true" name="add_blogs">

    <div class="modal-body">
        <div class="row">
          

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12"> Country *</label>
                    <select class="form-control select_search" name="country" id="currency_details"
                            onchange="sct_change()" data-validation="required" putdata="true">
                        <option value="">Select Country</option>
                        <?php
                        if (!empty($country) && is_array($country)) {
                            foreach ($country as $value) { ?>
                                <option value="<?php echo $value['name'] ?>"
                                        basecurrencyname="<?php echo $value['currency_name']; ?>"  basecurrencysymbol="<?php echo $value['currency_symbol']; ?>" basecurrency="<?php echo $value['currency']; ?>">
                                    <?php echo $value['name'] ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>



            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Currency * </label>
                    <input class="form-control inputtext fs12" type="text" name="currency"
                           placeholder="currency name" readonly>
                </div>
            </div>

           
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Currency Name* </label>
                    <input class="form-control inputtext fs12" type="text" name="currency_name"
                           placeholder=" currency name" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12"> Currency Symbol* </label>
                    <input class="form-control inputtext fs12" type="text" name="currency_symbol"
                           placeholder=" currency symbol" readonly>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Convertion Rate* </label>
                    <input class="form-control" type="text" name="convertion_rate" placeholder="Convertion Rate">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Decimal Point* </label>
                    <input class="form-control" type="text" name="decimal_point" placeholder=" Enter Decimal Point">
                </div>
            </div>


            <div class="col-md-6">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>


           

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>

<script>
    function sct_change() {
        var selectedOption = $("#currency_details option:selected");
        var baseCurrencyName = selectedOption.attr('basecurrencyname');
        var baseCurrencySymbol = selectedOption.attr('basecurrencysymbol');
        var baseCurrency= selectedOption.attr('basecurrency')

        if (baseCurrencyName) {
           
            baseCurrencyName = baseCurrencyName.replace(/ /g, "-");
            $("[name='currency_name']").val(baseCurrencyName);
        }

        if (baseCurrencySymbol) {
          
            baseCurrencySymbol = baseCurrencySymbol.replace(/ /g, "-");
            $("[name='currency_symbol']").val(baseCurrencySymbol);
        }

        if (baseCurrency) {
           
            baseCurrency = baseCurrency.replace(/ /g, "-");
            $("[name='currency']").val(baseCurrency);
        }
    }
</script>


