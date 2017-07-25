<?php
session_start();
include "connexion_bd.php";
$pdo = connexion();

$tmp = explode('-', $_GET['id']);
$tmp = explode('/', $tmp[0]);

if ($tmp[1] === $_SESSION['id']) {
  $getid = $_GET['id'];
  $requser = $pdo->prepare('DELETE FROM IMAGES WHERE EMPLACEMENT = ?');
  $requser->execute(array($getid));
  unlink($_GET['id']);
  header('location:profil.php?id='.$_SESSION['id']);
}
else {
  header('location:connexion.php');
}
?>
