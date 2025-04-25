<?php
session_start(); // Tambahkan ini di awal script
if (!isset($_SESSION['dept']) || $_SESSION['dept'] != 'APROD') {
    die("Akses ditolak!");
}
ob_start(); // Mulai buffer output
include("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dengan sanitasi awal
    $quality_id = trim($_POST['quality_id']);
    $npk = trim($_POST['npk']);

    // Validasi input
    if (empty($quality_id) || empty($npk)) {
        die("Error: quality_id dan npk tidak boleh kosong!");
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus data dari tabel qualifications_quality
        $delete_qualifications_sql = "DELETE FROM qualifications_quality WHERE quality_id = ?";
        $stmt1 = $conn->prepare($delete_qualifications_sql);
        $stmt1->bind_param("i", $quality_id);
        $stmt1->execute();
        $stmt1->close();

        // Hapus data dari tabel quality
        $delete_quality_sql = "DELETE FROM quality WHERE id = ?";
        $stmt2 = $conn->prepare($delete_quality_sql);
        $stmt2->bind_param("i", $quality_id);
        $stmt2->execute();
        $stmt2->close();

        // Jika semua query berhasil, commit transaksi
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
