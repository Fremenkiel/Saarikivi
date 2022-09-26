<?php

if(isset($_GET["viid"]) && !empty($_GET["viid"]) && isset($_GET["year"])) {
    $viid = $_GET["viid"];
    $year = $_GET["year"];

    if(empty($year)) {
        $year = "NA";
    }


    $cellar = file_get_contents('cellar.json');
    $cellar = json_decode($cellar, true);

    if($cellar[$viid][$year]["amount"] != 0) {
        $cellar[$viid][$year]["amount"] = strval(--$cellar[$viid][$year]["amount"]);
    }

    //echo $cellar[$viid][$year]["amount"];

    $fp = fopen('cellar.json', 'w');
    fwrite($fp, json_encode($cellar));
    fclose($fp);
}

header("location: index.php");





?>