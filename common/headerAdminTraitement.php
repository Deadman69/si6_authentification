<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="../../index.php">Accueil</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../../members/index.php">Home User<span class="sr-only"></span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="../../members/shoutbox.php">Shoutbox<span class="sr-only"></span></a>
      </li>
      <li class="nav-item inactive">
        <a class="nav-link" href="../../members/conversations.php">Private Messages<span class="sr-only"></span></a>
      </li>
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="../../members/disconnect.php">Disconnect</a></button>

      <?php 
        if($_SESSION['user_rank'] == "moderator" || $_SESSION['user_rank'] == "admin")
        {
          echo '<button class="btn btn-outline-success my-3 my-sm-0" type="submit"><a href="../../admin/index.php">Admin Page</a></button>';
        }
      ?>
    </ul>
  </div>
</nav>
