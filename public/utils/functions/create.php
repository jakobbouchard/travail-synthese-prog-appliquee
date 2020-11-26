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

    include(UTIL_CONNECT);

    // Vérifier quelle liste doit être modifiée
    switch($_SESSION['postdata']['send']) {

      // Créer un rapport
      case 'report':
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

        break;

      // Créer une évaluation
      case 'evaluation':

        break;
    }

    // Déconnecter la base de données, détruire les variables
    unset($_SESSION['postdata']);
    $connectedDB = null;
  }
}
