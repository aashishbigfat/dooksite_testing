<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <body style="margin: 0px 0; padding: 20px 0; font-family: inherit !important; background-color: #f4f4f4;">
      <table style="width: 100%; max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);" cellpadding="0" cellspacing="0">
         <!-- Header -->
         <tr>
            <td style="background-color: #fff; color: #ffffff; text-align: center; padding: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
               <img src="<?php echo htmlspecialchars(root_url . 'uploads/logo/' . super_admin_website_setting['company_logo']); ?>" alt="<?php echo htmlspecialchars(super_admin_website_setting['company_name']); ?>" style="max-width: 120px; height: auto;">
            </td>
         </tr>
         <!-- Body -->
         <tr>
            <td style="padding: 20px; color: #333333;">
               <h1 style="font-size: 24px; margin-bottom: 15px;"> <?php echo !empty($UseName) ? htmlspecialchars(ucwords($UseName)) : ""; ?></h1>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;"><?php echo !empty($AmountsMessage) ? htmlspecialchars(ucwords($AmountsMessage)) : ""; ?></p>
               <p></p>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;"><?php echo !empty($Remark) ? htmlspecialchars(ucwords($Remark)) : ''; ?></p>
               <div style="background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                  <h2 style="font-size: 20px; margin-bottom: 10px;">Account Details:</h2>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>Username :</strong>  <?php echo htmlspecialchars($LoginUserName); ?></p>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>Designation :</strong>  <?php echo htmlspecialchars($Designation); ?></p>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>AgentClass :</strong>  <?php echo htmlspecialchars(ucfirst($AgentClass)); ?></p>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>Company ID :</strong>  <?php echo htmlspecialchars($CompanyID); ?></p>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>Activation Date:</strong> <?php echo htmlspecialchars(date_created_format($ActivationDate)); ?></p>
                  <p style="font-size: 16px; line-height: 1.5; margin-bottom: 5px;"><strong>To log in,</strong> Please visit :- <a href="<?php echo htmlspecialchars($LoginUrl); ?>" target="_blank" style="color: #007BFF; text-decoration: none;">Click Now</a></p>
                 
               </div>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;">If you have any questions or encounter any issues while logging in, feel free to reach out to our support team at <?php echo htmlspecialchars(super_admin_website_setting['support_email']); ?>or call us at <?php echo htmlspecialchars(super_admin_website_setting['support_no']); ?>.</p><p></p>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;">We look forward to working with you!.</p>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;"> <strong>Note: This is an auto-generated email; please do not reply.</strong> </p>
               <p style="font-size: 16px; line-height: 1.5; margin-bottom: 15px;">Best regards,<br>The <?php echo htmlspecialchars(super_admin_website_setting['company_name']); ?> Team</p>
            </td>
         </tr>
         <!-- Contact Info -->
         <tr>
            <td style="background-color: #ebfffb; color: #333333; text-align: center; padding: 20px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
               <p style="font-size: 14px; color: #666666; margin: 10px 0;"><strong>Support Email:</strong> <a href="mailto:<?php echo htmlspecialchars(super_admin_website_setting['support_email']); ?>" style="color: #666666; text-decoration: none;"><?php echo htmlspecialchars(super_admin_website_setting['support_email']); ?></a></p>
               <p style="font-size: 14px; color: #666666; margin: 10px 0;"><strong>Support Phone:</strong> <a href="tel:<?php echo htmlspecialchars(super_admin_website_setting['support_no']); ?>" style="color: #666666; text-decoration: none;"><?php echo htmlspecialchars(super_admin_website_setting['support_no']); ?></a></p>
            </td>
         </tr>
         <!-- Footer --> 
         <tr style="text-align: center;">
            <td style="background-color: #ebfffb; color: #333333; text-align: center; padding: 20px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
               <p style="margin: 10px 0;">Connect with us:</p>
               <ul style="list-style: none; padding: 0; margin: 10px 0; text-align:center">
                  <?php if(super_admin_website_setting['facebook_link']){ ?>
                  <li style="margin: 0 8px; display:inline-block;"><a href="<?php echo htmlspecialchars(super_admin_website_setting['facebook_link']); ?>" style="display: block; width: 40px; height: 40px; line-height: 40px; text-align: center; color: #ffffff; border-radius: 50%; font-size: 16px; text-decoration: none; background-color: #3b5998;" target="_blank" title="Facebook">F</a></li>
                  <?php } ?>   
                  <?php if(super_admin_website_setting['twitter_link']){ ?>
                  <li style="margin: 0 8px; display:inline-block;"><a href="<?php echo htmlspecialchars(super_admin_website_setting['twitter_link']); ?>" style="display: block; width: 40px; height: 40px; line-height: 40px; text-align: center; color: #ffffff; border-radius: 50%; font-size: 16px; text-decoration: none; background-color: #1da1f2;" target="_blank" title="Twitter">T</a></li>
                  <?php } ?>   
                  <?php if(super_admin_website_setting['linkedin_link']){ ?>
                  <li style="margin: 0 8px; display:inline-block;"><a href="<?php echo htmlspecialchars(super_admin_website_setting['linkedin_link']); ?>" style="display: block; width: 40px; height: 40px; line-height: 40px; text-align: center; color: #ffffff; border-radius: 50%; font-size: 16px; text-decoration: none; background-color: #0077b5;" target="_blank" title="LinkedIn">L</a></li>
                  <?php } ?>   
                  <?php if(super_admin_website_setting['instagram_link']){ ?>
                  <li style="margin: 0 8px; display:inline-block;"><a href="<?php echo htmlspecialchars(super_admin_website_setting['instagram_link']); ?>" style="display: block; width: 40px; height: 40px; line-height: 40px; text-align: center; color: #ffffff; border-radius: 50%; font-size: 16px; text-decoration: none; background-color: #e4405f;" target="_blank" title="Instagram">I</a></li>
                  <?php } ?>    
               </ul>
               <p style="font-size: 14px; color: #666666; margin: 10px 0;">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(super_admin_website_setting['company_name']); ?>. All rights reserved.</p>
            </td>
         </tr>
      </table>
   </body>
</html>