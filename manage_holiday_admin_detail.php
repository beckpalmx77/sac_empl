<?php
session_start();
error_reporting(0);
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    ?>

    <!DOCTYPE html>
    <html lang="th">

    <body id="page-top">
    <div id="wrapper">


        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><span id="title"></span></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><span id="main_menu"></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><span id="sub_menu"></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">

                                        <form method="post" id="MainrecordForm">
                                            <input type="hidden" class="form-control" id="KeyAddData" name="KeyAddData"
                                                   value="">
                                            <div class="modal-body">
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label for="emp_id"
                                                                   class="control-label">รหัสพนักงาน</label>
                                                            <input type="text" class="form-control"
                                                                   id="emp_id" name="emp_id"
                                                                   readonly="true"
                                                                   placeholder="รหัสพนักงาน">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="f_name"
                                                                   class="control-label">ชื่อ</label>
                                                            <input type="text" class="form-control"
                                                                   id="f_name"
                                                                   name="f_name"
                                                                   required="required"
                                                                   placeholder="ชื่อ">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="l_name"
                                                                   class="control-label">นามสกุล</label>
                                                            <input type="text" class="form-control"
                                                                   id="l_name"
                                                                   name="l_name"
                                                                   required="required"
                                                                   placeholder="นามสกุล">
                                                        </div>
                                                    </div>

                                        </form>

                                        <div class="col-md-12 col-md-offset-2">
                                            <table id='TableRecordList' class='display dataTable'>
                                                <thead>
                                                <tr>
                                                    <th>ปี</th>
                                                    <th>วันที่หยุด</th>
                                                    <th>เวลา</th>
                                                    <th>ประเภทวันหยุด</th>
                                                    <th>วันที่บันทึก</th>
                                                    <th>หมายเหตุ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>ปี</th>
                                                    <th>วันที่หยุด</th>
                                                    <th>เวลา</th>
                                                    <th>ประเภทวันหยุด</th>
                                                    <th>วันที่บันทึก</th>
                                                    <th>หมายเหตุ</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                        <div id="result"></div>

                                    </section>


                                </div>

                            </div>

                        </div>

                    </div>
                    <!--Row-->

                    <!-- Row -->

                </div>

                <!---Container Fluid-->

            </div>

            <?php
            include('includes/Modal-Logout.php');
            include('includes/Footer.php');
            ?>

        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Select2 -->
    <script src="vendor/select2/dist/js/select2.min.js"></script>


    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->

    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/Calculate.js"></script>

    <script src="js/modal/show_customer_modal.js"></script>
    <script src="js/modal/show_product_modal.js"></script>
    <script src="js/modal/show_unit_modal.js"></script>
    <!-- Javascript for this page -->

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/-->

    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

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
            $('#doc_date').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

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
            $("#btnClose").click(function () {
                if ($('#save_status').val() !== '') {
                    window.opener = self;
                    window.close();
                } else {
                    alertify.error("กรุณากด save อีกครั้ง");
                }
            });
        });
    </script>

    <script type="text/javascript">
        let queryString = new Array();
        $(function () {
            if (queryString.length == 0) {
                if (window.location.search.split('?').length > 1) {
                    let params = window.location.search.split('?')[1].split('&');
                    for (let i = 0; i < params.length; i++) {
                        let key = params[i].split('=')[0];
                        let value = decodeURIComponent(params[i].split('=')[1]);
                        queryString[key] = value;
                    }
                }
            }

            let data = "<b>" + queryString["title"] + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#action').val(queryString["action"]);

            $('#save_status').val("before");

            if (queryString["action"] === 'ADD') {
                let KeyData = generate_token(15);
                $('#KeyAddData').val(KeyData + ":" + Date.now());
                $('#save_status').val("add");
            }

            if (queryString["emp_id"] != null && queryString["f_name"] != null) {
                $('#emp_id').val(queryString["emp_id"]);
                $('#f_name').val(queryString["f_name"]);
                $('#l_name').val(queryString["l_name"]);

                Load_Data_Detail(queryString["emp_id"], "v_order_detail");

            }
        });
    </script>

    <script>
        function Load_Data_Detail(emp_id) {

            let formData = {
                action: "GET_HOLIDAY_DOCUMENT",
                sub_action: "GET_MASTER",
                emp_id: emp_id
            };
            let dataRecords = $('#TableRecordList').DataTable({
                "paging": false,
                "ordering": false,
                'info': false,
                "searching": false,
                'lengthMenu': [[8, 16, 32, 48, 100], [8, 16, 32, 48, 100]],
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
                    'url': 'model/manage_holiday_admin_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'doc_year'},
                    {data: 'date_leave_start'},
                    {data: 't_leave_start'},
                    {data: 'leave_type_detail'},
                    {data: 'doc_date'},
                    {data: 'remark'},
                    {data: 'update'},
                ]
            });
        }
    </script>


    <script>

        $("#TableOrderDetailList").on('click', '.update', function () {

            let rec_id = $(this).attr("id");

            if ($('#KeyAddData').val() !== '') {
                doc_no = $('#KeyAddData').val();
                table_name = "v_order_detail_temp";
            } else {
                doc_no = $('#doc_no').val();
                table_name = "v_order_detail";
            }

            let formData = {action: "GET_DATA", id: rec_id, doc_no: doc_no, table_name: table_name};
            $.ajax({
                type: "POST",
                url: 'model/manage_order_detail_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let product_id = response[i].product_id;
                        let id = response[i].id;
                        let name_t = response[i].name_t;
                        let doc_date = response[i].doc_date;
                        let quantity = response[i].quantity;
                        let price = response[i].price;
                        let total_price = response[i].total_price;
                        let unit_id = response[i].unit_id;
                        let unit_name = response[i].unit_name;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#detail_id').val(rec_id);
                        $('#doc_no_detail').val(doc_no);
                        $('#doc_date_detail').val(doc_date);
                        $('#product_id').val(product_id);
                        $('#name_t').val(name_t);
                        $('#quantity').val(quantity);
                        $('#price').val(price);
                        $('#total_price').val(total_price);
                        $('#unit_id').val(unit_id);
                        $('#unit_name').val(unit_name);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action_detail').val('UPDATE');
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

        $("#TableOrderDetailList").on('click', '.delete', function () {

            let rec_id = $(this).attr("id");

            if ($('#KeyAddData').val() !== '') {
                doc_no = $('#KeyAddData').val();
                table_name = "v_order_detail_temp";
            } else {
                doc_no = $('#doc_no').val();
                table_name = "v_order_detail";
            }

            let formData = {action: "GET_DATA", id: rec_id, doc_no: doc_no, table_name: table_name};
            $.ajax({
                type: "POST",
                url: 'model/manage_order_detail_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let product_id = response[i].product_id;
                        let id = response[i].id;
                        let name_t = response[i].name_t;
                        let quantity = response[i].quantity;
                        let price = response[i].price;
                        let total_price = response[i].total_price;
                        let unit_id = response[i].unit_id;
                        let unit_name = response[i].unit_name;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#detail_id').val(rec_id);
                        $('#doc_no_detail').val(doc_no);
                        $('#product_id').val(product_id);
                        $('#name_t').val(name_t);
                        $('#quantity').val(quantity);
                        $('#price').val(price);
                        $('#total_price').val(total_price);
                        $('#unit_id').val(unit_id);
                        $('#unit_name').val(unit_name);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Edit Record");
                        $('#action_detail').val('DELETE');
                        $('#save').val('Confirm Delete');
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
            $("#btnSave").click(function () {
                if ($('#doc_date').val() == '' || $('#f_name').val() == '') {
                    alertify.error("กรุณาป้อนวันที่ / ชื่อลูกค้า ");
                } else {
                    let formData = $('#MainrecordForm').serialize();
                    $.ajax({
                        url: 'model/manage_order_process.php',
                        method: "POST",
                        data: formData,
                        success: function (data) {

                            if ($('#KeyAddData').val() !== '') {
                                let KeyAddData = $('#KeyAddData').val();
                                Save_Detail(KeyAddData);
                            }
                            alertify.success(data);
                            window.opener.location.reload();
                            $('#save_status').val("save");
                        }
                    })

                }

            });
        });
    </script>

    <script>
        function Save_Detail(KeyAddData) {

            let formData = {action: "SAVE_DETAIL", KeyAddData: KeyAddData};
            $.ajax({
                url: 'model/manage_order_detail_process.php',
                method: "POST",
                data: formData,
                success: function (data) {
                    //alertify.success(data);
                }
            })

        }
    </script>

    <script>

        $("#recordModal").on('submit', '#recordForm', function (event) {
            event.preventDefault();
            let KeyAddData = $('#KeyAddData').val();
            if (KeyAddData !== '') {
                $('#KeyAddDetail').val(KeyAddData);
            }
            let doc_no_detail = $('#doc_no_detail').val();
            let formData = $(this).serialize();
            $.ajax({
                url: 'model/manage_order_detail_process.php',
                method: "POST",
                data: formData,
                success: function (data) {
                    //alertify.success(data);
                    $('#recordForm')[0].reset();
                    $('#recordModal').modal('hide');

                    $('#TableOrderDetailList').DataTable().clear().destroy();

                    if (KeyAddData !== '') {
                        Load_Data_Detail(KeyAddData, "v_order_detail_temp");
                    } else {
                        Load_Data_Detail(doc_no_detail, "v_order_detail");
                    }
                }
            })

        });

    </script>

    <script>

        $('#quantity,#price,#total_price').blur(function () {
            let total_price = new Calculate($('#quantity').val(), $('#price').val());
            $('#total_price').val(total_price.Multiple().toFixed(2));
        });

    </script>

    </body>

    </html>

<?php } ?>


