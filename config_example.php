<?php
// Mysql Database, Name der Tabelle
$mysqli = new mysqli('localhost','DBuser','DBpass','database');
$tbl    = "abos";


// Website- Url, Titel, Header
$WebsiteUrl     = "https://YOUR_DOMAIN.COM";
$WebsiteTitle   = "Pogo Dein Stadtteil";
$header         = "f&uuml;r den DEIN STADTTEIL Mon IV - Telegram Kanal (nur Innenstadt). Du wirst dem Kanal automatisch nach deiner Zahlung hinzugef&uuml;gt!";


// API Server URL
$apiServer      = "http://YOURURL.COM:9503/api/";


// Pogo Map Config
$urlMap         = "http://YOURURL.COM/map/";    // Url to your pogo map
$use_map        = "PMSF";                       // settings --> "PMSF" or "Rocketmap" or "" for empty
$login_system   = "native";                     // login type
$access_level   = "1";                          // access level for user


// Mail Config
$botSend        = '1';					// send message to user from your bot
$mailSend       = '0';                  // set value '0' to off
$mailSubject    = "Welcome to PoGO MAP";// mail betreff
$mailSender     = 'pogo@YOURURL.COM';   // sender mail || ReplyTo
$mailHost       = 'domain.com';         // SMTP server
$smtpPort       = 25;                   // default 25
$smtpUser       = '';                   // SMTP account username
$smtpPass       = '';                   // SMTP account password


//PayPal Config
$paypallogo         = $WebsiteUrl."/logo_example.jpg";	// PayPal Logo
//$paypallogo       = ""; 							    // kein Logo anzeigen

$PayPalMode 		= 'live';                           // sandbox or live

$PayPalApiUsername 	= 'YOUR_API_USERNAME';              // PayPal API Username
$PayPalApiPassword 	= 'YOUR_API_PASSWORD';              // Paypal API password
$PayPalApiSignature = 'YOUR_API_SIGNATURE';             // Paypal API Signature

$PayPalCurrencyCode = 'EUR';                            // Paypal Currency Code

$PayPalReturnURL 	= $WebsiteUrl.'/process.php';       // Point to process.php page
$PayPalCancelURL 	= $WebsiteUrl.'/index.php';         // Cancel URL if user clicks cancel










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