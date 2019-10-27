<?php

while($row = $result->fetch_array()) {
	
	echo "<h2>". $row["name"] ."</h2>";
	$chat_id = $row["chatid"];
	$getMember = file_get_contents($apiServer."getPWRchat/?id=$chat_id");
	
	$output = json_decode($getMember, true);
	$output = $output["response"]["participants"];
	
	foreach ($output as $element) {
		$user_id = $element["user"]["id"];
		
		//$row_query = "SELECT * FROM ".$tbl." WHERE TelegramUser = '@". $element["user"]["username"] ."' ";
		$row_query = "SELECT * FROM ".$tbl." WHERE userid = ".$user_id;
		$row_result = $mysqli->query($row_query);
		$row_cnt = mysqli_num_rows($row_result);
		$row = $row_result->fetch_array();
		
		if($row["userid"] == NULL) {
			mysqli_query($mysqli, "UPDATE ".$tbl." SET userid = $user_id WHERE id = ".$row["id"]." ");
		}
		
		$userid = $row["userid"];
		
		if(!$row_cnt and $element["role"] == 'user') {
			echo "<span class='false'>@".$element["user"]["username"]." darf hier nicht sein !!!!</span><br>";	// user ohne ABO
			$deleteUser = file_get_contents($apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights");
			$botMessage = urlencode("Du wurdest aus den Kan‰len entfernt, da du kein Abo hast. Hier kannst du ein Abo abschlieﬂen:\n\n".$WebsiteUrl);
			$sendMessage = file_get_contents("https://api.telegram.org/bot".$apitoken."/sendMessage?chat_id=$userid&text=$botMessage");
		} elseif($row_cnt and $element["role"] == 'user' and $row["endtime"] < date("Y-m-d H:i:s") ) {			// user ABO abgelaufen
			echo "<span class='time'>@".$element["user"]["username"]." Abo ist ausgelaufen --> ".$row["endtime"]."</span><br>";
			$deleteUser = file_get_contents($apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights");
			$botMessage = urlencode("Dein Abo ist am ".date('d.m.Y', strtotime($row["endtime"]))." abgelaufen und du hast nun keinen Zutritt mehr zu den Kan‰len und yur MAP. Hier kannst du ein Abo abschlieﬂen:\n\n".$WebsiteUrl);
			$sendMessage = file_get_contents("https://api.telegram.org/bot".$apitoken."/sendMessage?chat_id=$userid&text=$botMessage");
			mysqli_query($mysqli, "DELETE FROM ".$tbl." WHERE id = ".$row["id"]." ");
			
			include '../Htpasswd.php';
			$htpasswd = new Htpasswd('../.htpasswd');
			$htpasswd->deleteUser($row["TelegramUser"]);
		} else {																								// ALLES OK
			echo "<span class='true'>@".$element["user"]["username"]."</span><br>";
		}
	}

}
?>