<?php
require_once dirname(__FILE__) . '/../config.php';
                    
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$sql = $mysqli->query("SELECT endtime FROM ".$tbl);
$active = 0;
$inactive = 0;
$stat = array();
while($row = $sql->fetch_array()) {

    if(date_create(date('Y-m-d H:i:s',time())) < date_create(date('Y-m-d H:i:s',strtotime($row["endtime"])))) {
        $active++;
    } else {
        $inactive++;
    }
}
array_push($stat, array("label"=>'Aktiv', "y"=>$active));
array_push($stat, array("label"=>'Inaktiv', "y"=>$inactive));

$sum = $mysqli->query("SELECT SUM(Amount) as summe FROM ".$tbl)->fetch_array();

$query = "SELECT SUM(Amount) as amt, COUNT(city) as anzahl, city, endtime FROM ".$tbl." GROUP BY city ORDER BY SUM(Amount) ";
$result = $mysqli->query($query);

$age = array();
while($row = $result->fetch_array()) {
    $city = (!$row["city"]) ? '*** OHNE ANGABE ***' : $row["city"];
    array_push($age, array("label"=>$city, "anzahl"=>$row["anzahl"], "y"=>round($row["amt"]), "gdp"=>number_format($row["amt"]*100/$sum['summe'],2)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/png" href="logo.png" sizes="32x32">
<link rel="icon" type="image/png" href="logo.png" sizes="96x96">
<title><?=$WebsiteTitle ?> - ADMIN STATS</title>
</head>

<body>
<div role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<script>
window.onload = function () {
	
var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	title: {
		text: "Zusammensetzung der Einnahmen"
	},
	axisX: {
		interval: 1
	},
	axisY: {
		title: "€",
		includeZero: false,
		scaleBreaks: {
			type: "wavy"
		}
	},
	data: [{
		type: "bar",
		toolTipContent: "<b>{label}</b><br>Einnahmen: {y} Euro<br>{gdp}% der Summe<br>Abonnenten: {anzahl}",
		dataPoints: <?=json_encode($age) ?>
	}]
});


var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	title: {
		text: "aktive/inaktive Abos"
	},
	data: [{
		type: "pie",
        startAngle: 240,
		indexLabel: "{label} {y}",
		dataPoints: <?=json_encode($stat) ?>
	}]
});

chart1.render();
chart2.render();

}
</script>
<div id="chartContainer1" style="height: 370px; width: 100%;"></div>
<br><br>
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>

</div>
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
