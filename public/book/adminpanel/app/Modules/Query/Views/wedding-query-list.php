<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Wedding Query List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                            onclick="confirm_delete('formsliderlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('query/wedding-query-list'); ?>" method="GET" class="row " name="query-search"
                        onsubmit="return searchvalidateForm()">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'query-search')"
                                    tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'name') {
                                                                echo "selected";
                                                            } ?>>Name
                                    </option>
                                    <option value="email" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'email') {
                                                                echo "selected";
                                                            } ?>>Email
                                    </option>
                                    <option value="wedding_city" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'wedding_city') {
                                                                        echo "selected";
                                                                    } ?>>Wedding City
                                    </option>
                                    <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                    echo "selected";
                                                                } ?>>Date Range
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                            echo trim($search_bar_data['key-text']);
                                                                        } ?>">
                        </div>
                        <div class="col-3">
                            <div class="form-group">
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
                                                                                                                                } ?> tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                                    value="<?php if (isset($search_bar_data['from_date'])) {
                                                echo $search_bar_data['from_date'];
                                            } ?>" placeholder="Select From Date" class="form-control"
                                    tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                        } ?>" placeholder="Select To Date" class="form-control"
                                    tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="form-group">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search</button>
                            </div>
                        </div>
                        <? if (isset($search_bar_data['key'])): ?>
                            <div class="col-2">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('query/wedding-query-list'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>
                <!----------End Search Bar ----------------->
                <div class="table-responsive">
                    <?php $trash_uri = "query/remove-wedding-query-list"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                        id="formsliderlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile Numaber</th>
                                    <th>Wedding Date</th>
                                    <th>Wedding City</th>
                                    <th>No Of Guests</th>
                                    <th>No Of Days</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($weddinglist) && is_array($weddinglist)) {
                                    foreach ($weddinglist as $data) {
                                ?>
                                        <tr>
                                            <td> <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                        value="<?php echo $data['id']; ?>" /></label> </td>
                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='carenquire' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('query/weddingquery-list-details/') . dev_encode($data['id']); ?>"><?php echo ucfirst($data['id']); ?></a>
                                            </td>
                                            <td>
                                                <?php echo ucfirst($data['name']); ?>
                                            </td>
                                            <td>
                                                <?php echo ucfirst($data['email']); ?>
                                            </td>
                                            <td>
                                                <?php echo '+' . ' ' . $data['dial_code'] . ' ' . $data['mobile']; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['wedding_date']; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['wedding_city']; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['no_of_guests']; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['no_of_days']; ?>
                                            </td>

                                            <td>
                                                <?php echo date_created_format($data['created']); ?>
                                            </td>

                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='carenquire' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('query/weddingquery-list-details/') . dev_encode($data['id']); ?>">View</a>
                                            </td>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='6' class='text-center'><b>No Query Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row pagiantion_row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page
                            <?= $pager->getCurrentPage() ?> of
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