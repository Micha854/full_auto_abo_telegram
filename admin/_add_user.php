<?php
echo '<div style="display:none">';
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$InputUser = $ItemDesc; // username

$Updates = $MadelineProto->channels->inviteToChannel(['silent' => false, 'channel' => $InputChannel, 'users' => [$InputUser, $InputUser], ]);
echo '</div>';
?>