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
    $userid = $_SESSION['id'];
    $getid = $_GET['id'];

    //INFOS IMAGE

    $requser = $pdo->prepare('SELECT * FROM IMAGES WHERE ID_IMAGE = ? ORDER BY DATEPHOTO DESC');
    $requser->execute(array($getid));
    $imginfo = $requser->fetch();
    $emplacement = $imginfo[3];
    $nom = $imginfo[2];

    $requser = $pdo->prepare('SELECT PSEUDO, ID FROM MEMBRES WHERE ID = ?');
    $requser->execute(array($imginfo[1]));
    $usrinfo = $requser->fetch();
    $usr = $usrinfo[0];
  ?>
      <div class="gallery_post">
        </br>
        <h3><?php echo($nom); ?></h3>
        <?php
        if ($_GET['p'] !== 'profile')
        echo('<a class="profile_link" target="_blank" href="profil.php?id='.
        $usrinfo[1].
        '"><h4>L\'Artiste: '
        .$usr.'</h4></a>');
        ?>
        <img  class="gallery_img"
              src="<?php echo($emplacement);?>"
              alt="image">
      </div>
  </body>
</html>
