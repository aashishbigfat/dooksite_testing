<div>
    <center style="padding:20px; width: 600px;margin:auto; border: 1px solid #C7C7C7; height: auto; position: absolute; top: 50%; left: 50%; background: #fff;  transform: translate(-50%, -50%);  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif;">
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%;">
                    <div style="text-align: center;">
                        <img src="<?php echo root_url . 'uploads/logo/' . super_admin_website_setting['company_logo'] ?>" alt="logo.png" class="CToWUd"
                            data-bit="iit"
                            style="margin-bottom:10px; width:250px; height: 100px; object-fit: contain;">
                    </div>
                    <p style="margin: 0; text-align: center; text-transform: uppercase; font-size: 18px;">Flight Booking Refunded Data</p>
                </td>
            </tr>

        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px 6px;">
                    <h3 style="color: #333; margin: 0px 0;">
                        <strong><?php echo $Subject; ?></strong>
                    </h3>
                </td>
            </tr>

            <tr>
                <td style="width: 100%; padding: 10px 6px;">
                    <h3 style="color: #333; margin: 0px 0;">
                        <strong><?php echo "Dear Travel Partner" . " " . $lead_pax ?></strong>
                    </h3>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 10px 6px;">
                    <h3 style="color: #333; margin: 0px 0;">
                        <strong><?php echo "We have processed your refund, please find the details as mentioned below:" ?></strong>
                    </h3>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Reference Number</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $BookingRefNo; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Booking Refunded Status</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $Amendment_status; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Sector</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $origin . " To" . " " . $destination; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Origin</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $origin ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Destination</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $destination ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Passenger Name</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">
                                <?php foreach ($paxs as $key => $pax) { ?>
                                    <?php echo $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name']; ?>
                                    <?php if ($key !== array_key_last($paxs)) echo ', '; ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Remark</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $remark; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;">Generation Time</td>
                            <td style="font-size: 14px; padding: 5px 8px; border: 1px solid gray;"><?php echo $GenerationTime; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <p style="text-align: left; margin: 0;  padding-bottom: 0px;">
                        <span> <span>Thanks &amp; Regards,</span> <br> <span>Team <span
                                    class="il"><?php echo super_admin_website_setting['company_name'] ?></span></span> </span>
                    </p>
                    <h4 style="text-align: center; "> Note: This is an auto generated email, please do not reply </h4>
                </td>
            </tr>
        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <h3 style="color: #333; margin: 0px 0;">
                        <strong><?php echo "Terms and conditions" ?></strong>
                    </h3>
                    <p> • Refund has been processed as per services requested</p>
                    <p> • In case of any discrepancies please reach out to us within 4 hours</p>
                    <p> • Cancellation charges are levied as per the airline policy with our service charges</p>
                </td>
            </tr>
        </table>
        <table style="width: 100%; background-color: #3e3e3e;">
            <tbody>
                <tr>
                    <td style=" padding: 10px 10px 10px 10px; color: #ff1010;  font-weight: normal; text-align: center; ">
                        <h3 style="color: #fff; margin-top: 0px; text-align: center; margin-bottom: 0;">
                            <span> <span style="color: #fff; font-size: 12px; font-weight: 500;"> Need More help? We are Here, Ready to Talk. </span> </span>
                        </h3>
                        <h2 style="margin: 0px; text-align: center; padding: 0; font-weight: 500; line-height: 1;">
                            <span style="font-size: 12px; color: #fff; margin-right: 30px;">
                                <a href="tel:<?php echo super_admin_website_setting['support_no'] ?>"
                                    style="color: #fff;" target="_blank">
                                    <img src="<?php echo root_url . 'uploads/icons/' . 'call-icon.png' ?>"
                                        style="vertical-align: middle; margin-right: 5px; width: 15px;"
                                        alt="phone.png" class="CToWUd"
                                        data-bit="iit"> <?php echo super_admin_website_setting['support_no'] ?>
                                </a>
                            </span>
                            <span>
                                <a href="mailto:<?php echo super_admin_website_setting['support_email'] ?>"
                                    style="color: #fff; font-size: 12px;" target="_blank"> <img
                                        src="<?php echo root_url . 'uploads/icons/' . 'email-icon.png' ?>"
                                        style="vertical-align: middle; margin-right: 5px; width: 15px;"
                                        alt="mail.png" class="CToWUd" data-bit="iit"> <span
                                        class="il"><?php echo super_admin_website_setting['support_email'] ?></span>.
                                </a>
                            </span>
                        </h2>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>

</div>