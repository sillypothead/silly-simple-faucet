<?php


$currency = 'CannaCoin';  // name of currency
$homepage = 'http://cannacoin.cc'; //homepage of the currency
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

//Links for exchange or information sites
//Can use an array for links.
$links = '
 Get a ' . $currency . ' wallet: <a href="' . $homepage . '">' . $currency . ' Wallet</a>.<br>
Help support the faucet by using any of these helpful links:
<table border="0">
 <tr>
 <td align="left">
 <ul>
 <li><b><a href="https://c-cex.com/?rf=376F1791EE6E0AAD">C-CEX</a></b>
 <li><b><a href="http://freebitco.in/?r=259805">Freebitco.in </a></b>
 <li><b><a href="https://ititch.com/billing/aff.php?aff=011">ititch.com</a>
 </ul>
 </td>
 </tr>
</table>';


?>
