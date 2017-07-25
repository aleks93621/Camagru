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


<script src="gallery.js" type="text/javascript"></script>


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


  <div class="gallery" id="gallery0">
    <iframe id="indexframe0" style="width: 100%" scrolling="no" class="frame_gallery" src="index_frame.php?p=0" onload="resizeIframe(this);"></iframe>
  </div>

<?php
  if ($nbr_pages > 0)
  {
?>
  <div id="button_pag" align="center">
    </br>
    <button onclick="<?php echo('pagination('.$nbr_pages.')');?>">Afficher plus de photos</button>
  </div>
<?php
}
?>


  </br>
  <footer>
      <p>Copyright aaleksov - Tous droits r&eacute;serv&eacute;s<br/>
      <a href="https://profile.intra.42.fr/users/aaleksov" target="_blank">Me contacter !</a></p>
  </footer>
</body>
</html>
