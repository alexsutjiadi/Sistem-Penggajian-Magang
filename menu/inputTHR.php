<?php
//thr
if (isset($_POST['edit'])) {
	$nik = strtoupper($_POST['nik']);
	$nama = strtoupper($_POST['nama']);
	$gaji = strtoupper($_POST['gaji_dasar']);
	$tunjangan_jab = strtoupper($_POST['tunjangan_jab']);
	$thr = strtoupper($_POST['thr']);

	$db = dbase_open('../B/GAJI.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);
		for ($i = 1; $i <= $record_numbers; $i++) {
			$row = dbase_get_record_with_names($db, $i);
			//echo $row['NAMA'];
			if ($row['NIK'] == $nik) {
				unset($row['deleted']);
				$row['THR'] = $thr;
				$row = array_values($row);
				dbase_replace_record($db, $row, $i);
			}
		}
		dbase_close($db);
	}
}
//fetch data golongan dri db
$db = dbase_open('../B/GAJI.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Input THR</title>
	<link rel="stylesheet" type="text/css" href="../src/View.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<h1> INPUT THR</h1>
	<table cellspacing='0' id="myTable">
		<thead>
			<tr>
				<th onclick="sortTable(0)">NIK</th>
				<th onclick="sortTable(1)">Nama</th>
				<th onclick="sortTable(2)">Gaji</th>
				<th onclick="sortTable(3)">Tunjangan Jabatan</th>
				<th onclick="sortTable(4)">THR</th>
			</tr>
		</thead>
		<tbody>

			<?php
			for ($i = 1; $i <= $record_numbers; $i++) { ?>
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i); ?>
					<td>
						<input type="text" name="nik" value=<?php echo $row['NIK']; ?> id=<?php echo "nik" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="gaji" value=<?php echo "'" . $row['GAJI_DASAR'] . "'"; ?> id=<?php echo "gaji" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="tunjangan_jab" value=<?php echo "'" . $row['TUNJ_JAB'] . "'"; ?> id=<?php echo "tunjangan_jab" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="thr" value=<?php echo "'" . $row['THR'] . "'"; ?> id=<?php echo "thr" . $i; ?> disabled>
					</td>
					<td>
						<input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
					</td>
				</tr>
			<?php }
			dbase_close($db); ?>
		</tbody>
	</table>

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
							<label for="nik">NIK</label>
							<input type="text" class="nik" name="nik" placeholder="">
						</div>
						<div class="col-lg-12">
							<label for="nama">NAMA</label>
							<input type="text" class="nama" name="nama" placeholder="">
						</div>
						<div class="col-lg-12">
							<label for="gaji">GAJI</label>
							<input type="text" class="gaji" name="gaji" placeholder="">
						</div>
						<div class="col-lg-12">
							<label for="tunjangan_jab">TUNJANGAN JABATAN</label>
							<input type="text" class="tunjangan_jab" name="tunjangan_jab" placeholder="">
						</div>
						<div class="col-lg-12">
							<label for="kode">THR</label>
							<input type="text" class="thr" name="thr" placeholder="">
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
			$(".modal-body .nik").val(nikValue);
			$(".modal-body .nama").val(namaValue);
			$(".modal-body .gaji").val(gajiValue);
			$(".modal-body .tunjangan_jab").val(tunjangan_jabValue);
			$(".modal-body .thr").val(thrValue);
		});

		function sortTable(n) {
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
						if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
							//if so, mark as a switch and break the loop:
							shouldSwitch = true;
							break;
						}
					} else if (dir == "desc") {
						if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
							//if so, mark as a switch and break the loop:
							shouldSwitch = true;
							break;
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

</body>

</html>