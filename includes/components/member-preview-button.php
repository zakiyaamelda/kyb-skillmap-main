<?php
function member_preview_button($member, $conn)
{
    $img_path = "../img/profile_pictures/" . $member['npk'] . ".jpg";
    if (!file_exists($img_path)) {
        $img_path = "img/profile_pictures/default.jpg";
    } else {
        $img_path = "img/profile_pictures/" . $member['npk'] . ".jpg";
    }

    echo " <a href='preview_member.php?q=" . $member['npk'] . "'>
    <div class='member-container d-flex'>
    
            <div class='member-info d-flex flex-column align-items-center'>
           
                <div class='member-info-photo-container'>
                    <img src='" . $img_path . "' alt='Profile Picture' style=' width: 100%;
                    height: 100%;'>
                </div>
                <div class='member-info-texts'>
                    <p>Name: " . $member['name'] . "</p>
                    <p>NPK: " . $member['npk'] . "</p>";
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
        echo "<p>WS: " . substr($workstations_string, 0, -2) . "</p>";
    }
    echo "      </div>
            </div>
            <div class='member-panels d-flex flex-row'>";
    $member_preview = "includes/components/member-chart-preview.php";

    if (!file_exists($member_preview)) {
        $member_preview = "../includes/components/member-chart-preview.php";
        include ($member_preview);
    } else {
        $member_preview = "includes/components/member-chart-preview.php";
        include ($member_preview);
    }

    echo "  </div>
    
        </div>
        </a>";
}
?>