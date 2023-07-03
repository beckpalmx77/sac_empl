<?php
include '../config_pg/connect_db.php';
$stmt = $conn->prepare(
    "SELECT * FROM sac_orders order by date desc limit 10");
$stmt->execute();
$orders = $stmt->fetchAll();
foreach($orders as $order)
{
        echo $order['date'] . "\n\r";
}