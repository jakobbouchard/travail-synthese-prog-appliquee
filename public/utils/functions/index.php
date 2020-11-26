<?php
if ($_GET['action'] == 'delete') {
  $intern = trim($_GET['intern']);

  if (!unlink("evaluations//interns//{$intern}.html")) {
    die("La suppression du fichier <code>{$intern}.html</code> est impossible.");
  }

  header('location: /?list-evaluations');
  exit;
}
