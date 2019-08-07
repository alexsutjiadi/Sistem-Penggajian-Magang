<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}

include('library/tcpdf.php');
$pdf = new TCPDF('p','mm','A4');

$pdf ->setPrintHeader(false);


$print = "";

$pdf ->AddPage();

$pdf ->SetFont('Helvetica','',7.4);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);


$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
$db2 = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 0);
$db3 = dbase_open($_SESSION['pathKota'] . 'TABEL.DBF', 0);

$ndata = dbase_numrecords($db);
$row3 = dbase_get_record_with_names($db3, 1);


for($i=1;$i<=$ndata;$i++){
$row = dbase_get_record_with_names($db, $i);
$row2 = dbase_get_record_with_names($db2, $i);


$DanaKesehatan = $row['TUNJ_KES']+$row['JPK'];
$TotalTambah = $row['GAJI_DASAR']+$row['TUNJ_JAB']+$DanaKesehatan;
$TotalJHT = $row3['THT'] * $row['GAJI_DASAR']/100;
$TotalJmnPens = 1 * $row['GAJI_DASAR']/100;
if($TotalJmnPens>85124)
{
	$TotalJmnPens = 85124;
}
$TotalKurang = $TotalJHT+$row['JPK']+$TotalJmnPens+$row2['PPH_21'];
$TotalTHR = $row['THR']-$row2['PPH_THR'];
$TotalSemua = $TotalTambah+$TotalTHR-$TotalKurang-$row['PINJAMAN'];

$html = "
<table>
				
	<tr>
		<th>===============================================</th>
	</tr>
	<tr>
		<th>No.ID : ".$row['DEPT']." / ".$row['NIK']." </th>
	</tr>
	<tr>
		<th>Nama : ".$row['NAMA']."</th>
	</tr>
	<tr>
		<th>Status : ".$row['STATUS']."</th>
	</tr>
	<tr>
		<th>Pangkat : ".$row['PANGKAT']."</th>
	</tr>
	<tr>
		<th>Bulan : ".$row2['YTD_BLN']."</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th> --== PENDAPATAN ==-- </th>
	</tr>
	<tr>
		<th>Gaji Pokok : ".$row['GAJI_DASAR']."</th>
	</tr>
	<tr>
		<th>Dana Kesehatan: ".$DanaKesehatan."</th>
	</tr>
	<tr>
		<th>Tunj.Jabatan : ".$row['TUNJ_JAB']."</th>
	</tr>
	<tr>
		<th>*Total = ".$TotalTambah."</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>--== POTONGAN ==-- </th>
	</tr>
	<tr>
		<th>JHT - ".$row3['THT']."% : ".$TotalJHT."</th>
	</tr>
	<tr>
		<th>BPJS Kesehatan : ".$row['JPK']."</th>
	</tr>
	<tr>
		<th>Jmn Pensiun - 1% : ".$TotalJmnPens."</th>
	</tr>
	<tr>
		<th>PPH21 : ".$row2['PPH_21']."</th>
	</tr>
	<tr>
		<th>*Total = ".$TotalKurang."</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Koperasi + Pinj : ".$row['PINJAMAN']."</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>THR : ".$row['THR']."</th>
	</tr>
	<tr>
		<th>Pajak THR : ".$row2['PPH_THR']."</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Yang di Terima : ".$TotalSemua."</th>
		<th> </th>
	</tr>
	<tr>
		<th>===============================================</th>
	</tr>
</table>

";

	if($i & 1){
		$y = $pdf->getY();
		$pdf ->WriteHTMLCell(100,0,'',$y,$html,0,0,1,true,'J',true);
	}
	else{
		$pdf ->WriteHTMLCell(100,0,'','',$html,0,1,1,true,'J',true);
	}
}


$pdf ->Output();