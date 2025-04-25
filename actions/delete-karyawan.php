<?php
include("../includes/db_connection.php");

if (isset($_GET['npk'])) {
    $npk = $_GET['npk'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus data dari tabel `relocate_history`
        $stmt = $conn->prepare("DELETE FROM relocate_history WHERE npk = ?");
        $stmt->bind_param("s", $npk);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `karyawan_workstation`
        $stmt = $conn->prepare("DELETE FROM karyawan_workstation WHERE npk = ?");
        $stmt->bind_param("s", $npk);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `qualifications`
        $stmt = $conn->prepare("DELETE FROM qualifications WHERE npk = ?");
        $stmt->bind_param("s", $npk);
        $stmt->execute();
        $stmt->close();

        // Hapus data dari tabel `karyawan`
        $stmt = $conn->prepare("DELETE FROM karyawan WHERE npk = ?");
        $stmt->bind_param("s", $npk);
        $stmt->execute();
        $stmt->close();

        // Commit transaksi
        $conn->commit();

        // Redirect ke index.php dengan notifikasi sukses
        header("Location: ../index.php?success=1"); // <-- Ubah redirect ke index.php
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();

        // Redirect ke preview_member.php dengan notifikasi error
        header("Location: ../preview_member.php?q=$npk&error=1");
        exit();
    }
} else {
    // Jika tidak ada NPK, redirect ke index.php
    header("Location: ../index.php");
    exit();
}
?>