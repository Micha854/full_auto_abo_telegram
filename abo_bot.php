<?php
require_once dirname(__FILE__) . '/config.php';
include dirname(__FILE__) . '/Telegram.php';

$telegram = new Telegram($BotToken);

$result = $telegram->getData();

//log every interaction with the bot
//file_put_contents('admin/request.txt', date('Y-m-d H:i:s') . ': ' . var_export($result, true) . PHP_EOL, FILE_APPEND);

$text = $telegram->Text();
$chat_id = $telegram->ChatID();


$userId = $telegram->UserID();
$userName = $telegram->Username();
$userFullName = trim($telegram->FirstName() . ' ' . $telegram->LastName());

$content = array();

switch ($text) {
    case '/start':
        include_once("admin/msg.php");
        $content['text'] = $botStartMsg;
        $content['reply_markup'] = get_no_abo_buttons();
        break;
    case '/abo':

        $query = $mysqli->query("SELECT * FROM ".$tbl." WHERE userid = $userId ");
        $row_cnt = $query->num_rows;
        $row = $query->fetch_array();

        $now = date_create(date('Y-m-d H:i:s',time()));
        $endtime = date_create(date('Y-m-d H:i:s',strtotime($row["endtime"])));

        if ($row_cnt == 0 || $now > $endtime) {
            include_once("admin/msg.php");
            $content['text'] = $botUserFalse;
            $content['reply_markup'] = get_no_abo_buttons();

            if($userName) {
                if($row_cnt == 0) {
                    $mysqli->query("INSERT INTO ".$tbl." SET userid = $userId, TelegramUser = '@$userName', buyerName = '$userFullName', interaktion = $now, info = 2");
                } else {
                    $mysqli->query("UPDATE ".$tbl." SET TelegramUser = '@$userName', buyerName = '$userFullName', interaktion = $now, info = 2 WHERE userid = $userId");
                }
            }

        } else {
            if($use_map == "PMSF") {
                $loginName = $row["buyerEmail"];
            } else {
                $loginName = $row["TelegramUser"];
            }
            $passwd = $row["pass"];
            $date = $row["endtime"];
            if($AccessAllChannels === false) {
                $InputChannel = array();
                $InputChannel = $row["channels"];
            } else {
                $InputChannel = NULL;
            }
            
            include_once("admin/msg.php");

            $content['text'] = $botUserTrue;
            $content['reply_markup'] = get_no_abo_buttons();
        }
        break;

    default:
        break;
}

//antworte nur wenn was zum antworten da ist
if ($content && $userId == $chat_id) {
    $content['chat_id'] = $chat_id;
    $content['parse_mode'] = 'html';
    $content['disable_web_page_preview'] = 'true';
    file_put_contents('admin/response.txt', date('Y-m-d H:i:s') . ': ' . var_export($content, true) . PHP_EOL, FILE_APPEND);
    $telegram->sendMessage($content);
}

function get_no_abo_buttons() {
    global $WebsiteUrl;
    $keyboard = array(
        'inline_keyboard' => array(
            array(
                //array('text' => 'inline', 'switch_inline_query' => 'true'),
                array('text' => 'Mein Abo', 'callback_data' => '/abo'),
                array('text' => 'Abo abschließen', 'url' => $WebsiteUrl),
            )
        )
    );

    return json_encode($keyboard);
}
