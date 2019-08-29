<?php

// database "definition"
$def = array(
    array("NO_URUT", "C", 3),
    array("NAMA", "C", 30),
    array("GAJI1", "N", 10, 0),
    array("THR1", "N", 10, 0),
    array("PPH1", "N", 10, 0),
    //2
    array("GAJI2", "N", 10, 0),
    array("THR2", "N", 10, 0),
    array("PPH2", "N", 10, 0),
    //10
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

// creation
if (!dbase_create('NREKAP.DBF', $def)) {
    echo "Error, can't create the database\n";
}
