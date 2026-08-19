<div style="width:850px; height: auto;margin: 0px auto;">
    <center style="width: 600px; height: auto; position: absolute; top: 50%; left: 50%; background: #fff;  transform: translate(-50%, -50%);  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif;">
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <div style="text-align: center;">
                        <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" alt="logo.png" class="CToWUd" data-bit="iit" style="margin-bottom:10px; width:250px; height: 100px; object-fit: contain;">
                    </div>
                    <p style="margin: 0; text-align: center; text-transform: uppercase; font-size: 24px;"></p>
                </td>
            </tr>

        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 15px;">
                    <p style="margin: 0; text-align: left; ">Dear Customer,</p>
                    <p style="margin: 0; text-align: left; ">We hope this message finds you well.</p>
                    <h3 style="color: #333; margin: 10px 0;">
                        <strong><?php echo $otp;?></strong> is the OTP (One Time Password) required for logging into your account. If this login attempt was not initiated by you, please contact our customer care immediately.
                    </h3>
                    <div>
                        <p style="margin: 0; text-align: left; ">In the interest of your account's security, we recommend the following: </p>
                    </div>
                </td>
            </tr>
        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 15px;">
                    <p style="margin: 0; text-align: left;">
Keep your mobile phone number and email address updated on your account to ensure prompt notification of any suspicious activity.
Ensure the security of your account by using unique and strong passwords. Don't forget to take advantage of any additional security features offered.
Consider creating longer passwords that combine letters and numbers.
Use different passwords for each of your online accounts to enhance security.
Avoid predictable passwords and make it a habit to change your passwords regularly.
Thank you for your attention to these recommendations.
</p>
                    
                </td>
            </tr>
        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 15px;">
                    <p style="text-align: left; margin: 0;  padding-bottom: 0px;"> <span> <span>Thanks &amp; Regards,</span> <br> <span>Team <span class="il"><?php echo web_partner_details['company_name']?></span></span> </span>
                    </p>
                    <h4 style="text-align: center; "> Note: This is an auto generated email, please do not reply </h4>
                </td>
            </tr>
        </table>
        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;">
            <tr>
                <td style="width:100%;">
                    <table style="width: 100%; background-color: #3e3e3e;">
                        <tbody>
                        <tr>
                            <td style=" padding: 10px 10px 10px 10px; color: #ff1010;  font-weight: normal; text-align: center; ">
                                <h3 style="color: #fff; margin-top: 0px; text-align: center; margin-bottom: 0;"> <span> <span style="color: #fff; font-size: 12px; font-weight: 500;"> Need More help? We are Here, Ready to Talk. </span> </span>
                                </h3>
                                <h2 style="margin: 0px; text-align: center; padding: 0; font-weight: 500; line-height: 1;">
                                    <span style="font-size: 12px; color: #fff; margin-right: 30px;">
                                        <a href="tel:<?php echo web_partner_details['support_no']?>" style="color: #fff;" target="_blank">
                                            <img src="<?php echo root_url.'uploads/icons/'.'call-icon.png' ?>" style="vertical-align: middle; margin-right: 5px; width: 15px;" alt="phone.png" class="CToWUd" data-bit="iit"> <?php echo web_partner_details['support_no']?>
                                        </a>
                                    </span>
                                    <span>
                                        <a href="mailto:<?php echo web_partner_details['support_email']?>" style="color: #fff; font-size: 12px;" target="_blank"> <img src="<?php echo root_url.'uploads/icons/'.'email-icon.png' ?>" style="vertical-align: middle; margin-right: 5px; width: 15px;" alt="mail.png" class="CToWUd" data-bit="iit"> <span class="il"><?php echo trim(web_partner_details['support_email'])?></span>
                                        </a>
                                    </span>
                                </h2>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>

</div>