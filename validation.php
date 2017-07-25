<?php
	include "connexion_bd.php";
	$pdo = connexion();
	$login = $_GET['log'];
	$cle = $_GET['cle'];
	$reqcle = $pdo->prepare("SELECT CLE,ACTIF FROM MEMBRES WHERE PSEUDO = ?");
	if ($reqcle->execute(array($login)) && $row = $reqcle->fetch())
	{
		$clebdd = $row['CLE'];
		$actif = $row['ACTIF'];
	}
	$reqid = $pdo->prepare("SELECT ID FROM MEMBRES WHERE PSEUDO = ?");
	$reqid->execute(array($login));
	$reqid_recup = $reqid->fetch();
	header("refresh:5;url=profil.php?id=".$reqid_recup['ID']);
	if ($actif == '1')
	{
		echo "Votre compte est déjà activé !";
	}
	else
	{
		if ($cle == $clebdd)
		{
			echo "Votre compte a bien été activé !";
			$reqcle = $pdo->prepare("UPDATE MEMBRES SET ACTIF = ?, CLE = ? WHERE PSEUDO = ?");
			$reqcle->execute(array(1, 0, $login));
		}
		else
		{
			echo "Votre compte n'a pas pu être activé !";
		}
	}
?>
