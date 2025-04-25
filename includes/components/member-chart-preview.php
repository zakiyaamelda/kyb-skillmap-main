<div class='member-panels d-flex flex-row justify-content-start'>
    <!-- Panel Process -->
    <div class='p-process-panel'>
        <div class='p-process-panel-text'>Process</div>
        <div class='p-process-panel-list'>
            <?php
            $process_id = isset($_POST['process_id']) ? $_POST['process_id'] : '';
            $ehs_id = isset($_POST['ehs_id']) ? $_POST['ehs_id'] : '';
            $quality_id = isset($_POST['quality_id']) ? $_POST['quality_id'] : '';
            $qualification_status = isset($_POST['qualification_status']) ? $_POST['qualification_status'] : '';

            $process_filter = $process_id ? "AND process.id = '$process_id'" : '';
            $ehs_filter = $ehs_id ? "AND ehs.id = '$ehs_id'" : '';
            $quality_filter = $quality_id ? "AND quality.id = '$quality_id'" : '';
            $qualification_filter_proses = '';
            $qualification_filter_ehs = '';
            $qualification_filter_quality = '';
            if ($qualification_status !== '') {
                if ($qualification_status === '1') {
                    if ($process_id !== '') {
                        $qualification_filter_proses = "AND (qualifications.value >= process.min_skill)";
                    }
                    if ($ehs_id !== '') {
                        $qualification_filter_ehs = "AND (qualifications_ehs.value >= ehs.min_skill )";
                    }
                    if ($quality_id !== '') {
                        $qualification_filter_quality = "AND (qualifications_quality.value >= quality.min_skill)";
                    } else {
                        $qualification_filter_proses = "AND (qualifications.value >= process.min_skill)";
                        $qualification_filter_ehs = "AND (qualifications_ehs.value >= ehs.min_skill )";
                        $qualification_filter_quality = "AND (qualifications_quality.value >= quality.min_skill)";
                    }
                } else {
                    if ($process_id !== '') {
                        $qualification_filter_proses = "AND (qualifications.value < process.min_skill)";
                    }
                    if ($ehs_id !== '') {
                        $qualification_filter_ehs = "AND (qualifications_ehs.value < ehs.min_skill )";
                    }
                    if ($quality_id !== '') {
                        $qualification_filter_quality = "AND (qualifications_quality.value < quality.min_skill)";
                    } else {
                        $qualification_filter_proses = "AND (qualifications.value < process.min_skill)";
                        $qualification_filter_ehs = "AND (qualifications_ehs.value < ehs.min_skill )";
                        $qualification_filter_quality = "AND (qualifications_quality.value < quality.min_skill)";

                    }
                }
            }



            $q_res = $conn->query(
                "SELECT 
                    process.name AS name, 
                    process.min_skill AS min_skill, 
                    qualifications.value AS skill, 
                    MONTH(qualifications.jadwal_training_process) AS training_month,
                    sub_workstations.name AS sub_workstation_name
                FROM 
                    process
                JOIN 
                    sub_workstations ON process.workstation_id = sub_workstations.id
                JOIN 
                    karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                JOIN 
                    karyawan ON karyawan_workstation.npk = karyawan.npk
                JOIN 
                    qualifications ON karyawan.npk = qualifications.npk AND process.id = qualifications.process_id
                WHERE 
                    karyawan.npk =  '" . $member['npk'] . "'
                    AND qualifications.status = 1
                    $process_filter  $qualification_filter_proses
                ORDER BY 
                    sub_workstations.name, process.name
                 "
            );


            while ($q_row = $q_res->fetch_assoc()) {
                $class = ($q_row['skill'] < $q_row['min_skill']) ? 'background-color: #E8DADA;' : '';
                $fontBold = ($q_row['skill'] < $q_row['min_skill']) ? 'font-weight: bolder;' : '';

                echo "<div class='p-process-panel-list-item p-1 mb-1 d-flex flex-row' style='align-items: flex-start; $class'>";
                echo "<p class='m-0 w-75' style='$fontBold'>" . $q_row['name'] . "</p>";
                echo "<div class='d-flex flex-row align-items-center'>";
                echo "<p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>";
                echo "<img style='width: 3rem; height: 3rem' src='img/C{$q_row['skill']}.png'/>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Panel Quality -->
    <div class='p-process-panel'>
        <div class='p-process-panel-text'>Quality</div>
        <div class='p-process-panel-list'>
            <?php

            $q_res2 = $conn->query(
                "SELECT 
                    quality.name AS name,
                    quality.min_skill AS min_skill, 
                    qualifications_quality.value AS skill,
                    MONTH(qualifications_quality.jadwal_training_quality) as training_month
                    FROM 
                    quality
                JOIN 
                    sub_workstations ON quality.workstation_id = sub_workstations.id
                JOIN 
                    karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                JOIN 
                    karyawan ON karyawan_workstation.npk = karyawan.npk
                JOIN 
                    qualifications_quality ON karyawan.npk = qualifications_quality.npk AND quality.id = qualifications_quality.quality_id
                WHERE 
                    karyawan.npk =  '" . $member['npk'] . "'
                    AND qualifications_quality.status = 1
                    $quality_filter $qualification_filter_quality
                ORDER BY 
                    sub_workstations.name, quality.name"
            );

            while ($q_row2 = $q_res2->fetch_assoc()) {
                $class = ($q_row2['skill'] < $q_row2['min_skill']) ? 'background-color: #E8DADA' : '';
                $fontBold = ($q_row2['skill'] < $q_row2['min_skill']) ? 'font-weight: bolder;' : '';

                echo "<div class='p-process-panel-list-item p-1 mb-1 d-flex flex-row' style='align-items: flex-start; $class'>";
                echo "<p class='m-0 w-75' style='$fontBold'>" . $q_row2['name'] . "</p>";
                echo "<div class='d-flex flex-row align-items-center'>";
                echo "<p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>";
                echo "<img style='width: 3rem; height: 3rem' src='img/C{$q_row2['skill']}.png'/>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Panel EHS -->
    <div class='p-process-panel'>
        <div class='p-process-panel-text'>EHS</div>
        <div class='p-process-panel-list'>
            <?php

            $q_res3 = $conn->query(
                "SELECT 
                    ehs.name AS name, 
                    ehs.min_skill AS min_skill, 
                    qualifications_ehs.value AS skill,
                    MONTH(qualifications_ehs.jadwal_training_ehs) as training_month
                FROM 
                    ehs
                JOIN 
                    sub_workstations ON ehs.workstation_id = sub_workstations.id
                JOIN 
                    karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                JOIN 
                    karyawan ON karyawan_workstation.npk = karyawan.npk
                JOIN 
                    qualifications_ehs ON karyawan.npk = qualifications_ehs.npk AND ehs.id = qualifications_ehs.ehs_id
                WHERE 
                    karyawan.npk =  '" . $member['npk'] . "'
                    AND qualifications_ehs.status = 1
                    $ehs_filter $qualification_filter_ehs
                ORDER BY 
                    sub_workstations.name, ehs.name"
            );

            while ($q_row3 = $q_res3->fetch_assoc()) {
                $class = ($q_row3['skill'] < $q_row3['min_skill']) ? 'background-color: #E8DADA' : '';
                $fontBold = ($q_row3['skill'] < $q_row3['min_skill']) ? 'font-weight: bolder;' : '';

                echo "<div class='p-process-panel-list-item p-1 mb-1 d-flex flex-row' style='align-items: flex-start; $class'>";
                echo "<p class='m-0 w-75' style='$fontBold'>" . $q_row3['name'] . "</p>";
                echo "<div class='d-flex flex-row ml-auto mr-2'>";
                echo "<p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>";
                echo "<img style='width: 3rem; height: 3rem' src='img/C{$q_row3['skill']}.png'/>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>