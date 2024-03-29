<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}

include "../src/main.php";
if (isset($_POST['addNew'])) {
    if (!empty($_POST['nama']) && !empty($_POST['nik']) && !empty($_POST['dept']) && !empty($_POST['alamat']) && !empty($_POST['npwp']) && !empty($_POST['jabatan']) && !empty($_POST['kotaLahir']) && !empty($_POST['tglLahir']) && !empty($_POST['status']) && !empty($_POST['jenisKelamin']) && !empty($_POST['keluarga']) && !empty($_POST['kodeBank']) && !empty($_POST['kotaAsal']) && !empty($_POST['gaji']) && !empty($_POST['pangkat']) && !empty($_POST['tglMasuk']) && !empty($_POST['tglAktif'])) {
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

        //if tergantung kota

        if ($kotaAsal == 'B') {
            $db = dbase_open('../kota/B/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/B/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/B/BCA.DBF', 2);
        } else if ($kotaAsal == 'H0') {
            $db = dbase_open('../kota/H0/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/H0/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/H0/BCA.DBF', 2);
        } else if ($kotaAsal == 'R') {
            $db = dbase_open('../kota/R/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/R/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/R/BCA.DBF', 2);
        } else if ($kotaAsal == 'S') {
            $db = dbase_open('../kota/S/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/S/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/S/BCA.DBF', 2);
        } else if ($kotaAsal == 'U') {
            $db = dbase_open('../kota/U/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/U/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/U/BCA.DBF', 2);
        } else if ($kotaAsal == 'V') {
            $db = dbase_open('../kota/V/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/V/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/V/BCA.DBF', 2);
        } else if ($kotaAsal == 'W') {
            $db = dbase_open('../kota/W/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/W/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/W/BCA.DBF', 2);
        } else if ($kotaAsal == 'Y') {
            $db = dbase_open('../kota/Y/GAJI.DBF', 2);
            $db2 = dbase_open('../kota/Y/WAKTU_MASUK.DBF', 2);
            $dbBca = dbase_open('../kota/Y/BCA.DBF', 2);
        }


        //get no urut
        $numberRecord = dbase_numrecords($db);
        //get no urut trakir dari db
        $no = dbase_get_record_with_names($db, $numberRecord);
        $no = (int) $no['NO_URUT'] + 1;
        if ($no < 100) {
            $no = "0" . $no;
        }

        //get no urut di deptnya
        $noDept = 0;
        for ($i = 1; $i <= $numberRecord; $i++) {
            $row = dbase_get_record_with_names($db, $i);
            $getKodeDept = substr($row['DEPT'], 0, 1);
            if ($getKodeDept == $dept) {
                if ((int) substr($row['DEPT'], 3, 2) > $noDept) // cari no urut dept terbesar
                    $noDept = (int) substr($row['DEPT'], 3, 2);
            }
        }
        //bkin dept code
        $noDept += 1; //no urut sekarang (terbesar + 1)
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
        if ($kodeBank == 1) {
            dbase_add_record($dbBca, array($dept, $nama, "0000000000", 1, "", 0, 0));
        }
        dbase_close($db2);
        dbase_close($db);
        dbase_close($dbBca);

        $message = "Data Berhasil di input";
        echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
        $message = "Tolong di Isi semua";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>INPUT MASTER</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->

    <!-- Font Awesome JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../src/tampilan.css">
    <link rel="stylesheet" type="text/css" href="../src/tampil.css">
    <script>
        $(document).ready(function() {
            $('#gajiId, #kotaId').change(function() {
                var inputValue = $("#gajiId").val();
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
    </script>
</head>

<body>
    <?php printSideBar() ?>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a>INPUT MASTER <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
            </div>
        </nav>
        <div class="form-style-3">
            <font face="Berlin Sans FB">
                <form action="" method="post">
                    <fieldset>
                        <legend>Input New</legend>
                        <tr>
                            <label>
                                <td>
                                    <span>Nama:</span>
                                    <input type="text" name="nama" placeholder="Nama..." />
                                </td>
                                <td>
                                    <span>NIK:</span>
                                    <input type="text" name="nik" placeholder="NIK..." />
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Dept:</span>
                                    <select name="dept">
                                        <option selected="true" value="">-</option>
                                        <?php
                                        $db = dbase_open($_SESSION['pathKota'] . "GOLONGAN.DBF", 0);
                                        $nRecord = dbase_numrecords($db);
                                        for ($i = 0; $i <= $nRecord; $i++) {
                                            $row = dbase_get_record_with_names($db, $i)
                                            ?>
                                        <option value=<?php echo "'" . $row['KODE'] . "'" ?>><?php echo $row['NAMA'] ?> </option>
                                        <?php }
                                        dbase_close($db); ?>
                                    </select>
                                </td>
                                <td>
                                    <span>Alamat:</span>
                                    <input type="text" name="alamat" placeholder="Alamat..." />
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>NPWP:</span>
                                    <input type="string" name="npwp" placeholder="NPWP..." />
                                </td>
                                <td>
                                    <span>Jabatan:</span>
                                    <select name="jabatan">
                                        <option selected="true" value="">-</option>
                                        <option value="MANAGER">MANAGER</option>
                                        <option value="STAFF">STAFF</option>
                                        <option value="STAFF">DIREKSI</option>
                                    </select>
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Kota Lahir:</span>
                                    <input type="text" name="kotaLahir" placeholder="Kota Lahir..." />
                                </td>
                                <td>
                                    <span>Tgl Lahir:</span>
                                    <input type="date" name="tglLahir" />
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Status:</span>
                                    <select name="status">
                                        <option selected="true" value="">-</option>
                                        <option value="K">KAWIN</option>
                                        <option value="T">BELUM KAWIN</option>
                                    </select>
                                </td>
                                <td>
                                    <span>Jenis Kelamin:</span>
                                    <select name="jenisKelamin">
                                        <option selected="true" value="">-</option>
                                        <option value="Pria">Pria</option>
                                        <option value="Wanita">Wanita</option>
                                    </select>
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Keluarga:</span>
                                    <select name="keluarga">
                                        <option selected="true" value="">-</option>
                                        <option value="TK-">TK-</option>
                                        <option value="TK1">TK1</option>
                                        <option value="TK2">TK2</option>
                                        <option value="TK3">TK3</option>
                                        <option value="K/-">K/-</option>
                                        <option value="K/1">K/1</option>
                                        <option value="K/2">K/2</option>
                                        <option value="K/3">K/3</option>
                                        <option value="K/4">K/4</option>
                                        <option value="K/5">K/5</option>
                                    </select>
                                </td>
                                <td>
                                    <span>Kode Bank:</span>
                                    <select name="kodeBank">
                                        <option selected="true" value="">-</option>
                                        <option value="1">1 (B.C.A)</option>
                                    </select>
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Kota:</span>
                                    <select name="kotaAsal" id="kotaId">
                                        <option selected="true" value="">-</option>
                                        <option value="V">JAKARTA (V)</option>
                                        <option value="H0">PUSAT (H0)</option>
                                        <option value="S">SURABAYA (S)</option>
                                        <option value="R">SEMARANG (R)</option>
                                        <option value="B">LAMPUNG (B)</option>
                                        <option value="Y">MEDAN (Y)</option>
                                        <option value="U">MAKASSAR (U)</option>
                                        <option value="W">PALEMBANG (W)</option>
                                    </select>
                                </td>
                                <td>
                                    <span>Gaji Dasar:</span>
                                    <input type="text" name="gaji" id="gajiId" placeholder="Rp..." />
                                </td>
                            </label>
                        </tr>
                        <tr>
                            <label>
                                <td>
                                    <span>Pangkat:</span>
                                    <input type="text" name="pangkat" id="pangkatId" placeholder="Pangkat..." />
                                </td>
                                <td>
                                    <span>Tgl Masuk:</span>
                                    <input type="date" name="tglMasuk" />
                                </td>
                            </label>
                        </tr>
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


    </div>

    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
</body>

</html>