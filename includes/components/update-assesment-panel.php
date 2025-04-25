<?php
echo "<form class='w-100 h-75 d-block overflow-auto' action='actions/update-assessment.php?q=" . $karyawan['npk'] . "' method='post'>";
if (!in_array($karyawan['role'], $roles_with_kao))
    unset($mp_categories['kao']);
foreach ($mp_categories as $cat => $cat_name) {
    echo "
                                <div class='cat-update-container'>
                                    <div class='cu-name'>
                                        <p>$cat_name:</p>
                                    </div>
                                    <div class='cu-radiogroup'>";
    for ($i = 1; $i <= 5; $i++) {
        echo "
                                        <input type='radio' id='$cat-value-$i' class='update-assessment-button' name='$cat' value='$i'";
        if ($karyawan[$cat] == $i)
            echo "checked";
        echo
            ">
                                        <div class='cu-radio-button'>
                                            <label for='$cat-value-$i'>
                                                <div>
                                                    <p>$i</p>
                                                </div>
                                            </label>
                                        </div>";
    }
    echo "
                                    </div>
                                </div>";
}
?>
<div class="d-flex w-100 flex-float-bottom d-flex-row mb-1">
    
    <div class='ml-auto'>
        <a href="#" onclick="show_popup('#delete-popup'); hide_popup('#edit-assessment-popup')" class="cu-delete-btn mr-2">DELETE</a>
        <a href="#" onclick="show_popup('#update-popup'); hide_popup('#edit-assessment-popup')" class="cu-submit-btn">UPDATE</a>
        <div class="hidden">
            <input type="submit" name="delete" id="cu-delete-btn">DELETE</input>
            <input type="submit" name="update" id="cu-submit-btn">UPDATE</input>
        </div>
    </div>
</div>
</form>