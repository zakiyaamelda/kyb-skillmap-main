<div id="content">
    <style>
        /* Styling untuk popup */
        #skill-history-process-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
            display: none;
        }

        #skill-history-quality-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
            display: none;
        }

        #skill-history-ehs-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
            display: none;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 50%;
            max-height: 70%;
            overflow-y: auto;
        }

        .popup-content {
            max-width: 500px;
            margin: auto;
        }

        /* Button styling */
        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
        }

        .score-container {
            display: grid;
            grid-template-columns: repeat(3, auto); /* 3 kolom per baris */
            gap: 10px 20px;
            font-size: 0.8rem;
            padding: 10px;
        }

        .score-item {
            white-space: nowrap;
        }

        html, body {
            overflow: hidden; /* Mencegah scroll */
            height: 100vh; /* Pastikan body memiliki tinggi penuh */
        }

        .scrollable-container {
            max-height: 300px; /* Sesuaikan tinggi maksimum */
            overflow-y: auto; /* Hanya container ini yang bisa di-scroll */
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        .p-process-panel-list {
            max-height: 400px; /* Sesuaikan tinggi maksimum */
            overflow-y: auto; /* Scroll hanya di dalam sini */
        }

        .p-process-panel-list-item {
            height: auto; /* Sesuaikan tinggi berdasarkan konten */
            min-height: 15px; /* Tetapkan tinggi minimum */
            padding: 2px 4px; /* Kurangi padding atas-bawah */
            margin-bottom: 5px; /* Jarak antar item */
            display: flex;
            align-items: center; /* Agar konten tetap rata tengah */
            border-radius: 10px; /* Opsional: Sudut membulat */
            background-color: #fff; /* Opsional: Warna latar */
            border: 1px solid #ccc; /* Opsional: Tambahkan garis tepi */
        }

        /* Ukuran tombol */
        .custom-btn {
            width: 190px;
            height: 50px;
            text-align: center;
            font-size: 1rem;
            font-weight: bold; /* Membuat teks tombol menjadi bold */
        }

        /* Warna berbeda untuk setiap tombol */
        .btn-blue {
            background-color: rgb(23, 26, 66);
            border: 1px solid rgb(23, 26, 66);
            color: white;
        }

        .btn-red {
            background-color: rgb(180, 40, 40);
            border: 1px solid rgb(180, 40, 40);
            color: white;
        }

        .btn-green {
            background-color: rgb(34, 139, 34);
            border: 1px solid rgb(34, 139, 34);
            color: white;
        }

        /* Menghilangkan margin antar tombol */
        .row button {
            margin-right: 0;
            padding: 10px;
        }

        /* Styling scrollbar horizontal */
        .checkbox-container {
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-start;
            align-items: center;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            padding: 2px; /* Mengurangi padding */
            max-width: 280px; /* Lebar container dikurangi */
        }

        /* Label proses */
        .process-label {
            display: flex;
            align-items: center;
            margin-left: 4px;
            font-size: 12px; /* Ukuran font lebih kecil */
        }

        /* Container bulan */
        .month-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 2px;
            width: 380px; /* Lebar lebih kecil */
        }

        /* Label bulan */
        .month-labels {
            display: flex;
            justify-content: start;
            width: 100%;
        }

        .month-label {
            width: 18px; /* Lebih kecil */
            text-align: center;
            font-size: 0.5rem; /* Diperkecil */
            margin-right: 1px;
        }

        /* Kotak bulan */
        .month-boxes {
            display: flex;
            justify-content: start;
            width: 100%;
        }

        .month-box {
            width: 18px; /* Lebih kecil */
            height: 18px; /* Lebih kecil */
            border: 1px solid #000;
            margin-right: 1px;
        }

        .highlighted {
            background-color: #39FF14;
        }

        /* Container skill & req */
        .skill-req-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-left: auto;
            margin-right: 0.8rem; /* Lebih kecil */
            max-width: 70px; /* Lebar container dikurangi */
        }

        /* Kontainer skill & requirement */
        .skill-container, .req-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Teks skill & req */
        .skill-text, .req-text {
            font-size: 0.55rem;
            margin-top: 2px;
        }

        /* Styling gambar Skill dan Req */
        .skill-container img, .req-container img {
            width: 1.4rem; /* Lebih kecil */
            height: 1.4rem; /* Lebih kecil */
        }

        /* Styling checkbox */
        .process-checkbox {
            width: 14px;
            height: 14px;
            margin-right: 3px;
        }

        /* Style untuk checkbox kustom */
        .custom-checkbox {
            width: 16px;
            height: 16px;
            background-color: #f0f0f0;
            border-radius: 4px;
            border: 1px solid #ccc;
            transition: 0.3s;
            cursor: pointer;
        }

        .custom-checkbox:checked {
            background-color: #4CAF50;
        }

        /* Perkecil ukuran scrollbar */
        .checkbox-container::-webkit-scrollbar {
            height: 2px;
        }

        .checkbox-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .checkbox-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 6px;
        }

        .checkbox-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Ukuran font navbar diperkecil */
        .navbar-nav .nav-link {
            font-size: 0.65rem;
        }

        /* Jarak antar checkbox */
        .checkbox-container div {
            margin-right: 5px;
        }

        .checkbox-container input[type='checkbox'] {
            margin-right: 2px;
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .low-skill {
            background-color:rgb(219, 186, 0);
        }

        /* Container utama untuk semua kualifikasi */
        .qualification-container {
            position: relative;
            width: 100%;
            height: 780px; /* Tinggi tetap */
            margin-top: 20px;
        }

        /* Style umum untuk semua konten kualifikasi */
        .qualification-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: none;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            overflow-y: auto; /* Scroll internal */
        }

        /* Aktifkan display block untuk konten yang tampil */
        .qualification-content.active {
            display: block;
        }

        .content-wrapper {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Styling header panel */
        .p-process-panel-text {
            background: #171a42;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #profile-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: calc(100vh - 3rem);
            overflow-y: auto; /* Tambahkan agar bisa di-scroll */
        }

        .pt-chart-info {
            font-size: 1rem;
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            flex-grow: 1;
            flex-basis: 0px;
            align-items: center;
            width: 100%;
        } 

        .profile-left {
            display: flex;
            flex-direction: column;
            flex: 1;
            max-height: 200%;
        }
    </style>

    <div id='dark-overlay' class='hidden'></div>
    <div class="popup popup-small popup hidden" id="update-popup">
        <div class='popup-content-wrapper h-100'>
            <p>Are you sure you want to update <?php echo $karyawan['name'] ?>?</p>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="#" class="cu-cancel-btn m-1"
                    onclick="hide_popup('#update-popup'); show_popup('#edit-assessment-popup')">Cancel</a>
                <label for="cu-submit-btn" tabindex="0" class='cu-submit-btn m-1'>Confirm</label>
            </div>
        </div>
    </div>
    <div class="popup popup-small hidden" id="delete-popup">
        <div class='popup-content-wrapper h-100'>
            <p>Are you sure you want to delete <?php echo $karyawan['name'] ?>?</p>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="#" class="cu-cancel-btn m-1"
                    onclick="hide_popup('#delete-popup'); show_popup('#edit-assessment-popup')">Cancel</a>
                <label for="cu-delete-btn" tabindex="0" class='cu-delete-btn m-1'>Confirm</label>
            </div>
        </div>
    </div>
    <div class="popup hidden" id="edit-process-popup">
        <div class="popup-content-wrapper h-100">
            <p>Edit Process Qualification for <?php echo $karyawan['name'] ?></p>
            <div class="d-flex flex-row w-100">
                <p class="pl-3 m-0 w-25">Name</p>
                <p class="m-0 m-1">Score</p>
                <p class="mb-1 ml-auto mr-1">Min Score</p>
                <p class="mb-2 ml-auto mr-2">Jadwal Training</p>
                <p class="mb-3 ml-auto mr-3">Status</p>
            </div>
            <?php include ('includes/components/process-edit-panel.php'); ?>
            <div class="d-flex flex-row justify-content-between w-100">
                <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Back</a>
                <label for="q-submit-btn" tabindex="0" class="cu-submit-btn m-1">Confirm</label>
            </div>
        </div>
    </div>

    <div class='popup hidden' id='edit-ehs-popup'>
        <div class='popup-content-wrapper h-100'>
            <p>Edit EHS Qualification for <?php echo $karyawan['name'] ?></p>
            <div class='d-flex flex-row w-100'>
                <p class="pl-3 m-0 w-25">Name</p>
                <p class="m-0 m-1">Score</p>
                <p class="mb-1 ml-auto mr-1">Min Score</p>
                <p class="mb-2 ml-auto mr-2">Jadwal Training</p>
                <p class="mb-3 ml-auto mr-3">Status</p>
            </div>
            <?php include ('includes/components/ehs-edit-panel.php'); ?>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Back</a>
                <label for="q2-submit-btn" tabindex="0" class='cu-submit-btn m-1'>Confirm</label>
            </div>
        </div>
    </div>
    <div class='popup hidden' id='edit-quality-popup'>
        <div class='popup-content-wrapper h-100'>
            <p>Edit Quality Qualification for <?php echo $karyawan['name'] ?></p>
            <div class='d-flex flex-row w-100'>
                <p class="pl-3 m-0 w-25">Name</p>
                <p class="m-0 m-1">Score</p>
                <p class="mb-1 ml-auto mr-1">Min Score</p>
                <p class="mb-2 ml-auto mr-2">Jadwal Training</p>
                <p class="mb-3 ml-auto mr-3">Status</p>
            </div>`
            <?php include ('includes/components/quality-edit-panel.php'); ?>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Back</a>
                <label for="q3-submit-btn" tabindex="0" class='cu-submit-btn m-1'>Confirm</label>
            </div>
        </div>
    </div>


    <div class='popup hidden' id='edit-assessment-popup'>
        <div class='popup-content-wrapper h-100'>
            <p>Edit Assessment <?php echo $karyawan['name'] ?></p>
            <?php include ('includes/components/update-assesment-panel.php'); ?>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Back</a>
            </div>
        </div>
    </div>

    <div class='popup hidden' id='edit-s-process-popup'>
        <div class='popup-content-wrapper h-100'>
            <p>Edit S-Process Qualification for <?php echo $karyawan['name'] ?></p>
            <?php include ('includes/components/s-process-edit-panel.php'); ?>
            <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Back</a>
                <label for="qq-submit-btn" tabindex="0" class='cu-submit-btn m-1'>Confirm</label>
            </div>
        </div>
    </div>


    <div class='popup hidden' id='edit-picture-popup'>
        <div class='popup-content-wrapper h-100'>
            <form action="actions/edit-picture.php?q=<?php echo $karyawan['npk']; ?>" method="post"
                class='w-100 h-100 d-flex flex-column align-items-center' enctype="multipart/form-data">
                <p>Edit Picture for <?php echo $karyawan['name'] ?></p>
                <input type="file" id="ap-form-photo" name="ap-form-photo" accept="image/*" class='m-5'>

                <div class='d-flex-row justify-content-between w-100 flex-float-bottom'>
                    <a href="preview_member.php?q=<?= $karyawan['npk'] ?>" class="cu-cancel-btn m-1">Cancel</a>
                    <input type="submit" name="update" class="cu-submit-btn"></input>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup untuk menampilkan riwayat skill -->
    <div id="skill-history-process-popup" style="display:none;">
        <div class="popup-content">
            <span class="close" onclick="closeSkillHistoryProcessPopup()">&times;</span>
            <h3>Riwayat Skill Process Qualifications</h3>
            <div class="scrollable-container">
                <div id="skill-history-process-content" class="table-responsive"></div>
            </div>
        </div>
    </div>

    <!-- Popup untuk menampilkan riwayat skill -->
    <div id="skill-history-quality-popup" style="display:none;">
        <div class="popup-content">
            <span class="close" onclick="closeSkillHistoryQualityPopup()">&times;</span>
            <h3>Riwayat Skill Quality</h3>
            <div class="scrollable-container">
                <div id="skill-history-quality-content" class="table-responsive"></div>
            </div>
        </div>
    </div>

    <!-- Popup untuk menampilkan riwayat skill -->
    <div id="skill-history-ehs-popup" style="display:none;">
        <div class="popup-content">
            <span class="close" onclick="closeSkillHistoryEHSPopup()">&times;</span>
            <h3>Riwayat Skill EHS</h3>
            <div class="scrollable-container">
                <div id="skill-history-ehs-content" class="table-responsive"></div>
            </div>
        </div>
    </div>

    <!-- Pop-up untuk Tambah Process -->
    <div class="popup hidden" id="add-process-popup">
        <div class="popup-content-wrapper">
            <p>Tambah Process Baru</p>
            <form action="actions/add-process.php" method="post">
                <div class="form-group">
                    <label for="process_name">Nama Process</label>
                    <input type="text" class="form-control" id="process_name" name="process_name" required>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                    </div>
                <div class="form-group">
                    <label for="workstation_id">Workstation</label>
                    <select class="form-control" id="workstation_id" name="workstation_id" required>
                        <?php
                        $q_workstation = $conn->query("SELECT id, name FROM sub_workstations");
                        while ($row = $q_workstation->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="min_skill">Minimal Skill</label>
                    <input type="number" class="form-control" id="min_skill" name="min_skill" min="1" max="5" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#add-process-popup')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up untuk Hapus Process -->
    <div class="popup hidden" id="delete-process-popup">
        <div class="popup-content-wrapper">
            <p>Hapus Process</p>
            <form action="actions/delete-process.php" method="post">
                <div class="form-group">
                    <label for="process_id">Pilih Process</label>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                    <select class="form-control" id="process_id" name="process_id" required>
                        <?php
                        $q_process = $conn->query("SELECT id, name FROM process");
                        while ($row = $q_process->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#delete-process-popup')">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up untuk Tambah Quality -->
    <div class="popup hidden" id="add-quality-popup">
        <div class="popup-content-wrapper">
            <p>Tambah Quality Baru</p>
            <form action="actions/add-quality.php" method="post">
                <div class="form-group">
                    <label for="quality_name">Nama Quality</label>
                    <input type="text" class="form-control" id="quality_name" name="quality_name" required>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                </div>
                <div class="form-group">
                    <label for="workstation_id">Workstation</label>
                    <select class="form-control" id="workstation_id" name="workstation_id" required>
                        <?php
                        $q_workstation = $conn->query("SELECT id, name FROM sub_workstations");
                        while ($row = $q_workstation->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="min_skill">Minimal Skill</label>
                    <input type="number" class="form-control" id="min_skill" name="min_skill" min="1" max="5" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#add-quality-popup')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up untuk Hapus Quality -->
    <div class="popup hidden" id="delete-quality-popup">
        <div class="popup-content-wrapper">
            <p>Hapus Quality</p>
            <form action="actions/delete-quality.php" method="post">
                <div class="form-group">
                    <label for="quality_id">Pilih Quality</label>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                    <select class="form-control" id="quality_id" name="quality_id" required>
                        <?php
                        $q_quality = $conn->query("SELECT id, name FROM quality");
                        while ($row = $q_quality->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#delete-quality-popup')">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up untuk Tambah EHS -->
    <div class="popup hidden" id="add-ehs-popup">
        <div class="popup-content-wrapper">
            <p>Tambah EHS Baru</p>
            <form action="actions/add-ehs.php" method="post">
                <div class="form-group">
                    <label for="ehs_name">Nama EHS</label>
                    <input type="text" class="form-control" id="ehs_name" name="ehs_name" required>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                </div>
                <div class="form-group">
                    <label for="workstation_id">Workstation</label>
                    <select class="form-control" id="workstation_id" name="workstation_id" required>
                        <?php
                        $q_workstation = $conn->query("SELECT id, name FROM sub_workstations");
                        while ($row = $q_workstation->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="min_skill">Minimal Skill</label>
                    <input type="number" class="form-control" id="min_skill" name="min_skill" min="1" max="5" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#add-ehs-popup')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up untuk Hapus EHS -->
    <div class="popup hidden" id="delete-ehs-popup">
        <div class="popup-content-wrapper">
            <p>Hapus EHS</p>
            <form action="actions/delete-ehs.php" method="post">
                <div class="form-group">
                    <label for="ehs_id">Pilih EHS</label>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                    <select class="form-control" id="ehs_id" name="ehs_id" required>
                        <?php
                        $q_ehs = $conn->query("SELECT id, name FROM ehs");
                        while ($row = $q_ehs->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#delete-ehs-popup')">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up Tambah S-Process -->
    <div class="popup hidden" id="add-s-process-popup">
        <div class="popup-content-wrapper">
            <p>Tambah S-Process Baru</p>
            <form action="actions/add-s-process.php" method="post">
                <div class="form-group">
                    <label for="s_process_name">Nama S-Process</label>
                    <input type="text" class="form-control" id="s_process_name" name="s_process_name" required>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                </div>
                <div class="form-group">
                    <label for="workstation_id">Workstation</label>
                    <select class="form-control" id="workstation_id" name="workstation_id" required>
                        <?php
                        $q_workstation = $conn->query("SELECT id, name FROM workstations");
                        while ($row = $q_workstation->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#add-s-process-popup')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up Hapus S-Process -->
    <div class="popup hidden" id="delete-s-process-popup">
        <div class="popup-content-wrapper">
            <p>Hapus S-Process</p>
            <form action="actions/delete-s-process.php" method="post">
                <div class="form-group">
                    <label for="s_process_id">Pilih S-Process</label>
                    <input type="hidden" name="npk" value="<?= htmlspecialchars($_GET['q']) ?>">
                    <select class="form-control" id="s_process_id" name="s_process_id" required>
                        <?php
                        $q_sprocess = $conn->query("SELECT id, name FROM s_process");
                        while ($row = $q_sprocess->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="hide_popup('#delete-s-process-popup')">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="profile-container">
        <div class="profile-left">
            <div id="page-title" class="w-100">
                <p>Assessment Production MP</p>
            </div>
            <div class="w-100 pl-3 mb-1">
                <?php
                echo "
                <span>
                    <a href='index.php'>Home</a>
                </span>
                <span> / </span>
                <span>
                    <a href='department_workstations.php?q=" . $karyawan['dept_id'] . "'>" . $karyawan['dept_name'] . "</a>
                </span>";
                if ($karyawan['ws_name'] != $karyawan['sub_ws_name'])
                    echo "
                <span> / </span>
                <span>
                    <a href='workstation_members.php?q=" . $karyawan['ws_id'] . "'>" . $karyawan['ws_name'] . "</a>
                </span>";
                echo "
                <span> / </span>
                <span>
                    <a href='sub_workstation_members.php?q=" . $karyawan['sub_ws_id'] . "'>" . $karyawan['sub_ws_name'] . "</a>
                </span>
                <span> / </span>
                <span>
                    " . $karyawan['name'] . "
                </span>
                ";
                ?>
            </div>
            <div class='p-title ml-3 shadow' style='flex: 1 ; height: 20vh; width: 59rem'>
                <?php
                if ($is_admin) {
                    ?>
                    <a href='#' onclick="show_popup('#edit-picture-popup')"
                        class="p-picture-container position-relative h-100  ">
                        <img src="
                        <?php
                        $img_path = "img/profile_pictures/" . $karyawan['npk'] . ".jpg";
                        if (file_exists($img_path))
                            echo $img_path;
                        else
                            echo "img/profile_pictures/default.jpg";
                        ?>
                    "></img>
                        <?php
                        ?>
                        <div id='p-picture-edit-overlay' class='w-100 h-100 justify-content-center align-items-center'>
                            <p>EDIT</p>
                        </div>
                    </a>
                    <?php
                } else {
                    ?>
                    <a class="p-picture-container position-relative  h-100">
                        <img src="
                        <?php
                        $img_path = "img/profile_pictures/" . $karyawan['npk'] . ".jpg";
                        if (file_exists($img_path))
                            echo $img_path;
                        else
                            echo "img/profile_pictures/default.jpg";
                        ?>
                    "></img>
                        <?php
                        ?>

                    </a>
                    <?php
                }
                ?>
                <div class="p-title-container">
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Nama</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php echo $karyawan['name'] ?></p>
                        </span>
                    </div>
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>NPK</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php echo $karyawan['npk'] ?></p>
                        </span>
                    </div>
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Department</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php echo $karyawan['dept_name'] ?></p>
                        </span>
                    </div>
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Role</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php
                            echo $karyawan['role_name'];
                            ?></p>
                        </span>
                    </div>
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Date Joined</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php
                            // YYYY-MM-DD TO DD-MM-YYYY
                            echo join("-", array_reverse(explode("-", $karyawan['date_joined'])));
                            ?></p>
                        </span>
                    </div>
                    <?php
                    if ($is_foreman) {
                        $workstations_query = $conn->query("
                            SELECT sub_workstations.name as name 
                            FROM karyawan_workstation
                            JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                            WHERE karyawan_workstation.npk = '" . $karyawan['npk'] . "'
                        ");
                        $workstations_string = '';
                        while ($workstations_row = $workstations_query->fetch_assoc()) {
                            $workstations_string .= $workstations_row['name'] . ', ';
                        }
                        echo "
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Workstations</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p>" . substr($workstations_string, 0, -2) . "</p>
                        </span>
                    </div>
                            ";
                    }
                    ?>
                    <div class='d-flex-row p-name-text'>
                        <span class='w-25'>
                            <p>Last Updated</p>
                        </span>
                        <span>
                            <p>:</p>
                        </span>
                        <span class='w-75 pl-1'>
                            <p><?php
                            // YYYY-MM-DD TO DD-MM-YYYY
                            $last_updated_query = $conn->query("
                            SELECT 
                    karyawan.npk,
                    karyawan.name,
                    CASE
                        WHEN qualifications.created_at >= qualifications_ehs.created_at 
                             AND qualifications.created_at >= qualifications_quality.created_at THEN qualifications.created_at
                        WHEN qualifications_ehs.created_at >= qualifications.created_at 
                             AND qualifications_ehs.created_at >= qualifications_quality.created_at THEN qualifications_ehs.created_at
                        WHEN qualifications_quality.created_at >= qualifications.created_at 
                             AND qualifications_quality.created_at >= qualifications_ehs.created_at THEN qualifications_quality.created_at
                        ELSE '0000-00-00 00:00:00'
                    END AS last_created_at
                FROM karyawan 
                LEFT JOIN karyawan_workstation ON karyawan_workstation.npk = karyawan.npk
                LEFT JOIN sub_workstations ON karyawan_workstation.workstation_id = sub_workstations.id
                LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
                LEFT JOIN roles ON karyawan.role = roles.id
                LEFT JOIN qualifications ON karyawan.npk = qualifications.npk
                LEFT JOIN qualifications_ehs ON karyawan.npk = qualifications_ehs.npk
                LEFT JOIN qualifications_quality ON karyawan.npk = qualifications_quality.npk
                LEFT JOIN process AS process_value ON process_value.id = qualifications.process_id
                LEFT JOIN ehs AS ehs_value ON ehs_value.id = qualifications_ehs.ehs_id
                LEFT JOIN quality AS quality_value ON quality_value.id = qualifications_quality.quality_id
                WHERE karyawan.npk = '" . $karyawan['npk'] . "'
                ORDER BY last_created_at DESC
                LIMIT 1
                        ");
                            while ($last_updated__row = $last_updated_query->fetch_assoc()) {

                                $date = new DateTime($last_updated__row['last_created_at']);

                                // Format tanggal ke format yang formal
                                $formattedDate = $date->format('d-m-Y H:i');
                                echo $formattedDate;
                            }
                            ?></p>
                        </span>
                    </div>
                </div>
                <?php
                if ((isset($_SESSION['dept']) && $_SESSION['dept'] === 'PROD' && $is_produksi) || (isset($_SESSION['dept']) && $_SESSION['dept'] === 'HRD' && $is_hrd)) {
                ?>
                    <a href='update_profile.php?npk=<?php echo $karyawan['npk']; ?>&dept_id=<?php echo $karyawan['dept_id']; ?>' style='flex: 1'>
                        <img class='edit-profile-button' src="img/edit-solid.png" alt="" style="border-radius: 0px 0px 0px 0px">
                    </a>
                <?php
                }
                ?>
            </div>

            <div class='d-flex flex-row' style='flex: 1 ; height: 60vh; width: 60rem'>
                <div class="p-stats m-3 p-3 background-light align-items-top justify-content-center d-flex flex-row"
                    style='flex: 1; margin-right: 0px!important;'>
                    <div class='p-stats m-2 background-light d-flex flex-column'
                        style='flex: 1; margin-right: 0px!important;'>

                        <div class='w-100 d-flex flex-row justify-content-around align-items-center p-1'
                            style='border-bottom: dotted; height: 2rem'>
                            <p class='m-0' style='color: #FF1313; font-weight: 800; font-size: 1rem;'>KPQ</p>
                            <?php
                     if (isset($_SESSION['dept']) && $_SESSION['dept'] == 'QA') {
                        ?>
                            <a href='#' onclick='show_popup("#edit-assessment-popup")' class='d-flex justify-content-center align-items-center'>
                                <img class='edit-profile-button' src='img/edit-solid.png' alt='' style='width: 2.5rem;'>
                            </a>
                        <?php
                        }
                        ?>
                        </div>

                        <div class="p-radar-container">
                            <?php
                            $member = $karyawan;
                            include ('includes/components/personal-radarchart.php');
                            ?>
                        </div>
                        <div id="p-stats-numeric">
                            <?php
                            foreach ($mp_categories as $mp_name => $mp_label) {
                                echo "
                <div class='d-flex-row p-name-text m-0'>
                    <span class='w-100'>
                        <p class='m-0' style='font-size: 0.8rem;'>$mp_label</p>
                    </span>
                    <span class='w-100'>
                        <p class='m-0' style='font-size: 0.8rem;'>: " . $karyawan[$mp_name] . "</p>
                    </span>
                </div>";
                        }
                            ?>
                        </div>
                    </div>
                    <div class='vertical-divider'></div>
                    <div class='p-stats m-2 background-light d-flex flex-column'
                        style='flex: 1; margin-right: 0px!important;'>
                        <div class='w-100 d-flex justify-content-around align-items-center p-1'
                            style='border-bottom: dotted; height: 2rem'>
                            <p class='m-0' style='color: #FF1313; font-weight: 800; font-size: 1rem;'>S-PROCESS</p>
                            <?php
                            if (isset($_SESSION['dept']) && ($_SESSION['dept'] === 'QA' || $_SESSION['dept'] === 'APROD')) {
                            ?>
                            <a href='#' onclick='show_popup("#edit-s-process-popup")'
                                class='d-flex justify-content-center align-items-center'>
                                <img class='edit-profile-button' src="img/edit-solid.png" alt="" style="width: 2.5rem;">
                            </a>
                            <?php
                            }
                            ?>
                        </div>
                        <div class='mt-1 h-100 overflow-auto'>
                            <ul class="h-100 pr-1 m-0" style='font-size: 0.85rem;'>
                                <?php
                                $q_res = $conn->query(
                                    "SELECT DISTINCT s_process.name as name FROM karyawan
                    JOIN s_process_certification ON karyawan.npk = s_process_certification.npk
                    JOIN s_process ON s_process_certification.s_process_id = s_process.id
                    WHERE karyawan.npk = '" . $karyawan['npk'] . "'
                    ORDER BY s_process.name ASC"
                                );
                                while ($q_row = $q_res->fetch_assoc()) {
                                    echo "
                    <li>" . $q_row['name'] . "</li>
                    ";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class='d-flex mb-2 align-items-center justify-content-center col-md-auto'
                style=' width: 69rem; height: 15rem ; margin-left: -40px; margin-bottom: 300px '>
                <div class="profile-table background-light d-inline-flex flex-column">
                    <div class="pt-tab pt-chart-info hidden">
                        <div class='w-100 h-100 background-light'
                            style='flex-grow: 1; flex-shrink: 1; flex-basis: 0px; overflow: auto; border-radius: 20px;'>

                            <div class='w-100 d-flex flex-column align-items-center'>
                                <p class='m-0 mt-3' style='font-size: 1.5rem'>Manpower History</p>
                                <ul class='w-100 ps-1' id="manpowerList">
                                    <?php
                                    $query = "SELECT
                                        start_time,
                                        end_time,
                                        sub_workstations.name as sw_name,
                                        workstations.name as w_name,
                                        department.dept_name as d_name
                                    FROM relocate_history
                                    LEFT JOIN sub_workstations ON relocate_history.subworkstation_id = sub_workstations.id
                                    LEFT JOIN workstations ON sub_workstations.workstation_id = workstations.id
                                    LEFT JOIN department ON workstations.dept_id = department.id
                                    WHERE relocate_history.npk = '" . $karyawan['npk'] . "' ORDER BY start_time DESC";
                                    $q_res = $conn->query($query);
                                    $total_count = $q_res->num_rows;  // Get total number of results
                                    $count = 0; // Counter for items displayed
                                    while ($q_row = $q_res->fetch_assoc()) {
                                        $count++;
                                        $end_time = $q_row['end_time'] == '' ? 'Present' : date("d-m-Y", strtotime($q_row['end_time']));
                                        $start_time = date("d-m-Y", strtotime($q_row['start_time']));
                                        $relocate_ws = $q_row['d_name'] . " - " . ($q_row['sw_name'] == $q_row['w_name'] ? $q_row['w_name'] : $q_row['w_name'] . " - " . $q_row['sw_name']);

                                        echo "
                                            <li class='mt-1 manpower-item' style='" . ($count > 5 ? "display: none;" : "") . "'>
                                                <p class='m-0' style='font-weight:600;'>" . $relocate_ws . "</p>
                                                <p class='m-0'>" . $start_time . " to " . $end_time . "</p>
                                            </li>
                                        ";
                                    }
                                    ?>
                                </ul>
                                <div style='margin-top: 10px;'>
                                    <?php if ($total_count > 5): ?>
                                        <button id='showMoreBtn' onclick='showMore()' class="btn btn-link"
                                            style='margin-right: 10px;'>Show
                                            More<i class="bi bi-chevron-down"></i></button>
                                        <button id='showLessBtn' onclick='showLess()' class="btn btn-link"
                                            style='display: none;'>Show
                                            Less <i class="bi bi-chevron-up"></i></button>
                                    <?php endif; ?>
                                </div>
                                <div class='w-100 px-1'>
                                    <div class='w-100' style='border-bottom: 2px dashed'></div>
                                </div>
                            </div>

                            <script>
                                let currentShown = 5; // Start by showing 5 items
                                function showMore() {
                                    let items = document.querySelectorAll('.manpower-item');
                                    let maxToShow = currentShown + 5; // Increase the number of items to show by 5
                                    for (let i = currentShown; i < maxToShow && i < items.length; i++) {
                                        items[i].style.display = 'block'; // Change display style to block to show items
                                    }
                                    currentShown = maxToShow; // Update the currentShown counter
                                    document.getElementById('showLessBtn').style.display = 'inline'; // Show the Show Less button
                                    if (currentShown >= items.length) {
                                        document.getElementById('showMoreBtn').style.display = 'none'; // Hide the button if all items are shown
                                    }
                                }

                                function showLess() {
                                    let items = document.querySelectorAll('.manpower-item');
                                    for (let i = items.length - 1; i >= 5; i--) {
                                        items[i].style.display = 'none'; // Hide items beyond the first 5
                                    }
                                    currentShown = 5; // Reset the count to the initial 5 items
                                    document.getElementById('showMoreBtn').style.display = 'inline'; // Show the Show More button again
                                    document.getElementById('showLessBtn').style.display = 'none'; // Hide the Show Less button since we're back to the initial state
                                }
                            </script>
                            <div class='w-100 d-flex align-items-center justify-content-center'>
                                <p class='m-0 mt-3' style='font-size: 1.5rem'>Information</p>
                            </div>
                            <div class="float-right">
                            <a href='preview_mp_file.php?q=<?php echo $karyawan['npk']; ?>'
                                class='cu-cancel-btn mp-file-img-container small-link d-flex flex-column align-items-center'>
                                <img class="ml-1 align-items-center" src='img/file-import-solid-white.png'></img>
                            </a>
                        </div>

                        <?php
                        $q_res = $conn->query("
                        SELECT id, filename, name, description, posted_time 
                        FROM mp_file_proof 
                        WHERE npk = '" . $_GET['q'] . "' AND posted_time > DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");

                        if ($q_res->num_rows == 0) {
                            echo "<p class='ml-3'>No files found.</p>";
                        } else {
                            echo "
                            <style>
                                .file-name {
                                    font-size: 20px;
                                    font-weight: bold;
                                }
                            </style>";

                            while ($file_row = $q_res->fetch_assoc()) {
                                echo "
                                <div class='file-preview-container w-100 p-3'>
                                    <p>" . $file_row['posted_time'] . "</p>
                                    <a href='files/" . $file_row['filename'] . "' target='_blank'>
                                        <p class='file-name'>" . $file_row['name'] . "</p>
                                    </a>
                                    <p>Description: " . $file_row['description'] . "</p>
                                    <a href='actions/delete-file.php?q=" . $file_row['id'] . "'>
                                        <button class='cu-submit-btn'>Delete</button>
                                    </a>
                                </div>";
                            }
                        }                                                
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-right ">
            <?php
            // if (!$is_foreman) {
            //     include ("includes/components/member-qualification-panel.php");
            // }
            ?>
        
        <div class="container mt-3">
            <div class="row justify-content-center">
                <button id="process-qualification-btn" class="btn custom-btn btn-blue me-1">Process Qualification</button>
                <button id="quality-btn" class="btn custom-btn btn-red me-1">Quality</button>
                <button id="ehs-btn" class="btn custom-btn btn-green me-1">EHS</button>
            </div>
        </div>

        <!-- Div yang berisi data PROCESS (disembunyikan secara default) -->
        <div class="qualification-container">
        <div id="process-qualification-content" class="qualification-content">
        <div class="content-wrapper">
            <div class='d-flex justify-content-center w-100' style='height: 190%; width: 250rem;'>
                <div class='p-process-panel d-flex flex-column align-items-center p-1' style="width: 150%; overflow-y: auto; max-height: 165vh;">
                    <div class='p-process-panel-text d-flex flex-row justify-content-between w-100 pl-1 pr-1' style='height: 2.5rem;'>
                        <p class='m-0'>Process Qualification</p>
                        <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] === 'PROD' || $_SESSION['dept'] === 'HRD' || $_SESSION['dept'] === 'APROD')) { ?>
                            <a href='#' onclick='show_popup("#edit-process-popup")' id='process-edit-btn' class='d-flex justify-content-center align-items-center'>
                                <p class='m-0'>EDIT</p>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="w-100 p-1" style='height: 150%; overflow-y: auto; max-height: calc(900vh - 8rem);'>
                            <?php
                            $q_res = $conn->query("
                            SELECT 
                                process.id AS process_id,
                                process.name AS name, 
                                process.min_skill AS min_skill, 
                                qualifications.value AS skill, 
                                karyawan.npk AS npk,
                                MONTH(qualifications.jadwal_training_process) AS training_month
                                FROM process
                                JOIN sub_workstations ON process.workstation_id = sub_workstations.id
                                JOIN karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                                JOIN karyawan ON karyawan_workstation.npk = karyawan.npk
                                JOIN qualifications ON karyawan.npk = qualifications.npk AND process.id = qualifications.process_id
                                WHERE karyawan.npk = '" . $karyawan['npk'] . "'
                                AND qualifications.status = 1
                                ORDER BY sub_workstations.name, process.name"
                            );

                            while ($q_row = $q_res->fetch_assoc()) {
                                $class = ($q_row['skill'] < $q_row['min_skill']) ? 'low-skill' : '';
                                $fontBold = ($q_row['skill'] < $q_row['min_skill']) ? 'font-weight: bolder;' : '';

                                echo "<div class='p-process-panel-list-item d-flex flex-row $class'>
                            <p class='m-0 w-75' style='$fontBold'>" . $q_row['name'] . "</p>
                            <div class='d-flex flex-row align-items-center'>
                                <p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row['skill']}.png'/>
                                <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Req: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row['min_skill']}.png'/>
                            </div>
                            <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Bulan Training: </p>
                            <div class='month-container'>
                                <div class='month-labels'>";
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<span class='month-label' style='$fontBold'>$i</span>";
                            }
                            echo "</div><div class='month-boxes'>";
                            for ($i = 1; $i <= 12; $i++) {
                                $class = ($i == $q_row['training_month'] && $q_row['skill'] < $q_row['min_skill']) ? 'month-box highlighted' : 'month-box';
                                echo "<div class='$class'></div>";
                            }
                            echo "</div></div>
                            <a href='#' onclick='showSkillHistoryProcessPopup(" . $q_row['process_id'] . ", \"" . $q_row['npk'] . "\")' class='ml-2 btn btn-info'>Riwayat Skill</a>
                            </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Div yang berisi data Quality (disembunyikan secara default) -->
        <div id="quality-qualification-content" class="qualification-content">
        <div class="content-wrapper">
        <div class='d-flex justify-content-center w-100' style='height: 190%; width: 250rem;'>
                <div class='p-process-panel d-flex flex-column align-items-center p-1' style="width: 150%; overflow-y: auto; max-height: 165vh;">
                    <div class='p-process-panel-text d-flex flex-row justify-content-between w-100 pl-1 pr-1' style='height: 2.5rem;'>
                        <p class='m-0'>Quality</p>
                        <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] === 'PROD' || $_SESSION['dept'] === 'HRD' || $_SESSION['dept'] === 'APROD')) { ?>
                            <a href='#' onclick='show_popup("#edit-quality-popup");' id='quality-edit-btn' class='d-flex justify-content-center align-items-center'>
                                <p class='m-0'>EDIT</p>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="w-100 p-1" style='height: 150%; overflow-y: auto; max-height: calc(900vh - 8rem);'>
                            <?php
                            $q_res2 = $conn->query(
                                "SELECT 
                                quality.id AS quality_id, 
                                quality.name AS name, 
                                quality.min_skill AS min_skill, 
                                qualifications_quality.value AS skill,
                                karyawan.npk AS npk,
                                MONTH(qualifications_quality.jadwal_training_quality) AS training_month
                                FROM quality
                                JOIN sub_workstations ON quality.workstation_id = sub_workstations.id
                                JOIN karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                                JOIN karyawan ON karyawan_workstation.npk = karyawan.npk
                                JOIN qualifications_quality ON karyawan.npk = qualifications_quality.npk AND quality.id = qualifications_quality.quality_id
                                WHERE karyawan.npk = '" . $karyawan['npk'] . "' 
                                AND qualifications_quality.status = 1
                                ORDER BY sub_workstations.name, quality.name
                            ");
                            
                            while ($q_row2 = $q_res2->fetch_assoc()) {
                                $training_months = array();
                                $training_months[] = $q_row['training_month'];
                                $class = ($q_row2['skill'] < $q_row2['min_skill']) ? 'low-skill' : '';
                                $fontBold = ($q_row2['skill'] < $q_row2['min_skill']) ? 'font-weight: bolder;' : '';
                                $fontMargin = ($q_row2['skill'] >= $q_row2['min_skill']) ? 'margin-right: 40px;' : '';

                                echo "<div class='p-process-panel-list-item d-flex flex-row $class'>
                                <p class='m-0 w-75' style='$fontBold'>" . $q_row2['name'] . "</p>
                                <div class='d-flex flex-row align-items-center'>
                                <p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row2['skill']}.png'/>
                                <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Req: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row2['min_skill']}.png'/>
                            </div>
                            <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Bulan Training: </p>
                            <div class='month-container'>
                                <div class='month-labels'>";
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<span class='month-label' style='$fontBold'>$i</span>";
                            }
                            echo "</div><div class='month-boxes'>";
                            for ($i = 1; $i <= 12; $i++) {
                                $class = ($i == $q_row2['training_month'] && $q_row2['skill'] < $q_row2['min_skill']) ? 'month-box highlighted' : 'month-box';
                                echo "<div class='$class'></div>";
                            }
                            echo "</div></div>
                            <a href='#' onclick='showSkillHistoryQualityPopup(" . $q_row2['quality_id'] . ", \"" . $q_row2['npk'] . "\")' class='ml-2 btn btn-info'>Riwayat Skill</a>
                            </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Div yang berisi data EHS (disembunyikan secara default) -->
        <div id="ehs-qualification-content" class="qualification-content">
        <div class="content-wrapper">
            <div class='d-flex justify-content-center w-100' style='height: 190%; width: 250rem;'>
                <div class='p-process-panel d-flex flex-column align-items-center p-1' style="width: 150%; overflow-y: auto; max-height: 165vh;">
                    <div class='p-process-panel-text d-flex flex-row justify-content-between w-100 pl-1 pr-1' style='height: 2.5rem;'>
                        <p class='m-0'>EHS</p>
                        <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] === 'PROD' || $_SESSION['dept'] === 'HRD' || $_SESSION['dept'] === 'APROD')) { ?>
                            <a href='#' onclick='show_popup("#edit-ehs-popup");' id='ehs-edit-btn' class='d-flex justify-content-center align-items-center'>
                                <p class='m-0'>EDIT</p>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="w-100 p-1" style='height: 150%; overflow-y: auto; max-height: calc(900vh - 8rem);'>
                        <?php
                        // Query untuk mengambil data skill
                        $q_res2 = $conn->query(
                            "SELECT 
                            ehs.id AS ehs_id,
                            ehs.name AS name, 
                            ehs.min_skill AS min_skill, 
                            qualifications_ehs.value AS skill,
                            karyawan.npk AS npk,
                            MONTH(qualifications_ehs.jadwal_training_ehs) AS training_month
                            FROM ehs
                            JOIN sub_workstations ON ehs.workstation_id = sub_workstations.id
                            JOIN karyawan_workstation ON sub_workstations.id = karyawan_workstation.workstation_id
                            JOIN karyawan ON karyawan_workstation.npk = karyawan.npk
                            JOIN qualifications_ehs ON karyawan.npk = qualifications_ehs.npk AND ehs.id = qualifications_ehs.ehs_id
                            WHERE karyawan.npk = '" . $karyawan['npk'] . "'
                            AND qualifications_ehs.status = 1
                            ORDER BY sub_workstations.name, ehs.name"
                        );

                        while ($q_row2 = $q_res2->fetch_assoc()) {
                            $training_months = array();
                            $training_months[] = $q_row2['training_month'];
                            $class = ($q_row2['skill'] < $q_row2['min_skill']) ? 'low-skill' : '';
                            $fontBold = ($q_row2['skill'] < $q_row2['min_skill']) ? 'font-weight: bolder;' : '';
                            $fontMargin = ($q_row2['skill'] >= $q_row2['min_skill']) ? 'margin-right: 40px;' : '';

                            echo "<div class='p-process-panel-list-item d-flex flex-row $class'>
                            <p class='m-0 w-75' style='$fontBold'>" . $q_row2['name'] . "</p>
                            <div class='d-flex flex-row align-items-center'>
                                <p class='m-0 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Skill: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row2['skill']}.png'/>
                                <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Req: </p>
                                <img style='width: 1.5rem; height: 1.5rem' src='img/C{$q_row2['min_skill']}.png'/>
                            </div>
                            <p class='m-0 ml-3 mt-auto mr-1' style='font-size:0.7rem; $fontBold'>Bulan Training: </p>
                            <div class='month-container'>
                                <div class='month-labels'>";
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<span class='month-label' style='$fontBold'>$i</span>";
                            }
                            echo "</div><div class='month-boxes'>";
                            for ($i = 1; $i <= 12; $i++) {
                                $class = ($i == $q_row2['training_month'] && $q_row2['skill'] < $q_row2['min_skill']) ? 'month-box highlighted' : 'month-box';
                                echo "<div class='$class'></div>";
                            }
                            echo "</div></div>
                            <a href='#' onclick='showSkillHistoryEhsPopup(" . $q_row2['ehs_id'] . ", \"" . $q_row2['npk'] . "\")' class='ml-2 btn btn-info'>Riwayat Skill</a>
                            </div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- script button -->
        <script>
        function showSkillHistoryProcessPopup(process_id, npk) {
            // Cek apakah tombol diklik
            console.log("Button clicked for PROCESS ID:", process_id);

            // Request untuk mendapatkan riwayat skill
            $.ajax({
                url: 'actions/get-process-history.php', // Path ke file PHP
                method: 'POST',
                data: { process_id: process_id, npk: npk },
                success: function(response) {
                    // Tampilkan riwayat skill di dalam modal
                    $('#skill-history-process-content').html(response);
                    $('#skill-history-process-popup').show(); // Tampilkan modal
                },
                error: function() {
                    console.log("AJAX request failed");
                }
            });
        }

        function closeSkillHistoryProcessPopup() {
            $('#skill-history-process-popup').hide(); // Sembunyikan popup
        }
        </script>

        <script>
        function showSkillHistoryQualityPopup(quality_id, npk) {
            // Cek apakah tombol diklik
            console.log("Button clicked for QUALITY ID:", quality_id);

            // Request untuk mendapatkan riwayat skill
            $.ajax({
                url: 'actions/get-quality-history.php', // Path ke file PHP
                method: 'POST',
                data: { quality_id: quality_id, npk: npk },
                success: function(response) {
                    // Tampilkan riwayat skill di dalam modal
                    $('#skill-history-quality-content').html(response);
                    $('#skill-history-quality-popup').show(); // Tampilkan modal
                },
                error: function() {
                    console.log("AJAX request failed");
                }
            });
        }

        function closeSkillHistoryQualityPopup() {
            $('#skill-history-quality-popup').hide(); // Sembunyikan popup
        }
        </script>

        <script>
        function showSkillHistoryEhsPopup(ehs_id, npk) {
            // Cek apakah tombol diklik
            console.log("Button clicked for EHS ID:", ehs_id);

            // Request untuk mendapatkan riwayat skill
            $.ajax({
                url: 'actions/get-ehs-history.php', // Path ke file PHP
                method: 'POST',
                data: { ehs_id: ehs_id, npk: npk },
                success: function(response) {
                    // Tampilkan riwayat skill di dalam modal
                    $('#skill-history-ehs-content').html(response);
                    $('#skill-history-ehs-popup').show(); // Tampilkan modal
                },
                error: function() {
                    console.log("AJAX request failed");
                }
            });
        }

        function closeSkillHistoryEHSPopup() {
            $('#skill-history-ehs-popup').hide(); // Sembunyikan popup
        }
        </script>

        <!-- Script jQuery untuk ADD DELETE PROCESS -->
        <script>
        function showAddProcessPopup() {
            document.getElementById('add-process-popup').classList.remove('hidden');
        }

        function showDeleteProcessPopup() {
            document.getElementById('delete-process-popup').classList.remove('hidden');
        }

        function hide_popup(popup_id) {
            document.querySelector(popup_id).classList.add('hidden');
        }
        </script>

        <!-- Script jQuery untuk ADD DELETE QUALITY -->
        <script>
        function showAddQualityPopup() {
            document.getElementById('add-quality-popup').classList.remove('hidden');
        }

        function showDeleteQualityPopup() {
            document.getElementById('delete-quality-popup').classList.remove('hidden');
        }

        function hide_popup(popup_id) {
            document.querySelector(popup_id).classList.add('hidden');
        }
        </script>

        <!-- Script jQuery untuk ADD DELETE EHS -->
        <script>
        function showAddEhsPopup() {
            document.getElementById('add-ehs-popup').classList.remove('hidden');
        }

        function showDeleteEhsPopup() {
            document.getElementById('delete-ehs-popup').classList.remove('hidden');
        }

        function hide_popup(popup_id) {
            document.querySelector(popup_id).classList.add('hidden');
        }
        </script>


        <!-- Script jQuery untuk ADD DELETE S-PROCESS -->
        <script>
        // Tambahkan fungsi untuk S-Process
        function showAddSProcessPopup() {
            document.getElementById('add-s-process-popup').classList.remove('hidden');
        }

        function showDeleteSProcessPopup() {
            document.getElementById('delete-s-process-popup').classList.remove('hidden');
        }

        // Fungsi hide popup yang sudah ada
        function hide_popup(popup_id) {
            document.querySelector(popup_id).classList.add('hidden');
        }
        </script><script>
        // Tambahkan fungsi untuk S-Process
        function showAddSProcessPopup() {
            document.getElementById('add-s-process-popup').classList.remove('hidden');
        }

        function showDeleteSProcessPopup() {
            document.getElementById('delete-s-process-popup').classList.remove('hidden');
        }

        // Fungsi hide popup yang sudah ada
        function hide_popup(popup_id) {
            document.querySelector(popup_id).classList.add('hidden');
        }
        </script>


        <script>
        // Fungsi untuk menutup semua bagian
        function closeAllSections() {
            document.getElementById('process-qualification-content').style.display = 'none';
            document.getElementById('quality-qualification-content').style.display = 'none';
            document.getElementById('ehs-qualification-content').style.display = 'none';
        }

        function toggleSection(sectionId) {
        const targetSection = document.getElementById(sectionId);
        const wasActive = targetSection.classList.contains('active');
        
        // Tutup semua section terlebih dahulu
        document.querySelectorAll('.qualification-content').forEach(section => {
            section.classList.remove('active');
        });
        
        // Toggle section yang diklik hanya jika sebelumnya tidak aktif
        if (!wasActive) {
            targetSection.classList.add('active');
        }
    }

        // Event listener untuk semua tombol
        document.getElementById("process-qualification-btn").addEventListener("click", () => {
            toggleSection('process-qualification-content');
            console.log('Process Qualification toggled');
        });

        document.getElementById("quality-btn").addEventListener("click", () => {
            toggleSection('quality-qualification-content');
            console.log('Quality toggled');
        });

        document.getElementById("ehs-btn").addEventListener("click", () => {
            toggleSection('ehs-qualification-content');
            console.log('EHS toggled');
        });

        </script>
                <script>
                    function showSection(sectionId) {
                        document.querySelectorAll('.qualification-section').forEach(function (section) {
                            section.classList.add('hidden');
                        });
                            document.getElementById(sectionId).classList.remove('hidden');
                        }
                </script>
            </div>
        </div>

        