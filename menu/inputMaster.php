<?php
if (isset($_POST['addNew'])) {
    $nama = strtoupper($_POST['nama']);
    $nik = $_POST['nik'];
    $dept = $_POST['dept'];
    $alamat = strtoupper($_POST['alamat']);
    $npwp = $_POST['npwp'];
    $jabatan = strtoupper($_POST['jabatan']);
    $kotaLahir = strtoupper($_POST['kotaLahir']);
    $tglLahir = date("Ymd", strtotime($_POST['tglLahir']));
    $pangkat = $_POST['pangkat'];
    $status = $_POST['status'];
    $jenisKelamin = $_POST['jenisKelamin'];
    $keluarga = $_POST['keluarga'];
    $kodeBank = $_POST['kodeBank'];
    $gajiDasar = $_POST['gaji'];
    $kotaAsal = $_POST['kotaAsal']; // kota aktif
    $tglMasuk = date("Ymd", strtotime($_POST['tglMasuk']));
    $tglAktif = date("Ymd", strtotime($_POST['tglAktif']));


    $db = dbase_open('../B/GAJI.DBF', 2);
    $db2 = dbase_open('../B/WAKTU_MASUK.DBF', 2);
    $dbBca =dbase_open('../B/BCA.DBF',2);


    // }else if (){

    // }else if(){

    // }else if(){

    // }else if(){

    // }else if(){

    // }else if(){

    // }else if(){

    // }


    $numberRecord = dbase_numrecords($db);
    //get no urut
    $no = $numberRecord + 1;
    if ($no < 100) {
        $no = "0" . $no;
    }

    //get no urut di deptnya
    $noDept = 1;
    for ($i = 1; $i <= $numberRecord; $i++) {
        $row = dbase_get_record_with_names($db, $i);
        $getKodeDept = substr($row['DEPT'], 0, 1);
        if ($getKodeDept == $dept) {
            $noDept = $noDept + 1;
        }
    }
    //bkin dept code
    if ($noDept < 10) {
        $dept = $dept . "0-0" . $noDept;
    } else {
        $dept = $dept . "0-" . $noDept;
    }



    //jamsostek & tunjangan kes
    $tunjanganKesehatan = 0;
    $premiKesehatan = 0;
    $jamsostek = (0.05 * $gajiDasar);
    if ($jamsostek > 400000) {
        $jamsostek = 400000;
    }
    if ($pangkat == "4A" || $pangkat == "4B" || $pangkat == "3A" || $pangkat == "3B") {
        $tunjanganDariPerusahaan = (0.1 * $gajiDasar);
    } else if ($pangkat == "2A" || $pangkat == "2B" || $pangkat == "1A" || $pangkat == "1B" || $pangkat == "TM") {
        $tunjanganDariPerusahaan = (0.12 * $gajiDasar);
    } else {
        $tunjanganDariPerusahaan = (0.08 * $gajiDasar);
    }
    $premiKesehatan = (0.2 * $jamsostek);
    $tunjanganKesehatan = $tunjanganDariPerusahaan - ($jamsostek - $premiKesehatan) - $premiKesehatan;

    //input ke db
    //no urut, nik, deptcode, nama, alamat,npwp, jabatan, noktp, kotalahir, tgl lahir, pangkat, status, kelamin, keluarga, kode bank, kode bank, gajidasar, 0 0 0 0 0 0 0 0 0 0 1 Y Y, bln aktiv 0
    dbase_add_record($db, array($no, $nik, $dept, $nama, $alamat, $npwp, $jabatan, $nik, $kotaLahir, $tglLahir, $pangkat, $status, $jenisKelamin, $keluarga, $kodeBank, $kodeBank, $gajiDasar, 0, 0, $tunjanganKesehatan, $premiKesehatan, 0, 0, 0, 0, 0, 0, 1, 'Y', 'Y', $tglAktif, ""));
    dbase_add_record($db2, array($dept, $nama, $tglMasuk));
    if($kodeBank==1){
       dbase_add_record($dbBca, array($dept,$nama,0000000000,1,"",0,0));
    }
    dbase_close($db2);
    dbase_close($db);
    dbase_close($dbBca);
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../src/tampilan.css">
    <link rel="stylesheet" type="text/css" href="../src/tampil.css">
    <title>Form Input</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#gajiId').change(function() {
                var inputValue = $(this).val();
                var kotaValue = $("#kotaId").val();
                //alert("value in js " + inputValue + kotaValue);

                //Ajax for calling php function
                $.post('../src/cekPangkat.php', {
                    gajiV: inputValue,
                    kotaV: kotaValue,
                    jamsos: "Y"
                }, function(data) {
                    //alert('ajax completed. Response:  ' + data);
                    //do after submission operation in DOM
                    $("#pangkatId").val(data.pangkat);

                }, "json");
            });
        });
        $(document).ready(function() {
            $('#kotaId').change(function() {
                var inputValue = $("#gajiId").val();
                var kotaValue = $(this).val();
                //alert("value in js " + inputValue + kotaValue);

                //Ajax for calling php function
                $.post('../src/cekPangkat.php', {
                    gajiV: inputValue,
                    kotaV: kotaValue,
                    jamsos: "Y"
                }, function(data) {
                    //alert('ajax completed. Response:  ' + data);
                    //do after submission operation in DOM
                    $("#pangkatId").val(data.pangkat);

                }, "json");
            });
        });
    </script>
</head>

<body>
    <header>
        <!--Section HEADER-->
        <img src="../img/rtn.jpg" />
        <div id='cssmenu'>
            <ul>
                <li class='has-sub '><a href='#'><span>Maintain Input MASTER</span></a>
                    <ul>
                        <li><a href='/penggajianMagang/menu/inputMaster.php'><span>Input Master</span></a></li>
                        <li><a href='/penggajianMagang/menu/payrollMasterFile.php'><span>Manage Master Gaji</span></a></li>
                        <li><a href='/penggajianMagang/menu/alamatDanNpwp.php'><span>Alamat & N.P.W.P</span></a></li>
                        <li><a href='/penggajianMagang/menu/masterBCA.php'><span>Master B.C.A</span></a></li>
                        <li><a href='/penggajianMagang/menu/showNamaGolongan.php'><span>Golongan</span></a></li>
                        <li><a href='/penggajianMagang/menu/inputGajiBaru.php'><span>Gaji Baru</span></a></li>
                        <li><a href='/penggajianMagang/menu/inputTunjanganJabatan.php'><span>Input Data Lain</span></a></li>
                    </ul>
                </li>
                <li class='has-sub '><a href='#'><span>THR/Bonus</span></a>
                    <ul>
                        <li><a href='/penggajianMagang/menu/inputTHR.php'><span>Input THR</span></a></li>
                        <li><a href='/penggajianMagang/menu/inputBonus.php'><span>Input Bonus</span></a></li>
                    </ul>
                </li>
                <li class='has-sub '><a href='#'><span>Manage Pangkat</span></a>
                    <ul>
                        <li><a href='/penggajianMagang/menu/masterPangkatK1.php'><span>K1 </span></a></li>
                        <li><a href='/penggajianMagang/menu/masterPangkatK2.php'><span>K2 </span></a></li>
                        <li><a href='/penggajianMagang/menu/masterPangkatK3.php'><span>K3 </span></a></li>
                    </ul>
                </li>
                <li class='active'><a href='index.html'><span>Home</span></a></li>
                <li><a href='#'><span>Contact</span></a></li>
            </ul>
        </div>
    </header>
    <div class="form-style-3">
        <font face="Berlin Sans FB">
            <form action="" method="post">
                <fieldset>
                    <legend>Input New</legend>
                    <label>
                        <span>Nama:</span>
                        <input type="text" name="nama" placeholder="Nama..." />
                    </label>
                    <label>
                        <span>NIK:</span>
                        <input type="text" name="nik" placeholder="NIK..." />
                    </label>
                    <label>
                        <span>Dept:</span>
                        <select name="dept">
                            <option selected="true" disabled="disabled">-</option>
                            <?php
                            $db = dbase_open('../B/GOLONGAN.DBF', 0);
                            $nRecord = dbase_numrecords($db);
                            for ($i = 0; $i <= $nRecord; $i++) {
                                $row = dbase_get_record_with_names($db, $i)
                                ?>
                                <option value=<?php echo "'" . $row['KODE'] . "'" ?>><?php echo $row['NAMA'] ?> </option>
                            <?php }
                            dbase_close($db); ?>
                        </select>

                    </label>
                    <label>
                        <span>Alamat:</span>
                        <input type="text" name="alamat" placeholder="Alamat..." />
                    </label>
                    <label>
                        <span>NPWP:</span>
                        <input type="string" name="npwp" placeholder="NPWP..." />
                    </label>
                    <label>
                        <span>Jabatan:</span>
                        <select name="jabatan">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="MANAGER">MANAGER</option>
                            <option value="STAFF">STAFF</option>
                            <option value="STAFF">DIREKSI</option>
                        </select>
                    </label>
                    <label>
                        <span>Kota Lahir:</span>
                        <input type="text" name="kotaLahir" placeholder="Kota Lahir..." />
                    </label>
                    <label>
                        <span>Tgl Lahir:</span>
                        <input type="date" name="tglLahir" />
                    </label>
                    <label>
                        <span>Status:</span>
                        <select name="status">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="K">KAWIN</option>
                            <option value="T">BELUM KAWIN</option>
                        </select>
                    </label>
                    <label>
                        <span>Jenis Kelamin:</span>
                        <select name="jenisKelamin">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                        </select>
                    </label>
                    <label>
                        <span>Keluarga:</span>
                        <select name="keluarga">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="TK-">TK-</option>
                            <option value="KT-">KT-</option>
                            <option value="K/1">K/1</option>
                            <option value="K/2">K/2</option>
                            <option value="K/3">K/3</option>
                        </select>
                    </label>
                    <label>
                        <span>Kode Bank:</span>
                        <select name="kodeBank">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="1">1</option>
                        </select>
                    </label>
                    <label>
                        <span>Kota:</span>
                        <select name="kotaAsal" id="kotaId">
                            <option selected="true" disabled="disabled">-</option>
                            <option value="V">JAKARTA (V)</option>
                            <option value="H0">PUSAT (H0)</option>
                            <option value="S">SURABAYA (S)</option>
                            <option value="R">SEMARANG (R)</option>
                            <option value="B">LAMPUNG (B)</option>
                            <option value="Y">MEDAN (Y)</option>
                            <option value="U">MAKASSAR (U)</option>
                            <option value="W">PALEMBANG (W)</option>
                        </select>
                    </label>
                    <label>
                        <span>Gaji Dasar:</span>
                        <input type="text" name="gaji" id="gajiId" placeholder="Rp..." />
                    </label>
                    <label>
                        <span>Pangkat:</span>
                        <input type="text" name="pangkat" id="pangkatId" placeholder="Pangkat..." />
                    </label>
                    <label>
                        <span>Tgl Masuk:</span>
                        <input type="date" name="tglMasuk" />
                    </label>
                    <label>
                        <span>Tgl Aktif:</span>
                        <input type="date" name="tglAktif" />
                    </label>
                    <br>
                    <label>
                        <input type="submit" name="addNew" value="ADD NEW" />
                    </label>
                </fieldset>
            </form>
        </font>
    </div>
</body>

</html>