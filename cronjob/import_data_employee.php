<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_sqlserver.php");
include("../config/connect_db.php");

$current_year = date("Y") - 1;

$sql_sqlsvr = "SELECT EMP_KEY,EMP_INTL,EMPFILE.EMP_NAME,EMPFILE.EMP_SURNME,EMPFILE.EMP_GENDER,EMPFILE.EMP_EMAIL
,PERSONALINFO.PRS_SC_D,PAYROLLINFO.PRI_SALARY ,PERSONALINFO.PRS_DEPT
,PERSONALINFO.PRS_JBT,DEPTTAB.DEPT_THAIDESC,JOBTITLE.JBT_THAIDESC
,PAYROLLINFO.PRI_STATUS
FROM EMPFILE 
LEFT JOIN PAYROLLINFO ON PAYROLLINFO.PRI_EMP = EMPFILE.EMP_KEY
LEFT JOIN PERSONALINFO ON PERSONALINFO.PRS_EMP = EMPFILE.EMP_KEY
LEFT JOIN DEPTTAB ON DEPTTAB.DEPT_KEY = PERSONALINFO.PRS_DEPT
LEFT JOIN JOBTITLE ON JOBTITLE.JBT_KEY = PERSONALINFO.PRS_JBT
WHERE YEAR(PERSONALINFO.PRS_SC_D) >= " . $current_year
    . " ORDER BY PERSONALINFO.PRS_DEPT DESC  ";


//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_sqlsvr);
//fclose($myfile);


$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

    $contact_name = $result_sqlsvr["CT_INTL"] . " " . $result_sqlsvr["CT_NAME"] . " "
        . $result_sqlsvr["CT_SURNME"] . " - " . $result_sqlsvr["CT_JOBTITLE"];

    $sql_find = "SELECT * FROM memployee WHERE customer_id = '" . $result_sqlsvr["AR_CODE"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
      echo "dup";
    } else {

        echo "Customer : " . $result_sqlsvr["ARCAT_CODE"] . " | " . $result_sqlsvr["AR_CODE"] . " | " . $result_sqlsvr["AR_NAME"] . "\n\r";

        $sql = "INSERT INTO memployee(customer_id,tax_id,f_name,credit,phone,address,tumbol,amphure,province
        ,zipcode,ARCD_NAME,sale_name,contact_name)
        VALUES (:customer_id,:tax_id,:f_name,:credit,:phone,:address,:tumbol,:amphure,:province
        ,:zipcode,:ARCD_NAME,:sale_name,:contact_name)";
        $query = $conn->prepare($sql);
        $query->bindParam(':customer_id', $result_sqlsvr["AR_CODE"], PDO::PARAM_STR);
        $query->bindParam(':tax_id', $result_sqlsvr["ADDB_TAX_ID"], PDO::PARAM_STR);
        $query->bindParam(':f_name', $result_sqlsvr["AR_NAME"], PDO::PARAM_STR);
        $query->bindParam(':credit', $result_sqlsvr["ARS_CRE_LIM"], PDO::PARAM_STR);
        $query->bindParam(':phone', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':address', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':tumbol', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':amphure', $result_sqlsvr["ADDB_ADDB_3"], PDO::PARAM_STR);
        $query->bindParam(':province', $result_sqlsvr["ADDB_PROVINCE"], PDO::PARAM_STR);
        $query->bindParam(':zipcode', $result_sqlsvr["ADDB_POST"], PDO::PARAM_STR);
        $query->bindParam(':ARCD_NAME', $result_sqlsvr["ARCD_NAME"], PDO::PARAM_STR);
        $query->bindParam(':sale_name', $result_sqlsvr["SLMN_NAME"], PDO::PARAM_STR);
        $query->bindParam(':contact_name', $contact_name, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            echo "Save OK";
        } else {
            echo "Error";
        }
    }

}



