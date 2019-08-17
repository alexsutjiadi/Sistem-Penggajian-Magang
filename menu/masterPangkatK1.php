<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}
include "../src/main.php";
if (isset($_POST['edit'])) {
    $rowId = $_POST['rowId'];
    $pangkat = strtoupper($_POST['pangkat']);
    $min = $_POST['min'];
    $max = $_POST['max'];

    $db = dbase_open('../src/golongan/PANGKAT_K1.DBF', 2);
    if ($db) {
        $row = dbase_get_record_with_names($db, $rowId);
        unset($row['deleted']);

        $row['PANGKAT'] = $pangkat;
        $row['MIN'] = $min;
        $row['MAX'] = $max;
        $row = array_values($row);
        dbase_replace_record($db, $row, $rowId);
    }
    dbase_close($db);
} else if (isset($_POST['add'])) {
    $pangkat = strtoupper($_POST['pangkat']);
    $min = strtoupper($_POST['min']);
    $max = $_POST['max'];

    $db = dbase_open('../src/golongan/PANGKAT_K1.DBF', 2);
    if ($db) {
        dbase_add_record($db, array($pangkat, $min, $max));
    }
    dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
    $idDelete = $_POST['idDelete'];

    $db = dbase_open('../src/golongan/PANGKAT_K1.DBF', 2);
    if ($db) {
        dbase_delete_record($db, $idDelete);
        dbase_pack($db);
    }
    dbase_close($db);
}


$db = dbase_open('../src/golongan/PANGKAT_K1.DBF', 0);
if ($db) {
    $record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>MASTER PANGKAT K1</title>

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
                <a>MASTER PANGKAT K1</a>
            </div>
        </nav>
        <table width="100%" cellspacing='0' id="myTable">
            <tr>
                <th onclick="sortTable(0)">PANGKAT</th>
                <th onclick="sortTable(1)">BATAS BAWAH</th>
                <th onclick="sortTable(2)">BATAS ATAS</th>
                <th></th>
            </tr>
            <?php
            for ($i = 1; $i <= $record_numbers; $i++) { ?>
            <tr>
                <?php $row = dbase_get_record_with_names($db, $i); ?>
                <td>
                    <?php echo $row['PANGKAT']; ?>
                </td>
                <td>
                    <?php echo $row['MIN'] ?>
                </td>
                <td>
                    <?php echo $row['MAX'] ?>
                </td>
                <td>
                    <input type="hidden" name="pangkat" value=<?php echo $row['PANGKAT']; ?> id=<?php echo "pangkat" . $i; ?>>
                    <input type="hidden" name="min" value=<?php echo "'" . $row['MIN'] . "'"; ?> id=<?php echo "min" . $i; ?>>
                    <input type="hidden" value="<?php echo $row['MAX'] ?>" id=<?php echo "max" . $i; ?>>
                    <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="idDelete" value=<?php echo $i; ?>>
                        <input type="submit" onclick="return isValidForm()" name="delete" class="btnDelete" value="DELETE">
                    </form>
                </td>
            </tr>
            <?php }
            dbase_close($db); ?>
            <!-- <tr>
                <td>
                    <form action="masterPangkatK1.php" method="post"><input type="submit" name="k1" value="K1"></form>
                </td>
                <td>
                    <form action="masterPangkatK2.php" method="post"><input type="submit" name="k2" value="K2"></form>
                </td>
                <td>
                    <form action="masterPangkatK3.php" method="post"><input type="submit" name="k3" value="K3"></form>
                </td>
            </tr> -->
            <!-- <tr>
                <td colspan="5">
                    <center><input type="submit" name="add" class="btnAdd" data-toggle="modal" data-target="#mdl-add" value="ADD"></center>
                    </form>
                </td>
            </tr> -->




        </table>
        <div id="mdl-add" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">TAMBAH DATA Pangkat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <label for="pangkat">PANGKAT</label>
                                <input type="text" class="pangkat" name="pangkat" autofocus>
                            </div>
                            <div class="col-lg-12">
                                <label for="min">BATAS BAWAH</label>
                                <input type="text" class="min" name="min" placeholder="">
                            </div>
                            <div class="col-lg-12">
                                <label for="max">BATAS ATAS</label>
                                <input type="text" class="max" name="max" placeholder="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" val="" id="add" name="add" value="ADD">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="mdl-update" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PEMELIHARAAN DATA PANGKAT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <label for="pangkat">PANGKAT</label>
                                <input type="text" class="pangkat" name="pangkat">
                                <input type="hidden" name="rowId" class="rowId">
                            </div>
                            <div class="col-lg-12">
                                <label for="min">BATAS BAWAH</label>
                                <input type="text" class="min" name="min" placeholder="">
                            </div>
                            <div class="col-lg-12">
                                <label for="max">BATAS ATAS</label>
                                <input type="text" class="max" name="max" placeholder="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" val="" id="edit" name="edit" value="SAVE CHANGE">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        sortTable(1);
        $(document).on("click", ".btnUpdate", function() {
            var clickId = $(this).data('id');
            var pangkatValue = $("#pangkat" + clickId).val();
            var minValue = $("#min" + clickId).val();
            var maxValue = $("#max" + clickId).val();

            $(".modal-body .pangkat").val(pangkatValue);
            $(".modal-body .pangkatValue").val(pangkatValue);
            $(".modal-body .rowId").val(clickId);
            $(".modal-body .min").val(minValue);
            $(".modal-body .max").val(maxValue);

        });

        function isValidForm() {
            var pilihan = confirm("hapus");;
            if (pilihan) {
                return true;
            } else {
                return false;
            }
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /*Loop through all table rows (except the
                first, which contains table headers):*/
                for (i = 1; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                    one from current row and one from the next:*/
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    //check if the two rows should switch place:
                    if (Number(x.innerHTML) > Number(y.innerHTML)) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
    </script>
    </script>
    <!-- Popper.JS -->
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