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
	<style>
	<!--
	body{
		font-family: "Open Sans", Arial, Helvetica, sans-serif;
		color: #7A7A7A;
		margin:0px;
		padding:0px;
		font-size: 13px;
	}

	.procut_item {
		width: 100%;
		margin-right: auto;
		margin-left: auto;
		padding-left: 20px;
		padding-right: 20px;
		padding-top: 10px;
		padding-bottom: 0px;
		background: #F1F1F1;
		margin-bottom: 1px;
	}

	.channel_item {width: 100%;margin-right: auto;margin-left: auto;padding: 20px 0 20px 0;background: #F1F1F1; border-top:solid 2px #00CC00;border-bottom:solid 2px #00CC00;margin-bottom: 1px;font-size: 12px; font-weight:bolder}
	.procut_item h4 {margin: 0px;padding: 0px;font-size: 20px;}
	.channel_item h4 {margin: 0px;padding: 0 0 10px 0;font-size: 14px;}
	.input{font-size:22px; padding:1px}
	.dw_button{font-size:16px}

	a {
		color: rgb(61, 12, 234);
		text-decoration: underline;
	}

	a:hover {
		color: rgb(54, 36, 117);
		text-decoration: underline;
	}

	.pageHeaderFacade {
		background-image: url("header.png");
		background-color: rgba(58, 109, 156, 1);
		background-size: cover;
		background-position: center top;
		background-repeat: no-repeat;
		min-height: 260px;
		height: 260px;
		max-height: 260px;
	}

	.layoutBoundary {
		min-width: 90%;
		width: 90%;
		max-width: 90%;
		padding: 0px 0px;
	}

	.pageHeaderLogo {
		height: 30px !important;
		width: 100% !important;
		text-align: center;
	}

	@media screen and (max-width:1024px){
		.pageHeaderLogo .pageHeaderLogoLarge{
			display:none
		}

		.pageHeaderLogo .pageHeaderLogoSmall{
			max-height:30px;max-width:100%
		}
	}

	@media screen and (min-width:1025px),print{
		.pageHeaderLogo{flex:1 1 auto}.pageHeaderLogo .pageHeaderLogoLarge{
			max-width:100%
		}

		.pageHeaderLogo .pageHeaderLogoSmall{
			display:none
		}

		.pageHeaderLogo > a{
			display:block;padding:10px 0
		}
	}

	.pageNavigation {
		background-color: rgba(58, 109, 156, 1);
		flex: 0 0 auto;
		padding: 0px 0px;
		min-width: 100%;
		max-width: 100%;
		height: 40px;
	}
	-->
	</style>
</head>
<body>
	<div id="pageHeaderFacade" class="pageHeaderFacade">
		<div class="layoutBoundary">
			<div id="pageHeaderLogo" class="pageHeaderLogo">
				<a href="<?=$WebsiteUrl ?>">
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
                <td style="width:70%; vertical-align: top;"><h4><?=$row["months"].$monate?> für <?=$row["item_price"]? &euro; <span style="font-size:12px">(<?=number_format($row["item_price"]/$row["months"], 2, ',', '.');?> &euro;/monatlich.)</span></h4>(das Abo beginnt mit dem Tag der Zahlung und endet automatisch nach <?=$row["abo_days"]?> Tagen)</td>
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
