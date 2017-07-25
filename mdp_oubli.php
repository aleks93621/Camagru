<?php
include "connexion_bd.php";
$pdo = connexion();

if (isset($_POST['envoyer']))
{
	$infouser = NULL;
	if (!empty($_POST['pseudo']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE PSEUDO = ?");
		$reqpseudo->execute(array($pseudo));
		$pseudoexist = $reqpseudo->rowCount();
		$infouser = $reqpseudo->fetch();
		if ($pseudoexist == 1)
		{
			if ($infouser['ACTIF'] == 1)
			{

				$erreur = "Un message a été envoyé à l'adresse mail lié à votre compte : ";

				$cle = hash('whirlpool', microtime(TRUE)*100000);
				$reqcle = $pdo->prepare("SELECT * FROM membres WHERE CLE_MDP_RST = ?");
				$reqcle->execute(array($cle));
				$cleexit = $reqcle->rowCount();
				if ($cleexit != 0)
				{
					while ($cleexit != 0)
					{
						$cle = hash('whirlpool', microtime(TRUE)*100000);
						$reqcle = $pdo->prepare("SELECT * FROM membres WHERE CLE_MDP_RST = ?");
						$reqcle->execute(array($cle));
						$cleexit = $reqcle->rowCount();
					}
				}
				$req_cle_rst = $pdo->prepare("UPDATE MEMBRES SET CLE_MDP_RST = ?, MDP_RST_ACTIF = ? WHERE PSEUDO = ?");
				$req_cle_rst->execute(array($cle, 1, $pseudo));

				$destinataire = $infouser['MAIL'];
				$sujet = "Reinitialisation du Mot de Passe";
				$entete = "From: support@camagru.com";
				$message = 'Bonjour/Bonsoir '.$pseudo.',

Pour réinitialiser son mot de passe veuillez cliquer sur le lien suivant
ou bien le copier/coller dans la barre de votre navigateur.

http://www.localhost:8080/Camagru/mdp_oublier_v.php?log='.urlencode($pseudo).'&cle='.urlencode($cle).'


---------------
Ceci est un mail automatique, Merci de ne pas y repondre.';
				mail($destinataire, $sujet, $message, $entete);
			}
			else
			{
				$infouser = NULL;
				$erreur = "Votre compte n'a pas été activé !";
			}
		}
		else
		{
			$erreur = "Ce pseudo n'existe pas";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent êtres remplis !";
	}
}
?>
<html>
	<head>
		<title>Camagru oubli de mot de passe</title>
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
		<div align="center">
			<h2>Oubli de mot de passe</h2>
			<br/><br/>
		<label>Pseudo: <input type="text" name="pseudo" placeholder="Pseudo"></label><br/>
		<input type="submit" name="envoyer" value="Envoyer"/>
			<br/><br/>
			<?php
			if (isset($erreur))
			{
				echo '<font color="red">'.$erreur."</font>". $infouser['MAIL'];
			}
			?>
		</div>
	</form>
</br>
	<footer>
			<p>Copyright aaleksov - Tous droits r&eacute;serv&eacute;s<br/>
			<a href="https://profile.intra.42.fr/users/aaleksov" target="_blank">Me contacter !</a></p>
	</footer>
	</body>
</html>
