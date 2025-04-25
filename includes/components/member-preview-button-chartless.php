<?php
function member_preview_button_chartless($member, $conn)
{
    $img_path = "img/profile_pictures/" . $member['npk'] . ".jpg";
    if (!file_exists($img_path))
        $img_path = "img/profile_pictures/default.jpg";
    echo
        "<div class='d-inline-block' style='padding-right: 50px;'>
        <div class='member-container mr-2 mb-2'>
            <a href='preview_member.php?q=" . $member['npk'] . "'>
                <div class='member-info'>
                    <div class='h-100'>
                        <div class='member-info-photo-container'>
                            <img src='" . $img_path . "'></img>
                        </div>
                    </div>
                    <div class='member-info-texts pl-1 pt-1 w-75 overflow-ellipsis'>
                        <div class='d-flex-row'>
                            <span class='w-25'>
                                <p>Name</p>
                            </span>
                            <span>
                                <p>: </p>
                            </span>
                            <span class='w-75 pl-2'>
                                <p>" . $member['name'] . "</p>
                            </span>
                        </div>
                        <div class='d-flex-row'>
                            <span class='w-25'>
                                <p>NPK</p>
                            </span>
                            <span>
                                <p>: </p>
                            </span>
                            <span class='w-75 pl-2'>
                                <p>" . $member['npk'] . "</p>
                            </span>
                        </div>";
    if ($member["role"] == 0) {
        $workstations_query = $conn->query("
                            SELECT sub_workstations.name as name 
                            FROM karyawan_workstation
                            JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                            WHERE karyawan_workstation.npk = '" . $member['npk'] . "'
                        ");
        $workstations_string = '';
        while ($workstations_row = $workstations_query->fetch_assoc()) {
            $workstations_string .= $workstations_row['name'] . ', ';
        }
        echo "
                        <div class='d-flex-row'>
                            <span class='w-25'>
                                <p>WS</p>
                            </span>
                            <span>
                                <p>: </p>
                            </span>
                            <span class='w-75 pl-2'>
                                <p>" . substr($workstations_string, 0, -2) . "</p>
                            </span>
                        </div>";
    }
    echo "
                    </div>
                </div>";

    include ('includes/components/member-chart-preview.php');

    echo "</a>
            
        </div>
    </div>";
}
?>