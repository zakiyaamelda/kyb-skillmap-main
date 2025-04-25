<?php include ("includes/db_connection.php"); ?>
<?php include ("includes/a_config.php"); ?>
<?php include ("includes/redirect_session.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include ("includes/head-tag-contents.php"); ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include ("includes/design-top.php"); ?>

    <?php
    $ws_id = $_REQUEST['q'];

    // Query data workstation
    $q_res = $conn->query("SELECT workstation_id, name FROM sub_workstations WHERE id = " . $ws_id);
    $num_results = $q_res->num_rows;
    $current_ws = $q_res->fetch_assoc();

    if ($num_results > 0) {
        // Simpan nilai `name` ke variabel baru tanpa menimpa array
        $current_ws_name = $current_ws['name'];
        $sub_ws_id = $current_ws['workstation_id'];

        // Termasuk file konten jika data ditemukan
        include ("includes/content/sub-ws-page.php");
    } else {
        // Termasuk file konten jika data tidak ditemukan
        include ("includes/content/not-found-page.php");
    }
    ?>

</body>

</html>
