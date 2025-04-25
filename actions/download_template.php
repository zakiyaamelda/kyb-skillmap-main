<?php
include '../lib/PHPExcel-1.8/Classes/PHPExcel.php';
include '../lib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
include ("../includes/a_config.php");
include ("../includes/db_connection.php");
include ("../includes/redirect_session.php");

// Mengambil dept_id dari query string
$dept_id = $_GET['dept'];

// Mengambil nama departemen dari database
$q_res = $conn->query("SELECT * FROM department WHERE id = " . $dept_id);
$row = $q_res->fetch_assoc();
$dept_id = $row['id'];
$dept_name = $row['dept_name'];

// Buat objek PHPExcel baru
$objPHPExcel = new PHPExcel();

// Menambahkan definisi style array
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);

// Fungsi untuk menambahkan informasi departemen dan keterangan gambar di lembar kerja
function addDepartmentInfoAndImages($sheet, $dept_name, $dept_id)
{
    $styleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    );

    // Menambahkan informasi departemen
    $sheet->setCellValue('A1', 'Departemen:')
        ->setCellValue('B1', $dept_name)
        ->setCellValue('A2', 'Departemen Id:')
        ->setCellValue('B2', $dept_id)
        ->setCellValue('A3', 'Jadwal Training:')
        ->setCellValue('A4', 'Process Name:');

    $sheet->getStyle('A1:B2')->applyFromArray($styleArray);
    $sheet->getStyle('A3:B3')->applyFromArray($styleArray);
    $sheet->getStyle('A4:B4')->applyFromArray($styleArray);
    

    
}

// Menambahkan informasi departemen dan gambar untuk lembar kerja pertama (process)
$objPHPExcel->setActiveSheetIndex(0);
addDepartmentInfoAndImages($objPHPExcel->getActiveSheet(), $dept_name, $dept_id);

$columns_process = array('NPK', 'Nama', 'Workstations', 'Sub_Workstations', 'Value');
$columnLetter = 'A';
$rowNumber = 6;
foreach ($columns_process as $column) {
    $objPHPExcel->getActiveSheet()->setCellValue($columnLetter . $rowNumber, $column);
    $columnLetter++;
}

// Mengatur format kolom NPK menjadi teks
$objPHPExcel->getActiveSheet()->getStyle('A7:A1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
// Mengatur format kolom Jadwal Training menjadi tanggal
$objPHPExcel->getActiveSheet()->getStyle('B3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
$objPHPExcel->getActiveSheet()->setTitle('Process');

// Menambahkan border untuk cell tertentu
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A7:E1000')->applyFromArray($styleArray);

// Menambahkan ukuran cell lebih besar
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
foreach (range('A', 'E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Tambahkan lembar kerja kedua (ehs)
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', 'Departemen:')
    ->setCellValue('B1', $dept_name)
    ->setCellValue('A2', 'Departemen Id:')
    ->setCellValue('B2', $dept_id)
    ->setCellValue('A3', 'Jadwal Training:')
    ->setCellValue('A4', 'EHS Name:');

$columns_ehs = array('NPK', 'Nama', 'Workstations', 'Sub_Workstations', 'Value');
$columnLetter = 'A';
$rowNumber = 6;
foreach ($columns_ehs as $column) {
    $objPHPExcel->getActiveSheet()->setCellValue($columnLetter . $rowNumber, $column);
    $columnLetter++;
}
$objPHPExcel->getActiveSheet()->getStyle('A7:A1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
$objPHPExcel->getActiveSheet()->setTitle('EHS');

// Menambahkan border untuk cell tertentu di lembar EHS
$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A6:E1000')->applyFromArray($styleArray);

// Menambahkan ukuran cell lebih besar di lembar EHS
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
foreach (range('A', 'E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Tambahkan lembar kerja ketiga (quality)
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1', 'Departemen:')
    ->setCellValue('B1', $dept_name)
    ->setCellValue('A2', 'Departemen Id:')
    ->setCellValue('B2', $dept_id)
    ->setCellValue('A3', 'Jadwal Training:')
    ->setCellValue('A4', 'Quality Name:');

$columns_quality = array('NPK', 'Nama', 'Workstations', 'Sub_Workstations', 'Value');
$columnLetter = 'A';
$rowNumber = 6;
foreach ($columns_quality as $column) {
    $objPHPExcel->getActiveSheet()->setCellValue($columnLetter . $rowNumber, $column);
    $columnLetter++;
}
$objPHPExcel->getActiveSheet()->getStyle('A7:A1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
$objPHPExcel->getActiveSheet()->setTitle('Quality');

// Menambahkan border untuk cell tertentu di lembar Quality
$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A6:E1000')->applyFromArray($styleArray);

// Menambahkan ukuran cell lebih besar di lembar Quality
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
foreach (range('A', 'E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}   

// Mengatur lembar kerja aktif ke lembar kerja pertama
$objPHPExcel->setActiveSheetIndex(0);

$filename = "template.xlsx";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>