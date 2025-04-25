<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");
// error_reporting(0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errMsg = "";
    $name = $npk = $dept = $role = $ws = "";

    if (empty($_POST["npk"])) {
        $errMsg .= "NPK is required\\n";
    } else {
        $npk = $_POST["npk"];
    }

    if ($errMsg != "") {
        echo "<script>alert('".$errMsg."');</script>";
        if(isset($_SERVER["HTTP_REFERER"])) {
            echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
        } else {
            echo "<script>window.location.replace('../index.php');</script>";
        }
    }

    if (empty($_POST["ws"])) {
        $errMsg .= "Workstation has not been selected\\n";
    } else {
        $ws = $_POST["ws"];
    }
    $role = $_POST["role"];

    $stmt = $conn->prepare("SELECT npk FROM karyawan WHERE npk = ?");
    $stmt->bind_param("s", $npk);
    $stmt->execute();
    $stmt->bind_result($res);
    $stmt->fetch();
    $stmt->close();

    if (isset($res) === false) {
        $errMsg .= "NPK does not exists.\\n";   
    }

    if ($errMsg == '') {
        $ws = trim($ws);
        $ws_arr = explode(" ", $ws);
    
        try {
            // Update role karyawan
            $stmt = $conn->prepare("UPDATE karyawan SET role = ? WHERE npk = ?");
            $stmt->bind_param("is", $role, $npk);
            $stmt->execute();
            $stmt->close();
    
            // Ambil workstation yang saat ini terdaftar sebelum update
            $current_ws = array();
            $result = $conn->query("SELECT workstation_id FROM karyawan_workstation WHERE npk = '$npk'");
            while ($row = $result->fetch_assoc()) {
                $current_ws[] = $row['workstation_id'];
            }
    
            // ========== TAMBAHKAN KODE INI ========== //
            // Pastikan semua workstation saat ini memiliki entri di relocate_history
            foreach ($current_ws as $sws_id) {
                // Cek apakah sudah ada entri aktif
                $check = $conn->query("SELECT id FROM relocate_history 
                                     WHERE npk = '$npk' 
                                     AND subworkstation_id = $sws_id 
                                     AND end_time IS NULL");
                
                // Jika tidak ada entri aktif, buat entri baru dengan start_time = waktu saat ini
                if ($check->num_rows == 0) {
                    $conn->query("INSERT INTO relocate_history (npk, subworkstation_id, start_time)
                                VALUES ('$npk', $sws_id, NOW())");
                }
            }
            // ========== BATAS KODE TAMBAHAN ========== //
    
            // Bandingkan dengan workstation baru
            $new_ws = $ws_arr;
            $removed_ws = array_diff($current_ws, $new_ws);
            $added_ws = array_diff($new_ws, $current_ws);
    
            // Update end_time untuk workstation yang dihapus
            foreach ($removed_ws as $sws_id) {
                $stmt = $conn->prepare("UPDATE relocate_history SET end_time = NOW() 
                                      WHERE npk = ? 
                                      AND subworkstation_id = ? 
                                      AND end_time IS NULL");
                $stmt->bind_param("si", $npk, $sws_id);
                $stmt->execute();
                $stmt->close();
            }

            // Tambahkan entri baru untuk workstation yang ditambahkan
            foreach ($added_ws as $sws_id) {
                $stmt = $conn->prepare("INSERT INTO relocate_history (npk, subworkstation_id, start_time) VALUES (?, ?, NOW())");
                $stmt->bind_param("si", $npk, $sws_id);
                $stmt->execute();
                $stmt->close();
            }

            // Hapus workstation yang tidak lagi digunakan
            $subquery = "SELECT ";
            if (count($ws_arr) == 0) {
                $subquery .= NULL;
            } else {
                $subquery .= join(" UNION SELECT ", $ws_arr);
            }

            $stmt = $conn->prepare("DELETE FROM karyawan_workstation WHERE npk = ? AND workstation_id NOT IN ( $subquery )");
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            $stmt->close();

            // Hapus qualifications yang terkait dengan workstation yang dihapus
            $stmt = $conn->prepare("DELETE FROM qualifications WHERE npk = ?");
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            $stmt->close();

            // Tambahkan workstation baru
            foreach($ws_arr as $ws) {
                $stmt = $conn->prepare("INSERT INTO karyawan_workstation (npk, workstation_id) VALUES (?, ?)");
                $stmt->bind_param("si", $npk, $ws);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect ke halaman preview member
            echo "<script>window.location.replace('../preview_member.php?q=$npk');</script>";  
        } catch(Exception $e) {
            $errMsg .= "Error: " . $e->getMessage();
        }
    }

    // Tampilkan pesan error jika ada
    echo "<script>alert('".$errMsg."');</script>";
    if(isset($_SERVER["HTTP_REFERER"])) {
        echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
    } else {
        echo "<script>window.location.replace('index.php');</script>";
    }
}
?>