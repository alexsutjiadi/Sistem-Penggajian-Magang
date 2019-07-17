<?php

if (!isset($_SESSION)) {
    session_start();
    session_unset();
}

if (isset($_POST['LAMPUNG'])) {
    $_SESSION['pathKota'] = "../kota/B/";
    $_SESSION['kota'] = "LAMPUNG";
    $_SESSION['kodeKota']="B";
}
if (isset($_POST['SURABAYA'])) {
    $_SESSION['pathKota'] = "../kota/S/";
    $_SESSION['kota'] = "SURABAYA";
    $_SESSION['kodeKota'] = "S";
}
if (isset($_POST['PUSAT'])) {
    $_SESSION['pathKota'] = "../kota/H0/";
    $_SESSION['kota'] = "PUSAT";
    $_SESSION['kodeKota'] = "H0";
}
if (isset($_POST['JAKARTA'])) {
    $_SESSION['pathKota'] = "../kota/V/";
    $_SESSION['kota'] = "JAKARTA";
    $_SESSION['kodeKota'] = "V";
}
if (isset($_POST['MAKASSAR'])) {
    $_SESSION['pathKota'] = "../kota/U/";
    $_SESSION['kota'] = "MAKASSAR";
    $_SESSION['kodeKota'] = "U";
}
if (isset($_POST['PALEMBANG'])) {
    $_SESSION['pathKota'] = "../kota/W/";
    $_SESSION['kota'] = "PALEMBANG";
    $_SESSION['kodeKota'] = "W";
}
if (isset($_POST['SEMARANG'])) {
    $_SESSION['pathKota'] = "../kota/R/";
    $_SESSION['kota'] = "SEMARANG";
    $_SESSION['kodeKota'] = "R";
}
if (isset($_POST['MEDAN'])) {
    $_SESSION['pathKota'] = "../kota/Y/";
    $_SESSION['kota'] = "MEDAN";
    $_SESSION['kodeKota'] = "Y";
}
if (isset($_SESSION['pathKota'])) {
    header("Location: index.php");
}
?>
<html>

<head>
    <!--Element-elemen tag <head> tulis disini-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" type="text/css" href="src/tampilan.css">
    <link rel="stylesheet" type="text/css" href="src/tampilan1.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <title>PT.RUTAN_GAJI</title>
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
                            <a href='menu/inputMaster.php'>Input Master</a>
                        </li>
                        <a href='menu/payrollMasterFile.php'>Manage Master Gaji</a>
                        <li>
                            <a href='menu/alamatDanNpwp.php'>Alamat & N.P.W.P</a>
                        </li>
                        <a href='menu/masterBCA.php'>Master B.C.A</a>
                        <li>
                            <a href='menu/showNamaGolongan.php'>Golongan</a>
                        </li>
                        <a href='menu/inputGajiBaru.php'>Gaji Baru</a>
                        <li>
                            <a href='menu/inputTunjanganJabatan.php'>Input Data Lain</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#pageSubTHRBONUS" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        THR BONUS
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubTHRBONUS">
                        <li>
                            <a href='menu/inputTHR.php'>Input THR</a>
                        </li>
                        <li>
                            <a href='menu/inputBonus.php'>Input Bonus</a>
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
                <a href="pilihKota.php">Pilih Kota</a>
            </li>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a>PILIH KOTA</a>
                </div>
            </nav>
            <div>
                <form action="" method="post">          
                    <button type="submit" class="button" style="vertical-align:middle" value="PUSAT" name="PUSAT">
                        <span>PUSAT</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="JAKARTA" name="JAKARTA">
                        <span>JAKARTA</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="SURABAYA" name="SURABAYA">
                        <span>SURABAYA</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="MAKASSAR" name="MAKASSAR">
                        <span>MAKASSAR</span>
                    </button>                    
                    <br>
                    <button type="submit" class="button" style="vertical-align:middle" value="LAMPUNG" name="LAMPUNG">
                        <span>LAMPUNG</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="SEMARANG" name="SEMARANG">
                        <span>SEMARANG</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="MEDAN" name="MEDAN">
                        <span>MEDAN</span>
                    </button>
                    <button type="submit" class="button" style="vertical-align:middle" value="PALEMBANG" name="PALEMBANG">
                        <span>PALEMBANG</span>
                    </button>
                </form>
            </div>
        </div>
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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