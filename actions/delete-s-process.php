<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Mulai buffer output
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_process_id = $_POST['s_process_id'];
    $npk = $_POST['npk']; // Ambil NPK dari form

    // Hapus dari tabel terkait
    $conn->query("DELETE FROM s_process_certification WHERE s_process_id = $s_process_id");
    $conn->query("DELETE FROM s_process_workstation WHERE id_s_process = $s_process_id");
    
    // Hapus process
    $stmt = $conn->prepare("DELETE FROM s_process WHERE id = ?");
    $stmt->bind_param("i", $s_process_id);
    
    if ($stmt->execute()) {
        ob_end_clean(); // Hapus semua output
        // Redirect ke preview-member.php dengan parameter NPK
        header("Location: ../preview_member.php?q=" . urlencode($npk)); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>