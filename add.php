<?php

$cellar = file_get_contents('cellar.json');
$cellar = json_decode($cellar, true);

function get_string_between($string){
    $start = "year=";
    $end = "&";
    if (strpos($string, "&") !== false) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    } else {
        return end(explode("year=", $string));
    }
    
}

if(isset($_GET["url"]) && isset($_GET["amount"]) && !empty($_GET["url"]) && !empty($_GET["amount"]) && isset($_GET["year"])) {
    $url = $_GET["url"];
    $amount = $_GET["amount"];
    $year = $_GET["year"];


    $url_end = end(explode("/", $url));
    $url_id = explode("?", $url_end)[0];

    if(empty($year)) {
    if (strpos($url_end, "year=") !== false) {
        $year = get_string_between($url_end);
        $year_key = strval($year);
    } else {
        $year = "";
        $year_key = "NA";
    }
    } else {
        $year_key = strval($year);
    }
    
    if(array_key_exists($url_id, $cellar)) {
        if(array_key_exists($year_key, $cellar[$url_id])) {
            $cellar[$url_id][$year_key]["amount"] = strval(++$cellar[$url_id][$year_key]["amount"]);
        } else {
            $vi_link = 'https://www.vivino.com/api/wines/' . $url_id . '/reviews?per_page=1&year=' . $year;

$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $vi_link);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0')
);  
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);


    $winery = $result["reviews"][0]["vintage"]["wine"]["winery"]["name"];
    $wine = $result["reviews"][0]["vintage"]["wine"]["name"];
    $region = $result["reviews"][0]["vintage"]["wine"]["region"]["name"];
    $contry = $result["reviews"][0]["vintage"]["wine"]["region"]["country"]["code"];

    $new_wine = array(
        "winery" => $winery,
        "wine" => $wine,
        "year" => $year,
        "region" => $region,
        "country" => $contry,
        "class" => "",
        "type" => "",
        "id" => $url_id,
        "amount" => $amount
    );

    $cellar[$url_id][$year_key] = $new_wine;
        }
    } else {

    $vi_link = 'https://www.vivino.com/api/wines/' . $url_id . '/reviews?per_page=50&year=' . $year;

$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $vi_link);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0')
);  
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);


    $winery = $result["reviews"][0]["vintage"]["wine"]["winery"]["name"];
    $wine = $result["reviews"][0]["vintage"]["wine"]["name"];
    $region = $result["reviews"][0]["vintage"]["wine"]["region"]["name"];
    $contry = $result["reviews"][0]["vintage"]["wine"]["region"]["country"]["code"];

    $new_wine = array(
        $year_key => array(
        "winery" => $winery,
        "wine" => $wine,
        "year" => $year,
        "region" => $region,
        "country" => $contry,
        "class" => "",
        "type" => "",
        "id" => $url_id,
        "amount" => $amount
        ));

    $cellar[$url_id] = $new_wine;

}



    $fp = fopen('cellar.json', 'w');
    fwrite($fp, json_encode($cellar));
    fclose($fp);

    header("Location: https://saarikivi.com");
    
    //var_dump($html->find("a[class=winery]"));
        //$src = $element->src;
    
        /*
        if (strpos($src, 'images.vivino.com/thumbs') !== false) {
            $pic = str_replace('//', '', $src);
            break;
        }
        */
    

}

?>
<html>
<head>
<script src="assets/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<style>
    html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

#url {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

#year {
    border-radius: 0;
    margin-bottom: -1;
}

#amount {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
</head>
<body class="text-center">
    
    <main class="form-signin w-100 m-auto">
    <form action="/add.php" method="get">
        <h1 class="h3 mb-3 fw-normal">Add Wine</h1>
    
        <div class="form-floating">
          <input type="text" name="url" class="form-control" id="url" placeholder="https://vivino.com">
          <label for="floatingInput">Vivino Link</label>
        </div>
        <div class="form-floating">
          <input type="number" name="year" class="form-control" id="year" placeholder="2000">
          <label for="floatingYear">Year</label>
        </div>
        <div class="form-floating">
          <input type="number" name="amount" class="form-control" id="amount" placeholder="1">
          <label for="floatingPassword">Amount</label>
        </div>
    
       
        <button class="w-100 btn btn-lg btn-primary" type="submit">Add</button>
      </form>
    </main>
    
    <!--<form action="/add.php" method="get">
    <input type="text" id="url" name="url" placeholder="URL"/>
    <input type="text" id="year" name="year" placeholder="Year"/>
    <input type="text" id="amount" name="amount" placeholder="Amount"/>
    <input type="submit"/>
</form>-->
</body>
</html>