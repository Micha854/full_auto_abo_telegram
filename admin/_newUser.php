<?php
include(__DIR__.'/../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$query = "SELECT SUM(item_price) as total, SUM(abo_days) as abo, COUNT(id) as menge FROM products";
$result = $mysqli->query($query);
$row = $result->fetch_array();

$schnitt = $row["total"]/$row["abo"];	// durchschnittlicher preis pro tag


if($_POST["submit"] and $_POST["user"]) {

	function generateRandomString($length = 10) {
		//return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	} $passwd = generateRandomString(8);
	
	$newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
	$newMail = mysqli_real_escape_string($mysqli, $_POST["email"]);
	$newAdd = $_POST["user"];
	$ItemDesc = $newAdd;
	
	$sumBar = $_POST["itemprice"];
	
	$days_to_end = $_POST["itemprice"]/$schnitt;
	$days_to_end = ceil($days_to_end);
	
	$InputChannel = array();
	$InputChannel = $_POST["added"];
					
	// NEW USER OR UPDATE
	$check = $mysqli->query("SELECT * FROM ".$tbl." WHERE TelegramUser = '".$newUser."' AND endtime > now()");
	$row_cnt = $check->num_rows;
	
	if($row_cnt != 0) {
		$update = $check->fetch_array();
		$statement = "update";
		$date = $update["endtime"];
		$amountInsert = $update["Amount"];
		$amountInsert+=$sumBar;
	} else {
		$statement = "insert";
		$date = new DateTime();
		$amountInsert = $sumBar;
	}
	
	if($use_map == "PMSF") {
		$hashedPwd = password_hash($passwd, PASSWORD_DEFAULT);
						
		$datum = $date->getTimestamp();
		$expire_timestamp = strtotime('+'.$days_to_end.' day', $datum);
						
		$empfaenger	= $newMail;
		$loginName	= $empfaenger;
						
		if($statement == "insert") {
			$insert_pmsf_user = $mysqli->query("INSERT INTO users 
			(user,temp_password,expire_timestamp,login_system,access_level)
			VALUES ('$newMail','$hashedPwd','$expire_timestamp','$login_system','$access_level')");
		} else {
			mysqli_query($mysqli, "UPDATE users SET expire_timestamp = '".$expire_timestamp."' WHERE id = ".$update["id"]);
		}
	}
					
	elseif($use_map == "Rocketmap") {
		
		if($statement == "insert") {				
			include("../Htpasswd.php");
			$htpasswd = new Htpasswd('../.htpasswd');
			$htpasswd->addUser($newAdd, $passwd);
		}
						
		$empfaenger	= $newMail;
		$loginName	= $newAdd;
	}
					
	else {
		$empfaenger	= $newMail;
	}
					
	$InputChannels = implode(',',$InputChannel);
	
	if($statement == "insert") {
		$mysqli->query("INSERT INTO ".$tbl." 
		(buyerName,buyerEmail,Amount,TelegramUser,channels,pass,paydate,endtime)
		VALUES ('','$empfaenger','$amountInsert','$newUser','$InputChannels','$passwd',now(),NOW() + INTERVAL $days_to_end DAY)");
	} elseif($statement == "update") {
		mysqli_query($mysqli, "UPDATE ".$tbl." SET Amount = $amountInsert, endtime = DATE_ADD(endtime,INTERVAL $days_to_end DAY) WHERE id = ".$update["id"]);
	}
					
	if($mailmail = '1') {
		$betreff = $mailSubject;
		$from = "From: ".$WebsiteTitle." <".$mailmail.">\r\n";
		$from .= "Reply-To: ".$mailmail."\r\n";
		$from .= "Content-Type: text/html\r\n";
						
		ob_start();
		include("../mail.php");
		$mailtext = ob_get_contents();
		ob_end_clean();
 
		mail($empfaenger, $betreff, $mailtext, $from);
	}
	echo "User wurde gespeichert!";				
	include_once("_add_user.php");
									
}

?>
<h2><a href="index.php">Zur&uuml;ck</a></h2>
<form method="post" action="">
<table width="100%" border="1">
<tr><td>Telegram Username:</td><td><input class="input" size="10" type="text" name="user" value="@" style="width:100%" /></td></tr>
<tr><td>eMail:</td><td><input class="input" size="10" type="text" name="email" value="" style="width:100%" /></td></tr>
<tr><td>Bar erhalten:</td><td><input type="text" name="itemprice" value="" style="width:100%" /></td></tr>
<tr><td>Channels:</td><td>
	<?php
		foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
    		echo $channel["name"]." beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /><br />";
		}
	?></td></tr>
	<tr><td colspan="2" align="center"><input class="dw_button" type="submit" name="submit" value="Benutzer erstellen" /></td></tr>
</table>
</form>