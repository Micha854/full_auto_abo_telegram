<?php
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/../functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/png" href="logo.png" sizes="32x32">
<link rel="icon" type="image/png" href="logo.png" sizes="96x96">
<title><?=$WebsiteTitle ?> - ADMIN EDIT</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <script>
    $(function() {
      $( "#datepicker" ).datepicker({
        firstDay: 1,
        dateFormat: "yy-mm-dd"
      });
    });

    $(function() {
        $( "#autocomplete" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "fetchData.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#autocomplete').val(ui.item.label);
                return false;
            }
        });
    });
  </script>

</head>
<body>

<?php                 
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

if(!file_exists(dirname(__FILE__) . '/msg.php') and file_exists(dirname(__FILE__) . '/msg_example.php')) {
    copy(dirname(__FILE__) . "/msg_example.php",dirname(__FILE__) . "/msg.php");
}

$query2 = "SELECT SUM(item_price) as total, SUM(abo_days) as abo, COUNT(id) as menge FROM products";
$result2 = $mysqli->query($query2);
$row2 = $result2->fetch_array();

$schnitt = $row2["total"]/$row2["abo"];	// durchschnittlicher preis pro tag
$schnitt_output = number_format((float)$schnitt, 8, '.', '');

$id = mysqli_real_escape_string($mysqli, $_GET["id"]);
                    
$query = "SELECT * FROM ".$tbl." WHERE id = $id";
$result = $mysqli->query($query);

$row = $result->fetch_array();

if($AccessAllChannels === false) {
  $InputChannel = array($row["channels"]);  
  $InputChannel = implode(',',$InputChannel);
} else {
  $InputChannel = NULL;
}

if(isset($_POST["submit"]) and $_POST["user"]) {
    
    // give costum passwort or generate automaticle
    if($_POST["pass"] != '') {
      $passwd = mysqli_real_escape_string($mysqli, $_POST["pass"]);
    } else {
      $passwd = generateRandomString(8);
    }
    
    $newUser = mysqli_real_escape_string($mysqli, $_POST["user"]);
    if(isset($_POST["email"])) {
        $newMail = mysqli_real_escape_string($mysqli, $_POST["email"]);
    } else {
        $newMail = $row["buyerEmail"];
    }

    if(isset($newUser)) {
      $newAdd = $_POST["user"];
      $ItemDesc = $newAdd;
      
      $OldUser = $row["TelegramUser"]; // old Username delete
      
      $getInfo	= callAPI('GET', $apiServer."getfullInfo/?id=".$ItemDesc, false);
      $getUserId	= json_decode($getInfo, true);
      $userid		= $getUserId["response"]["InputPeer"]["user_id"];
    }
    $date		= $row["endtime"];
    
    if(isset($userid)) {
        $useridnow = ", userid = '$userid'";
    } else {
        $useridnow = ", userid = NULL";
    }
    
    mysqli_query($mysqli, "UPDATE ".$tbl." SET TelegramUser = '".$newUser."'".$useridnow.", buyerEmail = '".$newMail."', pass = '".$passwd."' WHERE id = ".$row["id"]);
    
    if($use_map == "PMSF") {
        $statement = "insert";
        $hashedPwd = password_hash($passwd, PASSWORD_DEFAULT);
        
        $loginName	= $newMail;
        mysqli_query($mysqli, "UPDATE users SET user = '".$newMail."', password = NULL, temp_password = '".$hashedPwd."', session_id = NULL WHERE user = '".$loginName."' ");
    } elseif($use_map == "Rocketmap" && $newUser) {
        $statement = "insert";
        $loginName	= $newAdd;
                        
        include("../Htpasswd.php");
    
        $htpasswd = new Htpasswd('../.htpasswd');
        $load_htpasswd = file_get_contents('../.htpasswd');
        if(is_bool(strpos($load_htpasswd, $OldUser)) === false) {
            $htpasswd->deleteUser($OldUser);
        }
        $htpasswd->addUser($newAdd, $passwd);
    }
    
    include_once("msg.php");
    
    if($use_map == "PMSF" or $use_map == "Rocketmap") {
        $botMessage = urlencode($UserMsg);
        $mailMessage= nl2br($UserMsg);
    } else {
        $botMessage = urlencode($UserMsgShort);
        $mailMessage= nl2br($UserMsgShort);
    }
    
    if($AccessAllChannels === false) {
        $all_channels = $mysqli->query("SELECT * FROM channels WHERE id IN (".$InputChannel.")");
    } else {
        $all_channels = $mysqli->query("SELECT * FROM channels");
    }

    while($unsert_bann = $all_channels->fetch_array()) {		
        $chat_id = $unsert_bann["chatid"];
        $editBanned = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[participant]=$ItemDesc&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=0&data[banned_rights][_]=chatBannedRights", false);
    }
                        
    if($botSend == '1') {
        $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$userid&data[message]=$botMessage&data[parse_mode]=html&data[no_webpage]=1", false);
        APIlog($sendMessage, $userid);
    }
                    
    if($mailSend == '1') {
        
        $empfaenger	= $row["buyerEmail"];
        require_once('../mailer/class.phpmailer.php');

        $mail             = new PHPMailer();
        $mail->CharSet	  = 'utf-8';

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
        
        $mail->Subject    = mb_encode_mimeheader($mailSubject,"UTF-8");
        $mail->AltBody    = strip_tags($mailMessage); // optional, comment out and test
        $mail->MsgHTML($mailMessage);
        $mail->AddAddress($empfaenger, $WebsiteTitle);

        $mail->Send();
    }
    
    $query = "SELECT * FROM ".$tbl." WHERE id = $id";
    $result = $mysqli->query($query);
    $row = $result->fetch_array();
                
    $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Benutzer ge&auml;ndert zu ".$newAdd."</h3>";
} elseif(isset($_POST["submit2"])) {
    
    if(empty($_POST["itemprice"]) && date('Y-m-d',strtotime($row["endtime"])) >= $_POST["setAbo"] or empty($_POST["setAbo"]) && empty($_POST["itemprice"])) {
      if($_POST["setAbo"] && date('Y-m-d',strtotime($row["endtime"])) >= $_POST["setAbo"]) {
        $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Das Datum darf nicht kleiner = ".date('Y-m-d',strtotime($row["endtime"]))." sein!!</h3>";
      } else {
        $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Bitte ein \"Abo Ende\" oder \"Betrag\" angeben!!</h3>";
      }
    ?>
<div role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?php
if(isset($_POST["submit"]) or isset($_POST["submit2"])) { echo $userSave; }
if(isset($_GET["delete"])) {	
    echo "<div align='center'><h2>M&ouml;chtest du den Benutzer</h2><h1 style='font-style:italic'><a href='#'>".$row["TelegramUser"]."</a></h1><h2>unwiderruflich l&ouml;schen?</h2>";
    ?>
    <form method="post" action="index.php">
        <a class="btn btn-sm btn-outline-secondary" href="_edit_user.php?id=<?=$id?>" role="button">abbrechen</a>
        <input type="hidden" name="username" value="<?=$row["TelegramUser"]?>" />
        <input type="hidden" name="email" value="<?=$row["buyerEmail"]?>" />
        <input type="hidden" name="deleteUser" value="<?=$row["id"]?>" />
        <input type="submit" class="btn btn-sm btn-outline-secondary" value="Benutzer l&ouml;schen" />
    </form>
    <?php
    echo "</div>";
} else { ?>
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="?id=<?=$id?>&delete=<?=$id?>" role="button">Benutzer l&ouml;schen</a>
<h1>Benutzer umbenennen</h1>
<form name="one" method="post"> 
  <table class="table">
    <tr>
      <th scope="col">Aktueller @Username<br><span style="font-size:12px;font-weight:normal"><?php if($row["city"]) { echo 'aus '.$row["city"]; } ?></span></th>
      <th scope="col"><?=$row["TelegramUser"] ?> <span style="font-size:12px"><?php if($row["buyerEmail"]) { echo '('.$row["buyerEmail"].')'; } ?></span></th>
    </tr>
    <tr>
      <th scope="col">Passwort<br><span style="font-size:12px;font-weight:normal">max. 16 Zeichen</span></th>
      <th scope="col"><input type="text" name="pass" class="form-control" autocomplete="off" maxlength="16" placeholder="leave blank to generate a password" value="<?=$row["pass"] ?>" /></th>
    </tr>
    <tr>
      <th scope="col"><b>Neuer @Username</b></th>
      <th scope="col"><input type="text" name="user" id='autocomplete' class="form-control" autocomplete="off" required /></th>
    </tr>
    <?php if($use_map == "PMSF") { ?>
    <tr>
      <th scope="col">Neue Emailadresse</th>
      <th scope="col"><input type="text" name="email" class="form-control" autocomplete="off" required /></th>
    </tr>
    <?php } ?>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="User &auml;ndern!" /></th>
    </tr>
  </table>
</form>
<h1>Abo verl&auml;ngern</h1>
<form name="two" method="post"> 
  <table class="table">
    <tr>
      <th scope="col">Abonnent</th>
      <th scope="col"><?=$row["TelegramUser"] ?></th>
    </tr>
    <tr>
      <th scope="col">Bar erhalten<br><span style="font-size:12px;font-weight:normal">(Preis pro Tag <b><?=floatval($schnitt_output) ?></b> Euro)</span></th>
      <th scope="col"><input style="background:#FF0000; color:#FFFF00" type="text" name="itemprice" class="form-control" autocomplete="off" placeholder="&euro;"></th>
    </tr>
    <tr>
      <th scope="col">Abo endet am (prio)</th>
      <th scope="col"><input style="background:#FF0000; color:#FFFF00" type="text" id="datepicker" name="setAbo" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD"></th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit2" value="Abo verl&auml;ngern!" /></th>
    </tr>
  </table>
</form>
<?php
}
?>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php
        die();
    }
    $sumBar = mysqli_real_escape_string($mysqli, $_POST["itemprice"]);
    $sumBar = empty($sumBar) ? 0 : str_replace(",",".", $sumBar);
    //$sumBar = str_replace(",",".", $sumBar);
        
    $amountInsert = $row["Amount"];
    $amountInsert+=$sumBar;
    
    $userid = $row["userid"];
    $testDate1 = date_create(date('Y-m-d H:i:s',time()));
    $testDate2 = date_create(date('Y-m-d H:i:s',strtotime($row["endtime"])));
    
    if(isset($_POST["setAbo"]) && date("Y-m-d") < $_POST["setAbo"]) {
      $date = mysqli_real_escape_string($mysqli, $_POST["setAbo"]);
      $dateInsert = "cast('$date 23:59:59' AS datetime)";
    } else {
      $days_to_end = $_POST["itemprice"]/$schnitt;
      $days_to_end = ceil($days_to_end);

      // check of curr date
      if($testDate1 > $testDate2) {
        $date = date('Y-m-d H:i:s', strtotime('+'.$days_to_end.' days'));
      } else {
        $date = date('Y-m-d H:i:s', strtotime($row["endtime"]. " + {$days_to_end} days"));
      }
      $dateInsert = "'".$date."'";
    }
    
    if($use_map == "PMSF") {
        $datum = new DateTime($date);
        $datum = $datum->getTimestamp();
        $expire_timestamp = $datum;
        
        $check_user = $mysqli->query("SELECT id FROM users WHERE user = '".$row["buyerEmail"]."' ");
        if($check_user->num_rows != 0) {
            $update_user = $check_user->fetch_array();
            mysqli_query($mysqli, "UPDATE users SET expire_timestamp = '".$expire_timestamp."' WHERE id = ".$update_user["id"]);
        }
    } elseif($use_map == "Rocketmap") {
        // give costum passwort or generate automaticle
        if($row["pass"] != '') {
          $passwd = $row["pass"];
        } else {
          $passwd = generateRandomString(8);
        }
        // generate new acc
        include("../Htpasswd.php");
        $htpasswd = new Htpasswd('../.htpasswd');
        $load_htpasswd = file_get_contents('../.htpasswd');
        if(is_bool(strpos($load_htpasswd, $row["TelegramUser"])) === false) {
            $htpasswd->deleteUser($row["TelegramUser"]);
        }
        $htpasswd->addUser($row["TelegramUser"], $passwd);
    }
    
    mysqli_query($mysqli, "UPDATE ".$tbl." SET Amount = $amountInsert, TransID = NULL, paydate = now(), endtime = $dateInsert, info = NULL WHERE id = ".$row["id"]);
    
    include_once("msg.php");
    
    $all_channels = $mysqli->query("SELECT * FROM channels");
    while($unsert_bann = $all_channels->fetch_array()) {
        $ItemDesc = $row["TelegramUser"];		
        $chat_id = $unsert_bann["chatid"];
        $editBanned = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[participant]=$ItemDesc&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=0&data[banned_rights][_]=chatBannedRights", false);
    }
    
    if($botSend == '1') {
        $botMessage = urlencode($extendUserMsg);
        $mailMessage= nl2br($extendUserMsg);
        $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$userid&data[message]=$botMessage&data[parse_mode]=html&data[no_webpage]=1", false);
        APIlog($sendMessage, $userid);
    }
                    
    if($mailSend == '1') {
        
        $statement = "update";
        $empfaenger	= $row["buyerEmail"];
        require_once('../mailer/class.phpmailer.php');

        $mail             = new PHPMailer();
        $mail->CharSet	  = 'utf-8';

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
        
        $mail->Subject    = mb_encode_mimeheader($mailSubject,"UTF-8");
        $mail->AltBody    = strip_tags($mailMessage); // optional, comment out and test
        $mail->MsgHTML($mailMessage);
        $mail->AddAddress($empfaenger, $WebsiteTitle);

        $mail->Send();
    }
    
    $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Abo verl&auml;ngert auf ".$date."</h3>";
    
}
?>
<div role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?php
if(isset($_POST["submit"]) or isset($_POST["submit2"])) { echo $userSave; }
if(isset($_GET["delete"])) {	
    echo "<div align='center'><h2>M&ouml;chtest du den Benutzer</h2><h1 style='font-style:italic'><a href='#'>".$row["TelegramUser"]."</a></h1><h2>unwiderruflich l&ouml;schen?</h2>";
    ?>
    <form method="post" action="index.php">
        <a class="btn btn-sm btn-outline-secondary" href="_edit_user.php?id=<?=$id?>" role="button">abbrechen</a>
        <input type="hidden" name="username" value="<?=$row["TelegramUser"]?>" />
        <input type="hidden" name="email" value="<?=$row["buyerEmail"]?>" />
        <input type="hidden" name="deleteUser" value="<?=$row["id"]?>" />
        <input type="submit" class="btn btn-sm btn-outline-secondary" value="Benutzer l&ouml;schen" />
    </form>
    <?php
    echo "</div>";
} else { ?>
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" role="button">zur&uuml;ck</a>
<a class="btn btn-sm btn-outline-secondary" style="margin-bottom:20px" href="?id=<?=$id?>&delete=<?=$id?>" role="button">Benutzer l&ouml;schen</a>
<h1>Benutzer umbenennen</h1>
<form name="one" method="post"> 
  <table class="table">
    <tr>
      <th scope="col">Aktueller @Username<br><span style="font-size:12px;font-weight:normal"><?php if($row["city"]) { echo 'aus '.$row["city"]; } ?></span></th>
      <th scope="col"><?=$row["TelegramUser"] ?> <span style="font-size:12px"><?php if($row["buyerEmail"]) { echo '('.$row["buyerEmail"].')'; } ?></span></th>
    </tr>
    <tr>
      <th scope="col">Passwort<br><span style="font-size:12px;font-weight:normal">max. 16 Zeichen</span></th>
      <th scope="col"><input type="text" name="pass" class="form-control" autocomplete="off" maxlength="16" placeholder="leave blank to generate a password" value="<?=$row["pass"] ?>" /></th>
    </tr>
    <tr>
      <th scope="col"><b>Neuer @Username</b></th>
      <th scope="col"><input type="text" name="user" id='autocomplete' class="form-control" autocomplete="off" required /></th>
    </tr>
    <?php if($use_map == "PMSF") { ?>
    <tr>
      <th scope="col">Neue Emailadresse</th>
      <th scope="col"><input type="text" name="email" class="form-control" autocomplete="off" required value="<?=$row["buyerEmail"]?>"/></th>
    </tr>
    <?php } ?>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="User &auml;ndern!" /></th>
    </tr>
  </table>
</form>
<h1>Abo verl&auml;ngern</h1>
<form name="two" method="post"> 
  <table class="table">
    <tr>
      <th scope="col">Abonnent</th>
      <th scope="col"><?=$row["TelegramUser"] ?></th>
    </tr>
    <tr>
      <th scope="col">Bar erhalten<br><span style="font-size:12px;font-weight:normal">(Preis pro Tag <b><?=floatval($schnitt_output) ?></b> Euro)</span></th>
      <th scope="col"><input type="text" name="itemprice" class="form-control" autocomplete="off" placeholder="&euro;"></th>
    </tr>
    <tr>
      <th scope="col">Abo endet am (prio)</th>
      <th scope="col"><input type="text" id="datepicker" name="setAbo" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD"></th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><input class="btn btn-sm btn-outline-secondary" type="submit" name="submit2" value="Abo verl&auml;ngern!" /></th>
    </tr>
  </table>
</form>
<?php
}
?>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
