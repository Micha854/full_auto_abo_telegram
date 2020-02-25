<h2>Willkommen bei <?=$WebsiteTitle?></h2>
<?php
if($statement == "insert") {
    echo $emailInsertMsg;
    echo "<p><em>".utf8_decode($joinMail)."</em></p>";
} elseif($statement == "update") {
    echo $emailUpdateMsg;
    echo "<p><em>".utf8_decode($joinMail)."</em></p>";
}	echo $emailLastMsg;
?>
