<?php

include('config.php');

function print_stats() {
  global $mysqlhost, $mysqluser, $mysqlpass, $mysqldb;
  $mysqli = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
  $page = '<hr align="left" width="200">';
  /* check connection */
  if (mysqli_connect_errno()) {
    $page .= "Connect failed:" . mysqli.connect_error() . "\n";
    return $page;
  }

  //Total dispense
  $page .= "Total Dispenses: ";
  $query = "SELECT * FROM transactions";
  if ($result = $mysqli->query($query)) {

    /* determine number of rows result set */
    $row_cnt = $result->num_rows;

    $page .= $row_cnt;
  }

  $page .= "<br>";
  $page .= "Unique Addresses: ";
  //Unique Addresses
  $query = "SELECT COUNT(DISTINCT address) FROM transactions";
  if ($result = $mysqli->query($query)) {
    $unique = mysqli_fetch_array($result);
    $page .= $unique[0];
  }

  $page .= "<br>";
  $page .= "Avg. Dispense: ";
  //Unique Addresses
  $query = "SELECT AVG(amount) FROM transactions";
  if ($result = $mysqli->query($query)) {
    $unique = mysqli_fetch_array($result);
    $page .= bcdiv($unique[0],'100000000',8);
  }

  $page .= "<br>";
  $page .= "Dispenses Per Day: ";
  //Average Dispense Per Day
  $query = "SELECT MAX(time) AS last, MIN(time) AS first FROM transactions";
  if ($result = $mysqli->query($query)) {
    $dates = mysqli_fetch_array($result);
    $time = ($dates[0] - $dates[1]) / (60 * 60 * 24);
    $adpd = $row_cnt / $time;
    $page .=  number_format($adpd, 2);
  }

/* close connection */
$result->close();
$mysqli->close();

$page .= '<hr align="left" width="200">';

return $page;
}
?>
