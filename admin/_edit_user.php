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

function generateRandomString($length = 10) {
	//return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
} $passwd = generateRandomString(8);

$id = mysqli_real_escape_string($mysqli, $_GET["id"]);
					
$query = "SELECT * FROM ".$tbl." WHERE id = $id";
$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_ASSOC);


if($_POST["submit"] and $_POST["user"]) {
	
	$InputUser = $row["TelegramUser"]; // old Username delete
	
	if($use_map == "Rocketmap") {
						
		include("../Htpasswd.php");
		$newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
		$newAdd = $_POST["user"];
	
		$htpasswd = new Htpasswd('../.htpasswd');
		$htpasswd->deleteUser($InputUser);
		$htpasswd->addUser($newAdd, $passwd);
					
		mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."', pass = '".$passwd."' WHERE id = ".$row["id"]);
	}
						
	mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."', pass = '".$passwd."' WHERE id = ".$row["id"]);
	
	if($mailmail = '1') {
		$empfaenger	= $row["buyerEmail"];
		$betreff = $mailSubject;
		$from = "From: ".$WebsiteTitle." <".$mailmail.">\r\n";
		$from .= "Reply-To: ".$mailmail."\r\n";
		$from .= "Content-Type: text/html\r\n";
		
		$ItemDesc = $newUser;
		
		
		$datetime1 = new DateTime();
		$datetime2 = new DateTime(date('Y-m-d', strtotime($row["endtime"])));
		$interval = $datetime1->diff($datetime2);
		$days_to_end = $interval->format('%d');
		
				
		ob_start();
		include("../mail.php");
		$mailtext = ob_get_contents();
		ob_end_clean();
 
		mail($empfaenger, $betreff, $mailtext, $from);
	}
					
	echo '<div style="display:none">';
	include 'madeline.php';

	$MadelineProto = new \danog\MadelineProto\API('session.madeline');
	$MadelineProto->start();
	
	$ChatBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'until_date' => 0];

	$query2 = "SELECT url FROM channels WHERE id IN (".$row["channels"].")";
	$result2 = $mysqli->query($query2);
	while($channel = $result2->fetch_array()) {
		$Updates = $MadelineProto->channels->editBanned(['channel' => $channel["url"], 'user_id' => $InputUser, 'banned_rights' => $ChatBannedRights, ]);	// banned old user
		$Updates = $MadelineProto->channels->inviteToChannel(['channel' => $channel["url"], 'users' => [$newAdd, $newAdd], ]);								// add new user
	}
	echo '</div>';
	echo '<h3 style="background:#009900; color:#FFFFFF; padding:5px; text-align:center"><a href="/tme/admin/">back</a> | user has been changed to '.$newAdd.'</h3>';
}
?>
<p>
<body>
   <main role="main" class="container">
<?php include "nav.php"; ?>
<p>
      <div class="jumbotron">
        <h1>Benutzer umbenennen</h1>
		<form method="post" action=""> 
  <table class="table">
  <tr>
    <th scope="col"><b>Aktueller @user</b></th>
    <th scope="col"><b>Neuer @user</b></th>
  </tr>
  <tr>
    <th scope="col"><?=$row["TelegramUser"] ?></th>
    <th scope="col"><input type="text" name="user" class="form-control" autocomplete="off" required /></td>
  </tr>
  <tr>
    <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="User &auml;ndern!" /></td>
	<a class="btn btn-sm btn-outline-secondary" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zurück</a>
  </tr>
</table>
</form>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
