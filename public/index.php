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

<?php if ($_SESSION['userType'] == 'employeur') { ?>
    <h2 class="h3">Employeur !!!</h2>

<?php } elseif ($_SESSION['userType'] == 'superviseur') { ?>
    <h2 class="h3">Superviseur !!!</h2>

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
