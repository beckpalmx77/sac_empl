<?php
session_start();
error_reporting(0);
$curr_date = date("d-m-Y");

$start_work_date = $_SESSION['start_work_date'];

include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "" || strlen($_SESSION['department_id']) == "") {
    header("Location: index.php");
} else {
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <body id="page-top">
    <div id="wrapper">
        <?php
        include('includes/Side-Bar.php');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                include('includes/Top-Bar.php');
                ?>
                <!-- Container Fluid-->
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
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">

                                        <div class="col-md-12 col-md-offset-2">
                                            <label for="name_t"
                                                   class="control-label"><b>เพิ่ม <?php echo urldecode($_GET['s']) ?></b></label>
                                            <button type='button' name='btnAdd' id='btnAdd'
                                                    class='btn btn-primary btn-xs'>Add
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <div class="col-md-12 col-md-offset-2">
                                            <table id='TableRecordList' class='display dataTable'>
                                                <thead>
                                                <tr>
                                                    <th>ปี</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ชื่อ-นามสกุล</th>
                                                    <th>หน่วยงาน</th>
                                                    <th>วันที่ขอ OT</th>
                                                    <th>ช่วงเวลาที่ขอ OT</th>
                                                    <th>สถานะ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>ปี</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ชื่อ-นามสกุล</th>
                                                    <th>หน่วยงาน</th>
                                                    <th>วันที่ขอ OT</th>
                                                    <th>ช่วงเวลาที่ขอ OT</th>
                                                    <th>สถานะ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div id="result"></div>

                                        </div>

                                        <div class="modal fade" id="recordModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Modal title</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <form method="post" id="recordForm">
                                                        <div class="modal-body">
                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="text"
                                                                           class="control-label">เลขที่เอกสาร</label>
                                                                    <input type="doc_id" class="form-control"
                                                                           id="doc_id" name="doc_id"
                                                                           readonly="true"
                                                                           placeholder="สร้างอัตโนมัติ">
                                                                </div>


                                                                <input type="hidden" class="form-control"
                                                                       id="page_manage" name="page_manage"
                                                                       readonly="true"
                                                                       value="USER"
                                                                       placeholder="page_manage">

                                                                <input type="hidden" class="form-control"
                                                                       id="work_time_start" name="work_time_start"
                                                                       readonly="true"
                                                                       value="<?php echo $start_work_date ?>"
                                                                       placeholder="">

                                                                <input type="hidden" class="form-control"
                                                                       id="leave_before" name="leave_before"
                                                                       readonly="true"
                                                                       value=""
                                                                       placeholder="">

                                                                <input type="hidden" class="form-control"
                                                                       id="department" name="department"
                                                                       readonly="true"
                                                                       value="<?php echo $_SESSION['department_id'] ?>"
                                                                       placeholder="department">

                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <label for="text"
                                                                               class="control-label">รหัสพนักงาน</label>
                                                                        <input type="text" class="form-control"
                                                                               id="emp_id" name="emp_id"
                                                                               readonly="true"
                                                                               required="required"
                                                                               value=""
                                                                               placeholder="">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="text"
                                                                               class="control-label">ชื่อ -
                                                                            นามสกุล</label>
                                                                        <input type="text" class="form-control"
                                                                               id="full_name" name="full_name"
                                                                               readonly="true"
                                                                               value=""
                                                                               placeholder="">
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <label for="emp_id"
                                                                               class="control-label">เลือก</label>
                                                                        <a data-toggle="modal"
                                                                           href="#SearchEmployeeModal"
                                                                           class="btn btn-primary">
                                                                            Click <i class="fa fa-search"
                                                                                     aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-3">
                                                                        <label for="doc_date"
                                                                               class="control-label">วันที่เอกสาร</label>
                                                                        <i class="fa fa-calendar"
                                                                           aria-hidden="true"></i>
                                                                        <input type="text" class="form-control"
                                                                               id="doc_date"
                                                                               name="doc_date"
                                                                               required="required"
                                                                               value="<?php echo $curr_date ?>"
                                                                               readonly="true"
                                                                               placeholder="วันที่เอกสาร">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <input type="hidden" class="form-control"
                                                                           id="leave_type_id"
                                                                           required="required"
                                                                           name="leave_type_id">
                                                                    <div class="col-sm-10">
                                                                        <label for="leave_type_detail"
                                                                               class="control-label">ประเภทเอกสาร</label>
                                                                        <input type="text" class="form-control"
                                                                               id="leave_type_detail"
                                                                               name="leave_type_detail"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="">
                                                                    </div>

                                                                    <!--div class="col-sm-2">
                                                                        <label for="leave_type_id"
                                                                               class="control-label">เลือก</label>
                                                                        <a data-toggle="modal"
                                                                           href="#SearchLeaveTypeModal"
                                                                           class="btn btn-primary">
                                                                            Click <i class="fa fa-search"
                                                                                     aria-hidden="true"></i>
                                                                        </a>
                                                                    </div-->
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-3">
                                                                        <label for="date_leave_start"
                                                                               class="control-label">วันที่ขอ OT</label>
                                                                        <i class="fa fa-calendar"
                                                                           aria-hidden="true"></i>
                                                                        <input type="text" class="form-control"
                                                                               id="date_leave_start"
                                                                               name="date_leave_start"
                                                                               value=""
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="วันที่ขอ OT">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label for="time_leave_start"
                                                                               class="control-label">เวลาเริ่มต้น</label>
                                                                        <input type="text" class="form-control"
                                                                               id="time_leave_start"
                                                                               name="time_leave_start"
                                                                               value=""
                                                                               required="required"
                                                                               placeholder="hh:mm">
                                                                    </div>

                                                                    <input type="hidden" class="form-control"
                                                                           id="date_leave_to"
                                                                           name="date_leave_to"
                                                                           value="echo $curr_date"
                                                                           readonly="true"
                                                                           placeholder="วันที่ขอ OT">

                                                                    <!--div class="col-sm-3">
                                                                        <label for="date_leave_to"
                                                                               class="control-label">วันที่ขอ OT</label>
                                                                        <i class="fa fa-calendar"
                                                                           aria-hidden="true"></i>
                                                                        <input type="hidden" class="form-control"
                                                                               id="date_leave_to"
                                                                               name="date_leave_to"
                                                                               value="echo $curr_date"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="วันที่ขอ OT">
                                                                    </div-->

                                                                    <div class="col-sm-3">
                                                                        <label for="time_leave_to"
                                                                               class="control-label">เวลาสิ้นสุด</label>
                                                                        <input type="text" class="form-control"
                                                                               id="time_leave_to"
                                                                               name="time_leave_to"
                                                                               required="required"
                                                                               value=""
                                                                               placeholder="hh:mm">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="remark"
                                                                           class="control-label">หมายเหตุ</label>
                                                                    <textarea class="form-control"
                                                                              id="remark"
                                                                              name="remark"
                                                                              rows="3"></textarea>
                                                                </div>

                                                                <?php if ($_SESSION['approve_permission'] === 'Y') { ?>
                                                                    <div class="form-group">
                                                                        <label for="status"
                                                                               class="control-label">Status</label>
                                                                        N = รอพิจารณา , A = อนุมัติ , R = ไม่อนุมัติ
                                                                        <select id="status" name="status"
                                                                                class="form-control"
                                                                                data-live-search="true"
                                                                                title="Please select">
                                                                            <option>N</option>
                                                                            <option>A</option>
                                                                            <option>R</option>
                                                                        </select>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="form-group">
                                                                        <label for="status"
                                                                               class="control-label">Status</label>
                                                                        N = รอพิจารณา , A = อนุมัติ , R = ไม่อนุมัติ
                                                                        <select id="status" name="status"
                                                                                class="form-control"
                                                                                data-live-search="true"
                                                                                readonly="true"
                                                                                title="Please select">
                                                                            <option>N</option>
                                                                            <option>A</option>
                                                                            <option>R</option>
                                                                        </select>
                                                                    </div>

                                                                <?php } ?>

                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id"/>
                                                            <input type="hidden" name="action" id="action" value=""/>
                                                            <span class="icon-input-btn">
                                                                <i class="fa fa-check"></i>
                                                            <input type="submit" name="save" id="save"
                                                                   class="btn btn-primary" value="Save"/>
                                                            </span>
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Close <i
                                                                        class="fa fa-window-close"></i>
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
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


                                        <div class="modal fade" id="SearchLeaveTypeModal">
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
                                                                   id="TableLeaveTypeList"
                                                                   width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th>รหัสประเภทการลา</th>
                                                                    <th>รายละเอียด</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tfoot>
                                                                <tr>
                                                                    <th>รหัสประเภทการลา</th>
                                                                    <th>รายละเอียด</th>
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
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    include('includes/Modal-Logout.php');
    include('includes/Footer.php');
    ?>


    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/myadmin.min.js"></script>

    <script src="js/modal/show_employee_modal.js"></script>
    <script src="js/modal/show_leave_type_modal.js"></script>
    <script src="js/util/calculate_datetime.js"></script>

    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.min.js"></script>


    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

    <style>

        .icon-input-btn {
            display: inline-block;
            position: relative;
        }

        .icon-input-btn input[type="submit"] {
            padding-left: 2em;
        }

        .icon-input-btn .fa {
            display: inline-block;
            position: absolute;
            left: 0.65em;
            top: 30%;
        }
    </style>
    <script>
        $(document).ready(function () {
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            let formData = {action: "GET_LEAVE_DOCUMENT", sub_action: "GET_MASTER", page_manage: "USER",};
            let dataRecords = $('#TableRecordList').DataTable({
                'lengthMenu': [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
                'language': {
                    search: 'ค้นหา', lengthMenu: 'แสดง _MENU_ รายการ',
                    info: 'หน้าที่ _PAGE_ จาก _PAGES_',
                    infoEmpty: 'ไม่มีข้อมูล',
                    zeroRecords: "ไม่มีข้อมูลตามเงื่อนไข",
                    infoFiltered: '(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)',
                    paginate: {
                        previous: 'ก่อนหน้า',
                        last: 'สุดท้าย',
                        next: 'ต่อไป'
                    }
                },
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'autoWidth': true,
                <?php  if ($_SESSION['deviceType'] !== 'computer') {
                    echo "'scrollX': true,";
                }?>
                'ajax': {
                    'url': 'model/manage_ot_request_document_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'doc_year'},
                    {data: 'doc_id'},
                    {data: 'doc_date'},
                    {data: 'full_name'},
                    {data: 'department_id'},
                    {data: 'dt_leave_start'},
                    {data: 'dt_leave_to'},
                    {data: 'status'},
                    {data: 'update'},
                ]
            });

            <!-- *** FOR SUBMIT FORM *** -->
            $("#recordModal").on('submit', '#recordForm', function (event) {
                event.preventDefault();

                //alert("time_leave_start = " + chkTime($('#time_leave_start').val()));
                //alert("time_leave_to = " + chkTime($('#time_leave_to').val()));

                if (chkTime($('#time_leave_start').val()) && chkTime($('#time_leave_to').val())) {

                    if ($('#date_leave_start').val() !== '' && $('#time_leave_start').val() !== '' && $('#time_leave_to').val() !== '') {

                        let formData = $(this).serialize();
                        $.ajax({
                            url: 'model/manage_ot_request_document_process.php',
                            method: "POST",
                            data: formData,
                            success: function (data) {
                                alertify.success(data);
                                $('#recordForm')[0].reset();
                                $('#recordModal').modal('hide');
                                $('#save').attr('disabled', false);
                                dataRecords.ajax.reload();
                            }
                        })

                    } else {
                        alertify.error("กรุณาป้อนวันที่ - เวลา ต้องการขอ OT !!!");
                    }
                } else {
                    alertify.error("กรุณาป้อนวันที่ - เวลา ให้ถูกต้อง !!!");
                }

            });
            <!-- *** FOR SUBMIT FORM *** -->
        });

    </script>

    <script>
        $(document).ready(function () {

            $("#btnAdd").click(function () {
                $('#recordModal').modal('show');
                $('#id').val("");
                $('#doc_id').val("");
                $('#leave_type_id').val("O");
                $('#leave_type_detail').val("การขอทำงานล่วงเวลา (OT)");
                $('#date_leave_start').val("");
                $('#date_leave_to').val("");
                $('#remark').val("");
                $('#status').val("N");
                $('.modal-title').html("<i class='fa fa-plus'></i> ADD Record");
                $('#action').val('ADD');
                $('#save').val('Save');
            });
        });
    </script>

    <script>

        $("#TableRecordList").on('click', '.update', function () {
            let id = $(this).attr("id");
            //alert(id);
            let formData = {action: "GET_DATA", id: id};
            $.ajax({
                type: "POST",
                url: 'model/manage_ot_request_document_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let doc_id = response[i].doc_id;
                        let doc_date = response[i].doc_date;
                        let emp_id = response[i].emp_id;
                        let full_name = response[i].full_name;
                        let leave_type_id = response[i].leave_type_id;
                        let leave_type_detail = response[i].leave_type_detail;
                        let date_leave_start = response[i].date_leave_start;
                        let date_leave_to = response[i].date_leave_to;
                        let time_leave_start = response[i].time_leave_start;
                        let time_leave_to = response[i].time_leave_to;
                        let leave_before = response[i].leave_before;
                        let remark = response[i].remark;
                        let status = response[i].status;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#doc_id').val(doc_id);
                        $('#doc_date').val(doc_date);
                        $('#emp_id').val(emp_id);
                        $('#full_name').val(full_name);
                        $('#leave_type_id').val(leave_type_id);
                        $('#leave_type_detail').val(leave_type_detail);
                        $('#date_leave_start').val(date_leave_start);
                        $('#date_leave_to').val(date_leave_to);
                        $('#time_leave_start').val(time_leave_start);
                        $('#time_leave_to').val(time_leave_to);
                        $('#leave_before').val(leave_before);
                        $('#remark').val(remark);
                        $('#status').val(status);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action').val('UPDATE');
                        $('#save').val('Save');
                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script>

    <!--script>
        $(document).ready(function () {
            $('#doc_date').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script-->

    <script>
        $(document).ready(function () {
            $('#date_leave_start').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <!--script>
        $('#date_leave_start').change(function () {
            if ($('#leave_type_id').val() !== '') {
                check_before_leave();
            } else {
                $('#date_leave_start').val('');
                alertify.error("กรุณาเลือกประเภทการลา");
            }
        });

    </script-->

    <script>
        function check_before_leave() {

            let leave_type_id = $('#leave_type_id').val();

            let formData = {action: "SEARCH_DATA", leave_type_id: leave_type_id};
            $.ajax({
                type: "POST",
                url: 'model/manage_ot_request_document_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let leave_before = response[i].leave_before;
                        $('#leave_before').val(leave_before);
                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });

        }
    </script>

    <script>
        $(document).ready(function () {
            $('#date_leave_to').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $('#time_leave_start').on('change', function () {
                chkTime($(this).val());
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#time_leave_to').on('change', function () {
                chkTime($(this).val());
            });
        });
    </script>

    <script>

        function chkTime(TimeInput) {
            let timeFormat = /^([01]\d|2[0-3]):([0-5]\d)$/; // Regular expression for 24-hour HH:MM format
            if (timeFormat.test(TimeInput)) {
                $(this).removeClass('invalid');
                return true;
            } else {
                $(this).addClass('invalid');
                alertify.error("ป้อนเวลาตามรูปแบบ ชั่วโมง:นาที เท่่านั้น");
                return false;
            }
        }

    </script>

    <style>
        .invalid {
            border: 1px solid red;
        }
    </style>

    </body>
    </html>

<?php } ?>