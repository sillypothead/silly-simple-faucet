<?php


?>$currency = 'CannaCoin';  // name of currency
$addressprefix = '[C]';        // first char of address (regex)
$addresslength = '33';           // length in chars -1 (regex)
$addressversion = '1C';          // hex address prefix, upper case
$maxaddrlength = '34';           // maximum address length
$donations = 'CVxjvtWAXD4gcU1CjJfp4PyCw2ULeDVL7r';

$period = 7200;                  // payout period in seconds
$paydiv = 420;                   // fraction of balance paid
$minpay = 0.42;                  // minimum payment
$minbal = 4.2;                  // minimum balance (must cover fee)

$rpcuser = 'user';        // json-rpc login details
$rpcpass = 'pass';
$rpchost = 'localhost';
$rpcport = '1234';

$walletpass = '';                // wallet passphrase, if any

$mysqluser = 'user';           // mysql login details
$mysqlpass = 'pass';
$mysqlhost = 'localhost';
$mysqldb = 'faucetdb';

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "6LfNVvMSAAAAAJfp8mFOxz4tDC-Zk5mTEB3ubfW4";
$privatekey = "6LfNVvMSAAAAACvW6cmarzBmfOXPa92Ohv3c6qZV";

$links = '
  Don\'t have a ' . $currency. ' wallet? <a href="http://hempcoin.org/">Get one</a>.<br>
  Want to trade your ' . $currency. '?
  <ul>
  <li><b>Vote to be traded on <a href="https://www.mintpal.com/voting#THC">Mintpal.com</a></b>
  <li><a href="https://www.swisscex.com/market/CCN_BTC">SwissCEX</a>
  </ul>';
?>
