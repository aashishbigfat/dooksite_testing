<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo $details['supplier_name']; ?> Supplier</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('supplier-management/edit-api-supplier/' . dev_encode($id)); ?>" method="post" tts-form="true" name="edit_supplier" enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-12 ">
            <h6 class="view_head">Services</h6>
         </div>
         <?php
            $Supplier_name = $details['supplier_name'];
            $CredData = $details['credentials'];
            $Credentials_JSON = json_decode($CredData, true);
            unset($details['supplier_name'], $details['credentials']);
         ?>
         <div class="col-md-12">
            <?php foreach ($details as $keya => $item) : ?>
               <div class="form-check form-check-inline">
                  <input type="hidden" name="<?= $keya ?>" value="inactive">
                  <input class="form-check-input" type="checkbox" name="<?= $keya ?>" id="abhay-<?= $keya ?>" value="active" <?= ($item == 'active') ? 'checked' : '' ?>>
                  <label class="form-check-label" for="abhay-<?= $keya ?>"><?= ucfirst($keya) ?></label>
               </div>
            <?php endforeach; ?>
         </div>
      </div>

      <?php if ($credentials && !empty($credentials)) { ?>
         <div class="row">
            <div class="col-md-12 ">
               <h6 class="view_head">API Credentials</h6>
            </div>
            <div class="row">
               <?php foreach ($credentials as $key => $value) : ?>
                  <div class="col-sm-6">
                     <div class="mb-3">
                        <label for="<?= $key ?>" class="form-label"><?= str_replace("_", " ", $key); ?></label>
                        <?php if ($key === 'Mode') : ?>
                           <select class="form-select" id="<?= $key ?>" name="credentials[<?= $key ?>]">
                              <option value="Live" <?php if (isset($Credentials_JSON[$key]) && $Credentials_JSON[$key] == "Live") { echo "selected"; }  ?>>Live</option>
                              <option value="Test" <?php if (isset($Credentials_JSON[$key]) && $Credentials_JSON[$key] == "Test") { echo "selected"; }  ?>>Test</option>
                           </select>
                        <?php else : ?>
                           <input type="text" class="form-control" id="<?= $key ?>" name="credentials[<?= $key ?>]" value="<?= isset($Credentials_JSON[$key]) ? $Credentials_JSON[$key] : ''; ?>" placeholder="Enter <?= strtolower($key) ?>">
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      <?php } ?>
      
      
      <?php if ($api_account_group && !empty($api_account_group)) { ?>
         <div class="row">
            <?php foreach ($api_account_group as $group_key => $api_account) { ?>
               <div class="col-md-12 ">
                  <h6 class="view_head"><?php echo $group_key; ?> Login Credentials</h6>
               </div>
               
               <?php foreach ($api_account as $account_key => $account) { ?>
                  <input type="hidden" value="<?php echo $account['id']; ?>" name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][id]">
                  <div class="col-md-5">
                     <div class="form-group form-mb-20">
                        <label>Username</label>
                        <input class="form-control" type="text" name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][username]" value="<?php echo $account['username']; ?>" placeholder="username">
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group form-mb-20">
                        <label>Password</label>
                        <input class="form-control" type="text" name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][password]" value="<?php echo $account['password']; ?>" placeholder="password">
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        <label class="mt_20">
                           <input id="api_account_group[<?php echo $group_key; ?>][<?php echo $account_key; ?>][status]" class="" type="radio" onchange='api_flight_supplier_status(this,"<?php echo $group_key; ?>")' name="temp[<?php echo $group_key; ?>]" value="<?php echo $account['status']; ?>" <?php echo $account['status'] == "active" ? "checked" : "" ?>> <?php echo 'Active'; ?>
                        </label>
                     </div>
                     <input type="hidden" value="<?php echo $account['status']; ?>" class="<?php echo $group_key; ?>" name="api_account_group[<?php echo $group_key; ?>][<?php echo $account_key; ?>][status]">
                  </div>
               <?php } ?>
            <?php }
            ?>
         </div>
      <?php  } ?>
      <input type="hidden" name="supplier_name" value="<?php echo $Supplier_name; ?>">
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>

<script>
   function api_flight_supplier_status(thisval, group_key) {
      var els_all = document.getElementsByClassName(group_key);
      for (var v = 0; v < els_all.length; v++) {
         els_all[v].value = "inactive";
      }

      let field_name = thisval.id;
      var els = document.getElementsByName(field_name);
      if (thisval.checked) {
         for (var i = 0; i < els.length; i++) {
            els[i].value = "active";
         }
      }
   }
</script>