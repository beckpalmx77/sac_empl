<?php
include('../config/connect_db.php');

$year = date("Y");
$month = date("m");

$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$sql_find = "SELECT * FROM job_payment_month_total WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'";

$nRows = $conn->query($sql_find)->fetchColumn();
if ($nRows <= 0) {

    $effect_start_date = "01-" . $month . "-" . $year;
    $effect_to_date = $day . "-" . $month . "-" . $year;
    echo $effect_start_date . " | " .  $effect_to_date;

    $sql = "INSERT INTO job_payment_month_total(effect_start_date,effect_to_date,effect_month,effect_year) 
                    VALUES (:effect_start_date,:effect_to_date,:effect_month,:effect_year)";
    $query = $conn->prepare($sql);
    $query->bindParam(':effect_start_date', $effect_start_date, PDO::PARAM_STR);
    $query->bindParam(':effect_to_date', $effect_to_date, PDO::PARAM_STR);
    $query->bindParam(':effect_month', $month, PDO::PARAM_STR);
    $query->bindParam(':effect_year', $year, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $conn->lastInsertId();

    if ($lastInsertId) {
        echo "OK";
    } else {
        echo "Error";
    }

}

