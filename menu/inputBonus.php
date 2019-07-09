<?php
//thr
if (isset($_POST['edit'])) {
	$bonus = $_POST['bonus'];
	$rowId = $_POST['rowId'];
	$pphBonus = $_POST['pphBonus'];

	$db = dbase_open('../B/GAJI.DBF', 2);
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
//fetch data golongan dri db
$db = dbase_open('../B/GAJI.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Input BONUS</title>
	<link rel="stylesheet" type="text/css" href="../src/tampilan.css">
	<link rel="stylesheet" type="text/css" href="../src/View.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<header> <!--Section HEADER-->
        <img src="../img/rtn.jpg" />
      <div id='cssmenu'>
      <ul>
        <li class='has-sub '><a href='#'><span>Maintain Input MASTER</span></a>
            <ul>
              <li><a href='/penggajianMagang/menu/inputMaster.php'><span>Input Master</span></a></li>
              <li><a href='/penggajianMagang/menu/payrollMasterFile.php'><span>Manage Master Gaji</span></a></li>
              <li><a href='/penggajianMagang/menu/alamatDanNpwp.php'><span>Alamat & N.P.W.P</span></a></li>
              <li><a href='/penggajianMagang/menu/masterBCA.php'><span>Master B.C.A</span></a></li>
              <li><a href='/penggajianMagang/menu/showNamaGolongan.php'><span>Golongan</span></a></li>
              <li><a href='/penggajianMagang/menu/inputGajiBaru.php'><span>Gaji Baru</span></a></li>
              <li><a href='/penggajianMagang/menu/inputTunjanganJabatan.php'><span>Input Data Lain</span></a></li>
            </ul>
        </li>
        <li class='has-sub '><a href='#'><span>THR/Bonus</span></a>
          <ul>
              <li><a href='/penggajianMagang/menu/inputTHR.php'><span>Input THR</span></a></li>
              <li><a href='/penggajianMagang/menu/inputBonus.php'><span>Input Bonus</span></a></li>
            </ul>
        </li>
        <li class='has-sub '><a href='#'><span>Manage Pangkat</span></a>
          <ul>
              <li><a href='/penggajianMagang/menu/masterPangkatK1.php'><span>K1 </span></a></li>
              <li><a href='/penggajianMagang/menu/masterPangkatK2.php'><span>K2 </span></a></li>
              <li><a href='/penggajianMagang/menu/masterPangkatK3.php'><span>K3 </span></a></li>
            </ul>
        </li>
        <li class='active'><a href='index.html'><span>Home</span></a></li>
        <li><a href='#'><span>Contact</span></a></li>
      </ul>
      </div>
    </header>
	<table width="100%" cellspacing='0' id="myTable">

		<tr>
			<th onclick="sortTable(0.'T')">DEPT</th>
			<th onclick="sortTable(1,'T')">Nama</th>
			<th onclick="sortTable(2,'N')">Gaji</th>
			<th onclick="sortTable(3,'N')">BONUS</th>
			<th></th>
		</tr>

		<?php
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
					<?php echo $row['GAJI_DASAR'] ?>
				</td>
				<td>
					<?php echo $row['BONUS'] ?>
				</td>
				<td>
					<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
					<input type="hidden" name="bonus" value=<?php echo "'" . $row['BONUS'] . "'";
															$totalBonus += $row['BONUS'] ?> id=<?php echo "bonus" . $i; ?>>
					<input type="hidden" name="gaji" value=<?php echo "'" . $row['GAJI_DASAR'] . "'"; ?> id=<?php echo "gaji" . $i; ?>>
					<input type="hidden" name="nik" value=<?php echo $row['DEPT']; ?> id=<?php echo "nik" . $i; ?>>
					<input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
				</td>
			</tr>
		<?php }
		dbase_close($db); ?>

	</table>
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

</body>

</html>