<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Start output buffering
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dengan sanitasi awal
    $process_id = trim($_POST['process_id']);
    $npk = trim($_POST['npk']);

    // Validasi input
    if (empty($process_id) || empty($npk)) {
        die("Error: process_id dan npk tidak boleh kosong!");
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus data dari tabel qualifications
        $delete_qualifications_sql = "DELETE FROM qualifications WHERE process_id = ?";
        $stmt1 = $conn->prepare($delete_qualifications_sql);
        $stmt1->bind_param("i", $process_id);
        $stmt1->execute();
        $stmt1->close();

        // Hapus data dari tabel process
        $delete_process_sql = "DELETE FROM process WHERE id = ?";
        $stmt2 = $conn->prepare($delete_process_sql);
        $stmt2->bind_param("i", $process_id);
        $stmt2->execute();
        $stmt2->close();

        // Jika semuanya berhasil, commit transaksi
        $conn->commit();

        ob_end_clean(); // Bersihkan output buffer sebelum redirect
        header("Location: ../preview_member.php?q=" . urlencode($npk));
        exit();
    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
