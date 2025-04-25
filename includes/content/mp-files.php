<div id='content'>
    <div class='w-100' style='height: 12.5%'>
        <div id="page-title" class="w-100">
            <p>Files for <?php echo $karyawan['name'];?></p>
        </div>
        <div class="w-100 pl-3 mb-1">
            <?php 
            echo "
            <span>
                <a href='index.php'>Home</a>
            </span>
            <span> / </span>
            <span>
                <a href='department_workstations.php?q=".$karyawan['dept_id']."'>".$karyawan['dept_name']."</a>
            </span>";
            if ($karyawan['ws_name'] != $karyawan['sub_ws_name'])
            echo "<span> / </span>
            <span>
                <a href='workstation_members.php?q=".$karyawan['ws_id']."'>".$karyawan['ws_name']."</a>
            </span>";
            echo "<span> / </span>
            <span>
                <a href='sub_workstation_members.php?q=".$karyawan['sub_ws_id']."'>".$karyawan['sub_ws_name']."</a>
            </span>
            <span> / </span>
            <span>
                <a href='preview_member.php?q=".$karyawan['npk']."'>".$karyawan['name']."</a>
            </span>
            <span> / </span>
            <span>
                Files
            </span>
            ";
            ?>
        </div>
    </div>
    <div class='w-100 d-flex align-items-center justify-content-center flex-row' style='height: 87.5%'>
        <div class='w-75 h-100 p-3'>
            <div id='files-preview' class='w-100 h-100 background-light'>
                <div class='w-100 d-flex align-items-center justify-content-center'>
                    <p class='mt-3 small-title'>Information</p>
                </div>
                <?php 
                $q_res = $conn->query("
                SELECT id, filename, name, description, posted_time 
                FROM mp_file_proof 
                WHERE npk = '".$_GET['q']."' AND posted_time > DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
            
                if ($q_res->num_rows == 0) {
                    echo "<p class='ml-3'>No files found.</p>";
                } else {
                    while ($file_row = $q_res->fetch_assoc()) {
                        echo "
                        <div class='file-preview-container w-100 p-3'>
                            <p>".$file_row['posted_time']."</p>
                            <a href='files/".$file_row['filename']."' target='_blank'>
                                <p>".$file_row['name']."</p>
                            </a>
                            <p>Description: ".$file_row['description']."</p>
                            <a href='actions/delete-file.php?q=".$file_row['id']."'>
                                <button class='cu-submit-btn'>Delete</button>
                            </a>
                        </div>";
                    }
                }                       
                ?>
            </div>
        </div>
        <div class='w-25 h-100 p-3'>
            <div id='upload-file-section' class='w-100 h-100 p-3 background-light'>
                <p class='small-title'>Add New Files</p>
                <form action="actions/add-file.php?q=<?php echo $_GET['q'];?>" 
                    method="post" enctype="multipart/form-data" 
                    class="w-100 h-100 d-flex flex-column justify-content-center align-content-center">
                    
                    <input type="file" id="mp-file-input" name="mp-file-input" accept=".pdf">
                    <input class="mt-4" type="text" name="mp-file-name" placeholder="File Name" maxlength="100">
                    <textarea class="mt-4" name="mp-file-desc" placeholder="File Description" maxlength="100"></textarea>
                    
                    <input type="submit" name="update" class="cu-submit-btn">
                </form>
                </form>
            </div>
        </div>
    </div>
</div>