<?php

$cellar = file_get_contents('cellar.json');
$cellar = json_decode($cellar, true);

$cellar_arr = array();

foreach($cellar as $wine) {

    $wine_id = $wine["id"];
    $wine_year = $wine["year"];

    if(empty($wine_year)) {
        $wine_year = "NA";
    }


    $cellar_arr[$wine_id] = array(
        $wine_year => array(
        "winery" => $wine["winery"],
        "wine" => $wine["wine"],
        "year" => $wine["year"],
        "region" => $wine["region"],
        "country" => $wine["contry"],
        "class" => $wine["class"],
        "type" => $wine["type"],
        "id" => $wine["id"],
        "amount" => $wine["amount"]
    ));
}

unlink('cellar.json');


$fp = fopen('cellar_2.json', 'w');
fwrite($fp, json_encode($cellar_arr));
fclose($fp);

?>