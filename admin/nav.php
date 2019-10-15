<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?=dirname($_SERVER["SCRIPT_NAME"])?>">Reset</a>
        </div>
      </li>
    </ul>
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Suche" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Suche</button>
        </form>
  </div>
</nav>
