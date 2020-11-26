<?php
if (isset($_POST['send']) || isset($_SESSION['postdata']['send'])) {

  // Si la requête est faite via POST, mettre les variables POST dans un array dans SESSION
  // puis retourner à la page qui a fait la requête.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    $_POST = array();
    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit;

    // Si l'array "postdata" existe
  } elseif (array_key_exists('postdata', $_SESSION)) {

    // Vérifier quelle liste doit être modifiée
    switch ($_SESSION['postdata']['send']) {

        // Créer un rapport
      case 'report':
        include(UTIL_CONNECT);

        $supervisor = trim($_SESSION['postdata']['nom-superviseur']);
        $activite_1 = trim($_SESSION['postdata']['activite-1']);
        $activite_2 = trim($_SESSION['postdata']['activite-2']);
        $apprentissage_1 = trim($_SESSION['postdata']['apprentissage-1']);
        $apprentissage_2 = trim($_SESSION['postdata']['apprentissage-2']);
        $difficulte_2 = trim($_SESSION['postdata']['difficulte-2']);
        $difficulte_1 = trim($_SESSION['postdata']['difficulte-1']);
        $commentaire_1 = trim($_SESSION['postdata']['commentaire-1']);
        $commentaire_2 = trim($_SESSION['postdata']['commentaire-2']);

        try {
          $sql_query = "INSERT INTO journal
                                    (DateJournal,
                                    numetu,
                                    NomEtu,
                                    nomsup,
                                    AS1,
                                    AS2,
                                    AR1,
                                    AR2,
                                    DR1,
                                    DR2,
                                    C1,
                                    C2)
                        VALUES      (:current_date,
                                    :username,
                                    :displayName,
                                    :supervisor,
                                    :activite_1,
                                    :activite_2,
                                    :apprentissage_1,
                                    :apprentissage_2,
                                    :difficulte_1,
                                    :difficulte_2,
                                    :commentaire_1,
                                    :commentaire_2)";
          $stmt = $connectedDB->prepare($sql_query);
          $stmt->execute([
            ':current_date' => date('Y-m-d'),
            ':username' => $_SESSION['username'],
            ':displayName' => $_SESSION['displayName'],
            ':supervisor' => $supervisor,
            ':activite_1' => $activite_1,
            ':activite_2' => $activite_2,
            ':apprentissage_1' => $apprentissage_1,
            ':apprentissage_2' => $apprentissage_2,
            ':difficulte_1' => $difficulte_1,
            ':difficulte_2' => $difficulte_2,
            ':commentaire_1' => $commentaire_1,
            ':commentaire_2' => $commentaire_2
          ]);
        } catch (PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }

        // Déconnecter la base de données, détruire les variables
        unset($_SESSION['postdata']);
        $connectedDB = null;

        break;

        // Créer une évaluation
      case 'evaluation':
        $internNumber = trim($_SESSION['postdata']['internNumber']);
        $intern = trim($_SESSION['postdata']['intern']);
        $answerNames = [
          'Motivation',
          'Autonomie et initiative',
          'Qualité du travail',
          'Rythme d\'exécution du travail',
          'Sens des responsabilités',
          'Aptitudes',
          'Résolutions de problèmes',
          'Collaboration',
          'Assiduité',
          'Ponctualité',
        ];
        $answerValues = [
          trim($_SESSION['postdata']['motivation']),
          trim($_SESSION['postdata']['autonomie']),
          trim($_SESSION['postdata']['qualite']),
          trim($_SESSION['postdata']['rythme']),
          trim($_SESSION['postdata']['responsabilite']),
          trim($_SESSION['postdata']['aptitudes']),
          trim($_SESSION['postdata']['resolution']),
          trim($_SESSION['postdata']['collaboration']),
          trim($_SESSION['postdata']['assiduite']),
          trim($_SESSION['postdata']['ponctualite'])
        ];
        $totalPoints = 0;

        // Génération du fichier
        $content = <<<'EVALUATION'
          <!DOCTYPE html>

          <html>

          <head>
            <!-- Page info -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Évaluation de Employeur1 - Rapports de stage</title>
            <meta name="description" content="Tableau de bord pour les rapports de stage de la TIM.">

            <!-- CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
            <link rel="stylesheet" href="/styles/dashboard.css">

            <!-- Font Awesome -->
            <script defer src="https://kit.fontawesome.com/35e61bd17e.js" crossorigin="anonymous"></script>

            <!-- Favicons -->
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#e30512">
            <meta name="apple-mobile-web-app-title" content="Rapports de Stage">
            <meta name="application-name" content="Rapports de Stage">
            <meta name="msapplication-TileColor" content="#e30512">
            <meta name="theme-color" content="#e30512">

            <style>
              .container {
                height: 100vh;
              }
            </style>
          </head>

          <body>
            <div class="container d-grid align-items-center">
              <div class="card w-75 mx-auto my-4 border-0 shadow">
                <div class="py-3 card-header bg-white">
                  <div class="d-flex justify-content-between">
                    <h2 class="h3">Évaluation d'un stagiaire</h2>
                    <h2 class="h3">
                      <a class="btn btn-primary" href="/?list-evaluations">
                        Retourner au tableau de bord
                      </a>
                    </h2>
                  </div>

          EVALUATION;

        $content .= "<h3 class=\"h5\">Évalué par {$_SESSION['displayName']} pour l'étudiant {$intern}</h3>\n";
        $content .= "</div>\n<div class=\"card-body\">";

        for ($i = 0; $i < count($answerValues); $i++) {
          $totalPoints += $answerValues[$i];

          $content .= <<<'EVALUATION'
              <div class="w-50">
                <div class="d-flex justify-content-between mb-4 pb-2 pr-3 border-bottom border-dark">

            EVALUATION;
          $content .= "<div class=\"fs-6\">{$answerNames[$i]}</div>";
          $content .= "<div class=\"fs-6\">{$answerValues[$i]}</div>\n</div>\n</div>\n";
        }

        $content .= "<h3>Résultat - {$totalPoints} / 100</h3>\n";
        $content .= <<<'EVALUATION'
              </div>
            </div>
          </div>

          <!-- JavaScript Bundle with Popper.js -->
          <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
            crossorigin="anonymous"></script>
        </body>

        </html>
        EVALUATION;

        // Création et écriture du fichier
        $fileName = "{$internNumber}.html";

        if (!file_exists('evaluations')) {
          if (!mkdir("evaluations//interns", 0777, true)) {
            die('Failed to create folders...');
          }
        }
        $file = fopen("evaluations//interns//{$fileName}", "w");
        fwrite($file, $content);
        fclose($file);

        unset($_SESSION['postdata']);

        break;
    }
  }
}
