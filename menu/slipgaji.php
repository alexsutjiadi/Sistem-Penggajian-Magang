<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
if (isset($_POST['gogo'])) {
	include('library/tcpdf.php');
	$pdf = new TCPDF('p', 'mm', 'A4');

	$pdf->setPrintHeader(false);

	$pdf->AddPage();

	$pdf->SetFont('Helvetica', '', 8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);


	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
	$db2 = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 0);
	$db3 = dbase_open($_SESSION['pathKota'] . 'TABEL.DBF', 0);

	$ndata = dbase_numrecords($db);
	$row3 = dbase_get_record_with_names($db3, 1);
	if ($_POST['mode'] == 1) {
		for ($i = 1; $i <= $ndata; $i++) {
			$row = dbase_get_record_with_names($db, $i);
			$row2 = dbase_get_record_with_names($db2, $i);

			$DanaKesehatan = $row['TUNJ_KES'] + $row['JPK'];
			$TotalTambah = $row['GAJI_DASAR'] + $row['TUNJ_JAB'] + $DanaKesehatan;
			$TotalKurang = "";
			$TotalSemua = "";
			$html = "
<table>		
	<tr>
		<th>===============================================</th>
	</tr>
	<tr>
		<th>No.ID : " . $row['DEPT'] . " / " . $row['NIK'] . " </th>
	</tr>
	<tr>
		<th>Nama : " . $row['NAMA'] . "</th>
	</tr>
	<tr>
		<th>Status : " . $row['STATUS'] . "</th>
	</tr>
	<tr>
		<th>Pangkat : " . $row['PANGKAT'] . "</th>
	</tr>
	<tr>
		<th>Bulan : " . $row2['YTD_BLN'] . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th> --== PENDAPATAN ==-- </th>
	</tr>
	<tr>
		<th>Gaji Pokok : " . $row['GAJI_DASAR'] . "</th>
	</tr>
	<tr>
		<th>Dana Kesehatan: " . $DanaKesehatan . "</th>
	</tr>
	<tr>
		<th>Tunj.Jabatan : " . $row['TUNJ_JAB'] . "</th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalTambah . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>--== POTONGAN ==-- </th>
	</tr>
	<tr>
		<th>JHT - " . $row3['THT'] . "% : " . $row2['THT'] . "</th>
	</tr>
	<tr>
		<th>BPJS Kesehatan : " . $row['JPK'] . "</th>
	</tr>
	<tr>
		<th>Jmn Pensiun - 1% : </th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalKurang . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Koperasi + Pinj : </th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Yang di Terima : " . $TotalSemua . "</th>
		<th> </th>
	</tr>
	<tr>
		<th>===============================================</th>
	</tr>
</table>

";

			if ($i & 1) {
				$y = $pdf->getY();
				$pdf->WriteHTMLCell(100, 0, '', $y, $html, 0, 0, 1, true, 'J', true);
			} else {
				$pdf->WriteHTMLCell(100, 0, '', '', $html, 0, 1, 1, true, 'J', true);
			}
		}
	} else if ($_POST['mode'] == 2) {
		$coun = 1;
		for ($i = 1; $i <= $ndata; $i++) {
			$row = dbase_get_record_with_names($db, $i);
			$row2 = dbase_get_record_with_names($db2, $i);
			$dept = $row['DEPT'];
			$sub_dept = substr($dept, 0, 1);
			if ($sub_dept == strtoupper($_POST['opt'])) {
				$DanaKesehatan = $row['TUNJ_KES'] + $row['JPK'];
				$TotalTambah = $row['GAJI_DASAR'] + $row['TUNJ_JAB'] + $DanaKesehatan;
				$TotalKurang = "";
				$TotalSemua = "";

				$html = "
<table>
				
	<tr>
		<th>===============================================</th>
	</tr>
	<tr>
		<th>No.ID : " . $row['DEPT'] . " / " . $row['NIK'] . " </th>
	</tr>
	<tr>
		<th>Nama : " . $row['NAMA'] . "</th>
	</tr>
	<tr>
		<th>Status : " . $row['STATUS'] . "</th>
	</tr>
	<tr>
		<th>Pangkat : " . $row['PANGKAT'] . "</th>
	</tr>
	<tr>
		<th>Bulan : " . $row2['YTD_BLN'] . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th> --== PENDAPATAN ==-- </th>
	</tr>
	<tr>
		<th>Gaji Pokok : " . $row['GAJI_DASAR'] . "</th>
	</tr>
	<tr>
		<th>Dana Kesehatan: " . $DanaKesehatan . "</th>
	</tr>
	<tr>
		<th>Tunj.Jabatan : " . $row['TUNJ_JAB'] . "</th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalTambah . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>--== POTONGAN ==-- </th>
	</tr>
	<tr>
		<th>JHT - " . $row3['THT'] . "% : " . $row2['THT'] . "</th>
	</tr>
	<tr>
		<th>BPJS Kesehatan : " . $row['JPK'] . "</th>
	</tr>
	<tr>
		<th>Jmn Pensiun - 1% : </th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalKurang . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Koperasi + Pinj : </th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Yang di Terima : " . $TotalSemua . "</th>
		<th> </th>
	</tr>
	<tr>
		<th>===============================================</th>
	</tr>
</table>

";

				if ($coun & 1) {
					$y = $pdf->getY();
					$pdf->WriteHTMLCell(100, 0, '', $y, $html, 0, 0, 1, true, 'J', true);
				} else {
					$pdf->WriteHTMLCell(100, 0, '', '', $html, 0, 1, 1, true, 'J', true);
				}
				$coun++;
			}
		}
	} else if ($_POST['mode'] == 3) {
		$coun = 1;
		for ($i = 1; $i <= $ndata; $i++) {
			$row = dbase_get_record_with_names($db, $i);
			$row2 = dbase_get_record_with_names($db2, $i);
			$nik = $row['NIK'];
			if ($nik == $_POST['opt']) {
				$DanaKesehatan = $row['TUNJ_KES'] + $row['JPK'];
				$TotalTambah = $row['GAJI_DASAR'] + $row['TUNJ_JAB'] + $DanaKesehatan;
				$TotalKurang = "";
				$TotalSemua = "";

				$html = "
<table>
				
	<tr>
		<th>===============================================</th>
	</tr>
	<tr>
		<th>No.ID : " . $row['DEPT'] . " / " . $row['NIK'] . " </th>
	</tr>
	<tr>
		<th>Nama : " . $row['NAMA'] . "</th>
	</tr>
	<tr>
		<th>Status : " . $row['STATUS'] . "</th>
	</tr>
	<tr>
		<th>Pangkat : " . $row['PANGKAT'] . "</th>
	</tr>
	<tr>
		<th>Bulan : " . $row2['YTD_BLN'] . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th> --== PENDAPATAN ==-- </th>
	</tr>
	<tr>
		<th>Gaji Pokok : " . $row['GAJI_DASAR'] . "</th>
	</tr>
	<tr>
		<th>Dana Kesehatan: " . $DanaKesehatan . "</th>
	</tr>
	<tr>
		<th>Tunj.Jabatan : " . $row['TUNJ_JAB'] . "</th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalTambah . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>--== POTONGAN ==-- </th>
	</tr>
	<tr>
		<th>JHT - " . $row3['THT'] . "% : " . $row2['THT'] . "</th>
	</tr>
	<tr>
		<th>BPJS Kesehatan : " . $row['JPK'] . "</th>
	</tr>
	<tr>
		<th>Jmn Pensiun - 1% : </th>
	</tr>
	<tr>
		<th>PPH21 : " . $row2['PPH_21'] . "</th>
	</tr>
	<tr>
		<th>*Total = " . $TotalKurang . "</th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Koperasi + Pinj : </th>
	</tr>
	<tr>
		<th>----------------------------------------------------------------------------------</th>
	</tr>
	<tr>
		<th>Yang di Terima : " . $TotalSemua . "</th>
		<th> </th>
	</tr>
	<tr>
		<th>===============================================</th>
	</tr>
</table>

";

				if ($coun & 1) {
					$y = $pdf->getY();
					$pdf->WriteHTMLCell(100, 0, '', $y, $html, 0, 0, 1, true, 'J', true);
				} else {
					$pdf->WriteHTMLCell(100, 0, '', '', $html, 0, 1, 1, true, 'J', true);
				}
				$coun++;
			}
		}
	}
	$pdf->Output();
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>SLIP GAJI</title>

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
			<li class="active">
				<a href="hitungPPH.php">Hitung PPH</a>
			</li>
		</nav>
		<div id="content">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container-fluid">
					<a>SLIP GAJI </a>
				</div>
			</nav>
			<form action="" method="post">
				<input type="radio" name="mode" value="1">ALL<br>
				<input type="radio" name="mode" class="rad" value="2">Group<br>
				<input type="radio" name="mode" class="rad" value="3">Individu<br>
				<div id="optional">
				</div>
				<input type="submit" name="gogo" value="SUBMIT">

			</form>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
			<!-- Bootstrap JS -->
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

			<script type="text/javascript">
				$(document).ready(function() {
					$('#sidebarCollapse').on('click', function() {
						$('#sidebar').toggleClass('active');
					});
					$("input[type=radio][name=mode]").change(function() {
						var mode = parseInt($(this).val());
						if (mode == 2) {
							$("#optional").html("<h5>DEPT: </h5><input type='text' name='opt'>");
						} else if (mode == 3) {
							$("#optional").html("<h5>NIK: </h5><input type='text' name='opt'>");
						} else if (mode == 1) {
							$("#optional").html('');
						}

					});
				});
			</script>
</body>

</html>