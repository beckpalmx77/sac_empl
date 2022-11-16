<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
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
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ประเภท</th>
                                                    <th>รายละเอียดการแจ้ง</th>
                                                    <th>สถานะ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ประเภท</th>
                                                    <th>รายละเอียดการแจ้ง</th>
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
                                                                    <input type="job_id" class="form-control"
                                                                           id="job_id" name="job_id"
                                                                           readonly="true"
                                                                           placeholder="สร้างอัตโนมัติ">
                                                                </div>

                                                                <input type="hidden" class="form-control"
                                                                       id="department" name="department"
                                                                       readonly="true"
                                                                       value="<?php echo $_SESSION['department_id'] ?>"
                                                                       placeholder="department">

                                                                <div class="form-group row">
                                                                    <div class="col-sm-3">
                                                                        <label for="job_date"
                                                                               class="control-label">วันที่เอกสาร</label>
                                                                        <i class="fa fa-calendar"
                                                                           aria-hidden="true"></i>
                                                                        <input type="text" class="form-control"
                                                                               id="job_date"
                                                                               name="job_date"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="วันที่เอกสาร">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <input type="hidden" class="form-control"
                                                                           id="device_group_id"
                                                                           name="device_group_id">
                                                                    <div class="col-sm-10">
                                                                        <label for="device_group_id"
                                                                               class="control-label">ประเภท</label>
                                                                        <input type="text" class="form-control"
                                                                               id="device_desc"
                                                                               name="device_desc"
                                                                               required="required"
                                                                               readonly="true"
                                                                               placeholder="ประเภท">
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <label for="quantity"
                                                                               class="control-label">เลือก</label>
                                                                        <a data-toggle="modal"
                                                                           href="#SearchDeviceModal"
                                                                           class="btn btn-primary">
                                                                            Click <i class="fa fa-search"
                                                                                     aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="text" class="control-label">หมายเลขเครื่อง
                                                                        / IP Address</label>
                                                                    <input type="text" class="form-control"
                                                                           id="machine_no" name="machine_no"
                                                                           placeholder="หมายเลขเครื่อง / IP Address">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="job_problem_detail"
                                                                           class="control-label">รายละเอียดปัญหา</label>
                                                                    <textarea class="form-control"
                                                                              id="job_problem_detail"
                                                                              name="job_problem_detail"
                                                                              rows="3"></textarea>
                                                                </div>

                                                                <?php if ($_SESSION['account_type'] === 'admin') { ?>
                                                                    <div class="form-group">
                                                                        <label for="job_solve_detail"
                                                                               class="control-label">การแก้ไข</label>
                                                                        <textarea class="form-control"
                                                                                  id="job_solve_detail"
                                                                                  name="job_solve_detail"
                                                                                  rows="3"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="status"
                                                                               class="control-label">Status</label>
                                                                        N= เปิดใบแจ้ง W = กำลังดำเนินการ , P = รออะไหล่
                                                                        , Y ดำเนินการเสร็จสิ้น
                                                                        <select id="status" name="status"
                                                                                class="form-control"
                                                                                data-live-search="true"
                                                                                title="Please select">
                                                                            <option>N</option>
                                                                            <option>W</option>
                                                                            <option>P</option>
                                                                            <option>Y</option>
                                                                        </select>
                                                                    </div>
                                                                <?php } else { ?>

                                                                    <div class="form-group">
                                                                        <label for="job_solve_detail"
                                                                               class="control-label">การแก้ไข</label>
                                                                        <textarea class="form-control"
                                                                                  id="job_solve_detail"
                                                                                  name="job_solve_detail"
                                                                                  readonly="true"
                                                                                  rows="3"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="status"
                                                                               class="control-label">Status</label>
                                                                        N= เปิดใบแจ้ง W = กำลังดำเนินการ , P = รออะไหล่
                                                                        , Y ดำเนินการเสร็จสิ้น
                                                                        <select id="status" name="status"
                                                                                class="form-control"
                                                                                data-live-search="true"
                                                                                readonly="true"
                                                                                title="Please select">
                                                                            <option>N</option>
                                                                            <option>W</option>
                                                                            <option>P</option>
                                                                            <option>Y</option>
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


                                        <div class="modal fade" id="SearchDeviceModal">
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
                                                                   id="TableDeviceList"
                                                                   width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th>รหัสประเภท</th>
                                                                    <th>รายละเอียด</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tfoot>
                                                                <tr>
                                                                    <th>รหัสประเภท</th>
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

    <script src="js/modal/show_device_modal.js"></script>

    <!-- Page level plugins -->

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/-->

    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

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

        $("#device_desc").blur(function () {
            let method = $('#action').val();
            if (method === "ADD") {
                let job_date = $('#job_date').val();
                let device_desc = $('#device_desc').val();
                let formData = {action: "SEARCH", job_date: job_date, device_desc: device_desc};
                $.ajax({
                    url: 'model/manage_job_process.php',
                    method: "POST",
                    data: formData,
                    success: function (data) {
                        if (data == 2) {
                            alert("Duplicate มีข้อมูลนี้แล้วในระบบ กรุณาตรวจสอบ");
                        }
                    }
                })
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            let formData = {action: "GET_JOB", sub_action: "GET_MASTER"};
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
                'ajax': {
                    'url': 'model/manage_job_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'job_id'},
                    {data: 'job_date'},
                    {data: 'device_desc'},
                    {data: 'job_problem_detail'},
                    {data: 'status'},
                    {data: 'update'}
                ]
            });

            <!-- *** FOR SUBMIT FORM *** -->
            $("#recordModal").on('submit', '#recordForm', function (event) {
                event.preventDefault();
                $('#save').attr('disabled', 'disabled');
                let formData = $(this).serialize();
                $.ajax({
                    url: 'model/manage_job_process.php',
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
            });
            <!-- *** FOR SUBMIT FORM *** -->
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#btnAdd").click(function () {
                $('#recordModal').modal('show');
                $('#id').val("");
                $('#job_id').val("");
                $('#job_date').val("");
                $('#job_problem_detail').val("");
                $('#device_desc').val("");
                $('#job_solve_detail').val("");
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
                url: 'model/manage_job_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let job_id = response[i].job_id;
                        let job_date = response[i].job_date;
                        let device_group_id = response[i].device_group_id;
                        let device_desc = response[i].device_desc;
                        let machine_no = response[i].machine_no;
                        let job_problem_detail = response[i].job_problem_detail;
                        let job_solve_detail = response[i].job_solve_detail;
                        let status = response[i].status;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#job_id').val(job_id);
                        $('#job_date').val(job_date);
                        $('#device_group_id').val(device_group_id);
                        $('#device_desc').val(device_desc);
                        $('#machine_no').val(machine_no);
                        $('#job_problem_detail').val(job_problem_detail);
                        $('#job_solve_detail').val(job_solve_detail);
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

    <script>
        $(document).ready(function () {
            $('#job_date').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>


    </body>
    </html>

<?php } ?>