<?php
include("../includes/a_config.php");
include("../includes/db_connection.php");
include("../includes/redirect_session.php");

if (isset($_POST['update'])) {
    // Mengatur zona waktu ke Jakarta
    date_default_timezone_set('Asia/Jakarta');

    $qualifications = trim($_POST['qualificationsquality']);
    $q_arr = explode(" ", $qualifications);
    
    foreach ($q_arr as $q) {
        $q = explode("-", $q);
        $q_id = $q[0];
        $q_val = $q[1];

        // Validasi: Pastikan mandatory_value dikirim, jika tidak, gunakan nilai default
        $quality_mandatory = isset($_POST["mandatory_value-{$q_id}"]) ? $_POST["mandatory_value-{$q_id}"] : 1; // 1 sebagai default
        $jadwal_training = isset($_POST["jadwal_training-{$q_id}"]) ? $_POST["jadwal_training-{$q_id}"] : '';

        // Format tanggal jadwal training jika ada input
        if (!empty($jadwal_training)) {
            $dateTime = new DateTime($jadwal_training);
            $formattedDate = $dateTime->format("Y-m-d");
        } else {
            $formattedDate = null; // Atur null jika tidak ada input
        }

        // Debug: Cek nilai sebelum update
        echo "Quality ID: $q_id <br>";
        echo "Value: $q_val <br>";
        echo "Mandatory: $quality_mandatory <br>";
        echo "Jadwal Training: $formattedDate <br>";

        // Ambil data lama sebelum diupdate
        $old_data_query = $conn->query("
            SELECT value, jadwal_training_quality, status 
            FROM qualifications_quality
            WHERE quality_id = '$q_id' AND npk = '" . $_GET['q'] . "'
        ");
        $old_data = $old_data_query->fetch_assoc();

        if ($old_data) {
            // Cek apakah sudah ada data lama di qualifications_quality_history untuk quality_id & npk ini
            $check_history = $conn->query("
                SELECT * FROM qualifications_quality_history 
                WHERE quality_id = '$q_id' 
                AND npk = '" . $_GET['q'] . "'
                AND old_value = '" . $old_data['value'] . "'
            ");
        
            // Jika belum ada, baru masukkan ke qualifications_quality_history
            if ($check_history->num_rows == 0) {
                $conn->query("
                    INSERT INTO qualifications_quality_history 
                    (quality_id, npk, old_value, new_value, jadwal_training_quality, status, created_at)
                    VALUES (
                        '$q_id',
                        '" . $_GET['q'] . "',
                        '" . $old_data['value'] . "',
                        '$q_val',
                        '" . $old_data['jadwal_training_quality'] . "',
                        '" . $old_data['status'] . "',
                        NOW()
                    )
                ");
            }
        }        

        // Cek apakah data sudah ada
        $check_query = $conn->query("
            SELECT * FROM qualifications_quality
            WHERE npk = '" . $_GET['q'] . "' AND quality_id = '$q_id'
        ");

        if ($check_query->num_rows > 0) {
            // Jika data sudah ada, lakukan UPDATE
            $update_query = "
                UPDATE qualifications_quality
                SET value = '$q_val',
                    jadwal_training_quality = " . ($formattedDate ? "'$formattedDate'" : "NULL") . ",
                    status = '$quality_mandatory',
                    created_at = NOW()
                WHERE npk = '" . $_GET['q'] . "' AND quality_id = '$q_id'
            ";
            $conn->query($update_query);

            // Debug: Cek hasil update
            $check_status = $conn->query("
                SELECT status FROM qualifications_quality 
                WHERE npk = '" . $_GET['q'] . "' AND quality_id = '$q_id'
            ");
            $result = $check_status->fetch_assoc();
            echo "Status setelah update: " . $result['status'] . "<br>";
        } else {
            // Jika data belum ada, lakukan INSERT
            $conn->query("
                INSERT INTO qualifications_quality 
                (npk, quality_id, value, jadwal_training_quality, status, created_at)
                VALUES (
                    '" . $_GET['q'] . "',
                    '$q_id',
                    '$q_val',
                    " . ($formattedDate ? "'$formattedDate'" : "NULL") . ",
                    '$quality_mandatory',
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
