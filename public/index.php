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
      <h1 class="h2">Tableau de bord</h1>
    </div>

<?php
  } elseif ($_SESSION['userType'] == 'superviseur') {
    include( UTIL_CONNECT );

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
        <h2 class="h3">Tous les rapports (<strong><?= $reportCount ?></strong>)</h2>
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
                  <?= $report['Nom de l\'entreprise'] ?>
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
          <a href="create.php">Créez une nouvelle entrée de journal.</a>
        </p>
      </div>
      <div class="card-body">
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
                <a href="display.php?num=<?= $report['numero'] ?>">
                  <span class="fas fa-file-alt fa-2x"></span>
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
<?php } ?>
  </main>

  <!-- START INCLUDE FOOTER -->
<?php
  include( VIEW_FOOTER );
?>
  <!-- END INCLUDE FOOTER -->

</body>
</html>
