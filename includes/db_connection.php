<?php
// db_connection.php
include_once('db_config_.php'); // Gunakan include_once agar tidak di-load lebih dari sekali

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $db_name1, $db_port);
$conn1 = new mysqli($servername, $username, $password, $db_name2, $db_port);
$conn2 = new mysqli($servername, $username, $password, $db_name3, $db_port); // Jika diperlukan

// Cek koneksi
if ($conn->connect_error) {
    die("Connection to $db_name1 failed: " . $conn->connect_error);
}
if ($conn1->connect_error) {
    die("Connection to $db_name2 failed: " . $conn1->connect_error);
}
if ($conn2->connect_error) {
    die("Connection to $db_name3 failed: " . $conn2->connect_error);
}

// Pastikan fungsi tidak dideklarasikan dua kali
if (!function_exists('console_log')) {
    function console_log($message) {
        $escaped_message = addslashes($message); // Escape karakter spesial
        echo "<script>console.log('" . $escaped_message . "');</script>";
    }
}

$conn->select_db($db_name1);
$conn1->select_db($db_name2);
$conn2->select_db($db_name3);
?>
