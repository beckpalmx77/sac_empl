<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Ajax Example</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        body, h1, h2, h3, h4, h5, h6 {
            font-family: 'Prompt', sans-serif !important;
        }
    </style>

    <!--style>
        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid #000; /* กำหนดสีและความหนาของเส้นกรอบ */
        }
        table.dataTable {
            border-collapse: collapse; /* ทำให้เส้นกรอบไม่ซ้อนกัน */
        }
    </style-->

    <style>
        .custom-card {
            border: 2px solid #000; /* กำหนดสีและความหนาของเส้นกรอบ */
            border-radius: 10px; /* กำหนดความโค้งของมุม */
        }
    </style>

</head>
<body>
<div class="card">
    <div class="card-body">
        <div class="container-fluid" id="container-wrapper">
            <table id="example" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <!-- Add more columns as needed -->
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "lengthMenu": [[7, 10, 20, 50, 100], [7, 10, 20, 50, 100]],
            "language": {
                search: 'ค้นหา', lengthMenu: 'แสดง _MENU_ รายการ',
                info: 'หน้าที่ _PAGE_ จาก _PAGES_',
                infoEmpty: 'ไม่มีข้อมูล',
                zeroRecords: "ไม่มีข้อมูลตามเงื่อนไข",
                infoFiltered: '(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)',
                responsive: true,
                paginate: {
                    previous: 'ก่อนหน้า',
                    last: 'สุดท้าย',
                    next: 'ต่อไป'
                }
            },
            "ajax": {
                "url": "fetch_data.php",
                "type": "GET"
            },
            "columns": [
                {"data": "emp_id"},
                {"data": "first_name"},
                {"data": "last_name"}
                // Add more columns as needed
            ]
        });
    });
</script>
</body>
</html>
