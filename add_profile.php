<?php include("includes/db_connection.php"); ?>
<?php include("includes/a_config.php");?>
<?php include("includes/redirect_session.php");?>
<!DOCTYPE html>
<html>
<head>
	<?php include("includes/head-tag-contents.php");?>
	<script src="js/search-npk.js"></script>
	<script src="js/add-profile-form.js"></script>
</head>
<body>

<?php
	$dept_id = $_REQUEST['dept'];

	$q_res = $conn->query("SELECT dept_name FROM department WHERE id = ".$dept_id);
	$num_results = $q_res->num_rows;
	$row = $q_res->fetch_assoc();
	$current_dept = $row['dept_name'];
?>

<?php include("includes/design-top.php");?>
<?php include("includes/content/add-profile-page.php");?>

</body>
</html>
