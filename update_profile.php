<?php include ("includes/db_connection.php"); ?>
<?php include ("includes/a_config.php"); ?>
<?php include ("includes/redirect_session.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include ("includes/head-tag-contents.php"); ?>
    <script src="js/search-npk.js"></script>
    <script src="js/edit-profile-form.js"></script>
</head>

<body>

    <?php
    $npk = $_REQUEST['npk'];


    $q_res = $conn->query(
        "SELECT 
            karyawan.npk,
            karyawan.name as name,
            karyawan.role as role_id,
            karyawan_workstation.workstation_id as ws_id,
            sub_workstations.id as sub_ws_id,
            department.id as dept_id
        FROM karyawan 
        LEFT JOIN karyawan_workstation on karyawan.npk = karyawan_workstation.npk
        LEFT JOIN sub_workstations on karyawan_workstation.workstation_id = sub_workstations.id
        LEFT JOIN workstations on sub_workstations.workstation_id = workstations.id
        LEFT JOIN department on workstations.dept_id = department.id
        WHERE karyawan.npk = '" . $npk . "'");
    $num_results = $q_res->num_rows;
    $row = $q_res->fetch_assoc();
    $karyawan = $row;
    ?>

    <?php include ("includes/design-top.php"); ?>
    <?php
    if ($num_results > 0)
        include ("includes/content/update-profile-page.php");
    else
        include ("includes/content/not-found-page.php");
    ?>

</body>

</html>