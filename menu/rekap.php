<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
include "../src/main.php";
if (isset($_POST['gogo'])) {
	if (!$_POST['mode']) {
		$_POST['mode'] = date('Y-m-d');
	}
	require_once('library/tcpdf.php');

	$pdf = new TCPDF('L', 'mm', 'F4', true, 'UTF-8', false);

	//$init = parse_ini_file($_SESSION['pathKota'] . "init.ini");
	

	$pdf->SetHeaderData('logo.png', 80, 'REKAP (' . $_SESSION['kodeKota'] . ')', 'Cetak : ' . date('d-m-Y', strtotime($_POST['mode'])) );
	$pdf->SetFont('Helvetica', '', 9);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);

	$pdf->AddPage();
	$db = dbase_open($_SESSION['pathKota'] . 'NREKAP.DBF', 0);

	$ndata = dbase_numrecords($db);
	$No_urut = 0;

	for ($bln = 1; $bln <= 12; $bln++) {
		$print = "";
		$datapertama = true;	

		for ($gol = 1; $gol <= $ndata; $gol++) {
			$row = dbase_get_record_with_names($db, $gol);
			$row['GAJI1'] = rupiah($row['GAJI1']);
			$row['GAJI2'] = rupiah($row['GAJI2']);
			$row['GAJI3'] = rupiah($row['GAJI3']);
			$row['GAJI4'] = rupiah($row['GAJI4']);
			$row['GAJI5'] = rupiah($row['GAJI5']);
			$row['GAJI6'] = rupiah($row['GAJI6']);
			$row['GAJI7'] = rupiah($row['GAJI7']);
			$row['GAJI8'] = rupiah($row['GAJI8']);
			$row['GAJI9'] = rupiah($row['GAJI9']);
			$row['GAJI10'] = rupiah($row['GAJI10']);
			$row['GAJI11'] = rupiah($row['GAJI11']);
			$row['GAJI12'] = rupiah($row['GAJI12']);
			$row['THR1'] = rupiah($row['THR1']);
			$row['THR2'] = rupiah($row['THR2']);
			$row['THR3'] = rupiah($row['THR3']);
			$row['THR4'] = rupiah($row['THR4']);
			$row['THR5'] = rupiah($row['THR5']);
			$row['THR6'] = rupiah($row['THR6']);
			$row['THR7'] = rupiah($row['THR7']);
			$row['THR8'] = rupiah($row['THR8']);
			$row['THR9'] = rupiah($row['THR9']);
			$row['THR10'] = rupiah($row['THR10']);
			$row['THR11'] = rupiah($row['THR11']);
			$row['THR12'] = rupiah($row['THR12']);
			$row['PPH1'] = rupiah($row['PPH1']);
			$row['PPH2'] = rupiah($row['PPH2']);
			$row['PPH3'] = rupiah($row['PPH3']);
			$row['PPH4'] = rupiah($row['PPH4']);
			$row['PPH5'] = rupiah($row['PPH5']);
			$row['PPH6'] = rupiah($row['PPH6']);
			$row['PPH7'] = rupiah($row['PPH7']);
			$row['PPH8'] = rupiah($row['PPH8']);
			$row['PPH9'] = rupiah($row['PPH9']);
			$row['PPH10'] = rupiah($row['PPH10']);
			$row['PPH11'] = rupiah($row['PPH11']);
			$row['PPH12'] = rupiah($row['PPH12']);


			if ($bln == 1) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI1]</td>
						<td>$row[THR1]</td>
						<td>$row[PPH1]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI1]</td>
						<td>$row[THR1]</td>
						<td>$row[PPH1]</td>
					</tr>

EOD;
					if ($gol == $ndata) { 
						$print .= "</table>";
					}
				}
			} else if ($bln == 2) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI2]</td>
						<td>$row[THR2]</td>
						<td>$row[PPH2]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI2]</td>
						<td>$row[THR2]</td>
						<td>$row[PPH2]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 3) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI3]</td>
						<td>$row[THR3]</td>
						<td>$row[PPH3]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI3]</td>
						<td>$row[THR3]</td>
						<td>$row[PPH3]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 4) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI4]</td>
						<td>$row[THR4]</td>
						<td>$row[PPH4]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI4]</td>
						<td>$row[THR4]</td>
						<td>$row[PPH4]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 5) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI5]</td>
						<td>$row[THR5]</td>
						<td>$row[PPH5]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI5]</td>
						<td>$row[THR5]</td>
						<td>$row[PPH5]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 6) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI6]</td>
						<td>$row[THR6]</td>
						<td>$row[PPH6]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI6]</td>
						<td>$row[THR6]</td>
						<td>$row[PPH6]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 7) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI7]</td>
						<td>$row[THR7]</td>
						<td>$row[PPH7]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI7]</td>
						<td>$row[THR7]</td>
						<td>$row[PPH7]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 8) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI8]</td>
						<td>$row[THR8]</td>
						<td>$row[PPH8]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI8]</td>
						<td>$row[THR8]</td>
						<td>$row[PPH8]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 9) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI9]</td>
						<td>$row[THR9]</td>
						<td>$row[PPH9]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI9]</td>
						<td>$row[THR9]</td>
						<td>$row[PPH9]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 10) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI10]</td>
						<td>$row[THR10]</td>
						<td>$row[PPH10]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI10]</td>
						<td>$row[THR10]</td>
						<td>$row[PPH10]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 11) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI11]</td>
						<td>$row[THR11]</td>
						<td>$row[PPH11]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI11]</td>
						<td>$row[THR11]</td>
						<td>$row[PPH11]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			} else if ($bln == 12) {
				if ($datapertama == true) {
					$datapertama = false;
					$print .= <<<EOD
							<h1>Bulan $bln</h1>
							<table width="100%" border="1">
								<tr>
									<td>No</td>
									<td>Dept</td>
									<td>Nama</td>
									<td>Gaji</td>
									<td>THR</td>
									<td>PPH</td>
								</tr>
		
			
EOD;
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI12]</td>
						<td>$row[THR12]</td>
						<td>$row[PPH12]</td>
					</tr>

EOD;
				} else if ($datapertama == false) {
					$print .= <<<EOD
					<tr>
						<td>$row[NO_URUT]</td>
						<td>$row[DEPT]</td>
						<td>$row[NAMA]</td>
						<td>$row[GAJI12]</td>
						<td>$row[THR12]</td>
						<td>$row[PPH12]</td>
					</tr>

EOD;
					if ($gol == $ndata) {
						$print .= "</table>";
					}
				}
			}
		}
		$pdf->writeHTML($print, true, false, false, false, '');
	}

	$pdf->Output();
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>REKAP</title>

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
				<a>REKAP </a>
			</div>
		</nav>
		<form action="" method="post">
			<h5>Tanggal Cetak :</h5>
			<input type="date" name="mode">
			<div id="optional">
			</div>
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