<?php
include_once("config.php");

$query = "SELECT * FROM products ORDER BY id ASC";
$result = $mysqli->query($query);

$query_cha = "SELECT * FROM channels ORDER BY name ASC";
$result_cha = $mysqli->query($query_cha);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title><?=$WebsiteTitle ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
   <main role="main" class="container">
<p>
<div align="center" style="padding-bottom:5px; padding-top:15px; font-size:24px; font-weight:bolder">ABO</div>
<div align="center" style="padding-bottom:8px; font-size:12px"><?=$header ?></div>
<div class="product_wrapper">
<?php
while($row = $result->fetch_array()) { ?>
<table class="table" border="0" cellpadding="4">
  <tr>
    <td valign="middel" scope=".col-xs-"><h4><?=$row["months"]?> Monat <span style="font-size:12px">(<?=number_format($row["item_price"]/$row["months"], 2, ',', '.');?> &euro;/mtl.)</span></h4>
	<span style="font-size:11px">(das Abo beginnt mit dem Tag der Zahlung und endet automatisch nach <?=$row["abo_days"]?> Tagen)</span><p><br></th>
    <form method="post" action="process.php">
	<input type="hidden" name="itemname" value="<?=$row["months"]?> Monat Abo" /> 
	<input type="hidden" name="itemnumber" value="<?=$row["item_number"]?>" /> 
    Dein Telegram Username: <br /><span style="font-size:11px">beginnend mit @ (<a target="_blank" href="https://telegram.org/faq/de#benutzernamen">Du bist unsicher?</a>)<br></span><p>
		<input class="form-control" size="10" type="text" name="itemdesc" value="" required /><br>
	
	<?php if($use_map == "PMSF") { ?>
	Deine eMail: <br><input class="form-control" size="10" type="text" name="itemdesc2" value="" required /><p>
	<?php } ?> 
	
	<input type="hidden" name="itemprice" value="<?=$row["item_price"]?>" />
    <input type="hidden" name="itemQty" value="1" />
	<p>
	<?php
		foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
    		echo $channel["name"]." beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' />";
		}
	?>
	<center><input class="btn btn-success" type="submit" name="submitbutt" value="PayPal (<?=$row["item_price"]?> EUR)" /></center>
    </form>
    </td>
  </tr>
</table>
<?php
}
?>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
