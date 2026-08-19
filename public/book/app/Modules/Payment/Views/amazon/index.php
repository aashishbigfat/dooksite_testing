<?php
  
echo "<html xmlns='https://www.w3.org/1999/xhtml'>\n<head></head>\n<body>\n";
echo "<form action='$URL' method='post' name='frm'>\n";
foreach ($Paytm_details as $a => $b) {
    echo "\t<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>\n";
}
echo "\t<script type='text/javascript'>\n";
echo "\t\tdocument.frm.submit();\n";
echo "\t</script>\n";
echo "</form>\n</body>\n</html>";


?>