<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");
error_reporting(0);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo '<script>alert("'.$_FILES["ap-form-photo"]["tmp_name"].'");</script>';
    $errMsg = "";
    
    if (empty($_GET["q"])) {
        $errMsg .= "NPK is required\\n";
    } else {
        $npk = $_GET["q"];
    }

    if ($errMsg != "") {
        echo "<script>alert('".$errMsg."');</script>";
        if(isset($_SERVER["HTTP_REFERER"])) {
            echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
        } else {
            echo "<script>window.location.replace('../index.php');</script>";
        }
    }

    $target_dir = "../img/profile_pictures/";
    $target_file = $target_dir . $npk .'.jpg';
    $uploadOk = 1;

    if (file_exists($target_file))
        unlink($target_file);

    if (!move_uploaded_file($_FILES["ap-form-photo"]["tmp_name"], $target_file)) {
        $errMsg .= "Sorry, there was an error uploading your file.\\n".$_FILES["ap-form-photo"]["tmp_name"];
    } else {
        clearstatcache();
    }

    if ($errMsg == '') 
    {
        try {
            echo "<script>window.location.replace('../preview_member.php?q=$npk');</script>";  
        } catch(Exception $e) {
    
        }
    }
    echo "<script>alert('".$errMsg."');</script>";
    if(isset($_SERVER["HTTP_REFERER"])) {
    echo "<script>window.location.replace('".$_SERVER["HTTP_REFERER"]."');</script>";
    } else {
    echo "<script>window.location.replace('index.php');</script>";
    }
}
?>
