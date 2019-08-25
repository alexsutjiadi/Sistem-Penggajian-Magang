<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}
include "../src/main.php";
if (isset($_POST['gogo'])) {
    $init = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $hitungPPH = $init['hitung_pph'];
    if ($hitungPPH == 0) {
        $message = "Hitung PPH Terlebih Dahulu";
        echo "<script type='text/javascript'>alert('$message');</script>";
        header("Refresh:0");
        return;
    }
    if (!$_POST['mode']) {
        $_POST['mode'] = date('Y-m-d');
    }

    require_once('library/tcpdf.php');

    $pdf = new TCPDF('L', 'mm', 'F4', true, 'UTF-8', false);

    $init = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $bulan = $init['posisi_bulan'];
    $tahun = date('Y', strtotime($_POST['mode']));
    if ($bulan == 1) {
        $bulan = "January";
    } else if ($bulan == 2) {
        $bulan = "February";
    } else if ($bulan == 3) {
        $bulan = "March";
    } else if ($bulan == 4) {
        $bulan = "April";
    } else if ($bulan == 5) {
        $bulan = "May";
    } else if ($bulan == 6) {
        $bulan = "June";
    } else if ($bulan == 7) {
        $bulan = "July";
    } else if ($bulan == 8) {
        $bulan = "August";
    } else if ($bulan == 9) {
        $bulan = "September";
    } else if ($bulan == 10) {
        $bulan = "October";
    } else if ($bulan == 11) {
        $bulan = "November";
    } else if ($bulan == 12) {
        $bulan = "December";
    }
    $bulan .= " - " . $tahun;

    $pdf->SetHeaderData('logo.png', 80, '                SUMMARY DAFTAR GAJI (' . $_SESSION['kodeKota'] . ')', 'Cetak : ' . date('d-m-Y', strtotime($_POST['mode'])) . '   Periode: ' . $bulan);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->AddPage();
    $db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
    $db2 = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 0);
    $db3 = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 0);

    $ndata = dbase_numrecords($db);
    $ngol = dbase_numrecords($db2);
    $pph = dbase_numrecords($db3);
    $print = "";
    $no_urut = 1;
    $SuperTotalGajiBruto = 0;
    $SuperTotalTunjJab = 0;
    $SuperTotalTunjKes = 0;
    $SuperTotalGajiNetto = 0;
    $SuperTotalTHR = 0;
    $SuperTotalTHRBruto = 0;
    $SuperTotalKolomExtra = 0;
    $SuperTotalYangditerima = 0;
    $SuperTotalBCA = 0;
    $SuperTotalTunai = 0;

    $print .= <<<EOD
                            <table width="100%" border="1">
                                <tr>
                                    <td rowspan="2" width="15">No</td>
                                    <td rowspan="2" width="100">Nama</td>
                                    <td rowspan="2" width="70">Gaji Bruto</td>
                                    <td colspan="2" align="center">Tunjangan</td>
                                    <td rowspan="2" width="70">Gaji Netto</td>
                                    <td colspan="2" align="center">T.H.R</td>
                                    <td rowspan="2" width="70">Pend.Lain/ Pinjaman</td>
                                    <td rowspan="2" width="70">Yang Diterima</td>
                                    <td rowspan="2" >Transfer BCA</td>
                                    <td rowspan="2" width="70">Tunai</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>Kesehatan</td>
                                    <td>Bruto</td>
                                    <td>Netto</td>
                                </tr>
EOD;

    for ($gol = 1; $gol <= $ngol; $gol++) {

        $row2 = dbase_get_record_with_names($db2, $gol);
        $kode = $row2['KODE'];
        $namakode = $row2['NAMA'];
        $kode1 = substr($kode, 0, 1);
        $datapertama = true;
        $TotalGajiBruto = 0;
        $TotalTunjJab = 0;
        $TotalTunjKes = 0;
        $TotalGajiNetto = 0;
        $TotalTHR = 0;
        $TotalTHRBruto = 0;
        $TotalKolomExtra = 0;
        $TotalYangditerima = 0;
        $TotalBCA = 0;
        $TotalTunai = 0;

        for ($i = 1; $i <= $ndata; $i++) {

            $row = dbase_get_record_with_names($db, $i);
            $kodebca = $row['KODE_BANK'];
            $kolomkosong = 0;
            $row3 = dbase_get_record_with_names($db3, $i);
            $pangkat = $row['DEPT'];
            $pangkat1 = substr($pangkat, 0, 1);


            if ($kode1 == $pangkat1) {
                $datapertama = false;
                $GajiBruto = $row['GAJI_DASAR'] + $row['TUNJ_REG'] + $row['TUNJ_JAB'] + $row['TUNJ_KES'];
                $GajiNetto = $GajiBruto - ($row3['JABAT'] + $row3['THT'] + $row['JPK']);
                $KolomExtra = $row['EXTRA_LAIN'] - $row['PINJAMAN'];
                $Total = $GajiNetto - $KolomExtra;
                $BrutoTHR = $row['THR'] - $row3['PPH_THR'];

                $TotalGajiBruto = (int) $TotalGajiBruto + (int) $GajiBruto;
                $TotalTunjJab = (int) $TotalTunjJab + (int) $row['TUNJ_JAB'];
                $TotalTunjKes = (int) $TotalTunjKes + (int) $row['TUNJ_KES'];
                $TotalGajiNetto = (int) $TotalGajiNetto + (int) $GajiNetto;
                $TotalTHR = (int) $TotalTHR + (int) $row['THR'];
                $TotalTHRBruto = (int) $TotalTHRBruto + (int) $BrutoTHR;
                $TotalKolomExtra = (int) $TotalKolomExtra + (int) $KolomExtra;
                $TotalYangditerima = (int) $TotalYangditerima + (int) $Total;
                $transfer = 0;
                $tunai = 0;



                if ($kodebca == 1) {
                    $transfer = $Total;
                } else {
                    $tunai = $Total;
                }
                $TotalBCA = $TotalBCA + $transfer;
                $TotalTunai = $TotalTunai + $tunai;

                $BrutoTHR = rupiah($BrutoTHR);
                $GajiBruto = rupiah($GajiBruto);
                $GajiNetto = rupiah($GajiNetto);
                $KolomExtra = rupiah($KolomExtra);
                $Total = rupiah($Total);
                $row['TUNJ_JAB'] = rupiah($row['TUNJ_JAB']);
                $row['TUNJ_KES'] = rupiah($row['TUNJ_KES']);
                $row['THR'] = rupiah($row['THR']);
                $kolomkosong = rupiah($kolomkosong);
                $transfer = rupiah($transfer);
                $tunai = rupiah($tunai);
            }
            if ($i == $ndata && $datapertama == false) {
                
                $SuperTotalGajiBruto = (int) $SuperTotalGajiBruto + (int) $TotalGajiBruto;
                $SuperTotalTunjJab = (int) $SuperTotalTunjJab + (int) $TotalTunjJab;
                $SuperTotalTunjKes = (int) $SuperTotalTunjKes + (int) $TotalTunjKes;
                $SuperTotalGajiNetto = (int) $SuperTotalGajiNetto + (int) $TotalGajiNetto;
                $SuperTotalTHR = (int) $SuperTotalTHR + (int) $TotalTHR;
                $SuperTotalTHRBruto = (int) $SuperTotalTHRBruto + (int) $TotalTHRBruto;
                $SuperTotalKolomExtra = (int) $SuperTotalKolomExtra + (int) $TotalKolomExtra;
                $SuperTotalYangditerima = (int) $SuperTotalYangditerima + (int) $TotalYangditerima;
                $SuperTotalBCA = (int) $SuperTotalBCA + (int) $TotalBCA;
                $SuperTotalTunai = (int) $SuperTotalTunai + (int) $TotalTunai;

                $TotalGajiBruto = rupiah($TotalGajiBruto);
                $TotalTunjJab = rupiah($TotalTunjJab);
                $TotalTunjKes = rupiah($TotalTunjKes);
                $TotalGajiNetto = rupiah($TotalGajiNetto);
                $TotalTHR = rupiah($TotalTHR);
                $TotalTHRBruto = rupiah($TotalTHRBruto);
                $TotalKolomExtra = rupiah($TotalKolomExtra);
                $TotalYangditerima = rupiah($TotalYangditerima);
                $TotalBCA = rupiah($TotalBCA);
                $TotalTunai = rupiah($TotalTunai);

                $print .= <<<EOD
                    <tr>
                        <td>$no_urut</td>
                        <td>$namakode</td>
                        <td>$TotalGajiBruto</td>
                        <td>$TotalTunjJab</td>
                        <td>$TotalTunjKes</td>
                        <td>$TotalGajiNetto</td>
                        <td>$TotalTHR</td>
                        <td>$TotalTHRBruto</td>
                        <td>$TotalKolomExtra</td>
                        <td>$TotalYangditerima</td>
                        <td>$TotalBCA</td>
                        <td>$TotalTunai</td>
                    </tr>
EOD;
                $no_urut++;
            }
        }
    }

    $SuperTotalGajiBruto = rupiah($SuperTotalGajiBruto);
    $SuperTotalTunjJab = rupiah($SuperTotalTunjJab);
    $SuperTotalTunjKes = rupiah($SuperTotalTunjKes);
    $SuperTotalGajiNetto = rupiah($SuperTotalGajiNetto);
    $SuperTotalTHR = rupiah($SuperTotalTHR);
    $SuperTotalTHRBruto = rupiah($SuperTotalTHRBruto);
    $SuperTotalKolomExtra = rupiah($SuperTotalKolomExtra);
    $SuperTotalYangditerima = rupiah($SuperTotalYangditerima);
    $SuperTotalBCA = rupiah($SuperTotalBCA);
    $SuperTotalTunai = rupiah($SuperTotalTunai);

    $print .= <<<EOD
                    <tr>
                        <td colspan="2">Total</td>
                        <td>$SuperTotalGajiBruto</td>
                        <td>$SuperTotalTunjJab</td>
                        <td>$SuperTotalTunjKes</td>
                        <td>$SuperTotalGajiNetto</td>
                        <td>$SuperTotalTHR</td>
                        <td>$SuperTotalTHRBruto</td>
                        <td>$SuperTotalKolomExtra</td>
                        <td>$SuperTotalYangditerima</td>
                        <td>$SuperTotalBCA</td>
                        <td>$SuperTotalTunai</td>
                    </tr>
EOD;
    $print .= <<<EOD
    </table>
EOD;
    $pdf->writeHTML($print, true, false, false, false, '');
    $pdf->Output();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>REPORT SUMMARY THR</title>

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
</head>

<body>
    <?php printSideBar() ?>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a>REPORT SUMMARY THR </a>
            </div>
        </nav>
        <form action="" method="post">
            <h5>Tanggal Cetak :</h5>
            <input type="date" name="mode">
            <br>
            <input type="submit" name="gogo" value="SUBMIT">

        </form>
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