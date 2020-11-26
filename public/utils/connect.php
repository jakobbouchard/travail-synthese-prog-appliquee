<?php
$ini = parse_ini_file('./utils/config/app.ini.php');
$DB_host = $ini['db_host'];
$DB_name = $ini['db_name'];
$DB_user = $ini['db_username'];
$DB_pwd = $ini['db_password'];

try {
  $connectedDB = new PDO("mysql:host=$DB_host;dbname=$DB_name;charset=utf8", $DB_user, $DB_pwd);
  // set the PDO error mode to exception
  $connectedDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}
