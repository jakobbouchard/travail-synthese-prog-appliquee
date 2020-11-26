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
    $id = trim($_SESSION['postdata']['id']);
    $commentaire = trim($_SESSION['postdata']['commentaire']);
    $message = '';

    include(UTIL_CONNECT);

    try {
      $sql_query = "UPDATE journal
                    SET    commentaire = :commentaire
                    WHERE  numero = :id";
      $stmt = $connectedDB->prepare($sql_query);
      $stmt->execute([
        ':commentaire' => $commentaire,
        ':id' => $id
      ]);
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
    if ($_SESSION['postdata']['send'] == 'add') {
      $message = 'Le%20commentaire%20a%20%C3%A9t%C3%A9%20ajout%C3%A9%20avec%20succ%C3%A8s';
    } elseif ($_SESSION['postdata']['send'] == 'edit') {
      $message = 'Le%20commentaire%20a%20%C3%A9t%C3%A9%20modifi%C3%A9%20avec%20succ%C3%A8s';
    }

    // Déconnecter la base de données, détruire les variables
    unset($_SESSION['postdata']);
    $connectedDB = null;

    header("location: /display.php?id={$id}&message={$message}");
    exit;
  }
} elseif ($_GET['action'] == 'delete') {
  $id = trim($_GET['id']);

  include(UTIL_CONNECT);

  try {
    $sql_query = "DELETE
                  FROM   journal
                  WHERE  numero = :id";
    $stmt = $connectedDB->prepare($sql_query);
    $stmt->execute([
      ':id' => $id
    ]);
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }

  // Déconnecter la base de données, détruire les variables
  unset($_SESSION['postdata']);
  $connectedDB = null;

  header('location: /?message=Le%20rapport%20a%20%C3%A9t%C3%A9%20supprim%C3%A9');
  exit;
}
