<?php
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/../Htpasswd.php';
require_once dirname(__FILE__) . '/../functions.php';
                    
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$beginnScript = microtime(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/png" href="logo.png" sizes="32x32">
<link rel="icon" type="image/png" href="logo.png" sizes="96x96">
<title><?=$WebsiteTitle?> - Cron</title>
</head>
<body>

<?php
$sql_abos = $mysqli->query("SELECT * FROM ".$tbl);
while($get_user = $sql_abos->fetch_array()) {
    if($get_user["userid"] == NULL) {
        
        $from_username = $get_user["TelegramUser"];
        $getUserId = callAPI('GET', $apiServer."getInfo/?id=$from_username", false);
        
        $output = json_decode($getUserId, true);
        $user_id = $output["response"]["InputPeer"]["user_id"];
        
    
        mysqli_query($mysqli, "UPDATE ".$tbl." SET userid = $user_id WHERE id = ".$get_user["id"]." ");
        
        echo "set userid '".$user_id."' for ".$from_username."<br>";
    }
}


$timestamp = time();
$datum = date("Y-m-d H:i:s", $timestamp);
echo $datum;

echo "<br><br>";

$query = "SELECT * FROM channels";
$result = $mysqli->query($query);

if($result->num_rows) { 
    //echo "<h2>ergebnisse vorhanden</h2>";
    include(dirname(__FILE__) . "/_delete_user.php");
} else {
    echo "<h2>nix zu tun!</h2>";
}

// User löschen die in keinen Kanälen sind !!!
$non_exists_user = $mysqli->query("SELECT * FROM ".$tbl." WHERE NOT info = 2");
while($manual = $non_exists_user->fetch_array()) {
    if($manual["userid"]) {
        if($manual["endtime"] < date("Y-m-d H:i:s") ) {
            mysqli_query($mysqli, "UPDATE ".$tbl." SET info = 2 WHERE id = ".$manual['id']." ");
            //echo "<p>delete".$manual['TelegramUser'].'</p>';
            if($use_map == "PMSF") {
                mysqli_query($mysqli, "UPDATE users SET access_level = '0' WHERE user = '".$manual['buyerEmail']."' ");
            } elseif($use_map == "Rocketmap") {
                $load_htpasswd = file_get_contents('../.htpasswd');
                if(is_bool(strpos($load_htpasswd, $manual["TelegramUser"])) === false) {
                    $htpasswd = new Htpasswd('../.htpasswd');
                    $htpasswd->deleteUser($manual["TelegramUser"]);
                }
            }
        }
    }
}

$dauerScript = microtime(true) - $beginnScript;
echo "<h4>Verarbeitung der Cron in ".sprintf('%.3f', $dauerScript)." Sek.</h4>";

$datei = fopen(dirname(__FILE__) . "/cron.txt","w");
fwrite($datei, date("d.m.Y - H:i:s"),100);
fclose($datei);
?>
</body>
</html>