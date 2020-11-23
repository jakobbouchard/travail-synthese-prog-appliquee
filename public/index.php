<?php
  session_start();
  ob_start();

  // Importer les constantes et changer le titre de la page
  require( 'utils/config.php' );
  $page_title = HOME_TITLE;

  include( ACCESS_CONNECTED );
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- START INCLUDE META -->
<?php
  include( VIEW_META );
?>
  <!-- END INCLUDE META -->
</head>

<body>
  <!-- START INCLUDE HEADER -->
<?php
  include( VIEW_HEADER );
?>
  <!-- END INCLUDE HEADER -->

  <main class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2"><?= $page_title ?></h1>
    </div>

<?php
  if ($_SESSION['userType'] == 'employeur') {
    include( UTIL_CONNECT );

    $sql_query = 'SELECT numetu,
                         nometu,
                         nomsup
                  FROM   acces_etu
                  WHERE  noemployeur = :username';

    try {
      $interns = $connectedDB->prepare($sql_query);
      $interns->execute([
        ':username' => $_SESSION['username']
      ]);

    } catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
?>
    <div class="card my-4 border-0 shadow">
      <div class="py-3 card-header bg-white">
        <h2 class="h3">Liste des stagiaires</h2>
        <h3 class="h5">Voici la liste de tous les stagiaires à évaluer.</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">Stagiaire</th>
                <th scope="col">Superviseur</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
<?php
foreach ($interns as $intern) {
?>
              <tr>
                <td><?= $intern['nometu'] ?></td>
                <td><?= $intern['nomsup'] ?></td>
                <td>
                  <a class="text-decoration-none" href="evaluations/interns/<?= $intern['numetu'] ?>.html">
                    <span class="fas fa-file fa-fw fa-2x text-secondary"></span>
                  </a>
                  <a class="text-decoration-none" href="create.php?type=evaluation?intern=<?= $intern['numetu'] ?>">
                    <span class="fas fa-fw fa-2x fa-file-signature text-danger"></span>
                  </a>
                </td>
              </tr>
<?php
}
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

<?php
  } elseif ($_SESSION['userType'] == 'superviseur') {
    include( UTIL_CONNECT );

    if (!isset($_GET['list-evaluations'])) {
      $sql_query = 'SELECT journal.numero,
                           journal.DateJournal,
                           journal.NomEtu,
                           journal.nomsup,
                           journal.commentaire,
                           `acces_employeurs`.`Nom de l\'employeur`,
                           `acces_employeurs`.`Nom de l\'entreprise`
                    FROM   journal
                           INNER JOIN acces_etu
                                   ON journal.numetu = acces_etu.numetu
                           INNER JOIN acces_employeurs
                                   ON acces_etu.noemployeur = acces_employeurs.noemployeur
                    ORDER  BY journal.numero DESC';

      try {
        $reports = $connectedDB->prepare($sql_query);
        $reports->execute();
        $reportCount = $reports->rowCount();

      } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
?>
    <div class="card my-4 border-0 shadow">
      <div class="py-3 card-header bg-white">
        <div class="d-flex justify-content-between">
          <h2 class="h3">Tous les rapports (<strong><?= $reportCount ?></strong>)</h2>
          <h2 class="h3">
            <a href="index.php?list-evaluations">
              Voir la liste des évaluations des stagiaires
            </a>
          </h2>
        </div>
        <h3 class="h5">Liste des rapports complétés, du plus récent au plus ancien.</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">Numéro</th>
                <th scope="col">Date</th>
                <th scope="col">Stagiaire</th>
                <th scope="col">Superviseur</th>
                <th scope="col">Employeur</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
<?php
      foreach ($reports as $report) {
?>
              <tr>
                <th scope="row"><?= $report['numero'] ?></th>
                <td><?= $report['DateJournal'] ?></td>
                <td><?= $report['NomEtu'] ?></td>
                <td><?= $report['nomsup'] ?></td>
                <td>
                  <?= $report['Nom de l\'employeur'] ?>
                  <br>
                  <em><?= $report['Nom de l\'entreprise'] ?></em>
                </td>
                <td>
                  <a class="text-decoration-none" href="display.php?id=<?= $report['numero'] ?>">
                    <span class="fas fa-fw fa-2x fa-file text-secondary"></span>
                  </a>
                  <a class="text-decoration-none" href="display.php?id=<?= $report['numero'] ?>&action=comment">
                    <span
                      class="fas fa-fw fa-2x
                      <?= $report['commentaire'] ?
                          'fa-comment text-success' :
                          'fa-comment-slash text-warning'
                      ?>"
                    >
                    </span>
                  </a>
                  <a class="text-decoration-none" href="display.php?id=<?= $report['numero'] ?>&action=delete">
                    <span class="fas fa-fw fa-2x fa-trash text-danger"></span>
                  </a>
                </td>
              </tr>
<?php
      }
    } else {
      $sql_query = 'SELECT acces_etu.numetu,
                           acces_etu.nometu,
                           acces_etu.nomsup,
                           `acces_employeurs`.`Nom de l\'employeur`,
                           `acces_employeurs`.`Nom de l\'entreprise`
                    FROM   acces_etu
                           INNER JOIN acces_employeurs
                                   ON acces_etu.noemployeur = acces_employeurs.noemployeur
                    ORDER  BY acces_etu.numetu ASC';

      try {
        $interns = $connectedDB->prepare($sql_query);
        $interns->execute();
        $internCount = $interns->rowCount();

      } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
?>
    <div class="card my-4 border-0 shadow">
      <div class="py-3 card-header bg-white">
        <div class="d-flex justify-content-between">
          <h2 class="h3">Tous les stagiaires (<strong><?= $internCount ?></strong>)</h2>
          <h2 class="h3">
            <a href="index.php">Retourner à la liste des rapports</a>
          </h2>
        </div>
        <h3 class="h5">Liste des stagiaires, en ordre de numéro étudiant.</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">Numéro</th>
                <th scope="col">Étudiant</th>
                <th scope="col">Superviseur</th>
                <th scope="col">Employeur</th>
                <th scope="col">Grille d'évaluation</th>
              </tr>
            </thead>
            <tbody>
<?php
      foreach ($interns as $intern) {
?>
              <tr>
                <th scope="row"><?= $intern['numetu'] ?></th>
                <td><?= $intern['nometu'] ?></td>
                <td><?= $intern['nomsup'] ?></td>
                <td>
                  <?= $intern['Nom de l\'employeur'] ?>
                  <br>
                  <em><?= $intern['Nom de l\'entreprise'] ?></em>
                </td>
                <td>
                  <a class="text-decoration-none" href="evaluations/interns/<?= $intern['numetu'] ?>.html">
                    <span class="fas fa-file fa-fw fa-2x text-secondary"></span>
                  </a>
                  <a class="text-decoration-none" href="evaluations/interns/<?= $intern['numetu'] ?>.html">
                    <span class="fas fa-fw fa-2x fa-trash text-danger"></span>
                  </a>
                </td>
              </tr>
<?php
      }
    }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

<?php
  } elseif ($_SESSION['userType'] == 'etudiant') {
    include( UTIL_CONNECT );

    $sql_query = 'SELECT numero,
                         DateJournal
                  FROM   journal
                  WHERE  numetu = :username';

    try {
      $reports = $connectedDB->prepare($sql_query);
      $reports->execute([
        ':username' => $_SESSION['username']
      ]);
      $reportCount = $reports->rowCount();

    } catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
?>
    <div class="card my-4 border-0 shadow">
      <div class="py-3 card-header bg-white">
        <h2 class="h3">Tous les rapports</h2>
        <p class="fs-6">
          Vous <?= $reportCount == 0 ? 'n\'' : '' ?>avez complété
          <strong><?= $reportCount > 0 ? $reportCount : 'aucun' ?></strong>
          journa<?= $reportCount <= 1 ? 'l' : 'ux' ?> de bord.
          <a href="create.php?type=report">Créez une nouvelle entrée de journal.</a>
        </p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">Numéro</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
<?php
    foreach ($reports as $report) {
?>
              <tr>
                <th scope="row"><?= $report['numero'] ?></th>
                <td><?= $report['DateJournal'] ?></td>
                <td>
                  <a class="text-decoration-none" href="display.php?id=<?= $report['numero'] ?>">
                    <span class="fas fa-fw fa-2x fa-file text-secondary"></span>
                  </a>
                </td>
              </tr>
<?php
    }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
<?php } ?>
  </main>

  <!-- START INCLUDE FOOTER -->
<?php
  include( VIEW_FOOTER );
?>
  <!-- END INCLUDE FOOTER -->

</body>
</html>
