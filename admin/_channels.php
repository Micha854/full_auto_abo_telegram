<?php
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/../functions.php';
                    
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
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
<title><?=$WebsiteTitle ?> - ADMIN EDIT</title>
</head>

<body>
<div role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?php
if(isset($_GET["delete"])) {
    $id		= mysqli_real_escape_string($mysqli, $_GET["delete"]);
    $query	= "SELECT * FROM channels WHERE id = $id";
    $result = $mysqli->query($query);
    $row	= $result->fetch_array();
    
    echo "<div style='text-align:center'><h2>M&ouml;chtest du den Kanal</h2><h1 style='font-style:italic'><a href='".$row["url"]."' target='_blank'>".$row["name"]."</a></h1><h2>unwiderruflich l&ouml;schen?</h2>";
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
        $sort	= mysqli_real_escape_string($mysqli, $_POST["sort"]		[$id]);
        $url	= mysqli_real_escape_string($mysqli, $_POST["url"]		[$id]);
        $chatid	= mysqli_real_escape_string($mysqli, $_POST["chatid"]	[$id]);
        
        mysqli_query($mysqli, "UPDATE channels SET name = '".$name."', sort = '".$sort."', url = '".$url."', chatid = '".$chatid."' WHERE id = ".$id);
        
        $save = '<h3 style="background:#333333; color:#00CC00; padding:5px; text-align:center">&Auml;nderungen wurden gespeichert !</h3>';
    }
    
}

if(isset($_POST["newField"])) {
    mysqli_query($mysqli, "INSERT INTO channels SET name = '', sort = NULL, url = '', chatid = NULL");
}

if(isset($_POST["deleteChannel"])) {
    $deleteChannel = mysqli_real_escape_string($mysqli, $_POST["deleteChannel"]);
    mysqli_query($mysqli, "DELETE FROM channels WHERE id = $deleteChannel");
}

$query = "SELECT * FROM channels ORDER BY sort ASC, id ASC";
$result = $mysqli->query($query);
if(isset($_POST["submit"])) { echo $save; }
?>

<form method="post">
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<input type="submit" class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" name="newField" value="Neuer Eintrag" />
</form>
<h1>Telegram Channels</h1>
<p>höchster wert in Feld "Sort" zeigt den Kanal an erster Stelle</p>
<form method="post"> 
<?php
while($row = $result->fetch_array()) {
  $color 	= "";
  $txt	= "";
  //if($row["type"] == 0) {
    $chat_id = $row["chatid"];
    $getMember = callAPI('GET', $apiServer."getfullinfo/?chat_id=$chat_id", false);

        $check_chatid = json_decode($getMember, true);
        $check_invite = $check_chatid["response"]["full"]["exported_invite"]["link"];
        
    if(isset($check_invite)) {
      if($row["url"] != $check_invite) {
        $color 	= ";background:#FF0000;color:#FFFF00";
        $txt	= "<br>evtl. stimmt die Url f&uuml;r diesem Channel nicht, bitte pr&uuml;fe dies!
                  <input style='background:#009933;color:#FFF' type='text' class='form-control' value=".$check_invite.">";
      }
    } else {
        $color 	= "";
        $txt	= "<br>ChatID unbekannt!";
    }
  //}
?>

  <table>
    <tr>
      <th scope="col"><a class="btn btn-sm btn-outline-secondary" href="<?=$row["url"] ?>" target="_blank" role="button" title="<?=$row["name"] ?>">#<?=$row["id"] ?></a></th>
      <th scope="col"><a class="btn btn-sm btn-outline-secondary" href="?delete=<?=$row["id"] ?>" role="button">Channel l&ouml;schen</a></th>
    </tr>
    <tr>
      <th scope="col">Name: </th>
      <th scope="col" style="width:100%"><input type="hidden" name="id[]" value="<?=$row["id"]?>" /><input type="text" name="name[<?=$row["id"]?>]" class="form-control" value="<?=$row["name"] ?>" maxlength="155" style="width:100%" /></th>
    </tr>
    <tr>
      <th scope="col">Sort: </th>
      <th scope="col" style="width:100%"><input type="text" name="sort[<?=$row["id"]?>]" class="form-control" value="<?=$row["sort"] ?>" maxlength="155" style="width:100%" /></th>
    </tr>
    <tr>
      <th scope="col">URL: </th>
      <th scope="col" style="width:100%"><input type="text" name="url[<?=$row["id"]?>]" class="form-control" value="<?=$row["url"] ?>" maxlength="155" style="width:100%<?=$color?>" /><?=$txt?></th>
    </tr>
    <tr>
      <th scope="col">ChatID: </th>
      <th scope="col" style="width:100%"><input type="text" name="chatid[<?=$row["id"]?>]" class="form-control" value="<?=$row["chatid"] ?>" maxlength="15" style="width:100%" /></th>
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
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
