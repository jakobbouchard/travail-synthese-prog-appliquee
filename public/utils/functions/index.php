<?php
if ($_GET['action'] == 'delete') {
  $intern = trim($_GET['intern']);

  if (!unlink("evaluations//interns//{$intern}.html")) {
    die("La suppression du fichier <code>{$intern}.html</code> est impossible.");
  }

  header('location: /?list-evaluations&message=L%27%C3%A9valuation%20a%20%C3%A9t%C3%A9%20supprim%C3%A9e');
  exit;
}
