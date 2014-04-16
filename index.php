<?php

include('config.php');
include('functions.php');

$page = '<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8"/>
 <title>' . $currency . ' Faucet</title>
</head>
<body>';
$footer = '
 <p>Donate to: ' . $donations . '</p>
</body>
</html>';

// check if we have paid a request from this ip
$wait = checkip();
switch ($wait) {
   case $wait > 90: die($page . '<p>Please wait ' . round($wait/60) . ' minutes.</p>' . $footer); break;
   case $wait > 1: die($page . '<p>Please wait ' . $wait . ' seconds.</p>' . $footer); break;
}

// check if an address has been submitted, then if it's valid & if we've already paid it
if (isset($_POST['a'])) {
   $address = trim($_POST['a']);
   $test = test_address($address);
   switch ($test) {
      case 0:
         $pay = payout($address);
         if (is_array($pay))
            die ($page. '<p>Paid ' . $pay['amount'] . ' to ' . $address . ' in transaction id ' . $pay['tid'] . '</p>' . $footer);
         die ($page . '<p>Faucet is dry, please donate!<p>' . $footer);
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
  <input type="submit" value="Get coins">
 </form>';

echo $page . $footer;

?>
