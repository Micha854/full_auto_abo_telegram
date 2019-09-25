<?php
// Mysql Database
$mysqli = new mysqli('localhost','DBuser','DBpass','database');

// name der tabelle
$tbl = "abos";

// Telegram Chanel - Join-URL
$InputChannel = 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ';

// Website Titel
$WebsiteTitle = "Pogo Dein Stadtteil"

// PayPal Logo
$paypallogo = "http://YOURURL.COM/logo_example.jpg";
//$paypallogo = ""; // kein Logo anzeigen
  
//PayPal Config
$PayPalMode 		= 'live'; // sandbox or live

$PayPalApiUsername 	= 'YOUR_API_USERNAME'; //PayPal API Username
$PayPalApiPassword 	= 'YOUR_API_PASSWORD'; //Paypal API password
$PayPalApiSignature = 'YOUR_API_SIGNATURE'; //Paypal API Signature

$PayPalCurrencyCode = 'EUR'; //Paypal Currency Code

$PayPalReturnURL 	= 'http://YOUR_DOMAIN/process.php'; //Point to process.php page
$PayPalCancelURL 	= 'http://YOUR_DOMAIN/tme/index.php'; //Cancel URL if user clicks cancel
?>
