<?php
require_once dirname(__FILE__) . '/../config.php';

if(!file_exists(dirname(__FILE__) . '/cron.txt')) {
    $handle = fopen(dirname(__FILE__) . '/cron.txt', 'a');
    fclose($handle);
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
<title><?=$WebsiteTitle ?> - ADMIN</title>

</head>

<body>
<div role="main" class="container">
<?php 
include "nav.php";
if(isset($sortIndex) and (empty($_GET["spalte"]) or empty($_GET["sort"]))) {
    header("Location: $WebsiteUrl/admin/$sortIndex");
} else {
    if(isset($sortIndex) and !isset($_GET['page'])) {
        $getPage = '&';
    } elseif(isset($sortIndex) and $_GET['page']) {
        $getPage = '&';
    } else {
        $getPage = '?';
        $sortIndex = '';
    }
}					
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

if(isset($_POST["deleteUser"])) {
    $deleteUserId	 = mysqli_real_escape_string($mysqli, $_POST["deleteUser"]);
    $deleteUserName	 = mysqli_real_escape_string($mysqli, $_POST["username"]);
    $deleteUserEmail = mysqli_real_escape_string($mysqli, $_POST["email"]);
    mysqli_query($mysqli, "DELETE FROM ".$tbl." WHERE id = $deleteUserId");
    
    if($use_map == "PMSF") {
        mysqli_query($mysqli, "UPDATE users SET access_level = '0' WHERE user = '".$deleteUserEmail."' ");
    } elseif($use_map == "Rocketmap") {
        $load_htpasswd = file_get_contents('../.htpasswd');
        if(is_bool(strpos($load_htpasswd, $deleteUserName)) === false) {
            include '../Htpasswd.php';
            $htpasswd = new Htpasswd('../.htpasswd');
            $htpasswd->deleteUser($deleteUserName);
        }
    }
    
    $Save = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Benutzer ".$deleteUserName." wurde gelöscht<div style=\"font-size:13px;padding-top:10px\"><p>Hinweis: Der Benutzer wird erst bei dem nächsten Cron Aufruf aus den Kanälen gelöscht!</p></div></h3>";
}

$spalten = array(
"TelegramUser"	=> "Telegram Username ",
"paydate"		=> "Bezahlt am ",
"endtime"		=> "L&auml;uft bis ",
"city"		    => "Bereich ");

$spalte = isset($_GET["spalte"]) ? $_GET["spalte"] : 'id'; // Default-Wert
$sort = isset($_GET["sort"]) ? $_GET["sort"] : 'desc';

if (!array_key_exists($spalte ,$spalten)) {
    $spalte = 'id'; // Default-Wert
    $_GET['spalte'] = $spalte;
    $_GET['sort'] = 'desc';
}

if (!in_array($sort, array('asc', 'desc'))) {
    $sort = 'desc'; // Default-Wert
}

// switch between active and inactive users
$show_inactive  = '';
$where_inactive = ' WHERE endtime > CURRENT_TIMESTAMP()';
$show_user  = '&nbsp;&nbsp;<a href="?spalte=' . $_GET['spalte'] . '&sort=' . $_GET['sort'] . '&inactive=true">inaktive zeigen</a>';
if(isset($_GET["inactive"]) == 'true') {
    $show_inactive  = '&inactive=true';
    $where_inactive = ' WHERE endtime < CURRENT_TIMESTAMP()';
    $show_user  = '&nbsp;&nbsp;<a href="?spalte=' . $_GET['spalte'] . '&sort=' . $_GET['sort'] . '">aktive zeigen</a>';
}

if(isset($_POST["searchSubmit"])) {
    $result_tot	= "SELECT COUNT(*) as total FROM ".$tbl." WHERE (buyerEmail LIKE '%".$_POST["Search"]."%' OR city LIKE '%".$_POST["Search"]."%' OR TelegramUser LIKE '%".$_POST["Search"]."%')";
} else {
    $result_tot	= "SELECT COUNT(*) as total FROM ".$tbl.$where_inactive;
}
$row_total	= $mysqli->query($result_tot)->fetch_assoc();
$gesamte_anzahl = $row_total['total'];
if(!isset($ergebnisse_pro_seite)) { $ergebnisse_pro_seite = 50; }
$gesamt_seiten = ceil($gesamte_anzahl/$ergebnisse_pro_seite); 


if (empty($_GET['page'])) {
    $seite = 1;
    $gettingPage = '';
} else {
    $seite = $_GET['page'];
    if ($seite > $gesamt_seiten) {
        $seite = 1;
    }
    $gettingPage = '&page='.$seite;
} 

$limit = ($seite*$ergebnisse_pro_seite)-$ergebnisse_pro_seite;


if(isset($_POST["searchSubmit"])) {
    $query		= "SELECT * FROM ".$tbl." WHERE (buyerEmail LIKE '%".$_POST["Search"]."%' OR city LIKE '%".$_POST["Search"]."%' OR TelegramUser LIKE '%".$_POST["Search"]."%') ORDER BY " . $spalte . " " . $sort." LIMIT ".$limit.", ".$ergebnisse_pro_seite;
} else {
    $query		= "SELECT * FROM ".$tbl.$where_inactive."  ORDER BY " . $spalte . " " . $sort." LIMIT ".$limit.", ".$ergebnisse_pro_seite;
}
$result = $mysqli->query($query);

echo '<div class="jumbotron">';
if(isset($Save)) { echo $Save; }

$htpasswd_count = '';
if($use_map == "Rocketmap") {
    // .htpasswd auslesen
    $file="../.htpasswd";
    $linecount = 0;
    $handle = fopen($file, "r");
    while(!feof($handle)){
        $line = fgets($handle, 4096);
        $linecount = $linecount + substr_count($line, PHP_EOL);
    }
    fclose($handle);
    if($gesamte_anzahl != $linecount) {
        $htpasswd_count = "<span style='font-size:15px; background:#333333; color:#FF0000; padding:3px; font-weight:bolder'>$linecount User in .htpasswd</span>";
    }
}

echo '<h3 style="text-align:left;float:left">'.$gesamte_anzahl; if($gesamte_anzahl == 1) { echo ' Abonnent'; } else { echo ' Abonnenten'; } echo '</h3>'. $show_user .'<div style="text-align:right">'.$htpasswd_count.' </div>'; 
$sites = '';
if($gesamte_anzahl > $ergebnisse_pro_seite) {
    for ($i=1; $i<=$gesamt_seiten; ++$i) {
        if ($seite == $i) {
            $site = '<a class="btn btn-sm btn-outline-secondary" href="'.$sortIndex.$getPage.'page='.$i.'" style="font-weight:bold;color:#FF0000" title="Seite '.$i.' von '.$gesamt_seiten.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
        } else {
            $site = '<a class="btn btn-sm btn-outline-secondary" href="'.$sortIndex.$getPage.'page='.$i.'" title="Seite '.$i.' von '.$gesamt_seiten.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
        }
        $sites .= $site;
    }
}
?>
    <table class="table">
        <thead class="thead-light">
            <tr>
                 <?php
                if($sites) {
                    echo "<th colspan='4'>".$sites."</th>";
                }
                foreach ($spalten as $spalte => $name) {
                    
                    if(isset($_GET["spalte"]) and $_GET["spalte"] == $spalte) {
                        if($_GET["sort"] == 'asc') {
                            $active = 'id="active"';
                            $active2 = '';
                        } elseif($_GET["sort"] == 'desc') {
                            $active = '';
                            $active2 = 'id="active"';
                        }
                    } else {
                        $active = '';
                        $active2 = '';
                    }
                    
                    if($name == $spalten['city']) {
                        $th = '<th class="city">';
                    } else {
                        $th = '<th>';
                    }

                    echo $th .
                        ucfirst($name) .
                        '<a href="?spalte=' . $spalte . '&sort=asc'.$gettingPage.$show_inactive.'" '.$active.' title="Aufsteigend sortieren">&#9650;</a>' .
                        '<a href="?spalte=' . $spalte . '&sort=desc'.$gettingPage.$show_inactive.'" '.$active2.' title="Absteigend sortieren">&#9660;</a>' .
                    '</th>';
                }
                echo "<th class='mobile'></th>";
                ?>
                 <th scope="col"></th>
            </tr>
        </thead>
<?php
while($row = $result->fetch_array()) {

if($row["endtime"] < date("Y-m-d H:i:s")) {
    $color = ' style="color:#FF0000;font-weight:bolder"';
} else {
    $color = ' style="color:#000"';
}

if(strlen($row["TelegramUser"]) >= 16) {
    $teleUser =	substr($row["TelegramUser"], 0, 14).'..';
} else {
    $teleUser = $row["TelegramUser"];
}

// HighlightChannelId where user accessed
$HighlightUser = '';
if(is_int($HighlightChannelId)) {
    foreach(explode(',', $row["channels"]) as $n) {
        if($HighlightChannelId == $n) {
            $HighlightUser = ' <span class="dot"></span>';
        }
    }
}
?>
  <tr>
    <td class="mobile"><a href="https://t.me/<?=substr($row["TelegramUser"], 1) ?>"><?=$teleUser ?></a><?=$HighlightUser ?></td>
    <td class="destop"><a href="https://t.me/<?=substr($row["TelegramUser"], 1) ?>"><?=$row["TelegramUser"] ?></a><?=$HighlightUser ?></td>
    <td title="<?=date("d.m.Y H:i:s", strtotime($row["paydate"])) ?>"><?=date("d.m.y", strtotime($row["paydate"])) ?></td>
    <td<?=$color?> title="<?=date("d.m.Y H:i:s", strtotime($row["endtime"])) ?>"><?=date("d.m.y", strtotime($row["endtime"])) ?></td>
    <td class='city'><?=$row["city"] ?></td>
    <td><a class="btn btn-sm btn-outline-secondary" href="_edit_user.php?id=<?=$row["id"]?>" role="button">edit</a></td>
  </tr>
<?php
}
?>
    </table>
<a class="btn btn-sm btn-outline-secondary" href="_newUser.php" role="button">Neuer User</a>
<a class="btn btn-sm btn-outline-secondary" href="<?=$urlMap ?>" role="button">zur Map</a>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>