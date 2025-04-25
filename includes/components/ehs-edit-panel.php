<script src='js/edit-qualificationEhs-form.js'></script>
<?php
if ($karyawan['ws_id'] == 8) {
    $q_ehs = $conn->query(
        "SELECT
        ehs.id as ehs_id,
        ehs.name as ehs_name,
        ehs.min_skill as ehs_min_skill,
        sub_workstations.name as workstation_name,
        sub_workstations.id as sub_workstation_id,
        IF(
            ehs.id IN 
                (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
            , (SELECT value FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), 0
        ) as qualification_skill,
        IF( 
            ehs.id IN 
                (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
            , (SELECT status FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), 0
        ) as ehs_mandatory,
        IF(
            ehs.id IN 
                (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
            , (SELECT jadwal_training_ehs FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), ''
        ) as ehs_jadwal
    FROM ehs
    LEFT JOIN sub_workstations ON sub_workstations.id = ehs.workstation_id
    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
    WHERE workstations.id = '{$karyawan['ws_id']}'
    GROUP BY ehs.id, sub_workstations.id
    ORDER BY sub_workstations.id ASC, ehs.name ASC"
    );
} else {
    $q_ehs = $conn->query(
        "SELECT
            ehs.id as ehs_id,
            ehs.name as ehs_name,
            ehs.min_skill as ehs_min_skill,
            sub_workstations.name as workstation_name,
            sub_workstations.id as sub_workstation_id,
            IF(
                ehs.id IN 
                    (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
                , (SELECT value FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), 0
            ) as qualification_skill,
            IF( 
                ehs.id IN 
                    (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
                , (SELECT status FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), 0
            ) as ehs_mandatory,
            IF(
                ehs.id IN 
                    (SELECT qualifications_ehs.ehs_id FROM qualifications_ehs WHERE qualifications_ehs.npk = '{$karyawan['npk']}')
                , (SELECT jadwal_training_ehs FROM qualifications_ehs WHERE npk = '{$karyawan['npk']}' AND ehs.id = qualifications_ehs.ehs_id), ''
            ) as ehs_jadwal
        FROM ehs
        LEFT JOIN sub_workstations ON sub_workstations.id = ehs.workstation_id
        LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
        WHERE workstations.id = '{$karyawan['ws_id']}'
        ORDER BY sub_workstations.name ASC, ehs.name ASC"
    );
}

echo "<style>
.mandatory-label {
    display: flex;
    align-items: center;
    margin-left: 10px;
    font-size: 16px;
}

.mandatory-checkbox-ehs {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.custom-checkbox {
    width: 24px; /* Lebar checkbox */
    height: 24px; /* Tinggi checkbox */
    background-color: #f0f0f0; /* Warna latar belakang */
    border-radius: 5px; /* Membuat tepi menjadi bulat */
    border: 1px solid #ccc; /* Warna tepi */
    transition: 0.3s; /* Waktu transisi ketika di-click */
}

.custom-checkbox:checked {
    background-color: #4CAF50; /* Warna ketika checkbox dicentang */
}
</style>";

    echo "
        <form action='actions/update-qualifications-ehs.php?q=" . $karyawan['npk'] . "' method='post' class='w-100 h-75 d-block overflow-auto'>
        ";
    echo "<div class='d-block h-100' id='update-qualification-ehs-input-container' style='overflow: auto'>";

    // Tambahkan tombol Tambah dan Hapus EHS
    echo "<div class='d-flex justify-content-end mb-3'>";
    if ($_SESSION['dept'] == 'APROD') { 
    echo "<button type='button' class='btn btn-primary mr-2' onclick='showAddEhsPopup()'>Tambah EHS</button>";
    echo "<button type='button' class='btn btn-danger' onclick='showDeleteEhsPopup()'>Hapus EHS</button>";
    }
    echo "</div>";

    $current_workstation_name = null; // Menyimpan workstation terakhir yang telah dicetak

    while ($p2 = $q_ehs->fetch_assoc()) {
        if ($p2['workstation_name'] !== $current_workstation_name) {
            // Jika workstation berubah, cetak judul baru
            $current_workstation_name = $p2['workstation_name'];
            echo "<h3 class='mt-3'>" . strtoupper($current_workstation_name) . "</h3>"; // Menampilkan nama workstation
        }

    $disabled = ($p2['qualification_skill'] >= $p2['ehs_min_skill']) ? 'disabled' : '';

    echo "<div class='d-flex flex-row align-items-center'>";
    echo "<input type='checkbox' id='p2-" . $p2['ehs_id'] . "' class='hidden edit-ehs-checkbox' value='{$p2['ehs_id']}-{$p2['qualification_skill']}'";
    if ($p2['qualification_skill'] > 0)
        echo "checked";
    echo ">";

    echo "<div class='ehs-edit-checkbox-button m-0 d-flex flex-row align-items-center' for='p2-" . $p2['ehs_id'] . "'>";
    echo "<p class='m-0' style='width:25%'>" . $p2['ehs_name'] . ' (' . $p2['workstation_name'] . ")</p>";
    echo "<button type='button' class='p2-edit-min-value-btn ehs-edit-val-btn' value='{$p2['ehs_id']}' id='pval2-min-btn-{$p2['ehs_id']}'><p>-</p></button>";
    echo "<img src='img/C{$p2['qualification_skill']}.png' class='ml-1 mr-1' style='width: 20px; height: 20px;' id='pval2-{$p2['ehs_id']}' value='0'>";
    echo "<button type='button' class='p2-edit-add-value-btn ehs-edit-val-btn' value='{$p2['ehs_id']}' id='pval2-add-btn-{$p2['ehs_id']}'><p>+</p></button>";
    echo "<img class='ml-auto mr-3' src='img/C{$p2['ehs_min_skill']}.png' style='width: 20px; height: 20px;' value='0'>";

    $ehs_jadwal = $p2['ehs_jadwal'];
    $formatted_jadwal = date('Y-m', strtotime($ehs_jadwal));
    echo "<div class='ml-auto mr-4'>
        <input type='text' class='form-control datepicker' name='jadwal_training_ehs-{$p2['ehs_id']}' value='{$formatted_jadwal}' placeholder='yyyy-mm' id='monthYearPickerEhs{$p2['ehs_id']}'></div>";
    
    echo "<input type='checkbox' name='mandatory' class='mandatory-checkbox-ehs' id='mandatory-ehs-" . $p2['ehs_id'] . "' value='{$p2['ehs_mandatory']}'";
    if ($p2['ehs_mandatory'] == 1)
        echo "checked";
    echo ">";
    
    echo "<input type='hidden' name='mandatory_value-ehs-{$p2['ehs_id']}' id='mandatory_value-ehs-{$p2['ehs_id']}' value='{$p2['ehs_mandatory']}'>";
    echo "</div><br>";
    echo "</div>";

    echo "<script>
    $(document).ready(function () {
        $('#monthYearPickerEhs{$p2['ehs_id']}').datepicker({
            format: 'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose: true
        });
        function updateHiddenInput(checkbox, hiddenInput) {
                hiddenInput.val(checkbox.is(':checked') ? 1 : 0);
            }

            $('.mandatory-checkbox-ehs').change(function() {
                var hiddenInput = $('#mandatory_value-ehs-' + $(this).attr('id').split('-')[2]);
                updateHiddenInput($(this), hiddenInput);
            });

            $('.mandatory-checkbox-ehs').each(function() {
                var hiddenInput = $('#mandatory_value-ehs-' + $(this).attr('id').split('-')[2]);
                updateHiddenInput($(this), hiddenInput);
        });
    });
    </script>";
}

echo "</div>";
echo "<input type='text' name='qualificationsehs' id='ap-form-qualification-val-ehs' value='' hidden>";
echo "<div class='hidden'> <input type='submit' name='update' id='q2-submit-btn'>CONFIRM</input> </div>";
echo "
    </form>
    ";
?>