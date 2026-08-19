<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4 mb-3 mb-lg-0">
                  <h5 class="m0"> <?php echo 'Currency List'; ?> </h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (0) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='currency'  data-href="<?php echo site_url('currency/add-currency-view')?>"> <i class="fa-solid fa-add "></i> Add Currency </button>
                  <?php }?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="fa-solid fa-exchange"></i> Change Status
                        </button>
                  <?php //if (permission_access("Newsletter", "delete_newsletter")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formbloglist')"> <i class="fa-solid fa-trash "></i> Delete </button>
                  <?php //}?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row mb_10 ">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('currency'); ?>" method="GET" class="tts-dis-content" name="newsletter-search" onsubmit="return searchvalidateForm()">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-control" onchange="tts_searchkey(this,'newsletter-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="currency_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='currency_name'){ echo "selected";} ?>>Currency Name</option>
                           <option value="currency" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='currency'){ echo "selected";} ?>> Currency</option>
                           <option value="currency_symbol" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='currency_symbol'){ echo "selected";} ?>> Currency Symbol</option>
                           <option value="country" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='country'){ echo "selected";} ?>> Country Name</option>
                           <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                        <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                       
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  
                  <? if(isset($search_bar_data['key'])): ?>
                     <div class="col-md-3 mb-3">
                        <div class="search-reset-btn">
                           <a href="<?php echo site_url('currency');?>">Reset Search</a>
                        </div>
                      </div>
                  <? endif ?>
                  </div>
                 
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php
                  $trash_uri = "currency/remove-currency";
                  ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php //if (permission_access("Newsletter", "delete_newsletter")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                           <?php //}?>
                           <th> Country</th>
                           <th> Currency </th>
                           <th>Convertion Rate</th>
                           <th> Currency Name & Symbol</th>
                           <th> Status</th>
                           <?php if(0){?>                          
                           <th> Default Currency</th>   
                           <?php } ?>            
                           <th>Created Date</th>                                                
                           <th>Action</th>                           
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                  
                           if (!empty($list) && is_array($list)) {
                               foreach ($list as $data) { 


                                 if ($data['status'] == 'active') {
                                    $status_class = 'active-status';
                                } else {
                                    $status_class = 'inactive-status';
                                }

                                
                                if ($data['default_currency'] == 'active') {
                                 $default_class = 'active-status';
                             } else {
                                 $default_class = 'inactive-status';
                             }
                                ?>
                               
                               
                        <tr>
                           <?php //if (permission_access("Newsletter", "delete_newsletter")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                           </td>
                           <?php //}?>
                         
                           <td><?php echo ($data['country']); ?></td>
                           <td><?php echo ($data['currency']); ?></td>
                           <td>
                              <?php echo ucfirst($data['convertion_rate']); ?>
                           </td>
                           <td>(<?php echo ($data['currency_name']); ?>) .<?php echo ($data['currency_symbol']); ?></td>

                           <td>

                           <span class="<?php echo $status_class ?>">
                           <?php echo ucfirst($data['status']); ?>
                           </span>

                        </td>

                        
                       

                      
                        <?php if(0){ ?>
                           <td>
                        <a href="javascript:void(0);" view-data-modal="false" data-controller='currency' data-id="<?= dev_encode($data['id']); ?>" data-href="<?= site_url('currency/default-update-status/') . dev_encode($data['id']); ?>" onclick="return confirm('<?= ($data['default_currency'] == 'active') ? 'Are you sure? do you want to Disable this Currency.' : 'Are you sure? do you want to Enable this Vehicle.'; ?>')">
                           <?php
                           echo ($data['default_currency'] == 'active') ? '<i class="fa fa-toggle-on" aria-hidden="true" style="font-size:26px;color:green" title="Enable"></i>' : '<i class="fa fa-toggle-off" aria-hidden="true" style="font-size:26px;color:red" title="Disable"></i>';
                           ?>
                           </a>
                        </td>
                        <?php } ?>
                           <td><?php echo date_created_format($data['created']); ?></td>
                     
                          
                        
                           <?php //if (permission_access("Newsletter", "edit_newsletter")) { ?>
                           <td>
                               <a href="javascript:void(0);" view-data-modal="true" data-controller='currency' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/currency/edit-currency-view/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                           </td>
                           <?php //}?>
                        </tr>
                        <?php }
                           } else {
                               echo "<tr> <td colspan='11' class='text_center'><b>No Data Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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


<div id="status_change" class="modal fade" tabindex="-1"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Change Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo site_url('currency/currency-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
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

   
