<?php
require_once(__DIR__.'/../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	$query	= "SELECT * FROM products WHERE id = $id";
	$result = $mysqli->query($query);
	$row	= $result->fetch_array();
	
	echo "<div align='center'><h2>M&ouml;chtest du das Abo</h2><h1 style='font-style:italic'><a href='#' >Art. Nr.: \"".$row["item_number"]."\" | Preis: \"".$row["item_price"]."\" | Tage: \"".$row["abo_days"]."\"</a></h1><h2>unwiderruflich l&ouml;schen?</h2>";
	?>
	<form method="post" action="_products.php">
		<a class="btn btn-sm btn-outline-secondary" href="_products.php" role="button">abbrechen</a>
		<input type="hidden" name="deleteAbo" value="<?=$row["id"]?>" />
		<input type="submit" class="btn btn-sm btn-outline-secondary" value="Abo l&ouml;schen" />
	</form>
	<?php
	echo "</div>";
} else {

if(isset($_POST["submit"])) {
	
	$felder = $_POST["id"];
	foreach ($felder as $id) {
    	$months			= mysqli_real_escape_string($mysqli, $_POST["months"]		[$id]);
		$item_number	= mysqli_real_escape_string($mysqli, $_POST["item_number"]	[$id]);
		$item_price		= mysqli_real_escape_string($mysqli, $_POST["item_price"]	[$id]);
		$abo_days		= mysqli_real_escape_string($mysqli, $_POST["abo_days"]		[$id]);
		
		$sum = str_replace(",",".", $item_price);
		
		mysqli_query($mysqli, "UPDATE products SET months = '".(int)$months."', item_number = '".(int)$item_number."', item_price = '".$sum."', abo_days = '".(int)$abo_days."' WHERE id = ".$id);
		
		$save = '<h3 style="background:#333333; color:#00CC00; padding:5px; text-align:center">&Auml;nderungen wurden gespeichert !</h3>';
	}
	
}

if(isset($_POST["newField"])) {
	mysqli_query($mysqli, "INSERT INTO products SET months = '', item_number = '', item_price = '', abo_days = '' ");
}

if(isset($_POST["deleteAbo"])) {
	$deleteAbo = mysqli_real_escape_string($mysqli, $_POST["deleteAbo"]);
	mysqli_query($mysqli, "DELETE FROM products WHERE id = $deleteAbo");
}

$query = "SELECT * FROM products ORDER BY id DESC";
$result = $mysqli->query($query);
if(isset($_POST["submit"])) { echo $save; }
?>

<form method="post" action="">
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<input type="submit" class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" name="newField" value="Neuer Eintrag" />
</form>
<h1>Abos verwalten</h1>
<form method="post" action=""> 
<?php
while($row = $result->fetch_array()) {
?>

  <table>
	<tr>
      <th scope="col"><h2 style="margin-top:10px; margin-bottom:10px; font-style:oblique"><a href="#">#<?=$row["id"] ?></a></h2> </th>
	  <th scope="col"><a class="btn btn-sm btn-outline-secondary" href="?delete=<?=$row["id"] ?>" role="button">Abo l&ouml;schen</a></h2></th>
	</tr>
	<tr>
      <th scope="col">Monate: </th>
	  <th scope="col" style="width:100%"><input type="hidden" name="id[]" value="<?=$row["id"]?>" /><input type="text" name="months[<?=$row["id"]?>]" value="<?=$row["months"] ?>" maxlength="2" style="width:100%" /></th>
	</tr>
	<tr>
      <th scope="col">Art. Nr.: </th>
	  <th scope="col" style="width:100%"><input type="text" name="item_number[<?=$row["id"]?>]" value="<?=$row["item_number"] ?>" maxlength="6" style="width:100%" /></th>
	</tr>
	<tr>
	  <th scope="col">Preis: </th>
	  <th scope="col" style="width:100%"><input type="text" name="item_price[<?=$row["id"]?>]" value="<?=$row["item_price"] ?>" maxlength="5" style="width:100%" /></th>
    </tr>
	<tr>
	  <th scope="col">Tage: </th>
	  <th scope="col" style="width:100%"><input type="text" name="abo_days[<?=$row["id"]?>]" value="<?=$row["abo_days"] ?>" maxlength="3" style="width:100%" /></th>
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
