<?php
session_name ( 'ABO' );
session_start();
if(isset($_GET["reset"])) {
  session_destroy();
} elseif(isset($_GET["spalte"]) and isset($_GET["sort"])) {
  $_SESSION["sort"] = '?spalte='.$_GET["spalte"].'&sort='.$_GET["sort"];
  $sortIndex = $_SESSION["sort"];
} elseif(isset($_SESSION["sort"])) {
  $sortIndex = $_SESSION["sort"];
}

$path = $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'];
$file = basename($path, ".php");
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a href="<?=dirname($_SERVER["SCRIPT_NAME"])?>"><img src="logo.png" width="48" style="margin:-4px 2px -4px -12px" /></a>
  <a class="navbar-brand" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>">Adminpanel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Benutzercenter
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>">Übersicht</a>
          <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>/_newUser.php">Hinzufügen</a>
      <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>/_channels.php">Channels</a>
      <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>/_products.php">Abos</a>
      <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>/_log.php">Log</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"]).'/?reset=1' ?>">Reset</a>
        </div>
      </li>
    </ul>
    <?php
      if($file == 'index') { ?>
        <form class="form-inline mt-2 mt-md-0" method="post" action="">
          <input class="form-control mr-sm-2" type="text" placeholder="Suche" name="Search" aria-label="Search">
          <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="searchSubmit" value="Suche" />
        </form>
      <?php } ?>
  </div>
</nav>
<?php
$lastCron = file_get_contents('cron.txt');
if(!$lastCron) {
  $lastCron = 'UNKNOWN';
  $cronColorBack = '#FF0000';
  $cronColorText = '#FFFF00';
} else {
  $cronColorBack = '#009900';
  $cronColorText = '#FFFFFF';
}
?>
<div style="text-align:center; background:<?=$cronColorBack?>; color:<?=$cronColorText?>; font-weight:bolder; font-size:11px">LAST CRON UPDATE: <?=$lastCron?></div>
<style>
.container {
  position: relative;
  padding-right: 0;
  padding-left: 0;
}
.jumbotron {
  margin-bottom: 0;
  padding: 4rem 1.5rem 5rem 1.5rem;
}
@media only screen and (max-width: 575px) {
  .jumbotron {
    padding: 2rem 0.7rem 4rem 0.7rem;
  }
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  text-align: center;
  background: #212529;
  color: #f8f9fa;
  padding-top: 2px;
  padding-bottom: 5px;
}
</style>
<div class="footer">Script by @Micha854 | <a href="https://www.paypal.com/pools/c/8hBamIhqR6" target="_blank">Donate</a> | <a href="https://discord.gg/jsvX9pz" target="_blank">Help on Discord</a></div>