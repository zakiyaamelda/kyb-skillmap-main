<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npk = $_GET['q'];

    $path = $_FILES['mp-file-input']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    $target_dir = "../files/";
    $filename = date('YmdHis').$npk.'.'.$ext;
    $target_file = $target_dir . $filename;

    if (isset($_POST['mp-file-name']) && $_POST['mp-file-name'] != ""){
        $file_display_name = $_POST['mp-file-name'].'.'.$ext;
    } else {
        $file_display_name = $filename;
    }

    if (!move_uploaded_file($_FILES["mp-file-input"]["tmp_name"], $target_file)) {
        echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file.'); window.history.back();</script>";
        exit;
    }

    $conn->query(
        "INSERT INTO mp_file_proof (npk, filename, name, description) 
        VALUES ('".$npk."', '".$filename."', '".$file_display_name."', '".$_POST['mp-file-desc']."')"
    );

    if(isset($_SERVER["HTTP_REFERER"])) {
        echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
    } else {
        echo "<script>window.location.replace('index.php');</script>";
    }
}
?>
