<?php
// Cek apakah $CURRENT_PAGE sudah didefinisikan
if (!isset($CURRENT_PAGE)) {
    $CURRENT_PAGE = ""; // Berikan nilai default
}
?>

<title><?php print $PAGE_TITLE;?></title>

<?php if ($CURRENT_PAGE == "Index") { ?>
	<meta name="description" content="" />
	<meta name="keywords" content="" /> 
<?php } ?>

<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-datepicker.standalone.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="style/style.css">
<script src="./js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="./chartjs/dist/chart.umd.js"></script>
<link rel="icon" type="image/ico" href="favicon.ico" />
<script src="bootstrap/js/sweetalert2.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
