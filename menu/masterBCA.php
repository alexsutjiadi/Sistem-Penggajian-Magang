<?php
//edit data golongan
if(isset($_POST['edit'])){
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
      		//echo $row['NAMA'];
      		if($row['NO_INDUK']==$dept){
      			//echo "masuk";
				unset($row['deleted']);
      			$row['NO_REK']=$rekening;
      			$row['SEQ'] = $seq;
      			$row['PEMILIK'] = $pemilik;
      			$row = array_values($row);
  				dbase_replace_record($db, $row, $i);
      		}
  		}
  		dbase_close($db);
		}
	}
	//add data golongan
	else if (isset($_POST['add'])) {
		$dept = strtoupper($_POST['dept']);
		$nama = strtoupper($_POST['nama']);
		$rekening = $_POST['rekening'];
		$seq = $_POST['seq'];
		$pemilik = $_POST['pemilik'];

		$db = dbase_open('../B/BCA.DBF', 2);
		if ($db) {
  			dbase_add_record($db, array($dept,$nama,$rekening,$seq,$pemilik,0,0));
  		}
  		dbase_close($db);
	}
	else if (isset($_POST['delete']) == 1) {
		$idDelete = $_POST['idDelete'];
		//echo "string";
		
		$db = dbase_open('../B/BCA.DBF', 2);
		if($db){
			dbase_delete_record($db, $idDelete);
			dbase_pack($db);
		}
		dbase_close($db);
	}

	//fetch data golongan dri db
		$db = dbase_open('../B/BCA.DBF', 0);
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
		<th>NO. DEPT</th>
		<th>NAMA</th>
		<th>REKENING</th>
		<th colspan="2"></th>
	</tr>
	<?php 
	for ($i = 1; $i <= $record_numbers; $i++) { ?> 
	<tr>
		<?php $row = dbase_get_record_with_names($db, $i); ?>
		<td> 
			<input type="text" name="dept" value=<?php echo $row['NO_INDUK']; ?> id=<?php echo "dept".$i; ?> disabled>
		</td>
		<td> 
			<input type="text" name="nama" value=<?php echo "'".$row['NAMA']."'"; ?> id=<?php echo "nama".$i; ?> disabled> 
		</td>
		<td> 
			<input type="text" name="rekening" value=<?php echo $row['NO_REK']; ?> id=<?php echo "rek".$i; ?> disabled>
			<input type="hidden" name="seq" value= <?php echo $row['SEQ']; ?> id=<?php echo "seq".$i; ?> >
			<input type="hidden" name="pemilik" value= <?php echo $row['PEMILIK']; ?> id=<?php echo "pemilik".$i; ?> >
		</td>
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
		<td colspan="5"><center><input type="submit" name="add" class="btnAdd" data-toggle="modal" data-target="#mdl-add" value="ADD"></center></form></td>
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
								<p type="text" class="dept" name="dept" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="" >
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
								<input type="text" class="dept" name="dept" placeholder="" >
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="" >
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
$(document).on("click", ".btnUpdate", function () {
     var clickId = $(this).data('id');
     var deptValue = $("#dept"+clickId).val();
     var namaValue = $("#nama"+clickId).val();
     var rekValue = $("#rek"+clickId).val();
     var seqValue = $("#seq"+clickId).val();
     var pemilikValue = $("#pemilik"+clickId).val();
     
     $(".modal-body .dept").val( deptValue );
     $(".modal-body .nama").val( namaValue );
     $(".modal-body .rekening").val( rekValue );
     $(".modal-body .seq").val( seqValue );
     $(".modal-body .pemilik").val( pemilikValue );
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
