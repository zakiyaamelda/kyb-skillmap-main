<?php
include '../lib/PHPExcel-1.8/Classes/PHPExcel.php';
include '../lib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
include ("../includes/a_config.php");
include ("../includes/db_connection.php");
include ("../includes/redirect_session.php");

$response = array();

// Direktori untuk mengunggah file
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$fileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Periksa apakah file adalah Excel
if ($fileType != "xls" && $fileType != "xlsx") {
    $response['error'] = "Maaf, hanya file Excel yang diperbolehkan.";
    echo json_encode($response);
    exit;
}

// Proses unggahan file
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $response['success'] = "File " . basename($_FILES["fileToUpload"]["name"]) . " telah diunggah.";

    // Load Excel
    $inputFileType = PHPExcel_IOFactory::identify($target_file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($target_file);

    // Inisialisasi array untuk menyimpan hasil
    $results = array();

    // Mengambil Dept_ID dari URL
    $dept_id_url = $_GET['dept'];

    // Loop melalui semua sheet
    foreach ($objPHPExcel->getAllSheets() as $sheet) {
        $sheetTitle = $sheet->getTitle();
        $highestRow = $sheet->getHighestRow();

        // Mengambil Dept_ID dari Excel
        $dept_id_excel = $sheet->getCell("B2")->getValue();
        $jadwal_training = $sheet->getCell("B3")->getFormattedValue();
        $process_name = strtoupper($sheet->getCell("B4")->getValue());

        // Validasi Dept_ID
        if ($dept_id_excel != $dept_id_url) {
            $response['error'] = "Departemen tidak sesuai.";
            echo json_encode($response);
            exit;
        }

        // Tentukan tabel, kolom, dan logika berdasarkan nama sheet
        $table_name = '';
        $id_column = '';
        $id_qualifications = '';
        $table_qualification = '';
        $training_column = '';

        switch ($sheetTitle) {
            case 'Process':
                $table_name = 'process';
                $id_column = 'id';
                $id_qualifications = 'process_id';
                $table_qualification = 'qualifications';
                $training_column = 'jadwal_training_process';
                break;

            case 'EHS':
                $table_name = 'ehs';
                $id_column = 'id';
                $id_qualifications = 'ehs_id';
                $table_qualification = 'qualifications_ehs';
                $training_column = 'jadwal_training_ehs';
                break;

            case 'Quality':
                $table_name = 'quality';
                $id_column = 'id';
                $id_qualifications = 'quality_id';
                $table_qualification = 'qualifications_quality';
                $training_column = 'jadwal_training_quality';
                break;

            default:
                $results[] = "Lembar kerja tidak dikenal: $sheetTitle.";
                continue;
        }

        // Loop setiap baris mulai dari baris ke-7
        for ($row = 7; $row <= $highestRow; $row++) {
            $npk = strtoupper($sheet->getCell("A" . $row)->getFormattedValue());
            $name = strtoupper($sheet->getCell("B" . $row)->getValue());
            $workstations_name = strtoupper($sheet->getCell("C" . $row)->getValue());
            $sub_workstations_name = strtoupper($sheet->getCell("D" . $row)->getValue());
            $value = $sheet->getCell("E" . $row)->getValue();

            if (!empty($npk) && !empty($workstations_name) && !empty($sub_workstations_name)) {
                // Cari workstation_id berdasarkan workstation_name dan dept_id
                $workstations_result = $conn->query("SELECT id FROM workstations WHERE UPPER(name) = '$workstations_name' AND dept_id = '$dept_id_excel'");
                if ($workstations_result->num_rows > 0) {
                    $workstation_id = $workstations_result->fetch_assoc()['id'];

                    $sub_workstations_result = $conn->query("SELECT id FROM sub_workstations WHERE UPPER(name) = '$sub_workstations_name' AND workstation_id = '$workstation_id'");
                    if ($sub_workstations_result->num_rows > 0) {
                        $sub_workstation_id = $sub_workstations_result->fetch_assoc()['id'];

                        // Cari process_id berdasarkan process_name
                        $process_result = $conn->query("SELECT $id_column FROM $table_name WHERE UPPER(name) = '$process_name'");
                        if ($process_result->num_rows > 0) {
                            $process_id = $process_result->fetch_assoc()[$id_column];

                            // Periksa apakah data sudah ada
                            $check_sql = "SELECT * FROM $table_qualification WHERE npk = '$npk' AND $id_qualifications = '$process_id'";
                            $check_result = $conn->query($check_sql);

                            if ($check_result->num_rows > 0) {
                                // Jika ada, update data
                                $update_sql = "UPDATE $table_qualification SET value = '$value', status = '1', $training_column = '$jadwal_training' WHERE npk = '$npk' AND $id_qualifications = '$process_id'";
                                if ($conn->query($update_sql) === TRUE) {
                                    $results[] = "Baris $row: Data berhasil diperbarui.";
                                } else {
                                    $results[] = "Baris $row: Gagal memperbarui data: " . $conn->error;
                                }
                            } else {
                                // Jika tidak ada, masukkan data baru
                                $insert_sql = "INSERT INTO $table_qualification (npk, $id_qualifications, value, status, $training_column) VALUES ('$npk', '$process_id', '$value', 1, '$jadwal_training')";
                                if ($conn->query($insert_sql) === TRUE) {
                                    $results[] = "Baris $row: Data berhasil dimasukkan.";
                                } else {
                                    $results[] = "Baris $row: Gagal memasukkan data: " . $conn->error;
                                }
                            }
                        } else {
                            $results[] = "Baris $row: Process ID tidak ditemukan untuk $process_name.";
                        }
                    } else {
                        $results[] = "Baris $row: Sub Workstation ID tidak ditemukan untuk $sub_workstations_name.";
                    }
                } else {
                    $results[] = "Baris $row: Workstation ID tidak ditemukan untuk $workstations_name.";
                }
            } else {
                $results[] = "Baris $row: Data kosong atau tidak lengkap.";
            }
        }
    }

    $response['results'] = $results;
} else {
    $response['error'] = "Maaf, terjadi kesalahan saat mengunggah file.";
}

echo json_encode($response);
?>
