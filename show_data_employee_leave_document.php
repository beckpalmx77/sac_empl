<?php

include("config/connect_db.php");

/*
$month_start = $_POST["month_start"];
$month_to = $_POST["month_to"];
$year = $_POST["year"];
*/

$doc_date = substr($_POST["doc_date"],6,4) . "-" . substr($_POST["doc_date"],3,2) . "-" . substr($_POST["doc_date"],0,2);

$month_start = '01';
$month_to = date('m', strtotime($doc_date));
$year = date('Y', strtotime($doc_date));

$emp_id = $_POST["emp_id"];
$f_name = $_POST["f_name"];
$l_name = $_POST["l_name"];

$month_name_start = "";
$month_name_to = "";

$sql_start_month = "SELECT * FROM ims_month WHERE month_id = :month_start";
$stmt_start_month = $conn->prepare($sql_start_month);
$stmt_start_month->bindParam(':month_start', $month_start);
$stmt_start_month->execute();
$MonthStart = $stmt_start_month->fetchAll();
foreach ($MonthStart as $row_start) {
    $month_id_start = $row_start["month_id"];
    $month_name_start = $row_start["month_name"];
}

$sql_to_month = "SELECT * FROM ims_month WHERE month_id = :month_to";
$stmt_to_month = $conn->prepare($sql_to_month);
$stmt_to_month->bindParam(':month_to', $month_to);
$stmt_to_month->execute();
$MonthTo = $stmt_to_month->fetchAll();
foreach ($MonthTo as $row_to) {
    $month_id_to = $row_to["month_id"];
    $month_name_to = $row_to["month_name"];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta date="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <script src="js/jquery-3.6.0.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">
    <link href='vendor/calendar/main.css' rel='stylesheet'/>
    <script src='vendor/calendar/main.js'></script>
    <script src='vendor/calendar/locales/th.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src='js/util.js'></script>
    <title>สงวนออโต้คาร์</title>
    <style>
        table {
            width: 50%;
        }
    </style>
</head>
<p class="card">
<div class="card-header bg-primary text-white">
    <i class="fa fa-signal" aria-hidden="true"></i> แสดงข้อมูลการลา-เปลี่ยนวันหยุด-วันหยุดนักขัตฤกษ์ พนักงาน
    <?php echo " เดือน " . $month_name_start . " ถึงเดือน " . $month_name_to . " ปี " . $year; ?>
</div>

<div class="card-body">
    <!--a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์ </a-->
    <!--a id="myLink" href="#" onclick="window.close();"><i class="fa fa-window-close"></i> ปิด (Close) </a-->
    <div class="container-fluid" id="container-wrapper">
        <button class="btn btn-danger" onclick="window.close()">ปิด (Close)</button>
    </div>
</div>

<input type="hidden" class="form-control" id="f_name" name="f_name" value="">

<div class="card-body">
    <h4><span class="badge bg-success">แสดงข้อมูลการลา พนักงาน</span></h4>
    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>วันที่เอกสาร</th>
            <th>ชื่อพนักงาน</th>
            <th>หน่วยงาน</th>
            <th>ประเภทการลา</th>
            <th>วันที่ลาเริ่มต้น</th>
            <th>วันที่ลาสิ้นสุด</th>
            <th>จำนวนวัน</th>
            <th>หมายเหตุ</th>
        </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
        <?php
        $date = date("d/m/Y");
        $total = 0;
        $total_payment = 0;
        $sql_leave = " SELECT * FROM v_dleave_event 
            WHERE doc_year = :year 
            AND doc_month BETWEEN :month_id_start AND :month_id_to
            AND (f_name = :f_name OR emp_id = :emp_id) ";

        $sql_leave .= " ORDER BY f_name , doc_date ";

        $statement_leave = $conn->prepare($sql_leave);
        $statement_leave->bindParam(':year', $year);
        $statement_leave->bindParam(':month_id_start', $month_id_start);
        $statement_leave->bindParam(':month_id_to', $month_id_to);
        $statement_leave->bindParam(':f_name', $f_name);
        $statement_leave->bindParam(':emp_id', $emp_id);

        $statement_leave->execute();
        $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
        $line_no = 0;
        foreach ($results_leave as $row_leave) {
            $line_no++;
            ?>
            <tr>
                <td><?php echo htmlentities($line_no); ?></td>
                <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                <td><?php echo htmlentities($row_leave['leave_day']); ?></td>
                <td><?php echo htmlentities($row_leave['remark']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class="card-body">
    <h4><span class="badge bg-info">แสดงข้อมูลการใช้วันหยุด (นักขัตฤกษ์-ประจำปี) พนักงาน</span></h4>
    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>วันที่เอกสาร</th>
            <th>ชื่อพนักงาน</th>
            <th>หน่วยงาน</th>
            <th>ประเภท</th>
            <th>วันที่เริ่มต้น</th>
            <th>วันที่สิ้นสุด</th>
            <th>หมายเหตุ</th>
        </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
        <?php
        $sql_leave = " SELECT * FROM vdholiday_event 
            WHERE doc_year = :year 
            AND month BETWEEN :month_id_start AND :month_id_to
            AND (f_name = :f_name OR emp_id = :emp_id) ";

        $sql_leave .= " ORDER BY f_name , doc_date ";

        $statement_leave = $conn->prepare($sql_leave);
        $statement_leave->bindParam(':year', $year);
        $statement_leave->bindParam(':month_id_start', $month_id_start);
        $statement_leave->bindParam(':month_id_to', $month_id_to);
        $statement_leave->bindParam(':f_name', $f_name);
        $statement_leave->bindParam(':emp_id', $emp_id);

        $statement_leave->execute();
        $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
        $line_no = 0;
        foreach ($results_leave as $row_leave) {
            $line_no++;
            ?>
            <tr>
                <td><?php echo htmlentities($line_no); ?></td>
                <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                <td><?php echo htmlentities($row_leave['remark']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class="card-body">
    <h4><span class="badge bg-warning">แสดงข้อมูลการเปลี่ยนวันหยุด พนักงาน</span></h4>
    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>วันที่เอกสาร</th>
            <th>ชื่อพนักงาน</th>
            <th>หน่วยงาน</th>
            <th>ประเภท</th>
            <th>วันที่หยุดปกติ</th>
            <th>วันที่ต้องการหยุด</th>
            <th>หมายเหตุ</th>
        </tr>
        </thead>
        <tfoot></tfoot>
        <tbody>
        <?php
        $sql_leave = " SELECT * FROM v_dchange_event 
            WHERE doc_year = :year 
            AND doc_month BETWEEN :month_id_start AND :month_id_to
            AND (f_name = :f_name OR emp_id = :emp_id) ";

        $sql_leave .= " ORDER BY f_name , doc_date ";

        $statement_leave = $conn->prepare($sql_leave);
        $statement_leave->bindParam(':year', $year);
        $statement_leave->bindParam(':month_id_start', $month_id_start);
        $statement_leave->bindParam(':month_id_to', $month_id_to);
        $statement_leave->bindParam(':f_name', $f_name);
        $statement_leave->bindParam(':emp_id', $emp_id);

        $statement_leave->execute();
        $results_leave = $statement_leave->fetchAll(PDO::FETCH_ASSOC);
        $line_no = 0;
        foreach ($results_leave as $row_leave) {
            $line_no++;
            ?>
            <tr>
                <td><?php echo htmlentities($line_no); ?></td>
                <td><?php echo htmlentities($row_leave['doc_date']); ?></td>
                <td><?php echo htmlentities($row_leave['f_name'] . " " . $row_leave['l_name']); ?></td>
                <td><?php echo htmlentities($row_leave['department_id']); ?></td>
                <td><?php echo htmlentities($row_leave['leave_type_detail']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_start']); ?></td>
                <td><?php echo htmlentities($row_leave['date_leave_to']); ?></td>
                <td><?php echo htmlentities($row_leave['remark']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
