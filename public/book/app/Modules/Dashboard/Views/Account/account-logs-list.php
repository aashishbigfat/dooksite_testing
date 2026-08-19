<section class="btravTripsBannerWrapper">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <ol>
                <li>My Account</li>
                <li>Customer Account Log</li>
            </ol>
        </div>
    </div>
</section>
<section class="BookingStatus">
    <div class="container">
        <div class="row">
            <!--sidebar-->
            <?php echo view('\Modules\Dashboard\Views\side-bar'); ?>
            <div class="col-lg-9">
                <div class="BookingStatusWrappertabs">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                             aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="table-responsive-md">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">Ref. No.</th>
                                            <th scope="col">Invoice Number</th>
                                            <th scope="col">Credit Note No.</th>
                                            <th scope="col">Debit</th>
                                            <th scope="col">Credit</th>
                                       <!--      <th scope="col">Balance</th> -->
                                            <th scope="col">Date</th>
                                         <!--    <th scope="col">Payment Type</th> -->
                                          <!--   <th scope="col">type Off Booking</th> -->
                                            <th scope="col">Summary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($account_loge) && is_array($account_loge)) {
                                            foreach ($account_loge as $data) { 
                                                $debit = ($data['debit'] * $data['convertion_rate']);
                                                $credit = ($data['credit'] * $data['convertion_rate']);
                                                     ?>
                                                <tr scope="row">
                                                    <td><?php echo $data['acc_ref_number']; ?></td>
                                                    <td>
                                                        <?php echo ($data['action_type'] == 'booking' && $data['transaction_type'] == 'debit') ? $data['invoice_number'] : '-'; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($data['action_type'] == 'refund' && $data['transaction_type'] == 'credit') ? $data['invoice_number'] : '-'; ?>
                                                    </td>

                                                    <td><?php echo $data['currency_symbol'] .' '.round_value($debit); ?></td>
                                                    <td><?php echo $data['currency_symbol'] .' '.round_value($credit); ?></td>
                                            <!--         <td><?php echo round_value($data['balance']); ?></td> -->
                                                    <td><?php echo date_created_format($data['created']); ?></td>
                                                   <!--  <td><?php echo str_replace('_',' ',$data['payment_mode']); ?></td>     -->                                               
                                                 <!--    <td><?php echo $data['currency_symbol'] .' '. $data['currency']; ?></td>    -->                                                
                                                     <td>
                                                        <a href="javascript:void(0);" view-data-modal="true" data-controller='account' data-id="<?php echo dev_encode($data['id']); ?>" 
                                                        data-href="<?php echo site_url('/dashboard/account-details/') . dev_encode($data['id']); ?>">Views</a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            echo "<tr> <td colspan='9' class='text-center'><b>No Data Found</b></td></tr>";
                                        } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?>
                                                    records
                                                    found </p>
                                            </div>
                                            <div class="col-6">
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
            </div>
        </div>
    </div>
</section>
