<h2>Willkommen bei <?=$WebsiteTitle?></h2>
<?php if($statement == "insert") { ?>
<p>Dein Abo wurde eingerichtet und du erh&auml;ltst alle n&ouml;tigen Daten um die Map zu benutzen.</p>
<p><b>Du kannst nun folgenden Kan&auml;len beitreten:</b></p>
<p><em><?=utf8_decode($joinMail)?></em></p>
<p>Um die Map zu verwenden, benutze die folgende URL mit folgenden Login Daten:</p>
<h3><a href="<?=$urlMap?>"><?=$urlMap?></a></h3>
<p>Login- Name: <b><?=$loginName?></b><br />Passwort: <b><?=$passwd?></b></p>
<?php } elseif($statement == "update") { ?>
<p>Dein Abo wurde erfolgreich verl&auml;ngert. An deinen Login Daten hat sich nichts ge&auml;ndert!</p>
<p><b>Links zu den Kan&auml;len:</b></p>
<p><em><?=utf8_decode($joinMail)?></em></p>
<?php } ?>
<p>Dein Abo endet automatisch am <b><?=date('d.m.Y', strtotime($date))?></b> und wird nicht verl&auml;ngert! Du hast dann keinen Zugriff mehr auf unsere Kan&auml;le und die MAP</p>
<p>Viel Erfolg und GO! Trainer!</p>
