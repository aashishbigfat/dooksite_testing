
<div class="modal-header">
        <h5 class="modal-title" >Edit <?php echo 'Currency ';?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>


    <form action="<?php echo site_url('currency/edit-currency/' . dev_encode($id)); ?>" method="post"
          onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <?php if(0){ ?>
                <div class="col-md-6">
                <div class="form-group form-mb-20">
                <label class="fw_5 control-label fs12"> Country *</label>
                <select class="form-select inputtext h42i fs12" name="country" id="currency_details" onchange="sct_change()" data-validation="required" putdata="true">
                    <option value="">Select Country</option>
                    <?php
                    if (!empty($country) && is_array($country)) {
                        foreach ($country as $value) { 
                            ?>
                            <option value="<?php echo $value['name']; ?>" <?php if($value['name'] == $details['country']) { ?> selected <?php } ?>
                                    basecurrencyname="<?php echo $value['currency_name']; ?>" basecurrencysymbol="<?php echo $value['currency_symbol']; ?>" basecurrency="<?php echo $value['currency']; ?>">
                                <?php echo $value['name']; ?>
                            </option>
                        <?php } 
                    }
                    ?>
                </select>
                </div>
                </div>
              <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Currency * </label>
                    <input class="form-control inputtext fs12" type="text" name="currency" Value="<?php echo $details['currency'];?>"
                           placeholder="currency name" readonly>
                 </div>
             </div>

           
             <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Currency Name* </label>
                    <input class="form-control inputtext fs12" type="text" name="currency_name" Value="<?php echo $details['currency_name']?>"
                           placeholder=" currency name" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12"> Currency Symbol* </label>
                    <input class="form-control inputtext fs12" type="text" name="currency_symbol" value="<?php echo $details['currency_symbol'];?>"
                           placeholder=" currency symbol" readonly>
                </div>
            </div>

            <?php } ?>

            <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Convertion Rate*  </label>
                        <input class="form-control" type="text" name="convertion_rate" value="<?php echo $details['convertion_rate']; ?>" placeholder="Convertion Rate">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Decimal Point*  </label>
                        <input class="form-control" type="text" name="decimal_point" value="<?php echo $details['decimal_point']; ?>" placeholder="Decimal Point">
                    </div>
                </div>
        <?php if(0) { ?>

            <div class="col-md-6">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" <?php if($details['status'] == "active"){
                                 echo "selected";
                            } ?> >Active</option>
                            <option value="inactive" <?php if($details['status'] == "inactive"){ 
                          echo "selected";
                            }?>> Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">save</button>
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

