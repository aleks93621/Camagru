<?php
session_start();

include "connexion_bd.php";
$pdo = connexion();
if (isset($_POST['formconnect']))
{
	$pseudoconnect = htmlspecialchars($_POST['pseudoconnect']);
	$mdpconnect = hash('whirlpool', $_POST['mdpconnect']);
	if (!empty($pseudoconnect) && !empty($mdpconnect))
	{
		$requser = $pdo->prepare("SELECT * FROM membres WHERE pseudo = ? AND password = ?");
		$requser->execute(array($pseudoconnect, $mdpconnect));
		$userexist = $requser->rowCount();
		if($userexist == 1)
		{
			$userinfo = $requser->fetch();
			if ($userinfo['ACTIF'] == 1)
			{
				$_SESSION['id'] = $userinfo['ID'];
				$_SESSION['pseudo'] = $userinfo['PSEUDO'];
				$_SESSION['mail'] = $userinfo['MAIL'];
				header("Location: profil.php?id=".$_SESSION['id']);
			}
			else
			{
				$erreur = "Votre compte n'a pas été activé !";
			}
		}
		else
		{
			$erreur = "Mauvais pseudo ou mot de passe !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}
}
if (isset($_POST['bonjour']))
{
	echo "bonjour\n";
}
?>
<html>
<head>
<title>Camagru Connexion</title>
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
<form method="post">
<label>Pseudo: <input type="text" name="pseudoconnect" placeholder="Pseudo"></label><br/>
<label>Mot de passe: <input type="password" name="mdpconnect" placeholder="Mot de passe" /></label><br/>
<input type="submit" name="formconnect" value="Se connecter"/>
<a href="mdp_oubli.php">Mot de passe oublié</a>
<a href="inscription.php">Cr&eacute;er un compte</a>
</form>
<?php
	if (isset($erreur))
	{
		echo '<font color="red">'.$erreur."</font>";
	}
?>
</br>
<footer>
		<p>Copyright aaleksov - Tous droits r&eacute;serv&eacute;s<br/>
		<a href="https://profile.intra.42.fr/users/aaleksov" target="_blank">Me contacter !</a></p>
</footer>
</body>
</html>
