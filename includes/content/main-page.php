<div id="content">
    <div class='h-100 d-flex align-items-center'>
        <div id="dept-overall-stats">
            <script>
                var ctx_list = {}
            </script>
            <?php
            $q_res = $conn->query("SELECT 
            AVG(IFNULL(mp_scores.kao,0)) as kao,
            AVG(IFNULL(mp_scores.msk,0)) as msk,
            AVG(IFNULL(mp_scores.kt,0)) as kt,
            AVG(IFNULL(mp_scores.pssp,0)) as pssp,
            AVG(IFNULL(mp_scores.png,0)) as png,
            AVG(IFNULL(mp_scores.fivejq,0)) as fivejq,
            workstations.dept_id
            FROM (SELECT npk FROM karyawan) as k
                LEFT JOIN mp_scores ON k.npk = mp_scores.npk
                LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = k.npk
                LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
            GROUP BY workstations.dept_id;
            ");

            $operator_mp_categories = array();
            $dept_ids = array(1, 2, 3, 4, 5, 6);

            while ($row = $q_res->fetch_assoc()) {
                if (in_array($row['dept_id'], $dept_ids)) {
                    $operator_mp_categories[$row['dept_id']] = $row;
                }
            }

            // $q_res = $conn->query("SELECT
            // AVG(IFNULL(mp_scores.kao,0)) as kao,
            // workstations.dept_id
            // FROM (SELECT npk FROM karyawan WHERE karyawan.role != 2) as k
            // LEFT JOIN mp_scores ON k.npk = mp_scores.npk
            //     LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = k.npk
            //     LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
            //     LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
            // GROUP BY workstations.dept_id;");

            // while ($row = $q_res->fetch_assoc()) {
            //     if (in_array($row['dept_id'], $dept_ids)) {
            //         $operator_mp_categories[$row['dept_id']]['kao'] = $row['kao'];
            //     }
            // }
            $q_res = $conn->query('SELECT id, dept_name FROM department;');
            while ($row = $q_res->fetch_assoc()) {
                $d_id = $row['id'];
                $d_name = $row['dept_name'];
                $chart_id = "dept-stat-$d_id";
                $labels = "['MSK', 'KT', 'PSSP', 'PNG', '5JQ', 'KAO']";

                if (!isset($operator_mp_categories[$d_id])) {
                    $operator_mp_categories[$d_id] = [
                        'msk' => 0,
                        'kt' => 0,
                        'pssp' => 0,
                        'png' => 0,
                        'fivejq' => 0,
                        'kao' => 0
                    ];
                }

                $datas = "[".
                        $operator_mp_categories[$d_id]['msk'].",".
                        $operator_mp_categories[$d_id]['kt'].",".
                        $operator_mp_categories[$d_id]['pssp'].",".
                        $operator_mp_categories[$d_id]['png'].",".
                        $operator_mp_categories[$d_id]['fivejq'].",".
                        $operator_mp_categories[$d_id]['kao'].
                        "]";
                echo "
            <a class='dept-stat-container' href='department_workstations.php?q=$d_id'>
                <div class='ds-title'>
                    <p>$d_name</p>
                </div>
                <div class='ds-content'>";
                    //include('includes/components/sm-radarchart.php');
                    
                echo "
                </div>
            </a>";
            }
            ?>
        </div>
    </div>
</div>
