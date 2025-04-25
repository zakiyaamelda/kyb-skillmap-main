<?php include("includes/db_connection.php"); ?>
<?php include("includes/a_config.php");?>
<?php include("includes/redirect_session.php");?>
<!DOCTYPE html>
<html>
<head>
	<?php include("includes/head-tag-contents.php");?>
</head>
<body>

<?php include("includes/design-top.php");?>

<?php
    $ws_id = $_REQUEST['q'];

    $q_res = $conn->query("SELECT name FROM workstations WHERE id = ".$ws_id);
	$num_results = $q_res->num_rows;
    $current_ws = $q_res->fetch_assoc();
    $current_ws = $current_ws['name'];

    $sub_q_res = $conn->query("SELECT id, name FROM sub_workstations WHERE workstation_id = ".$ws_id);
    $sub_num_results = $sub_q_res->num_rows;
    if ($sub_num_results == 1) {
        $sub_ws = $sub_q_res->fetch_assoc();
        echo "<script>window.location.replace('sub_workstation_members.php?q=".$sub_ws['id']."');</script>";
    }
?>

<?php
if ($num_results > 0)
	include("includes/content/ws-page.php");
else include("includes/content/not-found-page.php");
?>

</body>
</html>
