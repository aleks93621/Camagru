<?php
session_start();

include "connexion_bd.php";
$pdo = connexion();
if(isset($_SESSION['id']))
{
	if(isset($_GET['id']) && $_GET['id'] > 0)
	{
		$getid = intval($_GET['id']);
		$requser = $pdo->prepare('SELECT * FROM MEMBRES WHERE ID = ?');
		$requser->execute(array($getid));
		$userinfo = $requser->fetch();
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



		<div align="center">
			<h1><?php echo $userinfo['PSEUDO']; ?></h1>
			<br/>
			Pseudo = <?php echo $userinfo['PSEUDO']; ?>
			<br/>
			Mail = <?php echo $userinfo['MAIL']; ?>
			<br/>
			</br>
			<?php
			if (isset($_SESSION['id']) AND $userinfo['ID'] == $_SESSION['id'])
			{
			?>
				<a href="edition_profil.php">Modifier mes informations</a>
			</br></br>
				<h2>Mes Images:</h2>
				<?php
		    $getid = $_SESSION['id'];
		    $requser = $pdo->prepare('SELECT count(*) FROM IMAGES WHERE ID_MEMBRES = ?');
		    $requser->execute(array($getid));
		    $userinfo = $requser->fetch();
		    $nb = $userinfo[0];

		    $requser = $pdo->prepare('SELECT EMPLACEMENT FROM IMAGES WHERE ID_MEMBRES = ?');
		    $requser->execute(array($getid));
		    $userinfo = $requser->fetchall();

				$requser = $pdo->prepare('SELECT ID_IMAGE FROM IMAGES WHERE ID_MEMBRES = ?');
		    $requser->execute(array($getid));
		    $userinfoid = $requser->fetchall();
		    ?>
		    <div class="profile_img">
		    <?php
		    while ($nb > 0) {
		    ?>
				<a href="https://www.facebook.com/sharer/sharer.php?u=www.localhost:8080/camagru/post.php?id=<?php echo($userinfoid[intval($nb) - 1][0]);?>" target="_blank">
				  <img class="soc_img" src="https://cdn3.iconfinder.com/data/icons/popular-services-brands/512/facebook-128.png"
				</a>

				<a class="twitter-share-button"
  		href="https://twitter.com/share/?url=http://www.localhost:8080/camagru/post.php?id=<?php echo($userinfoid[intval($nb) - 1][0]);?>"
  		data-size="large"
	  	data-text="custom share text"
	  	data-url="https://dev.twitter.com/web/"
	  	data-hashtags="example,demo"
	  	data-via="twitterdev"
	  	data-related="twitterapi,twitter">
			<img class="soc_img" src="http://icon-icons.com/icons2/730/PNG/512/twitter_icon-icons.com_62765.png"
			</a>

		      <div class="container">
		        <img class="profile_img2" src="<?php echo($userinfo[intval($nb) - 1][0]);?>" alt="Photomontage">
							<a href="suppr_img.php?id=<?php echo($userinfo[intval($nb) - 1][0]); ?>">
							<div class="middle">
								<img id="img_suppr" src="https://cdn3.iconfinder.com/data/icons/softwaredemo/PNG/256x256/DeleteRed.png" alt="supprimer">
							</div></a>

					</div>
		      <?php
		      $nb = $nb-1;
		      }
		      ?>
		    </div>
			<?php
			}
			?>
			<footer>
					<p>Copyright aaleksov - Tous droits r&eacute;serv&eacute;s<br/>
					<a href="https://profile.intra.42.fr/users/aaleksov" target="_blank">Me contacter !</a></p>
			</footer>
	</body>
	</html>
	<?php
	}
	else
	{
		header('Location: connexion.php');
	}
}
else
{
	header('Location: connexion.php');
}
?>
