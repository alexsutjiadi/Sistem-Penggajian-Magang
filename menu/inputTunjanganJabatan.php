<?php
//thr
if(isset($_POST['edit'])){
		$nik = strtoupper($_POST['nik']);
		$nama = strtoupper($_POST['nama']);
		$tunj_jab = strtoupper($_POST['tunj_jab']);
		$jabatan = strtoupper($_POST['jabatan']);

		$db = dbase_open('../B/GAJI.DBF', 2);
		if ($db) {
  		$record_numbers = dbase_numrecords($db);
  		for ($i = 1; $i <= $record_numbers; $i++) {
      		$row = dbase_get_record_with_names($db, $i);
      		//echo $row['NAMA'];
      		if($row['NIK']==$nik){
      				unset($row['deleted']);
      				$row['TUNJ_JAB'] = $tunj_jab;
      				$row = array_values($row);
  					dbase_replace_record($db, $row, $i);
      		}
  		}
  		dbase_close($db);
		}
	}

	//fetch data golongan dri db
		$db = dbase_open('../B/GAJI.DBF', 0);
		if($db){
			$record_numbers = dbase_numrecords($db);
		}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Input TUNJANGAN JABATAN</title>
        <link rel="stylesheet" type="text/css" href="../src/View.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
    <h1> INPUT TUNJANGAN JABATAN</h1>
    <table cellspacing='0'>
        <thead>
            <tr>
                <th>NIK</th>
                <th>Jabatan</th>
                <th>Nama</th>
                <th>TUNJANGAN JABATAN</th>
                
            </tr>
        </thead>
        <tbody>
        	
            <?php 
			for ($i = 1; $i <= $record_numbers; $i++) { ?> 
				<tr>
					<?php $row = dbase_get_record_with_names($db, $i); ?>
						<td> 
							<input type="text" name="nik" value=<?php echo $row['NIK']; ?> id=<?php echo "nik".$i; ?> disabled>
						</td>
						<td> 
							<input type="text" name="jabatan" value=<?php echo "'".$row['JABATAN']."'"; ?> id=<?php echo "jabatan".$i; ?> disabled> 
						</td>
						<td> 
							<input type="text" name="nama" value=<?php echo "'".$row['NAMA']."'"; ?> id=<?php echo "nama".$i; ?> disabled> 
						</td>
						<td> 
							<input type="text" name="tunj_jab" value=<?php echo "'".$row['TUNJ_JAB']."'"; ?> id=<?php echo "tunj_jab".$i; ?> disabled> 
						</td>
						
						<td> 
							<input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?> >
						</td>
				</tr>
				<?php } dbase_close($db); ?>
				<tr>
					<th colspan="2">TOTAL TUNJANGAN JABATAN</th>
				</tr>
        </tbody>
    </table>

<div id="mdl-update" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form action="" method="post">
						<div class="modal-header">
							<h5 class="modal-title">Add TUNJANGAN JABATAN</h5>
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
								<label for="jabatan">JABATAN</label>
								<input type="text" class="jabatan" name="jabatan" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="nama">NAMA</label>
								<input type="text" class="nama" name="nama" placeholder="">
							</div>
							<div class="col-lg-12">
								<label for="tunj_jab">TUNGJANGAN JABATAN</label>
								<input type="text" class="tunj_jab" name="tunj_jab" placeholder="">
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
     	var nikValue = $("#nik"+clickId).val();
     	var jabatanValue = $("#jabatan"+clickId).val();
     	var namaValue = $("#nama"+clickId).val();
     	var tunj_jabValue = $("#tunj_jab"+clickId).val();
     	$(".modal-body .nik").val( nikValue );
     	$(".modal-body .jabatan").val( jabatanValue );
     	$(".modal-body .nama").val( namaValue );
     	$(".modal-body .tunj_jab").val( tunj_jabValue );

	});
</script>

</body>
</html>