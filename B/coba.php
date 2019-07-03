<?php

$db = dbase_open("GAJI.DBF", 0);
$db2 = dbase_open("WAKTU_MASUK.DBF", 2);
$num_record = dbase_numrecords($db);
$num_record2 = dbase_numrecords($db2);

for ($i=1; $i <=$num_record; $i++) { 
    $row = dbase_get_record_with_names($db2, $i);
    echo $row['TGL_MASUK'];
    
}
dbase_close($db);
dbase_close($db2);







?>