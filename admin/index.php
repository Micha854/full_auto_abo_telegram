<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<title><?=$WebsiteTitle ?> - ADMIN</title>
</head>
<body>
<p>
<main role="main" class="container">
<?php include "nav.php"; ?>
<p>
<div class="jumbotron">
	<table class="table">
	<thead class="thead-light">
  <tr>
    <th scope="col">Telegram Username</th>
	<th scope="col">TempPW</th>
    <th scope="col">Bezahlt am:</th>
    <th scope="col">Läuft ab:</th>
    <th scope="col"></th>
  </tr>
  </thead>
<?php
require_once(__DIR__.'/../config.php');
					
//Output any connection error
if ($mysqli->connect_error) {
	die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
					
$query = "SELECT * FROM ".$tbl." ORDER BY endtime ASC";
$result = $mysqli->query($query);

while($row = $result->fetch_array()) { ?>
  <tr>
    <td><a href="https://t.me/<?=substr($row["TelegramUser"], 1) ?>"><?=$row["TelegramUser"] ?></a></td>
	<td><?=$row["pass"] ?></td>
    <td><?=$row["paydate"] ?></td>
    <td><?=$row["endtime"] ?></td>
	<td><a class="btn btn-sm btn-outline-secondary" href="_edit_user.php?id=<?=$row["id"]?>" role="button">edit</a></td>
  </tr>
<?php
}
?>
</table>
<a class="btn btn-sm btn-outline-secondary" href="_newUser.php" role="button">Neuer User</a>
<a class="btn btn-sm btn-outline-secondary" href="<?=$urlMap ?>" role="button">zur Map</a>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     </div>
  </main>
</body>
</html>
