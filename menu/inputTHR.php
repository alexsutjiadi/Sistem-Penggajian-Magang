<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}
include "../src/main.php";
//thr
if (isset($_POST['edit'])) {
	$thr = $_POST['thr'];
	$rowId = $_POST['rowId'];
	$pilihanBank = $_POST['pilihanBank'];


	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);

		$row = dbase_get_record_with_names($db, $rowId);
		//echo $row['NAMA'];

		unset($row['deleted']);
		$row['KODE_BANK'] = $pilihanBank;
		$row['THR'] = $thr;
		$row = array_values($row);
		dbase_replace_record($db, $row, $rowId);


		dbase_close($db);
	}
}

//edit all thr
if (isset($_POST['editThr'])) {
	$rowId = $_POST['rowId'];
	$val = $_POST['val'];

	$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);
		if ($val != "") {
			unset($row['deleted']);
			$row['THR'] = $val;
			$row = array_values($row);
			dbase_replace_record($db, $row, $rowId);
			dbase_close($db);
		}
	}
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
	<title>INPUT THR</title>

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
			$("#buttonEditThr").click(function() {
				var con = $("#buttonEditThr").data("condition");
				if (con == true) {
					//alert("masuk edit");
					var totalRow = $(".totalRow").val() - 1;
					$("#buttonEditThr").val("SAVE Thr");
					$("#buttonEditThr").data("condition", false);
					$("#myTable").off("click");
					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var sebelum = $(".tdThr" + i).text();
							//alert(sebelum);
							$(".tdThr" + i).html(sebelum + "<br><input type='text' class='editThr' id='etThr" + i + "'>")
						})(i);
					}
				} else {
					//alert("masuk save");
					var totalRow = $(".totalRow").val() - 1;
					//alert(totalRow);
					$("#buttonEditThr").val("Edit All Thr");
					$("#buttonEditThr").data("condition", true);
					$("#myTable").on("click", "td", function() {
						editPerKolom();
					});

					for (var i = 1; i <= totalRow; i++) {
						(function(i) {
							var valInput = $("#etThr" + i).val();
							if (valInput != "") {
								$(".tdThr" + i).html(valInput);
								$.post("innputBonus.php", {
									editThr: "1",
									rowId: i,
									val: valInput
								}, function(resp) {

								});
							} else {
								var valSebelum = $(".tdThr" + i).text();
								$(".tdThr" + i).html(valSebelum);
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

					// thr
					if ($(this).data("mode") == "thr") {
						$("#myTable").off("click");
						var form = '<form action="" method="POST"> \
					' + $(this).text() + ' \
                    <br><input type="text" name="val" /> \
					<input type="hidden" name="rowId" value="' + $(this).data("row") + '"> \
                    <br /> \
					<input type="submit" value="Submit" name="editThr"> \
                <input type="submit" value="Cancel"></form>';
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
	<?php printSideBar() ?>
	<div id="content">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a>INPUT THR <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
			</div>
		</nav>
		<div class="tableButton">
			<div style="margin-right: 120px">
				<input type="button" tabindex="-1" name="buttonEditThr" value="Edit All Thr" id="buttonEditThr" data-condition="true" class="bEdit">
			</div>
			<table width="100%" cellspacing='0' id="myTable">
				<thead>
					<tr>
						<th onclick="sortTable(0,'T')">DEPT</th>
						<th onclick="sortTable(1,'T')">Nama</th>
						<th onclick="sortTable(2,'N')">Gaji</th>
						<th onclick="sortTable(3,'N')">Tunjangan Jabatan</th>
						<th onclick="sortTable(4,'N')">THR</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$totalThr = 0;
					$i = 0;
					for ($i = 1; $i <= $record_numbers; $i++) { ?>
					<tr>
						<?php $row = dbase_get_record_with_names($db, $i);
							?>
						<td>
							<?php echo $row['DEPT']; ?>
						</td>
						<td>
							<?php echo $row['NAMA']; ?>
						</td>
						<td>
							<?php echo rupiah($row['GAJI_DASAR']); ?>
						</td>
						<td>
							<?php echo rupiah($row['TUNJ_JAB']); ?>

						</td>
						<td class=<?php echo "tdThr" . $i ?> data-mode="thr" data-row=<?php echo $i ?>>
							<?php echo rupiah($row['THR']); ?>
						</td>
						<td>
							<input type="submit" tabindex="-1" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
							<input type="hidden" name="total" value=<?php echo $row['GAJI_DASAR'] + $row['TUNJ_JAB'] ?> id=<?php echo "total" . $i; ?>>
							<input type="hidden" name="pilihanBank" value=<?php echo $row['KODE_BANK'] ?> id=<?php echo "pilihanBank" . $i; ?>>
							<input type="hidden" name="nik" value=<?php echo $row['DEPT']; ?> id=<?php echo "nik" . $i; ?>>
							<input type="hidden" name="gaji" value=<?php echo "'" . $row['GAJI_DASAR'] . "'"; ?> id=<?php echo "gaji" . $i; ?>>
							<input type="hidden" name="tunjangan_jab" value=<?php echo "'" . $row['TUNJ_JAB'] . "'"; ?> id=<?php echo "tunjangan_jab" . $i; ?>>
							<input type="hidden" name="thr" value=<?php echo "'" . $row['THR'] . "'";
																		$totalThr += (int) $row['THR']; ?> id=<?php echo "thr" . $i; ?>>
							<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>

						</td>
					</tr>
					<?php }
					dbase_close($db); ?>
				</tbody>
				<input type="hidden" name="totalRow" class="totalRow" value=<?php echo $i ?>>
			</table>
		</div>
		<p style="color:black;font-weight:bold;">Total THR yang dibayarkan : <?php echo rupiah($totalThr) ?></p>

		<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="" method="post">
						<div class="modal-header">
							<h5 class="modal-title">Add Jumlah THR</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-lg-12">
								<label for="nik">DEPT</label>
								<input type="text" class="nik" name="nik" placeholder="" disabled>
								<input type="hidden" id="rowId" class="rowId" name="rowId">
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
								<label for="tunjangan_jab">TUNJANGAN JABATAN</label>
								<input type="text" class="tunjangan_jab" name="tunjangan_jab" placeholder="" disabled>
							</div>
							<div class="col-lg-12">
								<label for="total">TOTAL</label>
								<input type="text" class="total" name="total" placeholder="" disabled>
							</div>
							<div class="col-lg-12">
								<label for="kode">THR</label>
								<input type="text" class="thr" name="thr" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="pilihanBank">Pilihan Bank (1. BCA, 2. Tunai)</label>
								<input type="text" class="pilihanBank" name="pilihanBank" placeholder="">
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
			var tunjangan_jabValue = $("#tunjangan_jab" + clickId).val();
			var thrValue = $("#thr" + clickId).val();
			var total = $("#total" + clickId).val();
			var pilihanBankValue = $("#pilihanBank" + clickId).val();

			$(".modal-body .nik").val(nikValue);
			$(".modal-body .nama").val(namaValue);
			$(".modal-body .gaji").val(gajiValue);
			$(".modal-body .tunjangan_jab").val(tunjangan_jabValue);
			$(".modal-body .thr").val(thrValue);
			$(".modal-body .total").val(total);
			$(".modal-body .rowId").val(clickId);
			$(".modal-body .pilihanBank").val(pilihanBankValue);
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
				$('#content').toggleClass('active');
			});
		});
	</script>
</body>

</html>