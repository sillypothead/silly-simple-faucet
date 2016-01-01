<?php

include('config.php');
include('functions.php');
include('recaptchalib.php');

$page = '<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8"/>
 <title>' . $currency . ' Faucet</title>
</head>
<body>
<center>';

//ad
$page .= "<table border=1 cellspacing=1 cellpadding=3>";
$page .= " <td colspan=3>" . $ads1 . "</td>";

$content1 = file_get_contents($ticker_url);
$content2 = file_get_contents($btc_ticker_url);
$data1=json_decode($content1, true);
$data2=json_decode($content2, true);
$ccnbtc = number_format($data1['result']['Last'], 8);
$btcusd = number_format($data2['last'], 2);
$ccnusd = number_format(($ccnbtc * $btcusd), 8);

//algorithm for payout.
$payamt = number_format((($payusd / $btcusd) / $ccnbtc), 8);

$page .= "  <tr><td colspan=3 align=center>";
$page .= $coin . '=<b>' . $ccnbtc . '</b> BTC | ' . ' <font color="green">$' . $ccnusd .'</font> USD | BTC=<font color="green">$' . $btcusd . '</font>';
$page .= "  </td><tr>";
$page .= '<tr><td colspan=3 align=center>';
$page .= ' <b>Current Payout: ' . $payamt . ' ' . $coin . '</b><br>';
$page .= 'Donations: ' . $donations . '</td></tr>';

$page .= '<tr><td align=center>';
$page .= '<hr width=200>Get your <a href="' . $homepage . '">' . $currency . ' Wallet</a><hr width=200>';
$page .= '
 <form id="faucet" method="post">
  <label for="a">Enter your <b>' . $currency .'</b> address:</label>
  <br>
  <input type="text" name="a" id="a" maxlength="' . $maxaddrlength . '" size="' . $maxaddrlength . '" pattern="' . $pattern . '">
  ' . recaptcha_get_html($publickey, $error) . '
  <input type="submit" value="Get coins" style="height:50px; width:200px" >
 </form>';
$page .= '<br>' . $ads2 . '</td>';
$page .= '<td>' . $links . '</td></tr>';

$res = send_json_request('getbalance');
$stats = print_stats();
$page .= '<tr><td colspan=3>Server Balance: ' . number_format($res['result'],8) . ' <br>' . $stats . '</td>';


$footer = '<a href="/">Home</a> | <a href="javascript: history.go(-1)">Go Back</a>';
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
           $pay = payout($address, $payamt);
           if (is_array($pay))
              die ('<center><p>Paid ' . $pay['amount'] . ' to ' . $address . ' in transaction id ' . $pay['tid'] . '</p>' . $page . $footer);
           die ('<center><p>Faucet is dry, less than ' . $minbal . ' ' . $coin . ', please donate!<p>' . $page . $footer);
    	  } else {
               $error = $resp->error;
               die("<center><p>Captcha Challenge Failed<br><a href=\"javascript:history.back()\">Back</a>" . $page . $footer);
  	  }
          break;
      case $test < 0:  die('<center><p>Invalid ' . $currency . ' address, please try again. ' . $test . '</p>' . $page . $footer); break;
      case $test > 99: die('<center><p>Please wait ' . round($test/60) . ' minutes.</p>' . $page . $footer); break;
      case $test > 1: die('<center><p>Please wait ' . $test . ' seconds.</p>' . $page . $footer); break;
      case 1: die('<center><p>Please wait one second.</p>' . $page . $footer); break;
   }
}

$page .= '</table>';

echo $page . $footer;
echo "<br>";
echo "<br><font size=\"2\">Source: <a href=\"https://github.com/sillypothead/silly-simple-faucet\">Git-Hub</a></font>";
echo "<br><font size=\"2\">Based on namecoin <a href=\"https://github.com/John-Kenney/testnet-faucet\">testnet-faucet</a></font>";
echo "</body></html>";

?>
