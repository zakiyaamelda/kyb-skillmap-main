<div id="content">

    <div id="add-profile-page-container">
        <div id="page-title">
            <p>UPDATE PROFILE</p>
        </div>
        <form class="w-100" action="actions/update-profile.php?dept_id=<?php echo $_GET['dept_id'] ?>" method="post">
            <div id="add-profile-form-container">
                <div class="ap-form-section">
                    <p>Nama:</p>
                    <input class="ap-form-input-box" name="name" type="text" maxlength='32' disabled
                        value='<?php echo $karyawan["name"] ?>'>
                </div>
                <div class="ap-form-section">
                    <p>NPK:</p>
                    <input class="ap-form-input-box" name="npk_" type="text" maxlength="11" disabled
                        value='<?php echo $karyawan["npk"] ?>'>
                    <input class="ap-form-input-box hidden" name="npk" type="text" maxlength="11"
                        value='<?php echo $karyawan["npk"] ?>'>
                </div>
                <div class="ap-form-section">
                    <p>Department:</p>
                    <select class="ap-form-input-box" id="ap-form-dept" name="dept">
                        <?php
                        $q_res = $conn->query("SELECT id, dept_name FROM department");
                        while ($row = $q_res->fetch_assoc()) {
                            $d_id = $row['id'];
                            echo "<option value='" . $d_id . "'  " . ($d_id == $karyawan['dept_id'] ? ' selected' : '') . ">" . $row['dept_name'] . "</option>";



                        }


                        ?>
                    </select>
                </div>
                <div class="ap-form-section">
                    <p>Role:</p>
                    <select class="ap-form-input-box" id="ap-form-role" name="role">
                        <?php
                        $q_res = $conn->query("SELECT id, name FROM roles");
                        while ($role_row = $q_res->fetch_assoc()) {
                            $role = $role_row['name'];
                            $role_id = $role_row['id'];
                            echo "<option value='" . $role_id . "'" . ($role_id == $karyawan['role_id'] ? 'selected' : '') . ">" . $role . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="ap-form-section">
                    <p>Workstation:</p>
                    <select class="ap-form-input-box" id="ap-form-ws" name="workstations[]" <?php echo $karyawan['role_id'] != 0 ? '' : 'multiple'; ?>>
                        <?php
                        $selected_sub_ws_ids = array();
                        if ($karyawan['role_id'] == 0) {
                            $result = $conn->query("SELECT workstation_id FROM karyawan_workstation WHERE npk = '" . $karyawan['npk'] . "'");
                            while ($row = $result->fetch_assoc()) {
                                $selected_sub_ws_ids[] = $row['workstation_id'];
                            }
                        } else {
                            $selected_sub_ws_ids[] = $karyawan['sub_ws_id'];
                        }

                        $q_res = $conn->query("
            SELECT 
                sub_workstations.id as id, 
                sub_workstations.name as name,
                workstations.id as ws_id,
                workstations.name as ws_name,
                department.id as dept_id
            FROM sub_workstations
                LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
                LEFT JOIN department ON workstations.dept_id = department.id
            ORDER BY workstations.id, sub_workstations.id
        ");

                        $current_group = '';

                        while ($ws_row = $q_res->fetch_assoc()) {
                            $sub_ws_name = $ws_row['name'];
                            $ws_name = $ws_row['ws_name'];
                            $sws_id = $ws_row['id'];
                            $ws_id = $ws_row['ws_id'];
                            $dept_id = $ws_row['dept_id'];
                            $is_selected = in_array($sws_id, $selected_sub_ws_ids) ? 'selected' : '';

                            if ($ws_name != $current_group) {
                                if ($current_group != '') {
                                    echo "</optgroup>";
                                }
                                echo "<optgroup class='form-sws-d-$dept_id form-ws-optgroup' label='$ws_name'>";
                                $current_group = $ws_name;
                            }
                            echo "<option class='form-sws-d-$dept_id' value='$sws_id' $is_selected>" . $sub_ws_name . "</option>";
                        }
                        if ($current_group != '') {
                            echo "</optgroup>";
                        }
                        ?>
                    </select>
                    <input type="text" name="ws" id="ap-form-ws-val" value='' hidden>
                </div>

                <div class="ap-form-section">
                    <a href="<?php
                    if (isset($_SERVER['HTTP_REFERER'])) {
                        echo $_SERVER['HTTP_REFERER'];
                    } else {
                        echo "index.php";
                    }
                    ?>" class="cu-cancel-btn">Cancel</a>
                    <!-- Tombol Delete -->
                    <button type="button" id="ap-form-delete" class="cu-delete-btn" onclick="confirmDelete('<?php echo $karyawan['npk']; ?>')">Delete</button>
                    <button id="ap-form-submit" name="submit" class="cu-submit-btn">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(npk) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan menghapus karyawan ini secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect ke action delete
            window.location.href = `actions/delete-karyawan.php?npk=${npk}`;
        }
    });
}
</script>