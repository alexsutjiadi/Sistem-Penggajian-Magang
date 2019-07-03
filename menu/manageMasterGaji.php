<?php
//edit data golongan
if (isset($_POST['edit'])) {
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
		$record_numbers = dbase_numrecords($db);
		for ($i = 1; $i <= $record_numbers; $i++) {
			$row = dbase_get_record_with_names($db, $i);
			$row2 = dbase_get_record_with_names($db2, $i);
			//echo $row['NAMA'];
			if ($row['NIK'] == $nik) {
				//echo "masuk";
				unset($row['deleted']);
				$no = $_POST['no'];
				$row['NAMA'] = $nama;
				$row['NIK'] = $nik;
				$row['DEPT'] = $dept;
				$row['TGL_LAHIR'] = $tglLahir;
				$row['ALAMAT'] = $alamat;
				$row['STATUS'] = $status;
				$row['KELAMIN'] = $kelamin;
				$row['GOLONGAN'] = $golongan;
				$row['KELUARGA'] = $tanggungan;
				$row2['TGL_MASUK'] = $tglMasuk;
				$row['BLN_AKTIV'] = $aktifFiskal;
				$row['NPWP'] = $npwp;
				$row['JAMSOSFLG'] = $jamsos;
				$row['GAJI_DASAR'] = $gajiDasar;
				$row['PANGKAT'] = $pangkat;
				//$row['PREMI']=$premiKesehatan;
				$row['TUNJ_KES'] = $tunjanganKesehatan;
				$row['KODE_BANK'] = $pilihanBank;
				$row['KODE_BANK1'] = $pilihanBank;
				$row = array_values($row);
				dbase_replace_record($db, $row, $i);
			}
		}
		dbase_close($db2);
		dbase_close($db);
	}
}
//add data golongan
// else if (isset($_POST['add'])) {
// 	$dept = strtoupper($_POST['dept']);
// 	$nama = strtoupper($_POST['nama']);
// 	$rekening = $_POST['rekening'];
// 	$seq = $_POST['seq'];
// 	$pemilik = $_POST['pemilik'];

// 	$db = dbase_open('../B/GAJI.DBF', 2);
// 	if ($db) {
//  			dbase_add_record($db, array($dept,$nama,$rekening,$seq,$pemilik,0,0));
//  		}
//  		dbase_close($db);
// }
else if (isset($_POST['delete']) == 1) {
	$idDelete = $_POST['idDelete'];
	//echo "string";

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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div>
		<table border="1">
			<tr>
				<th>DEPT</th>
				<th>NO</th>
				<th>NAMA</th>
				<th colspan="3"></th>
			</tr>
			<?php
			for ($i = 1; $i <= $record_numbers; $i++) { ?>
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i);
					$row2 = dbase_get_record_with_names($db2, $i);
					?>
					<td>
						<input type="text" name="dept" value=<?php echo $row['DEPT']; ?> id=<?php echo "dept" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="no" value=<?php echo $row['NO_URUT']; ?> id=<?php echo "no" . $i; ?> disabled>
					</td>
					<td>
						<input type="text" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?> disabled>
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
						<input type="hidden" name="premiKesehatan" value=<?php echo "0"; ?> id=<?php echo "premiKesehatan" . $i; ?>>
						<input type="hidden" name="tunjanganKesehatan" value=<?php echo $row['TUNJ_KES']; ?> id=<?php echo "tunjanganKesehatan" . $i; ?>>
						<input type="hidden" name="pilihanBank" value=<?php echo $row['KODE_BANK']; ?> id=<?php echo "pilihanBank" . $i; ?>>

					</td>
					<td> <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="DETAIL" name="modal" data-id=<?php echo $i; ?>>
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
			<!-- <tr>
				<td colspan="5">
					<center><input type="submit" name="add" class="btnAdd" data-toggle="modal" data-target="#mdl-add" value="ADD"></center>
					</form>
				</td>
			</tr> -->


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
							<div class="col-lg-12">
								<label for="bruto">BRUTO</label>
								<input type="text" class="bruto" name="bruto" placeholder="">
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
			//var bruto = gajiDasar+tunjanganKesehatan+premiKesehatan;

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
			$(".modal-body .bruto").val(bruto);

		});

		function isValidForm() {
			var pilihan = confirm("hapus");;
			if (pilihan) {
				return true;
			} else {
				return false;
			}
		}
	</script>
</body>
</html>