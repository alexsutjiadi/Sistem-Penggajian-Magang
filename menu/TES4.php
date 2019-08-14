<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}
function rupiah($angka)
{
    $hasil = "Rp. " . number_format((int) $angka, 0, '', '.');
    return $hasil;
}
include('library/tcpdf.php');
$pdf = new TCPDF('L', 'mm', 'F4');


$pdf->SetTitle('TCPDF Example 001');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< DAFTAR GAJI >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

$pdf->AddPage();

$pdf->SetFont('Helvetica', '', 7.4);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);


$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
$db2 = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 0);
$db3 = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 0);

$ndata = dbase_numrecords($db);
$ngol = dbase_numrecords($db2);
$pph = dbase_numrecords($db3);
$print = "";
$no_urut = 0;
$SuperTotalGajiBruto = 0;
$SuperTotalTunjJab = 0;
$SuperTotalTunjKes = 0;
$SuperTotalGajiNetto = 0;
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
                                    <td rowspan="2" width="70">Pend.Lain/ Pinjaman</td>
                                    <td rowspan="2" width="70">Yang Diterima</td>
                                    <td rowspan="2" >Transfer BCA</td>
                                    <td rowspan="2" width="70">Tunai</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>Kesehatan</td>
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
            $no_urut++;
            $datapertama = false;
            $GajiBruto = $row['GAJI_DASAR'] + $row['TUNJ_REG'] + $row['TUNJ_JAB'] + $row['TUNJ_KES'];
            $GajiNetto = $GajiBruto-($row3['JABAT'] + $row3['THT'] + $row['JPK']);
            $KolomExtra = $row['EXTRA_LAIN']-$row['PINJAMAN'];
            $Total = $GajiNetto-$KolomExtra;

            $TotalGajiBruto = (int)$TotalGajiBruto + (int)$GajiBruto;
            $TotalTunjJab = (int)$TotalTunjJab+(int)$row['TUNJ_JAB'];
            $TotalTunjKes = (int)$TotalTunjKes+(int)$row['TUNJ_KES'];
            $TotalGajiNetto = (int)$TotalGajiNetto+(int)$GajiNetto;
            $TotalKolomExtra = (int)$TotalKolomExtra+(int)$KolomExtra;
            $TotalYangditerima = (int)$TotalYangditerima+(int)$Total;
            $transfer = 0;
            $tunai = 0;
            


            if ($kodebca==1) {
                $transfer = $Total;
            }else{
                $tunai = $Total;
            }
            $TotalBCA = $TotalBCA + $transfer;
            $TotalTunai = $TotalTunai + $tunai;

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
            $SuperTotalGajiBruto = (int)$SuperTotalGajiBruto+(int)$TotalGajiBruto;
            $SuperTotalTunjJab = (int)$SuperTotalTunjJab+(int)$TotalTunjJab;
            $SuperTotalTunjKes = (int)$SuperTotalTunjKes+(int)$TotalTunjKes;
            $SuperTotalGajiNetto = (int)$SuperTotalGajiNetto+(int)$TotalGajiNetto;
            $SuperTotalKolomExtra = (int)$SuperTotalKolomExtra+(int)$TotalKolomExtra;
            $SuperTotalYangditerima = (int)$SuperTotalYangditerima+(int)$TotalYangditerima;
            $SuperTotalBCA = (int)$SuperTotalBCA+(int)$TotalBCA;
            $SuperTotalTunai = (int)$SuperTotalTunai+(int)$TotalTunai;
            
            $TotalGajiBruto = rupiah($TotalGajiBruto);
            $TotalTunjJab = rupiah($TotalTunjJab);
            $TotalTunjKes = rupiah($TotalTunjKes);
            $TotalGajiNetto = rupiah($TotalGajiNetto);
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
                        <td>$TotalKolomExtra</td>
                        <td>$TotalYangditerima</td>
                        <td>$TotalBCA</td>
                        <td>$TotalTunai</td>
                    </tr>
EOD;
        }
    }
    
    
}

$SuperTotalGajiBruto = rupiah($SuperTotalGajiBruto);
$SuperTotalTunjJab = rupiah($SuperTotalTunjJab);
$SuperTotalTunjKes = rupiah($SuperTotalTunjKes);
$SuperTotalGajiNetto = rupiah($SuperTotalGajiNetto);
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
