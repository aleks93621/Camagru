<?php
$i = $_GET['id'];
?>
<html>
<head>
<title>Camagru Profil</title>
<link rel="stylesheet" href="photo.css" />
</head>
<body>
 <header>
   <a href="index.php" class="header_logo"><img class="head_logo" src="http://images.frandroid.com/wp-content/uploads/2014/07/guide-787.png" alt="Logo"></a>
   <a href="photo2.php"><img class="head_logo" src="http://fr.seaicons.com/wp-content/uploads/2017/02/gallery-icon.png" alt="Take picture"></a>
   <?php
     if ($_SESSION['id'] != "") {
   ?><a href="profil.php?id=<?php echo ($_SESSION['id']); ?>"><img class="head_logo" src="http://www.icone-png.com/png/54/53793.png" alt="Profile"></a>
   <?php
     }
     else {
     ?><a href="connexion.php"><img class="head_logo" src="http://www.icone-png.com/png/54/53793.png" alt="Profile"></a>
     <?php
       }
       ?>
       <a href="deconnexion.php"><img class="head_logo" src="http://icon-icons.com/icons2/79/PNG/256/logoff_15259.png" alt="Profile"></a>
 </header>
 </br>
 <script src="gallery.js"></script>
 <div class="gallery">




 <div id="own_pm" class="own_pm">
   <div>
     <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_photo.php?id=".$i); ?>" onload="resizeIframe(this)"></iframe>
     <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_like.php?id=".$i); ?>" onload="resizeIframe(this)"></iframe>
     <iframe scrolling="no" class="frame_gallery" src="<?php echo ("gallery_comment.php?id=".$i); ?>" onload="resizeIframe(this)"></iframe>
   </div>
 </div>



 </div>
 </br>
 <footer>
     <p>Copyright aaleksov - Tous droits r&eacute;serv&eacute;s<br/>
     <a href="https://profile.intra.42.fr/users/aaleksov" target="_blank">Me contacter !</a></p>
 </footer>
</body>
</html>
