<?php
$router = service('router');
$class_name = $router->controllerName();
$methodName = $router->methodName();
$classparm = explode("\\", $class_name);
$controller = end($classparm);

?>
<?php if (!empty(session()->get("Message"))):
    $message = session()->get("Message"); ?>
    <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');"><?php echo $message['Message']; ?>
    </div>
<?php endif ?>
<div data-message="true"></div>
<div class="footer_newsletter">
    <div class="container">
        <div class="footer-bottom" style="background-image: url('../webroot/img/footer/subscribe_bg.svg');">
            <div>
                <h2 class="newsletter-title">Get Special Offers And More From
                    <?php echo web_partner_details['company_name'] ?> </h2>
                <p class="text-white fs-md mb-0">Sign up now and get the best deals straight in your inbox!</p>
            </div>
            <form method="post" action="<?php echo site_url('newsletter'); ?>" tts-form="true" name="newsletterform"
                class="newsletter-form">
                <input type="email" class="form-control" name="email" placeholder="Enter Email Address">
                <button type="submit" class="SUBCRIBE_BTN">SUBSCRIBE <i
                        class="fa-solid fa-paper-plane ms-2"></i></button>
                <p newsletterformerror="true" class="m-0"></p>
            </form>
        </div>
    </div>
</div>
<footer class="footer" id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-6 col-12">
                    <div class="footer-widget">
                        <div class="logo">
                            <a href="<?php echo site_url('/') ?>">
                                <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo']; ?>"
                                    class="img-fluid tts-logo-ragular" style="display: none;"
                                    alt="<?php echo web_partner_details['company_name'] ?>" />
                                <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo']; ?>"
                                    class="img-fluid tts-logo-white"
                                    alt="<?php echo web_partner_details['company_name'] ?>" />
                            </a>
                        </div>
                        <p class="description">We’re always in search for talented
                            and motivated people. Don’t be shy introduce yourself!
                        </p>

                        <ul class="social-icon social-default justify-content-start">


                            <? if (web_partner_details['facebook_link']): ?>
                                <li><a target="_blank" class="social__list"
                                        href="<?= web_partner_details['facebook_link']; ?>" title="Facebook"><i
                                            class="fa-brands fa-facebook-f"></i></a></li>
                            <? endif; ?>
                            <? if (web_partner_details['instagram_link']): ?>
                                <li><a target="_blank" class="social__list"
                                        href="<?= web_partner_details['instagram_link']; ?>" title="Instagram"><i
                                            class="fa-brands fa-instagram"></i></a></li>
                            <? endif; ?>
                            <? if (web_partner_details['twitter_link']): ?>
                                <li><a target="_blank" class="social__list"
                                        href="<?= web_partner_details['twitter_link']; ?>" title="Twitter"><i
                                            class="fa-brands fa-twitter"></i></a></li>
                            <? endif; ?>
                            <? if (web_partner_details['linkedin_link']): ?>
                                <li><a target="_blank" class="social__list"
                                        href="<?= web_partner_details['linkedin_link']; ?>" title="Linkedin"><i
                                            class="fa-brands fa-linkedin-in"></i></a></li>
                            <? endif; ?>
                            <? if (web_partner_details['youtube_link']): ?>
                                <li><a target="_blank" class="social__list"
                                        href="<?= web_partner_details['youtube_link']; ?>" title="Youtube"><i
                                            class="fa-brands fa-youtube"></i></a></li>
                            <? endif; ?>
                        </ul>


                    </div>
                </div>
                <?php if (isset(tts_pages['footer1']) && tts_pages['footer1']): ?>
                    <div class="col-lg-2 col-md-6 col-12 footer-widget">
                        <h4><?php echo tts_pages['footer1']['menu_name']; ?></h4>
                        <?php if (tts_pages['footer1']['page_content']): ?>
                            <ul>
                                <?php foreach (tts_pages['footer1']['page_content'] as $pagecontent):
                                    if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                        <li><a href="<?php echo $pagecontent['custom_url'] ?>"><?php echo $pagecontent['title'] ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <li><a href="<?php echo site_url() . $pagecontent['slug_url'] ?>"
                                                target="_self"><?php echo $pagecontent['title'] ?></a></li>
                                    <?php } ?>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>
                <?php if (isset(tts_pages['footer2']) && tts_pages['footer2']): ?>
                    <div class="col-lg-2 col-md-6 col-12 footer-widget">
                        <h4><?php echo tts_pages['footer2']['menu_name']; ?></h4>
                        <?php if (tts_pages['footer2']['page_content']): ?>
                            <ul>
                                <?php foreach (tts_pages['footer2']['page_content'] as $pagecontent):
                                    if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                        <li><a href="<?php echo $pagecontent['custom_url'] ?>"><?php echo $pagecontent['title'] ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <li><a href="<?php echo site_url() . $pagecontent['slug_url'] ?>"
                                                target="_self"><?php echo $pagecontent['title'] ?></a></li>
                                    <?php } ?>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>
                <?php if (isset(tts_pages['footer3']) && tts_pages['footer3']): ?>
                    <div class="col-lg-2 col-md-6 col-12 footer-widget">
                        <h4><?php echo tts_pages['footer3']['menu_name']; ?></h4>
                        <?php if (tts_pages['footer3']['page_content']): ?>
                            <ul>
                                <?php foreach (tts_pages['footer3']['page_content'] as $pagecontent):
                                    if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                        <li><a href="<?php echo $pagecontent['custom_url'] ?>"><?php echo $pagecontent['title'] ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <li><a href="<?php echo site_url() . $pagecontent['slug_url'] ?>"
                                                target="_self"><?php echo $pagecontent['title'] ?></a></li>
                                    <?php } ?>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>
                <div class="col-lg-3 col-md-6 col-12 footer-widget">
                    <h4>Get Contact</h4>
                    <ul>

                        <? if (web_partner_details['support_email']): ?>
                            <li><span>Email:</span> <a href="mailto: <?php echo web_partner_details['support_email']; ?>">
                                    <?php echo web_partner_details['support_email'] ?> </a></li>
                        <? endif; ?>
                        <? if (web_partner_details['tollfree_no']): ?>
                            <li><i class="fa-solid fa-phone"></i>Toll Free:<?= web_partner_details['tollfree_no']; ?> </li>
                        <? endif; ?>
                        <? if (web_partner_details['support_no']): ?>
                            <li><span>Phone:</span> <a href="tel:<?php echo web_partner_details['support_no']; ?>">
                                    <?php echo web_partner_details['support_no'] ?> </a></li>
                        <? endif; ?>
                        <li>
                            <span>Address:</span>
                            <?php //echo web_partner_details['address'] ?>
                            <?php //echo web_partner_details['city'] ?>
                            <?php //echo web_partner_details['pincode'] ?>
                            <?php //echo web_partner_details['state'] ?>
                            <?php //echo web_partner_details['country'] ?>
							
							
							Corporate Office: 304, World Trade Tower, Sec 16, Noida, Uttar Pradesh - 201301
                            <br><br>
                            Reg Office: 44, 2nd Floor, Regal Building, Connaught Place, New Delhi-110001
							
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <div class="container py-3">
        <div class="row">
            <div class="col-sm-6">
                <div class="copyright text-left">Copyright © <?php echo date('Y'); ?>
                    <strong><span><?php echo web_partner_details['company_name'] ?></span></strong>. All Rights Reserved
                </div>
            </div>

        </div>
    </div>

</footer>
<!----footer------>
<div id="common_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" data-modal-view="view_modal_data"></div>
    </div>
</div>
<div class="modal fade" id="login-modal-b5" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
    aria-labelledby="login-modal-b5Label" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content w-100" data-modal-view="view_modal_data">
        </div>
    </div>
</div>
<input type="hidden" value="" id="web-partner-company-id">
<script type="text/javascript">
    var get_param = "<?php echo http_build_query($_GET); ?>";
    var site_url = "<?php echo site_url(); ?>";
    var DateFormat = "<?php echo DateFormat; ?>";
</script>
<?php
if ($controller == 'Flight' && $methodName == 'search') { ?>
    <script>
        flightsearchData = <?php echo json_encode($searchData); ?>
    </script>
<?php } ?>

<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/js/owl.carousel.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/owl.carousel.min.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/js/custom.js<?php echo last_modifytime(FCPATH . 'webroot/js/custom.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/js/jquery.form-validator.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.form-validator.min.js'); ?>"></script>

<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/js/angular.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/angular.min.js'); ?>"></script>

<?php if ($controller == 'Hotel') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/hotel.js<?php echo last_modifytime(FCPATH . 'webroot/js/hotel.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Flight' || $controller == 'Home') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/flight.js<?php echo last_modifytime(FCPATH . 'webroot/js/flight.js'); ?>"></script>
    <script>
        setTimeout(function() {
            let notificationModal = document.getElementById("notificationModal");
            if (notificationModal) {
                new bootstrap.Modal(notificationModal).show();
            }
        }, 900);
    </script>
<?php } ?>
<?php if ($controller == 'CarBooking') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/car.js<?php echo last_modifytime(FCPATH . 'webroot/js/car.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Activities') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/activities.js<?php echo last_modifytime(FCPATH . 'webroot/js/activities.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Tourguide') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/tourguide.js<?php echo last_modifytime(FCPATH . 'webroot/js/tourguide.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Bus') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/bus.js<?php echo last_modifytime(FCPATH . 'webroot/js/bus.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'CruiseBooking') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/cruise.js<?php echo last_modifytime(FCPATH . 'webroot/js/cruise.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'HolidayBooking') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/holiday.js<?php echo last_modifytime(FCPATH . 'webroot/js/holiday.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Visa') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/visa.js<?php echo last_modifytime(FCPATH . 'webroot/js/visa.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'BikeTour') { ?>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/js/biketour.js<?php echo last_modifytime(FCPATH . 'webroot/js/biketour.js'); ?>"></script>
<?php } ?>
<script>
    $(document).on("click", "[forgot-password='true']", function(e) {
        $("[pass-param='true']").val('forgot-password');
        $("[continue]").click();
    });
    $(document).on("submit", "[praveen-login-form='true']", function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var name = form.attr('name');
        $("[data-message]").removeClass().html("");
        $(".form-error").removeClass().html("");
        var buttontxt;
        buttontxt = $("button[type=submit]", form).text();
        $("button[type=submit]", form).attr('disabled', true).html('Loading...');
        $("span.error-message", form).replaceWith("");
        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(resp) {
                $("button[type=submit]", form).attr('disabled', false).html(buttontxt);

                if (resp.StatusCode == 1) {
                    $.each(resp.ErrorMessage, function(key, val) {
                        $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');
                    });
                } else if (resp.StatusCode == 0) {
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                    window.location.reload();

                } else if (resp.StatusCode == 3) {
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);

                } else if (resp.StatusCode == 5) {

                    $("[data-modal-view='view_modal_data']").html("");
                    $("[data-modal-view='view_modal_data']").html(resp.Message);
                } else {
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                }

            },
            error: function(res) {
                alert("Unexpected error! Try again.");
                // location.reload();
            }
        });
    });
</script>
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>








<!-- currency Modal -->
<div class="modal fade" id="currency" tabindex="-1" aria-labelledby="currencyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="currencyLabel">Currency</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="currency-title">Top Currencies</div>
                <div class="urrency__content">
                    <ul class="currency__list curr_top u-clearfix">
                        <?php
                        $session  =  \Config\Services::session();
                        $website_currencies =  $session->get('website_currencies');
                        $selectedWebsiteCurrency = $session->get('selected_website_currency');

                        if (!empty($website_currencies)) {
                            foreach ($website_currencies as  $website_currency) {
                                $CurrencyIcon = [];
                                if (is_array($website_currency)) {
                                    $CurrencyIcon = getCurrencyIcon($website_currency);
                                }
                                $currencyName = isset($CurrencyIcon['currencyName']) ? $CurrencyIcon['currencyName'] : 'India';
                                $currencySymbol = isset($CurrencyIcon['currencySymbol']) ? $CurrencyIcon['currencySymbol'] : '₹';
                                $currencyCode = isset($website_currency['currency']) ? $website_currency['currency'] : "INR";
                                $selectedCurrency = isset($selectedWebsiteCurrency['currency']) ? $selectedWebsiteCurrency['currency'] : "INR";


                                $isSelected = ($selectedCurrency == $currencyCode);
                                $cssClass = $isSelected ? 'currency__list-item active disabled'  : 'currency__list-item';
                                $onClick = $isSelected ? '' : 'onclick="ChangeWebsiteCurrency(\'' . htmlspecialchars($currencyCode, ENT_QUOTES, 'UTF-8') . '\')"';
                                $tooltipMessage = $isSelected ? "Highlighted currency is already selected. If you choose another currency, it will be changed." : "Click to change the currently activate currency.";
                        ?>
                                <li class="<?php echo $cssClass; ?>" <?php echo $onClick; ?> data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo htmlspecialchars($tooltipMessage, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="currency__item">
                                        <span class="currency__code"><?php echo htmlspecialchars($currencyCode, ENT_QUOTES, 'UTF-8'); ?></span>-<span class="currency__name"> <?php echo htmlspecialchars($currencyName, ENT_QUOTES, 'UTF-8'); ?> ( <?php echo htmlspecialchars($currencySymbol, ENT_QUOTES, 'UTF-8'); ?> )</span>
                                    </div>
                                </li>
                        <?php }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--language Modal -->
<div class="modal fade" id="language" tabindex="-1" aria-labelledby="languageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="languageLabel">language</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="currency-title">Current Language</div>
                <div class="urrency__content">
                    <ul class="currency__list curr_top u-clearfix">
                        <li class="currency__list-item">
                            <div class="currency__item active"><span class="currency__code"><i
                                        class="ic-flag ic-flag-en_us"></i></span><span
                                    class="currency__name Country_name">English (United States)</span></div>
                        </li>

                    </ul>
                </div>
                <div class="currency-divider"></div>
                <div class="currency-title">All Languages</div>
                <div class="urrency__content">
                    <ul class="currency__list u-clearfix">
                        <li class="currency__list-item">
                            <div class="currency__item active"><span class="currency__code"><i
                                        class="ic-flag ic-flag-en_us"></i></span><span
                                    class="currency__name Country_name">English (United States)</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-dk"></i></span><span
                                    class="currency__name Country_name">Dansk</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-pl"></i></span><span
                                    class="currency__name Country_name">Polski</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-jp"></i></span><span
                                    class="currency__name Country_name">日本語</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-de"></i></span><span
                                    class="currency__name Country_name">Deutsch</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-br"></i></span><span
                                    class="currency__name Country_name">Português (Brasil)</span></div>
                        </li>
                        <li class="currency__list-item">
                            <div class="currency__item"><span class="currency__code"><i
                                        class="ic-flag ic-flag-kr"></i></span><span
                                    class="currency__name Country_name">한국어</span></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>