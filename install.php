<?php
	try
	{
		$pdo = new PDO('mysql:host=localhost', 'ahah', '123');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$requete = "CREATE DATABASE IF NOT EXISTS CAMAGRU";
		$pdo->exec($requete);

		$requete = "use CAMAGRU";
		$pdo->exec($requete);

		$requete = "CREATE TABLE IF NOT EXISTS MEMBRES(
					ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
					PSEUDO VARCHAR(255) NOT NULL,
					MAIL VARCHAR(255) NOT NULL,
					PASSWORD VARCHAR(1000) NOT NULL,
					CLE VARCHAR(1000) NOT NULL,
					CLE_MDP VARCHAR(1000) DEFAULT 0,
					CLE_MDP_RST VARCHAR(1000) DEFAULT 0,
					ACTIF INT DEFAULT 0,
					MDP_ACTIF INT DEFAULT 0,
					MDP_RST_ACTIF INT DEFAULT 0)";
		$pdo->exec($requete);

		$requete = "CREATE TABLE IF NOT EXISTS IMAGES(
					ID_IMAGE INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
					ID_MEMBRES INT REFERENCES MEMBRES (ID),
					NOM VARCHAR(1000) NOT NULL,
					EMPLACEMENT VARCHAR(1000) NOT NULL,
					DATEPHOTO DATETIME NOT NULL)";
		$pdo->exec($requete);

		$requete = "CREATE TABLE IF NOT EXISTS LIKES(
					ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
					ID_IMAGE INT REFERENCES IMAGES (ID),
					ID_MEMBRES INT REFERENCES MEMBRES (ID))";
		$pdo->exec($requete);

		$requete = "CREATE TABLE IF NOT EXISTS COMMENT(
					ID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
					ID_IMAGE INT REFERENCES IMAGES (ID),
					ID_MEMBRES INT REFERENCES MEMBRES (ID),
					DATECOMMENT DATETIME NOT NULL,
					EMPLACEMENT VARCHAR(1000) NOT NULL)";
		$pdo->exec($requete);
	}
	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}
	header('Location: inscription.php');
?>
