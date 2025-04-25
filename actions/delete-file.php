<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");
error_reporting(0);

$file_id = $_GET['q'];

$q_res = $conn->query("SELECT filename FROM mp_file_proof WHERE id = '".$file_id."'");
$row = $q_res->fetch_assoc();
$filename = $row['filename'];
if (file_exists("../files/".$filename))
    unlink("../files/".$filename);

$conn->query("DELETE FROM mp_file_proof WHERE id = '".$file_id."'");

if(isset($_SERVER["HTTP_REFERER"])) {
    echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
} else {
    echo "<script>window.location.replace('index.php');</script>";
}
?>