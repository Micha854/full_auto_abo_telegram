<?php
include_once("config.php");

#########################################################################
#//////////////   CONFIG THIS PART   ///////////////////////////////////#
#
$telegramSupport       = 'your support channel';
$telegramSupportTitle  = 'your title of the support channel';
$payPalSpendenLink     = 'your donate link';
#
#########################################################################

?>
<!DOCTYPE html>
<html dir="ltr" lang="de">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		
		<div style="padding-bottom:5px; padding-top:15px; padding-left:20px; padding-right:20px; font-size:24px; font-weight:bold"><?=$organization?> - Spenden Disclaimer</div>
		<div class="product_wrapper" style="margin:0px 20px;">
		<p><i>Version 3 (2020-12-15)</i></p>
		<p>Die <?=$organization?> sowie die damit verbundenen Dienste (Scannen nach Raids, Quests, Team Go Rocket Invasionen, Pokémon+IV, etc.) sind ein <u>Community-Projekt</u>.</p>
		<p>Das Projekt ist <u>nicht profitorientiert</u>:</p>
		<ul>
			<li><b>Finanziellen Aufwendungen</b> (Kosten für Server, Scanner, Lizenzen, Unterstützung von genutzten Dritt-Projekten, etwaig notwendige Entwicklungsarbeiten zum Erhalt der <?=$organization?>, etc.) <b>werden durch Spenden aus der Community getragen</b>.</li>
			<li>Die erheblichen technischen Arbeiten (also die Administration des gesamten Systems) sowie das Hosten der Scanner wird von Freiwilligen aus der Community <b>ohne jedwede Vergütung der Arbeitszeit übernommen</b>.</li>
			<li>Aus den Spenden der Community kann außerdem ein Finanzpolster für den Betrieb von einigen Monaten gebildet werden.</li>
		</ul>
		<p>Teile der Dienste (insbesondere das Scannen nach Raids) sind aufgrund ihrer Natur offen für alle (also auch Nicht-Spender) zugänglich, während andere (zum Beispiel die IV der Pokémon) auf Spender beschränkt sind.</p>
		<p>Als Dankeschön für die Unterstützung gewährt die Community den Spendern zeitweisen Zugang zu den beschränkten Diensten.<br>
		Hierzu ist ein Telegram-Account erforderlich, der beim Spenden angegeben werden muss.</p>
		<p>Spenden erfolgen zu folgenden Sätzen (für neue Spenden, können sich diese bei Bedarf jederzeit ändern):</p>
		<ul>
			<li>3,00 €: Zugang zu den beschränkten Diensten für 1 Monat</li>
			<li>7,50 €: Zugang zu den beschränkten Diensten für 3 Monate</li>
			<li>12,00 €: Zugang zu den beschränkten Diensten für 6 Monate</li>
		</ul>
		<p>Es erfolgt keine automatische Verlängerung.<br>
		Außerdem <u>erfolgt eine Verlängerung frühestens 7 Tage vor Ablauf des jeweils gültigen Zeitraums</u>, so dass beispielsweise zweimal mal kurz hintereinander gespendete 12,00 € nicht zu einem Zugang von 12 Monaten führen.<br>
		Für <b>zusätzliche</b> (also über das entsprechende Feld) <b>oder mehrmalige</b> (also während eines noch laufenden Zeitraums) <b>Spenden</b> wird gedankt; diese werden aber allgemein für die <?=$organization?> verwendet und <b>führen nicht zur Verlängerung des Zeitraums.</b></p>
		<p>Darüber hinaus können auch <a href="<?=$payPalSpendenLink?>" target="_blank">„allgemeine” Spenden</a> geleistet werden, die <b>nicht mit einem Zugang zu den beschränkten Diensten verbunden</b> sind.</p>
		<p><b>Die <?=$organization?> wird von Freiwilligen auf “best effort” Basis betrieben, was heißt, dass keinerlei Ansprüche auf vollständige oder teilweise Erfüllung ihrer Dienste besteht.</b></p>
		<p><b>Insbesondere:</b></p>
		<ul>
			<li>Die <?=$organization?> <u>kann jederzeit vollständig oder teilweise ausfallen</u>, zum Beispiel (aber nicht nur) wegen äußerer Einflüsse, höherer Gewalt, technischer Probleme jedweder Art, Soft- oder Hardware-Ausfälle, Rückzug eines oder mehrerer Administratoren beziehungsweise Scanner-Hoster, gestiegene Kosten für den Betrieb (die nicht mehr durch die Spenden der Community gedeckt werden können), etc..<br>
			Diese <u>Ausfälle können sowohl temporär als auch permanent sein</u>.</li>
			<li><u>Sollte es</u> (aus welchen Gründen auch immer) <u>zu einem permanenten Ausfall und/oder Auflösung der <?=$organization?> kommen, erfolgt keinerlei Rückvergütung oder ähnliches und zwar weder für noch laufende Zeiträume, noch für darüber hinausgehende finanzielle oder Sachspenden.</u><br>
			Die Spender stimmen zu, dass (nach einer optionalen und angemessenen Wartezeit, in der geprüft werden kann ob eine „Wiederbelebung“ möglich wäre) verbleibende Mittel (sowohl finanzielle- als auch Sach-Werte) erst zur Abwicklung und dann als Spende an eine gemeinnützige Organisation verwendet würden.</li>
			<li>Soweit nicht anders vereinbart, gehen finanzielle und Sachspenden (zum Beispiel Scanner) ins Eigentum der Community über, welches durch die Map-Administratoren nach besten Wissen und Gewissen verwaltet wird.<br>
			<u>Ansprüche auf Auszahlung oder Rückerstattung des anteiligen Eigentums bestehen nicht.</u> Wie oben beschrieben, auch nicht im Fall einer etwaigen Auflösung der <?=$organization?>.</li>
			<li>Es gibt <u>keinerlei Garantie, dass die bereitgestellten Daten und Dienste vollständig, richtig oder für irgendeinen Zweck verwendbar sind</u>.<br>
			Das bedeutet beispielsweise, dass Raids, Quests, Team Go Rocket Invasionen, Pokémon+IV, etc. fehlen oder falsch (zum Beispiel (aber nicht nur) die Despawn-Zeiten bei Pokémon) sein können.<br>
			Außerdem gibt es natürlich keine Garantie, dass irgendwelche besonderen oder seltenen Pokémon gefunden werden können.</li>
			<li>Die <u>Auswahl</u> (sowie etwaige Reihenfolge oder Zeiträume) <u>der gescannten Gebiete, Raids, Pokémon, etc. erfolgt allein nach Maßgabe der Map-Administratoren</u>, welche dies soweit möglich im Einklang mit dem größten Nutzen für die Community entscheiden. Gleiches gilt für Änderungen daran.<br>
			Insbesondere <u>entsteht aus der Abgabe einer Spende</u> (auch einer „größeren“) oder durch sonstige Beiträge (wie dem Hosten von Scannern) <u>kein Anspruch auf die Abdeckung eines bestimmten Wunsch-Gebiets</u>.</li>
			<li>Eine etwaige Auflösung der <?=$organization?> wird durch die Map-Administratoren entschieden.</li>
			<li>Die <u>Map-Administratoren behalten sich vor, beschränkte Dienste temporär oder permanent offen für alle zugänglich zu machen</u> (zum Beispiel (aber nicht nur) die IV der Pokémon an Events wie Community Days).<br>
			<u>Ebenso können momentan offen für alle zugängliche Dienste bei Bedarf jederzeit auf Spender beschränkt werden.</u></li>
			<li>Die <u>Weitergabe von Informationen aus den beschränkten Diensten</u> (zum Beispiel IV 100% Pokémon und deren Position) <u>an Nicht-Spender oder deren Veröffentlichung</u> widerspricht dem Community-Gedanken hinter dem Projekt und <u>kann grundsätzlich (und bei Bedarf auch ohne Vorwarnung) zu einem temporären oder permanenten Ausschluss von der <?=$organization?> oder den damit verbundenen Gruppen (zum Beispiel die Telegram Gruppen) führen</u>.<br>
			<u>Eine Rückvergütung oder ähnliches würde in diesem Fall nicht erfolgen.</u></li>
			<li>Die <?=$organization?> wird über Standard-Technologien (Web-Dienste) und Telegram zur Verfügung gestellt. Die notwendigen Mittel (zum Beispiel Smartphone, Internet-Zugang) sind vom Nutzer aufzubringen.<br>
			Es sei auch darauf hingewiesen, dass sich Technologien weiterentwickeln oder durch andere ersetzt werden können. Ein Anspruch auf Unveränderlichkeit besteht nicht.</li>
			<li>Datenschutz<br>
			Von Seiten der <?=$organization?> werden nur jene Daten erhoben und verarbeitet, die für den technischen Betrieb (zum Beispiel IP-Adressen auf Server-Logs oder Cookies für die Einstellungen der Web-Dienste) sowie Abwicklung der Spenden-Zeiträume (Telegram-Benutzername und Laufzeit) erforderlich sind.<br>
			Eine <u>Löschung dieser Daten kann in der Telegram-Gruppe <a href="<?=$telegramSupport?>" target="_blank"><?=$telegramSupportTitle?></a> beantragt werden</u>, bedeutet aber auch den Ausschluss von den Diensten.<br>
			Bezüglich Telegram und PayPal wird auf deren Datenschutz-Dokumente verwiesen.</li>
		</ul>
		<p>Bei Fragen oder Problemen kann man sich an die Telegram-Gruppe <a href="<?=$telegramSupport?>" target="_blank"><?=$telegramSupportTitle?></a> wenden.<br>
		Es handelt sich aber auch hierbei um einen “best effort” Dienst und insbesondere besteht kein Anspruch auf zeitnahe Rückantwort oder erfolgreiche Problemlösung.</p>
		</div>
	</body>
</html>
