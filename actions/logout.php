<?php
    session_start();
    session_destroy();
    echo "<script>window.location.replace('../login_page.php');</script>";  
?>