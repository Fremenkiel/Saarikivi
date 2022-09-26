<?php

$cellar = file_get_contents('cellar.json');
$cellar = json_decode($cellar, true);

foreach($cellar as $year) {
    foreach($year as $wine) {
        $wiID = $wine["id"];
        $wiYE = $wine["year"];

        if(empty($wiYE)) {
            $wiYE = "NA";
        }


$vi_link = 'https://www.vivino.com/api/wines/' . $wine["id"] . '/reviews?per_page=1&year=' . $wine["year"];

$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $vi_link);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0;')
);  
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);


$contry = $result["reviews"][0]["vintage"]["wine"]["region"]["country"]["code"];

//var_dump($contry);

$cellar[$wiID][$wiYE]["country"] = $contry;

    }}

  unlink("cellar.json");

  $fp = fopen('cellar.json', 'w');
    fwrite($fp, json_encode($cellar));
    fclose($fp);



?>