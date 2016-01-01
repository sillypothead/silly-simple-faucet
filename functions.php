<?php

$base58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
$hex = '0123456789ABCDEF';
$pattern = '^' . $addressprefix . '{1}[' . $base58 . ']{'. $addresslength . '}';
$rpcurl = 'http://' . $rpcuser . ':' . $rpcpass . '@' . $rpchost . ':' . $rpcport;
$ip = $_SERVER['REMOTE_ADDR'];

$link = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass,$mysqldb);
if (mysqli_connect_errno())
   die('database connection failed');

function checkip() {
   global $ip,$link,$period;
   $curtime = time();
   $time = time() - $period;
   if ($stmt = mysqli_prepare($link, 'SELECT time FROM transactions WHERE time > ? AND ip = ? ORDER BY time DESC LIMIT ?;')) {
      $limit = 1;
      mysqli_stmt_bind_param($stmt, 'isi',$time,$ip,$limit);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt,$lastreq);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      if ($lastreq > 1)
         return $period - ($curtime - $lastreq);
   }
   return 'false';
};

function dec2base($dec,$chars,$base) {
   $out = '';
   while (bccomp($dec,0) == 1) {
      $mod = bcmod($dec,$base);
      $dec = bcdiv($dec,$base,0);
      $out = $chars[$mod] . $out;
   }
   return $out;
}

function base2base($in,$inchars,$outchars) {
   $inbase = strlen($inchars);
   $outbase = strlen($outchars);
   $inlen = strlen($in);
   $out = '0';
   for ($i=0;$i<$inlen;$i++) {
      $pos = strpos($inchars,$in[$i]);
      $out = bcmul($out,$inbase,0);
      $out = bcadd($out,$pos,0);
   }
   return dec2base($out,$outchars,$outbase);
}

function send_json_request($method,$params=NULL) {
   global $rpcurl;
   $request = array('method' => $method,'params' => $params);
   $request = json_encode($request);
   $ch = curl_init($rpcurl);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
   curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/json'));
   curl_setopt($ch,CURLOPT_POST,true);
   curl_setopt($ch,CURLOPT_POSTFIELDS,$request);
   $response = json_decode(curl_exec($ch),true);
   curl_close($ch);
   return $response;
}

function test_address($address) {
   global $addressversion,$base58,$donations,$hex,$link,$pattern,$period;
   // check it's a valid address
   if ($address == $donations)
      return -1;
   if (!preg_match( '/'.$pattern.'/', $address))
      return -2;
   $addrhex = base2base($address,$base58,$hex);
   if (strlen($addrhex) !== 50)
      return -3;
   if (substr($addrhex,0,2) !== $addressversion) 
      return -4;
   $check = substr($addrhex,0,42);
   $check = pack('H*' , $check);
   $check = strtoupper(hash('sha256',hash('sha256',$check,true)));
   $check = substr($check,0,8);
   if ($check !== substr($addrhex,42))
      return -5;
   $params = array(0 => $address);
   $result = send_json_request('validateaddress',$params);
   if (!$result['result']['isvalid'])
      return -6;
   if ($result['result']['ismine'])
      return -1;
   // address is ok if we got this far
   // check the database to see if we have already sent coins
   $curtime = time();
   $time = time() - $period;
   if ($stmt = mysqli_prepare($link, 'SELECT time FROM transactions WHERE time > ? AND address = ? ORDER BY time DESC LIMIT ?;')) {
      $limit = 1;
      mysqli_stmt_bind_param($stmt, 'isi',$time,$address,$limit);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt,$lastreq);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      if ($lastreq > 1)
         return $period - ($curtime - $lastreq);
   }
   if (mysqli_errno($link))
     return -7;
   // returns 0 for success, negative for error, postive for waiting time
   return 0;
}

function tosatoshi($amount) {
   return bcmul($amount,'100000000',0);
}

function fromsatoshi($amount) {
   return bcdiv($amount,'100000000',8);
}

function payout($address, $pay) {
   global $currency,$ip,$link,$minbal,$minpay,$walletpass;
   $params = array(0 => NULL, 1 => 6);
   $res = send_json_request('getbalance');
   $bal = tosatoshi($res['result']);
   if (bccomp($bal,tosatoshi($minbal),0) == -1)
      return false;

   //Updated pay with current market value with flat usd base value. 
   //$pay = bcdiv($bal,$paydiv,0);

   if (bccomp($bal,tosatoshi($minbal)-$pay,0) == -1)
      return false;
   $params = array(0 => $walletpass, 1 => 1);
   $res = send_json_request('walletpassphrase',$params);
   $params = array(0 => $address, 1 => (float)$pay);
   
   // dev
   return array('amount' => $pay, 'tid' => '0');

   $paid = send_json_request('sendtoaddress',$params);
   if ($paid === false)
      return false;
   if ($stmt = mysqli_prepare($link, 'INSERT INTO transactions (address,amount,ip,tid,time) VALUES(?,?,?,?,?);')) {
      $json = json_encode($paid);
      $time = time();
      $tid = $paid['result'];
      mysqli_stmt_bind_param($stmt, 'sissi',$address,tosatoshi($pay),$ip,$tid,$time);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
   }
   return array('amount' => $pay, 'tid' => $tid);
}

function print_stats() {
  global $mysqlhost, $mysqluser, $mysqlpass, $mysqldb, $coin;
  $mysqli = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
  /* check connection */
  if (mysqli_connect_errno()) {
    $page .= "Connect failed:" . mysqli.connect_error() . "\n";
    return $page;
  }

  //Total dispense
  $page .= "Total Dispensed: ";
  $query = "SELECT SUM(amount) AS total FROM transactions";
  if ($result = $mysqli->query($query)) {
    $total = mysqli_fetch_array($result);
    /* determine number of rows result set */
    $page .= bcdiv($total[0], '100000000', 8) . ' ' . $coin;
  }

  $page .= "<br>";
  $page .= "Unique Addresses: ";
  //Unique Addresses
  $query = "SELECT COUNT(DISTINCT address) FROM transactions";
  if ($result = $mysqli->query($query)) {
    $unique = mysqli_fetch_array($result);
    $page .= $unique[0];
  }

/* close connection */
$result->close();
$mysqli->close();

return $page;
}

?>
