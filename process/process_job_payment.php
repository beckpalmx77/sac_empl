<?php
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
include('../config/connect_db.php');
include('../config/lang.php');

$year = date("Y");
$month_current = date("m");
$date = date("d");

$id = $_POST["id"];

$sql_month_total = "SELECT * FROM v_job_payment_month_total WHERE id = :id";
$statement = $conn->prepare($sql_month_total);
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$results_total = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results_total as $result_total) {
    $effect_month = $result_total['effect_month'];
    $effect_year = $result_total['effect_year'];
}

$txt .= " effect m - y " . $effect_month . " | " . $effect_year . "\n\r";

$date_str = "$year-$month_current-$date";
$previous_date = date('Y-m-d', strtotime("$date_str -1 months"));

$sql_month_loop = "SELECT * FROM ims_month";
$statement = $conn->query($sql_month_loop);
$results_month = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results_month as $result_month) {

    $month = $result_month['month_id'];
    $month_name = $result_month['month_name'];
    $sql_find = "SELECT job_date, COUNT(*) AS Record FROM job_transaction 
                 WHERE grade_point IN ('A','B','C') AND effect_month = :effect_month AND effect_year = :effect_year GROUP BY job_date";
    $statement = $conn->prepare($sql_find);
    $statement->bindParam(':effect_month', $effect_month, PDO::PARAM_STR);
    $statement->bindParam(':effect_year', $effect_year, PDO::PARAM_STR);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    $txt .= "sql_find = " . $sql_find . "\n\r";

    foreach ($results as $result) {
        $sql = "UPDATE job_payment_daily_total SET total_job_emp = :total_job_emp WHERE job_date = :job_date";
        $query = $conn->prepare($sql);
        $query->bindParam(':total_job_emp', $result['Record'], PDO::PARAM_INT);
        $query->bindParam(':job_date', $result['job_date'], PDO::PARAM_STR);
        $query->execute();

        //echo $month . " A " . $result['Record'] . " | " . $result['job_date'] . "\n\r";

        $txt .= "sql = " . $sql . "\n\r";
    }

    $sql_find2 = "SELECT job_date, SUM(percent) AS percent FROM v_job_transaction 
                  WHERE effect_month = :effect_month AND effect_year = :effect_year GROUP BY job_date";
    $statement = $conn->prepare($sql_find2);
    $statement->bindParam(':effect_month', $effect_month, PDO::PARAM_STR);
    $statement->bindParam(':effect_year', $effect_year, PDO::PARAM_STR);
    $statement->execute();
    $results2 = $statement->fetchAll(PDO::FETCH_ASSOC);

    $txt .= "sql_find = " . $sql_find . "\n\r";

    foreach ($results2 as $result2) {
        $sql2 = "UPDATE job_payment_daily_total SET total_grade_point = :total_grade_point WHERE job_date = :job_date";
        $query = $conn->prepare($sql2);
        $query->bindParam(':total_grade_point', $result2['percent'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result2['job_date'], PDO::PARAM_STR);
        $query->execute();

        //echo $month . " B " . $result2['percent'] . " | " . $result2['job_date'] . "\n\r";

        $txt .= "sql2 = " . $sql2 . "\n\r";

    }

    $sql_find3 = "SELECT effect_month, effect_year, SUM(total_tires) AS total_tires FROM job_payment_daily_total
                  WHERE effect_month = :effect_month AND effect_year = :effect_year GROUP BY effect_month, effect_year";
    $statement = $conn->prepare($sql_find3);
    $statement->bindParam(':effect_month', $effect_month, PDO::PARAM_STR);
    $statement->bindParam(':effect_year', $effect_year, PDO::PARAM_STR);
    $statement->execute();
    $results3 = $statement->fetchAll(PDO::FETCH_ASSOC);

    $txt .= "sql_find3 = " . $sql_find3 . "\n\r";

    foreach ($results3 as $result3) {
        $sql3 = "UPDATE job_payment_month_total SET total_tires = :total_tires 
                 WHERE effect_month = :effect_month AND effect_year = :effect_year";
        $query = $conn->prepare($sql3);
        $query->bindParam(':total_tires', $result3['total_tires'], PDO::PARAM_INT);
        $query->bindParam(':effect_month', $result3['effect_month'], PDO::PARAM_STR);
        $query->bindParam(':effect_year', $result3['effect_year'], PDO::PARAM_STR);
        $query->execute();

        //echo $month . " C " . $result3['total_tires'] . " | " . $result3['effect_month'] . " | " . $result3['effect_year'] . "\n\r";

        $txt .= "sql3 = " . $sql3 . "\n\r";

    }

    $sql_find4 = "SELECT job_date, emp_id, effect_month, effect_year, percent FROM v_job_transaction
                  WHERE effect_month = :month AND effect_year = :year";
    $statement = $conn->prepare($sql_find4);
    $statement->bindParam(':month', $month, PDO::PARAM_STR);
    $statement->bindParam(':year', $effect_year, PDO::PARAM_STR);
    $statement->execute();
    $results4 = $statement->fetchAll(PDO::FETCH_ASSOC);

    $txt .= "sql_find4 = " . $sql_find4 . "\n\r";

    foreach ($results4 as $result4) {
        $sql_find_daily = "SELECT * FROM job_payment_daily_total 
                           WHERE job_date = :job_date AND effect_month = :month AND effect_year = :year";
        $statement_daily = $conn->prepare($sql_find_daily);
        $statement_daily->bindParam(':job_date', $result4['job_date'], PDO::PARAM_STR);
        $statement_daily->bindParam(':month', $month, PDO::PARAM_STR);
        $statement_daily->bindParam(':year', $effect_year, PDO::PARAM_STR);
        $statement_daily->execute();
        $results_daily = $statement_daily->fetchAll(PDO::FETCH_ASSOC);

        $txt .= "sql_find_daily = " . $sql_find_daily . "\n\r";

        foreach ($results_daily as $result_daily) {
            if (!empty($result4['percent']) && !empty($result_daily['total_grade_point']) && $result_daily['total_grade_point'] > 0) {
                $total_percent_payment = ($result4['percent'] / $result_daily['total_grade_point']) * 100;
                $total_percent_payment = round($total_percent_payment, 2);
                //echo $month . " D " . $total_percent_payment . "\n\r";
            } else {
                $total_percent_payment = '0';
            }
        }

        $sql4 = "UPDATE job_transaction SET total_grade_point = :total_grade_point, total_percent_payment = :total_percent_payment 
                 WHERE emp_id = :emp_id AND job_date = :job_date";
        $query = $conn->prepare($sql4);
        $query->bindParam(':total_grade_point', $result4['percent'], PDO::PARAM_STR);
        $query->bindParam(':total_percent_payment', $total_percent_payment, PDO::PARAM_STR);
        $query->bindParam(':emp_id', $result4['emp_id'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result4['job_date'], PDO::PARAM_STR);
        $query->execute();
        $txt .= "sql4 = " . $sql4 . "\n\r";
    }
}

/*
$my_file = fopen("process-cal-total.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);
*/

echo $process_success;
