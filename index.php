<?php

include('config.php');
include('functions.php');
include('recaptchalib.php');
include('stats.php');

$page = '<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8"/>
 <title>' . $currency . ' Faucet</title>
</head>
<body>';

$back = '<a href="/">Home</a> | <a href="javascript: history.go(-1)">Go Back</a>';

$res = send_json_request('getbalance');
$stats = print_stats();
$footer = '
 <p>Donate to: ' . $donations . '<br>
 Server Balance: ' . number_format($res['result'],8) . ' <br>';
$footer .= $stats . $links . $back;

// check if we have paid a request from this ip
$wait = checkip();
switch ($wait) {
   case $wait > 90: die($page . '<p>Please wait ' . round($wait/60) . ' minutes.</p>' . $footer); break;
   case $wait > 1: die($page . '<p>Please wait ' . $wait . ' seconds.</p>' . $footer); break;
}

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

// check if an address has been submitted, then if it's valid & if we've already paid it
if (isset($_POST['a'])) {
   $address = trim($_POST['a']);
   $test = test_address($address);
   $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER['REMOTE_ADDR'],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
   switch ($test) {
      case 0:
         if ($resp->is_valid) {
           $pay = payout($address);
           if (is_array($pay))
              die ($page. '<p>Paid ' . $pay['amount'] . ' to ' . $address . ' in transaction id ' . $pay['tid'] . '</p>' . $footer);
           die ($page . '<p>Faucet is dry, please donate!<p>' . $footer);
          } else {
               $error = $resp->error;
               die("<p>Captcha Challenge Failed<br><a href=\"javascript:history.back()\">Back</a>");
          }
          break;
      case $test < 0: $page .= '<p>Invalid ' . $currency . ' address, please try again. ' . $test . '</p>'; break;
      case $test > 99: die($page . '<p>Please wait ' . round($test/60) . ' minutes.</p>' . $footer); break;
      case $test > 1: die($page . '<p>Please wait ' . $test . ' seconds.</p>' . $footer); break;
      case 1: die($page . '<p>Please wait one second.</p>' . $footer); break;
   }
}

$page .= '
 <form id="faucet" method="post">
  <label for="a">Enter your ' . $currency .' address</label>
  <input type="text" name="a" id="a" maxlength="' . $maxaddrlength . '" size="' . $maxaddrlength . '" pattern="' . $pattern . '">
  ' . recaptcha_get_html($publickey, $error) . '
  <input type="submit" value="Get coins">
 </form>';

echo $page . $footer;
echo "<br><br><br>";
echo '<a href="https://ititch.com/billing/aff.php?aff=011"><img src="https://ititch.com/wp-content/uploads/2012/09/cropped-blackbanner.jpg" width="120" height="60" border="0"></a>';
echo "<br><font size=\"2\">Based on namecoin <a href=\"https://github.com/John-Kenney/testnet-faucet\">testnet-faucet</a> by John Kenney.</font>";
echo "</body></html>";

?>
