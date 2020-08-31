<?php
include_once("config.php");

#########################################################################
#//////////////   CONFIG THIS PART   ///////////////////////////////////#
#
$organization          = 'your organization';
$telegramSupport       = 'your support channel';
$payPalSpendenLink     = 'your donate link';
#
#########################################################################

?>
<!DOCTYPE html>
<html dir="ltr" lang="de">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		padding: 20px;
		background: #F1F1F1;
		margin-bottom: 1px;
		border-radius: 5px;
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

<div style="padding-bottom:5px; padding-top:15px; padding-left:20px; padding-right:20px; font-size:24px; font-weight:bolder"><?=$organization ?> - Spenden Disclaimer</div>
<div class="product_wrapper">
Version 1 (2020-08-23)<br>
<br>
<br>
<br>
Die <?=$organization?> Map sowie die damit verbundenen Dienste (Scannen nach Raids, Quests, Team Go Rocket Invasionen, Pok&eacute;mon+IV, etc.) sind ein <u>Community-Projekt.</u><br>
<br>
<br>
Das Projekt ist <u>nicht profitorientiert</u>:
<ul>
	<li><b>Finanziellen Aufwendungen</b> (Kosten f&uuml;r Server, Scanner, Lizenzen, Unterst&uuml;tzung von genutzten Dritt-Projekten, etwaig notwendige Entwicklungsarbeiten zum Erhalt der <?=$organization?> Map, etc.) <b>werden durch Spenden aus der Community getragen.</b></li>
	<li>Die erheblichen technischen Arbeiten (also die Administration des gesamten Systems) sowie das Hosten der Scanner wird von Freiwilligen aus der Community <b>ohne jedwede Verg&uuml;tung der Arbeitszeit &uuml;bernommen.</b></li>
	<li>Aus den Spenden der Community kann au&szlig;erdem ein Finanzpolster f&uuml;r den Betrieb von einigen Monaten gebildet werden.</li>
</ul>
<br>
Teile der Dienste (insbesondere das Scannen nach Raids) sind aufgrund ihrer Natur offen f&uuml;r alle (also auch Nicht-Spender) zug&auml;nglich, w&auml;hrend andere (zum Beispiel die IV der Pok&eacute;mon) auf Spender beschr&auml;nkt sind.<br>
<br>
Als Dankesch&ouml;n f&uuml;r die Unterst&uuml;tzung gew&auml;hrt die Community den Spendern zeitweisen Zugang zu den beschr&auml;nkten Diensten.<br>
Hierzu ist ein Telegram-Account erforderlich, der beim Spenden angegeben werden muss.<br>
<br>
<br>
Spenden erfolgen zu folgenden S&auml;tzen (f&uuml;r neue Spenden, k&ouml;nnen sich diese bei Bedarf jederzeit &auml;ndern):<br>
3,00 &euro;: Zugang zu den beschr&auml;nkten Diensten f&uuml;r 1 Monat<br>
7,50 &euro;: Zugang zu den beschr&auml;nkten Diensten f&uuml;r 3 Monate<br>
12,00 &euro;: Zugang zu den beschr&auml;nkten Diensten f&uuml;r 6 Monate<br>
 <br>
Es erfolgt keine automatische Verl&auml;ngerung.<br>
Au&szlig;erdem <u>erfolgt eine Verl&auml;ngerung fr&uuml;hestens 7 Tage vor Ablauf des jeweils g&uuml;ltigen Zeitraums</u>, so dass beispielsweise zweimal mal kurz hintereinander gespendete 12,00 &euro; nicht zu einem Zugang von 12 Monaten f&uuml;hren.<br>
F&uuml;r <b>mehrmalige Spenden w&auml;hrend eines noch laufenden Zeitraums</b> wird gedankt; diese werden aber allgemein f&uuml;r die <?=$organization?> Map verwendet und <b>f&uuml;hren nicht zur Verl&auml;ngerung des Zeitraums.</b><br>
<br>
Dar&uuml;ber hinaus k&ouml;nnen auch <a href="<?=$payPalSpendenLink?>" target="_blank">&#8222;allgemeine&#8220; Spenden</a> geleistet werden, die <b>nicht mit einem Zugang zu den beschr&auml;nkten Diensten verbunden sind.</b><br>
<br>
<br>
<b>Die <?=$organization?> Map wird von Freiwilligen auf "best effort" Basis betrieben, was hei&szlig;t, dass keinerlei Anspr&uuml;che auf vollst&auml;ndige oder teilweise Erf&uuml;llung ihrer Dienste besteht.</b><br>
<br>
<b>Insbesondere:</b><br>
<ul>
	<li>Die <?=$organization?> Map <u>kann jederzeit vollst&auml;ndig oder teilweise ausfallen</u>, zum Beispiel (aber nicht nur) wegen &auml;u&szlig;erer Einfl&uuml;sse, h&ouml;herer Gewalt, technischer Probleme jedweder Art, Soft- oder Hardware-Ausf&auml;lle, R&uuml;ckzug eines oder mehrerer Administratoren beziehungsweise Scanner-Hoster, gestiegene Kosten f&uuml;r den Betrieb (die nicht mehr durch die Spenden der Community gedeckt werden k&ouml;nnen), etc..<br>
	Diese <u>Ausf&auml;lle k&ouml;nnen sowohl tempor&auml;r als auch permanent sein</u>.</li>
	<li><u>Sollte es</u> (aus welchen Gr&uuml;nden auch immer) <u>zu einem permanenten Ausfall und/oder Aufl&ouml;sung der <?=$organization?> Map kommen, erfolgt keinerlei R&uuml;ckverg&uuml;tung oder &auml;hnliches und zwar weder f&uuml;r noch laufende Zeitr&auml;ume, noch f&uuml;r dar&uuml;ber hinausgehende finanzielle oder Sachspenden.</u><br>
	Die Spender stimmen zu, dass (nach einer optionalen und angemessenen Wartezeit, in der gepr&uuml;ft werden kann ob eine &#8222;Wiederbelebung&#8220; m&ouml;glich w&auml;re) verbleibende Mittel (sowohl finanzielle- als auch Sach-Werte) erst zur Abwicklung und dann als Spende an eine gemeinn&uuml;tzige Organisation verwendet w&uuml;rden.</li>
	<li>Soweit nicht anders vereinbart, gehen finanzielle und Sachspenden (zum Beispiel Scanner) ins Eigentum der Community &uuml;ber, welches durch die Map-Administratoren nach besten Wissen und Gewissen verwaltet wird.<br>
	<u>Anspr&uuml;che auf Auszahlung oder R&uuml;ckerstattung des anteiligen Eigentums bestehen nicht.</u> Wie oben beschrieben, auch nicht im Fall einer etwaigen Aufl&ouml;sung der <?=$organization?> Map.</li>
	<li>Es gibt <u>keinerlei Garantie, dass die bereitgestellten Daten und Dienste vollst&auml;ndig, richtig oder f&uuml;r irgendeinen Zweck verwendbar sind</u>.<br>
	Das bedeutet beispielsweise, dass Raids, Quests, Team Go Rocket Invasionen, Pok&eacute;mon+IV, etc. fehlen oder falsch (zum Beispiel (aber nicht nur) die Despawn-Zeiten bei Pok&eacute;mon) sein k&ouml;nnen.<br>
	Au&szlig;erdem gibt es nat&uuml;rlich keine Garantie, dass irgendwelche besonderen oder seltenen Pok&eacute;mon gefunden werden k&ouml;nnen.</li>
	<li>Die <u>Auswahl</u> (sowie etwaige Reihenfolge oder Zeitr&auml;ume) <u>der gescannten Gebiete, Raids, Pok&eacute;mon erfolgt allein nach Ma&szlig;gabe der Map-Administratoren</u>, welche dies soweit m&ouml;glich im Einklang mit dem gr&ouml;&szlig;ten Nutzen f&uuml;r die Community entscheiden. Gleiches gilt f&uuml;r &Auml;nderungen daran.<br>
	Insbesondere <u>entsteht aus der Abgabe einer Spende</u> (auch einer &#8222;gr&ouml;&szlig;eren&#8220;) oder durch sonstige Beitr&auml;ge (wie dem Hosten von Scannern) <u>kein Anspruch auf die Abdeckung eines bestimmten Wunsch-Gebiets</u>.</li>
	<li>Eine etwaige Aufl&ouml;sung der <?=$organization?> Map wird durch die Map-Administratoren entschieden.</li>
	<li>Die <u>Map-Administratoren behalten sich vor, beschr&auml;nkte Dienste tempor&auml;r oder permanent offen f&uuml;r alle zug&auml;nglich zu machen</u> (zum Beispiel (aber nicht nur) die IV der Pok&eacute;mon an Events wie Community Days).<br>
	<u>Ebenso k&ouml;nnen momentan offen f&uuml;r alle zug&auml;ngliche Dienste bei Bedarf jederzeit auf Spender beschr&auml;nkt werden.</u></li>
	<li>Die <u>Weitergabe von Informationen aus den beschr&auml;nkten Diensten</u> (zum Beispiel IV 100% Pok&eacute;mon und deren Position) <u>an Nicht-Spender oder deren Ver&ouml;ffentlichung</u> widerspricht dem Community-Gedanken hinter dem Projekt und <u>kann grunds&auml;tzlich (und bei Bedarf auch ohne Vorwarnung) zu einem tempor&auml;ren oder permanenten Ausschluss von der <?=$organization?> Map oder den damit verbundenen Gruppen (zum Beispiel die Telegram Gruppen) f&uuml;hren.</u><br>
	<u>Eine R&uuml;ckverg&uuml;tung oder &auml;hnliches w&uuml;rde in diesem Fall nicht erfolgen.</u></li>
	<li>Die <?=$organization?> Map wird &uuml;ber Standard-Technologien (Web-Dienste) und Telegram zur Verf&uuml;gung gestellt. Die notwendigen Mittel (zum Beispiel Smartphone, Internet-Zugang) sind vom Nutzer aufzubringen.<br>
	Es sei auch darauf hingewiesen, dass sich Technologien weiterentwickeln oder durch andere ersetzt werden k&ouml;nnen. Ein Anspruch auf Unver&auml;nderlichkeit besteht nicht.</li>
	<li>Datenschutz<br>
	Von Seiten der <?=$organization?> Map werden nur jene Daten erhoben und verarbeitet, die f&uuml;r den technischen Betrieb (zum Beispiel IP-Adressen auf Server-Logs oder Cookies f&uuml;r die Einstellungen der Web-Dienste) sowie Abwicklung der Spenden-Zeitr&auml;ume (Telegram-Benutzername und Laufzeit) erforderlich sind.<br>
	Eine <u>L&ouml;schung dieser Daten kann in der <a href="<?=$telegramSupport?>" target="_blank">Telegram-Gruppe <?=$organization?> Support</a> beantragt werden</u>, bedeutet aber auch den Ausschluss von den Diensten.</li>
</ul>
<br>
Bez&uuml;glich Telegram und PayPal wird auf deren Datenschutz-Dokumente verwiesen.<br>
<br>
<br>
Bei Fragen oder Problemen kann man sich an die <a href="<?=$telegramSupport?>" target="_blank">Telegram-Gruppe <?=$organization?> Support</a> wenden.<br>
Es handelt sich aber auch hierbei um einen "best-effort" Dienst und insbesondere besteht kein Anspruch auf zeitnahe R&uuml;ckantwort oder erfolgreiche Probleml&ouml;sung.<br>
<br>
</div>
</body>
</html>