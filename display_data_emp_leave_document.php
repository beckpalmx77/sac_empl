<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "" || strlen($_SESSION['department_id']) == ""){
    header("Location: index");
} else {

    include("config/connect_db.php");

    $month_num_start = "1";
    $month_num_to = str_replace('0', '', date('m'));

    $sql_start_month = " SELECT * FROM ims_month WHERE month = '" . $month_num_start . "'";
    $sql_curr_month = " SELECT * FROM ims_month WHERE month = '" . $month_num_to . "'";

    $stmt_start_month = $conn->prepare($sql_start_month);
    $stmt_start_month->execute();
    $MonthStart = $stmt_start_month->fetchAll();
    foreach ($MonthStart as $row_start) {
        $month_name_start = $row_start["month_name"];
    }

    $stmt_curr_month = $conn->prepare($sql_curr_month);
    $stmt_curr_month->execute();
    $MonthCurr = $stmt_curr_month->fetchAll();
    foreach ($MonthCurr as $row_curr) {
        $month_name_to = $row_curr["month_name"];
    }

    $sql_month = " SELECT * FROM ims_month ";
    $stmt_month = $conn->prepare($sql_month);
    $stmt_month->execute();
    $MonthRecords = $stmt_month->fetchAll();

    $sql_year = " SELECT DISTINCT(doc_year) AS doc_year FROM dleave_event ORDER BY doc_year DESC ";
    $stmt_year = $conn->prepare($sql_year);
    $stmt_year->execute();
    $YearRecords = $stmt_year->fetchAll();

    $sql_branch = " SELECT * FROM ims_branch ";
    $stmt_branch = $conn->prepare($sql_branch);
    $stmt_branch->execute();
    $BranchRecords = $stmt_branch->fetchAll();

    ?>

    <!DOCTYPE html>
    <html lang="th">
    <body id="page-top">
    <div id="wrapper">
        <?php include('includes/Side-Bar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('includes/Top-Bar.php'); ?>

                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo urldecode($_GET['s']) ?></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><?php echo urldecode($_GET['m']) ?></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><?php echo urldecode($_GET['s']) ?></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"></div>
                                <div class="card-body">
                                    <section class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12 col-md-offset-2">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <form id="myform" name="myform" method="post">

                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="month_start">เลือกเดือน (เริ่มต้น)
                                                                        :</label>
                                                                    <select name="month_start" id="month_start"
                                                                            class="form-control" required
                                                                            onchange="validateMonths()">
                                                                        <option value="<?php echo $month_num_start; ?>"
                                                                                selected><?php echo $month_name_start; ?></option>
                                                                        <?php foreach ($MonthRecords as $row) { ?>
                                                                            <option value="<?php echo $row["month"]; ?>"><?php echo $row["month_name"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="month_to">เลือกเดือน (ถึง) :</label>
                                                                    <select name="month_to" id="month_to"
                                                                            class="form-control" required
                                                                            onchange="validateMonths()">
                                                                        <option value="<?php echo $month_num_to; ?>"
                                                                                selected><?php echo $month_name_to; ?></option>
                                                                        <?php foreach ($MonthRecords as $row) { ?>
                                                                            <option value="<?php echo $row["month"]; ?>"><?php echo $row["month_name"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="year">เลือกปี :</label>
                                                                    <select name="year" id="year" class="form-control"
                                                                            required>
                                                                        <?php foreach ($YearRecords as $row) { ?>
                                                                            <option value="<?php echo $row["doc_year"]; ?>"><?php echo $row["doc_year"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                    <?php
                                                                    if ($_SESSION['document_dept_cond'] !== "A") { ?>
                                                                        <input type="hidden" name="branch" id="branch"
                                                                               value="<?php echo $_SESSION['department_id'] ?>">
                                                                    <?php } else {
                                                                        ?>

                                                                        <label for="branch">เลือกสาขา :</label>
                                                                        <select name="branch" id="branch"
                                                                                class="form-control" required>
                                                                            <?php foreach ($BranchRecords as $row) { ?>
                                                                                <option value="<?php echo $row["branch"]; ?>"><?php echo $row["branch_name"]; ?></option>
                                                                            <?php } ?>
                                                                        </select>

                                                                    <?php } ?>
                                                                </div>
                                                            </div>

                                                            <br>

                                                            <div class="form-group row align-items-center">
                                                                <div class="col-sm-4">
                                                                    <label for="emp_id" class="control-label">รหัสพนักงาน</label>
                                                                    <input type="text" class="form-control" id="emp_id"
                                                                           name="emp_id" readonly="true"
                                                                           required="required" value="" placeholder="">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label for="full_name" class="control-label">ชื่อ -
                                                                        นามสกุล</label>
                                                                    <input type="text" class="form-control"
                                                                           id="full_name" name="full_name"
                                                                           readonly="true" value="" placeholder="">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <label class="control-label d-block">&nbsp;</label>
                                                                    <!-- ใช้ &nbsp; เพื่อสร้างช่องว่างให้ปุ่มอยู่ในระดับเดียวกับ input -->
                                                                    <a data-toggle="modal" href="#SearchEmployeeModal"
                                                                       class="btn btn-primary">
                                                                        Click <i class="fa fa-search"
                                                                                 aria-hidden="true"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <br>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" id="BtnData"
                                                                            name="BtnData"
                                                                            class="btn btn-outline-primary mb-3">
                                                                        สรุปข้อมูล
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>


                                                    <div class="modal fade" id="SearchEmployeeModal">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Modal title</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>

                                                                <div class="container"></div>
                                                                <div class="modal-body">

                                                                    <div class="modal-body">

                                                                        <table cellpadding="0" cellspacing="0" border="0"
                                                                               class="display"
                                                                               id="TableEmployeeList"
                                                                               width="100%">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>รหัสพนักงาน</th>
                                                                                <th>ชื่อพนักงาน</th>
                                                                                <th>ชื่อเล่น</th>
                                                                                <th>หน่วยงาน</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                            <tr>
                                                                                <th>รหัสพนักงาน</th>
                                                                                <th>ชื่อพนักงาน</th>
                                                                                <th>ชื่อเล่น</th>
                                                                                <th>หน่วยงาน</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                include('includes/Modal-Logout.php');
                include('includes/Footer.php');
                ?>

            </div>
        </div>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/myadmin.min.js"></script>

        <!--script src="js/modal/show_employee_modal.js"></script-->

        <script src="vendor/select2/dist/js/select2.min.js"></script>
        <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
        <script src="vendor/clock-picker/clockpicker.js"></script>
        <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
        <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
        <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

        <script>
            function validateMonths() {
                const startMonth = parseInt($('#month_start').val());
                const endMonth = parseInt($('#month_to').val());

                if (endMonth < startMonth) {
                    alertify.alert("เดือนสิ้นสุดไม่ควรอยู่ก่อนเดือนเริ่มต้น");
                    $('#month_to').val(startMonth);
                }
            }

            $(document).ready(function () {
                $('#myform').on('submit', function (e) {
                    e.preventDefault(); // Prevent the form from submitting normally

                    const startMonth = parseInt($('#month_start').val());
                    const endMonth = parseInt($('#month_to').val());

                    if (endMonth < startMonth) {
                        alert("เดือนสิ้นสุดไม่ควรอยู่ก่อนเดือนเริ่มต้น");
                        return false;
                    }

                    // Serialize the form data
                    let formData = $(this).serialize();

                    // Open a new window
                    let newWindow = window.open('', '_blank');

                    // Perform the AJAX request
                    $.ajax({
                        type: 'POST',
                        url: 'show_data_leave_document.php',
                        data: formData,
                        success: function (response) {
                            // Write the response to the new window
                            newWindow.document.write(response);
                        }
                    });
                });
            });
        </script>

        <script>

            // เลือก Select Element ที่ต้องการตรวจสอบ
            const selectElement = document.getElementById('branch');

            // ฟังเหตุการณ์ 'change' เมื่อค่าใน select เปลี่ยนไป
            selectElement.addEventListener('change', function (event) {

                $.ajax({
                    url: 'model/manage_menu_main_process.php',
                    method: "POST",
                    data: formData,
                    success: function (data) {
                        if (data == 2) {
                            alert("Duplicate มีข้อมูลนี้แล้วในระบบ กรุณาตรวจสอบ");
                        }
                    }
                })

            });

        </script>

    </body>
    </html>

<?php } ?>
