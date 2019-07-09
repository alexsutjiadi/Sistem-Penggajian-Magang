<?php
//edit gaji
if (isset($_POST['edit'])) {
	$pinjaman = $_POST['pinjaman'];
	$rowId = $_POST['rowId'];
	$extra = $_POST['extra'];
	$tunj_jab = $_POST['tunj_jab'];

	$db = dbase_open('../B/GAJI.DBF', 2);
	if ($db) {

		$row = dbase_get_record_with_names($db, $rowId);

		unset($row['deleted']);
		$row['PINJAMAN'] = $pinjaman;
		$row['EXTRA_LAIN'] = $extra;
		$row['TUNJ_JAB'] = $tunj_jab;
		$row = array_values($row);
		dbase_replace_record($db, $row, $rowId);

		dbase_close($db);
	}
}

//fetch data gaji dri db
$db = dbase_open('../B/GAJI.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../src/View.css">
	<link rel="stylesheet" type="text/css" href="../src/tampilan1.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#gajiId').change(function() {
				var inputValue = $(this).val();
				var kotaValue = $("#kotaId").val();
				//alert("value in js " + inputValue + kotaValue);

				//Ajax for calling php function
				$.post('../src/cekPangkat.php', {
					gajiV: inputValue,
					kotaV: "B"
				}, function(data) {
					//alert('ajax completed. Response:  ' + data);
					//do after submission operation in DOM
					$("#pangkatId").val(data);
					$("#pangkatValue").val(data);
				});
			});
		});
	</script>
</head>

<body>
	<div>
		<div class="wrap">
			<div class="header">
				<header>
					<img src="../img/back.png" align="right" height="40" width="40" margin-top="0" onclick="goBack()" />
					<h1>DATA LAIN - LAIN</h1>
				</header>
			</div>
		</div>
		<table border="1" id="myTable">
			<tr>
				<th onclick="sortTable(0,'T')">NO. DEPT</th>
				<th onclick="sortTable(1,'T')">NO. URUT</th>
				<th onclick="sortTable(2,'T')">NAMA</th>
				<th onclick="sortTable(3,'N')">TUNJANGAN JABATAN</th>
				<th onclick="sortTable(4,'N')">EXTRA LAIN</th>
				<th onclick="sortTable(5,'N')">PINJAMAN</th>
			</tr>
			<?php
			for ($i = 1; $i <= $record_numbers; $i++) { ?>
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i); ?>
					<td>
						<?php echo $row['DEPT']; ?>
					</td>
					<td>
						<?php echo $row['NO_URUT'] ?>
					</td>
					<td>
						<?php echo $row['NAMA']; ?>
					</td>
					<td>
						<?php echo $row['TUNJ_JAB'] ?>
					</td>
					<td>
						<?php echo $row['EXTRA_LAIN'] ?>
					</td>
					<td>
						<?php echo $row['PINJAMAN'] ?>
					</td>

					<td>
						<input type="hidden" name="tunj_jab" value=<?php echo "'" . $row['TUNJ_JAB'] . "'"; ?> id=<?php echo "tunj_jab" . $i; ?>>
						<input type="hidden" name="extra" value=<?php echo "'" . $row['EXTRA_LAIN'] . "'"; ?> id=<?php echo "extra" . $i; ?>>
						<input type="hidden" name="pinjaman" value=<?php echo "'" . $row['PINJAMAN'] . "'"; ?> id=<?php echo "pinjaman" . $i; ?>>
						<input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
					</td>
				</tr>
			<?php }
			dbase_close($db); ?>


		</table>
		<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">INPUT DATA LAIN</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="" method="POST">
						<div class="modal-body">
							<div class="col-lg-12">
								<label for="dept">NO. DEPT</label>
								<input type="text" class="dept" name="dept" placeholder="" disabled>
								<input type="hidden" class="rowId" name="rowId">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="" disabled>
							</div>
							<div class="col-lg-12">
								<label for="tunj_jab">TUNJANGAN JABATAN</label>
								<input type="text" class="tunj_jab" name="tunj_jab" id="tunj_jab" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="extra">EXTRA LAIN</label>
								<input type="text" class="extra" name="extra" id="extra" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="pinjaman">PINJAMAN</label>
								<input type="text" class="pinjaman" name="pinjaman" id="pinjaman" placeholder="">
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
			var deptValue = $("#dept" + clickId).val();
			var namaValue = $("#nama" + clickId).val();
			var tunj_jab = $("#tunj_jab" + clickId).val();
			var pinjaman = $("#pinjaman" + clickId).val();
			var extra = $("#extra" + clickId).val();

			$(".modal-body .dept").val(deptValue);
			$(".modal-body .nama").val(namaValue);
			$(".modal-body .tunj_jab").val(tunj_jab);
			$(".modal-body .rowId").val(clickId);
			$(".modal-body .extra").val(extra);
			$(".modal-body .pinjaman").val(pinjaman);



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
	<script>
		function goBack() {
			window.history.back();
		}
	</script>
</body>

</html>