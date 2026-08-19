<div id="topbar" class="topbar d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-end">
        <div class="contact-info d-flex align-items-center">
            <i class="fa-solid fa-envelope d-flex align-items-center"></i><a href="mailto:<?php echo web_partner_details['support_email']; ?>"><?php echo web_partner_details['support_email']; ?></a>
            <i class="fa-solid fa-phone d-flex align-items-center ms-4"></i><a href="tel:<?php echo web_partner_details['support_no']; ?>"><?php echo web_partner_details['support_no']; ?></a>
        </div>
        <?php
        $b2cBusinessActive = isset(whitelabel['b2c_business']) && whitelabel['b2c_business'] == "active";
        $multiCurrencyActive = isset(whitelabel['multi_currency']) && whitelabel['multi_currency'] == "active";
        $multiLanguageActive = isset(whitelabel['multi_language']) && whitelabel['multi_language'] == "active";
        ?>
        <?php  if (0){ ?>
       <?php  if ($b2cBusinessActive && ($multiCurrencyActive || $multiLanguageActive)): ?>
            <div class="languages d-none d-md-flex align-items-center">
                <ul>
                    <?php if ($multiCurrencyActive):
                        $session = \Config\Services::session();
                        $selectedWebsiteCurrency = $session->get('selected_website_currency');
                        $CurrencyIcon = [];
                        if (is_array($selectedWebsiteCurrency)) {
                            $CurrencyIcon = getCurrencyIcon($selectedWebsiteCurrency);
                        }
                        $currency = isset($CurrencyIcon['currency'])  ? $CurrencyIcon['currency'] : 'INR';
                        $currencySymbol = isset($CurrencyIcon['currencySymbol']) ? $CurrencyIcon['currencySymbol'] : ' ₹';
                        $currencyName = isset($CurrencyIcon['currencyName']) ? $CurrencyIcon['currencyName'] : 'India';
                    ?>
                        <li>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#currency">
                                <?php echo htmlspecialchars($currency, ENT_QUOTES, 'UTF-8'); ?>
                                <?php echo htmlspecialchars($currencyName, ENT_QUOTES, 'UTF-8'); ?>
                                ( <?php echo htmlspecialchars($currencySymbol, ENT_QUOTES, 'UTF-8'); ?> )
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($multiLanguageActive): ?>
                        <li>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#language">EN</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php } ?>
        <div class="dropdown has-megamenu d-none">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> / </a>
            <ul class="dropdown-menu dropdown-menu-end megamenu currency-dropdown">
                <div class="menu-language">
                    <div class="currency-language-text" tabindex="0">Language</div>
                    <ul class="p-0">
                        <li class="menu-language__item">
                            <a class="menu-language__link active" href="javascript:">
                                <span class="flag-us"></span><span class="code">United States - English</span>
                            </a>
                        </li>
                        <li class="menu-language__item">
                            <a class="menu-language__link" href="javascript:">
                                <span class="flag-us"></span><span class="code">Estados Unidos - Español</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php if (whitelabel['b2b_business'] == "active" && whitelabel['multi_currency'] == "active") { ?>
                    <div class="currency-language-text" tabindex="0">Currency</div>
                    <ul class="currencyList">
                        <li class="currency-dropdown__sub" tabindex="0" aria-label="select currency USD">
                            <a class="currency-dropdown__link">
                                <span class="flag-USD"></span><span class="code">USD <span class="badge">$</span></span>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="home-header d-md-flex align-items-center">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar_header">
            <div class="logo-style">
                <a class="navbar-brand" id="logo" href="<?php echo web_partner_redirect_url ?>">
                    <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid tts-logo-ragular" style="display: none;" alt="<?php echo web_partner_details['company_name'] ?>" />
                    <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid tts-logo-white" alt="<?php echo web_partner_details['company_name'] ?>" />
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="fa-solid fa-bars"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" aria-modal="true" role="dialog">
                <div class="offcanvas-header">
                    <div class="logo-style">
                        <a class="navbar-brand" id="logo" href="<?php echo site_url("/"); ?>">
                            <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid tts-logo-ragular" style="display: none;" alt="<?php echo web_partner_details['company_name'] ?>" />
                            <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid tts-logo-white" alt="<?php echo web_partner_details['company_name'] ?>" />
                        </a>
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav me-auto ms-auto mb-lg-0  mb-2">
                        <?php if (whitelabel['flight_module'] == 'active') { ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link1 <?php echo active_header("Flight"); ?>" aria-current="page" href="<?php echo site_url("/flight"); ?>">
                                    Flight
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (whitelabel['hotel_module'] == 'active') { ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link1 <?php echo active_header("Hotel"); ?>" href="<?php echo site_url("hotel"); ?>">
                                    Hotel
                                </a>
                            </li>
                        <?php } ?>
                        
                       
                        <li class="nav-item">
                            <a class="nav-link nav-link1 <?php echo active_header("Blog"); ?>" href="<?php echo site_url("blog"); ?>">
                                Blog </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link1 <?php echo active_header("ContactUs"); ?>" href="<?php echo site_url("contact-us"); ?>">
                                Contact Us
                            </a>
                        </li>

                    </ul>
                    <div class="dropdown login-dropdown">
                        <?php if (!session()->get('wl_customer')) { ?>
                            <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
                            <button type="button" data-bs-toggle="dropdown" data-id="" data-href="#" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa-solid fa-sign-in"></i> Login / Signup</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <span>Get exclusive deals &amp; Manage your trips</span>
                                <button type="button" view-data-modal="B5-Login" data-controller='login' data-id="" data-href="<?php echo site_url('login/login-modal/'); ?>" class="btn btn-secondary">Login / Signup</button>
                                <a href="<?php echo site_url('offers-list') ?>" class="dropdown-box">
                                    <i class="fa-solid fa-gift"></i>
                                    <div class="dropdown-contnt">
                                        <h4>Offer</h4>
                                        <p>Handpicked best deals</p>
                                    </div>
                                </a>
                                <?php if (whitelabel['b2b_business'] == "active") { ?>
                                    <a href="<?php echo site_url('/agent') ?>" class="dropdown-box">
                                        <i class="fa-solid fa-users"></i>
                                        <div class="dropdown-contnt">
                                            <h4>Agent Login</h4>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>

                        <?php } else { ?>
                            <button type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                <i class="fa fa-user"></i>
                                <?php
                                if (session()->get('wl_customer')['first_name'] || session()->get('wl_customer')['last_name']) {

                                    echo session()->get('wl_customer')['first_name'] . ' ' . session()->get('wl_customer')['last_name'] . " " . "(" . session()->get('wl_customer')['customer_id'] . ")";
                                } else {

                                    echo 'Hi Traveller' . " " . "(" . session()->get('wl_customer')['customer_id'] . ")";;
                                }

                                ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-menu-header">
                                    <li><a class="dropdown-item" href="<?php echo site_url("dashboard"); ?>"> <i class="tts-icon dashboard"></i> Dashboard </a></li>
                                    <li><a class="dropdown-item" href="<?php echo site_url("signout"); ?>"> <i class="tts-icon signout"></i> Signout </a></li>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>