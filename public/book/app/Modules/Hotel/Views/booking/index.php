<?php
$search_Form_template = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';
echo view('template/wl_searchs/wl_searchform-' . $search_Form_template . '.php');

$template = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';
echo view('template/wl_homes/wl_homepage-' . $template . '.php');
?>
