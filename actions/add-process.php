<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Mulai buffer output
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dengan sanitasi awal
    $process_name = trim($_POST['process_name']);
    $workstation_id = trim($_POST['workstation_id']);
    $min_skill = trim($_POST['min_skill']);
    $npk = trim($_POST['npk']); 

    // Validasi input
    if (empty($process_name) || empty($workstation_id) || empty($min_skill) || empty($npk)) {
        die("Semua field harus diisi!");
    }

    // Gunakan prepared statement untuk menghindari SQL Injection
    $sql = "INSERT INTO process (name, workstation_id, min_skill) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssi", $process_name, $workstation_id, $min_skill); // "ssi" -> string, string, integer
        if ($stmt->execute()) {
            ob_end_clean(); // Hapus semua output sebelum redirect
            header("Location: ../preview_member.php?q=" . urlencode($npk)); 
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error dalam menyiapkan statement: " . $conn->error;
    }
}
?>
