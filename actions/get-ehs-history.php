<?php
include("../includes/a_config.php");
include("../includes/db_connection.php"); // Pastikan sudah ada koneksi ke database

// Periksa apakah ehs_id dan npk diterima melalui POST
if (isset($_POST['ehs_id']) && isset($_POST['npk'])) {
    $ehs_id = $_POST['ehs_id'];
    $npk = $_POST['npk'];

    // Query untuk mengambil data awal dari qualifications (skill lama kosong)
    $query = "
        SELECT 
            NULL AS old_value, 
            q.jadwal_training_ehs AS jadwal_training,
            q.created_at AS created_at,
            0 AS order_priority -- Data awal diberi prioritas 0 (lebih tinggi)
        FROM qualifications_ehs q
        WHERE q.ehs_id = '$ehs_id' 
            AND q.npk = '$npk'
        
        UNION ALL 
        
        -- Ambil riwayat update dari qualifications_ehs
        SELECT 
            qh.old_value AS old_value, 
            qh.jadwal_training_ehs AS jadwal_training,
            qh.created_at AS created_at,
            1 AS order_priority -- Data history diberi prioritas 1 (lebih rendah)
        FROM qualifications_ehs_history qh
        WHERE qh.ehs_id = '$ehs_id' 
            AND qh.npk = '$npk'
        ORDER BY order_priority ASC, created_at ASC
    ";

    $result = $conn->query($query);

    // Cek apakah ada hasil
    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>Skill Lama</th>
                        <th>Bulan Training</th>
                        <th>Tanggal Update</th>
                    </tr>
                </thead>
                <tbody>";

        // Loop untuk menampilkan riwayat skill LAMA
        while ($row = $result->fetch_assoc()) {
            // Menentukan nilai skill lama (kosong jika pertama kali)
            $old_value = !empty($row['old_value']) ? $row['old_value'] : ''; 

            // Ambil bulan training
            $jadwal_training = isset($row['jadwal_training']) ? $row['jadwal_training'] : '0000-01-01';
            $training_month = date('F', strtotime($jadwal_training));
            $created_at = $row['created_at'];

            echo "<tr>
                    <td>" . (!empty($old_value) ? "<img src='img/C{$old_value}.png' style='width: 30px; height: 30px'>" : '-') . "</td>
                    <td>" . htmlspecialchars($training_month) . "</td>
                    <td>" . htmlspecialchars($created_at) . "</td>
                  </tr>";
        }                
        echo "</tbody></table>";
    } else {
        echo "<p>Tidak ada riwayat skill untuk EHS ini.</p>";
    }
} else {
    echo "<p>Data tidak lengkap.</p>";
}
?>