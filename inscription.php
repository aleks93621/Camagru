<?php
	include "connexion_bd.php";
	include "verif_pw.php";
	$pdo = connexion();
	if (isset($_POST['bouton']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$email = htmlspecialchars($_POST['email']);
		if (!empty($_POST['pseudo']) && !empty($_POST['passe']) && !empty($_POST['passe2']) && !empty($_POST['email']))
		{
			$passe = hash('whirlpool', $_POST['passe']);
			$passe2 = hash('whirlpool', $_POST['passe2']);

			$pseudolenght = strlen($pseudo);
			if ($pseudolenght <= 15 && $pseudolenght >= 5)
			{
				if (filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$reqmail = $pdo->prepare("SELECT * FROM membres WHERE mail = ?");
					$reqmail->execute(array($email));
					$mailexist = $reqmail->rowCount();

					$reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE pseudo = ?");
					$reqpseudo->execute(array($pseudo));
					$pseudoexist = $reqpseudo->rowCount();
					if ($pseudoexist == 0)
					{
						if ($mailexist == 0)
						{
							if ($passe == $passe2)
							{
								$lenmdp = strlen($_POST['passe']);
								if ($lenmdp >= 6 && verif_pw($_POST['passe'])===true)
								{
									$cle = hash('whirlpool', microtime(TRUE)*100000);
									$reqcle = $pdo->prepare("SELECT * FROM membres WHERE cle = ?");
									$reqcle->execute(array($cle));
									$cleexit = $reqcle->rowCount();
									if ($cleexit != 0)
									{
										while ($cleexit != 0)
										{
											$cle = hash('whirlpool', microtime(TRUE)*100000);
											$reqcle = $pdo->prepare("SELECT * FROM membres WHERE cle = ?");
											$reqcle->execute(array($cle));
											$cleexit = $reqcle->rowCount();
										}
									}
									$insertmbr = $pdo->prepare("INSERT INTO membres(PSEUDO, MAIL, PASSWORD, CLE) VALUES(?, ?, ?, ?)");
									$insertmbr->execute(array($pseudo, $email, $passe, $cle));
									$destinataire = $email;
									$sujet = "Activer votre compte Camagru";
									$entete = "From: inscription@camagru.com";
									$message = 'Bienvenue sur Camagru,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur internet.

http://localhost:8080/Camagru/validation.php?log='.urlencode($pseudo).'&cle='.urlencode($cle).'


---------------
Ceci est un mail automatique, Merci de ne pas y repondre.';
									mail($destinataire, $sujet, $message, $entete);
									$erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
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
							$erreur = "L'adresse mail est déjà utilisée !";
						}
					}
					else
					{
						$erreur = "Le pseudo est déjà utilisé !";
					}
				}
				else
				{
					$erreur = "Votre adresse mail est invalide !";
				}
			}
			else if ($pseudolenght > 15)
			{
				$erreur = "Votre pseudo ne doit pas dépasser 15 caracteres !";
			}
			else if ($pseudolenght < 5)
			{
				$erreur = "Votre pseudo ne doit pas faire moins de 5 caractères!";
			}
		}
		else
		{
			$erreur = "Tous les champs doivent être complétés !";
		}
	}
?>
<html>
<head>
<title>Camagru Inscription</title>
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
<label>Pseudo: <input type="text" name="pseudo" value="<?php if (isset($pseudo)) { echo $pseudo; }?>"/></label><br/>
<label>Mot de passe: <input type="password" name="passe"/></label><br/>
<label>Confirmation du mot de passe: <input type="password" name="passe2"/></label><br/>
<label>Adresse e-mail: <input type="text" name="email" value="<?php if (isset($email)) { echo $email; }?>"/></label><br/>
<input type="submit" name="bouton" value="M'inscrire"/>
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
