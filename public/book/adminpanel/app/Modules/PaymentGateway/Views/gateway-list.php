<div class="content ">
    <div class="page-content">

        <!-- Page Header Close -->
        <div class="table_title">
            <!--  -->
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">PAYMENT GATEWAY LIST</h5>
                    </div>
                    <div class="col-md-8 text-md-end ">

                        <button class="badge badge-wt" view-data-modal="true" data-controller='bankaccounts'
                            data-href="<?php echo site_url('payment-gateway/add-gateway-template') ?>"><i
                                class="fa-solid fa-add"></i> Add Payment Gateway
                        </button>

                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                            onclick="confirm_delete('formpaymentgatewaylist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>

                    </div>
                </div>
            </div>
            <!--  -->
            <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <?php $trash_uri = "payment-gateway/remove-gateway"; ?>
                            <form action="<?= site_url($trash_uri); ?>" method="post" tts-form="true"
                                id="formpaymentgatewaylist">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap table-hover">
                                        <thead class="table-active">
                                            <tr>
                                                <th scope="col"><label for="checkbox"><input type="checkbox"
                                                            name="check_all" id="selectall"></label></th>
                                                <th scope="col">Payment Gateway</th>
                                                <th scope="col">Payment Mode</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Remark</th>
                                                <th scope="col">Created Date</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($list) && is_array($list)) {
                                                foreach ($list as $data) { ?>
                                                    <tr>
                                                        <td>
                                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                                    value="<?php echo $data['id']; ?>" /></label>
                                                        </td>

                                                        <td>
                                                            <?= ucfirst($data['payment_gateway']); ?>
                                                        </td>

                                                        <td>
                                                            <span>
                                                                <?php
                                                                $payment_mode = explode(',', $data['payment_mode']);
                                                                for ($i = 0; $i < count($payment_mode); $i++) {
                                                                    if ($payment_mode[$i] === 'credit_card') {
                                                                        echo "Credit Card <br> ";
                                                                    } else if ($payment_mode[$i] === 'debit_card') {
                                                                        echo "Debit Card <br> ";
                                                                    } else if ($payment_mode[$i] === 'upi') {
                                                                        echo "UPI <br> ";
                                                                    } else if ($payment_mode[$i] === 'net_banking') {
                                                                        echo "Net Banking <br> ";
                                                                    } else if ($payment_mode[$i] === 'cash_card') {
                                                                        echo "Cash <br> ";
                                                                    } else if ($payment_mode[$i] === 'mobile_wallet') {
                                                                        echo "Mobile Wallet <br> ";
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <?= ucfirst($data['status']); ?>
                                                        </td>

                                                        <td>
                                                            <?= $data['remarks']; ?>
                                                        </td>

                                                        <td>
                                                            <?php
                                                            if (isset($data['created'])) {
                                                                echo date_created_format($data['created']);
                                                            }
                                                            ?>
                                                        </td>

                                                        <td>
                                                            <a href="javascript:void(0);" view-data-modal="true"
                                                                data-controller='common_modalLabel'
                                                                data-id="<?php echo dev_encode($data['id']); ?>"
                                                                data-href="<?php echo site_url('payment-gateway/edit-gateway/') . dev_encode($data['id']); ?>">
                                                                <i class="fa-solid fa-edit "></i></a>
                                                        </td>

                                                    </tr>
                                                <?php }
                                            } else {
                                                echo '<tr> <td colspan="13" class="text-center"><span><strong>!! Data not found !! </strong></span></td></tr>';
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <p class="m-0">Page
                                    <?= $pager->getCurrentPage() ?> of
                                    <?= $pager->getPageCount() ?>, total
                                    <?= $pager->getTotal() ?> records found
                                </p>
                                <nav aria-label="Page navigation" class="pagination-style-3">
                                    <ul class="pagination mb-0 flex-wrap justify-content-center ">
                                        <?php if ($pager): ?>
                                            <?= $pager->links() ?>
                                        <?php endif ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>