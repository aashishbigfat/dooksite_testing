<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4 mb-3 mb-lg-0">
                  <h5 class="m0">Payment Gateway List</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("Setting", "payment_setting_status")) {  ?>
                     <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status </button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="table-responsive">
               <?php $trash_uri = "javascript:void(0);"; ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formsliderlist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("Setting", "payment_setting_status") || permission_access("Setting", "payment_setting_edit")) {  ?>
                              <th scope="col"><label><input type="checkbox" name="check_all" id="selectall" /></label> </th>
                           <?php } ?>
                           <th scope="col">Payment Gateway Name</th>
                           <th scope="col">Payment Mode</th>
                           <th scope="col">Status</th>
                           <th scope="col">Remark</th>
                           <th scope="col">Created Date</th>
                           <th scope="col">Modified Date</th>
                           <?php if (permission_access("Setting", "payment_setting_status") || permission_access("Setting", "payment_setting_edit")) {  ?>
                              <th scope="col">Action</th>
                           <?php } ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if (!empty($PaymentGatewaylist) && is_array($PaymentGatewaylist)) {
                           foreach ($PaymentGatewaylist as $data) {

                              if ($data['status'] == 'active') {
                                 $class = 'active-status';
                              } else {
                                 $class = 'inactive-status';
                              }
                        ?>
                              <tr>
                                 <?php if (permission_access("Setting", "payment_setting_status") || permission_access("Setting", "payment_setting_edit")) {  ?>
                                    <td>
                                       <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                    </td>
                                 <?php } ?>
                                 <?php if (!empty($data['payment_gateway'])): ?>
                                    <td><?php echo ucfirst($data['payment_gateway']); ?></td>
                                 <?php endif; ?>

                                 <td>
                                    <?php
                                    $payment_modes = [
                                       'upi' => 'UPI',
                                       'credit_card' => 'Credit Card',
                                       'rupay_credit_card' => 'Rupay Credit Card',
                                       'visa_credit_card' => 'Visa Credit Card',
                                       'mastercard_credit_card' => 'Mastercard Credit Card',
                                       'american_express_credit_card' => 'American Express Credit Card',
                                       'debit_card' => 'Debit Card',
                                       'net_banking' => 'Net Banking',
                                       'cash_card' => 'Cash',
                                       'mobile_wallet' => 'Mobile Wallet'
                                    ];

                                    $selected_modes = explode(',', $data['payment_mode']);
                                    foreach ($selected_modes as $mode) {
                                       if (isset($payment_modes[$mode])) {
                                          echo $payment_modes[$mode] . "<br>";
                                       } else {
                                          echo "Unknown Payment Mode <br>";
                                       }
                                    }
                                    ?>
                                 </td>

                                 <td>
                                    <span class="<?php echo $class ?>">
                                       <?php if (!empty($data['status'])) { ?>
                                          <?php echo ucfirst($data['status']); ?>
                                       <?php  } ?>

                                    </span>
                                 </td>
                                 <td>
                                    <?php if (!empty($data['remarks'])) { ?>
                                       <?php echo ucfirst($data['remarks']); ?>
                                    <?php  } ?>
                                 </td>
                                 <td><?php echo date_created_format($data['created']); ?></td>
                                 <td><?php
                                       if (isset($data['modified'])) {
                                          echo date_created_format($data['modified']);
                                       } else {
                                          echo '-';
                                       }
                                       ?>
                                 </td>
                                 <?php if (permission_access("Setting", "payment_setting_status") || permission_access("Setting", "payment_setting_edit")) {  ?>
                                    <td>
                                       <a href="javascript:void(0);" view-data-modal="true" data-controller='Paymentsetting' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/payment-setting/edit-payment-setting-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                                    </td>
                                 <?php } ?>
                              </tr>
                        <?php }
                        } else {
                           echo "<tr> <td colspan='11' class='text_center'><b>No Payment setting Found</b></td></tr>";
                        } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row">
               <div class="col-md-6">
                  <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> Records found </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager) : ?>
                     <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('payment-setting/status-change-payment-setting'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group form-mb-20">
                        <select class="form-control" name="status">
                           <option value="active">Active</option>
                           <option value="inactive">Inactive</option>
                        </select>
                        <input type="hidden" name="checkedvalue">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary" value="Save">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>