<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}

include('library/tcpdf.php');
$pdf = new TCPDF('L','mm','A4');

$pdf ->setPrintHeader(false);

$pdf ->AddPage();

$pdf ->SetFont('Helvetica','',7.4);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);


$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
$db2 = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 0);

$ndata = dbase_numrecords($db);
$ngol = dbase_numrecords($db2);

for($gol=1;$gol<=$ngol;$gol++){
$row2 = dbase_get_record_with_names($db2, $gol);
$kode = $row2['KODE'];
$namakode = $row2['NAMA'];
$kode1 = substr($kode,0,1);
for($i=1;$i<=$ndata;$i++){
$row = dbase_get_record_with_names($db, $i);
$pangkat = $row['DEPT'];
$pangkat1 = substr($pangkat,0,1);
$datapertama = true;
if($kode1 == $pangkat1){
	if($datapertama==true){
		$datapertama=false;
		$html = <<<EOD
			<table width="850" cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td rowspan="2" width="15">No</td>
					<td rowspan="2" width="35">NIK</td>
					<td rowspan="2" width="100">Nama</td>
					<td rowspan="2" width="35">Pangkat</td>
					<td rowspan="2" width="70">Gaji Bruto</td>
					<td colspan="2" align="center">Tunjangan</td>
					<td rowspan="2" width="70">Gaji Netto</td>
					<td rowspan="2" width="70">Pend.Lain/ Pinjaman</td>
					<td rowspan="2" width="70">Yang Diterima</td>
					<td colspan="2" align="center">Transfer</td>
					<td rowspan="2" width="70">Tunai</td>
				</tr>
				<tr>
					<td>Jabatan</td>
					<td>Kesehatan</td>
					<td>BCA(F)</td>
					<td>BCA(NF)</td>
				</tr>
				<tr>
					<td>$i</td>
					<td>$row[NIK]</td>
					<td>$row[NAMA]</td>
					<td>$row[PANGKAT]</td>
					<td></td>
					<td>$row[TUNJ_JAB]</td>
					<td>$row[TUNJ_KES]</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

EOD;
}
	else{	
			$html .= <<<EOD
				<tr>
					<td>$i</td>
					<td>$row[NIK]</td>
					<td>$row[NAMA]</td>
					<td>$row[PANGKAT]</td>
					<td></td>
					<td>$row[TUNJ_JAB]</td>
					<td>$row[TUNJ_KES]</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
EOD;
}
}

}

$pdf->writeHTML($html, true, false, false, false, '');
}
$pdf ->Output();