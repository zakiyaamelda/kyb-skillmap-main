<?php 
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");

if(isset($_POST['update']))
{
    $certifications = trim($_POST['s-certifications']);
    $q_arr = explode(" ", $certifications);

    $conn->query(
        "DELETE FROM s_process_certification 
        WHERE id IN 
            (SELECT s_process_certification.id FROM `s_process_certification` 
            LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = s_process_certification.npk
            LEFT JOIN sub_workstations ON sub_workstations.id = karyawan_workstation.workstation_id
            LEFT JOIN s_process_workstation ON s_process_workstation.id_s_process = s_process_certification.s_process_id
            WHERE s_process_workstation.workstation_id = sub_workstations.workstation_id
            AND s_process_certification.npk = '{$_GET['q']}')"
        );

    foreach ($q_arr as $q) {
        $conn->query("
            INSERT INTO `s_process_certification` 
            (npk, s_process_id)
            VALUES (
            '".$_GET['q']."',
            ".strval($q)."
            )
        ");
    }

    $query_string = '?q='.$_GET['q'];
    header("Location: ../preview_member.php$query_string");
}
?>
