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

    //LIKES

    $requser = $pdo->prepare('SELECT count(*) FROM LIKES WHERE ID_IMAGE = ?');
    $requser->execute(array($getid));
    $likesinfo = $requser->fetch();
    $nb_like=$likesinfo[0];

    $requser = $pdo->prepare('SELECT count(*) FROM LIKES WHERE ID_IMAGE = ? AND ID_MEMBRES = ?');
    $requser->execute(array($getid, $userid));
    $likeinfo = $requser->fetch();
    if ($likeinfo[0] > 0)
      $liked = 1;
    else
      $liked = 0;

    if ($_POST['like_test'] === "OK" && $_SESSION['justliked'] < 2 && isset($_SESSION['id'])) {
      $_SESSION['justliked'] = 1;
      if ($liked == 1) {
        $requser = $pdo->prepare('DELETE FROM LIKES WHERE ID_IMAGE = ? AND ID_MEMBRES = ?');
        $requser->execute(array($getid, $userid));
      }
      else {
        $requser = $pdo->prepare('INSERT INTO LIKES(ID_IMAGE, ID_MEMBRES) VALUES (?,?)');
        $requser->execute(array($getid, $userid));
      }
    }
    if ($_SESSION['justliked'] == 2)
    {
      $_SESSION['justliked'] = 0;
    }
  ?>

      <script src="gallery.js"></script>
      <div class="gallery_post">
          <form action="<?php echo("gallery_like.php?id=".$getid); ?>" method="POST">
            <label class="nb_like"><?php echo $nb_like; ?></label>
            <input type="hidden" name="like_test" value="OK" onclick="reload();">
            <input class="
              <?php echo $liked == 1 ? 'liked_button' : 'like_button'; ?>"
              type="submit" value="">
          </form>
      </div>

<?php
if ($_SESSION['justliked'] == 1) {$_SESSION['justliked'] = 2;echo "<script> reload(); </script>";}
?>

  </body>
</html>
