<?php
session_start();

include "verif_pw.php";
include "connexion_bd.php";
$pdo = connexion();
if(isset($_SESSION['id']))
{
	if (isset($_POST['bouton']))
	{
		$pseudo = $_GET['log'];
		$cle = $_GET['cle'];
		if ($_SESSION['pseudo'] == $pseudo)
		{
			$reqclebdd = $pdo->prepare("SELECT CLE_MDP,MDP_ACTIF FROM MEMBRES WHERE ID = ?");
			$reqclebdd->execute(array($_SESSION['id']));
			$infocle = $reqclebdd->fetch();
			if ($infocle['MDP_ACTIF'] == 1)
			{
				if ($cle == $infocle['CLE_MDP'])
				{
					if (!empty($_POST['passe']) && !empty($_POST['passe2']))
					{
						$mdp1 = hash('whirlpool', $_POST['passe']);
						$mdp2 = hash('whirlpool', $_POST['passe2']);
						if ($mdp1 == $mdp2)
						{
							$lenmdp = strlen($_POST['passe']);
							if ($lenmdp >= 6 && verif_pw($_POST['passe'])===true)
							{
								$reqchdp = $pdo->prepare("UPDATE MEMBRES SET PASSWORD = ?, CLE_MDP = ?, MDP_ACTIF = ? WHERE ID = ?");
								$reqchdp->execute(array($mdp1, 0, 0, $_SESSION['id']));
								header('Location: profil.php?id='.$_SESSION['id']);
							}
							else
							{
								$erreur = "Votre mot de passe doit contenir plus de 6 caractères dont une miniscule, une majuscule, un chiffre et une caractere special !";
							}
						}
						else
						{
							$erreur = "Vos mots de passe ne correspondent pas !";
						}
					}
					else
					{
						$erreur = "Tous les champs doivent être complétés !";
					}
				}
				else
				{
					$erreur = "Votre lien de changement de mot de passe a expiré ou est éroné !";
				}
			}
			else
			{
				$erreur = "Vous n'êtes pas autorisé à changer de mot de passe !";
			}
		}
		else
		{
			$erreur = "Cette page est reservée à un autre utilisateur !";
		}
	}
?>
<html>
<head>
<title>Camagru Profil</title>
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
	<div align="center">
		<h2>Edition de mon profil</h2>
		<link rel="stylesheet" href="photo.css" />
		<div align="left">
			<form method="POST" action="">
				<label>Mot de passe: <input type="password" name="passe"/></label><br/>
				<label>Confirmation du mot de passe: <input type="password" name="passe2"/></label><br/>
				<input type="submit" name="bouton" value="Changer de mot de passe"/>
			</form>
			<?php
			if (isset($erreur))
			{
				echo '<font color="red">'.$erreur."</font>";
			}
			?>
		</div>
	</br>
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
