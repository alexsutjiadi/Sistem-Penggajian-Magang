<?php
//edit data golongan
if(isset($_POST['edit'])){
		$kode = strtoupper($_POST['kode']);
		$nama = strtoupper($_POST['nama']);
		$kodeLama = strtoupper($_POST['kodeLama']);

		$db = dbase_open('../B/GOLONGAN.DBF', 2);
		if ($db) {
  		$record_numbers = dbase_numrecords($db);
  		for ($i = 1; $i <= $record_numbers; $i++) {
      		$row = dbase_get_record_with_names($db, $i);
      		//echo $row['NAMA'];
      		if($row['KODE']==$kodeLama){
				unset($row['deleted']);
      			$row['KODE']==$kode;
      			$row['NAMA'] = $nama;
      			$row = array_values($row);
  				dbase_replace_record($db, $row, $i);
      		}
  		}
  		dbase_close($db);
		}
	}
	//add data golongan
	else if (isset($_POST['add'])) {
		$kode = strtoupper($_POST['addKode']);
		$nama = strtoupper($_POST['addNama']);

		$db = dbase_open('../B/GOLONGAN.DBF', 2);
		if ($db) {
  			dbase_add_record($db, array($kode,$nama));
  		}
  		dbase_close($db);
	}
	else if (isset($_POST['delete']) == 1) {
		$idDelete = $_POST['idDelete'];
		//echo "string";
		
		$db = dbase_open('../B/GOLONGAN.DBF', 2);
		if($db){
			dbase_delete_record($db, $idDelete);
			dbase_pack($db);
		}
		dbase_close($db);
	}

	//fetch data golongan dri db
		$db = dbase_open('../B/GOLONGAN.DBF', 0);
		if($db){
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
		<th>KODE</th>
		<th>NAMA GOLONGAN</th>
		<th colspan="2"></th>
	</tr>
	<?php 
	for ($i = 1; $i <= $record_numbers; $i++) { ?> 
	<tr>
		<?php $row = dbase_get_record_with_names($db, $i); ?>
		<td> 
			<input type="text" name="kode" value=<?php echo $row['KODE']; ?> id=<?php echo "kode".$i; ?> disabled>
		</td>
		<td> 
			<input type="text" name="nama" value=<?php echo "'".$row['NAMA']."'"; ?> id=<?php echo "nama".$i; ?> disabled> </td>
		<td> <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?> >
		</td>
		<td>
			<form action="" method="post">
			<input type="hidden" name="idDelete" value= <?php echo $i; ?> >
			<input type="submit" onclick="return isValidForm()" name="delete" class="btnDelete" value="DELETE">
			</form>
		</td>
	</tr>
	<?php } dbase_close($db); ?>
	<tr>
		<td><form action="" method="POST"><input type="text" name="addKode"></td>
		<td><input type="text" name="addNama"></td>
		<td colspan="2"><center><input type="submit" name="add" value="ADD"></center></form></td>
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
								<input type="hidden" name="kodeLama" class="kodeLama" value="">
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
$(document).on("click", ".btnUpdate", function () {
     var clickId = $(this).data('id');
     var kodeValue = $("#kode"+clickId).val();
     var namaValue = $("#nama"+clickId).val();
     $(".modal-body .kode").val( kodeValue );
     $(".modal-body .nama").val( namaValue );
     $(".modal-body .kodeLama").val( kodeValue );
});
function isValidForm() {
	var pilihan = confirm("hapus");;
	if(pilihan){
		return true;
	}else{
		return false;
	}
}

</script>
</body>
</html>
