<?php
if (!isset($_SESSION)) {
  session_start();
}
ob_start();

// Importer les constantes et changer le titre de la page
require('utils/config.php');
$page_title = 'Connexion';

include(FUNCTION_LOGIN);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- START INCLUDE META -->
  <?php
  include(VIEW_META);
  ?>
  <!-- END INCLUDE META -->
  <link rel="stylesheet" href="styles/login.css">
</head>

<body class="text-center d-flex align-items-center">

  <?php if (isset($_GET['error'])) { ?>
    <div class="alert alert-danger popup-alert mt-4" role="alert">
      Les identifiants ne correspondent pas !
    </div>
  <?php } ?>

  <form class="form-signin" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
    <img src="images/tim-logo.png" alt="Logo de la Technique d'intégration multimédia du Cégep de l'Outaouais" width="150" height="150">
    <h2 class="h3 mb-3 font-weight-normal">Rapports de stage</h2>
    <label class="visually-hidden" for="username">Utilisateur</label>
    <input class="form-control" type="text" id="username" name="username" value="<?= $username ?>" placeholder="Utilisateur" required autofocus>
    <label class="visually-hidden" for="password">Mot de passe</label>
    <input class="form-control" type="password" id="password" name="password" placeholder="Mot de passe" required>
    <div class="d-grid">
      <button class="btn btn-lg btn-primary mt-3" type="submit" name="login_user">Connexion</button>
    </div>
    <p class="mt-5 mb-3 text-muted">Copyright &copy; <?= date("Y") ?></p>
  </form>

  <!-- START INCLUDE FOOTER -->
  <?php
  include(VIEW_FOOTER);
  ?>
  <!-- END INCLUDE FOOTER -->

</body>

</html>
