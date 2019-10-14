<h2>Willkommen bei <?=$WebsiteTitle?></h2>
<?php if($statement == "insert") { ?>
<p>Dein Abo wurde eingerichtet und du erh&auml;lst alle n&ouml;tigen Daten um die Map zu benutzen.</p>
<p>Den ausgew&auml;lten Kan&auml;len bist du schon automatisch beigetreten, um die Map zu verwenden, benutze die folgende URL mit folgenden Login Daten:</p>
<h3><a href="<?=$urlMap?>"><?=$urlMap?></a></h3>
<p>Login- Name: <b><?=$loginName?></b><br />Passwort: <b><?=$passwd?></b></p>
<?php } elseif($statement == "insert") { ?>
<p>Dein Abo wurde erfolgreich verl&auml;ngert. An deinen Login Daten hat sich nichts ge&auml;ndert!</p>
<?php } ?>
<p>Dein Abo endet automatisch am <b><?=date('d.m.Y', strtotime("+30 day", strtotime($date)))?></b> ab und wird nicht verl&auml;ngert! Du hast dann keinen Zugriff mehr auf unsere Kan&auml;le und die MAP</p>
<p>Viel Erfolg und GO! Trainer!</p>