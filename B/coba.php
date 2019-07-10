<?php
$db = dbase_open('WAKTU_MASUK.DBF', 2);
$n = dbase_numrecords($db);
dbase_delete_record($db, $n);
?>