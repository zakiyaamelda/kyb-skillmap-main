<style>
    .spinner {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 0.5s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .accordion-button {
        width: 100%;
        text-align: left;
        padding: 10px 15px;
        color: black;
        background-color: transparent;
        border: none;
    }

    .accordion-button:hover {
        color: black;
        background-color: #f8f9fa;
    }

    .accordion-button:focus {
        color: black;
        background-color: #f8f9fa;
        box-shadow: none;
    }

    .accordion-button:not(.collapsed) {
        color: black;
        background-color: #f8f9fa;
    }

    .accordion-button .toggle-icon {
        color: black;
        font-size: 2rem;
    }

    .search-input {
        margin-bottom: 10px;
    }

    .no-data-message {
        display: none;
        text-align: center;
        margin-top: 10px;
        color: red;
    }
</style>
<div id='content'>
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-9'>
                <div id="page-title" class="w-100">
                    <p><?php echo $current_ws ['name']?> Members</p>
                </div>
                <div class="w-100 pl-3">
                    <?php
                    $q_heiarchy = $conn->query("SELECT
                                    department.id as dept_id,
                                    department.dept_name as dept_name,
                                    workstations.id as ws_id,
                                    workstations.name as ws_name
                                FROM sub_workstations
                                    LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
                                    LEFT JOIN department ON workstations.dept_id = department.id
                                WHERE sub_workstations.id = " . $ws_id . "
                            ");
                    $current_heiarchy = $q_heiarchy->fetch_assoc();
                    echo "
                            <span>
                                <a href='index.php'>Home</a>
                            </span>
                            <span> / </span>
                            <span>
                                <a href='department_workstations.php?q=" . $current_heiarchy['dept_id'] . "'>" . $current_heiarchy['dept_name'] . "</a>
                            </span>";
                    if ($current_heiarchy['ws_name'] != $current_ws)
                        echo "
                            <span> / </span>
                            <span>
                                <a href='workstation_members.php?q=" . $current_heiarchy['ws_id'] . "'>" . $current_heiarchy['ws_name'] . "</a>
                            </span>";
                    echo "
                            <span> / </span>
                            <span>
                                " . $current_ws ['name'] . "
                            </span>";
                    ?>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <a href='add_profile.php?dept=<?php echo $current_heiarchy['dept_id'] ?>'
                    class='dp-create-btn-container'>
                    <div id='ao-create-btn' class='h-100 w-100'>
                        <p class='m-0'>Add Profile</p>
                    </div>
                </a>
            </div>
        </div>
        <?php
        include_once ('includes/components/member-preview-button.php');
        include_once ('includes/components/member-preview-button-chartless.php');
        $q_roles = $conn->query("SELECT name, id FROM roles");
        echo "<div id='accordion'>";
        while ($role_row = $q_roles->fetch_assoc()) {
            $role = $role_row['name'];
            $role_id = $role_row['id'];
            echo "
                <div class='card'>
                    <div class='card-header' id='heading{$role_id}'>
                        <h5 class='mb-0'>
                            <button class='btn btn-link accordion-button' data-toggle='collapse' data-target='#collapse{$role_id}' aria-expanded='true' aria-controls='collapse{$role_id}'>
                            <span style='color: black; font-size: 2rem;'>  " . $role . "</span> <span class='float-right toggle-icon'>+</span>
                            </button>
                        </h5>
                    </div>

                    <div id='collapse{$role_id}' class='collapse' aria-labelledby='heading{$role_id}'>
                        <div class='card-body'>
                        <div class='form-row'>
                                
                                <label class='filter-label'>Search by Name or Npk:</label>
                            <input type='text' class='form-control search-input' placeholder='Search NPK or Name...' onkeyup='searchNPK(this, \"member-list-container-{$role_id}\")'>
                        
                            </div>
                            <div class='filter-container'>
                            <form method='POST' action='' id='filter-form-{$role_id}'>
                            <div class='form-row'>
                                <div class='form-group col-md-6'>
                                    <label class='filter-label'>Filter by Process:</label>
                                    <select name='process_id_{$role_id}' class='form-control filter-select' id='process_id_{$role_id}'>
                                        <option value=''>All Process</option>";
            if ($role_id == 0) {
                $q_process = $conn->query("SELECT process.id, process.name
                    FROM PROCESS
                    LEFT JOIN sub_workstations ON sub_workstations.id = process.workstation_id
                    LEFT JOIN qualifications ON qualifications.process_id = process.id
                    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                    WHERE workstations.dept_id
                    IN (
                    
                    SELECT id
                    FROM department
                    WHERE id =" . $current_heiarchy['dept_id'] . "
                    )
                    GROUP BY process.name
                    ORDER BY process.name ASC");
            } else {
                $q_process = $conn->query("SELECT process.id, process.name
                    FROM PROCESS
                    LEFT JOIN sub_workstations ON sub_workstations.id = process.workstation_id
                    LEFT JOIN qualifications ON qualifications.process_id = process.id
                    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                    WHERE sub_workstations.workstation_id =" . $current_heiarchy['ws_id'] . "
                    GROUP BY process.name
                    ORDER BY process.name ASC");
            }

            while ($process_row = $q_process->fetch_assoc()) {
                $selected = ($process_row['id'] == $_POST['process_id_' . $role_id]) ? "selected" : "";
                echo "<option value='{$process_row['id']}' {$selected}>{$process_row['name']}</option>";
            }
            echo "</select>
                                </div>
                                <div class='form-group col-md-6'>
                                    <label class='filter-label'>Filter by Ehs:</label>
                                    <select  name='ehs_id_{$role_id}' class='form-control filter-select' id='ehs_id_{$role_id}'>
                                        <option value='' selected>All EHS</option>";
            if ($role_id == 0) {
                $q_ehs = $conn->query("SELECT ehs.id, ehs.name
                    FROM ehs
                    LEFT JOIN sub_workstations ON sub_workstations.id = ehs.workstation_id
                    LEFT JOIN qualifications_ehs ON qualifications_ehs.ehs_id = ehs.id
                    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                    WHERE workstations.dept_id IN (SELECT id from department where id =" . $current_heiarchy['dept_id'] . " )
                    GROUP BY ehs.name
                    ORDER BY ehs.name ASC");
            } else {
                $q_ehs = $conn->query("SELECT ehs.id, ehs.name
                    FROM ehs
                    LEFT JOIN sub_workstations ON sub_workstations.id = ehs.workstation_id
                    LEFT JOIN qualifications_ehs ON qualifications_ehs.ehs_id = ehs.id
                    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                    WHERE sub_workstations.workstation_id =" . $current_heiarchy['ws_id'] . "
                    GROUP BY ehs.name
                    ORDER BY ehs.name ASC");
            }

            while ($ehs_row = $q_ehs->fetch_assoc()) {
                $selected = ($ehs_row['id'] == $_POST['ehs_id_' . $role_id]) ? "selected" : "";
                echo "<option value='{$ehs_row['id']}' {$selected}>{$ehs_row['name']}</option>";
            }
            echo "</select>
                                </div>
                            </div>
                            <div class='form-row'>
                                <div class='form-group col-md-6'>
                                    <label class='filter-label'>Filter by Quality:</label>
                                    <select name='quality_id_{$role_id}' class='form-control filter-select' id='quality_id_{$role_id}'>
                                        <option value=''>All Quality</option>";
            if ($role_id == 0) {
                $q_quality = $conn->query("SELECT quality.id, quality.name
                                            FROM quality
                                            LEFT JOIN sub_workstations ON sub_workstations.id = quality.workstation_id
                                            LEFT JOIN qualifications_quality ON qualifications_quality.quality_id = quality.id
                                            LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                                            WHERE workstations.dept_id IN (SELECT id from department WHERE id =" . $current_heiarchy['dept_id'] . "  )
                                            GROUP BY quality.name
                                            ORDER BY quality.name ASC");
            } else {
                $q_quality = $conn->query("SELECT quality.id, quality.name
                    FROM quality
                    LEFT JOIN sub_workstations ON sub_workstations.id = quality.workstation_id
                    LEFT JOIN qualifications_quality ON qualifications_quality.quality_id = quality.id
                    LEFT JOIN workstations ON workstations.id = sub_workstations.workstation_id
                    WHERE sub_workstations.workstation_id =" . $current_heiarchy['ws_id'] . "
                    GROUP BY quality.name
                    ORDER BY quality.name ASC");
            }


            while ($quality_row = $q_quality->fetch_assoc()) {
                $selected = ($quality_row['id'] == $_POST['quality_id_' . $role_id]) ? "selected" : "";
                echo "<option value='{$quality_row['id']}' {$selected}>{$quality_row['name']}</option>";
            }
            echo "</select>
                                </div>
                                <div class='form-group col-md-6'>
                                    <label class='filter-label'>Filter By Qualification:</label>
                                    <select name='qualification_status_{$role_id}' class='form-control filter-select' id='qualification_status_{$role_id}'>
                                        <option value='' selected>All Qualifications</option>
                                        <option value='1' " . (isset($_POST['qualification_status_' . $role_id]) && $_POST['qualification_status_' . $role_id] == '1' ? 'selected' : '') . ">Qualified</option>
                                        <option value='0' " . (isset($_POST['qualification_status_' . $role_id]) && $_POST['qualification_status_' . $role_id] == '0' ? 'selected' : '') . ">Not Qualified</option>
                                    </select>
                                </div>
                            </div>
                            </form>
                            </div>
                            <div class='no-data-message' id='no-data-{$role_id}'>No Employee Data Available</div>
                            <div id='loading-{$role_id}' class='spinner' style='display: none;'></div>
                            <div class='member-list-container' id='member-list-container-{$role_id}'>
                            </div>
                        </div>
                    </div>
                </div>";
        }
        echo "</div>";
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.collapse').on('show.bs.collapse', function () {
            $(this).prev('.card-header').find('.accordion-button .toggle-icon').text('-');
        });

        $('.collapse').on('hide.bs.collapse', function () {
            $(this).prev('.card-header').find('.accordion-button .toggle-icon').text('+');
        });

        // Event listener for dropdown change
        $('.filter-select').change(function () {
            var role_id = $(this).closest('form').attr('id').split('-').pop();
            var process_id = $('#process_id_' + role_id).val();
            var ehs_id = $('#ehs_id_' + role_id).val();
            var quality_id = $('#quality_id_' + role_id).val();
            var qualification_status = $('#qualification_status_' + role_id).val();

            fetchMemberData(role_id, process_id, ehs_id, quality_id, qualification_status);
        });

        // Function to fetch member data
        function fetchMemberData(role_id, process_id, ehs_id, quality_id, qualification_status) {
            $('#loading-'+role_id).show();  // Menampilkan elemen loading
            $('#member-list-container-' + role_id).hide(); // Menyembunyikan kontainer data

            $.ajax({
                url: 'actions/fetch_data.php',
                method: 'POST',
                data: {
                    ws_id: <?php echo $ws_id; ?>,
                    role_id: role_id,
                    process_id: process_id,
                    ehs_id: ehs_id,
                    quality_id: quality_id,
                    qualification_status: qualification_status
                },
                success: function (response) {
                    $('#member-list-container-' + role_id).html(response);
                    $('#loading-'+role_id).hide();  // Menyembunyikan elemen loading
                    $('#member-list-container-' + role_id).show(); // Menampilkan kontainer data
                    updateNoDataMessage(role_id);
                },
                error: function () {
                    $('#loading-'+role_id).hide();  // Menyembunyikan elemen loading meskipun terjadi error
                    $('#member-list-container-' + role_id).show(); // Menampilkan kontainer data meskipun terjadi error
                }
            });
        }

        // Fetch initial data for all roles
        <?php
        $q_roles = $conn->query("SELECT id FROM roles");
        while ($role_row = $q_roles->fetch_assoc()) {
            echo "fetchMemberData(" . $role_row['id'] . ", '', '', '', '');";
        }
        ?>

        function updateNoDataMessage(role_id) {
            var container = $('#member-list-container-' + role_id);
            var noDataMessage = $('#no-data-' + role_id);
            if (container.children().length === 0) {
                noDataMessage.show();
            } else {
                noDataMessage.hide();
            }
        }
    });

    function searchNPK(input, containerId) {
        var filter = input.value.toUpperCase();
        var container = document.getElementById(containerId);
        var items = container.getElementsByClassName('member-item');
        var noDataMessage = document.getElementById('no-data-' + containerId.split('-').pop());

        var visibleCount = 0;

        for (var i = 0; i < items.length; i++) {
            var npk = items[i].getElementsByClassName('npk')[0];
            var name = items[i].getElementsByClassName('name')[0];
            if (npk || name) {
                var npkValue = npk.textContent || npk.innerText;
                var nameValue = name.textContent || name.innerText;
                if (npkValue.toUpperCase().indexOf(filter) > -1 || nameValue.toUpperCase().indexOf(filter) > -1) {
                    items[i].style.display = "";
                    visibleCount++;
                } else {
                    items[i].style.display = "none";
                }
            }
        }

        if (visibleCount === 0) {
            noDataMessage.style.display = "block";
        } else {
            noDataMessage.style.display = "none";
        }
    }
</script>