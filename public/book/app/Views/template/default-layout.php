<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($title) && $title) {
                echo esc(trim(ucwords($title)));
            } else {
                echo trim(ucwords(web_partner_details['meta_title']));
            } ?></title>
    <meta name="keywords" content="<?php if (isset($metakeywords) && $metakeywords) {
                                        echo trim(ucwords($metakeywords));
                                    } else {
                                        echo trim(ucwords(web_partner_details['meta_keyword']));
                                    } ?>">
    <meta name="description" content="<?php if (isset($metadescription) && $metadescription) {
                                            echo trim(ucwords($metadescription));
                                        } else {
                                            echo trim(ucwords(web_partner_details['meta_description']));
                                        } ?>">
    <meta name="robots" content="<?php if (isset($metarobots) && $metarobots) {
                                        echo trim($metarobots);
                                    } else {
                                        echo trim(web_partner_details['meta_robots']);
                                    } ?>">
    <meta name="google-signin-client_id" content="00553622580-g1mcisnklgmkpdnq2b80tgqjql9n1hc3.apps.googleusercontent.com">
    <link rel="shortcut icon" href="<?php echo root_url . 'uploads/favicon/' . web_partner_details['company_favicon'] ?>" type="image/x-icon" />
    <link rel="apple-touch-icon" href="<?php echo root_url . 'uploads/favicon/' . web_partner_details['company_favicon'] ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/font-awesome-pro.css<?php echo last_modifytime(FCPATH . 'webroot/css/font-awesome-pro.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/jquery-ui.min.css<?php echo last_modifytime(FCPATH . 'webroot/css/jquery-ui.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/fancybox/css/jquery.fancybox.min.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/fancybox/css/jquery.fancybox.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/custom.css<?php echo last_modifytime(FCPATH . 'webroot/css/custom.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/flight_result.css<?php echo last_modifytime(FCPATH . 'webroot/css/flight_result.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/owl.carousel.css<?php echo last_modifytime(FCPATH . 'webroot/css/owl.carousel.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/owl.theme.css<?php echo last_modifytime(FCPATH . 'webroot/css/owl.theme.default.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/animate.min.css<?php echo last_modifytime(FCPATH . 'webroot/css/animate.min.css'); ?>">
    <?php $Header_template_css = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';  ?>
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/wl_headers/wl_headerpage-<?php echo $Header_template_css; ?>.css<?php echo last_modifytime(FCPATH . 'webroot/css/wl_headers/wl_headerpage-' . $Header_template_css . '.css'); ?>">
    <?php $Search_template_css = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';  ?>
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/wl_searchs/wl_searchform-<?php echo $Search_template_css; ?>.css<?php echo last_modifytime(FCPATH . 'webroot/css/wl_searchs/wl_searchform-' . $Search_template_css . '.css'); ?>">
    <?php $Home_template_css = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';  ?>
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/wl_homes/wl_homepage-<?php echo $Home_template_css; ?>.css<?php echo last_modifytime(FCPATH . 'webroot/css/wl_homes/wl_homepage-' . $Home_template_css . '.css'); ?>">
    <?php $Footer_template_css = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';  ?>
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/wl_footers/wl_footerpage-<?php echo $Footer_template_css; ?>.css<?php echo last_modifytime(FCPATH . 'webroot/css/wl_footers/wl_footerpage-' . $Footer_template_css . '.css'); ?>">
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
   
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery-ui.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery-ui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/angular.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/angular.min.js'); ?>"></script>

    
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/vendor/fancybox/js/jquery.fancybox.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/fancybox/js/jquery.fancybox.min.js'); ?>"></script>


    <?php if (whitelabel['custom_css']) { ?>
        <style>
            <?php echo whitelabel['custom_css']; ?>
        </style>
    <?php } ?>
    <?php echo isset(whitelabel['google_analytics']) ? trim(whitelabel['google_analytics']) : '' ?>
    <script type='application/ld+json'>
          {
          "@context": "http://schema.org",
          "@type": "Product",
          "@brand": "Dook International",
          "name": "Dook International Products Reviews Given by Customers, Travel Agents and Travel Agencies",
          "image": "https://www.dookinternational.com/assets/images/logo.png",
          "description": "We would like to thanks Dook International for such a wonderful trip. It was lifetime memorable trip and arrangement was so good like Hotels, Local Guide, Food, Sightseeing, Visa Support, Airlines, Attractions Included in Package, etc. We are very happy with the excellent services provided by Dook International. In future Dook will our preference for any CIS & European Countries Tour.",
          "aggregateRating": {
            "@type": "aggregateRating",
            "ratingValue": "4.3",
            "bestRating": "5",
            "reviewCount": "8863"
          }
        }
        </script>
        <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KXXVMQ9');</script>
            <!-- End Google Tag Manager -->

        <!-- Google tag (gtag.js) -->
   <!--      <script async src="https://www.googletagmanager.com/gtag/js?id=G-S1E85XHQJB"></script>

        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-S1E85XHQJB');
        </script> -->
        <!-- Facebook Pixel Code -->
<!--             <script>
              !function(f,b,e,v,n,t,s)
              {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
              n.callMethod.apply(n,arguments):n.queue.push(arguments)};
              if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
              n.queue=[];t=b.createElement(e);t.async=!0;
              t.src=v;s=b.getElementsByTagName(e)[0];
              s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
              fbq('init', '434898591343587');
              fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=434898591343587&ev=PageView&noscript=1"/></noscript> -->
            <!-- End Facebook Pixel Code -->
            <!-- Google tag (gtag.js) -->
<!--         <script async src="https://www.googletagmanager.com/gtag/js?id=AW-807561221"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'AW-807561221');
        </script> -->

</head>

<body>
            <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KXXVMQ9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <?php echo isset(whitelabel['google_analytics_body']) ? trim(whitelabel['google_analytics_body']) : '' ?>
    <?php
    $Header_template = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';
    echo view('template/wl_headers/wl_headerpage-' . $Header_template . '.php');
    ?>

    <div class="def_layout_content">
        <?php
        try {
            echo view('Modules/' . $view);
        } catch (Exception $e) {
            echo "<pre><code>$e</code></pre>";
        }
        ?>
    </div>
    <?php
    $Footer_template = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';
    echo view('template/wl_footers/wl_footerpage-' . $Footer_template . '.php');
    ?>




    <?php $Footer_template_javascript = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';  ?>

    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/wow.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/wow.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/wl_javascript/wl_javascript-<?php echo $Footer_template_javascript; ?>.js<?php echo last_modifytime(FCPATH . 'webroot/js/wl_javascript/wl_javascript-' . $Footer_template_javascript . '.js'); ?>"></script>

    <script>
        // $(document).ready(function() {
        //     var message = "Access To This Feature Has Been Disabled By A Restrictions Set By Your System Administrator";

        //     // Disable right-click
        //     $(document).on("contextmenu", function() {
        //         alert(message);
        //         return false;
        //     });

        //     // Disable Ctrl+U and Ctrl+C
        //     $(document).on("keydown", function(e) {
        //         if (e.ctrlKey && (e.which === 85 || e.which === 67)) {
        //             e.preventDefault();
        //             /* alert("Copying and viewing source code is disabled"); */
        //             alert(message);
        //         }
        //     });

        //     // Disable Ctrl+Shift+all keys
        //     $(document).on("keydown", function(event) {
        //         if (
        //             (event.ctrlKey && event.shiftKey && event.key === 'I') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'J') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'C') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'A') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'B') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'D') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'E') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'F') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'G') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'H') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'K') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'L') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'M') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'N') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'O') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'P') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'Q') ||
        //            /*  (event.ctrlKey && event.shiftKey && event.key === 'R') || */
        //             (event.ctrlKey && event.shiftKey && event.key === 'S') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'T') ||
        //            /*  (event.ctrlKey && event.shiftKey && event.key === 'U') || */
        //             (event.ctrlKey && event.shiftKey && event.key === 'V') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'W') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'X') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'Y') ||
        //             (event.ctrlKey && event.shiftKey && event.key === 'Z') 
        //         ) {
        //            /*  alert('Developer tools are disabled!'); */
        //            alert(message);
        //             event.preventDefault();
        //             return false;
        //         }
        //     });

        //    // Cutting text is disabled
        //    document.addEventListener("cut", function(e) {
        //       e.preventDefault();
        //      /*  alert("Cutting text is disabled"); */
        //      alert(message);
        //    });
        //    // Copying text is disabled
        //    document.addEventListener("copy", function(e) {
        //       e.preventDefault();
        //       /* alert("Copying text is disabled"); */
        //       alert(message);
        //    });

        //    // Disable text selection
        //    let FF
        //    if (CSS.supports("( -moz-user-select: none )"))
        //    { FF = 1 } else { FF = 0 }
        //    (FF===1) ? document.body.style.MozUserSelect="none" : document.body.style.userSelect="none"

        // });
    </script>
    <script>
        new WOW({
            boxClass: 'wow',
            animateClass: 'animate__animated',
            offset: 100,
            mobile: true,
            live: true
        }).init();
    </script>


</body>

</html>