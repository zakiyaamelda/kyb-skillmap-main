<div class='d-flex justify-content-center w-100' style='height: 27.5%'>
    <div class='p-process-panel d-flex flex-column align-items-center p-1'>
        <div class='p-process-panel-text d-flex flex-row justify-content-between w-100 pl-1 pr-1' style='height:1.5rem;'>
            <p class='m-0'>Process Qualification</p>
            <a href='#' onclick='show_popup("#edit-process-popup")' id='process-edit-btn' class='d-flex justify-content-center align-items-center'>
                <p class='m-0'>EDIT</p>
            </a>
        </div>
        <div class="w-100 p-1" style='height: calc(100% - 1.5rem);'>
            <div class='p-process-panel-list w-100'>
                <?php
                $q_res = $conn->query(
                    "SELECT 
                        process.name as name,
                        process.min_skill as min_skill,
                        qualifications.value as skill
                    FROM karyawan
                    JOIN qualifications ON karyawan.npk = qualifications.npk
                    JOIN process ON qualifications.process_id = process.id
                    WHERE karyawan.npk = '".$karyawan['npk']."' AND qualifications.value >= process.min_skill");
                while ($q_row = $q_res->fetch_assoc()) {
                    echo "
                    <div class='p-process-panel-list-item p-1 mb-1 d-flex flex-row'>
                        <p class='m-0 w-75'>".$q_row['name']."</p>
                        <div class='d-flex flex-row ml-auto mr-2'>
                            <p class='m-0 mt-auto mr-1' style='font-size:0.7rem'>Skill: </p>
                            <img style='width: 1.2rem; height: 1.2rem' src='img/C{$q_row['skill']}.png'/>
                            <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem'>Req: </p>
                            <img style='width: 1.2rem; height: 1.2rem' src='img/C{$q_row['min_skill']}.png'/>
                        </div>
                    </div>
                    ";
                }
                ?>
            </div>
        </div>
    </div>
</div>