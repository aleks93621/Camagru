<?php
session_start();

include "connexion_bd.php";
$pdo = connexion();
if(isset($_SESSION['id']))
{
	$requser = $pdo->prepare("SELECT * FROM membres WHERE ID  = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();
	if (isset($_POST['bouton_mdp']))
	{
		$erreur = "Un mail vous a été envoyé pour changer votre mot de passe !";

		$cle = hash('whirlpool', microtime(TRUE)*100000);
		$reqcle = $pdo->prepare("SELECT * FROM membres WHERE CLE_MDP = ?");
		$reqcle->execute(array($cle));
		$cleexit = $reqcle->rowCount();
		if ($cleexit != 0)
		{
			while ($cleexit != 0)
			{
				$cle = hash('whirlpool', microtime(TRUE)*100000);
				$reqcle = $pdo->prepare("SELECT * FROM membres WHERE CLE_MDP = ?");
				$reqcle->execute(array($cle));
				$cleexit = $reqcle->rowCount();
			}
		}
		$insertkey = $pdo->prepare("UPDATE MEMBRES SET CLE_MDP = ?, MDP_ACTIF = ? WHERE ID = ?");
		$insertkey->execute(array($cle, 1, $_SESSION['id']));
		$destinataire = $user['MAIL'];
		$sujet = "Modification mot de passe Camagru";
		$entete = "From: support@camagru.com";
		$message = "Bonjour ".$user['PSEUDO'].",

Pour modifier votre mot de passe veuillez cliquer sur le lien
ou bien le copier/coller dans la barre de recherche de votre
navigateur.

http://localhost:8080/Camagru/change_mdp.php?log=".urlencode($user['PSEUDO'])."&cle=".urlencode($cle)."


---------------
Ceci est un mail automatique, Merci de ne pas y repondre.";
		mail($destinataire, $sujet, $message, $entete);
	}
	else
	{
		if (isset($_POST['newpseudo']) && !empty($_POST['newpseudo']) && $_POST['newpseudo'] != $user['PSEUDO'])
		{
			$newpseudo = htmlspecialchars($_POST['newpseudo']);
			$pseudolenght = strlen($newpseudo);
			if ($pseudolenght >= 5 && $pseudolenght <= 255)
			{
				$reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE PSEUDO = ?");
				$reqpseudo->execute(array($newpseudo));
				$pseudoexist = $reqpseudo->rowCount();
				if ($pseudoexist == 0)
				{
					$insertpseudo = $pdo->prepare('UPDATE membres SET PSEUDO = ? WHERE ID = ?');
					$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
					$_SESSION['pseudo'] = $newpseudo;
					header('Location: profil.php?id='.$_SESSION['id']);
				}
				else
				{
					$erreur = "Le pseudo est déjà utilisé !";
				}
			}
			else if ($pseudolenght < 5)
			{
				$erreur = "Votre pseudo ne doit pas faire moins de 5 caractères!";
			}
			else if ($pseudolenght > 15)
			{
				$erreur = "Votre pseudo ne doit pas dépasser 15 caracteres !";
			}
		}

		if (isset($_POST['newmail']) && !empty($_POST['newmail']) && $_POST['newmail'] != $user['MAIL'])
		{
			$newmail = htmlspecialchars($_POST['newmail']);
			if (filter_var($newmail, FILTER_VALIDATE_EMAIL))
			{
				$reqmail = $pdo->prepare("SELECT * FROM membres WHERE mail = ?");
				$reqmail->execute(array($newmail));
				$mailexist = $reqmail->rowCount();
				if ($mailexist == 0)
				{
					$insertmail = $pdo->prepare('UPDATE membres SET MAIL = ? WHERE ID = ?');
					$insertmail->execute(array($newmail, $_SESSION['id']));
					$_SESSION['mail'] = $newmail;
					header('Location: profil.php?id='.$_SESSION['id']);
				}
				else
				{
					$erreur = "L'adresse mail est déjà utilisé !";
				}
			}
			else
			{
				$erreur = "Votre adresse mail est invalide !";
			}
		}
		if (isset($_POST['newpseudo']) AND $_POST['newpseudo'] == $user['PSEUDO'] && isset($_POST['newmail']) AND $_POST['newmail'] == $user['MAIL'])
		{
			header('Location: profil.php?id='.$_SESSION['id']);
		}
	}
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
		<h1>Modifier mes informations</h1>
		<div>
			</br>
			<form method="POST" action="">
			<label>Pseudo:</label>
				<input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['PSEUDO']; ?>"/><br/><br/>
			<label>Mail:</label>
				<input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['MAIL']; ?>"/><br/><br/>
				<input type="submit" value="Mettre à jour mon profil !"/><br/><br/>
				<input type="submit" name="bouton_mdp" value="Changer de mot de passe !"/><br/><br/>
			</form>
			<?php
			if (isset($erreur))
			{
				echo '<font color="red">'.$erreur."</font>";
			}
			?>
		</div>
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
	header("Location: connexion.php");
}
?>
