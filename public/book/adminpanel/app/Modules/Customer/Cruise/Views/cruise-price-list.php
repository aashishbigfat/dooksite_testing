<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Cruise Price List</h5>
                    </div>
                    <div class="col-md-8 text-md-right">


                        <?php /*if (permission_access("Visa", "add_blog")) { */ ?>

                        <button class="badge badge-wt" view-data-modal="true" data-controller='cruise'
                                data-href="<?php echo site_url('cruise/add-cruise-price-template/').dev_encode($cruise_list_id) ?>">
                            <i class="fa-solid fa-add "></i> Add Price
                        </button>
                        <!-- --><?php /*} */ ?>

                        <?php /*if (permission_access("Visa", "blog_status")) { */ ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                            <i class="fa-solid fa-exchange"></i> Change Status
                        </button>
                        <!-- --><?php /*} */ ?>

                        <?php /*if (permission_access("Visa", "delete_blog")) { */ ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>
                        <?php /*} */ ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
                <!----------End Search Bar ----------------->
                <div class="table-responsive">
                    <?php $trash_uri = "cruise/remove-cruise-price"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php /*if (permission_access("Visa", "delete_blog") || permission_access("Visa", "blog_status")) { */ ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <!-- --><?php /*}*/ ?>

                                <th>Departure Port</th>
                                <th>Selling Date</th>
                                <th>Cabin Name</th>
                                <th>Twin Pax Price</th>
                                <th>Single Pax Price</th>
                                <th>Port Charges</th>
                                <th>Max Pax Stay</th>
                                <th>Book Online</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                                <!-- --><?php /*if (permission_access("Visa", "edit_blog")) { */ ?>
                                <th>Action</th>
                                <?php /*}*/ ?>

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
                                    ?>

                                    <tr>
                                        <?php /*if (permission_access("Visa", "delete_blog") || permission_access("Visa", "blog_status")) { */ ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php /*}*/ ?>
                                        <td>
                                            <?php echo $data['port_name'] ?>
                                        </td>
                                        <td><?php echo date_created_format($data['selling_date']); ?></td>
                                        <td>
                                            <?php echo $data['cabin_name'] ?>
                                        </td>

                                        <td>
                                            <?php echo $data['twin_pax_price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['single_pax_price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['port_charges']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['max_pax_stay']; ?>
                                        </td>

                                        <td>
                                            <?php echo $data['book_online']; ?>
                                        </td>

                                        <td>
                                            <span class="<?php echo $status_class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
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

                                        <?php /*if (permission_access("Visa", "edit_blog")) { */ ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='cruise'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/cruise/edit-cruise-price-template/') . dev_encode($data['id']); ?>"><i
                                                        class="fa fa-edit "></i></a>
                                        </td>
                                        <?php /*}*/ ?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='12' class='text_center'><b>No Cruise Price Found      </b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                   
                </div>
                <div class="row pagiantion_row align-items-center">
                            <div class="col-md-6 mb-3 mb-lg-0">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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
</div>


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo site_url('cruise/cruise-price-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <select class="form-control" name="status">
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
                    <button class="btn btn-primary" type="submit" value="Save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>