<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}
include "../src/main.php";

?>
<!DOCTYPE html>
<html>

<head>
    <title>Hitung PPH</title>

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
    <?php printSideBar();?>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a>PPH Bonus</a>
            </div>
        </nav>
        <?php
        $db = dbase_open($_SESSION['pathKota'] . 'PPH.DBF', 0);
        $dbgaji = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
        $ndata = dbase_numrecords($db);
        if ($ndata != 0) {
            ?>
        <table width="100%" border="1" id="myTable">
            <tr>
                <th>NO. DEPT</th>
                <th>NIK</th>
                <th>NAMA</th>
                <th>BONUS</th>
                <th>PPH BONUS</th>
            </tr>
            <?php
                for ($i = 1; $i <= $ndata; $i++) { ?>
            <tr>
                <?php $row = dbase_get_record_with_names($db, $i);
                        $rowgaji = dbase_get_record_with_names($dbgaji, $i); ?>
                <td>
                    <?php echo $row['DEPT']; ?>
                </td>
                <td>
                    <?php echo $row['NIK'] ?>
                </td>
                <td>
                    <?php echo $rowgaji['NAMA']; ?>
                </td>
                <td>
                    <?php echo rupiah($row['BONUS']); ?>
                </td>
                <td>
                    <?php echo rupiah($row['PPH_BONUS']); ?>
                </td>

            </tr>
            <?php }
            }
            dbase_close($db);
            dbase_close($dbgaji) ?>


        </table>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebarCollapse').on('click', function() {
                    $('#sidebar').toggleClass('active');
                });
            });
        </script>
</body>

</html>