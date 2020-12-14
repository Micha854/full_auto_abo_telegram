// Mysql Database, Name der Tabelle
include_once("config.php");
$mysqli = new mysqli('localhost','DBuser','DBpass','database');

$tbl    = "abos";
#########################################################################

#//////////////   CONFIG THIS PART   ///////////////////////////////////#

#
// Website- Url, Titel, Header
$telegramSupport       = 'your support channel';
$WebsiteUrl           = "https://YOUR_DOMAIN.COM";
$payPalSpendenLink     = 'your donate link';
$WebsiteUrlHeader     = $WebsiteUrl; // If you want to link another Website in the header image
#
$WebsiteTitle         = "Pogo Dein Stadtteil";
#########################################################################
$pageHeaderLogoLarge  = "logo.png";

$pageHeaderLogoSmall  = "logosmall.png";
?>
$header               = "für den DEIN STADTTEIL Mon IV - Telegram Kanal (nur Innenstadt). Du wirst dem Kanal automatisch nach deiner Zahlung hinzugefügt!";
<!DOCTYPE html>
$organization         = 'your organization';
<html dir="ltr" lang="de">
$showDisclaimer       = false;  // to use disclaimer set it true, rename the disclaimer_example.php to disclaimer.php and config the up part in the file !!!
	<head>
$AccessAllChannels    = false;  // set true to Access for all Channels or false(default) for costum handling
		<meta charset="UTF-8">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title><?=$WebsiteTitle ?></title>
// API Server URL, bot token, Admins
		<link rel="stylesheet" type="text/css" href="style.css">
$apiServer      = "http://YOURURL.COM:9503/api/";
	</head>
$BotToken	      = "123456789:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";	// without "_bot" first !!!

$admins         = "Admin_1, Admin_2";           // send a message to Telegram admins when a subscription is completed or renewed
	<body>

		<div id="pageHeaderFacade" class="pageHeaderFacade">

			<div class="layoutBoundary">
// Pogo Map Config
				<div id="pageHeaderLogo" class="pageHeaderLogo">
$urlMap         = "http://YOURURL.COM/map/";    // Url to your pogo map
					<a href="<?=$WebsiteUrlHeader ?>">
$use_map        = "PMSF";                       // settings --> "PMSF" or "Rocketmap" or "" for empty
						<img src="<?=$pageHeaderLogoLarge ?>" alt="" class="pageHeaderLogoLarge" style="width: 350px;height: 165px">
$login_system   = "native";                     // login type
						<img src="<?=$pageHeaderLogoSmall ?>" alt="" class="pageHeaderLogoSmall">
$access_level   = "1";                          // access level for user
					</a>
$maxAboLength   = 0;                            // Maximal days of abo length (0 if unrestricted)
				</div>

			</div>

		</div>
// Mail Config
		<div class="pageNavigation">
$botSend        = '1';					          // send message to user from your bot
			<div class="layoutBoundary">
$mailSend       = '0';                    // set value '0' to off
			</div>

		</div>
$sequenceInfo   = '3';					          // x Tage vor Ablauf des Abos wird der User informiert || zum deaktivieren = "0"

$ergebnisse_pro_seite = 50;				        // x Abos pro Seite anzeigen || Admin Panel || default value 50
		<div style="padding-bottom:5px; padding-top:15px; padding-left:20px; padding-right:20px; font-size:24px; font-weight:bold"><?=$organization?> - Spenden Disclaimer</div>

		<div class="product_wrapper" style="margin:0px 20px;">
$mailSubject    = "Welcome to PoGO MAP";  // mail betreff
		<p><i>Version 3 (2020-12-15)</i></p>
$mailSender     = 'pogo@YOURURL.COM';     // sender mail || ReplyTo
		<p>Die <?=$organization?> sowie die damit verbundenen Dienste (Scannen nach Raids, Quests, Team Go Rocket Invasionen, Pokémon+IV, etc.) sind ein <u>Community-Projekt</u>.</p>
$mailHost       = 'domain.com';           // SMTP server
		<p>Das Projekt ist <u>nicht profitorientiert</u>:</p>
$smtpPort       = 25;                     // usually the port for TLS is 587, for SSL is 465 and non-secure is 25
		<ul>
$smtpSecure     = '';				          	  // TLS, SSL or  empty the line
			<li><b>Finanziellen Aufwendungen</b> (Kosten für Server, Scanner, Lizenzen, Unterstützung von genutzten Dritt-Projekten, etwaig notwendige Entwicklungsarbeiten zum Erhalt der <?=$organization?>, etc.) <b>werden durch Spenden aus der Community getragen</b>.</li>
$smtpUser       = '';                     // SMTP account username
			<li>Die erheblichen technischen Arbeiten (also die Administration des gesamten Systems) sowie das Hosten der Scanner wird von Freiwilligen aus der Community <b>ohne jedwede Vergütung der Arbeitszeit übernommen</b>.</li>
$smtpPass       = '';                     // SMTP account password
			<li>Aus den Spenden der Community kann außerdem ein Finanzpolster für den Betrieb von einigen Monaten gebildet werden.</li>

		</ul>

		<p>Teile der Dienste (insbesondere das Scannen nach Raids) sind aufgrund ihrer Natur offen für alle (also auch Nicht-Spender) zugänglich, während andere (zum Beispiel die IV der Pokémon) auf Spender beschränkt sind.</p>
//PayPal Config
		<p>Als Dankeschön für die Unterstützung gewährt die Community den Spendern zeitweisen Zugang zu den beschränkten Diensten.<br>
$paypallogo     = $WebsiteUrl."images/logo_example.jpg";	// PayPal Logo
		Hierzu ist ein Telegram-Account erforderlich, der beim Spenden angegeben werden muss.</p>
//$paypallogo   = ""; 							                // kein Logo anzeigen
		<p>Spenden erfolgen zu folgenden Sätzen (für neue Spenden, können sich diese bei Bedarf jederzeit ändern):</p>

		<ul>
$PayPalMode     = 'live';                           // sandbox or live
			<li>4,00 €: Zugang zu den beschränkten Diensten für 1 Monat</li>

			<li>11,00 €: Zugang zu den beschränkten Diensten für 3 Monate</li>
if($PayPalMode == 'live') {								          // live mode
		</ul>
  $PayPalApiUsername 	= 'YOUR_API_USERNAME';        // PayPal API Username
		<p>Es erfolgt keine automatische Verlängerung.<br>
  $PayPalApiPassword 	= 'YOUR_API_PASSWORD';        // Paypal API password
		Außerdem <u>erfolgt eine Verlängerung frühestens 7 Tage vor Ablauf des jeweils gültigen Zeitraums</u>, so dass beispielsweise zweimal mal kurz hintereinander gespendete 11,00 € nicht zu einem Zugang von 6 Monaten führen.<br>
  $PayPalApiSignature = 'YOUR_API_SIGNATURE';       // Paypal API Signature
		Für <b>zusätzliche</b> (also über das entsprechende Feld) <b>oder mehrmalige</b> (also während eines noch laufenden Zeitraums) <b>Spenden</b> wird gedankt; diese werden aber allgemein für die <?=$organization?> verwendet und <b>führen nicht zur Verlängerung des Zeitraums.</b></p>
} elseif($PayPalMode == 'sandbox') {					      // sandbox mode
		<p>Darüber hinaus können auch <a href="<?=$payPalSpendenLink?>" target="_blank">„allgemeine” Spenden</a> geleistet werden, die <b>nicht mit einem Zugang zu den beschränkten Diensten verbunden</b> sind.</p>
  $PayPalApiUsername 	= 'YOUR_API_USERNAME';        // PayPal API Username
		<p><b>Die <?=$organization?> wird von Freiwilligen auf “best effort” Basis betrieben, was heißt, dass keinerlei Ansprüche auf vollständige oder teilweise Erfüllung ihrer Dienste besteht.</b></p>
  $PayPalApiPassword 	= 'YOUR_API_PASSWORD';        // Paypal API password
		<p><b>Insbesondere:</b></p>
  $PayPalApiSignature = 'YOUR_API_SIGNATURE';       // Paypal API Signature
		<ul>
} else {
			<li>Die <?=$organization?> <u>kann jederzeit vollständig oder teilweise ausfallen</u>, zum Beispiel (aber nicht nur) wegen äußerer Einflüsse, höherer Gewalt, technischer Probleme jedweder Art, Soft- oder Hardware-Ausfälle, Rückzug eines oder mehrerer Administratoren beziehungsweise Scanner-Hoster, gestiegene Kosten für den Betrieb (die nicht mehr durch die Spenden der Community gedeckt werden können), etc..<br>
  echo '<h1 style="text-align:center;background:#FFFF00;padding:5px">Paypal Mode Error - options "live" or "sandbox" Check this!</h1>';
			Diese <u>Ausfälle können sowohl temporär als auch permanent sein</u>.</li>
  exit();
			<li><u>Sollte es</u> (aus welchen Gründen auch immer) <u>zu einem permanenten Ausfall und/oder Auflösung der <?=$organization?> kommen, erfolgt keinerlei Rückvergütung oder ähnliches und zwar weder für noch laufende Zeiträume, noch für darüber hinausgehende finanzielle oder Sachspenden.</u><br>
}
			Die Spender stimmen zu, dass (nach einer optionalen und angemessenen Wartezeit, in der geprüft werden kann ob eine „Wiederbelebung“ möglich wäre) verbleibende Mittel (sowohl finanzielle- als auch Sach-Werte) erst zur Abwicklung und dann als Spende an eine gemeinnützige Organisation verwendet würden.</li>

			<li>Soweit nicht anders vereinbart, gehen finanzielle und Sachspenden (zum Beispiel Scanner) ins Eigentum der Community über, welches durch die Map-Administratoren nach besten Wissen und Gewissen verwaltet wird.<br>
$PayPalCurrencyCode = 'EUR';                        // Paypal Currency Code
			<u>Ansprüche auf Auszahlung oder Rückerstattung des anteiligen Eigentums bestehen nicht.</u> Wie oben beschrieben, auch nicht im Fall einer etwaigen Auflösung der <?=$organization?>.</li>

			<li>Es gibt <u>keinerlei Garantie, dass die bereitgestellten Daten und Dienste vollständig, richtig oder für irgendeinen Zweck verwendbar sind</u>.<br>
$PayPalReturnURL  = $WebsiteUrl.'/process.php';     // Point to process.php page
			Das bedeutet beispielsweise, dass Raids, Quests, Team Go Rocket Invasionen, Pokémon+IV, etc. fehlen oder falsch (zum Beispiel (aber nicht nur) die Despawn-Zeiten bei Pokémon) sein können.<br>
$PayPalCancelURL  = $WebsiteUrl.'/index.php';       // Cancel URL if user clicks cancel
			Außerdem gibt es natürlich keine Garantie, dass irgendwelche besonderen oder seltenen Pokémon gefunden werden können.</li>

			<li>Die <u>Auswahl</u> (sowie etwaige Reihenfolge oder Zeiträume) <u>der gescannten Gebiete, Raids, Pokémon, etc. erfolgt allein nach Maßgabe der Map-Administratoren</u>, welche dies soweit möglich im Einklang mit dem größten Nutzen für die Community entscheiden. Gleiches gilt für Änderungen daran.<br>

			Insbesondere <u>entsteht aus der Abgabe einer Spende</u> (auch einer „größeren“) oder durch sonstige Beiträge (wie dem Hosten von Scannern) <u>kein Anspruch auf die Abdeckung eines bestimmten Wunsch-Gebiets</u>.</li>
$DEBUG = 0;                                         // debug_mode, 0 OR 1
			<li>Eine etwaige Auflösung der <?=$organization?> wird durch die Map-Administratoren entschieden.</li>

			<li>Die <u>Map-Administratoren behalten sich vor, beschränkte Dienste temporär oder permanent offen für alle zugänglich zu machen</u> (zum Beispiel (aber nicht nur) die IV der Pokémon an Events wie Community Days).<br>

			<u>Ebenso können momentan offen für alle zugängliche Dienste bei Bedarf jederzeit auf Spender beschränkt werden.</u></li>

			<li>Die <u>Weitergabe von Informationen aus den beschränkten Diensten</u> (zum Beispiel IV 100% Pokémon und deren Position) <u>an Nicht-Spender oder deren Veröffentlichung</u> widerspricht dem Community-Gedanken hinter dem Projekt und <u>kann grundsätzlich (und bei Bedarf auch ohne Vorwarnung) zu einem temporären oder permanenten Ausschluss von der <?=$organization?> oder den damit verbundenen Gruppen (zum Beispiel die Telegram Gruppen) führen</u>.<br>

			<u>Eine Rückvergütung oder ähnliches würde in diesem Fall nicht erfolgen.</u></li>

			<li>Die <?=$organization?> wird über Standard-Technologien (Web-Dienste) und Telegram zur Verfügung gestellt. Die notwendigen Mittel (zum Beispiel Smartphone, Internet-Zugang) sind vom Nutzer aufzubringen.<br>

			Es sei auch darauf hingewiesen, dass sich Technologien weiterentwickeln oder durch andere ersetzt werden können. Ein Anspruch auf Unveränderlichkeit besteht nicht.</li>

			<li>Datenschutz<br>

			Von Seiten der <?=$organization?> werden nur jene Daten erhoben und verarbeitet, die für den technischen Betrieb (zum Beispiel IP-Adressen auf Server-Logs oder Cookies für die Einstellungen der Web-Dienste) sowie Abwicklung der Spenden-Zeiträume (Telegram-Benutzername und Laufzeit) erforderlich sind.<br>

			Eine <u>Löschung dieser Daten kann in der Telegram-Gruppe <a href="<?=$telegramSupport?>" target="_blank"><?=$organization?> Support</a> beantragt werden</u>, bedeutet aber auch den Ausschluss von den Diensten.<br>

			Bezüglich Telegram und PayPal wird auf deren Datenschutz-Dokumente verwiesen.</li>

		</ul>

		<p>Bei Fragen oder Problemen kann man sich an die Telegram-Gruppe <a href="<?=$telegramSupport?>" target="_blank"><?=$organization?> Support</a> wenden.<br>

		Es handelt sich aber auch hierbei um einen “best effort” Dienst und insbesondere besteht kein Anspruch auf zeitnahe Rückantwort oder erfolgreiche Problemlösung.</p>

		</div>

	</body>

</html>




/*************** NO CHANGE ****************/

if($DEBUG == 1) {
  print_r('<h1 style="text-align:center;background:#FFFF00;padding:5px">DEBUG MODE = ON</h1>');
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
}

$apicall = file_get_contents($apiServer."getSelf");
if (strpos($_SERVER['SCRIPT_NAME'], 'admin') !== false && $apicall == false) {
    echo '<h1 style="text-align:center;background:#FFFF00;padding:5px">WARNING: your apiServer is not running !!!</h1>';

}

// SET DEFAULT CHARSETS TO UTF-8
mysqli_query($mysqli, "SET NAMES 'utf8'");
header("Content-Type: text/html; charset=utf-8");

// DEBUGGING
class Logger
{
  private static function addEntry($str)
  {
    $t = microtime(true);
  $micro = sprintf("%06d",($t - floor($t)) * 1000000);
  $handle = fopen(__DIR__.'/admin/log.txt', 'a');
    fwrite($handle, sprintf("%s %s\n", "[".date("m-d H:i:s.".$micro,$t)."]", $str));
    fclose($handle);
  }

  public static function warn($str)
  {
    self::addEntry("[\tWARNING] [\t\t". $_SERVER['SCRIPT_NAME'] ."] \t$str");
  }

  public static function error($str)
  {
    self::addEntry("[\tERROR] [\t\t". $_SERVER['SCRIPT_NAME'] ."] \t$str");
  }

  public static function info($str)
  {
    self::addEntry("[\tINFO] [\t\t". $_SERVER['SCRIPT_NAME'] ."] \t$str");
  }
}
?> 
