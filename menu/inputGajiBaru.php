<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}
//edit gaji
if (isset($_POST['edit'])) {
    $gaji = $_POST['gajiDasar'];
    $rowId = $_POST['rowId'];
    $tunjReg = $_POST['tunjReg'];
    $pangkat = $_POST['pangkat'];
    $premi = $_POST['premi'];
    $tunjKes = $_POST['tunjKes'];

    $db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
    if ($db) {

        $row = dbase_get_record_with_names($db, $rowId);

        unset($row['deleted']);
        $row['GAJI_DASAR'] = $gaji;
        $row['TUNJ_REG'] = $tunjReg;
        $row['PANGKAT'] = $pangkat;
        $row['JPK'] = $premi;
        $row['TUNJ_KES'] = $tunjKes;
        $row = array_values($row);
        dbase_replace_record($db, $row, $rowId);

        dbase_close($db);
    }
}

if (isset($_POST['editTunjangan'])) {
    $rowId = $_POST['rowId'];
    $val = $_POST['val'];

    $db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
    if ($db) {
        $row = dbase_get_record_with_names($db, $rowId);
        if($val!=""){
            unset($row['deleted']);
            $row['TUNJ_REG'] = $val;
            $row = array_values($row);
            dbase_replace_record($db, $row, $rowId);
        }
        dbase_close($db);
    }
}
//
if (isset($_POST['editGaji'])) {
    $rowId = $_POST['rowId'];
    $gaji = $_POST['val'];
    $kota = $_POST['kodeKota'];
    $pangkat = "";
    $premiKesehatan = 0;
    $tunjanganKesehatan = 0;
    $db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
    $row1 = dbase_get_record_with_names($db, $rowId);
    if ($gaji == "") {
        $gaji = $row1['GAJI_DASAR'];
    }
    if ($db) {
        
        if ($kota == 'V' || $kota == 'H0' || $kota == 'S') {
            $dbPangkat = dbase_open('../src/golongan/PANGKAT_K1.DBF', 0);
        } else if ($kota == 'R' || $kota == 'B') {
            $dbPangkat = dbase_open('../src/golongan/PANGKAT_K3.DBF', 0);
        } else {
            $dbPangkat = dbase_open('../src/golongan/PANGKAT_K2.DBF', 0);
        }
        $dbPangkat2 = dbase_open('../src/golongan/Pangkat_4BPLUS.DBF', 0);

        $n = dbase_numrecords($dbPangkat);
        for ($i = 1; $i <= $n; $i++) {
            $row = dbase_get_record_with_names($dbPangkat, $i);

            if ($i == $n && $gaji > $row['MAX']) {
                $n2 = dbase_numrecords($dbPangkat2);
                for ($i2 = 1; $i < $n2; $i2++) {
                    $row2 = dbase_get_record_with_names($dbPangkat2, $i2);
                    if ($i2 == $n2 && $gaji > $row2['MAX']) {
                        $pangkat = "X";
                        break;
                    }
                    if ($gaji >= $row2['MIN'] && $gaji <= $row2['MAX']) {
                        $pangkat = $row2['PANGKAT'];
                        break;
                    }
                }
            }
            if ($gaji >= $row['MIN'] && $gaji <= $row['MAX']) {
                $pangkat = $row['PANGKAT'];
                break;
            }
        }
        dbase_close($dbPangkat);
        dbase_close($dbPangkat2);

        $premiKesehatan = 0;
        $jamsostek = (0.05 * $gaji);
        if ($jamsostek > 400000) {
            $jamsostek = 400000;
        }
        if ($pangkat == "4A" || $pangkat == "4B" || $pangkat == "3A" || $pangkat == "3B") {
            $tunjanganDariPerusahaan = (0.1 * $gaji);
        } else if ($pangkat == "2A" || $pangkat == "2B" || $pangkat == "1A" || $pangkat == "1B" || $pangkat == "TM") {
            $tunjanganDariPerusahaan = (0.12 * $gaji);
        } else {
            $tunjanganDariPerusahaan = (0.08 * $gaji);
        }

        //cek jamsos flg
        $jamsos = $row1['JAMSOSFLG'];

        if (strtoupper($jamsos) == 'Y') {
            $premiKesehatan = (0.2 * $jamsostek);
            $tunjanganKesehatan = $tunjanganDariPerusahaan - ($jamsostek - $premiKesehatan) - $premiKesehatan;
        } else {
            $premiKesehatan = 0;
            $tunjanganKesehatan = $tunjanganDariPerusahaan - ($jamsostek - $premiKesehatan) - $premiKesehatan;
        }

        //edit
        unset($row1['deleted']);
        $row1['GAJI_DASAR'] = $gaji;
        $row1['PANGKAT'] = $pangkat;
        $row1['JPK'] = $premiKesehatan;
        $row1['TUNJ_KES'] = $tunjanganKesehatan;
        $row1 = array_values($row1);
        dbase_replace_record($db, $row1, $rowId);
        dbase_close($db);
    }
}

//fetch data gaji dri db
$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
if ($db) {
    $record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>GAJI BARU</title>

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
    <link rel="stylesheet" type="text/css" href="../src/View.css">
    <script>
        $(document).ready(function() {
            $('#gajiId, #jamsosflg').change(function() {

                var inputValue = $("#gajiId").val();
                var kotaValue = $("#pangkatId").val();
                var jamsosflg = $("#jamsosflg").val();
                jamsosflg = jamsosflg.toUpperCase();
                if (kotaValue.substring(0, 2) == "K1") {
                    kotaValue = "V";
                } else if (kotaValue.substring(0, 2) == "K3") {
                    kotaValue = "B";
                } else {
                    kotaValue = "W";
                }

                //Ajax for calling php function
                $.post('../src/cekPangkat.php', {
                    gajiV: inputValue,
                    kotaV: kotaValue,
                    jamsos: jamsosflg
                }, function(data) {
                    $("#pangkatId").val(data.pangkat);
                    $("#tunjKes").val(data.tunjKes);
                    $("#premi").val(data.premi);

                }, "json");

            });

            //button edit gaji
            $("#buttonEditGaji").click(function() {
                var con = $("#buttonEditGaji").data("condition");
                if (con == true) {
                    //alert("masuk edit");
                    var totalRow = $(".totalRow").val() - 1;
                    $("#buttonEditGaji").val("SAVE GAJI");
                    $("#buttonEditGaji").data("condition", false);
                    $("#myTable").off("click");
                    for (var i = 1; i <= totalRow; i++) {
                        (function(i) {
                            var sebelum = $(".tdGaji" + i).text();
                            //alert(sebelum);
                            $(".tdGaji" + i).html(sebelum + "<br><input type='text' class='editGaji' id='etGaji" + i + "'>")
                        })(i);
                    }
                } else {
                    //alert("masuk save");
                    var totalRow = $(".totalRow").val() - 1;
                    //alert(totalRow);
                    $("#buttonEditGaji").val("Edit All Gaji");
                    $("#buttonEditGaji").data("condition", true);
                    $("#myTable").on("click", "td", function() {
                        editPerKolom();
                    });

                    for (var i = 1; i <= totalRow; i++) {
                        (function(i) {
                            var valInput = $("#etGaji" + i).val();
                            if (valInput != "") {
                                $(".tdGaji" + i).html(valInput);
                                var kodeKota = $("#kodeKota").val();
                                $.post("inputGajiBaru.php", {
                                    editGaji: "1",
                                    rowId: i,
                                    val: valInput,
                                    kodeKota: kodeKota
                                }, function(resp) {
                                    location.reload();
                                });
                            } else {
                                var valSebelum = $(".tdGaji" + i).text();
                                $(".tdGaji" + i).html(valSebelum);
                            }
                        })(i);
                    }

                }
            });

            //button edit tunjangan reguler
            $("#buttonEditTunjangan").click(function() {
                var con = $("#buttonEditTunjangan").data("condition");
                if (con == true) {
                    //alert("masuk edit");
                    var totalRow = $(".totalRow").val() - 1;
                    $("#buttonEditTunjangan").val("SAVE Tunjangan");
                    $("#buttonEditTunjangan").data("condition", false);
                    $("#myTable").off("click");
                    for (var i = 1; i <= totalRow; i++) {
                        (function(i) {
                            var sebelum = $(".tdTunjangan" + i).text();
                            //alert(sebelum);
                            $(".tdTunjangan" + i).html(sebelum + "<br><input type='text' class='editTunjangan' id='etTunjangan" + i + "'>")
                        })(i);
                    }
                } else {
                    //alert("masuk save");
                    var totalRow = $(".totalRow").val() - 1;
                    //alert(totalRow);
                    $("#buttonEditTunjangan").val("Edit All Tunjangan");
                    $("#buttonEditTunjangan").data("condition", true);
                    $("#myTable").on("click", "td", function() {
                        editPerKolom();
                    });

                    for (var i = 1; i <= totalRow; i++) {
                        (function(i) {
                            var valInput = $("#etTunjangan" + i).val();
                            if (valInput != "") {
                                $(".tdTunjangan" + i).html(valInput);
                                $.post("payrollMasterFile.php", {
                                    editTunjangan: "1",
                                    rowId: i,
                                    val: valInput
                                }, function(resp) {

                                });
                            } else {
                                var valSebelum = $(".tdTunjangan" + i).text();
                                $(".tdTunjangan" + i).html(valSebelum);
                            }
                        })(i);
                    }

                }
            });

            function editPerKolom() {
                // click 1 kolom
                $("#myTable").on("click", "td", function() {
                    // alert($(this).data("row"));
                    // alert($(this).data("mode"));
                    // alert($(this).text());

                    //tunjangan jabatan click
                    if ($(this).data("mode") == "gaji") {
                        $("#myTable").off("click");
                        var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <input type="hidden" name="kodeKota" value="' +$("#kodeKota").val()+'"> \
                    <br /> \
					<input type="submit" name="editGaji"> \
                </form><form action=""><input type="submit" value="Cancel"></form>';
                        $(this).html(form);
                    }

                    //click tunjangan
                    else if ($(this).data("mode") == "tunjangan") {
                        $("#myTable").off("click");
                        var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <br /> \
					<input type="submit" name="editTunjangan"> \
                </form><form action=""><input type="submit" value="Cancel"></form>';
                        $(this).html(form);
                    }

                });
            }
            // click 1 kolom
            editPerKolom();
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                </button>
            </div>
            <ul class="list-unstyled components">
                <!-- <img src="img/rtn.jpg" /> -->
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Maintain Input MASTER
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href='inputMaster.php'>Input Master</a>
                        </li>
                        <a href='payrollMasterFile.php'>Manage Master Gaji</a>
                        <li>
                            <a href='alamatDanNpwp.php'>Alamat & N.P.W.P</a>
                        </li>
                        <a href='masterBCA.php'>Master B.C.A</a>
                        <li>
                            <a href='showNamaGolongan.php'>Golongan</a>
                        </li>
                        <a href='inputGajiBaru.php'>Gaji Baru</a>
                        <li>
                            <a href='inputTunjanganJabatan.php'>Input Data Lain</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#pageSubTHRBONUS" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        THR BONUS
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubTHRBONUS">
                        <li>
                            <a href='inputTHR.php'>Input THR</a>
                        </li>
                        <li>
                            <a href='inputBonus.php'>Input Bonus</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#pageSubpangkat" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Manage Pangkat
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubpangkat">
                        <li>
                            <a href='masterPangkatK1.php' class="w3-bar-item w3-button">K1 </a>
                        </li>
                        <li>
                            <a href='masterPangkatK2.php' class="w3-bar-item w3-button">K2 </a>
                        </li>
                        <li>
                            <a href='masterPangkatK3.php' class="w3-bar-item w3-button">K3 </a>
                        </li>
                        <li>
                            <a href='master4Bplus.php' class="w3-bar-item w3-button">4B - TM </a>
                        </li>
                </li>
            </ul>
            <li class="active">
                <a href="../pilihKota.php">Pilih Kota</a>
            </li>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a>GAJI BARU <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
                </div>
            </nav>
            <div class="tableButton">
                <div style="margin-right: 200px">
                    <input type="button" tabindex="-1" name="buttonEditGaji" value="Edit All Gaji" id="buttonEditGaji" data-condition="true" class="bEdit">
                    <input type="button" tabindex="-1" name="buttonEditTunjangan" value="Edit All Tunjangan" id="buttonEditTunjangan" data-condition="true" class="bEdit">
                </div>
                <table width="100%" border="1" id="myTable">
                    <tr>
                        <th onclick="sortTable(0)">NO. DEPT</th>
                        <th onclick="sortTable(1)">NO. URUT</th>
                        <th onclick="sortTable(2)">NAMA</th>
                        <th onclick="sortTable(3)">GAJI DASAR</th>
                        <th onclick="sortTable(4)">TUNJANGAN REGIONAL</th>
                        <th></th>
                    </tr>
                    <?php
                    $i = 0;
                    for ($i = 1; $i <= $record_numbers; $i++) { ?>
                        <tr>
                            <?php $row = dbase_get_record_with_names($db, $i); ?>
                            <td>
                                <?php echo $row['DEPT']; ?>
                            </td>
                            <td>
                                <?php echo $row['NO_URUT'] ?>
                            </td>
                            <td>
                                <?php echo $row['NAMA']; ?>
                            </td>
                            <td class=<?php echo "tdGaji" . $i ?> data-mode="gaji" data-row=<?php echo $i ?>>
                                <?php echo $row['GAJI_DASAR']; ?>
                            </td>
                            <td class=<?php echo "tdTunjangan" . $i ?> data-mode="tunjangan" data-row=<?php echo $i ?>>
                                <?php echo $row['TUNJ_REG']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
                                <input type="hidden" name="no" value=<?php echo $row['NO_URUT'] ?> id=<?php echo "no" . $i ?>>
                                <input type="hidden" name="dept" value=<?php echo $row['DEPT']; ?> id=<?php echo "dept" . $i; ?>>
                                <input type="hidden" name="pangkat" value=<?php echo $row['PANGKAT']; ?> id=<?php echo "pangkat" . $i; ?>>
                                <input type="hidden" name="gajiDasar" value=<?php echo $row['GAJI_DASAR']; ?> id=<?php echo "gajiDasar" . $i; ?>>
                                <input type="hidden" name="tunjReg" value=<?php echo $row['TUNJ_REG']; ?> id=<?php echo "tunjReg" . $i; ?>>
                                <input type="hidden" name="jamsosflg" value=<?php echo $row['JAMSOSFLG']; ?> id=<?php echo "jamsosflg" . $i ?>>
                                <input type="submit" tabindex="-1" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
                            </td>
                        </tr>
                    <?php }
                    dbase_close($db); ?>
                    <input type="hidden" name="totalRow" class="totalRow" value=<?php echo $i ?>>
                    <input type="hidden" name="kodeKota" id="kodeKota" value=<?php echo $_SESSION['kodeKota'] ?>>
                </table>
            </div>

            <div id="mdl-update" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">INPUT GAJI BARU</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" id="formId">
                            <div class="modal-body">
                                <div class="col-lg-12">
                                    <label for="no">NOMER URUT</label>
                                    <input type="text" class="no" name="no" placeholder="" disabled>
                                </div>
                                <div class="col-lg-12">
                                    <label for="dept">NO. DEPT</label>
                                    <input type="text" class="dept" name="dept" placeholder="" disabled>
                                    <input type="hidden" class="rowId" name="rowId" id="rowId">
                                </div>
                                <div class="col-lg-12">
                                    <label for="nama">NAMA</label>
                                    <input type="text" class="nama" name="nama" placeholder="" disabled>
                                </div>
                                <div class="col-lg-12">
                                    <label for="pangkat">PANGKAT</label>
                                    <input type="text" class="pangkat" name="pangkat" id="pangkatId" placeholder="">
                                    <!-- <input type="hidden" class="pangkatValue" name="pangkatValue" id="pangkatValue"> -->
                                </div>
                                <div class="col-lg-12">
                                    <label for="gajiDasar">GAJI DASAR</label>
                                    <input type="text" class="gajiDasar" name="gajiDasar" id="gajiId" placeholder="">
                                </div>
                                <div class="col-lg-12">
                                    <label for="tunjReg">TUNJANGAN REGIONAL</label>
                                    <input type="text" class="tunjReg" name="tunjReg" placeholder="">
                                    <input type="hidden" id="jamsosflg" name="jamsosflg" class="jamsosflg">
                                    <input type="hidden" id="tunjKes" name="tunjKes" class="tunjKes">
                                    <input type="hidden" id="premi" name="premi" class="premi">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" val="" id="edit" name="edit" value="SAVE CHANGE">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on("click", ".btnUpdate", function() {
            var clickId = $(this).data('id');
            var deptValue = $("#dept" + clickId).val();
            var namaValue = $("#nama" + clickId).val();
            var gajiDasarValue = $("#gajiDasar" + clickId).val();
            var tunjRegValue = $("#tunjReg" + clickId).val();
            var pangkat = $("#pangkat" + clickId).val();
            var no = $("#no" + clickId).val();
            var jamsosflg = $("#jamsosflg" + clickId).val();

            $(".modal-body .dept").val(deptValue);
            $(".modal-body .nama").val(namaValue);
            $(".modal-body .gajiDasar").val(gajiDasarValue);
            $(".modal-body .rowId").val(clickId);
            $(".modal-body .tunjReg").val(tunjRegValue);
            $(".modal-body .no").val(no);
            $(".modal-body .pangkat").val(pangkat);
            $(".modal-body .jamsosflg").val(jamsosflg);

        });

        function isValidForm() {
            var pilihan = confirm("hapus");;
            if (pilihan) {
                return true;
            } else {
                return false;
            }
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("myTable");
            switching = true;
            //Set the sorting direction to ascending:
            dir = "asc";
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /*Loop through all table rows (except the
                first, which contains table headers):*/
                for (i = 1; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                    one from current row and one from the next:*/
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    /*check if the two rows should switch place,
                    based on the direction, asc or desc:*/
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    //Each time a switch is done, increase this count by 1:
                    switchcount++;
                } else {
                    /*If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again.*/
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>