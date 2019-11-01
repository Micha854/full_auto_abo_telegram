<style type="text/css">
.admin {
background:#0099FF;
color:#FFFFFF;
padding:3px;
line-height:160%
}

.true {
background:#009900;
color:#FFFFFF;
padding:3px;
line-height:160%
}

.false {
background:#FF0000;
color:#FFFFFF;
padding:3px;
line-height:160%
}

.time {
background:#FFFF00;
color:#000000;
padding:3px;
line-height:160%
}

.iderror {
background:#333333;
color:#FF3300;
padding:3px;
line-height:160%;
font-weight:bolder
}

.stat {
background:#FF99CC;
color:#000000;
padding:3px;
line-height:160%;
font-weight:bolder;
font-style:oblique
}
</style>
<?php
require_once(__DIR__.'/../config.php');
					
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$timestamp = time();
$datum = date("Y-m-d H:i:s", $timestamp);
echo $datum;

echo "<br><br>";

$query = "SELECT name, chatid FROM channels";
$result = $mysqli->query($query);

if($result->num_rows) { 
	//echo "<h2>ergebnisse vorhanden</h2>";
	include("_delete_user.php");
} else {
	echo "<h2>nix zu tun!</h2>";
}
?>
