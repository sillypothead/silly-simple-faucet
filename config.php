<?php


$currency = 'CannaCoin';  // name of currency
$homepage = 'http://cannacoin.cc'; // homepage of the currency
$coin     = 'CCN';             // coin ticker symbol
$addressprefix = '[C]';        // first char of address (regex)
$addresslength = '33';           // length in chars -1 (regex)
$addressversion = '1C';          // hex address prefix, upper case
$maxaddrlength = '34';           // maximum address length
$donations = 'CVxjvtWAXD4gcU1CjJfp4PyCw2ULeDVL7r';

$period = 3600;                  // payout period in seconds
$payusd = 0.001;                 // payout in USD value ex: $0.001 worth of currency.
$minpay = 0.00;                  // minimum payment
$minbal = 1.00;                  // minimum balance (must cover fee)

$rpcuser = '';        // json-rpc login details
$rpcpass = '';
$rpchost = '';
$rpcport = '';

$walletpass = '';                // wallet passphrase

$mysqluser = '';           // mysql login details
$mysqlpass = '';
$mysqlhost = '';
$mysqldb   = '';

//Free public price ticker apis
$ticker_url     =  'https://bittrex.com/api/v1.1/public/getticker?market=BTC-'. $coin;
$btc_ticker_url =  'https://www.bitstamp.net/api/ticker/';

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey  = '';
$privatekey = '';

//IP of the server
$REMOTE_ADDR = '';

//top banner ad
$ads1 = "";
//bottom banner ad
$ads2 = '';

//Side links for referrals , ads or information sites
$links = '
Referrals:
<table border="0">
 <tr>
 <td align="left">
 <br><b><a href="http://cur.lv/tpso8">Free Bitcoins</a></b>
 <br><b><a href="http://cur.lv/tpsji">Bitvisitor</a></b>
 <br><b><a href="http://cur.lv/tpstq">BitcoinZebra</a></b>
 <br><b><a href="http://cur.lv/tpu12">Annonymous Ads</a></b>
 <br><b><a href="https://coinurl.com/index.php?ref=29f9ca27f102db636b527764f449d8bd">CoinURL</a></b>
 </td>
 </tr>
</table>';

?>
