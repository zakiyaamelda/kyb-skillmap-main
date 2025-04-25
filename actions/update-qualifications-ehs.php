<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");

if (isset($_POST['update'])) {
    // Mengatur zona waktu ke Jakarta
    date_default_timezone_set('Asia/Jakarta');

    $qualifications = trim($_POST['qualificationsehs']);
    $q_arr = explode(" ", $qualifications);
    
    foreach ($q_arr as $q) {
        $q = explode("-", $q);
        $q_id = $q[0];
        $q_val = $q[1];

        // Validasi: Pastikan mandatory_value dikirim, jika tidak, gunakan nilai default
        $ehs_mandatory = isset($_POST["mandatory_value-{$q_id}"]) ? $_POST["mandatory_value-{$q_id}"] : 1; // 1 sebagai default
        $jadwal_training = isset($_POST["jadwal_training-{$q_id}"]) ? $_POST["jadwal_training-{$q_id}"] : '';

        // Format tanggal jadwal training jika ada input
        if (!empty($jadwal_training)) {
            $dateTime = new DateTime($jadwal_training);
            $formattedDate = $dateTime->format("Y-m-d");
        } else {
            $formattedDate = null; // Atur null jika tidak ada input
        }

        // Debug: Cek nilai sebelum update
        echo "Ehs ID: $q_id <br>";
        echo "Value: $q_val <br>";
        echo "Mandatory: $ehs_mandatory <br>";
        echo "Jadwal Training: $formattedDate <br>";

        // Ambil data lama sebelum diupdate
        $old_data_query = $conn->query("
            SELECT value, jadwal_training_ehs, status 
            FROM qualifications_ehs 
            WHERE ehs_id = '$q_id' AND npk = '" . $_GET['q'] . "'
        ");
        $old_data = $old_data_query->fetch_assoc();

        if ($old_data) {
            // Cek apakah sudah ada data lama di qualifications_ehs_history untuk ehs_id & npk ini
            $check_history = $conn->query("
                SELECT * FROM qualifications_ehs_history 
                WHERE ehs_id = '$q_id' 
                AND npk = '" . $_GET['q'] . "'
                AND old_value = '" . $old_data['value'] . "'
            ");
        
            // Jika belum ada, baru masukkan ke qualifications_ehs_history
            if ($check_history->num_rows == 0) {
                $conn->query("
                    INSERT INTO qualifications_ehs_history 
                    (ehs_id, npk, old_value, new_value, jadwal_training_ehs, status, created_at)
                    VALUES (
                        '$q_id',
                        '" . $_GET['q'] . "',
                        '" . $old_data['value'] . "',
                        '$q_val',
                        '" . $old_data['jadwal_training_ehs'] . "',
                        '" . $old_data['status'] . "',
                        NOW()
                    )
                ");
            }
        }        

        // Cek apakah data sudah ada
        $check_query = $conn->query("
            SELECT * FROM qualifications_ehs
            WHERE npk = '" . $_GET['q'] . "' AND ehs_id = '$q_id'
        ");

        if ($check_query->num_rows > 0) {
            // Jika data sudah ada, lakukan UPDATE
            $update_query = "
                UPDATE qualifications_ehs
                SET value = '$q_val',
                    jadwal_training_ehs = " . ($formattedDate ? "'$formattedDate'" : "NULL") . ",
                    status = '$ehs_mandatory',
                    created_at = NOW()
                WHERE npk = '" . $_GET['q'] . "' AND ehs_id = '$q_id'
            ";
            $conn->query($update_query);

            // Debug: Cek hasil update
            $check_status = $conn->query("
                SELECT status FROM qualifications_ehs 
                WHERE npk = '" . $_GET['q'] . "' AND ehs_id = '$q_id'
            ");
            $result = $check_status->fetch_assoc();
            echo "Status setelah update: " . $result['status'] . "<br>";
        } else {
            // Jika data belum ada, lakukan INSERT
            $conn->query("
                INSERT INTO qualifications_ehs 
                (npk, ehs_id, value, jadwal_training_ehs, status, created_at)
                VALUES (
                    '" . $_GET['q'] . "',
                    '$q_id',
                    '$q_val',
                    " . ($formattedDate ? "'$formattedDate'" : "NULL") . ",
                    '$ehs_mandatory',
                    NOW()
                )
            ");
        }
    }

    // Redirect kembali ke halaman preview
    $query_string = '?q=' . $_GET['q'];
    header("Location: ../preview_member.php$query_string");
    exit;
}
?>
