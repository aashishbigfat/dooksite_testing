<div class="content">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5>Job Application List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <a href="<?php echo site_url('/career/'); ?>" class="badge badge-wt"><i class="fa-solid fa-list "></i> Career List</a>
                        <?php if (permission_access("Career", "delete_applications")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formbloglist')"> <i class="fa-solid fa-trash"></i> Delete </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row ">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('career/job-applications-list'); ?>"  class="row" method="GET" class="tts-dis-content" name="career-search" onsubmit="return searchvalidateForm()">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-select" onchange="tts_searchkey(this,'career-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="city" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'city') {
                                                                    echo "selected";
                                                                } ?>>City</option>
                                        <option value="email" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'email') {
                                                                    echo "selected";
                                                                } ?>>Email</option>
                                        <option value="current_salary" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'current_salary') {
                                                                            echo "selected";
                                                                        } ?>>Current Salary</option>
                                        <option value="job_title" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'job_title') {
                                                                        echo "selected";
                                                                    } ?>>Job Title</option>
                                        <option value="job_category" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'job_category') {
                                                                            echo "selected";
                                                                        } ?>>Job Category</option>
                                        <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                        echo "selected";
                                                                    } ?>>Date Range</option>
                                    </select>
                                </div>
                                <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                                echo trim($search_bar_data['key-text']);
                                                                            } ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                                echo $search_bar_data['key-text'] . " *";
                                            } else {
                                                echo "Value";
                                            } ?> </label>
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
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                        echo $search_bar_data['from_date'];
                                                                                                                                    } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                echo $search_bar_data['to_date'];
                                                                                                                            } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group form-mb-20">
                                    <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <? if (isset($search_bar_data['key'])): ?>
                                <div class="col-md-3 mb-3">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('career/job-applications-list'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                    </form>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <?php

                        $trash_uri = "career/remove-job-application";
                        ?>
                        <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                            <table class="table table-bordered table-hover">
                                <thead class="table-active">
                                    <tr>
                                        <?php if (permission_access("Career", "delete_applications")) { ?>
                                            <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                            </th>
                                        <?php } ?>
                                        <th>Job Title</th>
                                        <th>Job Category</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>City</th>
                                        <th>Notice Period</th>
                                        <th>Current Organization</th>
                                        <th>Total Experience</th>
                                        <th>Current Salary</th>
                                        <th>Created Date</th>
                                        <th>Resume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (!empty($list) && is_array($list)) {
                                        foreach ($list as $data) { ?>

                                            <tr>
                                                <?php if (permission_access("Career", "delete_applications")) { ?>
                                                    <td>
                                                        <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                                    </td>
                                                <?php } ?>
                                                <td><?php echo ($data['job_title']); ?></td>
                                                <td><?php echo ($data['job_category']); ?></td>
                                                <td><?php echo $data['title'] . ' ' . $data['first_name'] . ' ' . $data['last_name']; ?></td>
                                                <td><?php echo ($data['email']); ?></td>
                                                <td><?php echo ($data['mobile']); ?></td>


                                                <td><?php echo $data['city'] ?></td>
                                                <td><?php echo $data['notice_period'] ?></td>
                                                <td><?php echo $data['current_organization'] ?></td>
                                                <td><?php echo $data['total_experience'] ?></td>
                                                <td><?php echo $data['current_salary'] ?></td>
                                                <td>
                                                    <?php
                                                    if (isset($data['created'])) {
                                                        echo date_created_format($data['created']);
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo root_url . 'uploads/career/' . $data['resume_file']; ?>" download>
                                                        Download Resume
                                                    </a>
                                                </td>

                                            </tr>
                                    <?php }
                                    } else {
                                        echo "<tr> <td colspan='14' class='text-center'><b>No Job Application Found</b></td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>