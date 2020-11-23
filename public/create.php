<?php
  session_start();
  ob_start();
  date_default_timezone_set("America/New_York");

  // Importer les constantes et changer le titre de la page
  require( 'utils/config.php' );
  $page_title = CREATE_TITLE;

  include( ACCESS_CONNECTED );
  include( ACCESS_NO_SUPERVISOR );
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
    if ($_GET['type'] == 'report') {
      include(UTIL_CONNECT);

      $sql_query = 'SELECT nomsup
                    FROM   acces_etu
                    WHERE  numetu = :username';

      try {
        $students = $connectedDB->prepare($sql_query);
        $students->execute([
          ':username' => $_SESSION['username']
        ]);
        $student = $students->fetch();
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
    ?>
      <div class="card my-4 border-0 shadow">
        <div class="py-3 card-header bg-white">
          <div class="d-flex justify-content-between">
            <h2 class="h3">Rapport d'étape</h2>
            <h2 class="h3">
              <a href="index.php">
                Retourner au tableau de bord
              </a>
            </h2>
          </div>
          <h3 class="h5 border-bottom border-dark pb-3 mb-3">
            Rapport d'étape créé par <?= $_SESSION["displayName"] ?> et remis à
            <?= $student["nomsup"] ?> le <?= date("d-m-Y") ?>.
          </h3>
          <div>
            <p>
              Rapport d'étape est un outil de réflexion personnalisé; tout au
              long de son stage, la ou le stagiaire y consigne plus
              particulièrement les tâches professionnelles qu’elle ou qu'il
              accomplit dans l'entreprise ainsi que les apprentissages que ces
              dernières lui permettent de réaliser.
            </p>
            <h4 class="h5">Objectifs </h4>
            <ul>
              <li>Objectiver de façon continue son vécu professionnel.</li>
              <li> Consigner ses réflexions. </li>
            </ul>
            <h4 class="h5"> Directives</h4>
            <p>
              Tout au long de son stage, la ou le stagiaire doit complétéer
              des rapports d'étapes. <strong>Deux ou trois fois dans la
              session</strong>, elle ou il complète un rapport d'étape et le
              transmet à son superviseur de stage par l'entremise du site Web
              de stages.
            </p>
          </div>
        </div>

        <div class="card-body">
          <form name="jdb" method="post">
            <fieldset>
              <legend>Activités significatives :</legend>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label" for="activite-1">
                    Veuillez entrer une première activité significative cette semaine :
                  </label>
                  <textarea class="form-control" name="activite-1" rows="5" id="activite-1" required></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="activite-2">
                    Veuillez entrer une deuxième activité significative cette semaine :
                  </label>
                  <textarea class="form-control" name="activite-2" rows="5" id="activite-2" required></textarea>
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend class="mt-4">Apprentissages réalisés :</legend>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label" for="apprentissage-1">
                    Veuillez indiquer l'apprentissage relié à la première activité significative :
                  </label>
                  <textarea class="form-control" name="apprentissage-1" rows="5" id="apprentissage-1" required></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="apprentissage-2">
                    Veuillez indiquer l'apprentissage relié à la deuxième activité significative :
                  </label>
                  <textarea class="form-control" name="apprentissage-2" rows="5" id="apprentissage-2" required></textarea>
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend class="mt-4">Difficultés rencontrées (s'il y a lieu) :</legend>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label" for="difficulte-1">
                    Veuillez indiquer une première difficulté rencontrée :
                  </label>
                  <textarea class="form-control" name="difficulte-1" rows="5" id="difficulte-1"></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="difficulte-2">
                    Veuillez indiquer une deuxième difficulté rencontrée :
                  </label>
                  <textarea class="form-control" name="difficulte-2" rows="5" id="difficulte-2"></textarea>
                </div>
              </div>
            </fieldset>

            <fieldset>
              <legend class="mt-4">Commentaires &mdash; Questions :</legend>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label" for="commentaire-1">
                    Veuillez indiquer un premier commentaire ou une question pertinente :
                  </label>
                  <textarea class="form-control" name="commentaire-1" rows="5" id="commentaire-1"></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="commentaire-2">
                    Veuillez indiquer un deuxième commentaire ou une question pertinente :
                  </label>
                  <textarea class="form-control" name="commentaire-2" rows="5" id="commentaire-2"></textarea>
                </div>
              </div>
            </fieldset>

            <div class="mt-4">
              <button type="submit" name="send" class="btn btn-primary">Envoyer</button>
              <button type="reset" name="reset" class="btn btn-danger">Réinitialiser</button>
            </div>
          </form>
        </div>
      </div>
    <?php } ?>
  </main>

  <!-- START INCLUDE FOOTER -->
<?php
  include(VIEW_FOOTER);
?>
  <!-- END INCLUDE FOOTER -->

</body>

</html>
