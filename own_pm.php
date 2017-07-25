<?php
include "connexion_bd.php";
$pdo = connexion();
session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="photo.css" />
  </head>
  <body>
    <?php
    $getid = $_SESSION['id'];
    $requser = $pdo->prepare('SELECT count(*) FROM IMAGES WHERE ID_MEMBRES = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
    $nb = $userinfo[0];
    $requser = $pdo->prepare('SELECT EMPLACEMENT FROM IMAGES WHERE ID_MEMBRES = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetchall();
    ?>
    <div class="own_img">
    <?php
    while ($nb > 0) {
    ?>
      <div>
        <img class="own_img2" src="<?php echo($userinfo[intval($nb) - 1][0]);?>" alt="Photomontage">
      </div>
      <?php
      $nb = $nb-1;
      }
      ?>
    </div>
  </body>
</html>
