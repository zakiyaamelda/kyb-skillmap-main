<div class="header-bar pl-3 pr-3">
	<div class="dashboard-title-container d-flex flex-row align-items-center h-100">
		<a href="index.php" class="logo-container">
			<img src="img/logo-kyb.png" alt="Skill Map">
		</a>
		<p class="h1 ml-4">SKILL MAP MAN POWER DASHBOARD</p>
	</div>
	<div class='d-flex flex-row align-items-center'>
		<a href="index.php" class='font-default-light hover-overlay-light p-2'>
			Home
		</a>
		<a href="#" id="logoutButton" class='font-default-light hover-overlay-light p-2'>
			Logout
		</a>
		<div>
			<?php include ('includes/components/npk-search-bar.php'); ?>
		</div>
	</div>
</div>

<script>
	document.getElementById('logoutButton').addEventListener('click', function (event) {
		event.preventDefault(); // Mencegah aksi default dari link

		Swal.fire({
			title: 'Are you sure?',
			text: "You will be logged out!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, logout!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = 'actions/logout.php';
			}
		});
	});
</script>