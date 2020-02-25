<?php
include_once("config.php");

if($use_map == 'Rocketmap' and !file_exists(__DIR__.'/.htpasswd')) {
$handle = fopen(__DIR__.'/.htpasswd', 'a');
fclose($handle);
}

if(!file_exists(__DIR__.'/admin/msg.php') and file_exists(__DIR__.'/admin/msg_example.php')) {
    copy(__DIR__."/admin/msg_example.php",__DIR__."/admin/msg.php");
}

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
<style type="text/css">
<!--
body{font-family: arial;color: #7A7A7A;margin:0px;padding:0px;}
.procut_item {width: 100%;margin-right: auto;margin-left: auto;padding: 20px;background: #F1F1F1;margin-bottom: 1px;font-size: 12px;border-radius: 5px;text-shadow: 1px 1px 1px #FCFCFC;}
.channel_item {width: 100%;margin-right: auto;margin-left: auto;padding: 20px 0 20px 0;background: #F1F1F1; border-top:solid 2px #00CC00;border-bottom:solid 2px #00CC00;margin-bottom: 1px;font-size: 12px;text-shadow: 1px 1px 1px #FCFCFC; font-weight:bolder}
.procut_item h4 {margin: 0px;padding: 0px;font-size: 20px;}
.channel_item h4 {margin: 0px;padding: 0 0 10px 0;font-size: 14px;}
.input{font-size:22px; padding:1px}
.dw_button{font-size:16px}
-->
</style>
</head>

<body>
<div align="center" style="padding-bottom:5px; padding-top:15px; font-size:24px; font-weight:bolder">ABO</div>
<div align="center" style="padding-bottom:8px; font-size:12px"><?=$header ?></div>
<div class="product_wrapper">

<?php
while($row = $result->fetch_array()) { 
    if($row["months"] > 1) {
        $monate = " Monate ";
    } else {
        $monate = " Monat ";
    }
?>
<form method="post" action="process.php">
<table class="procut_item" border="0" cellpadding="4">
  <tr>
    <td width="70%"><h4><?=$row["months"].$monate?><span style="font-size:12px">(<?=number_format($row["item_price"]/$row["months"], 2, ',', '.');?> &euro;/mtl.)</span></h4>(das Abo beginnt mit dem Tag der Zahlung und endet automatisch nach <?=$row["abo_days"]?> Tagen)</td>
    <td width="30%">
    <input type="hidden" name="itemname" value="<?=$row["months"]?> Monat Abo" /> 
    <input type="hidden" name="itemnumber" value="<?=$row["item_number"]?>" /> 
    Dein Telegram Username: <br /><span style="font-size:11px">beginnend mit @</span> <input class="input" size="10" type="text" name="itemdesc" value="" required />
    
    <?php if($use_map == "PMSF") { ?>
    <br />Deine eMail: <input class="input" size="10" type="text" name="itemdesc2" value="" />
    <?php } ?> 
    
    <input type="hidden" name="itemprice" value="<?=$row["item_price"]?>" />
    <input type="hidden" name="itemQty" value="1" />
    
    <p>
    <?php
        foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
            echo $channel["name"]." beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /><br />";
        }
    ?></p>

    <p><input class="dw_button" type="submit" name="submitbutt" value="PayPal (<?=$row["item_price"]?> EUR)" /></p>
    </td>
  </tr>
</table>
</form>
<?php
}
///$array = implode(',',$_POST["added"]);
//echo '--> '.$array." --> ".$_POST["itemnumber"];
//print_r($_POST["added"]);
?>
</div>
</body>
</html>
