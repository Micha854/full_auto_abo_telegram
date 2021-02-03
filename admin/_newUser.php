<?php
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/../functions.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" type="image/png" href="logo.png" sizes="32x32">
<link rel="icon" type="image/png" href="logo.png" sizes="96x96">
<title><?=$WebsiteTitle ?> - ADMIN NEW USER</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
    #country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
    #country-list li{padding: 10px; background: #fff; border-bottom: #bbb9b9 1px solid;}
    #country-list li:hover{background:#E9ECEF;cursor: pointer;}
  </style>

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <script>
    $(function() {
      $( "#datepicker" ).datepicker({
        firstDay: 1,
        dateFormat: "yy-mm-dd"
      });
    });
  </script>
  
  <script>
    // AJAX call for autocomplete 
    $(document).ready(function(){
      $("#search-box").keyup(function(){
        $.ajax({
          type: "POST",
          url: "request_city.php",
          data:'keyword='+$(this).val(),
          beforeSend: function(){
            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
          },
          success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
          }
        });
      });
    });
    //To select country name
    function selectCountry(val) {
      $("#search-box").val(val);
      $("#suggesstion-box").hide();
    }
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

$query = "SELECT SUM(item_price) as total, SUM(abo_days) as abo, COUNT(id) as menge FROM products";
$result = $mysqli->query($query);
$row = $result->fetch_array();

$schnitt = $row["total"]/$row["abo"];	// durchschnittlicher preis pro tag


if(isset($_POST["submit"]) and $_POST["user"]) {
    
    function generateRandomString($length = 10) {
        //return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    
    // give costum passwort or generate automaticle
    if($_POST["pass"] != '') {
      $passwd = mysqli_real_escape_string($mysqli, $_POST["pass"]);
    } else {
      $passwd = generateRandomString(8);
    }
    
    $newUser   = $_POST["user"];
    $getInfo   = callAPI('GET', $apiServer."getfullInfo/?id=".$newUser, false);
    $getUserId = json_decode($getInfo, true);
    $userid		 = $getUserId["response"]["InputPeer"]["user_id"];

    if($userid) {
      $useridnow = ", userid = '$userid'";
      $newUser   = '@'.$getUserId["response"]["User"]["username"];
    }

    $newCity = ucfirst(mysqli_real_escape_string($mysqli, $_POST["city"]));
    $newMail = mysqli_real_escape_string($mysqli, $_POST["email"]);
    
    $sumBar = $_POST["itemprice"];
    $sumBar = empty($sumBar) ? 0 : str_replace(",",".", $sumBar);
    //$sumBar = str_replace(",",".", $sumBar);
    
    $days_to_end = $_POST["itemprice"]/$schnitt;
    $days_to_end = ceil($days_to_end);

    if($AccessAllChannels === false) {
        $InputChannel = array();
        $InputChannel = $_POST["added"];  
    } else {
        $InputChannel = NULL;
    }
                    
    // NEW USER OR UPDATE
    $check = $mysqli->query("SELECT * FROM ".$tbl." WHERE TelegramUser = '".$newUser."' ");
    $row_cnt = $check->num_rows;
    
    if($row_cnt != 0) {
        $update = $check->fetch_array();
        $statement = "update";
        $passwd = $update["pass"];
        
        $testDate1 = date_create(date('Y-m-d H:i:s',time()));
        $testDate2 = date_create(date('Y-m-d H:i:s',strtotime($update["endtime"])));

        if(isset($_POST["setAbo"]) && date("Y-m-d") < $_POST["setAbo"]) {
            $date = mysqli_real_escape_string($mysqli, $_POST["setAbo"]);
            $dateInsert = "cast('$date 23:59:59' AS datetime)";
        } else {
            // check of curr date
            if($testDate1 > $testDate2) {
              $date = date('Y-m-d H:i:s', strtotime('+'.$days_to_end.' days'));

              if($use_map == "Rocketmap") {
                // generate new acc
                include("../Htpasswd.php");
                $htpasswd = new Htpasswd('../.htpasswd');
                $htpasswd->addUser($update["TelegramUser"], $update["pass"]);
              }
      
            } else {
              $date = date('Y-m-d H:i:s', strtotime($update["endtime"]. " + {$days_to_end} days"));
            }
            $dateInsert = $date;
        }
        $amountInsert = $update["Amount"];
        $amountInsert+=$sumBar;
    } else {
        $statement = "insert";
        //$date = new DateTime();
        //$date->modify('+$days_to_end day');
        //$date = $date->format('Y-m-d H:i:s');
        if(isset($_POST["setAbo"]) && date("Y-m-d") < $_POST["setAbo"]) {
            $date = $_POST["setAbo"];
            $dateInsert = "cast('$date 23:59:59' AS datetime)";
        } else {
            $date = date('Y-m-d H:i:s', strtotime('+'.$days_to_end.' days'));
            $dateInsert = "NOW() + INTERVAL $days_to_end DAY";
        }
        $amountInsert = $sumBar;
    }
    
    if(empty($_POST["itemprice"]) && date("Y-m-d") >= $_POST["setAbo"] or empty($_POST["setAbo"]) && empty($_POST["itemprice"]) or !$useridnow) {
      if(!$useridnow) {
        $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Username \"$newUser\" existiert nicht!!</h3>";
        $username_err = 'background:#FF0000; color:#FFFF00';
        $abo_err = '';
      } else {
        $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Bitte ein \"Abo Ende\" oder \"Betrag\" angeben!!</h3>";
        $username_err = '';
        $abo_err = 'background:#FF0000; color:#FFFF00';
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
      <input style="<?=$username_err ?>" type="text" name="user" value="<?=$newUser?>" class="form-control" autocomplete="off" aria-describedby="telegramname @" placeholder="@" required>
    </div>
    <div class="form-group">
      <p class="lead">Bereich:</p>
      <input type="text" name="city" id="search-box" value="<?=$newCity?>" class="form-control" autocomplete="off" aria-describedby="City" placeholder="City" required>
      <div id="suggesstion-box"></div>
    </div>
    <div class="form-group">
      <p class="lead">Passwort: (max. 16 Zeichen)</p>
      <input type="text" name="pass" value="<?=$passwd?>" class="form-control" autocomplete="off" maxlength="16" aria-describedby="passwort" placeholder="leave blank to generate a password" />
    </div>
    <div class="form-group">
      <p class="lead">eMail:</p>
      <input type="email" name="email" value="<?=$newMail?>" class="form-control" autocomplete="off" placeholder="Emailadresse" required>
    </div>
    <div class="form-group">
      <p class="lead">Bar erhalten</p>
      <input style="<?=$abo_err ?>" type="text" name="itemprice" autocomplete="off" class="form-control" placeholder="&euro;">
    </div>
    <div class="form-group">
      <p class="lead">Abo endet am (prio)</p>
      <input style="<?=$abo_err ?>" type="text" id="datepicker" autocomplete="off" name="setAbo" class="form-control" placeholder="YYYY-MM-DD">
    </div>
    <div>
      <table>
        <tr>
          <td colspan="2"><b>Channels:</b><br></td>
        </tr>
          <?php
          if($AccessAllChannels === false) {
            foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
              echo "<tr>";
              echo "<td>".$channel["name"]."</td><td valign='top'>beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr>";
            echo "<td>Zugang zu allen Channels</td>";
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php
        die();
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
            //$htpasswd->deleteUser($newUser);
            $htpasswd->addUser($newUser, $passwd);
        }
                        
        $empfaenger	= $newMail;
        $loginName	= $newUser;
    }
                    
    else {
        $empfaenger	= $newMail;
    }

    $InputChannels = implode(',',$InputChannel);
    
    if($statement == "insert") {
        $sql_insert = "INSERT INTO ".$tbl." SET buyerName = '', city = '$newCity', buyerEmail = '$empfaenger', Amount = '$amountInsert', TelegramUser = '$newUser'".$useridnow.", channels = '$InputChannels', pass = '$passwd', TransID = NULL, paydate = now(), endtime = '$dateInsert' ";
        #print_r($sql_insert);
        $mysqli->query($sql_insert);
    } elseif($statement == "update") {
        mysqli_query($mysqli, "UPDATE ".$tbl." SET city = '$newCity', buyerEmail = '$empfaenger', Amount = $amountInsert, channels = '$InputChannels', endtime = '$dateInsert', info = NULL WHERE id = ".$update["id"]);
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
        $all_channels = $mysqli->query("SELECT * FROM channels WHERE id IN (".$InputChannels.")");
    } else {
        $all_channels = $mysqli->query("SELECT * FROM channels");
    }

    while($unsert_bann = $all_channels->fetch_array()) {		
        $chat_id = $unsert_bann["chatid"];
        $editBanned = callAPI('GET', $apiServer."channels.editBanned/?data[channel]=$chat_id&data[user_id]=$newUser&data[banned_rights][until_date]=0&data[banned_rights][view_messages]=0&data[banned_rights][_]=chatBannedRights", false);
    }
    
    if($botSend == '1') {
        $sendMessage = callAPI('GET', $apiServer."sendMessage/?data[peer]=$userid&data[message]=$botMessage&data[parse_mode]=html&data[no_webpage]=1", false);
    }
                    
    if($mailSend == '1') {
        
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
        
        $mail->Subject    = mb_encode_mimeheader($mailSubject,"UTF-8");;
        $mail->AltBody    = strip_tags($mailMessage); // optional, comment out and test
        $mail->MsgHTML($mailMessage);
        $mail->AddAddress($empfaenger, $WebsiteTitle);

        $mail->Send();
    }
    $userSave = "<h3 style=\"background:#333333; color:#00CC00; padding:5px; text-align:center\">Neuer Benutzer ".$newUser." wurde erstellt!</h3>";
                                    
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
      <input type="text" name="user" class="form-control" autocomplete="off" aria-describedby="telegramname @" placeholder="@" required>
    </div>
    <div class="form-group">
      <p class="lead">Bereich:</p>
      <input type="text" name="city" id="search-box" class="form-control" autocomplete="off" aria-describedby="City" placeholder="City">
      <div id="suggesstion-box"></div>
    </div>
    <div class="form-group">
      <p class="lead">Passwort: (max. 16 Zeichen)</p>
      <input type="text" name="pass" class="form-control" autocomplete="off" maxlength="16" aria-describedby="passwort" placeholder="leave blank to generate a password" />
    </div>
    <div class="form-group">
      <p class="lead">eMail:</p>
      <input type="email" name="email" class="form-control" autocomplete="off" placeholder="Emailadresse" required>
    </div>
    <div class="form-group">
      <p class="lead">Bar erhalten</p>
      <input type="text" name="itemprice" class="form-control" autocomplete="off" placeholder="&euro;">
    </div>
    <div class="form-group">
      <p class="lead">Abo endet am (prio)</p>
      <input type="text" id="datepicker" name="setAbo" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
    </div>
    <div>
      <table>
        <tr>
          <td colspan="2"><b>Channels:</b><br></td>
        </tr>
          <?php
          if($AccessAllChannels === false) {
            foreach ( $mysqli->query("SELECT * FROM channels ORDER BY name ASC") as $channel ) {
              echo "<tr>";
              echo "<td>".$channel["name"]."</td><td valign='top'>beitreten <input type='checkbox' name='added[]' value='".$channel["id"]."' checked='checked' /></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr>";
            echo "<td>Zugang zu allen Channels</td>";
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>