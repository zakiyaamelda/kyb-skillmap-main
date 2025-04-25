<?php
$shift = $_POST['shift_filter'] ?? '';

$query = "
    SELECT 
        karyawan.name as name,
        karyawan.npk as npk,
        karyawan.role as role,
        roles.name as role_name,
        karyawan.shift as shift
    FROM karyawan 
    LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
    LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
    LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
    LEFT JOIN roles ON karyawan.role = roles.id
    WHERE sub_workstations.id = " . $ws_id . " AND karyawan.role = $role_id";

if ($shift === '') {
    $query .= " AND karyawan.shift IS NULL";
} elseif (!empty($shift)) {
    $query .= " AND karyawan.shift = '$shift'";
}

$query .= " ORDER BY name ASC";
$q_res = $conn->query($query);

?>