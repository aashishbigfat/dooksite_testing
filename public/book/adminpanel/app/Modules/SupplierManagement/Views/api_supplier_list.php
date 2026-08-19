<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0">API Supplier List</h5>
               </div>
               <div class="col-md-8 text-md-end">

                  <?php if (permission_access("APISuppliers", "APISuppliers_status")) { ?>
                     <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                        <i class="fa-solid fa-exchange"></i> Change Status
                     </button>
                  <?php } ?>

               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row mb_10">

            </div>
            <!----------End Search Bar ----------------->
            <?php $trash_uri = ""; ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formcarextranetinfolist">
               <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("APISuppliers", "APISuppliers_status")) { ?>
                              <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                           <?php } ?>
                           <th>Supplier Name</th>
                           <th>Status</th>
                           <th>Flight</th>
                           <th>Hotel</th>
                           <th>Bus</th>
                           <th>API Mode</th>
                           <th>Created Date</th>
                           <th>Modified Date</th>
                           <?php if (permission_access("APISuppliers", "APISuppliers_edit")) { ?>
                              <th>Action</th>
                           <?php  }  ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if (!empty($allsupplier) && is_array($allsupplier)) {
                           foreach ($allsupplier as $data) {

                              $credentials_Json = $data['credentials'];

                              $JSONEND = json_decode($credentials_Json);


                              if ($data['status'] == 'active') {
                                 $status_class = 'active-status';
                              } else {
                                 $status_class = 'inactive-status';
                              }

                              if ($data['flight'] == 'active') {
                                 $status_flight = 'color: #008000;font-weight: bolder;';
                              } else {
                                 $status_flight = 'color: #f30505; font-weight: bolder;';
                              }
                              if ($data['hotel'] == 'active') {
                                 $status_hotel = 'color: #008000;font-weight: bolder;';
                              } else {
                                 $status_hotel = 'color: #f30505; font-weight: bolder;';
                              }
                              if ($data['bus'] == 'active') {
                                 $status_bus = 'color: #008000;font-weight: bolder;';
                              } else {
                                 $status_bus = 'color: #f30505; font-weight: bolder;';
                              }
                        ?>
                              <tr>
                                 <?php if (permission_access("APISuppliers", "APISuppliers_status")) { ?>
                                    <td>
                                       <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                    </td>
                                 <?php } ?>

                                 <td>
                                    <?php echo ucfirst($data['supplier_name']); ?>
                                 </td>


                                 <td>
                                    <span class="<?php echo $status_class ?>">
                                       <?php echo ucfirst($data['status']); ?>
                                    </span>
                                 </td>

                                 <td>
                                    <span style="<?php echo $status_flight ?>">
                                       <?php echo ucfirst($data['flight']); ?>
                                    </span>
                                 </td>
                                 <td>
                                    <span style="<?php echo $status_hotel ?>">
                                       <?php echo ucfirst($data['hotel']); ?>
                                    </span>
                                 </td>
                                 <td>
                                    <span style="<?php echo $status_bus ?>">
                                       <?php echo ucfirst($data['bus']); ?>
                                    </span>
                                 </td>

                                 <td>
                                    <?php
                                    if (isset($JSONEND->Mode)) {
                                       echo ucfirst($JSONEND->Mode);
                                    }
                                    ?>
                                 </td>

                                 <td><?php
                                       if (isset($data['created'])) {
                                          echo date_created_format($data['created']);
                                       } else {
                                          echo '-';
                                       }
                                       ?>
                                 </td>
                                 <td><?php
                                       if (isset($data['modified'])) {
                                          echo date_created_format($data['modified']);
                                       } else {
                                          echo '-';
                                       }
                                       ?>
                                 </td>
                                 <?php if (permission_access("APISuppliers", "APISuppliers_edit")) { ?>
                                    <td>
                                       <a href="javascript:void(0);" view-data-modal="true" data-controller='SupplierManagement' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('supplier-management/edit-api-supplier-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                                    </td>
                                 <?php } ?>
                              </tr>
                        <?php }
                        } else {
                           echo "<tr> <td colspan='11' class='text_center'><b>No data found</b></td></tr>";
                        } ?>
                     </tbody>
                  </table>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('supplier-management/api-supplier-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <select class="form-select" name="status">
                           <option value="" selected="selected">Select Status</option>
                           <option value="active">Active</option>
                           <option value="inactive">Inactive</option>
                        </select>
                        <input type="hidden" name="checkedvalue">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>