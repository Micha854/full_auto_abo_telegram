<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__) . '/../phpmailer/Exception.php';
require_once dirname(__FILE__) . '/../phpmailer/PHPMailer.php';
require_once dirname(__FILE__) . '/../phpmailer/SMTP.php';

while($rowX = $result->fetch_array()) {
    
    $chat_id = $rowX["chatid"];
    $getMember = callAPI('GET', $apiServer."getPWRchat/?id=$chat_id", false);
    
    $check_chatid = json_decode($getMember, true);
    $check_title = $check_chatid["response"]["title"];
    //$check_invite = $check_chatid["response"]["invite"];
    
    /*	
    if($rowX["url"] != $check_invite) {
        echo "<p style='background:#FFFF00; padding:5px'>your chatid \"<b>".$rowX["chatid"]."</b>\" for \"<b>".$check_title."</b>\" is not correct, this channel invite URL is \"<b>".$check_invite."</b>\" please upgrade the chatid or give them the correct channel invite URL</p>";
    } else {
    */
        echo "<h2>Channel: ". $rowX["name"] ."</h2>";
        echo "<table><tr><td>Username</td><td>Rolle</td><td>UserID Telegram</td><td>UserID Datenbank</td><td>Abo endet</td></tr>";
    
        $output = json_decode($getMember, true);
        $output = $output["response"]["participants"];
    
        $i_user = 0;
        $i_bann = 0;
    
        foreach ($output as $element) {
            $user_id = $element["user"]["id"];
        
            $row_query = "SELECT * FROM ".$tbl." WHERE userid = ".$user_id;
            $row_result = $mysqli->query($row_query);
            $row_cnt = mysqli_num_rows($row_result);
            $row = $row_result->fetch_array();

            $channel= $rowX["name"];
            
            // userid ist unbekannt !!!
            $delete     = is_null($row) ? 'no'      : 'yes';
            $userid     = is_null($row) ? $user_id  : $row["userid"];
            $date       = is_null($row) ? ''        : $row["endtime"];
            $empfaenger = is_null($row) ? ''        : $row["buyerEmail"];
                        
            $userid_check = is_null($row) && $element["role"] == 'user' ? " class='iderror'" : "";
                    
            if($element["role"] == 'banned') {
                $i_bann++;
            } else {
                $i_user++;
            }
        
            if($element["role"] == 'admin' or $element["role"] == 'creator') {
                // ***********************************************************************
                // admin && creator duerfen immer !!!
                if($userid) {
                    $admin_id = $userid;
                    if($row_cnt != 0) {
                        $ending = $row["endtime"];
                    } else {
                        $ending = 'does not expire';
                    }
                } else {
                    $admin_id = 'Not in DB';
                    $ending = 'does not expire';
                }
                echo "<tr><td class='admin'>@".$element["user"]["username"]."</td><td class='admin'>".$element["role"]."</td><td class='admin'>".$user_id."</td><td".$userid_check." class='admin'>".$admin_id."</td><td class='admin'>".$ending."</td></tr>";
            } elseif(!$row_cnt and $element["role"] == 'user') {
                // ***********************************************************************
                // user existiert nicht in abos || user = user
                echo "<tr><td class='false'>@".$element["user"]["username"]."</td><td class='false'>".$element["role"]."</td><td class='false'>".$user_id."</td><td".$userid_check." class='false'>".$userid."</td><td class='false'>-- KICKED --</td></tr>";	// user ohne ABO
                $deleteUser = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[participant]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights", false);
                include("msg.php");
                $botMessage = urlencode($userKicked);
                $mailMessage= nl2br($userKicked);
                if($botSend == '1') {
                    $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$user_id&data[message]=$botMessage", false);
                    APIlog($sendMessage, $user_id);
                    sleep(1);
                }
            } elseif($row_cnt and $element["role"] == 'user' and $row["endtime"] < date("Y-m-d H:i:s", strtotime("+".$sequenceInfo." days")) and !$row["info"] and $sequenceInfo ) {
                // ***********************************************************************
                // user ABO läuft bald aus, user informieren || user = user
                echo "<tr><td class='left'>@".$element["user"]["username"]."</td><td class='left'>".$element["role"]."</td><td class='left'>".$user_id."</td><td".$userid_check." class='left'>".$userid."</td><td class='left'>".$row["endtime"]."</td></tr>";
                $endtime = $row["endtime"];
                include("msg.php");
                $botMessage = urlencode($userInfo);
                $mailMessage= nl2br($userInfo);
                if($botSend == '1') {
                    $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$user_id&data[message]=$botMessage", false);
                    APIlog($sendMessage, $user_id);
                    sleep(1);
                }
                if($mailSend == '1') {
        
                    $mail             = new PHPMailer(true);
                    $mail->CharSet	  = 'utf-8';

                    $mail->IsSMTP();                          //Send using SMTP
                    $mail->Host       = $mailHost;            //Send using SMTP
                    $mail->SMTPAuth   = true;                 //Set the SMTP server to send
                    $mail->Username   = $smtpUser;            //SMTP username
                    $mail->Password   = $smtpPass;            //SMTP password

                    if ($smtpSecure == 'ssl') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit SSL encryption
                    } elseif ($smtpSecure == 'tls') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                    }

                    $mail->Port       = $smtpPort;            //TCP port to connect to; use
        
                    $mail->SetFrom($mailSender, $WebsiteTitle);
                    $mail->AddReplyTo($mailSender, $WebsiteTitle);
        
                    $mail->Subject    = mb_encode_mimeheader($mailSubject,"UTF-8");
                    $mail->AltBody    = strip_tags($mailMessage); // optional, comment out and test
                    $mail->MsgHTML($mailMessage);
                    $mail->AddAddress($empfaenger, $WebsiteTitle);

                    $mail->Send();
                } mysqli_query($mysqli, "UPDATE ".$tbl." SET info = '1' WHERE id = ".$row["id"]." ");
            
            } elseif($row_cnt and $element["role"] == 'user' and $row["endtime"] < date("Y-m-d H:i:s") ) {
                // ***********************************************************************
                // user ABO abgelaufen || user = user
                echo "<tr><td class='time'>@".$element["user"]["username"]."</td><td class='time'>".$element["role"]."</td><td class='time'>".$user_id."</td><td".$userid_check." class='time'>".$userid."</td><td class='time'>".$row["endtime"]."</td></tr>";
                if($delete == 'yes') { // nur loeschen wenn userid bekannt !!!
                    $deleteUser = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[participant]=$user_id&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=1&data[banned_rights][_]=chatBannedRights", false);
                    include("msg.php");
                    $botMessage = urlencode($aboEnds);
                    $mailMessage= nl2br($aboEnds);
                    if($botSend == '1') {
                        $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$user_id&data[message]=$botMessage", false);
                        APIlog($sendMessage, $user_id);
                        sleep(1);
                    }
                    if($mailSend == '1') {
        
                        $mail             = new PHPMailer(true);
                        $mail->CharSet	  = 'utf-8';

                        $mail->IsSMTP();                          //Send using SMTP
                        $mail->Host       = $mailHost;            //Send using SMTP
                        $mail->SMTPAuth   = true;                 //Set the SMTP server to send
                        $mail->Username   = $smtpUser;            //SMTP username
                        $mail->Password   = $smtpPass;            //SMTP password

                        if ($smtpSecure == 'ssl') {
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit SSL encryption
                        } elseif ($smtpSecure == 'tls') {
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                        }

                        $mail->Port       = $smtpPort;            //TCP port to connect to; use
        
                        $mail->SetFrom($mailSender, $WebsiteTitle);
                        $mail->AddReplyTo($mailSender, $WebsiteTitle);
        
                        $mail->Subject    = mb_encode_mimeheader($mailSubject,"UTF-8");
                        $mail->AltBody    = strip_tags($mailMessage); // optional, comment out and test
                        $mail->MsgHTML($mailMessage);
                        $mail->AddAddress($empfaenger, $WebsiteTitle);

                        $mail->Send();
                    } //mysqli_query($mysqli, "DELETE FROM ".$tbl." WHERE id = ".$row["id"]." ");
            
                    if($use_map == "PMSF") {
                        mysqli_query($mysqli, "UPDATE users SET access_level = '0' WHERE user = '".$row["buyerEmail"]."' ");
                    } elseif($use_map == "Rocketmap") {
                        $load_htpasswd = file_get_contents('../.htpasswd');
                        if(is_bool(strpos($load_htpasswd, $row["TelegramUser"])) === false) {
                            $htpasswd = new Htpasswd('../.htpasswd');
                            $htpasswd->deleteUser($row["TelegramUser"]);
                        }
                    }
                }
            } elseif($element["role"] != 'banned') {
                // ***********************************************************************
                // ALLES OK
                echo "<tr><td class='true'>@".$element["user"]["username"]."</td><td class='true'>".$element["role"]."</td><td class='true'>".$user_id."</td><td".$userid_check." class='true'>".$userid."</td><td class='true'>".$row["endtime"]."</td></tr>";
            }
        }
        echo "<tr><td class='stat'>Abonnenten:</td><td class='stat' colspan='4'>".$i_user."</td></tr>";
        echo "<tr><td class='stat'>banned User:</td><td class='stat' colspan='4'>".$i_bann."</td></tr>";
        echo "</table>";
    //}
}
?>