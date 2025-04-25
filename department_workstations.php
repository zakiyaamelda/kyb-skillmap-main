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
    $dept_id = $_REQUEST['q'];

    $q_res = $conn->query("SELECT dept_name FROM department WHERE id = ".$dept_id);
	$num_results = $q_res->num_rows;
    $row = $q_res->fetch_assoc();
    $current_dept = $row['dept_name'];
?>

<?php 
if ($num_results > 0)
	include("includes/content/dept-page.php");
else include("includes/content/not-found-page.php");
?>

</body>
</html>
