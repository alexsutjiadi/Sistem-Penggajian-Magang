<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
//edit button bonus
if (isset($_POST['edit'])) {
	$bonus = $_POST['bonus'];
	$rowId = $_POST['rowId'];
	$pphBonus = $_POST['pphBonus'];

	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);
		$row = dbase_get_record_with_names($db, $rowId);
		unset($row['deleted']);
		$row['BONUS'] = $bonus;
		$row = array_values($row);
		dbase_replace_record($db, $row, $rowId);


		dbase_close($db);
	}
}

//edit all bonus
if (isset($_POST['editBonus'])) {
	$rowId = $_POST['rowId'];
	$val = $_POST['val'];

	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);
		if($val!=""){
			unset($row['deleted']);
			$row['BONUS'] = $val;
			$row = array_values($row);
			dbase_replace_record($db, $row, $rowId);
		}
		dbase_close($db);
	}
}

function rupiah($angka)
{
	$hasil = "Rp. " . number_format((int)$angka, 0, '', '.');
	return $hasil;
}

//fetch data golongan dri db
$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>INPUT BONUS</title>

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
			$("#buttonEditBonus").click(function() {
				var con = $("#buttonEditBonus").data("condition");
				if (con == true) {
					//alert("masuk edit");
					var totalRow = $(".totalRow").val() - 1;
					$("#buttonEditBonus").val("SAVE Bonus");
					$("#buttonEditBonus").data("condition", false);
					$("#myTable").off("click");
					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var sebelum = $(".tdBonus" + i).text();
							//alert(sebelum);
							$(".tdBonus" + i).html(sebelum + "<br><input type='text' class='editBonus' id='etBonus" + i + "'>")
						})(i);
					}
				} else {
					//alert("masuk save");
					var totalRow = $(".totalRow").val() - 1;
					//alert(totalRow);
					$("#buttonEditBonus").val("Edit All Bonus");
					$("#buttonEditBonus").data("condition", true);
					$("#myTable").on("click", "td", function() {
						editPerKolom();
					});

					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var valInput = $("#etBonus" + i).val();
							if (valInput != "") {
								$(".tdBonus" + i).html(valInput);
								$.post("payrollMasterFile.php", {
									editBonus: "1",
									rowId: i,
									val: valInput
								}, function(resp) {

								});
							} else {
								var valSebelum = $(".tdBonus" + i).text();
								$(".tdBonus" + i).html(valSebelum);
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

					// Bonus
					if ($(this).data("mode") == "bonus") {
						$("#myTable").off("click");
						var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <br /> \
					<input type="submit" name="editBonus"> \
                </form><form action=""><input type="submit" value="Cancel"></form>';
						$(this).html(form);
					}
				});
			}
			// click 1 kolom
			editPerKolom();
		})
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
					<a>INPUT BONUS <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
				</div>
			</nav>
			<div class="tableButton">
				<div style="margin-right: 120px">
					<input type="button" tabindex="-1" name="buttonEditBonus" value="Edit All Bonus" id="buttonEditBonus" data-condition="true" class="bEdit">
				</div>
				<table width="100%" cellspacing='0' id="myTable">

					<tr>
						<th onclick="sortTable(0.'T')">DEPT</th>
						<th onclick="sortTable(1,'T')">Nama</th>
						<th onclick="sortTable(2,'N')">Gaji</th>
						<th onclick="sortTable(3,'N')">BONUS</th>
						<th></th>
					</tr>

					<?php
					$i = 0;
					$totalBonus = 0;
					for ($i = 1; $i <= $record_numbers; $i++) { ?>
						<tr>
							<?php $row = dbase_get_record_with_names($db, $i); ?>
							<td>
								<?php echo $row['DEPT'] ?>
							</td>
							<td>
								<?php echo $row['NAMA'] ?>
							</td>
							<td>
								<?php echo rupiah($row['GAJI_DASAR']) ?>
							</td>
							<td class=<?php echo "tdBonus" . $i ?> data-mode="bonus" data-row=<?php echo $i ?>>
								<?php echo rupiah($row['BONUS']) ?>
							</td>
							<td>
								<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
								<input type="hidden" name="bonus" value=<?php echo "'" . $row['BONUS'] . "'";
																		$totalBonus += $row['BONUS'] ?> id=<?php echo "bonus" . $i; ?>>
								<input type="hidden" name="gaji" value=<?php echo "'" . $row['GAJI_DASAR'] . "'"; ?> id=<?php echo "gaji" . $i; ?>>
								<input type="hidden" name="nik" value=<?php echo $row['DEPT']; ?> id=<?php echo "nik" . $i; ?>>
								<input type="submit" tabindex="-1" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
							</td>
						</tr>
					<?php }
					dbase_close($db); ?>
					<input type="hidden" name="totalRow" class="totalRow" value=<?php echo $i ?>>
				</table>
			</div>
			<p>Total Bonus Yang dibayarkan : <?php echo $totalBonus ?></p>

			<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form action="" method="post">
							<div class="modal-header">
								<h5 class="modal-title">Masukan Bonus Karywan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="col-lg-12">
									<label for="nik">NIK</label>
									<input type="text" class="nik" name="nik" placeholder="" disabled>
									<input type="hidden" class="rowId" name="rowId">
								</div>
								<div class="col-lg-12">
									<label for="nama">NAMA</label>
									<input type="text" class="nama" name="nama" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="gaji">GAJI</label>
									<input type="text" class="gaji" name="gaji" placeholder="" disabled>
								</div>
								<div class="col-lg-12">
									<label for="bonus">BONUS</label>
									<input type="text" class="bonus" name="bonus" placeholder="">
								</div>
								<div class="col-lg-12">
									<label for="pphBonus">PPH BONUS</label>
									<input type="text" class="pphBonus" name="pphBonus" placeholder="">
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
			$(document).on("click", ".btnUpdate", function() {
				var clickId = $(this).data('id');
				var nikValue = $("#nik" + clickId).val();
				var namaValue = $("#nama" + clickId).val();
				var gajiValue = $("#gaji" + clickId).val();
				var bonusValue = $("#bonus" + clickId).val();
				$(".modal-body .nik").val(nikValue);
				$(".modal-body .nama").val(namaValue);
				$(".modal-body .gaji").val(gajiValue);
				$(".modal-body .bonus").val(bonusValue);
				$(".modal-body .rowId").val(clickId);
			});

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