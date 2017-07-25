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

    //COMMENTS

    $requser = $pdo->prepare('SELECT count(*) FROM COMMENT WHERE ID_IMAGE = ?');
    $requser->execute(array($getid));
    $nb_comment = $requser->fetch();

    $requser = $pdo->prepare('SELECT * FROM COMMENT WHERE ID_IMAGE = ?');
    $requser->execute(array($getid));
    $commentinfo = $requser->fetchall();
    //print_r($commentinfo);

    if ($_POST['comment_test'] === "OK" && $_SESSION['justliked'] < 2) {
      $_SESSION['justliked'] = 1;
      $date_photo = date('Y-m-d H:i:s');
      $requser = $pdo->prepare('INSERT INTO COMMENT(ID_IMAGE, ID_MEMBRES, DATECOMMENT, EMPLACEMENT) VALUES (?,?,?,?)');
      $requser->execute(array($getid, $userid, $date_photo, $_POST['comment']));
      }
    if ($_SESSION['justliked'] == 2)
    {
      $_SESSION['justliked'] = 0;
    }

  ?>

      <script src="gallery.js"></script>
      <div class="gallery_comment">
        <?php
          $i = 0;
        while ($i < $nb_comment[0]) {
          $requser = $pdo->prepare('SELECT * FROM MEMBRES WHERE ID = ?');
          $requser->execute(array($commentinfo[$i][2]));
          $usr = $requser->fetch();
          echo "<p>".$usr[1].": ".$commentinfo[$i][4]."</p>";
          $i++;
        }
        ?>
      </div>

<?php
if ($_SESSION['justliked'] == 1) {$_SESSION['justliked'] = 2;echo "<script> reload(); </script>";}
?>
  </body>
</html>
