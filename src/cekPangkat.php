<?php
if (!isset($_SESSION)) {
    session_start();
}

function cekPangkat($gaji, $kota, $jamsos = "N")
{
    if ($kota == 'V' || $kota == 'H0' || $kota == 'S') {
        $dbPangkat = dbase_open('../src/golongan/PANGKAT_K1.DBF', 0);
    } else if ($kota == 'R' || $kota == 'B') {
        $dbPangkat = dbase_open('../src/golongan/PANGKAT_K3.DBF', 0);
    } else {
        $dbPangkat = dbase_open('../src/golongan/PANGKAT_K2.DBF', 0);
    }
    $dbPangkat2 = dbase_open('../src/golongan/Pangkat_4BPLUS.DBF', 0);

    $n = dbase_numrecords($dbPangkat);
    for ($i = 1; $i <= $n; $i++) {
        $row = dbase_get_record_with_names($dbPangkat, $i);

        if ($i == $n && $gaji > $row['MAX']) {
            $n2 = dbase_numrecords($dbPangkat2);
            for ($i2 = 1; $i < $n2; $i2++) {
                $row2 = dbase_get_record_with_names($dbPangkat2, $i2);
                if ($i2 == $n2 && $gaji > $row2['MAX']) {
                    $pangkat = "X";
                    break;
                }
                if ($gaji >= $row2['MIN'] && $gaji <= $row2['MAX']) {
                    $pangkat = $row2['PANGKAT'];
                    break;
                }
            }
        }
        if ($gaji >= $row['MIN'] && $gaji <= $row['MAX']) {
            $pangkat = $row['PANGKAT'];
            break;
        }
    }
    dbase_close($dbPangkat);
    dbase_close($dbPangkat2);

    $premiKesehatan = 0;
    $jamsostek = (0.05 * $gaji);
    if ($jamsostek > 400000) {
        $jamsostek = 400000;
    }
    if ($pangkat == "4A" || $pangkat == "4B" || $pangkat == "3A" || $pangkat == "3B") {
        $tunjanganDariPerusahaan = (0.1 * $gaji);
    } else if ($pangkat == "2A" || $pangkat == "2B" || $pangkat == "1A" || $pangkat == "1B" || $pangkat == "TM") {
        $tunjanganDariPerusahaan = (0.12 * $gaji);
    } else {
        $tunjanganDariPerusahaan = (0.08 * $gaji);
    }
    if (strtoupper($jamsos) == 'Y') {
        $premiKesehatan = (0.2 * $jamsostek);
        $tunjanganKesehatan = $tunjanganDariPerusahaan - ($jamsostek - $premiKesehatan) - $premiKesehatan;
    } else {
        $premiKesehatan = 0;
        $tunjanganKesehatan = $tunjanganDariPerusahaan - ($jamsostek - $premiKesehatan) - $premiKesehatan;
    }



    $returnArray = array('pangkat' => $pangkat, 'premi' => $premiKesehatan, 'tunjKes' => $tunjanganKesehatan);

    echo json_encode($returnArray);
}

if ($_POST['gajiV']) {
    //call the function or execute the code
    if ($_POST['kotaV']) {
        cekPangkat($_POST['gajiV'], $_POST['kotaV'], $_POST['jamsos']);
    }
}
