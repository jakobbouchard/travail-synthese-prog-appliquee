<?php
define('DIR_VIEWS',              'views/');
define('VIEW_META',              DIR_VIEWS . 'meta.php');
define('VIEW_HEADER',            DIR_VIEWS . 'header.php');
define('VIEW_NAVIGATION',        DIR_VIEWS . 'navigation.php');
define('VIEW_FOOTER',            DIR_VIEWS . 'footer.php');

define('DIR_UTILS',              'utils/');
define('UTIL_CONNECT',           DIR_UTILS . 'connect.php');
define('UTIL_LOGOUT',            'logout.php');

define('DIR_ACCESS',             DIR_UTILS . 'access/');
define('ACCESS_CONNECTED',       DIR_ACCESS . 'connected.php');
define('ACCESS_ONLY_STUDENT',    DIR_ACCESS . 'only-student.php');
define('ACCESS_ONLY_EMPLOYER',   DIR_ACCESS . 'only-employer.php');
define('ACCESS_NO_EMPLOYER',     DIR_ACCESS . 'no-employer.php');

define('DIR_FUNCTIONS',          DIR_UTILS . 'functions/');
define('FUNCTION_LOGIN',         DIR_FUNCTIONS . 'login.php');
define('FUNCTION_CREATE',        DIR_FUNCTIONS . 'create.php');

define('HOME_TITLE',             'Tableau de bord');
define('CREATE_TITLE',           'Créer une entrée');
define('DISPLAY_TITLE',          'Voir une entrée');
