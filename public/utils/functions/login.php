<?php
if (isset($_POST['login_user']) || isset($_SESSION['postdata']['login_user'])) {

  // Si la requête est faite via POST, mettre les variables POST dans un array dans SESSION
  // puis retourner à la page qui a fait la requête.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    $_POST = array();
    header('Location: ' . $_SERVER['REQUEST_URI'], true, 303);
    exit;
  } elseif (array_key_exists('postdata', $_SESSION)) {
    $username = trim($_SESSION['postdata']['username']);
    $password = trim($_SESSION['postdata']['password']);
    $usertype = $sql_query = '';

    // Récupérer l'utilisateur
    switch (strlen($username)) {
      case 4:
        $sql_query = "SELECT noemployeur,
                             mdpemployeur AS password,
                             `Nom de l'employeur` AS displayName
                      FROM   acces_employeurs
                      WHERE  noemployeur = :username
                      LIMIT  1";
        $userType = 'employeur';
        break;

      case 5:
        $sql_query = "SELECT noemploye,
                             motdepasse AS password,
                             nomsup AS displayName
                      FROM   acces_adm
                      WHERE  noemploye = :username
                      LIMIT  1";
        $userType = 'superviseur';
        break;

      case 7:
        $sql_query = "SELECT numetu,
                             motdepasse AS password,
                             nometu AS displayName
                      FROM   acces_etu
                      WHERE  numetu = :username
                      LIMIT  1";
        $userType = 'etudiant';
        break;

      default:
        // Déconnecter la base de données, détruire les variables
        $connectedDB = null;
        unset($_SESSION['postdata'], $password);

        header('location: login.php?error');
        exit;
        break;
    }

    include(UTIL_CONNECT);

    try {
      $stmt = $connectedDB->prepare($sql_query);
      $stmt->execute([
        ':username' => $username
      ]);
      $user = $stmt->fetch();
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }

    // Si les identifiants sont corrects, générer la session et naviguer au tableau de bord
    if ($user['password'] == $password) {
      session_regenerate_id(true);
      $_SESSION['connected'] = true;
      $_SESSION['username'] = $username;
      $_SESSION['displayName'] = $user['displayName'];
      $_SESSION['userType'] = $userType;

      // Déconnecter la base de données, détruire les variables
      $connectedDB = null;
      unset($_SESSION['postdata'], $password);

      header('location: ./');
      exit;
    } else {
      // Déconnecter la base de données, détruire les variables
      $connectedDB = null;
      unset($_SESSION['postdata'], $password);

      header('location: login.php?error');
      exit;
    }
  }
}
