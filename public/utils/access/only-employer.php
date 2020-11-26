<?php
if ($_SESSION['userType'] != 'employeur') {
  header('location: /');
  exit;
}
