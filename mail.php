<h2>Willkommen bei <?=$WebsiteTitle?></h2>
<?php
if($statement == "insert") {
	echo $emailInsertMsg;
} elseif($statement == "update") {
	echo $emailUpdateMsg;
}	echo $emailLastMsg;
?>
