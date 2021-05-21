<?php
$date		= isset($date)		? $date 		: '';
$loginName	= isset($loginName)	? $loginName	: '';
$passwd		= isset($passwd)	? $passwd		: '';

/**********************************************************
Link zur MAP	= $urlMap	OR	<a href=\"$urlMap\">$urlMap</a>
Username		= $loginName
Password		= $passwd
Abo End Datum	= date('d.m.Y', strtotime($date))
Channel Links	= $joinMsg
Zeilenumbruch	= \n	[NOT USE <br> !!!]
Fetter Text		= <b>TEXT</b>

**********************************************************/
$endtime = date('d.m.Y', strtotime($date));

// config emojis ---> https://www.alt-codes.net/smiley_alt_codes.php
$emoji_confused		= "&#128533;"; // confused
$emoji_point_down	= "&#128071;"; // point_down
$emoji_point_right	= "&#128073;"; // point_right
$emoji_smirk_cat	= "&#128572;"; // smirk_cat

// DO NOT CHANGE THIS PART !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!		
if(is_null($InputChannel)) {
	$channels = $mysqli->query("SELECT * FROM channels ORDER BY sort DESC, id DESC");
} else {
	$channels = $mysqli->query("SELECT * FROM channels WHERE id IN (".$InputChannel.") ORDER BY sort DESC, id DESC");
}

$joinMsg	= '';
$chl_names	= '';
if(isset($channels->num_rows)) {
	$joinMsg = '<b>*** Links zu den Kanälen: ***</b>';
	while ($channel = $channels->fetch_array()) {
		$joinMsg	.= "\n\n".$channel["name"] . ": $emoji_point_right <a href=\"" . $channel["url"] . "\">" . $channel["url"] . "</a>";
		$chl_names	.= $channel["name"] . "\n";
	}
}
// END !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// Dein Support Kanal
$supportChannel = "<a href=\"https://t.me/MonsHLSupport\">@MonsHLSupport</a>";

// wenn ein user geändert oder neu über das Admin Panel hinzugefügt wird
$UserMsg			= "Link zur MAP: $emoji_point_right <a href=\"$urlMap\">$urlMap</a>\n\n<b>Deine Logindaten:</b>\nUsername: $loginName\nPasswort: <code>$passwd</code>\n\nDein Abo endet am $endtime\n\n$joinMsg\n\nFalls du Fragen hast wende dich an unseren Support $emoji_point_right $supportChannel\n\nViel Spass beim Pokemon fangen $emoji_smirk_cat";
$UserMsgShort		= "Dein Abo endet am $endtime"; // es wird keine Map verwendet !!!


// wenn ein abo verlängert wird
$extendUserMsg		= "Dein Abo wurde verlängert und endet folglich am <b>$endtime</b>\n\nInformationen zu deinem Abo gibt dir jederzeit der $emoji_point_right <a href=\"https://t.me/meinAbo_bot\">@meinAbo_bot</a> aus!";


// User zahlt über PayPal
$userPayedMsg		= "Vielen Dank, wir haben deine Zahlung erhalten!\n\nLink zur MAP:\n$urlMap\n\nDeine Logindaten:\nUsername: $loginName\nPasswort: <a href=\"$urlMap\">$passwd</a>\n\nDein Abo endet am $endtime";
$userPayedMsgShort	= "Vielen Dank, wir haben deine Zahlung erhalten!\n\nDein Abo endet am $endtime"; // es wird keine Map verwendet !!!
$notifyAdmin		= "Es wurde ein neues Abo abgeschlossen von <b>$loginName</b>, laufzeit bis <b>$endtime</b>";


// Info an den user, das er aus dem Kanal entfernt wurde
$userKicked			= "Du wurdest aus den Kanälen $emoji_point_down\n\n<b>$chl_names</b>\nentfernt! Du kannst hier ein Abo abschließen:\n\n<a href=\"$WebsiteUrl\">$WebsiteUrl</a>";

// Info an den user, das Abo ist abgelaufen
$aboEnds			= "Dein Abo ist am $endtime abgelaufen, du hast keinen Zutritt mehr zu $emoji_point_down\n\n<b>$chl_names</b>\nund zur MAP! Du kannst hier ein Abo abschließen:\n\n<a href=\"$WebsiteUrl\">$WebsiteUrl</a>";

// Info an den user, das sein Abo in X Tagen ausläuft
$userInfo			= "Dein Abo läuft am $endtime aus, du kannst dein Abo hier verlängern:\n\n<a href=\"$WebsiteUrl\">$WebsiteUrl</a>";


// TELEGRAM BOT HANDLING
$botStartMsg		= "Willkommen bei Mein Abo-Bot!\nWas möchtest du tun?";

$botUserFalse		= "<b>Ohh... Du hast wohl noch kein Abo!</b> $emoji_confused\n\nFalls du Probleme mit deinem Abo hast wende dich an unseren Support $emoji_point_right $supportChannel\n\nWenn du ein Abo abschließt, wird es nicht automatisch verlängert und läuft automatisch aus $emoji_point_down";

$botUserTrue		= "Dein Abo ist Aktiv bis zum <b>*** $endtime ***</b>\n\nUm die Map zu verwenden, benutze folgende URL: $emoji_point_right $urlMap\n\n<b>*** Deine Login Daten: ***</b>\nUsername: $loginName\nPassword: <code>$passwd</code>\n\n" . $joinMsg . "\n\nFalls du Fragen hast wende dich an unseren Support $emoji_point_right $supportChannel\n\nViel Spass beim Pokemon fangen $emoji_smirk_cat";