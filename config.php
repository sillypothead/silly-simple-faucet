<?php

$currency = 'Namecoin Testnet';  // name of currency
$addressprefix = '[m-n]';        // first char of address (regex)
$addresslength = '33';           // length in chars -1 (regex)
$addressversion = '6F';          // hex address prefix, upper case
$maxaddrlength = '34';           // maximum address length
$donations = 'mze2dAzHLZjTLPiF87WcyZiJBVtoUNeKre';

$period = 3600;                  // payout period in seconds
$paydiv = 200;                   // fraction of balance paid
$minpay = 0.01;                  // minimum payment
$minbal = 0.01;                  // minimum balance (must cover fee)

$rpcuser = 'namecoinrpc';        // json-rpc login details
$rpcpass = '';
$rpchost = 'localhost';
$rpcport = '18336';

$walletpass = '';                // wallet passphrase

$mysqluser = 'faucet';           // mysql login details
$mysqlpass = '';
$mysqlhost = 'localhost';
$mysqldb = 'faucet';

?>
