<div id="content">
    <div id="add-profile-page-container">
        <div id="page-title">
            <p>ADD PROFILE TO <?php echo strtoupper($current_dept)?></p>
        </div>
        <form  class="w-100" action="actions/add-profile.php" method="post" enctype="multipart/form-data">
            <div id="add-profile-form-container">
                <div class="ap-form-section">
                    <p>Nama:</p>
                    <input class="ap-form-input-box" name="name" type="text" maxlength='32'>
                </div>
                <div class="ap-form-section">
                    <p>NPK:</p>
                    <input class="ap-form-input-box" name="npk" type="text" maxlength="11">
                </div>
                <div class="ap-form-section">
                    <p>Role:</p>
                    <select class="ap-form-input-box" id="ap-form-role" name="role">
                        <?php 
                            $q_res = $conn->query("SELECT id, name FROM roles");
                            while ($role_row = $q_res->fetch_assoc()) {
                                $role = $role_row['name'];
                                $role_id = $role_row['id'];
                                echo "<option value='".$role_id."'>$role</option>";
                            }
                            ?>
                    </select>
                </div>
                <div class="ap-form-section">
                    <p>Join Date:</p>
                    <input type="date" class="ap-form-input-box" id="ap-form-date" name="join-date">
                </div>
                <div class="ap-form-section">
                    <p>Workstation:</p>
                    <select class="ap-form-input-box" id="ap-form-ws" multiple>
                        <?php 
                        $q_res = $conn->query("
                            SELECT 
                                sub_workstations.id as id, 
                                sub_workstations.name as name,
                                workstations.name as ws_name
                            FROM sub_workstations
                                LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
                            WHERE workstations.dept_id = ".$dept_id."
                            ORDER BY workstations.id, sub_workstations.id
                            ");

                        $current_group = '';

                        while ($ws_row = $q_res->fetch_assoc()) {
                            $sub_ws_name = $ws_row['name'];
                            $ws_name = $ws_row['ws_name'];
                            $ws_id = $ws_row['id'];
                            if ($ws_name != $current_group) {
                                echo "<optgroup label='$ws_name'>";
                                $current_group = $ws_name;
                            }
                            echo "<option value='".$ws_id."'>$sub_ws_name</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="ws" id="ap-form-ws-val" value='' hidden>
                </div>
                <div class="ap-form-section">
                    <p>Profile Picture:</p>
                    <input type="file" id="ap-form-photo" name="ap-form-photo" accept="image/*">
                </div>
                <div class="ap-form-section">
                    <a href="<?php 
                        if (isset($_SERVER['HTTP_REFERER'])) {
                            echo $_SERVER['HTTP_REFERER'];
                        } else {
                            echo "index.php";
                        }
                    ?>" class="cu-cancel-btn">Cancel</a>
                    <button id="ap-form-submit" name="submit" class="cu-submit-btn">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
