<?php
include ("../includes/db_connection.php");
include ("functions.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npk = $_POST['npk']; // NPK dari input user

    // Cek apakah NPK ada di tabel role menggunakan $conn (database sm_db)
    $stmt = $conn->prepare("SELECT npk FROM role WHERE npk = ?");
    $stmt->bind_param("s", $npk);
    $stmt->execute();
    $stmt->bind_result($res_npk);
    $stmt->fetch();
    $stmt->close();

    if (!$res_npk) {
        echo json_encode(["status" => "error", "message" => "NPK tidak ditemukan"]);
        exit();
    }

    // Cek nomor HP dari tabel isd menggunakan $conn2 (database isd)
    $stmt = $conn2->prepare("SELECT no_hp FROM hp WHERE npk = ?");
    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "Query error: " . $conn2->error]));
    }
    $stmt->bind_param("s", $npk);
    $stmt->execute();
    $stmt->bind_result($res_no_hp);
    $stmt->fetch();
    $stmt->close();

    if (!$res_no_hp) {
        echo json_encode(["status" => "error", "message" => "Nomor HP tidak ditemukan"]);
        exit();
    }

    // Generate OTP
    $otp = trim(generateOTP());
    $exp_date = date("Y-m-d H:i:s", strtotime("+5 minutes")); // Expire dalam 5 menit
    $send_date = date("Y-m-d H:i:s");

    // Cek apakah NPK sudah ada di tabel otp (gunakan $conn karena otp ada di sm_db)
    $check_stmt = $conn->prepare("SELECT OTP_ID FROM otp WHERE NPK = ?");
    if (!$check_stmt) {
        die(json_encode(["status" => "error", "message" => "Query error: " . $conn->error]));
    }
    $check_stmt->bind_param("s", $npk);
    $check_stmt->execute();
    $check_stmt->store_result();
    $otp_exists = $check_stmt->num_rows > 0;
    $check_stmt->close();

    if ($otp_exists) {
        // Update OTP jika NPK sudah ada
        $update_stmt = $conn->prepare("UPDATE otp SET NO_OTP = ?, EXP_DATE = ?, SEND = 1, SEND_DATE = ? WHERE NPK = ?");
        if (!$update_stmt) {
            die(json_encode(["status" => "error", "message" => "Query error: " . $conn->error]));
        }
        $update_stmt->bind_param("ssss", $otp, $exp_date, $send_date, $npk);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Insert OTP baru jika NPK belum ada
        $insert_stmt = $conn->prepare("INSERT INTO otp (NPK, NO_OTP, EXP_DATE, SEND, SEND_DATE) VALUES (?, ?, ?, 1, ?)");
        if (!$insert_stmt) {
            die(json_encode(["status" => "error", "message" => "Query error: " . $conn->error]));
        }
        $insert_stmt->bind_param("ssss", $npk, $otp, $exp_date, $send_date);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    // Simpan nomor HP di session untuk digunakan saat verifikasi
    $_SESSION['otp_npk'] = $npk;
    $_SESSION['otp_no_hp'] = $res_no_hp;

    // Simulasi pengiriman OTP ke nomor HP
    echo json_encode(["status" => "success", "message" => "OTP telah dikirim ke " . $res_no_hp]);
}
?>