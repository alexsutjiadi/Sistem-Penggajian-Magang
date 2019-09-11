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
                                <a href="/penggajianMagang/menu/inputMaster.php">Input Master</a>
                            </li>
                            <a href="/penggajianMagang/menu/payrollMasterFile.php">Manage Master Gaji</a>
                            <li>
                                <a href="/penggajianMagang/menu/alamatDanNpwp.php">Alamat & N.P.W.P</a>
                            </li>
                            <a href="/penggajianMagang/menu/masterBCA.php">Master B.C.A</a>
                            <li>
                                <a href="/penggajianMagang/menu/showNamaGolongan.php">Golongan</a>
                            </li>
                            <a href="/penggajianMagang/menu/inputGajiBaru.php">Gaji Baru</a>
                            <li>
                                <a href="/penggajianMagang/menu/inputTunjanganJabatan.php">Input Data Lain</a>
                            </li>
                        </ul>
                </li>
                <li class="active">
                    <a href="#pageSubpangkat" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Manage Pangkat
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubpangkat">
                        <li>
                            <a href="/penggajianMagang/menu/masterPangkatK1.php" class="w3-bar-item w3-button">K1 </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/masterPangkatK2.php" class="w3-bar-item w3-button">K2 </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/masterPangkatK3.php" class="w3-bar-item w3-button">K3 </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/master4Bplus.php" class="w3-bar-item w3-button">4B - TM </a>
                        </li>
                </li>
                <li class="active">
                    <a href="#pageSubTHRBONUS" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        THR BONUS
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubTHRBONUS">
                        <li>
                            <a href="/penggajianMagang/menu/inputTHR.php">Input THR</a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/inputBonus.php">Input Bonus</a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/pphTHR.php">PPH THR</a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/pphBonus.php">PPH Bonus</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <li class="active">
                <a href="/penggajianMagang/menu/hitungPPH.php">Hitung PPH</a>
            </li>
            <li class="active">
                    <a href="#pageSubLaporanBulan" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        Laporan Akhir Bulan
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubLaporanBulan">
                        <li>
                            <a href="/penggajianMagang/menu/slipgaji.php" class="w3-bar-item w3-button">Slip Gaji</a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/slipgajiTHR.php" class="w3-bar-item w3-button">Slip Gaji + THR </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/FullReport.php" class="w3-bar-item w3-button">Full Report </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/FullReportTHR.php" class="w3-bar-item w3-button">Full Report + THR </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/ReportSummary.php" class="w3-bar-item w3-button">Summary Report </a>
                        </li>
                        <li>
                            <a href="/penggajianMagang/menu/ReportSummaryTHR.php" class="w3-bar-item w3-button">Summary Report + THR </a>
                        </li>
                        </ul>
                </li>
            <li class="active">
                <a href="/penggajianMagang/menu/clearfile.php">Clear File</a>
            </li>
             <li class="active">
                <a href="/penggajianMagang/menu/rekap.php">Rekap</a>
            </li>
            <li class="active">
                <a href="/penggajianMagang/pilihKota.php">Pilih Kota</a>
            </li>
    </div>';
}

function rupiah($angka)
{
    $hasil = "Rp. " . number_format((int) $angka, 0, '', '.');
    return $hasil;
}


function sortDbf($dbf, $field){ // $dbf->lokasi+nama dbf, $field -> sort berdasar field mana
    $db=dbase_open($dbf, 2);
    $n = dbase_numrecords($db);

    for($i = 1; $i<=$n; $i++){
        for($j=$i+1;$j<=$n;$j++){
            $rowi = dbase_get_record_with_names($db, $i);
            $rowj = dbase_get_record_with_names($db, $j);

            if($rowi[$field] > $rowj[$field]){
                unset($rowi['deleted']);
                unset($rowj['deleted']);

                $temp = $rowi;
                $rowi = $rowj;
                $rowj = $temp;

                $rowi = array_values($rowi);
                $rowj = array_values($rowj);

                dbase_replace_record($db, $rowi, $i);
                dbase_replace_record($db, $rowj, $j);
            }
        }
    }
    dbase_close($db);
}
