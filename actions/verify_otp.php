<?php
session_start();
include("../includes/a_config.php");
include("../includes/db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npk = $_POST['npk'];
    $otp = $_POST['otp'];

    // Cek OTP di tabel otp (termasuk expired_date dan use_date)
    $otp_stmt = $conn->prepare("SELECT no_otp, exp_date, use_date FROM otp WHERE npk = ?");
    $otp_stmt->bind_param("s", $npk);
    $otp_stmt->execute();
    $otp_stmt->bind_result($db_otp, $exp_date, $use_date);
    $otp_stmt->fetch();
    $otp_stmt->close();
    
    if ($db_otp) {
        // OTP valid, lanjutkan ke halaman utama
        echo "<script>window.location.replace('../index.php');</script>";
    } else {
        // OTP tidak valid
        echo "<script>alert('OTP tidak valid.')</script>";
        echo "<script>window.location.replace('../login_page.php');</script>";
    }
}
