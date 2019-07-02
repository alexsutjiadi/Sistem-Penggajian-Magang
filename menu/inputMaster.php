<?php
if (isset($_POST['addNew'])) {
	$nama = $_POST['nama'];
	$nik = $_POST['nik'];
	$dept = $_POST['dept'];
	$alamat = $_POST['alamat'];
	$npwp = $_POST['npwp'];
	$jabatan = $_POST['jabatan'];
	$kotaLahir =$_POST['kotaLahir'];
	$tglLahir = $_POST['tglLahir'];
	$pangkat = $_POST['pangkat'];
	$status = $_POST['status'];
	$jenisKelamin = $_POST['jenisKelamin'];
	$keluarga =$_POST['keluarga'];
	$kodeBank =$_POST['kodeBank'];
	$gajiDasar = $_POST['gaji'];
    $kotaAsal = $_POST['kotaAsal'];
	$tglAktif = $_POST['tglAktif'];

    //pangkat
    if ($kotaAsal == 'B') {
        $db = dbase_open('../B/GAJI.DBF', 2);} //buka db sesuai kota aktif/asal.
    // }else if (){

    // }else if(){

    // }else if(){

    // }else if(){

    // }else if(){
        
    // }else if(){
        
    // }else if(){
        
    // }


    $numberRecord = dbase_numrecords($db);
    //get no urut
    $no = $numberRecord+1;
    if($no<100){
        $no = "0".$no;
    }

    //get no urut di deptnya
    $noDept = 1;
    for ($i=1; $i <= $numberRecord; $i++) { 
        $row = dbase_get_record_with_names($db, $i);
        $getKodeDept = substr($row['DEPT'], 0, 1);
        if ($getKodeDept == $dept) {
            $noDept = $noDept+1;
        }
    }
    //bkin dept code
    if ($noDept<10) {
        $dept = $dept."0-0".$noDept;
    }else{
        $dept = $dept."0-".$noDept;
    }

    //pangkat
        $pangkat = "";
        //kode kota / cluster
        if ($kotaAsal=='V' || $kotaAsal=='H0' || $kotaAsal=='S') {
            $pangkat=$pangkat."K1";
        }else if ($kotaAsal=='R' || $kotaAsal=='B') {
            $pangkat=$pangkat."K3";
        }else{
            $pangkat=$pangkat."K2";
        }

        //kode gaji
        if ($gajiDasar>0 && $gajiDasar <= 500000) {
            $pangkat=$pangkat."-C4";
        }else if ($gajiDasar>500000 && $gajiDasar <= 1000000) {
            $pangkat=$pangkat."-C3";
        }else if ($gajiDasar>1000000 && $gajiDasar <= 2000000) {
            $pangkat=$pangkat."-C2";
        }else if ($gajiDasar>2000000 && $gajiDasar <= 3000000) {
            $pangkat=$pangkat."-C1";
        }else if ($gajiDasar>3000000 && $gajiDasar <= 5000000) {
            $pangkat="4B";
        }else if ($gajiDasar>5000000 && $gajiDasar <= 7000000) {
            $pangkat="4A";
        }else if ($gajiDasar>7000000 && $gajiDasar <= 10000000) {
            $pangkat="3B";
        }else if ($gajiDasar>10000000 && $gajiDasar <= 12000000) {
            $pangkat="3A";
        }else if ($gajiDasar>12000000 && $gajiDasar <= 14000000) {
            $pangkat="2B";
        }else if ($gajiDasar>14000000 && $gajiDasar <= 16000000) {
            $pangkat="2A";
        }else if ($gajiDasar>18000000 && $gajiDasar <= 20000000) {
            $pangkat="1B";
        }else if ($gajiDasar>20000000 && $gajiDasar <= 22000000) {
            $pangkat="1B";
        }else if ($gajiDasar>22000000 && $gajiDasar <= 20000000) {
            $pangkat="TM";
        }

    //input ke db
    //no urut, nik, deptcode, nama, alamat,npwp, jabatan, noktp, kotalahir, tgl lahir, pangkat, status, kelamin, keluarga, kode bank, kode bank, gajidasar, 0 0 0 0 0 0 0 0 0 0 1 Y Y, bln aktiv 0
    dbase_add_record($db, array($no,$nik,$dept,$nama,$alamat,$npwp,$jabatan,$nik,$kotaLahir,$tglLahir,$pangkat,$status,$jenisKelamin,$keluarga,$kodeBank,$kodeBank,$gajiDasar,0,0,0,0,0,0,0,0,0,0,1,'Y','Y',$tglAktif,""));
    dbase_close($db);
    


}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Input</title>
</head>
<body>
    <font face="Berlin Sans FB" >
        <form action="" method="post">
        <fieldset>
        <legend>Input New</legend>
        <p>
            <label>Nama:</label>
            <input type="text" name="nama" placeholder="Nama..." />
        </p>
        <p>
            <label>NIK:</label>
            <input type="text" name="nik" placeholder="NIK..." />
        </p>
        <p>
            <label>Dept:</label>
            
            <select name="dept">
                <?php 
                    $db = dbase_open('../B/GOLONGAN.DBF', 0);
                    $nRecord = dbase_numrecords($db);
                    for ($i=0; $i <=$nRecord ; $i++) { 
                    $row = dbase_get_record_with_names($db, $i) 
                ?>
                <option value=<?php echo "'".$row['KODE']."'" ?> ><?php echo $row['NAMA'] ?> </option>
                <?php } dbase_close($db); ?>
            </select>
            
        </p>
        <p>
            <label>Alamat:</label>
            <input type="text" name="alamat" placeholder="Alamat..." />
        </p>
        <p>
            <label>NPWP:</label>
            <input type="string" name="npwp" placeholder="NPWP..." />
        </p>
        <p>
            <label>Jabatan:</label>
            <select name="jabatan" >
                <option value="Ketua">Ketua</option>
                <option value="Anggota">Anggota</option>
            </select>
        </p>
        <p>
            <label>Kota Lahir:</label>
            <input type="text" name="kotaLahir" placeholder="Kota Lahir..." />
        </p>
        <p>
            <label>Tgl Lahir:</label>
            <input type="date" name="tglLahir" />
        </p>
        <p>
            <label>Pangkat:</label>
            <input type="text" name="pangkat" placeholder="Pangkat..." />
        </p>
        <p>
            <label>Status:</label>
            <input type="text" name="status" placeholder="Status..." />
        </p>
        <p>
            <label>Jenis Kelamin:</label>
            <select name="jenisKelamin">
                <option value="Pria">Pria</option>
                <option value="Wanita">Wanita</option>
            </select>
        </p>
        <p>
            <label>Keluarga:</label>
            <input type="text" name="keluarga" placeholder="Keluarga..." />
        </p>
        <p>
            <label>Kode Bank:</label>
            <input type="text" name="kodeBank" placeholder="KodeBank..." />
        </p>
        <p>
            <label>Gaji Dasar:</label>
            <input type="text" name="gaji" placeholder="Rp..." />
        </p>
        <p>
            <label>Kota:</label>
            <select name="kotaAsal">
                <option value="V">JAKARTA (V)</option>
                <option value="H0">PUSAT (H0)</option>
                <option value="S">SURABAYA (S)</option>
                <option value="R">SEMARANG (R)</option>
                <option value="B">LAMPUNG (B)</option>
                <option value="Y">MEDAN (Y)</option>
                <option value="U">MAKASSAR (U)</option>
                <option value="W">PALEMBANG (W)</option>
            </select>
        </p>
        <p>
            <label>Tgl Aktif:</label>
            <input type="date" name="tglAktif" />
        </p>

        <p>
            <input type="submit" name="addNew" value="ADD NEW" />
        </p>
        </fieldset>
        </form>
    </font>
</body>
</html>