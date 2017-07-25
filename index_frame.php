<?php
include "connexion_bd.php";
$pdo = connexion();
session_start();

$req_count = $pdo->prepare('SELECT * FROM IMAGES ORDER BY DATEPHOTO DESC');
$req_count->execute();
$count_img = $req_count->rowCount();
$info_img = $req_count->fetchAll();
$message_p_page = 12;

$nbr_pages = (int)($count_img / $message_p_page);

$page = htmlspecialchars($_GET['p']);


?>
<html>
<head>
<title>Camagru Profil</title>
<link rel="stylesheet" href="photo.css" />
</head>
<body>

  </br>
  <script src="gallery.js"></script>

  <div class="gallery">

  <?php
  $i = 0;
  $nb_images = 12;
  while ($i+($nb_images*$page) < $nb_images+($nb_images*$page) && $i+($nb_images*$page) < $count_img) { ?>
  <div id="own_pm" class="own_pm">
    <div>
      <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_photo.php?id=".$info_img[$i+($nb_images*$page)][0]); ?>" onload="resizeIframe(this)"></iframe>
      <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_like.php?id=".$info_img[$i+($nb_images*$page)][0]); ?>" onload="resizeIframe(this)"></iframe>
      <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_comment.php?except=OK&id=".$info_img[$i+($nb_images*$page)][0]); ?>" onload="resizeIframe(this)"></iframe>
    </div>
  </div>
  <?php
    $i++;
  }
  ?>


  </div>

</body>
</html>
