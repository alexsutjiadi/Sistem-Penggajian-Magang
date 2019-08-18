<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['pathKota'])) {
    header("Location: ../pilihKota.php");
}

include "../src/main.php";
//edit gaji
if (isset($_POST['edit'])) {
    $alamat = $_POST['alamat'];
    $rowId = $_POST['rowId'];
    $npwp = $_POST['npwp'];

    $db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 2);
    if ($db) {

        $row = dbase_get_record_with_names($db, $rowId);

        unset($row['deleted']);
        $row['ALAMAT'] = $alamat;
        $row['NPWP'] = $npwp;
        $row = array_values($row);
        dbase_replace_record($db, $row, $rowId);

        dbase_close($db);
    }
}

//fetch data gaji dri db
$db = dbase_open($_SESSION['pathKota'] . 'GAJI.DBF', 0);
if ($db) {
    $record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>ALAMAT & N.P.W.P</title>

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
                <a>ALAMAT & N.P.W.P <?php echo " (" . $_SESSION['kota'] . ")" ?></a>
            </div>
        </nav>
        <table width="100%" border="1" id="myTable">
            <tr>
                <th onclick="sortTable(0)">NO. DEPT</th>
                <th onclick="sortTable(1)">NO. URUT</th>
                <th onclick="sortTable(2)">NAMA</th>
                <th></th>
            </tr>
            <?php
            for ($i = 1; $i <= $record_numbers; $i++) { ?>
            <tr>
                <?php $row = dbase_get_record_with_names($db, $i); ?>
                <td>
                    <?php echo $row['DEPT']; ?>
                </td>
                <td>
                    <?php echo $row['NO_URUT'] ?>
                </td>
                <td>
                    <?php echo $row['NAMA']; ?>
                </td>
                <td>
                    <input type="hidden" name="nama" value=<?php echo "'" . $row['NAMA'] . "'"; ?> id=<?php echo "nama" . $i; ?>>
                    <input type="hidden" name="no" value=<?php echo $row['NO_URUT'] ?> id=<?php echo "no" . $i ?>>
                    <input type="hidden" name="dept" value=<?php echo $row['DEPT']; ?> id=<?php echo "dept" . $i; ?>>
                    <input type="hidden" name="alamat" value=<?php echo "'" . $row['ALAMAT'] . "'"; ?> id=<?php echo "alamat" . $i; ?>>
                    <input type="hidden" name="npwp" value=<?php echo "'" . $row['NPWP'] . "'"; ?> id=<?php echo "npwp" . $i; ?>>
                    <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
                </td>
            </tr>
            <?php }
            dbase_close($db); ?>


        </table>
        <div id="mdl-update" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PERUBAHAN DATA ALAMAT & N.P.W.P</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <label for="dept">NO. DEPT</label>
                                <input type="text" class="dept" name="dept" placeholder="" disabled>
                                <input type="hidden" class="rowId" name="rowId">
                            </div>
                            <div class="col-lg-12">
                                <label for="nama">NAMA</label>
                                <input type="text" class="nama" name="nama" placeholder="" disabled>
                            </div>
                            <div class="col-lg-12">
                                <label for="alamat">ALAMAT</label>
                                <input type="text" class="alamat" name="alamat" id="alamat" placeholder="">
                            </div>
                            <div class="col-lg-12">
                                <label for="npwp">N.P.W.P</label>
                                <input type="text" class="npwp" name="npwp" id="npwp" placeholder="">
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
        $(document).on("click", ".btnUpdate", function() {
            var clickId = $(this).data('id');
            var deptValue = $("#dept" + clickId).val();
            var namaValue = $("#nama" + clickId).val();
            var alamatValue = $("#alamat" + clickId).val();
            var npwpValue = $("#npwp" + clickId).val();

            $(".modal-body .dept").val(deptValue);
            $(".modal-body .nama").val(namaValue);
            $(".modal-body .alamat").val(alamatValue);
            $(".modal-body .rowId").val(clickId);
            $(".modal-body .npwp").val(npwpValue);


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
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("myTable");
            switching = true;
            //Set the sorting direction to ascending:
            dir = "asc";
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
                    /*check if the two rows should switch place,
                    based on the direction, asc or desc:*/
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    //Each time a switch is done, increase this count by 1:
                    switchcount++;
                } else {
                    /*If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again.*/
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
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
    </script>
</body>

</html>