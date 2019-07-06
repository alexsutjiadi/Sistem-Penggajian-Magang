<?php
//edit data golongan
if (isset($_POST['edit'])) {
	$kode = strtoupper($_POST['kode']);
	$nama = strtoupper($_POST['nama']);
	$rowId = $_POST['rowId'];

	$db = dbase_open('../B/GOLONGAN.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);

		$row = dbase_get_record_with_names($db, $rowId);
		//echo $row['NAMA'];

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

	$db = dbase_open('../B/GOLONGAN.DBF', 2);
	if ($db) {
		dbase_add_record($db, array($kode, $nama));
	}
	dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];
	//echo "string";

	$db = dbase_open('../B/GOLONGAN.DBF', 2);
	if ($db) {
		dbase_delete_record($db, $idDelete);
		dbase_pack($db);
	}
	dbase_close($db);
}

//fetch data golongan dri db
$db = dbase_open('../B/GOLONGAN.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div>
		<table border="1" id="myTable">
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
						<input type="text" name="kode" value=<?php echo $row['KODE']; ?> id=<?php echo "kode" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?> disabled> </td>
					<td> <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
					</td>
					<td>
						<form action="" method="post">
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
</body>

</html>