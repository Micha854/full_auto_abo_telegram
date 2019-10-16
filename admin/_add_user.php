<?php
echo '<div style="display:none">';
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$InputUser = $ItemDesc; // username

$query = "SELECT url FROM channels WHERE id IN (".implode(',',$InputChannel).")";
$result = $mysqli->query($query);
while($channel = $result->fetch_array()) {
	if($Updates = $MadelineProto->channels->inviteToChannel(['silent' => false, 'channel' => $channel["url"], 'users' => [$InputUser, $InputUser], ])) {
		Logger::info("INVITE ".$InputUser." ON ".$channel["url"]); // LOGGER
	}
}
echo '</div>';
?>