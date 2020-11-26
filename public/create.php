<?php
session_start();
ob_start();
date_default_timezone_set("America/New_York");

// Importer les constantes et changer le titre de la page
require('utils/config.php');
$page_title = CREATE_TITLE;

include(ACCESS_CONNECTED);
include(FUNCTION_CREATE);
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
      include(ACCESS_ONLY_STUDENT);
      include(UTIL_CONNECT);

      $sql_query = "SELECT nomsup
            FROM   acces_etu
            WHERE  numetu = :username";

      try {
        $students = $connectedDB->prepare($sql_query);
        $students->execute([
          ':username' => $_SESSION['username']
        ]);
        $student = $students->fetch();
        $connectedDB = null;
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
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
          <h3 class="h5 border-bottom border-dark pb-3 mb-3">
            Rapport d'étape créé par <?= $_SESSION['displayName'] ?> et remis à
            <?= $student['nomsup'] ?> le <?= date('d-m-Y') ?>.
          </h3>
          <div>
            <p>
              Rapport d'étape est un outil de réflexion personnalisé; tout au
              long de son stage, la ou le stagiaire y consigne plus
              particulièrement les tâches professionnelles qu’elle ou qu'il
              accomplit dans l'entreprise ainsi que les apprentissages que ces
              dernières lui permettent de réaliser.
            </p>
            <h4 class="h5">Objectifs</h4>
            <ul>
              <li>Objectiver de façon continue son vécu professionnel.</li>
              <li> Consigner ses réflexions. </li>
            </ul>
            <h4 class="h5">Directives</h4>
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
          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
          <input type="hidden" name="nom-superviseur" value="<?= $student['nomsup'] ?>">
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
              <button type="submit" name="send" value="report" class="btn btn-lg btn-primary">Envoyer</button>
              <button type="reset" name="reset" class="btn btn-lg btn-danger">Réinitialiser</button>
            </div>
          </form>
        </div>
      </div>
    <?php
    } elseif ($_GET['type'] == 'evaluation') {
      include(ACCESS_ONLY_EMPLOYER);
      include(UTIL_CONNECT);

      $sql_query = "SELECT nometu
                    FROM   acces_etu
                    WHERE  numetu = :intern";

      try {
        $interns = $connectedDB->prepare($sql_query);
        $interns->execute([
          ':intern' => $_GET['intern']
        ]);
        $intern = $interns->fetch();
        $connectedDB = null;
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
    ?>
      <div class="card my-4 border-0 shadow">
        <div class="py-3 card-header bg-white">
          <div class="d-flex justify-content-between">
            <h2 class="h3">Évaluation d'un stagiaire</h2>
            <h2 class="h3">
              <a class="btn btn-primary" href="index.php">
                Retourner au tableau de bord
              </a>
            </h2>
          </div>
          <h3 class="h5 border-bottom border-dark pb-3 mb-3">
            Évaluation du stagiaire <?= $intern['nometu'] ?> en date du <?= date("d-m-Y") ?>
          </h3>
          <div>
            <h4 class="h5">Évaluation du stagiaire</h4>
            <p>
              À l’usage de la personne responsable de l’évaluation au sein de
              l’entreprise. Sur les deux pages suivantes se trouve la grille
              d’évaluation devant être remplie par la personne qui, au sein de
              l’entreprise, est responsable de l’évaluation des stagiaires.
              Cette grille contient les dix points devant être évalués à l’aide
              d’une échelle d’appréciation à cinq échelons. Pour évaluer chacun
              des points, il suffit de déterminer l’échelon qui décrit le mieux
              ce qui est observé chez l’élève à évaluer.
            </p>
            <h4 class="h5">Exemple d’évaluation</h4>
            <p>
              Avant d’évaluer la <strong>Motivation</strong> d’une ou d’un
              stagiaire, il faut lire la description de chacun des cinq échelons
              qui établissent des différences entre Excellent, Très bien, Bien,
              Faible et Très faible ; ensuite, il s’agit simplement de situer la
              ou le stagiaire à l’échelon qui correspond le mieux à ce qu’on a
              pu observer de son engagement, de sa participation et de sa
              persistance dans les activités. Ce faisant, on lui accorde ainsi
              une note selon le barème établi pour chacun des éléments
              d'évaluation, la somme des pointages donnera une note sur 100.
              Après avoir procédé de cette façon pour chacun des dix éléments de
              la grille, un calcul de la note s'effectuera lorsque vous
              appuierez sur le bouton Calculer. Finalement, cliquez sur le
              bouton Envoyer afin de sauvegarder l'évaluation.
            </p>
          </div>
        </div>

        <div class="card-body">
          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="evaluation" method="POST">
            <fieldset>
              <legend>Motivation</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="motivation-1">
                        Engagement, participation et persistance remarquables dans les activités.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="motivation" id="motivation-1" value="10">
                        <label class="form-label" for="motivation-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="motivation-2">
                        Degré supérieur d’engagement, de participation et de persistance.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="motivation" id="motivation-2" value="8">
                        <label class="form-label" for="motivation-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="motivation-3">
                        Degré suffisant d’engagement, de participation et de persistance dans les activités.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="motivation" id="motivation-3" value="6">
                        <label class="form-label" for="motivation-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="motivation-4">
                        Peu d’engagement et de participation ; pas de persistance au travail.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="motivation" id="motivation-4" value="4">
                        <label class="form-label" for="motivation-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="motivation-5">
                        Ni engagement réel, ni persistance dans les activités ; peu de participation.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="motivation" id="motivation-5" value="2">
                        <label class="form-label" for="motivation-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Autonomie et initiative</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="autonomie-1">
                        Autonomie remarquable et grande capacité de prendre des initiatives.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="autonomie" id="autonomie-1" value="10">
                        <label class="form-label" for="autonomie-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="autonomie-2">
                        Bonne capacité de planification et d’exécution de façon autonome.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="autonomie" id="autonomie-2" value="8">
                        <label class="form-label" for="autonomie-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="autonomie-3">
                        Besoin raisonnable ou acceptable d’assistance dans la planification et la réalisation de ses tâches.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="autonomie" id="autonomie-3" value="6">
                        <label class="form-label" for="autonomie-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="autonomie-4">
                        Manque d’initiative et d’autonomie.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="autonomie" id="autonomie-4" value="4">
                        <label class="form-label" for="autonomie-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="autonomie-5">
                        N’entreprend rien personnellement.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="autonomie" id="autonomie-5" value="2">
                        <label class="form-label" for="autonomie-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Qualité du travail</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="qualite-1">
                        Travail d’une qualité exceptionnelle, excellents résultats.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="qualite" id="qualite-1" value="25">
                        <label class="form-label" for="qualite-1">
                          25
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="qualite-2">
                        Travail très bien exécuté; très bons résultats.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="qualite" id="qualite-2" value="20">
                        <label class="form-label" for="qualite-2">
                          20
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="qualite-3">
                        Qualité du travail et résultats satisfaisants.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="qualite" id="qualite-3" value="15">
                        <label class="form-label" for="qualite-3">
                          15
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="qualite-4">
                        Qualité du travail et résultats plus ou moins satisfaisants.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="qualite" id="qualite-4" value="10">
                        <label class="form-label" for="qualite-4">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="qualite-5">
                        Qualité du travail et résultats non satisfaisants.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="qualite" id="qualite-5" value="5">
                        <label class="form-label" for="qualite-5">
                          5
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Rythme d'exécution du travail</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="rythme-1">
                        Toujours très rapide.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="rythme" id="rythme-1" value="5">
                        <label class="form-label" for="rythme-1">
                          5
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="rythme-2">
                        Habituellement plus rapide que la normale.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="rythme" id="rythme-2" value="4">
                        <label class="form-label" for="rythme-2">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="rythme-3">
                        Satisfaisant ; exécution selon le temps normalement requis.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="rythme" id="rythme-3" value="3">
                        <label class="form-label" for="rythme-3">
                          3
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="rythme-4">
                        Plutôt lent.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="rythme" id="rythme-4" value="2">
                        <label class="form-label" for="rythme-4">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="rythme-5">
                        Très lent.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="rythme" id="rythme-5" value="1">
                        <label class="form-label" for="rythme-5">
                          1
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Sens des responsabilités</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="responsabilite-1">
                        Recherche des responsabilités.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="responsabilite" id="responsabilite-1" value="10">
                        <label class="form-label" for="responsabilite-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="responsabilite-2">
                        Acceptation enthousiaste des responsabilités confiées.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="responsabilite" id="responsabilite-2" value="8">
                        <label class="form-label" for="responsabilite-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="responsabilite-3">
                        Acceptation sereine des responsabilités.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="responsabilite" id="responsabilite-3" value="6">
                        <label class="form-label" for="responsabilite-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="responsabilite-4">
                        Réticence à accepter des responsabilités
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="responsabilite" id="responsabilite-4" value="4">
                        <label class="form-label" for="responsabilite-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="responsabilite-5">
                        Fuite devant les responsabilités.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="responsabilite" id="responsabilite-5" value="2">
                        <label class="form-label" for="responsabilite-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Aptitudes</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="aptitudes-1">
                        Très grande facilité à apprendre ; compréhension très rapide, même sans explication.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="aptitudes" id="aptitudes-1" value="10">
                        <label class="form-label" for="aptitudes-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="aptitudes-2">
                        Facilité à apprendre, mais requiert des explications détaillées.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="aptitudes" id="aptitudes-2" value="8">
                        <label class="form-label" for="aptitudes-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="aptitudes-3">
                        Facilité à apprendre, mais requiert des explications détaillées.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="aptitudes" id="aptitudes-3" value="6">
                        <label class="form-label" for="aptitudes-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="aptitudes-4">
                        Légères difficultés à apprendre; exige des répétitions.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="aptitudes" id="aptitudes-4" value="4">
                        <label class="form-label" for="aptitudes-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="aptitudes-5">
                        Difficultés d’apprentissage exigeant de multiples explications répétitives.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="aptitudes" id="aptitudes-5" value="2">
                        <label class="form-label" for="aptitudes-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Résolutions de problèmes</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="resolution-1">
                        Retient toujours la meilleure solution à un problème
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="resolution" id="resolution-1" value="10">
                        <label class="form-label" for="resolution-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="resolution-2">
                        Émet presque toujours une opinion éclairée lorsque surgit un problème.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="resolution" id="resolution-2" value="8">
                        <label class="form-label" for="resolution-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="resolution-3">
                        Émet assez souvent une opinion éclairée lorsque surgit un problème.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="resolution" id="resolution-3" value="6">
                        <label class="form-label" for="resolution-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="resolution-4">
                        Émet des opinions plus ou moins appropriées à la résolution d’un problème.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="resolution" id="resolution-4" value="4">
                        <label class="form-label" for="resolution-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="resolution-5">
                        Les rares opinions émises ne sont presque jamais appropriées pour résoudre un problème.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="resolution" id="resolution-5" value="2">
                        <label class="form-label" for="resolution-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Collaboration</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="collaboration-1">
                        Très bonne collaboration participation très active et très efficace à un travail d’équipe.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="collaboration" id="collaboration-1" value="10">
                        <label class="form-label" for="collaboration-1">
                          10
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="collaboration-2">
                        Très bonne collaboration ; participation active à un travail d’équipe.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="collaboration" id="collaboration-2" value="8">
                        <label class="form-label" for="collaboration-2">
                          8
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="collaboration-3">
                        Bonne collaboration ; participation valable à un travail d’équipe.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="collaboration" id="collaboration-3" value="6">
                        <label class="form-label" for="collaboration-3">
                          6
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="collaboration-4">
                        Collaboration plus ou moins active et plus ou moins valable à un travail d’équipe.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="collaboration" id="collaboration-4" value="4">
                        <label class="form-label" for="collaboration-4">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="collaboration-5">
                        Pas de collaboration ; est à la remorque de son équipe.
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="collaboration" id="collaboration-5" value="2">
                        <label class="form-label" for="collaboration-5">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <fieldset>
              <legend>Assiduité</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="assiduite-1">
                        Aucune absence
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="assiduite" id="assiduite-1" value="5">
                        <label class="form-label" for="assiduite-1">
                          5
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="assiduite-2">
                        Une absence
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="assiduite" id="assiduite-2" value="4">
                        <label class="form-label" for="assiduite-2">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="assiduite-3">
                        Deux absences
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="assiduite" id="assiduite-3" value="3">
                        <label class="form-label" for="assiduite-3">
                          3
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="assiduite-4">
                        Trois absences
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="assiduite" id="assiduite-4" value="2">
                        <label class="form-label" for="assiduite-4">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="assiduite-5">
                        Plus de trois absences
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="assiduite" id="assiduite-5" value="1">
                        <label class="form-label" for="assiduite-5">
                          1
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>


            <fieldset>
              <legend>Ponctualité</legend>
              <ol>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="ponctualite-1">
                        Jamais en retard ; Respecte toujours l'horaire de travail
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="ponctualite" id="ponctualite-1" value="5">
                        <label class="form-label" for="ponctualite-1">
                          5
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="ponctualite-2">
                        Arrive rarement en retard ; respecte généralement l'horaire de travail
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="ponctualite" id="ponctualite-2" value="4">
                        <label class="form-label" for="ponctualite-2">
                          4
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="ponctualite-3">
                        Arrive quelques fois en retard ; respecte l'horaire de travail
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="ponctualite" id="ponctualite-3" value="3">
                        <label class="form-label" for="ponctualite-3">
                          3
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="ponctualite-4">
                        Arrive souvent en retard ; ne respecte généralement pas l'horaire de travail
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="ponctualite" id="ponctualite-4" value="2">
                        <label class="form-label" for="ponctualite-4">
                          2
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="ponctualite-5">
                        Très souvent en retard ; ne respecte pas du tout l'horaire de travail
                      </label>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" required name="ponctualite" id="ponctualite-5" value="1">
                        <label class="form-label" for="ponctualite-5">
                          1
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </fieldset>

            <div class="mt-4">
              <button type="submit" name="send" class="btn btn-lg btn-primary">Envoyer</button>
              <button type="reset" name="reset" class="btn btn-lg btn-danger">Réinitialiser</button>
            </div>
          </form>
        </div>
      </div>
      <div class="fixed-bottom w-25 mx-auto p-3 rounded-top bg-secondary text-white text-center pe-none transition-200 opacity-0" id="resultCard">
        <h4>Résulats</h4>
        <h5><span id="result"></span>/100</h5>
      </div>

      <script src="script/showEvaluationScore.js"></script>
    <?php
    } else {
      header('location: /');
      exit;
    }
    ?>
  </main>

  <!-- START INCLUDE FOOTER -->
  <?php
  include(VIEW_FOOTER);
  ?>
  <!-- END INCLUDE FOOTER -->

</body>

</html>
