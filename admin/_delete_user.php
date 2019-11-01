<?php

while($rowX = $result->fetch_array()) {
	
	echo "<h2>Channel: ". $rowX["name"] ."</h2>";
	echo "<table><tr><td>Username</td><td>Rolle</td><td>UserID Telegram</td><td>UserID Datenbank</td><td>Abo endet</td></tr>";
	$chat_id = $rowX["chatid"];
	$getMember = file_get_contents($apiServer."getPWRchat/?id=$chat_id");
	
	$output = json_decode($getMember, true);
	$output = $output["response"]["participants"];
	
	$i_user = 0;
	$i_bann = 0;
	
	foreach ($output as $element) {
		$user_id = $element["user"]["id"];
		$username= $element["user"]["username"];
		
		$row_query = "SELECT * FROM ".$tbl." WHERE TelegramUser = '@".$username."' ";
		$row_result = $mysqli->query($row_query);
		$row_cnt = mysqli_num_rows($row_result);
		$row = $row_result->fetch_array();
		
		if($row["userid"] == NULL) {
			mysqli_query($mysqli, "UPDATE ".$tbl." SET userid = $user_id WHERE id = ".$row["id"]." ");
		}
		
		$userid = $row["userid"];
		$channel= $rowX["name"];
		
		if($userid != $user_id and $element["role"] == 'user') {
			$userid_check = " class='iderror'";
		} else {
			$userid_check = "";
		}
		
		if($element["banned"]) {
			$i_bann++;
		} else {
			$i_user++;
		}
		
		if($element["role"] == 'admin' or $element["role"] == 'creator') {	// admin && creator duerfen immer !!!
			if($userid) {
				$admin_id = $userid;
				$ending	= $row["endtime"];
			} else {
				$admin_id = 'Not in DB';
				$ending = 'does not expire';
			}
			echo "<tr><td class='admin'>@".$element["user"]["username"]."</td><td class='admin'>".$element["role"]."</td><td class='admin'>".$user_id."</td><td".$userid_check." class='admin'>".$admin_id."</td><td class='admin'>".$ending."</td></tr>";
		} elseif(!$row_cnt and $element["role"] == 'user') {	// user existiert nicht in abos || user = user
			echo "<tr><td class='false'>@".$element["user"]["username"]."</td><td class='false'>".$element["role"]."</td><td class='false'>".$user_id."</td><td".$userid_check." class='false'>".$userid."</td><td class='false'>-- KICKED --</td></tr>";	// user ohne ABO
			$deleteUser = file_get_contents($apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights");
			$botMessage = urlencode("Du wurdest aus dem Kanal $channel entfernt, du kannst hier ein Abo abschliessen: \n\n$WebsiteUrl");
			$sendMessage = file_get_contents($apiServer."sendMessage/?data[peer]=$user_id&data[message]=$botMessage");
			time.sleep(1);
		} elseif($row_cnt and $element["role"] == 'user' and $row["endtime"] < date("Y-m-d H:i:s") ) {			// user ABO abgelaufen || user = user
			echo "<tr><td class='time'>@".$element["user"]["username"]."</td><td class='time'>".$element["role"]."</td><td class='time'>".$user_id."</td><td".$userid_check." class='time'>".$userid."</td><td class='time'>".$row["endtime"]."</td></tr>";
			$deleteUser = file_get_contents($apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights");
			$botMessage = urlencode("Dein Abo ist am ".date('d.m.Y', strtotime($row["endtime"]))." abgelaufen, du hast keinen Zutritt mehr zu $channel und zur MAP, du kannst hier ein Abo abschliessen: \n\n$WebsiteUrl");
			$sendMessage = file_get_contents($apiServer."sendMessage/?data[peer]=$user_id&data[message]=$botMessage");
			time.sleep(1);
			mysqli_query($mysqli, "DELETE FROM ".$tbl." WHERE id = ".$row["id"]." ");
			
			if($use_map == "Rocketmap") {
				include '../Htpasswd.php';
				$htpasswd = new Htpasswd('../.htpasswd');
				$htpasswd->deleteUser($row["TelegramUser"]);
			}
		} elseif($element["role"] != 'banned') {																								// ALLES OK
			echo "<tr><td class='true'>@".$element["user"]["username"]."</td><td class='true'>".$element["role"]."</td><td class='true'>".$user_id."</td><td".$userid_check." class='true'>".$userid."</td><td class='true'>".$row["endtime"]."</td></tr>";
		}
	}
	echo "<tr><td class='stat'>Abonnenten:</td><td class='stat' colspan='4'>".$i_user."</td></tr>";
	echo "<tr><td class='stat'>banned User:</td><td class='stat' colspan='4'>".$i_bann."</td></tr>";
	echo "</table>";
}
?>