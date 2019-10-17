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

$query = "SELECT * FROM ".$tbl." WHERE endtime < now() ORDER BY endtime ASC";
$result = $mysqli->query($query);

if($result->num_rows) { 
	echo "<h2>ergebnisse vorhanden</h2>";
	include("_delete_user.php");
} else {
	echo "<h2>nix zu tun!</h2>";
}
?>
