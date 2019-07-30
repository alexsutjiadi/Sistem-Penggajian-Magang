<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
//edit data golongan
if (isset($_POST['edit'])) {
	$kode = strtoupper($_POST['kode']);
	$nama = strtoupper($_POST['nama']);
	$rowId = $_POST['rowId'];

	$db = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);

		$row = dbase_get_record_with_names($db, $rowId);

		unset($row['deleted']);
		$row['KODE'] = $kode;
		$row['NAMA'] = $nama;
		$row = array_values($row);
		dbase_replace_record($db, $row, $rowId);

		dbase_close($db);
	}
}
//add data golongan
else if (isset($_POST['add'])) {
	$kode = strtoupper($_POST['addKode']);
	$nama = strtoupper($_POST['addNama']);

	$db = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 2);
	if ($db) {
		dbase_add_record($db, array($kode, $nama));
	}
	dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];
	//echo "string";

	$db = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 2);
	if ($db) {
		dbase_delete_record($db, $idDelete);
		dbase_pack($db);
	}
	dbase_close($db);
}

//fetch data golongan dri db
$db = dbase_open($_SESSION['pathKota'] . 'GOLONGAN.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>SHOW NAMA GOLONGAN</title>

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
					<a>SHOW NAMA GOLONGAN <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
				</div>
			</nav>
			<table width="100%" border="1" id="myTable">
				<tr>
					<th onclick="sortTable(0)">KODE</th>
					<th onclick="sortTable(1)">NAMA GOLONGAN</th>
					<th colspan="2"></th>
				</tr>

				<?php
				for ($i = 1; $i <= $record_numbers; $i++) { ?>
					<tr>
						<?php $row = dbase_get_record_with_names($db, $i); ?>
						<td>
							<?php echo $row['KODE']; ?>
						</td>
						<td>
							<?php echo $row['NAMA'] ?> </td>
						<td> <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
						</td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="kode" value=<?php echo $row['KODE']; ?> id=<?php echo "kode" . $i; ?>>
								<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
								<input type="hidden" name="idDelete" value=<?php echo $i; ?>>
								<input type="submit" onclick="return isValidForm()" name="delete" class="btnDelete" value="DELETE">
							</form>
						</td>
					</tr>
				<?php }
				dbase_close($db); ?>
				<tr>
					<td>
						<form action="" method="POST"><input type="text" name="addKode">
					</td>
					<td><input type="text" name="addNama"></td>
					<td colspan="2">
						<center><input type="submit" name="add" value="ADD"></center>
						</form>
					</td>
				</tr>

			</table>
		</div>
		<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="" method="post">
						<div class="modal-header">
							<h5 class="modal-title">Update Data Golongan</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-lg-12">
								<label for="kode">KODE</label>
								<input type="text" class="kode" name="kode" placeholder="">
								<input type="hidden" name="rowId" class="rowId" value="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA GOLONGAN</label>
								<input type="text" class="nama" name="nama" placeholder="">
							</div>
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
		sortTable(0);
		$(document).on("click", ".btnUpdate", function() {
			var clickId = $(this).data('id');
			var kodeValue = $("#kode" + clickId).val();
			var namaValue = $("#nama" + clickId).val();
			$(".modal-body .kode").val(kodeValue);
			$(".modal-body .nama").val(namaValue);
			$(".modal-body .rowId").val(clickId);
		});

		function isValidForm() {
			var pilihan = confirm("hapus");;
			if (pilihan) {
				return true;
			} else {
				return false;
			}
		}

		function sortTable(n) {
			var table, rows, switching, i, x, y, shouldSwitch;
			table = document.getElementById("myTable");
			switching = true;
			/* Make a loop that will continue until
			no switching has been done: */
			while (switching) {
				// Start by saying: no switching is done:
				switching = false;
				rows = table.rows;
				/* Loop through all table rows (except the
				first, which contains table headers): */
				for (i = 1; i < (rows.length - 1); i++) {
					// Start by saying there should be no switching:
					shouldSwitch = false;
					/* Get the two elements you want to compare,
					one from current row and one from the next: */
					x = rows[i].getElementsByTagName("TD")[n];
					y = rows[i + 1].getElementsByTagName("TD")[n];
					// Check if the two rows should switch place:
					if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
						// If so, mark as a switch and break the loop:
						shouldSwitch = true;
						break;
					}
				}
				if (shouldSwitch) {
					/* If a switch has been marked, make the switch
					and mark that a switch has been done: */
					rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
					switching = true;
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