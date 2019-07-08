<?php
function cekPangkat($gaji, $kota)
{
    if ($kota == 'V' || $kota == 'H0' || $kota == 'S') {
        $dbPangkat = dbase_open('../B/PANGKAT_K1.DBF', 0);
    } else if ($kota == 'R' || $kota == 'B') {
        $dbPangkat = dbase_open('../B/PANGKAT_K3.DBF', 0);
    } else {
        $dbPangkat = dbase_open('../B/PANGKAT_K2.DBF', 0);
    }

    $n = dbase_numrecords($dbPangkat);
    for ($i = 1; $i <= $n; $i++) {
        $row = dbase_get_record_with_names($dbPangkat, $i);

        if ($i == $n && $gaji > $row['MAX']) {
            $pangkat = "X";
        }
        if ($gaji >= $row['MIN'] && $gaji <= $row['MAX']) {
            $pangkat = $row['PANGKAT'];
            break;
        }
    }
    dbase_close($dbPangkat);
    echo $pangkat;
}

if ($_POST['gajiV']) {
    //call the function or execute the code
    if($_POST['kotaV']){
        cekPangkat($_POST['gajiV'],$_POST['kotaV']);
    }
}
