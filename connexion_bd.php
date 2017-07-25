<?php
	function connexion()
	{
		$pdo = new PDO('mysql:host=localhost;dbname=Camagru', 'root', 'root');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return ($pdo);
	}
?>
