<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['LAMPUNG'])) {
    $_SESSION['pathKota'] = "../kota/B/";
}
if (isset($_POST['SURABAYA'])) {
    $_SESSION['pathKota'] = "../kota/S/";
}
if (isset($_POST['PUSAT'])) {
    $_SESSION['pathKota'] = "../kota/H0/";
}
if (isset($_POST['JAKARTA'])) {
    $_SESSION['pathKota'] = "../kota/V/";
}
if (isset($_POST['MAKASSAR'])) {
    $_SESSION['pathKota'] = "../kota/U/";
}
if (isset($_POST['PALEMBANG'])) {
    $_SESSION['pathKota'] = "../kota/W/";
}
if (isset($_POST['SEMARANG'])) {
    $_SESSION['pathKota'] = "../kota/R/";
}
if (isset($_POST['MEDAN'])) {
    $_SESSION['pathKota'] = "../kota/Y/";
}
?>
<span><?php echo $_SESSION['pathKota'] ?></span>
<form action="" method="post">
    <input type="submit" value="LAMPUNG" name="LAMPUNG">
    <input type="submit" value="PUSAT" name="PUSAT">
    <input type="submit" value="SURABAYA" name="SURABAYA">
    <input type="submit" value="JAKARTA" name="JAKARTA">
    <input type="submit" value="MAKASSAR" name="MAKASSAR">
    <input type="submit" value="SEMARANG" name="SEMARANG">
    <input type="submit" value="MEDAN" name="MEDAN">
    <input type="submit" value="PALEMBANG" name="PALEMBANG">
</form>