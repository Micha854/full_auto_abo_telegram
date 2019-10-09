<?php
// Mysql Database
$mysqli = new mysqli('localhost','DBuser','DBpass','database');

// name der tabelle
$tbl = "abos";

// Channel Join - Url´s
$InputChannel = array(
"channel name 1" => "https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ",		// channel 1
"channel name 2" => "https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ",		// channel 2
"channel name 3" => "https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ",		// channel 3
"channel name 4" => "https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ"		// channel 4
);

// Website Titel
$WebsiteTitle = "Pogo Dein Stadtteil";

// index header  
$header = "f&uuml;r den DEIN STADTTEIL Mon IV - Telegram Kanal (nur Innenstadt). Du wirst dem Kanal automatisch nach deiner Zahlung hinzugef&uuml;gt!";

// Mail Settings
$mailSend = '1'; // set value '0' to off
$mailmail = 'pogo@YOURURL.COM'; // sender mail
$mailSubject = "Welcome to PoGO MAP - here is your login data";
$urlRocketmap= "https://YOURURL.COM/go/";

$paypallogo = "http://YOURURL.COM/logo_example.jpg";	// PayPal Logo
//$paypallogo = ""; 									// kein Logo anzeigen

$PayPalMode 		= 'live'; 							// sandbox or live

$PayPalApiUsername 	= 'YOUR_API_USERNAME'; 				//PayPal API Username
$PayPalApiPassword 	= 'YOUR_API_PASSWORD'; 				//Paypal API password
$PayPalApiSignature = 'YOUR_API_SIGNATURE'; 			//Paypal API Signature

$PayPalCurrencyCode = 'EUR'; 							//Paypal Currency Code

$PayPalReturnURL 	= 'http://YOUR_DOMAIN/process.php'; //Point to process.php page
$PayPalCancelURL 	= 'http://YOUR_DOMAIN/index.php'; 	//Cancel URL if user clicks cancel
?>
