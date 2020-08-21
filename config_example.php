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
$maxAboLength   = 0;                            // Maximal days of abo length (0 if unrestricted)


// Mail Config
$botSend        = '1';					          // send message to user from your bot
$mailSend       = '0';                    // set value '0' to off

$sequenceInfo	= '3';					            // x Tage vor Ablauf des Abos wird der User informiert || zum deaktivieren = "0"
$ergebnisse_pro_seite = 50;				        // x Abos pro Seite anzeigen || Admin Panel || default value 50

$mailSubject    = "Welcome to PoGO MAP";  // mail betreff
$mailSender     = 'pogo@YOURURL.COM';     // sender mail || ReplyTo
$mailHost       = 'domain.com';           // SMTP server
$smtpPort       = 25;                     // usually the port for TLS is 587, for SSL is 465 and non-secure is 25
$smtpSecure		  = '';					            // TLS, SSL or  empty the line
$smtpUser       = '';                     // SMTP account username
$smtpPass       = '';                     // SMTP account password


//PayPal Config
$paypallogo         = $WebsiteUrl."/logo_example.jpg";	// PayPal Logo
//$paypallogo       = ""; 							                // kein Logo anzeigen

$PayPalMode 		= 'live';                               // sandbox or live

if($PayPalMode == 'live') {								              // live mode
  $PayPalApiUsername 	= 'YOUR_API_USERNAME';            // PayPal API Username
  $PayPalApiPassword 	= 'YOUR_API_PASSWORD';            // Paypal API password
  $PayPalApiSignature = 'YOUR_API_SIGNATURE';           // Paypal API Signature
} elseif($PayPalMode == 'sandbox') {					          // sandbox mode
  $PayPalApiUsername 	= 'YOUR_API_USERNAME';            // PayPal API Username
  $PayPalApiPassword 	= 'YOUR_API_PASSWORD';            // Paypal API password
  $PayPalApiSignature = 'YOUR_API_SIGNATURE';           // Paypal API Signature
} else {
  echo '<h1 style="text-align:center;background:#FFFF00;padding:5px">Paypal Mode Error - options "live" or "sandbox" Check this!</h1>';
  exit();
}

$PayPalCurrencyCode = 'EUR';                            // Paypal Currency Code

$PayPalReturnURL 	= $WebsiteUrl.'/process.php';         // Point to process.php page
$PayPalCancelURL 	= $WebsiteUrl.'/index.php';           // Cancel URL if user clicks cancel


$DEBUG = 0;		// debug_mode, 0 OR 1




















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
