<div id="content">
    <div class='d-flex flex-row justify-content-between w-100'>
        <div class='w-75'>
            <div id="page-title">
                <p>Workstations for <?php echo $current_dept ?></p>
            </div>
            <div class="pl-3">
                <?php
                echo "
                    <span>
                        <a href='index.php'>Home</a>
                    </span>
                    <span> / </span>
                    <span>
                        " . $current_dept . "
                    </span>";
                ?>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center pr-3">
            <a href='add_profile.php?dept=<?php echo $dept_id ?>' class='dp-create-btn-container'>
                <div id='ao-create-btn' class='h-200 w-200'>
                    Add Profile
                </div>
            </a>
            <a href="#" id="downloadTemplate" class='dp-create-btn-container ml-2'>
                <div id='ao-create-btn' class='h-200 w-200'>
                    Download Template
                </div>
            </a>
            <a href="#" id="downloadTemplate" class='dp-create-btn-container ml-2' data-toggle="modal"
                data-target="#uploadModal">
                <div id='ao-create-btn' class='h-200 w-200'>
                    Upload Kualifikasi
                </div>
            </a>
        </div>
    </div>

    <div class='w-100'>
        <div id="ws-overall-stats">
            <script>
                var ctx_list = {}
            </script>
            <?php
            $q_res = $conn->query("SELECT * FROM workstations WHERE dept_id = " . $dept_id);
            while ($ws_row = $q_res->fetch_assoc()) {
                $ws_name = $ws_row['name'];
                $ws_id = $ws_row['id'];
                $chart_id = str_replace(" ", "-", $ws_name);

                $operator_mp_categories = array(
                    "qualifications.value" => 0.0,
                    "qualifications_ehs.value" => 0.0,
                    "qualifications_quality.value" => 0.0
                );
                foreach ($operator_mp_categories as $cat => $val) {
                    $res = $conn->query("
                            SELECT AVG(IFNULL($cat,0)) as average
                            FROM karyawan
                                LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
                                LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                                LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id 
                                LEFT JOIN qualifications ON karyawan.npk = qualifications.npk
                                LEFT JOIN qualifications_ehs ON karyawan.npk = qualifications_ehs.npk
                                LEFT JOIN qualifications_quality ON karyawan.npk = qualifications_quality.npk
                            WHERE workstations.id = $ws_id
                        ");
                    $row = $res->fetch_assoc();
                    $avg_val = $row['average'];
                    $operator_mp_categories[$cat] = $avg_val;
                }
                $query_string = "
                        SELECT AVG(IFNULL(qualifications_quality.value,0)) as average 
                        FROM karyawan
                            LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
                            LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                            LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id 
                            LEFT JOIN qualifications_quality ON karyawan.npk = qualifications_quality.npk
                        WHERE workstations.id = $ws_id AND (
                    ";
                foreach ($roles_with_kao as $role) {
                    $query_string .= "role = $role OR ";
                }
                if (count($roles_with_kao) > 0)
                    $query_string = substr($query_string, 0, -4);
                $query_string .= ")";

                $res = $conn->query($query_string);
                $row = $res->fetch_assoc();
                $avg_val = $row['average'];
                $operator_mp_categories["qualifications_quality.value"] = $avg_val;

                $labels = "['Process', 'EHS', 'Quality']";
                $datas = "[" .
                    $operator_mp_categories['qualifications.value'] . "," .
                    $operator_mp_categories['qualifications_ehs.value'] . "," .
                    $operator_mp_categories['qualifications_quality.value'] . "," .
                    "]";

                echo "
                <a class='ws-stat-container' href='workstation_members.php?q=$ws_id'>
                    <div class='ws-title'>
                        <p>$ws_name</p>
                    </div>
                    <div class='ws-content'>

                    </div>
                </a>";
            }
            ?>
        </div>
        <!-- Modal untuk Unggah File -->

    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Penilaian Kualifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="custom-file mb-3">
                            <label for="fileToUpload">Pilih file penilaian berupa excel:</label>
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control-file" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#downloadTemplate').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan mendownload template penilaian kualifikasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, download!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengunduh...',
                        html: 'Tunggu sebentar, file sedang diunduh.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    var dept_id = <?php echo $dept_id; ?>;
                    $.ajax({
                        url: 'actions/download_template.php',
                        type: 'GET',
                        data: { dept: dept_id },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (data) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(data);
                            a.href = url;
                            a.download = 'Template\t<?php echo $current_dept ?>.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                            Swal.close();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal mengunduh template.'
                            });
                        }
                    });
                }
            });
        });

        $('#uploadForm').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Mengunggah...',
                html: 'Tunggu sebentar, file sedang diunggah.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });

            var formData = new FormData(this);
            $.ajax({
                url: 'actions/upload_qualifications.php?dept=<?php echo $dept_id; ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = JSON.parse(response);
                    Swal.close();
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.error
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.success
                        });
                        if (response.results) {
                            var resultHtml = '<ul>';
                            for (var i = 0; i < response.results.length; i++) {
                                resultHtml += '<li>' + response.results[i] + '</li>';
                            }
                            resultHtml += '</ul>';
                            $('#uploadResults').html(resultHtml);
                        }
                    }
                    $('#uploadModal').modal('hide');
                },
                error: function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengunggah file.'
                    });
                }
            });
        });
    });
</script>