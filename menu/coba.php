<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['pathKota'])) {
	header("Location: ../pilihKota.php");
}

$dept = "A0-01";
echo (int)substr($dept,3,2) + 1;

