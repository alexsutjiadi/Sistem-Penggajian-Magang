<?php
$db1 = dbase_open('../B/PANGKAT_K1.DBF', 2);
$db2 = dbase_open('../B/PANGKAT_K2.DBF', 2);
$db3 = dbase_open('../B/PANGKAT_K3.DBF', 2);
$n = dbase_numrecords($db1);

for ($i=1; $i < $n ; $i++) {
  $s = dbase_get_record_with_names($db1, $i);
  dbase_add_record($db2, array($s['PANGKAT'],$s['MIN'],$s['MAX']));
  dbase_add_record($db3, array($s['PANGKAT'],$s['MIN'],$s['MAX']));
}
dbase_close($db1);
dbase_close($db2);
dbase_close($db3);

?>