<?php 
$cellar = array_map('str_getcsv', file('cellar.csv'));
unset($cellar[0]);

$i = 1;

foreach($cellar as $wine) {

    $url = $wine[9];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $a = curl_exec($ch);

    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    $vi_id = end(explode("/", $url));

    if(strpos($vi_id, '?') !== false){
        $vi_id = explode("?", $vi_id)[0];
    }

    $cellar[$i][9] = $vi_id;

    $i++;
}

unlink('cellar.json');


$fp = fopen('cellar.json', 'w');
fwrite($fp, json_encode($cellar));
fclose($fp);
?>