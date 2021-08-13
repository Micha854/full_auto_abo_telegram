<?php
require_once dirname(__FILE__) . '/../config.php';
                    
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

function compare_sum($a, $b)
  {
    return strnatcmp($a[sum], $b[sum]);
  }

  // stats_1, starts_2
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
    $city = (!$row["city"]) ? '** NONE **' : $row["city"];
    array_push($age, array("label"=>$city, "anzahl"=>$row["anzahl"], "y"=>number_format($row["amt"],2), "gdp"=>number_format($row["amt"]*100/$sum['summe'],2)));
}

// stats_3
$query2 = "SELECT * FROM $tbl WHERE NOW() < endtime order by city DESC";
$result2 = mysqli_query($mysqli, $query2);


$test = mysqli_query($mysqli, "SELECT item_price FROM products order by cast(abo_days as unsigned) ASC");
$abo = array();
while($tes2 = $test->fetch_array()) {
    array_push($abo, $tes2['item_price']);
}


$grouped = array();
$age2 = [];
$age3 = [];
$sumarr = [];
while($row2 = $result2->fetch_array()) {

    $sql2 = mysqli_query($mysqli, "SELECT * FROM products order by cast(abo_days as unsigned) ASC");

    $date1 = date_create(date('Y-m-d H:i:s',strtotime($row2["paydate"])));
    $date2 = date_create(date('Y-m-d H:i:s',strtotime($row2["endtime"])));

    $diff = $date1->diff($date2)->days;
    $array = [];
    while($day = $sql2->fetch_array()) {

        $value = $diff-$day['abo_days'];
        if ($value < 0) {
            $value = 999999;
        }
        $array[] = $value;
    }

    $city2 = (!$row2["city"]) ? '** NONE **' : $row2["city"];
    $summe = $abo[array_search(min($array), $array)];

	if (isset($sumarr[$summe])) {
		$sumarr[$summe]['count']++;
	} else {
        $sumarr[$summe] = array('count' => 1);
		$name = $mysqli->query("SELECT months FROM products WHERE item_price = '".$summe."'")->fetch_array();
		$sumarr[$summe] = array('count' => 1, 'name' => $name["months"]);
    }

    if (isset($grouped[$city2])) {
        $grouped[$city2]['count']++;
        $grouped[$city2]['sum']+=$summe;
    } else {
        $grouped[$city2] = $row2 + array('count' => 1) + array('sum' => $summe);
    }

    $grouped[$city2]['sum'] = number_format($grouped[$city2]['sum'],2);

}

usort($grouped, 'compare_sum');

foreach($grouped as $i) {
    $city2 = (!$i[city]) ? '** NONE **' : $i[city];
    array_push($age2, array("label"=>$city2, "anzahl"=>$i[count], y=>$i[sum]));
}

foreach($sumarr as $i => $item) {
	if($item[name] > 1) {
		$sumarr[$i][name] = $sumarr[$i][name] . ' Monate';
	} else {
		$sumarr[$i][name] = $sumarr[$i][name] . ' Monat';
	}
}

foreach($sumarr as $final) {
    array_push($age3, array("label"=>$final[name], y=>$final[count]));
}

/*
echo '<pre>';
print_r($grouped);
echo '</pre';
*/
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
		text: "bisherige Einnahmen"
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
		dataPoints: <?=json_encode($age, JSON_NUMERIC_CHECK) ?>
	}]
});


var chart3 = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	title: {
		text: "laufende Abonnenten"
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
		toolTipContent: "<b>{label}</b><br>Einnahmen: {y} Euro<br>Abonnenten: {anzahl}",
		dataPoints: <?=json_encode($age2, JSON_NUMERIC_CHECK) ?>
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
		dataPoints: <?=json_encode($stat, JSON_NUMERIC_CHECK) ?>
	}]
});


var chart4 = new CanvasJS.Chart("chartContainer4", {
	animationEnabled: true,
	title:{
		text: "meistgenutzte Abos",
	},
	data: [{
		type: "doughnut",
		startAngle: 60,
		//innerRadius: 60,
		indexLabelFontSize: 17,
		indexLabel: "{label}: #percent%",
		toolTipContent: "<b>{label}:</b> #percent%<br>Abonnenten {y}",
		dataPoints: <?=json_encode($age3, JSON_NUMERIC_CHECK) ?>
	}]
});

chart1.render();
chart3.render();
chart4.render();
chart2.render();

}
</script>
<div id="chartContainer1" style="height: 370px; width: 100%;"></div>
<br>
<div id="chartContainer3" style="height: 370px; width: 100%;"></div>
<br>
<div id="chartContainer4" style="height: 370px; width: 100%;"></div>
<br>
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>

</div>
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
