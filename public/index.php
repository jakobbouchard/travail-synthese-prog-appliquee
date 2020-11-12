<?php
  session_start();
  ob_start();

  // Importer les constantes et changer le titre de la page
  require( 'utils/config.php' );
  $page_title = HOME_TITLE;

  include( ACCESS_CONNECTED );
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- START INCLUDE META -->
<?php
  include( VIEW_META );
?>
  <!-- END INCLUDE META -->
</head>

<body>
  <!-- START INCLUDE HEADER -->
<?php
  include( VIEW_HEADER );
?>
  <!-- END INCLUDE HEADER -->

  <div class="container-fluid">
    <div class="row">

      <!-- START INCLUDE NAVIGATION -->
<?php
  include( VIEW_NAVIGATION );
?>
      <!-- END INCLUDE NAVIGATION -->

      <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <div class="container">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tableau de bord</h1>
          </div>
        </div>
      </main>

    </div>
  </div>

  <!-- START INCLUDE FOOTER -->
<?php
  include( VIEW_FOOTER );
?>
  <!-- END INCLUDE FOOTER -->

</body>
</html>
