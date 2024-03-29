<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}

include "../src/main.php";

if (isset($_POST['month'])) {
    $ini_array = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $nposisi = $ini_array['posisi_bulan'];
    $ncount = $ini_array['count_bulan'];
    $hitung_pph = $ini_array['hitung_pph'];

    if ($hitung_pph == 1) {
        $dbpph = dbase_open($_SESSION['pathKota'] . "PPH.DBF", 2);
        $dbgaji = dbase_open($_SESSION['pathKota'] . "GAJI.DBF", 2);
        $dbwaktu = dbase_open($_SESSION['pathKota'] . "WAKTU_MASUK.DBF", 2);
        $dbnrekap = dbase_open($_SESSION['pathKota'] . "NREKAP.DBF", 2);

        //pindahin data ke nrekap.dbf
        //gaji
        $ngaji = dbase_numrecords($dbgaji);
        $bln_gaji = 'GAJI' . $nposisi;
        for ($i = 1; $i <= $ngaji; $i++) {
            $nrekap = dbase_numrecords($dbnrekap);
            if ($nrekap == 0 || $i > $nrekap) {
                $rowgaji = dbase_get_record_with_names($dbgaji, $i);
                dbase_add_record($dbnrekap, array(
                    $rowgaji['NO_URUT'],
                    $rowgaji['NAMA'],
                    $rowgaji['DEPT'],
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                ));
                $edit = dbase_get_record_with_names($dbnrekap, $i);
                unset($edit['deleted']);
                $edit[$bln_gaji] = $rowgaji['GAJI_DASAR'];
                $edit = array_values($edit);
                dbase_replace_record($dbnrekap, $edit, $i);
            } else {
                for ($j = 1; $j <= $ngaji; $j++) {
                    $rowgaji = dbase_get_record_with_names($dbgaji, $i);
                    $rowrekap = dbase_get_record_with_names($dbnrekap, $j);
                    if ($rowgaji['NO_URUT'] == $rowrekap['NO_URUT']) {
                        unset($rowrekap['deleted']);
                        $rowrekap[$bln_gaji] = $rowgaji['GAJI_DASAR'];
                        $rowrekap = array_values($rowrekap);
                        dbase_replace_record($dbnrekap, $rowrekap, $j);
                        break;
                    } else {
                        if ($j > $nrekap) {
                            dbase_add_record($dbnrekap, array(
                                $rowgaji['NO_URUT'],
                                $rowgaji['NAMA'],
                                $rowgaji['DEPT'],
                                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                            ));
                            $n = dbase_numrecords($dbnrekap);
                            $edit = dbase_get_record_with_names($dbnrekap, $n);
                            unset($edit['deleted']);
                            $edit[$bln_gaji] = $rowgaji['GAJI_DASAR'];
                            $edit = array_values($edit);
                            dbase_replace_record($dbnrekap, $edit, $j);
                            break;
                        }
                    }
                }
            }
        }
        //pph+thr
        $nrekap = dbase_numrecords($dbnrekap);
        $npph = dbase_numrecords($dbpph);
        $bln_pph = 'PPH' . $nposisi;
        $bln_thr = 'THR' . $nposisi;
        for ($i = 1; $i <= $npph; $i++) {
            for ($j = 1; $j <= $nrekap; $j++) {
                $rowpph = dbase_get_record_with_names($dbpph, $i);
                $rowrekap = dbase_get_record_with_names($dbnrekap, $j);
                if ($rowpph['NO_URUT'] == $rowrekap['NO_URUT']) {
                    unset($rowrekap['deleted']);
                    $rowrekap[$bln_pph] = $rowpph['PPH_21'];
                    $rowrekap[$bln_thr] = $rowpph['THR'];
                    $rowrekap = array_values($rowrekap);
                    dbase_replace_record($dbnrekap, $rowrekap, $j);
                    break;
                }
            }
        }

        //clear
        $ngaji = dbase_numrecords($dbgaji);
        for ($i = 1; $i <= $ngaji; $i++) {
            $rowgaji = dbase_get_record_with_names($dbgaji, $i);
            $rowpph = dbase_get_record_with_names($dbpph, $i);
            $rowwaktu = dbase_get_record_with_names($dbwaktu, $i);
            $gaji = $rowgaji['GAJI_DASAR'];

            unset($rowgaji['deleted']);
            $rowgaji['THR'] = 0;
            $rowgaji['BONUS'] = 0;

            $rowgaji = array_values($rowgaji);
            dbase_replace_record($dbgaji, $rowgaji, $i);


            //clear pph
            unset($rowpph['deleted']);
            $rowpph['YTD_TJAB'] = $rowpph['YTD_TJAB'] + $rowpph['TUNJ_JAB'];
            $rowpph['TUNJ_JAB'] = 0;
            $rowpph['YTD_TKES'] = $rowpph['YTD_TKES'] + $rowpph['TUNJ_KES'];
            $rowpph['TUNJ_KES'] = 0;
            $rowpph['YTD_PTKP'] = $rowpph['YTD_PTKP'] + ($rowpph['PTKP'] / 12);
            $rowpph['PTKP'] = 0;
            $rowpph['YTD_JAMSOS'] = $rowpph['YTD_JAMSOS'] + $rowpph['JAMSOS'];
            $rowpph['JAMSOS'] = 0;
            $rowpph['YTD_JPK'] = $rowpph['YTD_JPK'] + $rowpph['JPK'];
            $rowpph['JPK'] = 0;
            $rowpph['YTD_JABAT'] = $rowpph['YTD_JABAT'] + $rowpph['JABAT'];
            $rowpph['JABAT'] = 0;
            $rowpph['YTD_THT'] = $rowpph['YTD_THT'] + $rowpph['THT'];
            $rowpph['THT'] = 0;
            $rowpph['YTD_PPH'] = $rowpph['YTD_PPH'] + $rowpph['PPH_21'];
            $rowpph['PPH_21'] = 0;
            $rowpph['YTD_PKP'] = $rowpph['YTD_PKP'] + ($rowpph['PKP'] / 12);
            $rowpph['PKP'] = 0;
            $rowpph['YTDPPHTHR'] = $rowpph['YTDPPHTHR'] + $rowpph['PPH_THR'];
            $rowpph['PPH_THR'] = 0;
            $rowpph['YTDPPHBON'] = $rowpph['YTDPPHBON'] + $rowpph['PPH_BONUS'];
            $rowpph['PPH_BONUS'] = 0;
            $rowpph['YTD_GAJI'] = $rowpph['YTD_GAJI'] + $gaji;
            $rowpph['YTD_THR'] = $rowpph['YTD_THR'] + $rowpph['THR'];
            $rowpph['THR'] = 0;
            $rowpph['YTD_BONUS'] = $rowpph['YTD_BONUS'] + $rowpph['BONUS'];
            $rowpph['BONUS'] = 0;
            $rowpph['YTD_BLN'] = $nposisi;

            $rowpph = array_values($rowpph);
            dbase_replace_record($dbpph, $rowpph, $i);
        }
        if ($nposisi == 12) {
            $nposisi = 1;
            $ncount += 1;
        } else {
            $nposisi += 1;
            $ncount += 1;
        }
        $file = ($_SESSION['pathKota'] . "init.ini");
        //echo $file;
        $myfile = fopen($file, "w") or die("Unable to open file!");
        $txt = "posisi_bulan = " . $nposisi . "\n" . "count_bulan = " . $ncount . "\nhitung_pph = 0";
        fwrite($myfile, $txt);
        fclose($myfile);
        echo "<script type='text/javascript'>alert('berhasil');</script>";
        dbase_close($dbgaji);
        dbase_close($dbpph);
        dbase_close($dbwaktu);
        dbase_close($dbnrekap);

        //sort nrekap.dbf
        sortDbf($_SESSION['pathKota'] . 'NREKAP.DBF',"NO_URUT");
        
    } else {
        echo "<script type='text/javascript'>alert('Lakukan perhitungan pph dahulu');</script>";
    }
}

if (isset($_POST['ytd'])) {
    $ini_array = parse_ini_file($_SESSION['pathKota'] . "init.ini");
    $nposisi = $ini_array['posisi_bulan'];
    $ncount = $ini_array['count_bulan'];
    $hitung_pph = $ini_array['hitung_pph'];

    if ($ncount < 12) {
        echo "<script type='text/javascript'>alert('data belum 1 tahun');</script>";
    } else {
        $dbpph = dbase_open($_SESSION['pathKota'] . "PPH.DBF", 2);
        $dbgaji = dbase_open($_SESSION['pathKota'] . "GAJI.DBF", 2);
        $dbwaktu = dbase_open($_SESSION['pathKota'] . "WAKTU_MASUK.DBF", 2);
        $dbpph1 = dbase_open($_SESSION['pathKota'] . "PPH1.DBF", 2);

        //delete isi pph1
        $npph1 = dbase_numrecords($dbpph1);
        for ($i = 1; $i <= $npph1; $i++) {
            dbase_delete_record($dbpph1, $i);
        }
        dbase_pack($dbpph1);

        $ngaji = dbase_numrecords($dbgaji);
        for ($i = 1; $i <= $ngaji; $i++) {
            $rowgaji = dbase_get_record_with_names($dbgaji, $i);
            $rowpph = dbase_get_record_with_names($dbpph, $i);
            $rowwaktu = dbase_get_record_with_names($dbwaktu, $i);
            // $rowpph1 = dbase_get_record_with_names($dbpph1, $i);

            dbase_add_record($dbpph1, array($rowpph['NO_URUT'], 0, 0, 0, $rowpph['YTD_JABAT'], $rowpph['YTD_PKP'], $rowpph['YTD_PPH']));
            unset($rowpph['deleted']);
            $rowpph['YTD_TJAB'] = 0;
            $rowpph['YTD_TKES'] = 0;
            $rowpph['YTD_PTKP'] = 0;
            $rowpph['YTD_JAMSOS'] = 0;
            $rowpph['YTD_JPK'] = 0;
            $rowpph['YTD_JABAT'] = 0;
            $rowpph['YTD_THT'] = 0;
            $rowpph['YTD_PKP'] = 0;
            $rowpph['YTD_PPH'] = 0;
            $rowpph['YTDPPHTHR'] = 0;
            $rowpph['YTDPPHBON'] = 0;
            $rowpph['YTD_GAJI'] = 0;
            $rowpph['YTD_THR'] = 0;
            $rowpph['YTD_BONUS'] = 0;
            $rowpph['YTD_TJAB'] = 0;
        }
        // nrekap.dbf bikin baru
        $def = array(
            array("NO_URUT", "C", 3),
            array("NAMA", "C", 30),
            array("DEPT", "C", 5),
            array("GAJI1", "N", 10, 0),
            array("THR1", "N", 10, 0),
            array("PPH1", "N", 10, 0),
            //2
            array("GAJI2", "N", 10, 0),
            array("THR2", "N", 10, 0),
            array("PPH2", "N", 10, 0),
            //3
            array("GAJI3", "N", 10, 0),
            array("THR3", "N", 10, 0),
            array("PPH3", "N", 10, 0),
            //4
            array("GAJI4", "N", 10, 0),
            array("THR4", "N", 10, 0),
            array("PPH4", "N", 10, 0),
            //5
            array("GAJI5", "N", 10, 0),
            array("THR5", "N", 10, 0),
            array("PPH5", "N", 10, 0),
            //6
            array("GAJI6", "N", 10, 0),
            array("THR6", "N", 10, 0),
            array("PPH6", "N", 10, 0),
            //7
            array("GAJI7", "N", 10, 0),
            array("THR7", "N", 10, 0),
            array("PPH7", "N", 10, 0),
            //8
            array("GAJI8", "N", 10, 0),
            array("THR8", "N", 10, 0),
            array("PPH8", "N", 10, 0),
            //9
            array("GAJI9", "N", 10, 0),
            array("THR9", "N", 10, 0),
            array("PPH9", "N", 10, 0),
            //10
            array("GAJI10", "N", 10, 0),
            array("THR10", "N", 10, 0),
            array("PPH10", "N", 10, 0),
            //11
            array("GAJI11", "N", 10, 0),
            array("THR11", "N", 10, 0),
            array("PPH11", "N", 10, 0),
            //12
            array("GAJI12", "N", 10, 0),
            array("THR12", "N", 10, 0),
            array("PPH12", "N", 10, 0)
        );
        if (!dbase_create($_SESSION['pathKota'] . 'NREKAP.DBF', $def)) {
            echo "Error, can't create the database\n";
        }

        $ncount = 0;
        echo "<script type='text/javascript'>alert('berhasil');</script>";
    }

    // echo $posisi;
    // echo $count;
    $file = ($_SESSION['pathKota'] . "init.ini");
    //echo $file;
    $myfile = fopen($file, "w") or die("Unable to open file!");
    $txt = "posisi_bulan = " . $nposisi . "\n" . "count_bulan = " . $ncount . "\nhitung_pph = " . $hitung_pph;
    fwrite($myfile, $txt);
    fclose($myfile);
}

$init = parse_ini_file($_SESSION['pathKota'] . "init.ini");
$posisi = $init['posisi_bulan'];
$count = $init['count_bulan'];

?>
<!DOCTYPE html>
<html>

<head>
    <title>Clear File</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->

    <!-- Font Awesome JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../src/tampilan.css">
    <link rel="stylesheet" type="text/css" href="../src/View.css">
</head>

<body>
    <?php printSideBar() ?>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a>Clear File<?php echo " (" . $_SESSION['kota'] . ")" ?></a>
                <a>Posisi Bulan: <?php echo $posisi ?></a>
            </div>
        </nav>
        <div>
            <form action="" method="post">
                <input type="submit" value="C.Month" name="month">
                <input type="submit" value="Y.T.D" name="ytd">
            </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebarCollapse').on('click', function() {
                    $('#sidebar').toggleClass('active');
                    $('#content').toggleClass('active');
                });
            });
        </script>
</body>

</html>