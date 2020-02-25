<?php
/**********************************************************
Link zur MAP	= $urlMap
Username		= $loginName
Password		= $passwd
Abo End Datum	= date('d.m.Y', strtotime($date))
Channel Links	= utf8_decode($joinMail)
**********************************************************/

############################################
###### ****************************** ######
######        TELEGRAM MESSAGES       ######
###### **** .................... **** ######
############################################
 
// wenn ein abo/user ge�ndert oder neu �ber das Admin Panel hinzugef�gt wird
$UserMsg			= urlencode("Link zur MAP:<br>$urlMap<br><br>Deine Logindaten:<br>Username: $loginName<br>Passwort: <a href=\"$urlMap\">$passwd</a><br><br>Dein Abo endet am ".date('d.m.Y', strtotime($date)));
$UserMsgShort		= urlencode("Dein Abo endet am ".date('d.m.Y', strtotime($date))); // es wird keine Map verwendet !!!


// wenn ein abo verl�ngert wird
$extendUserMsg		= urlencode("Dein Abo wurde verl&auml;ngert und endet folglich am ".date('d.m.Y', strtotime($date)));


// User zahlt �ber PayPal
$userPayedMsg		= urlencode("Vielen Dank, wir haben deine Zahlung erhalten!<br><br>Link zur MAP:<br>$urlMap<br><br>Deine Logindaten:<br>Username: $loginName<br>Passwort: <a href=\"$urlMap\">$passwd</a><br><br>Dein Abo endet am ".date('d.m.Y', strtotime($date)));
$userPayedMsgShort	= urlencode("Vielen Dank, wir haben deine Zahlung erhalten!<br><br>Dein Abo endet am ".date('d.m.Y', strtotime($date)));

############################################
###### ****************************** ######
######         EMAIL MESSAGES         ######
###### **** .................... **** ######
############################################

// wenn ein abo/user ge�ndert oder neu �ber das Admin Panel hinzugef�gt wird
$emailInsertMsg = "<p>Dein Abo wurde eingerichtet und du erh&auml;ltst alle n&ouml;tigen Daten um die Map zu benutzen.</p>
    <p>Um die Map zu verwenden, benutze die folgende URL mit folgenden Login Daten:</p>
    <h3><a href=\"$urlMap\">$urlMap</a></h3>
    <p>Login- Name: <b>$loginName</b><br />Passwort: <b>$passwd</b></p>
    <p><b>Du kannst nun folgenden Kan&auml;len beitreten:</b></p>";

// wenn ein abo verl�ngert wird
$emailUpdateMsg = "<p>Dein Abo wurde erfolgreich verl&auml;ngert. An deinen Login Daten hat sich nichts ge&auml;ndert!</p>
    <p><b>Links zu den Kan&auml;len:</b></p>";

// Abschluss Text in jeder Mail (unabh�ngig ob Neues Abo oder verl�ngert)	
$emailLastMsg = "<p>Dein Abo endet automatisch am <b>".date('d.m.Y', strtotime($date))."</b> und wird nicht verl&auml;ngert! Du hast dann keinen Zugriff mehr auf unsere Kan&auml;le und die MAP</p>
<p>Viel Erfolg und GO! Trainer!</p>";