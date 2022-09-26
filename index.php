<?php

include("countries-array.php");

$cellar = file_get_contents('cellar.json');
$cellar = json_decode($cellar, true);

$flag = json_decode($flag, true);

//var_dump($flag);


//$vi_id = $cellar[1][9];

?>
<html>
<head>
<script src="assets/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="context-menu">
      <!--<div class="item" id="con-dri" onClick="Drink()">Drink</div>
      <div class="item" id="con-add" onClick="Add()">Add</div>
      <div class="item" id="con-edi" onClick="Edit()">Edit</div>-->
      <input id="cur-viid" hidden type="number" style="display: none;"/>
      <ul class="dropdown-menu position-static d-grid gap-1 p-2 rounded-3 mx-0 shadow w-220px">
    <li><a class="dropdown-item rounded-2" id="con-add" onClick="Add()">Add</a></li>
    <li><a class="dropdown-item rounded-2" id="con-edi" onClick="Edit()">Edit</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item rounded-2" id="con-dri" onClick="DrinkPop()">Drink</a></li>
  </ul>
    </div>
    <div class="popup-dialog">
    <div class="modal-dialog" role="document">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title">Drink</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="$('.popup-dialog').css('display', 'none');"></button>
      </div>
      <div class="modal-body py-0">
        <p>Would you like to "Drink" on bottle of this.</p>
      </div>
      <div class="modal-footer flex-column border-top-0">
        <button type="button" class="btn btn-lg btn-primary w-100 mx-0 mb-2" onClick="Drink()">Drink</button>
        <button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal" onClick="$('.popup-dialog').css('display', 'none');">Close</button>
      </div>
    </div>
  </div>
</div>
    <div class="col-lg-6 mx-auto">
      <p class="lead mb-4"></p>
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <button type="button" class="btn btn-primary btn-lg px-4 gap-3" onClick="window.location.href = 'add.php';">Add</button>
        <button type="button" class="btn btn-outline-secondary btn-lg px-4" onClick="window.location.reload();">Refresh</button>
      </div>
    </div>
    <div class="list-group list-group-checkable d-grid gap-2 border-0 w-auto">
  <?php
foreach($cellar as $year) {
  foreach($year as $wine) {

  if($wine["amount"] != 0) { 

  if($wine["year"] != "") {
    $re_link = $wine["id"] . "?year=" . $wine["year"];
  } else {
    $re_link = $wine["id"];
  }

  echo '<div onClick="Vivino(\'' . $re_link . '\')" class="wine-div list-group-item rounded-3 py-3" id="' . $wine["id"] . $wine["year"] . '"><div class="img-div">';
  echo '<img src="/pic/' . $wine["id"] . '.png">';
  echo '</div><div class="info-div">';
  echo "<h1>" . $wine["winery"] . "</h1>";
  echo "<h2><b>" . $wine["wine"] . "</b> " . $wine["year"] . "</h2>";
  echo '<div class="wine-country-div"><img src="/assets/flags/flag_' . $wine["country"] . '.png">';
  echo '<div class="wine-country-text">';
  echo '<p>' . $wine["region"] . ", " . $countries_list[strtoupper($wine["country"])] . "</p>";
  echo "</div></div>";
  echo '</div>';
  echo '<div class="wine-amount"><p>' . $wine["amount"] . '</p></div>';
  echo '<form action="drink.php" class="form-wine" id="form-' . $wine["id"] . $wine["year"] . '" method="get">';
  echo '<input name="viid" value="' . $wine["id"] . '">';
  echo '<input name="year" value="' . $wine["year"] . '">';
  echo '</form>';
  echo '</div>';
}
}
}
  ?>
  </div>

<script>
  function Add() {
    
  }

  function Vivino(wineId) {
    //window.location.href = "https://www.vivino.com/DK/en/w/" + wineId;
    window.open("https://www.vivino.com/DK/en/w/" + wineId, '_blank');

  }

  function DrinkPop() {
    contextMenu.classList.remove("visible");
     $('.popup-dialog').css('display', 'block');
  }

  function Drink() {
    var ViID = $("#cur-viid").val();

    $("#form-"+ViID).submit();

  }

  const contextMenu = document.getElementById("context-menu");
  const scope = document.querySelector("body");
  const container = document.getElementsByClassName('wine-div');


  scope.addEventListener("contextmenu", (event) => {
      contextMenu.classList.remove("visible");

    for (var i = 0; i < container.length; i++) {
      if (container[i] !== event.target && !container[i].contains(event.target)) { 
        $("#cur-viid").val("");
      } else {
        event.preventDefault();
        const { clientX: mouseX, clientY: mouseY } = event;

    contextMenu.style.top = `${mouseY}px`;
    contextMenu.style.left = `${mouseX}px`;

    contextMenu.classList.add("visible");
    $("#cur-viid").val(container[i].id);
    break;
      }
  }

    
  });

  scope.addEventListener("click", (e) => {
    if(e.target.offsetParent != contextMenu) {
      contextMenu.classList.remove("visible");
    }
  });
  

  </script>
</body>
</html>