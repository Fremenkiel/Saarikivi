<?php

require_once('assets/simple_html_dom.php');

$cellar = file_get_contents('cellar.json');
$cellar = json_decode($cellar, true);

foreach($cellar as $wine) {
    $vi_id = $wine[9];

    $vi_link = 'https://www.vivino.com/DK/en/w/' . $vi_id;

/*$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $vi_link);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0')
);  
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);

foreach($result["reviews"] as $review) {

    if (array_key_exists('bottle_large', $review["vintage"]["image"]["variations"])) {
        //echo $review["vintage"]["image"]["variations"]["bottle_large"];
        $pic = str_replace('//', '', $review["vintage"]["image"]["variations"]["bottle_large"]);
        break;
    } elseif (array_key_exists('bottle_large', $review["vintage"]["image"]["variations"])) {
        $pic = "";
    }

}

//$pic = str_replace('//', '', $result["reviews"][0]["vintage"]["image"]["variations"]["bottle_large"]);

if(empty($pic)) {
    $pic = str_replace('//', '', $result["reviews"][0]["vintage"]["image"]["variations"]["large"]);
}*/

$html = file_get_html($vi_link);
foreach($html->find('img') as $element) {
    $src = $element->src;

    if (strpos($src, 'images.vivino.com/thumbs') !== false) {
        $pic = str_replace('//', '', $src);
        break;
    }
    
}

$img = 'pic/' . $vi_id . '.png';

var_dump($pic);

$ch = curl_init($pic);
$fp = fopen($img, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
    fclose($fp);

    
}

//$vi_id = $cellar[1][9];




?>