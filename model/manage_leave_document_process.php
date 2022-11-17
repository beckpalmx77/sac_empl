<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');

if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT dl.*,lt.leave_type_detail FROM dleave_event dl
            left join mleave_type lt on lt.leave_type_id = dl.leave_type_id 
            WHERE dl.id = " . $id;

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "doc_id" => $result['doc_id'],
            "doc_date" => $result['doc_date'],
            "leave_type_id" => $result['leave_type_id'],
            "leave_type_detail" => $result['leave_type_detail'],
            "emp_id" => $result['emp_id'],
            "date_leave_start" => $result['date_leave_start'],
            "date_leave_to" => $result['date_leave_to'],            
            "time_leave_start" => $result['time_leave_start'],
            "time_leave_to" => $result['time_leave_to'],
            "approve_1_id" => $result['approve_1_id'],
            "approve_1_status" => $result['approve_1_status'],
            "approve_2_id" => $result['approve_2_id'],
            "approve_2_status" => $result['approve_2_status'],
            "remark" => $result['remark'],
            "status" => $result['status']);
    }
    echo json_encode($return_arr);
}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["leave_type_id"] !== '') {

        $doc_id = $_POST["doc_id"];
        $sql_find = "SELECT * FROM dleave_event WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["doc_date"] !== '' && $_POST["emp_id"] !== '') {
        $dept_id = $_POST["department"];
        $doc_date = $_POST["doc_date"];
        $doc_id = $dept_id . "-" . substr($doc_date, 6) . "-" . sprintf('%04s', LAST_ID($conn, "dleave_event", 'id'));
        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_to"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];

        $sql_find = "SELECT * FROM dleave_event WHERE doc_id = '" . $doc_id . "'";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO dleave_event (doc_id,doc_date,leave_type_id,emp_id,date_leave_start,time_leave_start,date_leave_to,time_leave_to,remark) 
                    VALUES (:doc_id,:doc_date,:leave_type_id,:emp_id,:date_leave_start,:time_leave_start,:date_leave_to,:time_leave_to,:remark)";

            $query = $conn->prepare($sql);
            $query->bindParam(':doc_id', $doc_id, PDO::PARAM_STR);
            $query->bindParam(':doc_date', $doc_date, PDO::PARAM_STR);
            $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
            $query->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
            $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
            $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
            $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
            $query->bindParam(':time_leave_to', $time_leave_to, PDO::PARAM_STR);
            $query->bindParam(':remark', $remark, PDO::PARAM_STR);
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

    if ($_POST["doc_id"] != '') {

        $id = $_POST["id"];
        $doc_id = $_POST["doc_id"];
        $doc_date = $_POST["doc_date"];
        $dept_id = $_POST["department"];
        $leave_type_id = $_POST["leave_type_id"];
        $emp_id = $_POST["emp_id"];
        $date_leave_start = $_POST["date_leave_start"];
        $time_leave_start = $_POST["time_leave_start"];
        $date_leave_to = $_POST["date_leave_to"];
        $time_leave_to = $_POST["time_leave_to"];
        $remark = $_POST["remark"];
        $status = $_POST["status"];

        $sql_find = "SELECT * FROM dleave_event WHERE doc_id = '" . $doc_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            if ($dept_id === 'IT' || $dept_id === 'HR') {
                $sql_update = "UPDATE dleave_event SET status=:status
                               WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                echo $save_success;
            } else {
                $sql_update = "UPDATE dleave_event SET date_leave_start=:date_leave_start,date_leave_to=:date_leave_to,leave_type_id=:leave_type_id
            ,time_leave_start=:time_leave_start        
            WHERE id = :id";
                $query = $conn->prepare($sql_update);
                $query->bindParam(':date_leave_start', $date_leave_start, PDO::PARAM_STR);
                $query->bindParam(':date_leave_to', $date_leave_to, PDO::PARAM_STR);
                $query->bindParam(':leave_type_id', $leave_type_id, PDO::PARAM_STR);
                $query->bindParam(':time_leave_start', $time_leave_start, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                //$query->execute();
                echo $save_success;
            }
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM dleave_event WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM dleave_event WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_LEAVE_DOCUMENT') {

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
    $searchQuery = " ";
    if ($_POST["page_manage"]!=="ADMIN") {
        $searchQuery = " AND emp_id = '" . $_SESSION['emp_id'] . "'";
    }

    if ($searchValue != '') {
        $searchQuery = " AND (leave_type_id LIKE :leave_type_id or
        doc_date LIKE :doc_date ) ";
        $searchArray = array(
            'leave_type_id' => "%$searchValue%",
            'doc_date' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM dleave_event ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM dleave_event WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $stmt = $conn->prepare("SELECT dl.*,lt.leave_type_detail FROM dleave_event dl
            left join mleave_type lt on lt.leave_type_id = dl.leave_type_id WHERE 1 " . $searchQuery
        . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");


        $txt = $_POST["page_manage"] . " | " . $searchQuery . " | " . $columnName . " | " . $columnSortOrder ;
        $my_file = fopen("leave_a.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $txt);
        fclose($my_file);



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
                    $status = "รอพิจารณา";
                    break;
                case "A":
                    $status = "อนุมัติ";
                    break;
                case "R":
                    $status = "ไม่อนุมัติ";
                    break;
            }


            $data[] = array(
                "id" => $row['id'],
                "doc_id" => $row['doc_id'],
                "doc_date" => $row['doc_date'],
                "emp_id" => $row['emp_id'],
                "leave_type_id" => $row['leave_type_id'],
                "leave_type_detail" => $row['leave_type_detail'],
                "date_leave_start" => $row['date_leave_start'],
                "date_leave_to" => $row['date_leave_to'],
                "time_leave_start" => $row['time_leave_start'],
                "time_leave_to" => $row['time_leave_to'],
                "dt_leave_start" => $row['date_leave_start'] . " " .  $row['time_leave_start'],
                "dt_leave_to" => $row['date_leave_to'] . " " .  $row['time_leave_to'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "approve" => "<button type='button' name='approve' id='" . $row['id'] . "' class='btn btn-success btn-xs solve' data-toggle='tooltip' title='Solve'>Approve</button>",
                "status" => $row['status'] === 'Y' ? "<div class='text-success'>" . $status . "</div>" : "<div class='text-muted'> " . $status . "</div>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "leave_type_id" => $row['leave_type_id'],
                "leave_type_detail" => $row['leave_type_detail'],
                "select" => "<button type='button' name='select' id='" . $row['leave_type_id'] . "@" . $row['leave_type_detail'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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
