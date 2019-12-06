<?php
require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/../functions.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
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
<?php
if(isset($_GET["delete"])) {
	$id		= mysqli_real_escape_string($mysqli, $_GET["delete"]);
	$query	= "SELECT * FROM channels WHERE id = $id";
	$result = $mysqli->query($query);
	$row	= $result->fetch_array();
	
	echo "<div align='center'><h2>M&ouml;chtest du den Kanal</h2><h1 style='font-style:italic'><a href='".$row["url"]."' target='_blank'>".$row["name"]."</a></h1><h2>unwiderruflich l&ouml;schen?</h2>";
	?>
	<form method="post" action="_channels.php">
		<a class="btn btn-sm btn-outline-secondary" href="_channels.php" role="button">abbrechen</a>
		<input type="hidden" name="deleteChannel" value="<?=$row["id"]?>" />
		<input type="submit" class="btn btn-sm btn-outline-secondary" value="Kanal l&ouml;schen" />
	</form>
	<?php
	echo "</div>";
} else {

if(isset($_POST["submit"])) {
	
	$felder = $_POST["id"];
	foreach ($felder as $id) {
    	$name	= mysqli_real_escape_string($mysqli, $_POST["name"]		[$id]);
		$url	= mysqli_real_escape_string($mysqli, $_POST["url"]		[$id]);
		$chatid	= mysqli_real_escape_string($mysqli, $_POST["chatid"]	[$id]);
		
		mysqli_query($mysqli, "UPDATE channels SET name = '".$name."', url = '".$url."', chatid = '".$chatid."' WHERE id = ".$id);
		
		$save = '<h3 style="background:#333333; color:#00CC00; padding:5px; text-align:center">&Auml;nderungen wurden gespeichert !</h3>';
	}
	
}

if(isset($_POST["newField"])) {
	mysqli_query($mysqli, "INSERT INTO channels SET name = '', url = '', chatid = NULL");
}

if(isset($_POST["deleteChannel"])) {
	$deleteChannel = mysqli_real_escape_string($mysqli, $_POST["deleteChannel"]);
	mysqli_query($mysqli, "DELETE FROM channels WHERE id = $deleteChannel");
}

$query = "SELECT * FROM channels ORDER BY id DESC";
$result = $mysqli->query($query);
?>

<form method="post" action="">
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<input type="submit" class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" name="newField" value="Neuer Eintrag" />
</form>
<?php if(isset($_POST["submit"])) { echo $save; } ?>
<h1>Telegram Channels</h1>
<form method="post" action=""> 
<?php
while($row = $result->fetch_array()) {
$chat_id = $row["chatid"];
$getMember = callAPI('GET', $apiServer."getPWRchat/?id=$chat_id", false);

	$check_chatid = json_decode($getMember, true);
	$check_invite = $check_chatid["response"]["invite"];
	
if($row["url"] != $check_invite) {
	$color 	= ";background:#FF0000;color:#FFFF00";
	$txt	= "<br>evtl. stimmt die Url f&uuml;r diesem Channel nicht, bitte pr&uuml;fe dies! <span style='background:#009933;color:#FFF'>".$check_invite."</span>";
} else {
	$color 	= "";
	$txt	= "";
}
?>

  <table>
	<tr>
      <th scope="col"><h2 style="margin-top:10px; margin-bottom:10px; font-style:oblique"><a href="<?=$row["url"] ?>" target="_blank" title="<?=$row["name"] ?>">#<?=$row["id"] ?></a></h2></th>
	  <th scope="col"><a class="btn btn-sm btn-outline-secondary" href="?delete=<?=$row["id"] ?>" role="button">Channel l&ouml;schen</a></h2></th>
	</tr>
	<tr>
      <th scope="col">Name: </th>
	  <th scope="col" style="width:100%"><input type="hidden" name="id[]" value="<?=$row["id"]?>" /><input type="text" name="name[<?=$row["id"]?>]" value="<?=$row["name"] ?>" maxlength="155" style="width:100%" /></th>
	</tr>
	<tr>
      <th scope="col">URL: </th>
	  <th scope="col" style="width:100%"><input type="text" name="url[<?=$row["id"]?>]" value="<?=$row["url"] ?>" maxlength="155" style="width:100%<?=$color?>" /><?=$txt?></th>
	</tr>
	<tr>
	  <th scope="col">ChatID: </th>
	  <th scope="col" style="width:100%"><input type="text" name="chatid[<?=$row["id"]?>]" value="<?=$row["chatid"] ?>" maxlength="15" style="width:100%" /></th>
    </tr>
  </table>
<hr />
<?php } ?>
<input type="submit" class="btn btn-sm btn-outline-secondary" name="submit" value="Speichern" />
</form>
<?php
}
?>
</div>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
