<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Pogo L&uuml;beck - ADMIN</title>
<style type="text/css">
table {
   border-collapse: collapse; 
}
td {
	border:solid #CCCCCC 1px;
	padding:2px
}
</style>
</head>

<body>
<table width="100%" border="0">
  <tr>
    <td bgcolor="#FFFFCC"><b>Telegram @user</b></td>
	<td bgcolor="#FFFFCC"><b>pass [QuestMap]</b></td>
    <td bgcolor="#FFFFCC"><b>paydate</b></td>
    <td bgcolor="#FFFFCC"><b>enddate</b></td>
    <td bgcolor="#FFFFCC"><b>@user change</b></td>
  </tr>
<?php
require_once('../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
					
$query = "SELECT * FROM ".$tbl." ORDER BY id DESC";
$result = $mysqli->query($query);

while($row = $result->fetch_array()) { ?>
  <tr>
    <td><a href="https://t.me/<?=substr($row["TelegramUser"], 1) ?>"><?=$row["TelegramUser"] ?></a></td>
	<td><?=$row["pass"] ?></td>
    <td><?=$row["paydate"] ?></td>
    <td><?=$row["endtime"] ?></td>
    <td><a href="_edit_user.php?id=<?=$row["id"]?>">edit</a></td>
  </tr>
<?php
}
?>
</table>
</body>
</html>
