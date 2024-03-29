<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM mwork_time "
        . " WHERE mwork_time.id = " . $id;

    //$myfile = fopen("myqeury_1.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $sql_get);
    //fclose($myfile);

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "work_time_id" => $result['work_time_id'],
            "work_time_detail" => $result['work_time_detail'],
            "work_time_start" => $result['work_time_start'],
            "break_time_start" => $result['break_time_start'],
            "break_time_stop" => $result['break_time_stop'],
            "work_time_stop" => $result['work_time_stop'],
            "status" => $result['status']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["work_time_id"] !== '') {

        $work_time_id = $_POST["work_time_id"];
        $sql_find = "SELECT * FROM mwork_time WHERE work_time_id = '" . $work_time_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["work_time_detail"] !== '') {
        $work_time_id = "S" . sprintf('%03s', LAST_ID($conn, "mwork_time", 'id'));
        //$work_time_id = $_POST["work_time_id"];
        $work_time_detail = $_POST["work_time_detail"];
        $work_time_start = $_POST["work_time_start"];
        $work_time_stop = $_POST["work_time_stop"];
        $break_time_start = $_POST["break_time_start"];
        $break_time_stop = $_POST["break_time_stop"];
        $status = $_POST["status"];
        $sql_find = "SELECT * FROM mwork_time WHERE work_time_id = '" . $work_time_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO mwork_time(work_time_id,work_time_detail,work_time_start,work_time_stop,break_time_start,break_time_stop) 
                    VALUES (:work_time_id,:work_time_detail,:work_time_start,:work_time_stop,:break_time_start,:break_time_stop)";
            $query = $conn->prepare($sql);
            $query->bindParam(':work_time_id', $work_time_id, PDO::PARAM_STR);
            $query->bindParam(':work_time_detail', $work_time_detail, PDO::PARAM_STR);
            $query->bindParam(':work_time_start', $work_time_start, PDO::PARAM_STR);
            $query->bindParam(':work_time_stop', $work_time_stop, PDO::PARAM_STR);
            $query->bindParam(':break_time_start', $break_time_start, PDO::PARAM_STR);
            $query->bindParam(':break_time_stop', $break_time_stop, PDO::PARAM_STR);
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
    if ($_POST["work_time_detail"] != '') {
        $id = $_POST["id"];
        $work_time_id = $_POST["work_time_id"];
        $work_time_detail = $_POST["work_time_detail"];
        $work_time_start = $_POST["work_time_start"];
        $work_time_stop = $_POST["work_time_stop"];
        $status = $_POST["status"];
        $sql_find = "SELECT * FROM mwork_time WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE mwork_time SET work_time_id=:work_time_id,work_time_detail=:work_time_detail,work_time_start=:work_time_start,work_time_stop=:work_time_stop,status=:status            
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':work_time_id', $work_time_id, PDO::PARAM_STR);
            $query->bindParam(':work_time_detail', $work_time_detail, PDO::PARAM_STR);
            $query->bindParam(':work_time_start', $work_time_start, PDO::PARAM_STR);
            $query->bindParam(':work_time_stop', $work_time_stop, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }
    }
}

if ($_POST["action"] === 'DELETE') {
    $id = $_POST["id"];
    $sql_find = "SELECT * FROM mwork_time WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM mwork_time WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_WORKTIME') {
    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (work_time_id LIKE :work_time_id or
        work_time_detail LIKE :work_time_detail ) ";
        $searchArray = array(
            'work_time_id' => "%$searchValue%",
            'work_time_detail' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM mwork_time ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM mwork_time WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $stmt = $conn->prepare("SELECT * FROM mwork_time WHERE 1 " . $searchQuery
        . " ORDER BY id desc , " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

    /*
        $txt = $searchQuery . " | " . $columnName . " | " . $columnSortOrder ;
        $my_file = fopen("device_a.txt", "w") or die("Unable to open file!");
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
            $data[] = array(
                "id" => $row['id'],
                "work_time_id" => $row['work_time_id'],
                "work_time_detail" => $row['work_time_detail'],
                "work_time_start" => $row['work_time_start'],
                "work_time_stop" => $row['work_time_stop'],
                "break_time_start" => $row['break_time_start'],
                "break_time_stop" => $row['break_time_stop'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "delete" => "<button type='button' name='delete' id='" . $row['id'] . "' class='btn btn-danger btn-xs delete' data-toggle='tooltip' title='Delete'>Delete</button>",
                "status" => $row['status'] === 'Y' ? "<div class='text-success'>" . $row['status'] . "</div>" : "<div class='text-muted'> " . $row['status'] . "</div>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "work_time_id" => $row['work_time_id'],
                "work_time_detail" => $row['work_time_detail'],
                "select" => "<button type='button' name='select' id='" . $row['work_time_id'] . "@" . $row['work_time_detail'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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
