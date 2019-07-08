<?php
//edit data golongan
if (isset($_POST['edit'])) {
    $rowId = $_POST['rowId'];
    $pangkat = strtoupper($_POST['pangkat']);
    $min = $_POST['min'];
    $max = $_POST['max'];

    $db = dbase_open('../B/PANGKAT_K1.DBF', 2);
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
    }
//add data golongan
else if (isset($_POST['add'])) {
    $pangkat = strtoupper($_POST['pangkat']);
    $min = strtoupper($_POST['min']);
    $max = $_POST['max'];

    $db = dbase_open('../B/PANGKAT_K1.DBF', 2);
    if ($db) {
        dbase_add_record($db, array($pangkat, $min, $max));
    }
    dbase_close($db);
} else if (isset($_POST['delete']) == 1) {
    $idDelete = $_POST['idDelete'];
    //echo "string";

    $db = dbase_open('../B/PANGKAT_K1.DBF', 2);
    if ($db) {
        dbase_delete_record($db, $idDelete);
        dbase_pack($db);
    }
    dbase_close($db);
}

//fetch data golongan dri db
$db = dbase_open('../B/PANGKAT_K1.DBF', 0);
if ($db) {
    $record_numbers = dbase_numrecords($db);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div>
        <table border="1" id="myTable">
            <tr>
                <th onclick="sortTable(0)">PANGKAT</th>
                <th onclick="sortTable(1)">BATAS BAWAH</th>
                <th onclick="sortTable(2)">BATAS ATAS</th>
            </tr>
            <?php
            for ($i = 1; $i <= $record_numbers; $i++) { ?>
                <tr>
                    <?php $row = dbase_get_record_with_names($db, $i); ?>
                    <td>
                        <input type="text" name="pangkat" value=<?php echo $row['PANGKAT']; ?> id=<?php echo "pangkat" . $i; ?> disabled>
                    </td>
                    <td>
                        <input type="text" name="min" value=<?php echo "'" . $row['MIN'] . "'"; ?> id=<?php echo "min" . $i; ?> disabled>
                    </td>
                    <td>
                        <input type="text" value="<?php echo $row['MAX'] ?>" id=<?php echo "max" . $i; ?> disabled>
                    </td>
                    <td> <input type="submit" class="btnUpdate" data-toggle="modal" data-target="#mdl-update" value="EDIT" name="modal" data-id=<?php echo $i; ?>>
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
            <tr>
                <td><form action="masterPangkatK1.php" method="post"><input type="submit" name="k1" value="K1"></form></td>
                <td><form action="masterPangkatK2.php" method="post"><input type="submit" name="k2" value="K2"></form></td>
                <td><form action="masterPangkatK3.php" method="post"><input type="submit" name="k3" value="K3"></form></td>
            </tr>
            <tr>
                <td colspan="5">
                    <center><input type="submit" name="add" class="btnAdd" data-toggle="modal" data-target="#mdl-add" value="ADD"></center>
                    </form>
                </td>
            </tr>
            
           


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
</body>

</html>