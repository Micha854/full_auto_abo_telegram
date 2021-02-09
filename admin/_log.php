<?php
require_once dirname(__FILE__) . '/../config.php';
                    
//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

// filename of log
$filename = "response.txt";

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
<title><?=$WebsiteTitle ?> - ADMIN LOG</title>
</head>

<body>
<div role="main" class="container">
<?php include "nav.php"; ?>
<div class="jumbotron">
<?php
if(isset($_GET["delete"])) {
    
    echo "<div style='text-align:center'><h2>M&ouml;chtest du das Log wirklich leeren?</h2>";
    ?>
    <form method="post" action="_log.php">
        <a class="btn btn-sm btn-outline-secondary" href="_log.php" role="button">abbrechen</a>
        <input type="hidden" name="deleteLog" />
        <input type="submit" class="btn btn-sm btn-outline-secondary" value="Log leeren" />
    </form>
    <?php
    echo "</div>";
} else {

    if(isset($_POST["deleteLog"])) {
        $fp=fopen($filename, "w");
        fclose($fp);
        echo '<h3 style="background:#333333; color:#00CC00; padding:5px; text-align:center">Das Log wurde geleert !</h3>';
    }   

    echo '<h1>Log <a class="btn btn-sm btn-outline-secondary" href="?delete=log" role="button">Log leeren</a></h1>';
    $n = 0;
    $log = array();

    if (file_exists($filename)) {
        $zitate = file($filename);
        for($i=1;$i < count($zitate); $i++){
            if(!isset($log[0])) {
                $log[0] = $zitate[0];
            }
            if (preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/", $zitate[$i])) {
                $n++;
                $log[$n] = $zitate[$i];
            } else {
                $log[$n] .= $zitate[$i];
            }
        }

        rsort($log);

        $e = count($log);
        if($e == 0) {
            echo "<br><p>sorry, es gibt keine Einträge...</p>";
        }
        foreach($log AS $block) {
            echo '<hr><b>Eintrag ['.$e.']</b> '.nl2br($block);
            $e--;
        }
    } else {
        echo "<br><p>sorry, die Datei '$filename' existiert nicht...</p>";
    }
}
?>

</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
