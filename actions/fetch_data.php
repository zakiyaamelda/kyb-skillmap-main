<?php
include ("../includes/a_config.php");
include ("../includes/db_connection.php");
include ("../includes/redirect_session.php");
include ("../includes/components/member-preview-button.php");

$ws_id = $_POST['ws_id'];
$role_id = $_POST['role_id'];
$process_id = $_POST['process_id'];
$ehs_id = $_POST['ehs_id'];
$quality_id = $_POST['quality_id'];
$qualification_status = $_POST['qualification_status'];

$process_filter = $process_id ? "AND process_value.id = '$process_id'" : '';
$ehs_filter = $ehs_id ? "AND ehs_value.id = '$ehs_id'" : '';
$quality_filter = $quality_id ? "AND quality_value.id = '$quality_id'" : '';
$qualification_filter_proses = '';
$qualification_filter_ehs = '';
$qualification_filter_quality = '';
$qualification_filter = '';
if ($qualification_status !== '') {
    if ($qualification_status === '1') {
        if ($process_id !== '') {
            $qualification_filter_proses = "AND (qualifications.value >= process_value.min_skill)";
        }
        if ($ehs_id !== '') {
            $qualification_filter_ehs = "AND (qualifications_ehs.value >= ehs_value.min_skill )";
        }
        if ($quality_id !== '') {
            $qualification_filter_quality = "AND (qualifications_quality.value >= quality_value.min_skill)";
        } else {
            $qualification_filter = "AND (qualifications.value >= process_value.min_skill 
                OR qualifications_ehs.value >= ehs_value.min_skill 
                OR qualifications_quality.value >= quality_value.min_skill)";
        }
    } else {
        if ($process_id !== '') {
            $qualification_filter_proses = "AND (qualifications.value < process_value.min_skill)";
        }
        if ($ehs_id !== '') {
            $qualification_filter_ehs = "AND (qualifications_ehs.value < ehs_value.min_skill )";
        }
        if ($quality_id !== '') {
            $qualification_filter_quality = "AND (qualifications_quality.value < quality_value.min_skill)";
        } else {
            $qualification_filter = "AND (qualifications.value < process_value.min_skill 
                    OR qualifications_ehs.value < ehs_value.min_skill 
                    OR qualifications_quality.value < quality_value.min_skill)";
        }
    }
}

$q_res = $conn->query("SELECT 
    karyawan.name as name,
    karyawan.npk as npk,
    karyawan.role as role,
    AVG(IFNULL(qualifications.value, 0)) AS process,
    AVG(IFNULL(qualifications_ehs.value, 0)) AS ehs,
    AVG(IFNULL(qualifications_quality.value, 0)) AS quality,
    roles.name as role_name,
    roles.id as role_id,
    sub_workstations.id as sub_ws_id,
    sub_workstations.name as sub_ws_name,
    workstations.id as ws_id,
    workstations.name as ws_name
FROM karyawan 
LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
LEFT JOIN roles ON karyawan.role = roles.id
LEFT JOIN qualifications on karyawan.npk = qualifications.npk
LEFT JOIN qualifications_ehs on karyawan.npk = qualifications_ehs.npk
LEFT JOIN qualifications_quality on karyawan.npk = qualifications_quality.npk
LEFT JOIN process as process_value on process_value.id = qualifications.process_id
LEFT JOIN ehs as ehs_value on ehs_value.id = qualifications_ehs.ehs_id
LEFT JOIN quality as quality_value on quality_value.id = qualifications_quality.quality_id
WHERE sub_workstations.id = $ws_id AND role = $role_id $process_filter $ehs_filter $quality_filter $qualification_filter_proses $qualification_filter_ehs $qualification_filter_quality $qualification_filter
GROUP BY karyawan.npk
ORDER BY name ASC");

while ($member = $q_res->fetch_assoc()) {
    echo "<div class='member-item'>";
    member_preview_button($member, $conn);
    echo "<div class='npk' style='display: none;'>{$member['npk']}</div>";
    echo "<div class='name' style='display: none;'>{$member['name']}</div>";
    echo "</div>";
}
?>