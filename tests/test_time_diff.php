<?php
include('../config/connect_db.php');

$time_start = "18:00";
$time_to = "20:00";


$sql_time = "SELECT TIMEDIFF('". $time_to . "','" . $time_start ."') AS data ";
foreach ($conn->query($sql_time) AS $row) {
    $tim_diff = $row['data'];
    echo $tim_diff ;
}
