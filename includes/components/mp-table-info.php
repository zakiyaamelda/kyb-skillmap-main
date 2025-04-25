<div>
    <form id="show-mp-desc" class="fill-container d-flex-row">
        <?php 
        foreach ($mp_categories as $mp_name => $mp_label)
        echo '
            <input type="radio" id="show-'.$mp_name.'" value="desc-'.$mp_name.'" name="show-mp-desc">
            <div class="mp-desc-toggle">
                <label for="show-'.$mp_name.'" class="m-0">
                    <p class="m-0">
                        '.$mp_label.'
                    </p>
                </label>
            </div>
            ';
        ?>
    </form>
</div>
<div class='mp-desc-container w-100 pl-3'>
    <div class="mp-desc" id="desc-msk">
        <p class="m-0">MSK</p>
        <ol class="mb-0">
            <li>Opr tidak menjalankan sesuai OM/IM</li>
            <li>OM & IM tidak dijalankan (Karena tidak mau)</li>
            <li>OM & IM sudah dijalankan, bisa melakukan tapi masih ada poin yang terlewatkan </li>
            <li>OM & IM sudah dijalankan, bisa melakukan, tapi terkada perlu diingatkan </li>
            <li>OM & IM sudah dijalankan, dan dapat melakukan dengan detail seperti instruksi kerja</li>
        </ol>
    </div>

    <div class="hidden mp-desc" id="desc-kt">
        <p class="m-0">KT</p>
        <ol>
            <li>Melakukan kerja tuntas dengan persentase 0%-60% dari total audit</li>
            <li>Melakukan kerja tuntas dengan persentase 61%-70% dari total audit</li>
            <li>Melakukan kerja tuntas dengan persentase 71%-80% dari total audit</li>
            <li>Melakukan kerja tuntas dengan persentase 81%-90% dari total audit</li>
            <li>Melakukan kerja tuntas dengan persentase 91%-100% dari total audit</li>
        </ol>
    </div>

    <div class="hidden mp-desc" id="desc-pssp">
        <p class="m-0">PSSP</p>
        <ol>
            <li>Opr tidak menjalankan pemisahan part sebelum dan sesudah proses karena tidak mau</li>
            <li>Opr tidak menjalankan pemisahan part karena tidak tahu</li>
            <li>Tidak menjalankan pemisahan part sebelum dan sesudah proses karena tidak ada fasilitas</li>
            <li>Menjalankan pemisahan part sebelum dan sesudah proses namun masih butuh perbaikan</li>
            <li>Menjalankan pemisahan part sebelum dan sesudah proses dengan jelas</li>
        </ol>
    </div>

    <div class="hidden mp-desc" id="desc-png">
        <p class="m-0">PNG</p>
        <ol>
            <li>Tidak melakukan Penangan Part NG (Karena tidak tersedia fasilitasnya)</li>
            <li>Tidak melakukan penanganan Part NG (Karena tidak mau meskipun fasilitas dan tag ada)</li>
            <li>Tidak melakukan Penanganan part NG ( Karena tidak tau dan ada fasilitasnya) </li>
            <li>Melakukan penangan part NG tetapi masih perlu perbaikan</li>
            <li>Melakukan penanganan part NG dengan baik dan fasilitas lengkap</li>
        </ol>
    </div>

    <div class="hidden mp-desc" id="desc-fivejq">
        <p class="m-0">5JQ</p>
        Kondisi Operator melakukan pengecekan part dan mesin sebelum proses
        <ol>
        <li>Tidak dilakukan pengecekan</li>
        <li value="3">Dilakukan pengecekan namun masih perlu perbaikan (Pengecekan nembak, tidak sesuai kondisi aktual)</li>
        <li value="5">Dilakukan pengecekan sesuai dengan standart</li>
        </ol>
    Kondisi oprator menjaga hasil kerja untuk part yg sudah di proses
        <ol>
    <li>Tidak menjaga hasil kerja karena tidak mau (terdapat fasilitas namun tidak melakukan)</li>
    <li>Tidak menjaga hasil kerja karena tidak bisa ( Tidak dapat fasilitas / kekurangan partisi atau layer)</li>
    <li value="4">Menjaga hasil kerja namun belum sesuai (std, act : box biasa)</li>
    <li>Menjaga hasil kerja sesuai dengan std yang telah ditetapkan.</li>
        </ol>
    </div>

    <div class="hidden mp-desc" id="desc-kao">
        <p class="m-0">KAO</p>
        <ol>
            <li>Tidak update papan henkaten sama skali</li>
            <li>Update papan henkaten tetapi hanya tanggal saja</li>
            <li>Skil MP tidak sesuai tetapi ada rencana perbaikan </li>
            <li>Skill MP sesuai dengan skill Map tapi masih perlu perbaikan </li>
            <li>Perubahan MP direcord dan Henkaten update keseluruhan</li>
        </ol>
    </div>
</div>