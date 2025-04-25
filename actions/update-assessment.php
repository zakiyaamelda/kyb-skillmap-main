<?php 
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");

if(isset($_POST['update']))
{
    $scores = array();
    foreach ($mp_categories as $cat => $cat_name) {
        if (isset($_POST[$cat])) $scores[$cat] = $_POST[$cat];
        else $scores[$cat] = 1;
    }

    // print_r($scores);

    $sql_query=
        "SELECT npk, msk, kt, pssp, png, fivejq, kao
        FROM mp_scores WHERE npk = '".$_GET['q']."'";

    if ($result = $conn->query($sql_query)) {
        if ($result->num_rows > 0) {
            $q_files = 
                "SELECT mp_category, filename
                FROM mp_file_proof WHERE npk = '".$_GET['q']."'";

            $temp_files = array();
            if ($result_files = $conn->query($q_files)) {
                if ($result_files->num_rows > 0) {
                    while ($row = $result_files->fetch_assoc()) {
                        $temp_files[$row['mp_category']] = $row['filename'];
                    }
                }
            }

            $row = $result->fetch_assoc();
            foreach ($mp_categories as $cat => $cat_name) {
                if ($scores[$cat] <> $row[$cat]) {
                    // Hapus file terkait jika skor berubah
                    if (isset($temp_files[$cat])) {
                        $target_dir = "../files/";
                        $target_file = $target_dir . $temp_files[$cat];
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        $sql_query=
                            "DELETE FROM `mp_file_proof` " .
                            "WHERE npk = '".$_GET['q']."' " .
                            "AND mp_category = '".$cat."'";
                        $conn->query($sql_query);
                    }
                }
            }
        }
    }

    $sql_query=
        "INSERT INTO `mp_scores` 
        (npk, msk, kt, pssp, png, fivejq, kao)
        VALUES (
        '".$_GET['q']."',
        ".$scores['msk'].", 
        ".$scores['kt'].", 
        ".$scores['pssp'].", 
        ".$scores['png'].", 
        ".$scores['fivejq'].", 
        ".$scores['kao']." 
        ) 
        ON DUPLICATE KEY UPDATE 
        msk = ".$scores['msk'].",
        kt = ".$scores['kt'].",
        pssp = ".$scores['pssp'].",
        png = ".$scores['png'].",
        fivejq = ".$scores['fivejq'].",
        kao = ".$scores['kao']."";

    $conn->query($sql_query);

    $query_string = '?q='.$_GET['q'];
    header("Location: ../preview_member.php$query_string");
} 
else if (isset($_POST['delete'])) {
    $npk = $_GET['q'];

    // Hanya menghapus data dari mp_scores, bukan karyawan
    $sql_query = "DELETE FROM `mp_scores` WHERE npk = '".$npk."'";
    $conn->query($sql_query);

    // Hapus file bukti dari mp_file_proof jika ada
    $target_dir = "../files/";
    $q_files = "SELECT filename FROM mp_file_proof WHERE npk = '".$npk."'";
    if ($result_files = $conn->query($q_files)) {
        while ($row = $result_files->fetch_assoc()) {
            $target_file = $target_dir . $row['filename'];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
    }
    
    // Hapus data dari mp_file_proof setelah file dihapus
    $sql_query = "DELETE FROM `mp_file_proof` WHERE npk = '".$npk."'";
    $conn->query($sql_query);

    // Redirect ke preview_member.php setelah penghapusan berhasil
    header("Location: ../preview_member.php?q=".$npk);
    exit();
}
?>
