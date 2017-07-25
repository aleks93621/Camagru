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

    if ($_POST['comment_test'] === "OK" && $_SESSION['justliked'] < 2 && isset($_SESSION['id'])) {
      $_SESSION['justliked'] = 1;
      $date_photo = date('Y-m-d H:i:s');
      $requser = $pdo->prepare('INSERT INTO COMMENT(ID_IMAGE, ID_MEMBRES, DATECOMMENT, EMPLACEMENT) VALUES (?,?,?,?)');
      $requser->execute(array($getid, $userid, $date_photo, $_POST['comment']));

      $req_id_destinataire = $pdo->prepare('SELECT ID_MEMBRES FROM IMAGES WHERE ID_IMAGE = ?');
      $req_id_destinataire->execute(array($getid));
      $destinataire_id = $req_id_destinataire->fetch();

      echo $destinataire_id['ID_MEMBRES'];

      $req_infos_destinataire = $pdo->prepare('SELECT PSEUDO, MAIL FROM MEMBRES WHERE ID = ?');
      $req_infos_destinataire->execute(array($destinataire_id['ID_MEMBRES']));
      $destinataire_infos = $req_infos_destinataire->fetch();

      $destinataire_pseudo = $destinataire_infos['PSEUDO'];
      $destinataire_mail = $destinataire_infos['MAIL'];

      $sujet = "Une de vos publications a ete commentee !";
      $entete = "From: infos@camagru.com";
      $message = "Bonjour/Bonsoir ".$destinataire_pseudo.",

Une de vos photos a ete commentee ! Cliquez sur le lien suivant pour acceder a votre photo !

http://localhost:8080/Camagru/post.php?id=".urlencode($getid)."


---------------
Ceci est un mail automatique, Merci de ne pas y repondre.";

      mail($destinataire_mail, $sujet, $message, $entete);

      }
    if ($_SESSION['justliked'] == 2)
    {
      $_SESSION['justliked'] = 0;
    }

  ?>

      <script src="gallery.js"></script>
      <div class="gallery_comment">
        <div class="gallery_comments">
        <?php
        if ($_GET['except'] === "OK") {
          $i = $nb_comment[0] - 5;
        }
        else {
          $i = 0;
        }
        while ($i < $nb_comment[0]) {
          $getexcept = $_GET['except'];
          $requser = $pdo->prepare('SELECT * FROM MEMBRES WHERE ID = ?');
          $requser->execute(array($commentinfo[$i][2]));
          $usr = $requser->fetch();
          $datepost = date_create($commentinfo[$i][3]);
          $datepost = date_format($datepost, "H:i d/m/y");
          if (isset($commentinfo[$i][4])) {
          echo "<p>".$usr[1]." [".$datepost."] : ".$commentinfo[$i][4]."</p>";
          }
          $i++;
        }
        ?>
        </div>
        <form action="<?php echo("gallery_comment.php?except=".$getexcept."&id=".$getid); ?>" method="POST">
          <input type="text" name="comment" rows="1" cols="50" class="comment_text" placeholder="Commentez la publication"></textarea>
          <input type="hidden" name="comment_test" value="OK">
          <input type="submit" value="commenter" class="boutton_comment">
        </form>
      </div>
      <a target="_blank" href=" <?php echo("post.php?id=".$getid);?>">voir tous les commentaires</a>
<?php
if ($_SESSION['justliked'] == 1) {$_SESSION['justliked'] = 2;echo "<script> reload(); </script>";}
?>
  </body>
</html>
