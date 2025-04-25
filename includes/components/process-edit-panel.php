<script src='js/edit-qualification-form.js'></script>
<?php
if ($karyawan['ws_id'] == 8) {
    $q_process = $conn->query(
        "SELECT
        process.id as process_id,
        process.name as process_name,
        process.min_skill as process_min_skill,
        sub_workstations.name as workstation_name,
        sub_workstations.id as sub_workstation_id,
        IF(
            process.id IN 
                (SELECT qualifications.process_id FROM qualifications WHERE qualifications.npk = '{$karyawan['npk']}')
            , (SELECT value FROM qualifications WHERE npk = '{$karyawan['npk']}' AND process.id = qualifications.process_id), 0
        ) as qualification_skill,
       IF(
            process.id IN 
                (SELECT qualifications.process_id FROM qualifications WHERE qualifications.npk = '{$karyawan['npk']}')
            , (SELECT status FROM qualifications WHERE npk = '{$karyawan['npk']}' AND process.id = qualifications.process_id), 0
        ) as process_mandatory,
       IF(
            process.id IN 
                (SELECT qualifications.process_id FROM qualifications WHERE qualifications.npk = '{$karyawan['npk']}')
            , (SELECT jadwal_training_process FROM qualifications WHERE npk = '{$karyawan['npk']}' AND process.id = qualifications.process_id),''
        ) as process_jadwal
    FROM process
    LEFT JOIN sub_workstations ON sub_workstations.id = process.workstation_id
    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
    LEFT JOIN karyawan_workstation ON karyawan_workstation.workstation_id = sub_workstations.id
    WHERE karyawan_workstation.npk = '{$karyawan['npk']}' AND process.name != '' OR workstations.id = '{$karyawan['ws_id']}'
    GROUP BY process.name
    ORDER BY sub_workstations.id ASC, process.name ASC"
    );
} else {
    $q_process = $conn->query("
    SELECT
        p.id as process_id,
        p.name as process_name,
        p.min_skill as process_min_skill,
        sw.name as workstation_name,
        sw.id as sub_workstation_id,
        COALESCE(q.value, 0) as qualification_skill,
        COALESCE(q.status, 0) as process_mandatory,
        COALESCE(q.jadwal_training_process, '') as process_jadwal
    FROM process p
    LEFT JOIN sub_workstations sw ON sw.id = p.workstation_id
    LEFT JOIN workstations w ON w.id = sw.workstation_id
    LEFT JOIN karyawan_workstation kw ON kw.workstation_id = sw.id
    LEFT JOIN qualifications q ON q.process_id = p.id AND q.npk = '{$karyawan['npk']}'
    WHERE (kw.npk = '{$karyawan['npk']}' AND p.name != '') OR w.id = '{$karyawan['ws_id']}'
    GROUP BY p.name
    ORDER BY sw.id ASC, p.name ASC
");

}

echo "<style>
.mandatory-label {
    display: flex;
    align-items: center;
    margin-left: 10px;
    font-size: 16px;
}

.mandatory-checkbox {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.custom-checkbox {
    width: 24px;
    height: 24px;
    background-color: #f0f0f0;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: 0.3s;
}

.custom-checkbox:checked {
    background-color: #4CAF50;
}
</style>";

echo "
    <form action='actions/update-qualifications.php?q=" . $karyawan['npk'] . "' method='post' class='w-100 h-75 d-block overflow-auto'>";
echo "<div class='d-block h-100' id='update-qualification-input-container' style='overflow: auto'>";

// Tambahkan tombol Tambah dan Hapus Process
echo "<div class='d-flex justify-content-end mb-3'>";
if ($_SESSION['dept'] == 'APROD') { 
echo "<button type='button' class='btn btn-primary mr-2' onclick='showAddProcessPopup()'>Tambah Process</button>";
echo "<button type='button' class='btn btn-danger' onclick='showDeleteProcessPopup()'>Hapus Process</button>";
}
echo "</div>";

$current_workstation_name = null; // Menyimpan workstation terakhir yang telah dicetak

while ($p = $q_process->fetch_assoc()) {
    if ($p['workstation_name'] !== $current_workstation_name) {
        // Jika workstation berubah, cetak judul baru
        $current_workstation_name = $p['workstation_name'];
        echo "<h3 class='mt-3'>" . strtoupper($current_workstation_name) . "</h3>"; // Menampilkan nama workstation
    }

    $disabled = ($p['qualification_skill'] >= $p['process_min_skill']) ? 'disabled' : '';

    echo "<div class='d-flex flex-row align-items-center'>";
    echo "<input type='checkbox' id='p-" . $p['process_id'] . "' class='hidden edit-process-checkbox' value='{$p['process_id']}-{$p['qualification_skill']}'";
    if ($p['qualification_skill'] > 0) echo "checked";
    echo ">";

    echo "<div class='process-edit-checkbox-button m-0 d-flex flex-row align-items-center' for='p-" . $p['process_id'] . "'>";
    echo "<p class='m-0' style='width:25%'>" . $p['process_name'] . "</p>";
    
    echo "<button type='button' class='p-edit-min-value-btn process-edit-val-btn' value='{$p['process_id']}' id='pval-min-btn-{$p['process_id']}'><p>-</p></button>";
    echo "<img src='img/C{$p['qualification_skill']}.png' class='ml-1 mr-1' style='width: 20px; height: 20px;' id='pval-{$p['process_id']}' value='0'>";
    echo "<button type='button' class='p-edit-add-value-btn process-edit-val-btn' value='{$p['process_id']}' id='pval-add-btn-{$p['process_id']}'><p>+</p></button>";
    echo "<img class='ml-auto mr-3' src='img/C{$p['process_min_skill']}.png' style='width: 20px; height: 20px;' value='0'>";

    $process_jadwal = $p['process_jadwal'];
    $formatted_jadwal = date('Y-m', strtotime($process_jadwal));
    echo "<div class='ml-auto mr-4'>
        <input type='text' class='form-control datepicker' name='jadwal_training-{$p['process_id']}' value='{$formatted_jadwal}' placeholder='yyyy-mm' id='monthYearPicker{$p['process_id']}'></div>";
    
    echo "<input type='checkbox' name='mandatory' class='mandatory-checkbox' id='mandatory-" . $p['process_id'] . "' value='{$p['process_mandatory']}'";
    if ($p['process_mandatory'] == 1)
        echo "checked";
    echo ">";
    
    echo "<input type='hidden' name='mandatory_value-{$p['process_id']}' id='mandatory_value-{$p['process_id']}' value='{$p['process_mandatory']}'>";
    echo "</div><br>";
    echo "</div>";

    echo "<script>
    $(document).ready(function () {
        $('#monthYearPicker{$p['process_id']}').datepicker({
            format: 'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose: true
        });
        $('.mandatory-checkbox').change(function() {
            var checkboxId = $(this).attr('id');
            var hiddenInputId = checkboxId.replace('mandatory', 'mandatory_value');
            if($(this).is(':checked')) {
                $('#' + hiddenInputId).val(1);
            } else {
                $('#' + hiddenInputId).val(0);
            }
        });
    });
    </script>";
}


echo "</div>";  
echo "<input type='text' name='qualifications' id='ap-form-qualification-val' value='' hidden>";
echo "<div class='hidden'> <input type='submit' name='update' id='q-submit-btn'>CONFIRM</input> </div>";
echo "
</form>
";

?>