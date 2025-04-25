<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Mulai buffer output
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi input
    $quality_name = trim($_POST['quality_name']);
    $workstation_id = trim($_POST['workstation_id']);
    $min_skill = trim($_POST['min_skill']);
    $npk = trim($_POST['npk']); 

    // Validasi input tidak boleh kosong
    if (empty($quality_name) || empty($workstation_id) || empty($min_skill) || empty($npk)) {
        die("Error: Semua input harus diisi!");
    }

    // Gunakan Prepared Statement untuk keamanan
    $sql = "INSERT INTO quality (name, workstation_id, min_skill) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $quality_name, $workstation_id, $min_skill);

    if ($stmt->execute()) {
        ob_end_clean(); // Hapus semua output sebelum redirect
        header("Location: ../preview_member.php?q=" . urlencode($npk)); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Tutup statement
}
?>
