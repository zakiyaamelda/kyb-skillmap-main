<div id='content'>
    <div class='w-100 d-flex flex-row justify-content-between pr-3'>
        <div class='w-75'>
            <div id="page-title" class="w-100">
                <p><?php echo $current_ws ?> Sub Workstations</p>
            </div>
            <div class="w-100 pl-3">
                <?php
                $q_heiarchy = $conn->query("
                    SELECT
                        department.id as dept_id,
                        department.dept_name as dept_name
                    FROM workstations
                    LEFT JOIN department ON workstations.dept_id = department.id
                    WHERE workstations.id = " . $ws_id . "
                ");

                if (!$q_heiarchy) {
                    echo "Error: " . $conn->error;
                    exit;
                }

                $current_heiarchy = $q_heiarchy->fetch_assoc();
                echo "
                <span>
                    <a href='index.php'>Home</a>
                </span>
                <span> / </span>
                <span>
                    <a href='department_workstations.php?q=" . $current_heiarchy['dept_id'] . "'>" . $current_heiarchy['dept_name'] . "</a>
                </span>
                <span> / </span>
                <span>
                    " . $current_ws . "
                </span>
                ";
                ?>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center pr-3">
            <a href='add_profile.php?dept=<?php echo $current_heiarchy['dept_id'] ?>' class='dp-create-btn-container'>
                <div id='ao-create-btn' class='h-100 w-100'>
                    <p class='m-0'>Add Profile</p>
                </div>
            </a>
        </div>
    </div>
    <div class='w-100 pt-3'>
        <script>
            var ctx_list = {}
        </script>
        <div id="ws-overall-stats">
            <?php
            $q_res = $conn->query("SELECT * FROM sub_workstations WHERE workstation_id = " . $ws_id);
            if (!$q_res) {
                echo "Error: " . $conn->error;
                exit;
            }

            while ($ws_row = $q_res->fetch_assoc()) {
                $ws_name = $ws_row['name'];
                $ws_id = $ws_row['id'];
                $chart_id = str_replace(" ", "-", $ws_name);

                $operator_mp_categories = array(
                    "qualifications.value" => 0.0,
                    "qualifications_ehs.value" => 0.0,
                    "qualifications_quality.value" => 0.0,
                );
                foreach ($operator_mp_categories as $cat => $val) {
                    $res = $conn->query("
                        SELECT AVG(IFNULL($cat, 0)) as average 
                        FROM karyawan
                            LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
                            LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                            LEFT JOIN qualifications on karyawan.npk = qualifications.npk
                            LEFT JOIN qualifications_ehs on karyawan.npk = qualifications_ehs.npk
                            LEFT JOIN qualifications_quality on karyawan.npk = qualifications_quality.npk
                        WHERE sub_workstations.id = $ws_id
                    ");

                    if (!$res) {
                        echo "Error: " . $conn->error;
                        exit;
                    }

                    $row = $res->fetch_assoc();
                    $avg_val = $row['average'];
                    $operator_mp_categories[$cat] = $avg_val;
                }

                $query_string = "
                    SELECT AVG(IFNULL(qualifications_quality.value, 0)) as average 
                    FROM karyawan
                        LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
                        LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                        LEFT JOIN qualifications_quality on karyawan.npk = qualifications_quality.npk
                    WHERE sub_workstations.id = $ws_id AND (
                ";
                foreach ($roles_with_kao as $role) {
                    $query_string .= "role = $role OR ";
                }
                if (count($roles_with_kao) > 0)
                    $query_string = substr($query_string, 0, -4);
                $query_string .= ")";

                $res = $conn->query($query_string);
                if (!$res) {
                    echo "Error: " . $conn->error;
                    exit;
                }

                $row = $res->fetch_assoc();
                $avg_val = $row['average'];
                $operator_mp_categories["qualifications_quality.value"] = $avg_val;

                $labels = "['Proses', 'EHS', 'Quality']";
                $datas = "[" .
                    $operator_mp_categories['qualifications.value'] . "," .
                    $operator_mp_categories['qualifications_ehs.value'] . "," .
                    $operator_mp_categories['qualifications_quality.value'] .
                    "]";

                echo "
            <a class='ws-stat-container' href='sub_workstation_members.php?q=$ws_id'>
                <div class='ws-title'>
                    <p>$ws_name</p>
                </div>
                <div class='ws-content'>";
               
                ;

                echo "
                </div>
            </a>";
            }
            ?>
        </div>
    </div>
</div>