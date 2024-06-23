<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');
include('../util/reorder_record.php');

if ($_POST["action"] === 'GET_JOB_DATA') {

    $job_date = $_POST["job_date"];

    $return_arr = array();

    $sql_get = "SELECT * FROM job_payment_daily_total  jd
            WHERE jd.job_date = " . $job_date;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_date" => $result['job_date'],
            "effect_month" => $result['effect_month'],
            "effect_year" => $result['effect_year'],
            "total_job_emp" => $result['total_job_emp'],
            "total_tires" => $result['total_tires'],
            "total_grade_point" => $result['total_grade_point'],
            "total_percent_payment" => $result['total_percent_payment'],
            "total_money" => $result['total_money']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'GET_JOB_TRANS_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM v_job_transaction  vjtrans
            WHERE vjtrans.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_date" => $result['job_date'],
            "emp_id" => $result['emp_id'],
            "f_name" => $result['f_name'],
            "effect_month" => $result['effect_month'],
            "effect_year" => $result['effect_year'],
            "grade_point" => $result['grade_point'],
            "total_grade_point" => $result['total_grade_point'],
            "total_percent_payment" => $result['total_percent_payment'],
            "total_money" => $result['total_money'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action_detail"] === 'UPDATE') {

    $id = $_POST["detail_id"];
    $effect_month = $_POST["effect_month"];
    $effect_year = $_POST["effect_year"];

    $grade_point = strtoupper($_POST["grade_point"]);
    $sql_update = "UPDATE job_transaction SET grade_point=:grade_point              
            WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':grade_point', $grade_point, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();

    echo $save_success;

/*
    include('../config/connect_db.php');

    $year = date("Y");
    $month = date("m");
    $date = date("d");
*/

    $month = $_POST["effect_month"];
    $year = $_POST["effect_year"];

    $sql_find = "SELECT job_date , COUNT(*) AS Record FROM job_transaction 
             WHERE grade_point in ('A','B','C') AND effect_month = '" . $month . "' AND effect_year = '" . $year . "' GROUP BY job_date ";
    $statement = $conn->query($sql_find);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        //echo $sql_find;
        $sql = "UPDATE job_payment_daily_total SET total_job_emp =:total_job_emp WHERE job_date = :job_date";
        $query = $conn->prepare($sql);
        $query->bindParam(':total_job_emp', $result['Record'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result['job_date'], PDO::PARAM_STR);
        $query->execute();

    }

    $sql_find2 = "SELECT job_date , sum(percent) AS percent FROM v_job_transaction WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' GROUP BY job_date ";
//echo "Update2 = " . $sql_find2;
    $statement = $conn->query($sql_find2);
    $results2 = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results2 as $result2) {
        //echo $sql_find2;
        $sql2 = "UPDATE job_payment_daily_total SET total_grade_point =:total_grade_point WHERE job_date = :job_date";
        $query = $conn->prepare($sql2);
        $query->bindParam(':total_grade_point', $result2['percent'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result2['job_date'], PDO::PARAM_STR);
        $query->execute();
    }

    $sql_find3 = "select effect_month,effect_year,sum(total_tires) as total_tires from job_payment_daily_total
              WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'  
              GROUP BY effect_month , effect_year";
//echo "Update3 = " . $sql_find3;
    $statement = $conn->query($sql_find3);
    $results3 = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results3 as $result3) {
        //echo $sql_find3 . "\n\r";
        $sql3 = "UPDATE job_payment_month_total SET total_tires =:total_tires WHERE effect_month = :effect_month AND effect_year = :effect_year ";
        $query = $conn->prepare($sql3);
        $query->bindParam(':total_tires', $result3['total_tires'], PDO::PARAM_STR);
        $query->bindParam(':effect_month', $result3['effect_month'], PDO::PARAM_STR);
        $query->bindParam(':effect_year', $result3['effect_year'], PDO::PARAM_STR);
        $query->execute();
    }


    $sql_find4 = "select job_date,emp_id,effect_month,effect_year,percent from v_job_transaction
              WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "'";
//echo "Update4 = " . $sql_find4 . "\n\r" ;
    $statement = $conn->query($sql_find4);
    $results4 = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results4 as $result4) {

        $sql_find_daily = "select * from job_payment_daily_total
              WHERE job_date = '" . $result4['job_date'] . "' AND effect_month = '" . $month . "' AND effect_year = '" . $year . "'";
        //echo "Update_daily = " . $sql_find_daily . "\n\r" ;
        $statement = $conn->query($sql_find_daily);
        $results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results_daily as $result_daily) {

            //echo "data = " . $result4['emp_id'] . " | " . $result4['job_date'] . " | " . $result4['percent'] . " | " . $result_daily['total_grade_point'] . "\n\r";

            if ($result4['percent'] !== null && $result4['percent'] !== 0 && $result4['percent'] !== '0' && $result4['percent'] !== '-'
                && $result_daily['total_grade_point'] !== null && $result_daily['total_grade_point'] !== 0 && $result_daily['total_grade_point'] !== '0' && $result_daily['total_grade_point'] !== '-'
                && $result_daily['total_grade_point'] > 0) {

                $total_percent_payment = ($result4['percent'] / $result_daily['total_grade_point']) * 100;
                $total_percent_payment = round($total_percent_payment, 2);

            } else {
                $total_percent_payment = '0';
            }

        }


        $sql4 = "UPDATE job_transaction SET total_grade_point =:total_grade_point , total_percent_payment =:total_percent_payment WHERE emp_id = :emp_id AND job_date = :job_date ";
        //echo $sql4 . "\n\r";
        $query = $conn->prepare($sql4);
        $query->bindParam(':total_grade_point', $result4['percent'], PDO::PARAM_STR);
        $query->bindParam(':total_percent_payment', $total_percent_payment, PDO::PARAM_STR);
        $query->bindParam(':emp_id', $result4['emp_id'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result4['job_date'], PDO::PARAM_STR);
        $query->execute();

    }

    $sql_find_month = "SELECT * FROM job_payment_month_total WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' ORDER BY id ";
//echo "Update2 = " . $sql_find2;
    $statement = $conn->query($sql_find_month);
    $results_month = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_month as $result_month) {
        $effect_month = $result_month['effect_month'];
        $effect_year = $result_month['effect_year'];
        $total_tires = $result_month['total_tires'];
        $total_money = $result_month['total_money'];
    }

//echo "Data Month = " . $total_tires . " | " . $total_money . "\n\r";


    $sql_find_daily = "SELECT * FROM job_payment_daily_total WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' GROUP BY job_date ";
//echo "Update2 = " . $sql_find2;
    $statement = $conn->query($sql_find_daily);
    $results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_daily as $result_daily) {

        if ($result_daily['total_tires'] !== null && $result_daily['total_tires'] !== 0 && $result_daily['total_tires'] !== '0' && $result_daily['total_tires'] !== '-'
            && $total_tires !== null && $total_tires !== 0 && $total_tires !== '0' && $total_tires !== '-') {

            $total_percent_payment = ($result_daily['total_tires'] / $total_tires) * 100;
            $total_percent_payment_round = round($total_percent_payment, 2);

        }

        if ($total_percent_payment !== null && $total_percent_payment !== 0 && $total_percent_payment !== '0' && $total_percent_payment !== '-'
            && $total_money !== null && $total_money !== 0 && $total_money !== '0' && $total_money !== '-' && $result_daily['total_grade_point'] > 0) {

            //echo "XXX = " . $result_daily['job_date'] . " | " . $total_percent_payment . " | " . $total_money . "\n\r";

            $total_pay_money = ($total_percent_payment / 100) * $total_money;
            $total_pay_money = round($total_pay_money, 2);

            //echo "XXX = " . $result_daily['job_date'] . " | " . $total_percent_payment . " | " . $total_money . " = " . $total_pay_money . "\n\r";

        } else {
            $total_percent_payment_round = '0';
            $total_pay_money = '0';
        }

        $sql_up_daily = "UPDATE job_payment_daily_total SET total_percent_payment =:total_percent_payment ,total_money=:total_money
    WHERE job_date = :job_date ";
        $query = $conn->prepare($sql_up_daily);
        $query->bindParam(':total_percent_payment', $total_percent_payment_round, PDO::PARAM_STR);
        $query->bindParam(':total_money', $total_pay_money, PDO::PARAM_STR);
        $query->bindParam(':job_date', $result_daily['job_date'], PDO::PARAM_STR);
        $query->execute();

    }


    $sql_find_trans = "SELECT * FROM job_transaction WHERE effect_month = '" . $month . "' AND effect_year = '" . $year . "' ORDER BY job_date,emp_id ";

    $statement = $conn->query($sql_find_trans);
    $results_trans = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_trans as $result_trans) {

        $sql_find_daily = "select * from job_payment_daily_total
              WHERE job_date = '" . $result_trans['job_date'] . "' AND effect_month = '" . $month . "' AND effect_year = '" . $year . "'";

        $statement = $conn->query($sql_find_daily);
        $results_daily = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results_daily as $result_daily) {

            if (($result_daily['total_money'] !== null && $result_daily['total_money'] !== 0
                    && $result_daily['total_money'] !== '0' && $result_daily['total_money'] !== '-'
                    && $result_trans['total_percent_payment'] !== null && $result_trans['total_percent_payment'] !== 0
                    && $result_trans['total_percent_payment'] !== '0' && $result_trans['total_percent_payment'] !== '-') && $result_trans['total_grade_point'] > 0) {

                $total_money_payment = ($result_daily['total_money'] * $result_trans['total_percent_payment'] / 100);
                $total_money_payment_round = number_format($total_money_payment, 2);
                //$total_money_payment_round = round($total_money_payment, 2);
            } else {
                $total_money_payment = '0';
                $total_money_payment_round = '0';
            }

        }

        /*
            echo "YYY = " . $result_trans['job_date'] . " | " . $result_trans['emp_id'] . " | " . $result_daily['total_money']
                . " | " . $result_trans['total_percent_payment'] . " | " . $total_money_payment . " = " . $total_money_payment_round . "\n\r";
        */

        //echo "total_money_payment = " . number_format($total_money_payment, 2) . "\n";
/*
        $myfile = fopen("job-getdata.txt", "w") or die("Unable to open file!");
        fwrite($myfile, "id = " . $id . " month = " . $effect_month . " month = " . $effect_year . " SQL = " . $sql_find_trans);
        fclose($myfile);
*/

        $sql_up_trans = "UPDATE job_transaction SET total_money=:total_money
    WHERE emp_id =:emp_id AND job_date = :job_date ";
        $query = $conn->prepare($sql_up_trans);
        $query->bindParam(':total_money', $total_money_payment_round, PDO::PARAM_STR);
        $query->bindParam(':emp_id', $result_trans['emp_id'], PDO::PARAM_STR);
        $query->bindParam(':job_date', $result_trans['job_date'], PDO::PARAM_STR);
        $query->execute();

    }



}

if ($_POST["action"] === 'GET_JOB_DETAIL') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value
    $searchArray = array();

    $job_date = $_POST['job_date'];

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (grade_point LIKE :grade_point or
        f_name LIKE :f_name ) ";
        $searchArray = array(
            'grade_point' => "%$searchValue%",
            'f_name' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $sql_get_all = "SELECT COUNT(*) AS allcount FROM v_job_transaction WHERE job_date = '" . $job_date . "'";
    $stmt = $conn->prepare($sql_get_all);
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

/*
    $myfile = fopen("job-getdata.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_all . " Record = " . $totalRecords);
    fclose($myfile);
*/


## Total number of records with filtering
    $sql_get_filter = "SELECT COUNT(*) AS allcount FROM v_job_transaction WHERE job_date = '" . $job_date . "' " . $searchQuery ;
    $stmt = $conn->prepare($sql_get_filter);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

/*
    $myfile = fopen("job-getdata_2.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_filter . " Filter Record = " . $totalRecordwithFilter);
    fclose($myfile);
*/

## Fetch records
    $sql_get_load = "SELECT * FROM v_job_transaction WHERE job_date = '" . $job_date . "' " . $searchQuery
        . " ORDER BY id " . " LIMIT :limit,:offset";

    $stmt = $conn->prepare($sql_get_load);

/*
    $myfile = fopen("job-getdata_3.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_get_load . " Row Record = " . $row . " Row Record Per Page = " . $rowperpage);
    fclose($myfile);
*/

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    $line_no = 0;

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $line_no++;
            $data[] = array(
                "id" => $row['id'],
                "line_no" => $line_no,
                "job_date" => $row['job_date'],
                "emp_id" => $row['emp_id'],
                "f_name" => $row['f_name'],
                "grade_point" => $row['grade_point'],
                "total_grade_point" => $row['total_grade_point'],
                "total_percent_payment" => $row['total_percent_payment'],
                "total_money" => $row['total_money'],
                "effect_month" => $row['effect_month'],
                "effect_year" => $row['effect_year'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "grade_point" => $row['grade_point'],
                "total_grade_point" => $row['total_grade_point'],
                "select" => "<button type='button' name='select' id='" . $row['grade_point'] . "@" . $row['total_grade_point'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
</button>",
            );
        }

    }

## Response Return Value
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    echo json_encode($response);
}

