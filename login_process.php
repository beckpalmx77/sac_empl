<?php
session_start();
error_reporting(0);
include('config/connect_db.php');
include('config/lang.php');


if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}


$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$remember = $_POST['remember'];

$sql = "SELECT iu.*,pm.dashboard_page as dashboard_page,em.work_time_id,wt.work_time_detail,wt.work_time_start,wt.work_time_stop
        ,wt.break_time_start,wt.break_time_stop,em.sex,em.start_work_date,em.dept_id  
        FROM ims_user iu
        left join ims_permission pm on pm.permission_id = iu.account_type        
        left join memployee em on em.emp_id = iu.emp_id
        left join mwork_time wt on wt.work_time_id = em.work_time_id        
        WHERE iu.user_id=:username ";
/*
$txt =  $sql;
$my_file = fopen("login_a.txt", "w") or die("Unable to open file!");
fwrite($my_file, $txt);
fclose($my_file);
*/

$query = $conn->prepare($sql);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() == 1) {
    foreach ($results as $result) {
        if (password_verify($_POST['password'], $result->password)) {
            $_SESSION['alogin'] = $result->user_id;
            $_SESSION['login_id'] = $result->id;
            $_SESSION['username'] = $result->email;
            $_SESSION['emp_id'] = $result->emp_id;
            $_SESSION['first_name'] = $result->first_name;
            $_SESSION['last_name'] = $result->last_name;
            $_SESSION['sex'] = $result->sex;
            $_SESSION['email'] = $result->email;
            $_SESSION['account_type'] = $result->account_type;
            $_SESSION['user_picture'] = $result->picture;
            $_SESSION['dept_id'] = $result->dept_id;
            $_SESSION['department_id'] = $result->department_id;
            $_SESSION['lang'] = $result->lang;
            $_SESSION['permission_price'] = $result->permission_price;
            $_SESSION['dashboard_page'] = $result->dashboard_page;
            $_SESSION['system_name'] = $system_name;
            $_SESSION['start_work_date'] = $result->start_work_date;
            $_SESSION['work_time_start'] = $result->work_time_start;
            $_SESSION['work_time_stop'] = $result->work_time_stop;
            $_SESSION['break_time_start'] = $result->break_time_start;
            $_SESSION['break_time_stop'] = $result->break_time_stop;
            $_SESSION['approve_permission'] = $result->approve_permission;
            $_SESSION['document_dept_cond'] = $result->document_dept_cond;

/*
            $txt =  $_SESSION['work_time_start'] . " | " . $_SESSION['work_time_stop'] . " | " . $_SESSION['dashboard_page'] . " | " . $_SESSION['dept_id'];
            $my_file = fopen("time.txt", "w") or die("Unable to open file!");
            fwrite($my_file, $txt);
            fclose($my_file);
*/


            if ($remember == "on") { // ถ้าติ๊กถูก Login ตลอดไป ให้ทำการสร้าง cookie
                setcookie("username", $_POST["username"], time() + (86400 * 10000), "/");
                setcookie("password", $_POST["password"], time() + (86400 * 10000), "/");
                setcookie("remember_chk", "check", time() + (86400 * 10000), "/");
            } else {
                /*
                setcookie("username", "");
                setcookie("password", "");
                setcookie("remember_chk", "");*/

                setcookie("username", $_POST["username"], time() + (86400 * 10000), "/");
                setcookie("password", $_POST["password"], time() + (86400 * 10000), "/");
                setcookie("remember_chk", "check", time() + (86400 * 10000), "/");
            }
            //echo $result->dashboard_page . ".php";
            echo $result->dashboard_page;

        } else {
            echo 0;
        }
    }
}