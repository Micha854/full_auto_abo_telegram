<?php
echo '<div style="display:none">';
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$InputUser = $ItemDesc; // username

foreach($InputChannel as $joinded)
$Updates = $MadelineProto->channels->inviteToChannel(['silent' => false, 'channel' => $joinded, 'users' => [$InputUser, $InputUser], ]);
echo '</div>';
?>