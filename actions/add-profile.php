<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");
error_reporting(0);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errMsg = "";
    $name = $npk = $dept = $role = "";

    if (empty($_POST["name"])) {
        $errMsg .= "Name is required\\n";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["npk"])) {
        $errMsg .= "NPK is required\\n";
    } else {
        $npk = $_POST["npk"];
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

    $ws = $_POST["ws"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("SELECT npk FROM karyawan WHERE npk = ?");
    $stmt->bind_param("s", $npk);
    $stmt->execute();
    $stmt->bind_result($res);
    $stmt->fetch();
    $stmt->close();

    if (isset($res) === false) {
        if (!move_uploaded_file($_FILES["ap-form-photo"]["tmp_name"], $target_file)) {
            $errMsg .= "Sorry, there was an error uploading your file.\\n";
        }
    } else {
        $errMsg .= "NPK already exists.\\n";
    }

    if ($errMsg == '') 
    {
        $ws = trim($ws);
        $ws_arr = explode(" ", $ws);
        try {
            $stmt = $conn->prepare("INSERT INTO karyawan (npk, name, role, date_joined) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $npk, $name, $role, $_POST["join-date"]);
            $stmt->execute();
            $stmt->close();

            foreach($ws_arr as $ws)
            {
                $stmt = $conn->prepare("INSERT INTO karyawan_workstation (npk, workstation_id) VALUES (?, ?)");
                $stmt->bind_param("si", $npk, $ws);
                $stmt->execute();
                $stmt->close();
            }
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
