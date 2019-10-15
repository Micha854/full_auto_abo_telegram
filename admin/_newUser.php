<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<title><?=$WebsiteTitle ?> - ADMIN</title>
</head>
<body>
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
<p>
  <main role="main" class="container">
	<?php include "nav.php"; ?>
<p>
      <div class="jumbotron">
        <h1>Benutzer hinzufügen</h1>
		<form method="post" action="">
  <div class="form-group">
    <p class="lead">Telegram Username:</p>
    <input type="text" name="user" class="form-control" aria-describedby="telegramname @" placeholder="@" required>
  </div>
   <div class="form-group">
    <p class="lead">eMail:</p>
    <input type="email" name="email" class="form-control" placeholder="Emailadresse" required>
  </div>
  <div class="form-group">
    <p class="lead">Bar erhalten</p>
    <input type="text" name="itemprice" class="form-control" placeholder="€" required>
  </div>
  <div>
    <p><tr><td><b>Channels:</b><br></td><td>
	<?php
		foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
    		echo $channel["name"]." beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /><br />";
		}
	?><p>
	  <button type="submit" name="submit" class="btn btn-sm btn-outline-secondary" value="Benutzer erstellen">Erstellen</button>
	  <a class="btn btn-sm btn-outline-secondary" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zurück</a>
	</td></tr></div>
  </form>
     </div>
  </main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
