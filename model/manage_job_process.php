<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM djob_request "
        . " WHERE djob_request.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "job_id" => $result['job_id'],
            "job_date" => $result['job_date'],
            "job_department" => $result['job_department'],
            "device_group_id" => $result['device_group_id'],
            "device_desc" => $result['device_desc'],
            "machine_no" => $result['machine_no'],
            "job_problem_detail" => $result['job_problem_detail'],
            "job_solve_detail" => $result['job_solve_detail'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["job_id"] !== '') {

        $job_id = $_POST["job_id"];
        $sql_find = "SELECT * FROM djob_request WHERE job_id = '" . $job_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["job_date"] !== '') {
        $job_department = $_POST["department"];
        $job_date = $_POST["job_date"];
        $job_id = $job_department . "-" . substr($job_date, 6) . "-" . sprintf('%04s', LAST_ID($conn, "djob_request", 'id'));
        $device_group_id = $_POST["device_group_id"];
        $device_desc = $_POST["device_desc"];
        $machine_no = $_POST["machine_no"];
        $job_problem_detail = $_POST["job_problem_detail"];
        //$status = $_POST["status"];

        $sql_find = "SELECT * FROM djob_request WHERE job_id = '" . $job_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO djob_request (job_id,job_date,job_department,device_group_id,device_desc,machine_no,job_problem_detail) 
                    VALUES (:job_id,:job_date,:job_department,:device_group_id,:device_desc,:machine_no,:job_problem_detail)";

            $query = $conn->prepare($sql);
            $query->bindParam(':job_id', $job_id, PDO::PARAM_STR);
            $query->bindParam(':job_date', $job_date, PDO::PARAM_STR);
            $query->bindParam(':job_department', $job_department, PDO::PARAM_STR);
            $query->bindParam(':device_group_id', $device_group_id, PDO::PARAM_STR);
            $query->bindParam(':device_desc', $device_desc, PDO::PARAM_STR);
            $query->bindParam(':machine_no', $machine_no, PDO::PARAM_STR);
            $query->bindParam(':job_problem_detail', $job_problem_detail, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();


            if ($lastInsertId) {
                echo $save_success;
            } else {
                echo $error;
            }
        }
    }
}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["job_id"] != '') {

        $id = $_POST["id"];
        $job_id = $_POST["job_id"];
        $job_department = $_POST["department"];
        $device_group_id = $_POST["device_group_id"];
        $device_desc = $_POST["device_desc"];
        $machine_no = $_POST["machine_no"];
        $job_problem_detail = $_POST["job_problem_detail"];
        $job_solve_detail = $_POST["job_solve_detail"];
        $status = $_POST["status"];

        $sql_find = "SELECT * FROM djob_request WHERE job_id = '" . $job_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            if ($job_department === 'IT') {
                $sql_update = "UPDATE djob_request SET job_problem_detail=:job_problem_detail,job_solve_detail=:job_solve_detail        
                               ,status=:status
                               WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':job_problem_detail', $job_problem_detail, PDO::PARAM_STR);
                $query->bindParam(':job_solve_detail', $job_solve_detail, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            } else {
                $sql_update = "UPDATE djob_request SET device_group_id=:device_group_id,device_desc=:device_desc,machine_no=:machine_no,job_problem_detail=:job_problem_detail        
            WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':device_group_id', $device_group_id, PDO::PARAM_STR);
                $query->bindParam(':device_desc', $device_desc, PDO::PARAM_STR);
                $query->bindParam(':machine_no', $machine_no, PDO::PARAM_STR);
                $query->bindParam(':job_problem_detail', $job_problem_detail, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            }
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM djob_request WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM djob_request WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_JOB') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $columnSortOrder = 'desc'; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    if ($_SESSION['account_type']!=='admin') {
        $searchQuery = " AND job_department = '" . $_SESSION['department_id'] . "'";
    } else {
        $searchQuery = " ";
    }


    if ($searchValue != '') {
        $searchQuery = " AND (job_id LIKE :job_id or
        job_date LIKE :job_date ) ";
        $searchArray = array(
            'job_id' => "%$searchValue%",
            'job_date' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM djob_request ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM djob_request WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $stmt = $conn->prepare("SELECT * FROM djob_request WHERE 1 " . $searchQuery
        . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

/*
        $txt = $searchQuery . " | " . $columnName . " | " . $columnSortOrder ;
        $my_file = fopen("job_a.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $txt);
        fclose($my_file);
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

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {

            switch ($row['status']) {
                case "N":
                    $status = "เปิดใบแจ้ง";
                    break;
                case "W":
                    $status = "กำลังดำเนินการ";
                    break;
                case "P":
                    $status = "รออะไหล่";
                    break;
                case "Y":
                    $status = "ดำเนินการเสร็จสิ้น";
                    break;
            }

            $data[] = array(
                "id" => $row['id'],
                "job_id" => $row['job_id'],
                "job_date" => $row['job_date'],
                "device_desc" => $row['device_desc'],
                "job_problem_detail" => $row['job_problem_detail'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "solve" => "<button type='button' name='solve' id='" . $row['id'] . "' class='btn btn-success btn-xs solve' data-toggle='tooltip' title='Solve'>Solve</button>",
                "status" => $row['status'] === 'Y' ? "<div class='text-success'>" . $status . "</div>" : "<div class='text-muted'> " . $status . "</div>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "job_id" => $row['job_id'],
                "job_date" => $row['job_date'],
                "select" => "<button type='button' name='select' id='" . $row['job_id'] . "@" . $row['job_date'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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
