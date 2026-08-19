<div class="modal-header">

   <h5 class="modal-title">Edit <?php echo $details['payment_gateway']; ?> Payment Gateway</h5>

   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<form action="<?php echo site_url('payment-setting/edit-payment-setting/' . dev_encode($id)); ?>" method="post" tts-form="true" name="payment_gateway" enctype="multipart/form-data">

   <div class="modal-body">

      <?php

      $Supplier_name = $details['payment_gateway'];

      $CredData = $details['credentials'];

      $Credentials_JSON = [];
      if(!empty($CredData) && $CredData !=null){
         $Credentials_JSON = json_decode($CredData, true);
      }
 
      unset($details['payment_gateway'], $details['credentials']);

      ?>

      <div class="row">

         <div class="col-md-6 ">

            <div class="form-group form-mb-20">

               <label for="payment_mode">Payment Mode *</label>

               <select class="form-control select_search" name="payment_mode[]" id="payment_mode" multiple="multiple">

                  <?php echo generatePaymentOptions(explode(",", $details['payment_mode'])); ?>

               </select>

            </div>

         </div>

         <div class="col-md-6">

            <div class="form-group form-mb-20">

               <label>Status *</label>

               <select class="form-control" name="status" placeholder="Status">

                  <option value="active" <?php echo ($details['status'] == "active") ? "selected" : ""; ?>>Active</option>

                  <option value="inactive" <?php echo ($details['status'] == "inactive") ? "selected" : ""; ?>>Inactive</option>

               </select>

            </div>

         </div>

         <div class="col-md-12">

            <div class="form-group form-mb-20">

               <label>Remarks</label>

               <textarea class="form-control" name="remarks" placeholder="Remarks"><?php echo $details['remarks']; ?></textarea>

            </div>

         </div>

      </div>

      <?php if ($credentials && !empty($credentials)) { ?>

         <div class="row">

            <div class="col-md-12">

               <h6 class="view_head">Payment Gateway Credentials</h6>

            </div>

            <?php foreach ($credentials as $key => $value) : ?>

               <div class="col-sm-6">

                  <div class="mb-3">

                     <label for="<?= $key ?>" class="form-label"><?= str_replace("_", " ", ucwords(strtolower($key))); ?></label>

                     <?php if ($key === 'Mode') : ?>

                        <select class="form-control" id="<?= $key ?>" name="credentials[<?= $key ?>]">

                           <option value="Test" <?= (isset($Credentials_JSON[$key]) && $Credentials_JSON[$key] == "Test") ? "selected" : "" ?>>Test</option>

                           <option value="Live" <?= (isset($Credentials_JSON[$key]) && $Credentials_JSON[$key] == "Live") ? "selected" : "" ?>>Live</option>

                        </select>

                     <?php else : ?>

                        <input type="text" class="form-control" id="<?= $key ?>" name="credentials[<?= $key ?>]" value="<?= isset($Credentials_JSON[$key]) ? $Credentials_JSON[$key] : ''; ?>" placeholder="Enter <?= str_replace("_", " ", strtolower($key))  ?>">

                     <?php endif; ?>

                  </div>

               </div>

            <?php endforeach; ?>

         </div>

      <?php } ?>

      <input type="hidden" name="payment_gateway" value="<?php echo $Supplier_name; ?>">

   </div>

   <div class="modal-footer">

      <button type="submit" class="btn btn-primary" value="Save">Save</button>

   </div>

</form>