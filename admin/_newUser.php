<?php 
require_once(__DIR__.'/../config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<title><?=$WebsiteTitle ?> - ADMIN NEW USER</title>
</head>
<body>
<?php					
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

if(!file_exists(__DIR__.'/msg.php') and file_exists(__DIR__.'/msg_example.php')) {
    copy(__DIR__."/msg_example.php",__DIR__."/msg.php");
}

$query = "SELECT SUM(item_price) as total, SUM(abo_days) as abo, COUNT(id) as menge FROM products";
$result = $mysqli->query($query);
$row = $result->fetch_array();

$schnitt = $row["total"]/$row["abo"];	// durchschnittlicher preis pro tag


if(isset($_POST["submit"]) and $_POST["user"]) {

    require_once(__DIR__.'/../functions.php');
    
    function generateRandomString($length = 10) {
        //return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
	
	// give costum passwort or generate automaticle
	if(isset($_POST["pass"])) {
		$passwd = mysqli_real_escape_string($mysqli, $_POST["pass"]);
	} else {
		$passwd = generateRandomString(8);
	}
    
    $newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
    $newMail = mysqli_real_escape_string($mysqli, $_POST["email"]);
    $newAdd = $_POST["user"];
    $ItemDesc = $newAdd;
    
    $sumBar = $_POST["itemprice"];
    $sumBar = str_replace(",",".", $sumBar);
    
    $days_to_end = $_POST["itemprice"]/$schnitt;
    $days_to_end = ceil($days_to_end);
    
    $InputChannel = array();
    $InputChannel = $_POST["added"];
                    
    // NEW USER OR UPDATE
    $check = $mysqli->query("SELECT * FROM ".$tbl." WHERE TelegramUser = '".$newUser."' ");
    $row_cnt = $check->num_rows;
    
    if($row_cnt != 0) {
        $update = $check->fetch_array();
        $statement = "update";
        $passwd = $update["pass"];
        $date = date('Y-m-d H:i:s', strtotime($update["endtime"]. " + {$days_to_end} days"));
        $amountInsert = $update["Amount"];
        $amountInsert+=$sumBar;
    } else {
        $statement = "insert";
        //$date = new DateTime();
        //$date->modify('+$days_to_end day');
        //$date = $date->format('Y-m-d H:i:s');
        $date = date('Y-m-d H:i:s', strtotime('+'.$days_to_end.' days'));
        $amountInsert = $sumBar;
    }
    
    $getInfo	= callAPI('GET', $apiServer."getfullInfo/?id=".$ItemDesc, false);
    $getUserId	= json_decode($getInfo, true);
    $userid		= $getUserId["response"]["InputPeer"]["user_id"];
    
    if($userid) {
        $useridnow = ", userid = '$userid'";
    }
                    
    if($use_map == "PMSF") {
        $hashedPwd = password_hash($passwd, PASSWORD_DEFAULT);
                        
        //$datum = $date->getTimestamp();
        //$expire_timestamp = strtotime('+'.$days_to_end.' day', $datum);
        $datum = new DateTime($date);
        $datum = $datum->getTimestamp();
        $expire_timestamp = $datum;
                                        
        $empfaenger	= $newMail;
        $loginName	= $empfaenger;
        
        $check_user = $mysqli->query("SELECT id FROM users WHERE user = '".$loginName."' ");
        if($check_user->num_rows != 0) {
            mysqli_query($mysqli, "UPDATE users SET password = NULL, temp_password = '".$hashedPwd."', expire_timestamp = '".$expire_timestamp."', session_id = NULL, login_system = '".$login_system."', access_level = '".$access_level."'  WHERE user = '".$loginName."' ");
        } elseif($statement == "insert") {
            $insert_pmsf_user = $mysqli->query("INSERT INTO users 
            (user,temp_password,expire_timestamp,login_system,access_level)
            VALUES ('$newMail','$hashedPwd','$expire_timestamp','$login_system','$access_level')");
        } else {
            $update_user = $check_user->fetch_array();
            mysqli_query($mysqli, "UPDATE users SET expire_timestamp = '".$expire_timestamp."' WHERE id = ".$update_user["id"]);
        }
    }
                    
    elseif($use_map == "Rocketmap") {
        
        if($statement == "insert") {				
            include("../Htpasswd.php");
            $htpasswd = new Htpasswd('../.htpasswd');
            //$htpasswd->deleteUser($newAdd);
            $htpasswd->addUser($newAdd, $passwd);
        }
                        
        $empfaenger	= $newMail;
        $loginName	= $newAdd;
    }
                    
    else {
        $empfaenger	= $newMail;
    }
                    
    $InputChannels = implode(',',$InputChannel);
    
    if($statement == "insert") {
        $sql_insert = "INSERT INTO ".$tbl." SET buyerName = '', buyerEmail = '$empfaenger', Amount = '$amountInsert', TelegramUser = '$newUser'".$useridnow.", channels = '$InputChannels', pass = '$passwd', TransID = NULL, paydate = now(), endtime = NOW() + INTERVAL $days_to_end DAY";
        $mysqli->query($sql_insert);
    } elseif($statement == "update") {
        mysqli_query($mysqli, "UPDATE ".$tbl." SET Amount = $amountInsert, endtime = DATE_ADD(endtime,INTERVAL $days_to_end DAY), info = NULL WHERE id = ".$update["id"]);
    }
    
    include_once("msg.php");
    
    if($botSend == '1') {
        if($use_map == "PMSF" or $use_map == "Rocketmap") {
            $botMessage = $UserMsg;
        } else {
            $botMessage = $UserMsgShort;
        }
        $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$userid&data[message]=$botMessage&data[parse_mode]=html", false);
    }
    
    include_once("_add_user.php");
                    
    if($mailSend == '1') {
        
        require_once('../mailer/class.phpmailer.php');

        $mail             = new PHPMailer();
        $mail->CharSet	  = 'ISO-8859-1';
        
        ob_start();
        include("../mail.php");
        $body = ob_get_contents();
        ob_end_clean();

        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host       = $mailHost; // SMTP server
        $mail->Port       = $smtpPort;                    // set the SMTP port for the GMAIL server
        $mail->SMTPSecure = $smtpSecure;
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Username   = $smtpUser; // SMTP account username
        $mail->Password   = $smtpPass;        // SMTP account password
        
        $mail->SetFrom($mailSender, $WebsiteTitle);
        $mail->AddReplyTo($mailSender, $WebsiteTitle);
        
        $mail->Subject    = $mailSubject;
        $mail->AltBody    = strip_tags($body); // optional, comment out and test
        $mail->MsgHTML($body);
        $mail->AddAddress($empfaenger, $WebsiteTitle);

        $mail->Send();
    }
    $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Neuer Benutzer ".$newAdd." wurde erstellt!</h3>";
                                    
}

?>
<main role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?php if(isset($_POST["submit"])) { echo $userSave; } ?>
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<h1>Benutzer hinzuf&uuml;gen</h1>
  <form method="post" action="">
    <div class="form-group">
      <p class="lead">Telegram Username:</p>
      <input type="text" name="user" class="form-control" aria-describedby="telegramname @" placeholder="@" required>
    </div>
    <div class="form-group">
      <p class="lead">Passwort:</p>
      <input type="text" name="pass" class="form-control" aria-describedby="passwort" placeholder="leave blank to generate a password" />
    </div>
    <div class="form-group">
      <p class="lead">eMail:</p>
      <input type="email" name="email" class="form-control" placeholder="Emailadresse" required>
    </div>
    <div class="form-group">
      <p class="lead">Bar erhalten</p>
      <input type="text" name="itemprice" class="form-control" placeholder="&euro;" required>
    </div>
    <div>
      <table>
        <tr>
          <td colspan="2"><b>Channels:</b><br></td>
        </tr>
          <?php
          foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
            echo "<tr>";
            echo "<td>".$channel["name"]."</td><td valign='top'>beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /></td>";
            echo "</tr>";
          }
          ?>
        <tr>
          <td colspan="2"><button type="submit" name="submit" class="btn btn-sm btn-outline-secondary" value="Benutzer erstellen">Erstellen</button>
        </tr>
      </table>
    </div>
  </form>
</div>
</main>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
