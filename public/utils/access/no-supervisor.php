<?php
  if ($_SESSION['userType'] != 'etudiant') {
    header('location: /');
    exit;
  }
?>
