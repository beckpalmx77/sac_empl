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

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["l_name"] !== '') {

        $emp_id = $_POST["emp_id"];
        $sql_find = "SELECT * FROM memployee WHERE emp_id = '" . $emp_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["f_name"] !== '' && $_POST["emp_id"] !== '') {
        //$emp_id = $dept_id . "-" . substr($f_name, 6) . "-" . sprintf('%04s', LAST_ID($conn, "memployee", 'id'));
        $emp_id = $_POST["emp_id"];
        $f_name = $_POST["f_name"];
        $l_name = $_POST["l_name"];
        $dept_id = $_POST["dept_id"];
        $work_time_id = $_POST["work_time_id"];
        $remark = $_POST["remark"];
        $sex = $_POST["sex"];
        $prefix = $_POST["prefix"];
        $nick_name = $_POST["nick_name"];
        $position = $_POST["position"];
        $start_work_date = $_POST["start_work_date"];

        $sql_find = "SELECT * FROM memployee WHERE emp_id = '" . $emp_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO memployee (emp_id,f_name,l_name,work_time_id,dept_id,remark,email_address,sex,prefix,nick_name,position,start_work_date) 
                    VALUES (:emp_id,:f_name,:l_name,:work_time_id,:dept_id,:remark,:email_address,:sex,:prefix,:nick_name,:position,:start_work_date)";

            $query = $conn->prepare($sql);
            $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
            $query->bindParam(':f_name', $f_name, PDO::PARAM_STR);
            $query->bindParam(':l_name', $l_name, PDO::PARAM_STR);
            $query->bindParam(':work_time_id', $work_time_id, PDO::PARAM_STR);
            $query->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
            $query->bindParam(':remark', $remark, PDO::PARAM_STR);
            $query->bindParam(':email_address', $email, PDO::PARAM_STR);
            $query->bindParam(':sex', $sex, PDO::PARAM_STR);
            $query->bindParam(':prefix', $prefix, PDO::PARAM_STR);
            $query->bindParam(':nick_name', $nick_name, PDO::PARAM_STR);
            $query->bindParam(':position', $position, PDO::PARAM_STR);
            $query->bindParam(':start_work_date', $start_work_date, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();
            if ($lastInsertId) {
                $sql_user = "INSERT INTO ims_user (emp_id,user_id,first_name,last_name,password,department_id,account_type,picture,company,email) 
                    VALUES (:emp_id,:user_id,:first_name,:last_name,:password,:dept_id,:account_type,:user_picture,:company,:email)";
                $query_user = $conn->prepare($sql_user);
                $query_user->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
                $query_user->bindParam(':user_id', $emp_id, PDO::PARAM_STR);
                $query_user->bindParam(':first_name', $f_name, PDO::PARAM_STR);
                $query_user->bindParam(':last_name', $l_name, PDO::PARAM_STR);
                $query_user->bindParam(':password', $user_password, PDO::PARAM_STR);
                $query_user->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
                $query_user->bindParam(':account_type', $account_type_default, PDO::PARAM_STR);
                $query_user->bindParam(':user_picture', $user_picture, PDO::PARAM_STR);
                $query_user->bindParam(':company', $company, PDO::PARAM_STR);
                $query_user->bindParam(':email', $email, PDO::PARAM_STR);
                $query_user->execute();
                $lastInsertUser = $conn->lastInsertId();
                if ($lastInsertUser) {
                    Reorder_Record($conn, "ims_user");
                    echo $save_success;
                }
            } else {
                echo $error;
            }
        }
    }
}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["emp_id"] != '') {

        $id = $_POST["id"];
        $emp_id = $_POST["emp_id"];
        $f_name = $_POST["f_name"];
        $l_name = $_POST["l_name"];
        $dept_id = $_POST["dept_id"];
        $work_time_id = $_POST["work_time_id"];
        $remark = $_POST["remark"];
        $sex = $_POST["sex"];
        $prefix = $_POST["prefix"];
        $nick_name = $_POST["nick_name"];
        $position = $_POST["position"];
        $week_holiday = $_POST["week_holiday"];

        $sql_find = "SELECT * FROM memployee WHERE emp_id = '" . $emp_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            $sql_update = "UPDATE memployee SET week_holiday=:week_holiday              
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':week_holiday', $week_holiday, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();

            $sql_user = "UPDATE ims_user SET first_name=:f_name,last_name=:l_name,department_id=:dept_id       
            WHERE emp_id = :emp_id";
            $query_user = $conn->prepare($sql_user);
            $query_user->bindParam(':f_name', $f_name, PDO::PARAM_STR);
            $query_user->bindParam(':l_name', $l_name, PDO::PARAM_STR);
            $query_user->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
            $query_user->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
            $query_user->execute();

            echo $save_success;
            /*
                        $txt = $id . " | " . $emp_id . " | " . $week_holiday . " | " . $save_success;
                        $my_file = fopen("holiday_a.txt", "w") or die("Unable to open file!");
                        fwrite($my_file, $txt);
                        fclose($my_file);
            */

        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM memployee WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM memployee WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action_detail"] === 'UPDATE') {

    $id = $_POST["detail_id"];
    $grade_point = strtoupper($_POST["grade_point"]);
    $sql_update = "UPDATE job_transaction SET grade_point=:grade_point              
            WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':grade_point', $grade_point, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    echo $save_success;

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

