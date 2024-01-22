<?php
include('../config/connect_db.php');

$temp =  $_GET["temp"];

$currentDate = date('Y-m-d H:i:s');

$status = 'N';

$sql = "INSERT INTO temperature_data(date_time,temperature_c,status) 
            VALUES (:date_time,:temperature_c,:status)";
$query = $conn->prepare($sql);
$query->bindParam(':date_time', $currentDate, PDO::PARAM_STR);
$query->bindParam(':temperature_c', $temp, PDO::PARAM_STR);
$query->bindParam(':status', $status, PDO::PARAM_STR);
$query->execute();
$lastInsertId = $conn->lastInsertId();

if ($lastInsertId) {

    //$res = notify_message($str, $token);

    echo 1;
} else {
    echo 0;
}