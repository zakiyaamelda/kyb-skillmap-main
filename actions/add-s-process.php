<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Mulai buffer output
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_process_name = $_POST['s_process_name'];
    $workstation_id = $_POST['workstation_id'];
    $npk = $_POST['npk']; // Ambil NPK dari form

    // Gunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO s_process (name) VALUES (?)");
    $stmt->bind_param("s", $s_process_name);
    
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        
        // Link ke workstation
        $stmt_link = $conn->prepare("INSERT INTO s_process_workstation (id_s_process, workstation_id) VALUES (?, ?)");
        $stmt_link->bind_param("ii", $last_id, $workstation_id);
        $stmt_link->execute();
        
        ob_end_clean(); // Hapus semua output
        // Redirect ke preview-member.php dengan parameter NPK
        header("Location: ../preview_member.php?q=" . urlencode($npk));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>