<?php
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
include 'Htpasswd.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

while($row = $result->fetch_array())
{
echo "delete ".$row["TelegramUser"]."<br>";
$InputUser = $row["TelegramUser"]; // username
$ChatBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'until_date' => 0];

$Updates = $MadelineProto->channels->editBanned(['silent' => false, 'channel' => $InputChannel, 'user_id' => $InputUser, 'banned_rights' => $ChatBannedRights, ]);
mysqli_query($mysqli, "DELETE FROM ".$tbl." WHERE id = ".$row["id"]." ");

$htpasswd = new Htpasswd('.htpasswd_user');
$htpasswd->deleteUser($InputUser);
}
?>