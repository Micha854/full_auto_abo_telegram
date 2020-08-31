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
<!DOCTYPE html>
<html dir="ltr" lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=$WebsiteTitle ?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="pageHeaderFacade" class="pageHeaderFacade">
			<div class="layoutBoundary">
				<div id="pageHeaderLogo" class="pageHeaderLogo">
					<a href="<?=$WebsiteUrlHeader ?>">
						<img src="<?=$pageHeaderLogoLarge ?>" alt="" class="pageHeaderLogoLarge" style="width: 350px;height: 165px">
						<img src="<?=$pageHeaderLogoSmall ?>" alt="" class="pageHeaderLogoSmall">
					</a>
				</div>
			</div>
		</div>
		<div class="pageNavigation">
			<div class="layoutBoundary">
			</div>
		</div>
		
		<div style="padding-bottom:5px; padding-top:15px; font-size:24px; font-weight:bolder; text-align: center;">Abo</div>
		<div style="padding-bottom:8px; text-align: center;"><?=$header ?></div>
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
				<table class="procut_item">
					<tr>
						<td style="width:70%; vertical-align: top;"><h4><?=$row["months"].$monate?> für <?=$row["item_price"]?> &euro; <span style="font-size:12px">(<?=number_format($row["item_price"]/$row["months"], 2, ',', '.');?> &euro;/monatlich.)</span></h4>(das Abo beginnt mit dem Tag der Zahlung und endet automatisch nach <?=$row["abo_days"]?> Tagen)</td>
						<td style="width:30%;">
							<input type="hidden" name="itemname" value="<?=$row["months"]?> Monat Abo" /> 
		
							<input type="hidden" name="itemnumber" value="<?=$row["item_number"]?>" /> 
							Dein Telegram Username: <br /><input class="input" size="10" type="text" name="itemdesc" value="" required /> <span style="font-size:11px">(beginnend mit @)</span>
		
							<?php if($use_map == "PMSF") { ?>
							<br />Deine eMail: <input class="input" size="10" type="text" name="itemdesc2" value="" />
							<?php } ?> 
		
							<input type="hidden" name="itemprice" value="<?=$row["item_price"]?>" />
							<input type="hidden" name="itemQty" value="1" />
	
							<p>
								<?php
									foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
										?>
										<input id="<?=$row["item_price"].$channel["id"]?>" type="checkbox" name="added[]" value="<?=$channel["id"]?>" checked="checked" /><br /> <label for="<?=$row["item_price"].$channel["id"]?>"><?=$channel["name"]?> beitreten</label>
										<?php
									}
								?>
							</p>
							<p>
								Zahlungsweise:<br>
								<img src="paypal.png" alt="Bezahlung mit PayPal" title="Bezahlung mit PayPal" style="width:102px; height:33px;">
							</p>
							<?php if($showDisclaimer) { ?>
							<p>
								<input id="3disclaimer" type='checkbox' name='disclaimer' required /> <label for="3disclaimer">Ich akzeptiere die Regeln im <a href="disclaimer.php">Disclaimer</a></label>
							</p>
							<?php } ?>
							<p>
								<input class="dw_button" type="submit" name="submitbutt" value="weiter" />
							</p>
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
