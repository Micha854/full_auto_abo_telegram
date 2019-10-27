<?php
require_once(__DIR__.'/../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$id = mysqli_real_escape_string($mysqli, $_GET["id"]);
					
$query = "SELECT * FROM ".$tbl." WHERE id = $id";
$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_ASSOC);


if($_POST["submit"] and $_POST["user"]) {
	
	function generateRandomString($length = 10) {
		//return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	} $passwd = generateRandomString(8);
	
	$newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
	$newMail = mysqli_real_escape_string($mysqli, $_POST["email"]);
	$newAdd = $_POST["user"];
	$ItemDesc = $newAdd;
	
	$OldUser = $row["TelegramUser"]; // old Username delete
	$InputChannel = array($row["channels"]);
	
	$getInfo	= file_get_contents($apiServer."getfullInfo/?id=".$ItemDesc);
	$getUserId	= json_decode($getInfo, true);
	$userid		= $getUserId["response"]["InputPeer"]["user_id"];
	
	if($use_map == "PMSF") {
		$statement = "insert";
		$hashedPwd = password_hash($passwd, PASSWORD_DEFAULT);
		
		$date = $row["endtime"];
		$expire_timestamp = $date->getTimestamp();
						
		$loginName	= $_POST["email"];
		
		mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."', userid = '".$userid."', pass = '".$passwd."' WHERE id = ".$row["id"]);				
		mysqli_query($mysqli, "UPDATE users SET user = '".$newMail."', password = NULL, temp_password = '".$hashedPwd."', expire_timestamp = '".$expire_timestamp."', session_id = NULL, WHERE id = ".$row["buyerEmail"]);
	}
	
	elseif($use_map == "Rocketmap") {
		$statement = "insert";
		$date = $row["endtime"];
		$loginName	= $newAdd;
						
		include("../Htpasswd.php");
	
		$htpasswd = new Htpasswd('../.htpasswd');
		$htpasswd->deleteUser($OldUser);
		$htpasswd->addUser($newAdd, $passwd);
					
		mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."', userid = '".$userid."', pass = '".$passwd."' WHERE id = ".$row["id"]);
	}
						
	if($botSend == '1') {
		$botMessage = urlencode("Link zur MAP:\n$urlMap\n\nDeine Logindaten:\nUsername: $loginName\nPasswort: $passwd\n\nDein Abo endet am ".date('d.m.Y', strtotime($date)));
		$sendMessage = file_get_contents("https://api.telegram.org/bot".$apitoken."/sendMessage?chat_id=$userid&text=$botMessage");
		include_once("_add_user.php");
	}
					
	if($mailmail = '1') {
		
		$empfaenger	= $row["buyerEmail"];
		require_once('../mailer/class.phpmailer.php');

		$mail             = new PHPMailer();
		$mail->CharSet	  = 'ISO-8859-1';
		
		ob_start();
		include("../mail.php");
		$body = ob_get_contents();
		ob_end_clean();

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = $mailHost; // SMTP server
		$mail->Port       = $smtpPort;                    // set the SMTP port for the GMAIL server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Username   = $smtpUser; // SMTP account username
		$mail->Password   = $smtpPass;        // SMTP account password
		
		$mail->SetFrom($mailSender, $WebsiteTitle);
		$mail->AddReplyTo($mailSender, $WebsiteTitle);
		
		$mail->Subject    = $mailSubject;
		$mail->AltBody    = strip_tags($body); // optional, comment out and test
		$mail->MsgHTML($body);
		$mail->AddAddress($empfaenger, $WebsiteTitle);

		$mail->Send();
	}
					
	$userSave = "<h1>Benuzter ge&auml;ndert zu ".$newAdd."</h1>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<title><?=$WebsiteTitle ?> - ADMIN EDIT</title>
</head>

<body>
<main role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?=$userSave?>
<h1>Benutzer umbenennen</h1>
<form method="post" action=""> 
  <table class="table">
    <tr>
      <th scope="col"><b>Aktueller @Username</b></th>
      <th scope="col"><b><?=$row["TelegramUser"] ?></b></th>
    </tr>
    <tr>
      <th scope="col">Neuer @Username</th>
      <th scope="col"><input type="text" name="user" class="form-control" autocomplete="off" required /></th>
    </tr>
	<?php if($use_map == "PMSF") { ?>
	<tr>
      <th scope="col">Neue Emailadresse</th>
      <th scope="col"><input type="text" name="email" class="form-control" autocomplete="off" required /></th>
    </tr>
	<?php } ?>
    <tr>
      <th scope="col"><a class="btn btn-sm btn-outline-secondary" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a></th>
	  <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="User &auml;ndern!" /></th>
    </tr>
  </table>
</form>
</div>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
