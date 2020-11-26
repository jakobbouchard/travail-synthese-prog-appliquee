<?php
session_start();
ob_start();
date_default_timezone_set("America/New_York");

// Importer les constantes et changer le titre de la page
require('utils/config.php');
$page_title = DISPLAY_TITLE;

include(ACCESS_CONNECTED);
include(ACCESS_NO_EMPLOYER);

// Ajouter les fonctions pour commenter et supprimer si l'utilisateur est superviseur
if ($_SESSION['userType'] == 'superviseur') {
  include(FUNCTION_DISPLAY);
}

// Si la page est accédée sans numéro de rapport, retourner à l'accueil
if (empty($_GET['id'])) {
  header('location: /');
  exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- START INCLUDE META -->
  <?php
  include(VIEW_META);
  ?>
  <!-- END INCLUDE META -->
</head>

<body>
  <!-- START INCLUDE HEADER -->
  <?php
  include(VIEW_HEADER);
  ?>
  <!-- END INCLUDE HEADER -->

  <main class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2"><?= $page_title ?></h1>
    </div>

    <?php
    include(UTIL_CONNECT);

    $sql_query = "SELECT *
                  FROM   journal
                  WHERE  numero = :id";

    try {
      $reports = $connectedDB->prepare($sql_query);
      $reports->execute([
        ':id' => $_GET['id']
      ]);
      $report = $reports->fetch();
      $connectedDB = null;
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }

    // Rediriger les étudiants qui essaient de voir le rapport d'un d'autre
    if (
      $_SESSION['userType'] == 'etudiant' &&
      $report['numetu'] != $_SESSION['username']
    ) {
      header('location: /');
      exit;
    }
    ?>
    <div class="card my-4 border-0 shadow">
      <div class="py-3 card-header bg-white">
        <div class="d-flex justify-content-between">
          <h2 class="h3">Rapport d'étape</h2>
          <h2 class="h3">
            <a class="btn btn-primary" href="index.php">
              Retourner au tableau de bord
            </a>
          </h2>
        </div>
        <h3 class="h5">
          Rapport d'étape créé par <?= $report["NomEtu"] ?>, réalisé le
          <?= $report["DateJournal"] ?>.
        </h3>
      </div>

      <div class="card-body">
        <h4>Activités significatives :</h4>
        <div class="row">
          <div class="col-md-6">
            <h5 class="h6">
              Première activité significative de cette semaine :
            </h5>
            <p><?= $report["AS1"] ?></p>
          </div>
          <div class="col-md-6">
            <h5 class="h6">
              Deuxième activité significative de cette semaine :
            </h5>
            <p><?= $report["AS2"] ?></p>
          </div>
        </div>

        <h4 class="mt-4">Apprentissages réalisés :</h4>
        <div class="row">
          <div class="col-md-6">
            <h5 class="h6">
              Premier apprentissage réalisé :
            </h5>
            <p><?= $report["AR2"] ?></p>
          </div>
          <div class="col-md-6">
            <h5 class="h6">
              Deuxième apprentissage réalisé :
            </h5>
            <p><?= $report["AR2"] ?></p>
          </div>
        </div>

        <h4 class="mt-4">Difficultés rencontrées (s'il y a lieu) :</h4>
        <div class="row">
          <div class="col-md-6">
            <h5 class="h6">
              Première difficulté rencontrée :
            </h5>
            <p><?= $report['DR1'] ?></p>
          </div>
          <div class="col-md-6">
            <h5 class="h6">
              Deuxième difficulté rencontrée :
            </h5>
            <p><?= $report['DR2'] ?></p>
          </div>
        </div>

        <h4 class="mt-4">Commentaires &mdash; Questions :</h4>
        <div class="row">
          <div class="col-md-6">
            <h5 class="h6">
              Premier commentaire :
            </h5>
            <p><?= $report['C1'] ?></p>
          </div>
          <div class="col-md-6">
            <h5 class="h6">
              Deuxième commentaire :
            </h5>
            <p><?= $report['C2'] ?></p>
          </div>
        </div>

        <?php if (!empty($report['commentaire'])) { ?>
          <h3 id="comment">Commentaire du superviseur</h3>
          <p><?= $report['commentaire'] ?></p>
        <?php
        }

        if ($_SESSION['userType'] == 'superviseur') {
        ?>
          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
          <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
            <fieldset>
              <legend class="mt-4">
                <label for="commentaire">
                  <?= empty($report['commentaire']) ? 'Ajouter' : 'Modifier' ?> un commentaire :
                </label>
              </legend>
              <div class="row">
                <div class="col-md-6">
                  <textarea class="form-control" name="commentaire" rows="5"
                    id="commentaire" <?= $_GET['action'] == 'comment' ? 'autofocus' : '' ?>
                    ></textarea>
                </div>
              </div>
            </fieldset>
            <button
              type="submit"
              name="send"
              value="comment"
              class="btn btn-primary mt-2"
              >
              <?= empty($report['commentaire']) ? 'Ajouter' : 'Modifier' ?>
            </button>
          </form>
        <?php } ?>
      </div>
    </div>
  </main>

  <!-- START INCLUDE FOOTER -->
  <?php
  include(VIEW_FOOTER);
  ?>
  <!-- END INCLUDE FOOTER -->

</body>

</html>
