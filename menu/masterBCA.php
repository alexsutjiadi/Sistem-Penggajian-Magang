<?php

if (isset($_POST['edit'])) {
	$dept = strtoupper($_POST['dept']);
	$nama = strtoupper($_POST['nama']);
	$rekening = $_POST['rekening'];
	$seq = $_POST['seq'];
	$pemilik = $_POST['pemilik'];

	$db = dbase_open('../B/BCA.DBF', 2);
	if ($db) {
		$record_numbers = dbase_numrecords($db);
		for ($i = 1; $i <= $record_numbers; $i++) {
			$row = dbase_get_record_with_names($db, $i);

			if ($row['NO_INDUK'] == $dept) {

				unset($row['deleted']);
				$row['NO_REK'] = $rekening;
				$row['SEQ'] = $seq;
				$row['PEMILIK'] = $pemilik;
				$row = array_values($row);
				dbase_replace_record($db, $row, $i);
			}
		}
		dbase_close($db);
	}
}

else if (isset($_POST['add'])) {
	$dept = strtoupper($_POST['dept']);
	$nama = strtoupper($_POST['nama']);
	$rekening = $_POST['rekening'];
	$seq = $_POST['seq'];
	$pemilik = $_POST['pemilik'];

	$db = dbase_open('../B/BCA.DBF', 2);
	if ($db) {
		dbase_add_record($db, array($dept, $nama, $rekening, $seq, $pemilik, 0, 0));
	}
	dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];


	$db = dbase_open('../B/BCA.DBF', 2);
	if ($db) {
		dbase_delete_record($db, $idDelete);
		dbase_pack($db);
	}
	dbase_close($db);
}


$db = dbase_open('../B/BCA.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Master BCA</title>
	<link rel="stylesheet" type="text/css" href="../src/View.css">
    <link rel="stylesheet" type="text/css" href="../src/tampilan1.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div>
		<div class="wrap">
			<div class="header">
			<header>
				<img src="../img/back.png" align="right" height="40" width="40" margin-top="0" onclick="goBack()" />
  				<h1>MASTER BCA</h1>
			</header>
			</div>
		<table border="1" id="myTable">
			<tr>
				<th onclick="sortTable(0)">NO. DEPT</th>
				<th onclick="sortTable(1)">NAMA</th>
				<th onclick="sortTable(2)">REKENING</th>
				<th colspan="2"></th>
				<?php
				date_default_timezone_set("Asia/Jakarta");
				echo "Last Modified " . date(" H:i:s - d M Y", filemtime("../B/BCA.DBF"))
				?>
			</tr>
			<?php
			for ($i = 1; $i <= $record_numbers; $i++) { ?>
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i); ?>
					<td>
						<input type="text" name="dept" value=<?php echo $row['NO_INDUK']; ?> id=<?php echo "dept" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" value="<?php echo substr($row['NO_REK'], 0, 4) . " - " . substr($row['NO_REK'], 4, 3) . " - " . substr($row['NO_REK'], 7, 3); ?>" disabled>
						<input type="hidden" name="rekening" value=<?php echo $row['NO_REK'] ?> id=<?php echo "rekening" . $i; ?>>
						<input type="hidden" name="seq" value=<?php echo $row['SEQ']; ?> id=<?php echo "seq" . $i; ?>>
						<input type="hidden" name="pemilik" value=<?php echo $row['PEMILIK']; ?> id=<?php echo "pemilik" . $i; ?>>
					</td>
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
				<th colspan="5">
					<center><input type="submit" name="add" class="btnAdd" data-toggle="modal" data-target="#mdl-add" value="ADD"></center>
					</form>
				</th>
			</tr>


		</table>
		<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">PEMELIHARAAN DATA B.C.A</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="" method="POST">
						<div class="modal-body">
							<div class="col-lg-12">
								<label for="dept">NO. DEPT</label>
								<input type="text" class="dept" name="dept" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="rekeing">NO. REKENING</label>
								<input type="text" class="rekening" name="rekening" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="seq">SEQ. NO.</label>
								<input type="text" class="seq" name="seq" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="pemilik">ATAS NAMA</label>
								<input type="text" class="pemilik" name="pemilik" placeholder="">
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

		<div id="mdl-add" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">TAMBAH DATA B.C.A</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="" method="post">
						<div class="Add">
							<div class="col-lg-12">
								<label for="dept">NO. DEPT</label>
								<input type="text" class="dept" name="dept" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="rekeing">NO. REKENING</label>
								<input type="text" class="rekening" name="rekening" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="seq">SEQ. NO.</label>
								<input type="text" class="seq" name="seq" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="pemilik">ATAS NAMA</label>
								<input type="text" class="pemilik" name="pemilik" placeholder="">
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" val="" id="add" name="add" value="ADD">
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
			var rekValue = $("#rekening" + clickId).val();
			var seqValue = $("#seq" + clickId).val();
			var pemilikValue = $("#pemilik" + clickId).val();

			$(".modal-body .dept").val(deptValue);
			$(".modal-body .nama").val(namaValue);
			$(".modal-body .rekening").val(rekValue);
			$(".modal-body .seq").val(seqValue);
			$(".modal-body .pemilik").val(pemilikValue);
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
	<script>
    function goBack() {
        window.history.back();
    }
	</script>
</body>

</html>