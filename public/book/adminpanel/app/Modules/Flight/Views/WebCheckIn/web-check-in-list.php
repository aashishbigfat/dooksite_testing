<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Web Check In List</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("WebCheckIn", "add_web_check_in")) { ?>
                     <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                        data-href="<?php echo site_url('web-check-in/add-web-check-in-template') ?>">
                        <i class="fa-solid fa-add "></i> Add Web Check-in
                     </button>
                  <?php } ?>
                  <?php if (permission_access("WebCheckIn", "delete_web_check_in")) { ?>
                     <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                        onclick="confirm_delete('formairlinelist')"><i class="fa-solid fa-trash"></i> Delete
                     </button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row ">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('web-check-in'); ?>" method="GET" class="row" name="airline-search"
                  onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'airline-search')"
                           tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="<?php echo trim('airline_code'); ?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_code') {
                                 echo "selected";

                              } ?>>Airline Code
                           </option>
                           <option value="<?php echo trim('airline_name'); ?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_name') {
                                 echo "selected";

                              } ?> >Airline Name
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);

                     } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>
                           <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                              echo $search_bar_data['key-text'] . " *";

                           } else {

                              echo "Value";

                           } ?>
                        </label>
                        <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                           echo $search_bar_data['value'];

                        } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                            echo "disabled";

                         } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           } else {

                              echo 'tts-validatation="Required"';

                           } ?> tts-error-msg="Please enter value"/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">

                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                              class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])): ?>
                  <div class="col-md-3 align-self-center">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('web-check-in'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php
               $trash_uri = "web-check-in/remove-web-check-in";

               ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formairlinelist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("WebCheckIn", "delete_web_check_in")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                           </th>
                           <?php } ?>
                           <th>Image</th>
                           <th>Airline Code</th>
                           <th>Airline Name</th>
                           <th>url</th>
                           <th>created</th>
                           <th>modified</th>
                           <?php if (permission_access("WebCheckIn", "edit_web_check_in")) { ?>
                           <th>Action</th>
                           <?php } ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if (!empty($list) && is_array($list)) {

                           foreach ($list as $data) { ?>
                        <tr>
                           <?php if (permission_access("WebCheckIn", "delete_web_check_in")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                    value="<?php echo $data['id']; ?>" /></label>
                           </td>
                           <?php } ?>
                           <td>
                              <a href="<?php echo root_url . "uploads/web-check-in-images/thumbnail/" . $data['image']; ?>"
                                 target="_blank">
                                 <img
                                    src="<?php echo root_url . "uploads/web-check-in-images/thumbnail/" . $data['image']; ?>"
                                    alt="<?php echo $data['airline_code']; ?>" class="tts-airline-image" width="25">
                              </a>
                           </td>
                           <td>
                              <?php echo $data['airline_code']; ?>
                           </td>
                           <td>
                              <?php echo ucfirst($data['airline_name']); ?>
                           </td>
                           <td>
                              <?php echo $data['url']; ?>
                           </td>
                           <td>
                              <?php echo date_created_format($data['created']); ?>
                           </td>
                           <td>
                              <?php
                              if (isset($data['modified'])) {
                                 echo date_created_format($data['modified']);
                              } else {
                                 echo '-';
                              }
                              ?>
                           </td>
                           <?php if (permission_access("WebCheckIn", "edit_web_check_in")) { ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true" data-controller='webcheckin'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('/web-check-in/edit-web-check-in-template/') . dev_encode($data['id']); ?>"><i
                                    class="fa-solid fa-edit "></i></a>
                           </td>
                           <?php } ?>
                        </tr>
                        <?php }
                        } else {

                           echo "<tr> <td colspan='11' class='text-center'><b>No Data Found</b></td></tr>";

                        } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page
                     <?= $pager->getCurrentPage() ?>
                     of
                     <?= $pager->getPageCount() ?>, total
                     <?= $pager->getTotal() ?> records found
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager): ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- Show Detail Lead Modal content -->