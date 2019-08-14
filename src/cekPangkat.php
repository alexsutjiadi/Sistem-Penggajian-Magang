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

if (isset($_POST['gajiV'])) {
    //call the function or execute the code
    if ($_POST['kotaV']) {
        cekPangkat($_POST['gajiV'], $_POST['kotaV'], $_POST['jamsos']);
    }
}
if (isset($_POST['hitungPPH'])) {
    $dbgaji = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
    $dbtabel = dbase_open($_SESSION['pathKota'] . 'TABEL.DBF', 0);
    $dbptkp = dbase_open($_SESSION['pathKota'] . 'PTKP.DBF', 0);
    $dbpph = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 2);
    $dbpph1 = dbase_open($_SESSION['pathKota'] . 'PPH1.DBF', 0);

    // //clear db pph
    // $npph = dbase_numrecords($dbpph);
    // //echo "npph" . $npph . "<br>";
    // for ($j = 1; $j <= $npph; $j++) {
    //     dbase_delete_record($dbpph, $j);
    // }
    // dbase_pack($dbpph);

    //mulai hitung pph sebanyak data di dbgaji
    $numrecord = dbase_numrecords($dbgaji);
    for ($i = 1; $i <= $numrecord; $i++) {
        $rowgaji = dbase_get_record_with_names($dbgaji, $i);
        $rowtabel = dbase_get_record_with_names($dbtabel, 1);
        //$rowpph = dbase_get_record_with_names($dbpph, $i);
        //$rowpph1 = dbase_get_record_with_names($dbpph1, $i);

        //gaji dasar & biaya jabatan & tht & neto & jamsos
        $gaji_dasar = $rowgaji['GAJI_DASAR'];
        $tunjreg = (int) $rowgaji['TUNJ_REG'];
        $tunjjab = (int) $rowgaji['TUNJ_JAB'];
        $tunjkes = (int) $rowgaji['TUNJ_KES'];
        $jpk = (int) $rowgaji['JPK'];
        $pensiun = $gaji_dasar * 0.01; //1%gajipokok
        if($pensiun>85124){
            $pensiun=85124;
        }

        $biayaJabatan = $gaji_dasar * $rowtabel['JAB'];
        $jamsos = $gaji_dasar * $rowtabel['JAMSS'];
        if ($biayaJabatan > $rowtabel['JAB_MAX']) {
            $biayaJabatan = $rowtabel['JAB_MAX'];
        }
        $jht = ($gaji_dasar * $rowtabel['THT']) / 100; //2% gaji pokok
        $tht = $jht+$pensiun;
        $gaji_net = $gaji_dasar + ($tunjreg + $tunjjab + $tunjkes) - ($biayaJabatan + $tht + $jpk);
        $ygaji_net = $gaji_net * 12;

        //cek ptkp
        $nptkp = dbase_numrecords($dbptkp);
        for ($ii = 1; $ii <= $nptkp; $ii++) {
            $rowptkp = dbase_get_record_with_names($dbptkp, $ii);
            if ($rowptkp['KELUARGA'] == $rowgaji['KELUARGA']) {
                $ptkp = $rowptkp['NILAI'];
                break;
            }
        }

        //pkp
        $pkp = $ygaji_net - ($ptkp * 12);
        if ($pkp < 0) {
            $pkp = 0;
        }

        //cek tarif
        if ($pkp <= $rowtabel['TAB_1']) {
            $tarif = $rowtabel['PERS1'] / 100;
        } else if ($pkp <= $rowtabel['TAB_2']) {
            $tarif = $rowtabel['PERS2'] / 100;
        } else if ($pkp <= $rowtabel['TAB_3']) {
            $tarif = $rowtabel['PERS3'] / 100;
        } else {
            $tarif = $rowtabel['PERS4'] / 100;
        }

        $ypph = $tarif * $pkp;
        $pph = $ypph / 12;

        //posisi bulan
        $bulan = parse_ini_file($_SESSION['pathKota'] . "init.ini");
        $bulan = $bulan['posisi_bulan'];

        //pph thr
        if ($rowgaji['THR'] <= $rowtabel['TAB_1']) {
            $thr = ($rowgaji['THR'] * $rowtabel['PERS1']) / 100;
        } else if ($rowgaji['THR'] <= $rowtabel['TAB_2']) {
            $thr = ($rowgaji['THR'] * $rowtabel['PERS2']) / 100;
        } else {
            $thr = ($rowgaji['THR'] * $rowtabel['PERS3']) / 100;
        }

        //pph bonus
        if ($rowgaji['BONUS'] <= $rowtabel['TAB_1']) {
            $bonus = ($rowgaji['BONUS'] * $rowtabel['PERS1']) / 100;
        } else if ($rowgaji['BONUS'] <= $rowtabel['TAB_2']) {
            $bonus = ($rowgaji['BONUS'] * $rowtabel['PERS2']) / 100;
        } else {
            $bonus = ($rowgaji['BONUS'] * $rowtabel['PERS3']) / 100;
        }

        // echo  "depT:".$rowgaji['DEPT']."pph:".$pph . ", ypph: " . $ypph . ", tarif: " . $tarif . ",pkp: " . $pkp . ",ptkp: " . $ptkp . ",gaji net: " . $gaji_net . ", tht:" . $tht . ", biaya jab: " . $biayaJabatan . "<br>";

        $npph = dbase_numrecords($dbpph);
        $ngaji =dbase_numrecords($dbgaji);
        if ($npph != $ngaji) {
            dbase_add_record($dbpph, array(
                $rowgaji['NO_URUT'],
                $rowgaji['NIK'],
                $rowgaji['DEPT'],
                $rowgaji['TUNJ_JAB'],
                $rowgaji['TUNJ_KES'],
                $ptkp,
                $jamsos,
                $rowgaji['JPK'],
                $biayaJabatan,
                $tht,
                $pkp,
                $pph,
                $rowgaji['THR'],
                $thr,
                $rowgaji['BONUS'],
                $bonus, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $bulan, $rowgaji['AKTIV'], 0
            ));
        } else {
            //update berdasarkan deptID
            for ($n = 1; $n <= $ngaji; $n++) {
                    $rowpph = dbase_get_record_with_names($dbpph, $n);
                    if ($rowpph['NO_URUT'] == $rowgaji['NO_URUT']) {
                        
                    unset($rowpph['deleted']);

                    $rowpph['NO_URUT'] = $rowgaji['NO_URUT'];
                    $rowpph['TUNJ_JAB'] = $rowgaji['TUNJ_JAB'];
                    $rowpph['TUNJ_KES'] = $rowgaji['TUNJ_KES'];
                    $rowpph['PTKP'] = $ptkp;
                    $rowpph['JAMSOS'] = $jamsos;
                    $rowpph['JPK'] = $rowgaji['JPK'];
                    $rowpph['JABAT'] = $biayaJabatan;
                    $rowpph['THT'] = $tht;
                    $rowpph['PKP'] = $pkp;
                    $rowpph['PPH_21'] = $pph;
                    $rowpph['THR'] = $rowgaji['THR'];
                    $rowpph['PPH_THR'] = $thr;
                    $rowpph['BONUS'] = $rowgaji['BONUS'];
                    $rowpph['PPH_BONUS'] = $bonus;
                    $rowpph['YTD_BLN'] = $bulan;
                    $rowpph['AKTIV'] = $rowgaji['AKTIV'];
                    $rowpph = array_values($rowpph);
                    dbase_replace_record($dbpph,$rowpph,$n);
                    break;
                    }
                    
                }
        }
    }
    $ini_array = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $nposisi = $ini_array['posisi_bulan'];
    $ncount = $ini_array['count_bulan'];
    $hitung_pph = $ini_array['hitung_pph'];
    $file = ($_SESSION['pathKota'] . "init.ini");
    //echo $file;
    $myfile = fopen($file, "w") or die("Unable to open file!");
    $txt = "posisi_bulan = " . $nposisi . "\n" . "count_bulan = " . $ncount . "\nhitung_pph = 1";
    fwrite($myfile, $txt);
    fclose($myfile);

    dbase_close($dbgaji);
    dbase_close($dbpph);
    dbase_close($dbpph1);
    dbase_close($dbptkp);
    dbase_close($dbtabel);
    header("Location: ../menu/hitungPPH.php");
    

}
if (isset($_POST['clear'])) {
    $dbpph = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 2);
    $npph = dbase_numrecords($dbpph);
    //echo "npph" . $npph . "<br>";
    for ($j = 1; $j <= $npph; $j++) {
        dbase_delete_record($dbpph, $j);
    }

    $ini_array = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $nposisi = $ini_array['posisi_bulan'];
    $ncount = $ini_array['count_bulan'];
    $hitung_pph = $ini_array['hitung_pph'];
    $file = ($_SESSION['pathKota'] . "init.ini");
    //echo $file;
    $myfile = fopen($file, "w") or die("Unable to open file!");
    $txt = "posisi_bulan = " . $nposisi . "\n" . "count_bulan = " . $ncount . "\nhitung_pph = 0";
    fwrite($myfile, $txt);
    fclose($myfile);

    dbase_pack($dbpph);
    dbase_close($dbpph);
    header("Location: ../menu/hitungPPH.php");
}
