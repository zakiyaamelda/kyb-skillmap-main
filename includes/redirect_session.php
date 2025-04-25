<?php
include('a_config.php');
session_start();
 
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
}
?>