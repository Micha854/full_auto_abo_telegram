<?php
$joinMail = '';

$query = "SELECT * FROM channels WHERE id IN (".implode(',',$InputChannel).")";
$result = $mysqli->query($query);
while($channel = $result->fetch_array()) {		
	
	$chat_id = $channel["chatid"];
	$editBanned = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$ItemDesc&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=0&data[banned_rights][_]=chatBannedRights", false);
	
	$joinName = urlencode($channel["name"].": ".$channel["url"]);
	$joinUrl  = $channel["url"];
	
	if($botSend == '1') {
		sleep(1);
		$sendMessageUrl = callAPI('GET', $apiServer."sendMessage/?data[peer]=$userid&data[message]=$joinName&data[no_webpage]=1&data[silent]=1", false);
	}
	
	$joinMail .= $channel["name"].': <a href="'.$channel["url"].'">'.$channel["url"].'</a><br>';
}
?>