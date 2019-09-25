<?php
include('../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

function generateRandomString($length = 10) {
	return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
}
$passwd = generateRandomString(8);

include("Htpasswd.php");

$id = mysqli_real_escape_string($mysqli, $_GET["id"]);
					
$query = "SELECT * FROM ".$tbl." WHERE id = $id";
$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_ASSOC);


if($_POST["submit"] and $_POST["user"]) {
	echo '<div style="display:none">';
	include 'madeline.php';

	$MadelineProto = new \danog\MadelineProto\API('session.madeline');
	$MadelineProto->start();

	$InputUser = $row["TelegramUser"]; // old Username delete
	$ChatBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'until_date' => 0];

	$Updates = $MadelineProto->channels->editBanned(['channel' => $InputChannel, 'user_id' => $InputUser, 'banned_rights' => $ChatBannedRights, ]);
	
	//$htpasswd = new Htpasswd('.htpasswd_user');
	//$htpasswd->deleteUser($InputUser);

	$newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
	$newAdd = $_POST["user"];
	
	//$htpasswd = new Htpasswd('.htpasswd_user');
	//$htpasswd->addUser($newAdd, $passwd);
					
	mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."', pass = '".$passwd."' WHERE id = ".$row["id"]);
	
	$Updates = $MadelineProto->channels->inviteToChannel(['channel' => $InputChannel, 'users' => [$newAdd, $newAdd], ]);
	echo '</div>';
	echo '<h3 style="background:#009900; color:#FFFFFF; padding:5px; text-align:center"><a href="admin/">back</a> | user has been changed to '.$newAdd.'</h3>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Pogo L&uuml;beck - ADMIN EDIT</title>
<style type="text/css">
html {
font-size:20px
}
td {
border:solid #999999 1px
}
.input {
font-size:18px;
padding:5px;
margin-top:10px;
margin-bottom:10px
}
</style>
</head>

<body>
<form action="" method="post">
<table width="100%" border="0" style="border:solid #999999 1px">
  <tr>
    <td><b>Aktueller @user</b></td>
    <td><b>Neuer @user</b></td>
  </tr>
  <tr>
    <td><?=$row["TelegramUser"] ?></td>
    <td><input class="input" type="text" name="user" autocomplete="off" style="width:90%; background:#FFFF99" /></td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center"><input class="input" type="submit" name="submit" value="User &auml;ndern!" /></td>
  </tr>
</table>
</form>
</body>
</html>