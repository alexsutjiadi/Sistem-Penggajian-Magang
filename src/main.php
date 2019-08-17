<?php
function printSideBar(){
    echo '<div class="wrapper">
        <!-- Sidebar  -->
        <div id="sidebar">
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
                                <a href="inputMaster.php">Input Master</a>
                            </li>
                            <a href="payrollMasterFile.php">Manage Master Gaji</a>
                            <li>
                                <a href="alamatDanNpwp.php">Alamat & N.P.W.P</a>
                            </li>
                            <a href="masterBCA.php">Master B.C.A</a>
                            <li>
                                <a href="showNamaGolongan.php">Golongan</a>
                            </li>
                            <a href="inputGajiBaru.php">Gaji Baru</a>
                            <li>
                                <a href="inputTunjanganJabatan.php">Input Data Lain</a>
                            </li>
                        </ul>
                </li>
                <li class="active">
                    <a href="#pageSubTHRBONUS" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        THR BONUS
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubTHRBONUS">
                        <li>
                            <a href="inputTHR.php">Input THR</a>
                        </li>
                        <li>
                            <a href="inputBonus.php">Input Bonus</a>
                        </li>
                        <li>
                            <a href="pphTHR.php">PPH THR</a>
                        </li>
                        <li>
                            <a href="pphBonus.php">PPH Bonus</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#pageSubpangkat" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Manage Pangkat
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubpangkat">
                        <li>
                            <a href="masterPangkatK1.php" class="w3-bar-item w3-button">K1 </a>
                        </li>
                        <li>
                            <a href="masterPangkatK2.php" class="w3-bar-item w3-button">K2 </a>
                        </li>
                        <li>
                            <a href="masterPangkatK3.php" class="w3-bar-item w3-button">K3 </a>
                        </li>
                        <li>
                            <a href="master4Bplus.php" class="w3-bar-item w3-button">4B - TM </a>
                        </li>
                </li>
            </ul>
            <li class="active">
                <a href="../pilihKota.php">Pilih Kota</a>
            </li>
            <li class="active">
                <a href="hitungPPH.php">Hitung PPH</a>
            </li>
            <li class="active">
                <a href="clearfile.php">Clear File</a>
            </li>
            <li class="active">
                    <a href="#pageSubLaporanBulan" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Laporan Akhir Bulan
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubLaporanBulan">
                        <li>
                            <a href="slipgaji.php" class="w3-bar-item w3-button">Slip Gaji</a>
                        </li>
                        <li>
                            <a href="slipgajiTHR.php" class="w3-bar-item w3-button">Slip Gaji + THR </a>
                        </li>
                        <li>
                            <a href="TES1.php" class="w3-bar-item w3-button">TES 1 </a>
                        </li>
                        <li>
                            <a href="TES2.php" class="w3-bar-item w3-button">TES 2 </a>
                        </li>
                        <li>
                            <a href="TES3.php" class="w3-bar-item w3-button">TES 3 </a>
                        </li>
                        <li>
                            <a href="TES4.php" class="w3-bar-item w3-button">TES 4 </a>
                        </li>
                </li>

    </div>';
}

function rupiah($angka)
{
    $hasil = "Rp. " . number_format((int) $angka, 0, '', '.');
    return $hasil;
}
?>