<?php
if ($_SESSION['userType'] != 'etudiant') {
  header('location: /?message=Vous%20n%27avez%20pas%20acc%C3%A8s%20%C3%A0%20cette%20page&error');
  exit;
}
