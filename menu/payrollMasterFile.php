<?php
//edit data master gaji
if (isset($_POST['edit'])) {
	$rowId = $_POST['rowId'];
	$no = $_POST['no'];
	$nama = strtoupper($_POST['nama']);
	$nik = $_POST['nik'];
	$dept = $_POST['dept'];
	$tglLahir = date("Ymd", strtotime($_POST['tglLahir']));
	$alamat = strtoupper($_POST['alamat']);
	$status = $_POST['status'];
	$kelamin = $_POST['kelamin'];
	$golongan = $_POST['golongan'];
	$tanggungan = $_POST['tanggungan'];
	$tglMasuk = date("Ymd", strtotime($_POST['tglMasuk']));
	$aktifFiskal = date("Ymd", strtotime($_POST['aktifFiskal']));
	$npwp = $_POST['npwp'];
	$jamsos = $_POST['jamsos'];
	$gajiDasar = $_POST['gajiDasar'];
	$pangkat = $_POST['pangkat'];
	$premiKesehatan = $_POST['premiKesehatan'];
	$tunjanganKesehatan = $_POST['tunjanganKesehatan'];
	$pilihanBank = $_POST['pilihanBank'];



	$db = dbase_open('../B/GAJI.DBF', 2);
	$db2 = dbase_open('../B/WAKTU_MASUK.DBF', 2);
	if ($db) {
		$row = dbase_get_record_with_names($db, $rowId);
		$row2 = dbase_get_record_with_names($db2, $rowId);
		
		unset($row['deleted']);
		unset($row2['deleted']);
		
		$row['NO_URUT'] = $no; 
		$row['NAMA'] = $nama;
		$row['NIK'] = $nik;
		$row['DEPT'] = $dept;
		$row['TGL_LAHIR'] = $tglLahir;
		$row['ALAMAT'] = $alamat;
		$row['STATUS'] = $status;
		$row['KELAMIN'] = $kelamin;
		$row['GOLONGAN'] = $golongan;
		$row['KELUARGA'] = $tanggungan;
		$row['BLN_AKTIV'] = $aktifFiskal;
		$row['NPWP'] = $npwp;
		$row['JAMSOSFLG'] = $jamsos;
		$row['GAJI_DASAR'] = $gajiDasar;
		$row['PANGKAT'] = $pangkat;
		$row['JPK']=$premiKesehatan;
		$row['TUNJ_KES'] = $tunjanganKesehatan;
		$row['KODE_BANK'] = $pilihanBank;
		$row['KODE_BANK1'] = $pilihanBank;
		$row2['TGL_MASUK'] = $tglMasuk;

		$row = array_values($row);
		$row2 = array_values($row2);
		dbase_replace_record($db, $row, $rowId);
		dbase_replace_record($db2, $row2, $rowId);

	}
	dbase_close($db2);
	dbase_close($db);
}
else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];

	$db = dbase_open('../B/GAJI.DBF', 2);
	$db2 = dbase_open('../B/WAKTU_MASUK.DBF', 2);
	if ($db) {
		dbase_delete_record($db, $idDelete);
		dbase_delete_record($db2, $idDelete);
		dbase_pack($db);
		dbase_pack($db2);
	}
	dbase_close($db);
	dbase_close($db2);
}

//fetch data golongan dri db
$db = dbase_open('../B/GAJI.DBF', 0);
$db2 = dbase_open('../B/WAKTU_MASUK.DBF', 0);
if ($db) {
	$record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../src/View.css">
	<link rel="stylesheet" type="text/css" href="../src/tampilan.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="wrap">
	<div class="header">
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
</div>
</div>
	<div>
		<table width="100%" border="1" id="myTable">
			<tr>
				<th onclick="sortTable(0)">DEPT</th>
				<th onclick="sortTable(1)">NO</th>
				<th onclick="sortTable(2)">NAMA</th>
				<th colspan="3"></th>
			</tr>
			<?php
			for ($i = 1; $i <= $record_numbers; $i++) { ?>
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i);
					$row2 = dbase_get_record_with_names($db2, $i);
					?>
					<td>
						<?php echo $row['DEPT']?>
					</td>
					<td>
						<?php echo $row['NO_URUT']; ?>
					</td>
					<td>
						<?php echo $row['NAMA']; ?>
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
						<input type="hidden" name="dept" value=<?php echo $row['DEPT']; ?> id=<?php echo "dept" . $i; ?> >
						<input type="hidden" name="no" value=<?php echo $row['NO_URUT']; ?> id=<?php echo "no" . $i; ?>>
						<input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?> >
					<input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="DETAIL" name="modal" data-id=<?php echo $i; ?>>
					</td>
					<td>
						<form action="" method="post">
							<input type="hidden" name="idDelete" value=<?php echo $i; ?>>
							<input type="submit" onclick="return isValidForm()" name="delete" class="btnDelete" value="DELETE">
						</form>
					</td>
				</tr>
			<?php }
			dbase_close($db);
			dbase_close($db2); ?>

		</table>
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
								<input type="text" class="no" name="no" placeholder="">
								<input type="hidden" class="rowId" name="rowId">
							</div>
							<div class="col-lg-12">
								<label for="nik">NO. ID</label>
								<input type="text" class="nik" name="nik" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="dept">DEPARTEMEN</label>
								<input type="text" class="dept" name="dept" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="tglLahir">TGL LAHIR</label>
								<input type="text" class="tglLahir" name="tglLahir" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="alamat">ALAMAT</label>
								<input type="text" class="alamat" name="alamat" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="status">STATUS</label>
								<input type="text" class="status" name="status" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="kelamin">JENIS KELAMIN</label>
								<input type="text" class="kelamin" name="kelamin" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="golongan">GOLONGAN</label>
								<input type="text" class="golongan" name="golongan" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="tanggungan">TANGGUNGAN</label>
								<input type="text" class="tanggungan" name="tanggungan" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="tglMasuk">TANGGAL MASUK</label>
								<input type="text" class="tglMasuk" name="tglMasuk" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="aktifFiskal">AKTIF FISKAL</label>
								<input type="text" class="aktifFiskal" name="aktifFiskal" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="npwp">N.P.W.P</label>
								<input type="text" class="npwp" name="npwp" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="jamsos">JAMSOS (Y/T)</label>
								<input type="text" class="jamsos" name="jamsos" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="gajiDasar">GAJI DASAR</label>
								<input type="text" class="gajiDasar" name="gajiDasar" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="pangkat">PANGKAT GOLONGAN</label>
								<input type="text" class="pangkat" name="pangkat" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="premiKesehatan">PREMI KESEHATAN</label>
								<input type="text" class="premiKesehatan" name="premiKesehatan" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="tunjanganKesehatan">TUNJANGAN KESEHATAN</label>
								<input type="text" class="tunjanganKesehatan" name="tunjanganKesehatan" placeholder="">
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