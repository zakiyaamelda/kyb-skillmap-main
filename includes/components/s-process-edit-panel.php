<!-- <script src='js/edit-qualification-form.js'></script> -->
<?php
    $q_process = $conn->query("SELECT DISTINCT
        s_process.id as process_id,
        s_process.name as process_name,
        IF(
        s_process.id IN 
            (SELECT s_process_certification.s_process_id FROM s_process_certification WHERE s_process_certification.npk = '{$karyawan['npk']}')
            , 'yes', 'no') as qualified
        FROM s_process
        LEFT JOIN s_process_workstation ON s_process.id = s_process_workstation.id_s_process
        LEFT JOIN workstations ON workstations.id = s_process_workstation.workstation_id
        LEFT JOIN sub_workstations ON sub_workstations.workstation_id = workstations.id
        LEFT JOIN karyawan_workstation ON karyawan_workstation.workstation_id = sub_workstations.id
        WHERE karyawan_workstation.npk = '{$karyawan['npk']}' AND s_process.name != ''
        ORDER BY s_process.name ASC, sub_workstations.name ASC");

    echo "
    <form action='actions/update-s-certifications.php?q=".$karyawan['npk']."' method='post' class='w-100 h-75 d-block overflow-auto'>
    ";

    // TAMBAH TOMBOL DI BAWAH INI
    echo "<div class='d-flex justify-content-end mb-3'>";
    if ($_SESSION['dept'] == 'APROD') { // Sesuaikan dengan nama session role Anda
    echo "<button type='button' class='btn btn-primary mr-2' onclick='showAddSProcessPopup()'>Tambah S-Process</button>";
    echo "<button type='button' class='btn btn-danger' onclick='showDeleteSProcessPopup()'>Hapus S-Process</button>";
    }
    echo "</div>";

    echo "<div class='d-block h-100' style='overflow: auto'>";
    if ($q_process->num_rows == 0) {
        echo "<p class='m-0'>No process found</p>";
    }
    else
    while($p = $q_process->fetch_assoc()) {
        echo "<div class='d-flex flex-row align-items-center'>";
        echo "<input type='checkbox'  id='sp-".$p['process_id']."' class='edit-s-process-checkbox' value='".$p['process_id']."'";
        if ($p['qualified'] == 'yes') echo "checked";
        echo ">";
        echo "<label class='process-edit-checkbox-button m-0' for='sp-".$p['process_id']."'>";
        echo "<p class='m-0'>".$p['process_name']."</p>";
        echo "</label><br>";
        echo "</div>";

        // Handle notifikasi
$success = isset($_GET['success']) ? $_GET['success'] : 0;

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($success == 1) {
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: 'S-Process berhasil ditambahkan',
        timer: 1500
    });
    </script>";
} elseif ($success == 2) {
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: 'S-Process berhasil dihapus',
        timer: 1500
    });
    </script>";
}

    }
    echo "</div>";
    echo "<input type='text' name='s-certifications' class='ap-form-s-certification-val' value='' hidden>";
    echo "<div class='hidden'> <input type='submit' name='update' id='qq-submit-btn'>CONFIRM</input> </div>";
    echo "
    </form>
    ";
?>
