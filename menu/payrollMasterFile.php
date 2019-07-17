<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
//edit data master gaji
if (isset($_POST['edit'])) {
	$rowId = $_POST['rowId'];
	$jamsos = strtoupper($_POST['jamsos']);
	$gajiDasar = $_POST['gajiDasar'];
	$pangkat = $_POST['pangkat'];
	$premiKesehatan = $_POST['premiKesehatan'];
	$tunjanganKesehatan = $_POST['tunjanganKesehatan'];
	$pilihanBank = $_POST['pilihanBank'];

	$db = dbase_open($_SESSION['pathKota'] . "GAJI.DBF", 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);

		unset($row['deleted']);

		$row['JAMSOSFLG'] = $jamsos;
		$row['GAJI_DASAR'] = $gajiDasar;
		$row['PANGKAT'] = $pangkat;
		$row['JPK'] = $premiKesehatan;
		$row['TUNJ_KES'] = $tunjanganKesehatan;
		$row['KODE_BANK'] = $pilihanBank;
		$row['KODE_BANK1'] = $pilihanBank;

		$row = array_values($row);
		dbase_replace_record($db, $row, $rowId);
	}
	dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];

	$db = dbase_open($_SESSION['pathKota'] . "GAJI.DBF", 2);
	$db2 = dbase_open($_SESSION['pathKota'] . 'WAKTU_MASUK.DBF', 2);
	if ($db) {
		dbase_delete_record($db, $idDelete);
		dbase_delete_record($db2, $idDelete);
		dbase_pack($db);
		dbase_pack($db2);
	}
	dbase_close($db);
	dbase_close($db2);
}

//edit Gaji all / perkolom
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


if (isset($_POST['editPremi'])) {
	$rowId = $_POST['rowId'];
	$val = $_POST['val'];

	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);
	
		if($val!=""){
			unset($row['deleted']);
			$row['JPK'] = $val;
			$row = array_values($row);
			dbase_replace_record($db, $row, $rowId);

		}

		dbase_close($db);
	}
}

if (isset($_POST['editTunjKes'])) {
	$rowId = $_POST['rowId'];
	$val = $_POST['val'];

	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);
		
		if($val!=""){
			unset($row['deleted']);
			$row['TUNJ_KES'] = $val;
			$row = array_values($row);
			dbase_replace_record($db, $row, $rowId);
		}

		dbase_close($db);
	}
}


//fetch data golongan dri db
$db = dbase_open($_SESSION['pathKota'] . "GAJI.DBF", 0);
$db2 = dbase_open($_SESSION['pathKota'] . 'WAKTU_MASUK.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>MANAGE MASTER GAJI</title>

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
			//gaji  jamsosflg brubah
			$('#jamsosId, #gajiId').change(function() {
				var inputValue = $("#gajiId").val();
				var kotaValue = $("#kodeKota").val();
				var jamsosflg = $("#jamsosId").val();
				var pangkatAwal = $("#pangkatId").val();
				jamsosflg = jamsosflg.toUpperCase();

				//Ajax for calling php function
				$.post('../src/cekPangkat.php', {
					gajiV: inputValue,
					kotaV: kotaValue,
					jamsos: jamsosflg
				}, function(data) {
					$("#pangkatId").val(data.pangkat);
					$("#tunjKesId").val(data.tunjKes);
					$("#premiId").val(data.premi);
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
								$.post("payrollMasterFile.php", {
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

			////button edit Premi
			$("#buttonEditPremi").click(function() {
				var con = $("#buttonEditPremi").data("condition");
				if (con == true) {
					//alert("masuk edit");
					var totalRow = $(".totalRow").val() - 1;
					$("#buttonEditPremi").val("SAVE PREMI");
					$("#buttonEditPremi").data("condition", false);
					$("#myTable").off("click");
					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var sebelum = $(".tdPremi" + i).text();
							//alert(sebelum);
							$(".tdPremi" + i).html(sebelum + "<br><input type='text' class='editPremi' id='etPremi" + i + "'>")
						})(i);
					}
				} else {
					//alert("masuk save");
					var totalRow = $(".totalRow").val() - 1;
					//alert(totalRow);
					$("#buttonEditPremi").val("Edit All Premi");
					$("#buttonEditPremi").data("condition", true);
					$("#myTable").on("click", "td", function() {
						editPerKolom();
					});

					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var valInput = $("#etPremi" + i).val();
							if (valInput != "") {
								$(".tdPremi" + i).html(valInput);
								$.post("payrollMasterFile.php", {
									editPremi: "1",
									rowId: i,
									val: valInput
								}, function(resp) {

								});
							} else {
								var valSebelum = $(".tdPremi" + i).text();
								$(".tdPremi" + i).html(valSebelum);
							}
						})(i);
					}
				}
			});

			//button edit TunjKes
			$("#buttonEditTunjKes").click(function() {
				var con = $("#buttonEditTunjKes").data("condition");
				if (con == true) {
					//alert("masuk edit");
					var totalRow = $(".totalRow").val() - 1;
					$("#buttonEditTunjKes").val("SAVE TUNJ_KES");
					$("#buttonEditTunjKes").data("condition", false);
					$("#myTable").off("click");
					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var sebelum = $(".tdTunjKes" + i).text();
							//alert(sebelum);
							$(".tdTunjKes" + i).html(sebelum + "<br><input type='text' class='editTunjKes' id='etTunjKes" + i + "'>")
						})(i);
					}
				} else {
					//alert("masuk save");
					var totalRow = $(".totalRow").val() - 1;
					//alert(totalRow);
					$("#buttonEditTunjKes").val("Edit All Tunj_Kes");
					$("#buttonEditTunjKes").data("condition", true);
					$("#myTable").on("click", "td", function() {
						editPerKolom();
					});

					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var valInput = $("#etTunjKes" + i).val();
							if (valInput != "") {
								$(".tdTunjKes" + i).html(valInput);
								$.post("payrollMasterFile.php", {
									editTunjKes: "1",
									rowId: i,
									val: valInput
								}, function(resp) {

								});
							} else {
								var valSebelum = $(".tdTunjKes" + i).text();
								$(".tdTunjKes" + i).html(valSebelum);
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
					<input type="hidden" name="kodeKota" value="' + $("#kodeKota").val() + '"> \
                    <br /> \
					<input type="submit" name="editGaji"> \
                </form><form action=""><input type="submit" value="Cancel"></form>';
						$(this).html(form);
					}

					//click extra
					else if ($(this).data("mode") == "premi") {
						$("#myTable").off("click");
						var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <br /> \
					<input type="submit" name="editPremi"> \
                </form><form action=""><input type="submit" value="Cancel"></form>';
						$(this).html(form);
					}

					//click kolom pinjaman
					else if ($(this).data("mode") == "tunjKes") {
						$("#myTable").off("click");
						var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <br /> \
					<input type="submit" name="editTunjKes"> \
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
					<a>MANAGE MASTER GAJI <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
				</div>
			</nav>
			<div class="tableButton">
				<div style="margin-right:300px">
					<input type="button" tabindex="-1" name="buttonEditGaji" value="Edit All Gaji" id="buttonEditGaji" data-condition="true" class="bEdit">
					<input type="button" tabindex="-1" name="buttonEditPremi" value="Edit All Premi" id="buttonEditPremi" data-condition="true" class="bEdit">
					<input type="button" tabindex="-1" name="buttonEditTunjKes" value="Edit All Tunj_Kes" id="buttonEditTunjKes" data-condition="true" class="bEdit">
				</div>

				<table width="100%" cellspacing="0" id="myTable">
					<tr>
						<th onclick="sortTable(0,'T')">DEPT</th>
						<th onclick="sortTable(1,'T')">NO</th>
						<th onclick="sortTable(2,'T')">NAMA</th>
						<th onclick="sortTable(3,'N')">GAJI DASAR</th>
						<th onclick="sortTable(4,'N')">PREMI KESEHATAN</th>
						<th onclick="sortTable(5,'N')">TUNJANGAN KESEHATAN</th>
						<th colspan="2"></th>
					</tr>
					<?php
					$i = 0;
					for ($i = 1; $i <= $record_numbers; $i++) { ?>
						<tr>
							<?php $row = dbase_get_record_with_names($db, $i);
							$row2 = dbase_get_record_with_names($db2, $i);
							?>
							<td>
								<?php echo $row['DEPT'] ?>
							</td>
							<td>
								<?php echo $row['NO_URUT']; ?>
							</td>
							<td>
								<?php echo $row['NAMA']; ?>
							</td>
							<td class=<?php echo "tdGaji" . $i ?> data-mode="gaji" data-row=<?php echo $i ?>>
								<?php echo $row['GAJI_DASAR']; ?>
							</td>
							<td class=<?php echo "tdPremi" . $i ?> data-mode="premi" data-row=<?php echo $i ?>>
								<?php echo $row['JPK']; ?>
							</td>
							<td class=<?php echo "tdTunjKes" . $i ?> data-mode="tunjKes" data-row=<?php echo $i ?>>
								<?php echo $row['TUNJ_KES']; ?>
							</td>
							<td>
								<input type="hidden" name="nik" value=<?php echo $row['NIK']; ?> id=<?php echo "nik" . $i; ?>>
								<input type="hidden" name="tglLahir" value=<?php echo substr($row['TGL_LAHIR'], 6, 2) . "-" . substr($row['TGL_LAHIR'], 4, 2) . "-" . substr($row['TGL_LAHIR'], 0, 4); ?> id=<?php echo "tglLahir" . $i; ?>>
								<input type="hidden" name="alamat" value=<?php echo $row['ALAMAT']; ?> id=<?php echo "alamat" . $i; ?>>
								<input type="hidden" name="status" value=<?php echo $row['STATUS']; ?> id=<?php echo "status" . $i; ?>>
								<input type="hidden" name="kelamin" value=<?php echo $row['KELAMIN']; ?> id=<?php echo "kelamin" . $i; ?>>
								<input type="hidden" name="golongan" value=<?php echo $row['GOLONGAN']; ?> id=<?php echo "golongan" . $i; ?>>
								<input type="hidden" name="tanggungan" value=<?php echo $row['KELUARGA']; ?> id=<?php echo "tanggungan" . $i; ?>>
								<input type="hidden" name="tglMasuk" value=<?php echo substr($row2['TGL_MASUK'], 6, 2) . "-" . substr($row2['TGL_MASUK'], 4, 2) . "-" . substr($row2['TGL_MASUK'], 0, 4); ?> id=<?php echo "tglMasuk" . $i; ?>>
								<input type="hidden" name="aktifFiskal" value=<?php echo substr($row['BLN_AKTIV'], 6, 2) . "-" . substr($row['BLN_AKTIV'], 4, 2) . "-" . substr($row['BLN_AKTIV'], 0, 4); ?> id=<?php echo "aktifFiskal" . $i; ?>>
								<input type="hidden" name="npwp" value=<?php echo $row['NPWP']; ?> id=<?php echo "npwp" . $i; ?>>
								<input type="hidden" name="jamsos" value=<?php echo $row['JAMSOSFLG']; ?> id=<?php echo "jamsos" . $i; ?>>
								<input type="hidden" name="gajiDasar" value=<?php echo $row['GAJI_DASAR']; ?> id=<?php echo "gajiDasar" . $i; ?>>
								<input type="hidden" name="pangkat" value=<?php echo $row['PANGKAT']; ?> id=<?php echo "pangkat" . $i; ?>>
								<input type="hidden" name="premiKesehatan" value=<?php echo $row['JPK']; ?> id=<?php echo "premiKesehatan" . $i; ?>>
								<input type="hidden" name="tunjanganKesehatan" value=<?php echo $row['TUNJ_KES']; ?> id=<?php echo "tunjanganKesehatan" . $i; ?>>
								<input type="hidden" name="pilihanBank" value=<?php echo $row['KODE_BANK']; ?> id=<?php echo "pilihanBank" . $i; ?>>
								<input type="hidden" name="dept" value=<?php echo $row['DEPT']; ?> id=<?php echo "dept" . $i; ?>>
								<input type="hidden" name="no" value=<?php echo $row['NO_URUT']; ?> id=<?php echo "no" . $i; ?>>
								<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
								<input type="submit" class="btnUpdate" tabindex="-1" data-toggle="modal" data-target="#mdl-update" value="DETAIL" name="modal" data-id=<?php echo $i; ?>>
							</td>
							<td>
								<form action="" method="post">
									<input type="hidden" name="idDelete" value=<?php echo $i; ?>>
									<input type="submit" tabindex="-1" onclick="return isValidForm()" name="delete" class="btnDelete" value="DELETE">
								</form>
							</td>
						</tr>
					<?php }
					dbase_close($db);
					dbase_close($db2); ?>
					<input type="hidden" name="totalRow" class="totalRow" value=<?php echo $i ?>>
					<input type="hidden" name="kodeKota" id="kodeKota" value=<?php echo $_SESSION['kodeKota'] ?>>
				</table>
			</div>

			<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">PEMELIHARAAN DATA KARYAWAN</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="" method="post">
							<div class="modal-body">
								<div class="col-lg-12">
									<label for="no">NO. URUT</label>
									<input type="text" class="no" name="no" placeholder="" disabled>
									<input type="hidden" class="rowId" name="rowId">
								</div>
								<div class="col-lg-12">
									<label for="nik">NO. ID</label>
									<input type="text" class="nik" name="nik" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="dept">DEPARTEMEN</label>
									<input type="text" class="dept" name="dept" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="tglLahir">TGL LAHIR</label>
									<input type="text" class="tglLahir" name="tglLahir" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="nama">NAMA</label>
									<input type="text" class="nama" name="nama" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="alamat">ALAMAT</label>
									<input type="text" class="alamat" name="alamat" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="status">STATUS</label>
									<input type="text" class="status" name="status" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="kelamin">JENIS KELAMIN</label>
									<input type="text" class="kelamin" name="kelamin" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="golongan">GOLONGAN</label>
									<input type="text" class="golongan" name="golongan" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="tanggungan">TANGGUNGAN</label>
									<input type="text" class="tanggungan" name="tanggungan" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="tglMasuk">TANGGAL MASUK</label>
									<input type="text" class="tglMasuk" name="tglMasuk" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="aktifFiskal">AKTIF FISKAL</label>
									<input type="text" class="aktifFiskal" name="aktifFiskal" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="npwp">N.P.W.P</label>
									<input type="text" class="npwp" name="npwp" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="jamsos">JAMSOS (Y/T)</label>
									<input type="text" class="jamsos" name="jamsos" id="jamsosId" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="gajiDasar">GAJI DASAR</label>
									<input type="text" class="gajiDasar" name="gajiDasar" id="gajiId" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="pangkat">PANGKAT GOLONGAN</label>
									<input type="text" class="pangkat" name="pangkat" id="pangkatId" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="premiKesehatan">PREMI KESEHATAN</label>
									<input type="text" class="premiKesehatan" name="premiKesehatan" id="premiId" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="tunjanganKesehatan">TUNJANGAN KESEHATAN</label>
									<input type="text" class="tunjanganKesehatan" name="tunjanganKesehatan" id="tunjKesId" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="pilihanBank">PILIHAN BANK (1=BCA, 2=TUNAI)</label>
									<input type="text" class="pilihanBank" name="pilihanBank" placeholder="">
								</div>
								<!-- <div class="col-lg-12">
								<label for="bruto">BRUTO</label>
								<input type="text" class="bruto" name="bruto" placeholder="">
							</div> -->
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

		<script>
			$(document).on("click", ".btnUpdate", function() {
				var clickId = $(this).data('id');
				var noValue = $("#no" + clickId).val();
				var nikValue = $("#nik" + clickId).val();
				var deptValue = $("#dept" + clickId).val();
				var tglLahirValue = $("#tglLahir" + clickId).val();
				var namaValue = $("#nama" + clickId).val();
				var alamatValue = $("#alamat" + clickId).val();
				var statusValue = $("#status" + clickId).val();
				var KelaminValue = $("#kelamin" + clickId).val();
				var golonganValue = $("#golongan" + clickId).val();
				var tanggunganValue = $("#tanggungan" + clickId).val();
				var tglMasukValue = $("#tglMasuk" + clickId).val();
				var aktifFiskalValue = $("#aktifFiskal" + clickId).val();
				var npwpValue = $("#npwp" + clickId).val();
				var jamsosValue = $("#jamsos" + clickId).val();
				var gajiDasarValue = $("#gajiDasar" + clickId).val();
				var pangkatValue = $("#pangkat" + clickId).val();
				var premiKesehatanValue = $("#premiKesehatan" + clickId).val();
				var tunjanganKesehatanValue = $("#tunjanganKesehatan" + clickId).val();
				var pilihanBankValue = $("#pilihanBank" + clickId).val();

				$(".modal-body .rowId").val(clickId);
				$(".modal-body .no").val(noValue);
				$(".modal-body .nik").val(nikValue);
				$(".modal-body .dept").val(deptValue);
				$(".modal-body .tglLahir").val(tglLahirValue);
				$(".modal-body .nama").val(namaValue);
				$(".modal-body .alamat").val(alamatValue);
				$(".modal-body .status").val(statusValue);
				$(".modal-body .kelamin").val(KelaminValue);
				$(".modal-body .golongan").val(golonganValue);
				$(".modal-body .tanggungan").val(tanggunganValue);
				$(".modal-body .tglMasuk").val(tglMasukValue);
				$(".modal-body .aktifFiskal").val(aktifFiskalValue);
				$(".modal-body .npwp").val(npwpValue);
				$(".modal-body .jamsos").val(jamsosValue);
				$(".modal-body .gajiDasar").val(gajiDasarValue);
				$(".modal-body .pangkat").val(pangkatValue);
				$(".modal-body .premiKesehatan").val(premiKesehatanValue);
				$(".modal-body .tunjanganKesehatan").val(tunjanganKesehatanValue);
				$(".modal-body .pilihanBank").val(pilihanBankValue);
				//$(".modal-body .bruto").val(bruto);

			});

			function isValidForm() {
				var pilihan = confirm("hapus");;
				if (pilihan) {
					return true;
				} else {
					return false;
				}
			}

			function sortTable(n, mode) {
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
							if (mode == 'N') {
								if (Number(x.innerHTML) > Number(y.innerHTML)) {
									//if so, mark as a switch and break the loop:
									shouldSwitch = true;
									break;
								}
							} else {
								if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
									//if so, mark as a switch and break the loop:
									shouldSwitch = true;
									break;
								}
							}

						} else if (dir == "desc") {
							if (mode == 'N') {
								if (Number(x.innerHTML) < Number(y.innerHTML)) {
									//if so, mark as a switch and break the loop:
									shouldSwitch = true;
									break;
								}
							} else {
								if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
									//if so, mark as a switch and break the loop:
									shouldSwitch = true;
									break;
								}
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