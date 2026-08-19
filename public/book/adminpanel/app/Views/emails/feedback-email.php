<!DOCTYPE html>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 20px;">
                            <tr>
                                <td style="width: 150px; vertical-align: middle;">
                                    <img src="<?= root_url .'uploads/logo/'.$logo; ?>" alt="Company Logo" style="max-height: 60px;">
                                   
                                </td>
                                <td style="vertical-align: middle;">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="font-size: 14px; color: #666;">
                                                <p><strong><?= $company_name; ?></strong></p>
                                                <p><?= $address; ?></p>
                                                <p><?= $city; ?></p>
                                                <p><?= $state; ?>, <?= $country; ?></p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="color: #e74c3c; font-size: 24px; margin-bottom: 20px; font-weight: bold; text-align: center; border-bottom: 2px solid #e74c3c; padding-bottom: 10px;">
                            Journey Feedback Request for <?php echo $service; ?> booking reference number: <?= $BookingRefrenceNumber; ?>
                        </p>
                        <p style="margin: 0 0 15px; line-height: 1.6;"><span style="font-weight: bold; color: #e74c3c;">Passenger Name:</span> <?= $PassengerName; ?></p>
                        <p style="margin: 0 0 15px; line-height: 1.6;"><span style="font-weight: bold; color: #e74c3c;">Booking Date:</span> <?= date('d M Y', $createdDate); ?></p>
                        <p style="margin: 0 0 15px; line-height: 1.6;"><span style="font-weight: bold; color: #e74c3c;">Travel Date:</span> <?= date('d M Y', $TravelStartDate); ?></p>
                        <p style="margin: 0 0 15px; line-height: 1.6;">We value your feedback. Please click the link below to let us know how we did:</p>
                        <a href="<?= $url; ?>" style="display: inline-block; padding: 12px 20px; font-size: 16px; color: #fff; background-color: #e74c3c; text-decoration: none; border-radius: 5px; text-align: center; border: none; cursor: pointer;">Share Your Experience</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
