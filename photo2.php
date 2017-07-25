<?php
	session_start();
	include "connexion_bd.php";
	$pdo = connexion();
	date_default_timezone_set("Europe/Paris");
	if (isset($_SESSION['id']))
	{
		if (isset($_POST['data']))
		{
			if (!empty($_POST['data']))
			{
				if (isset($_POST['nom']))
				{
					$date_photo = date('Y-m-d H:i:s');
					$nom_img = htmlspecialchars($_POST['nom']);
					$len_nom = strlen($nom_img);
						$img = $_POST['data'];
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$fileData = base64_decode($img);
						$dossier = "Images/";
						$cle = hash('whirlpool', microtime(TRUE)*100000);
						$filename = $dossier.$_SESSION['id']."-".$nom_img."-".$cle.".jpg";
						$emplacement_img = $filename;
						file_put_contents($filename, $fileData);
						list($width, $height) = getimagesize($filename);
						$new_img = imagecreatetruecolor(600, 400);
						$image = imagecreatefromjpeg($filename);
						imagecopyresampled($new_img, $image, 0, 0, 0, 0, 600, 400, $width, $height);
						imagejpeg($new_img, $filename);
						if ($_POST['1png'] == "1ère image")
							$src = imagecreatefrompng("PNG/Png-1.png");
						else if ($_POST['1png'] == "2ème image")
							$src = imagecreatefrompng("PNG/Png-2.png");
						else if ($_POST['1png'] == "3ème image")
							$src = imagecreatefrompng("PNG/Png-3.png");
							unlink($filename);
							imagecopy($new_img, $src, 210, 100, 0, 0, 200, 199);
							imagejpeg($new_img, $emplacement_img);
							imagedestroy($filename);
							imagedestroy($src);
						$req_img_path = $pdo->prepare("INSERT INTO IMAGES(ID_MEMBRES, EMPLACEMENT, NOM, DATEPHOTO) VALUES(?, ?, ?, ?)");
						$req_img_path->execute(array($_SESSION['id'], $emplacement_img, $nom_img, $date_photo));
				}
		}
	}
	else if (isset($_POST['nom2']) && $_POST['verif'] == 'ok')
	{
		$nom_img = htmlspecialchars($_POST['nom2']);
		$extensions = array('.png', '.jpg', '.jpeg', '.gif');
		$extension = strrchr($_FILES['image']['name'], '.');
		if (in_array($extension, $extensions))
		{
			$taille_max = "5242880";
			$taille = filesize($_FILES['image']['tmp_name']);
			if ($taille <= $taille_max)
			{
				if ($_FILES['image']['error'] == 0 && $_FILES['image']['size'] != 0)
				{
						$dossier = "Images/";
						$fichier = basename($_FILES['image']['name']);
						$dossier_dest = $dossier . $fichier;
						if (move_uploaded_file($_FILES['image']['tmp_name'], $dossier_dest))
						{
							$date_photo = date('Y-m-d H:i:s');
							list($width, $height) = getimagesize($dossier_dest);
							$new_img = imagecreatetruecolor(600, 400);
							$mime = mime_content_type($dossier_dest);
							if ($mime == "image/png")
								$image = imagecreatefrompng($dossier_dest);
							else if ($mime == "image/jpg")
								$image = imagecreatefromjpeg($dossier_dest);
							if ($mime == "image/jpeg")
								$image = imagecreatefromjpeg($dossier_dest);
							imagecopyresampled($new_img, $image, 0, 0, 0, 0, 600, 400, $width, $height);
							imagejpeg($new_img, $dossier_dest);
							if ($_POST['1png'] == "1ère image")
								$src = imagecreatefrompng("PNG/Png-1.png");
							else if ($_POST['1png'] == "2ème image")
								$src = imagecreatefrompng("PNG/Png-2.png");
							else if ($_POST['1png'] == "3ème image")
								$src = imagecreatefrompng("PNG/Png-3.png");
							unlink($dossier_dest);
							$cle = hash('whirlpool', microtime(TRUE)*100000);
							$emplacement_img = $dossier.$_SESSION['id']."-".$nom_img."-".$cle.".jpg";
							imagecopy($new_img, $src, 210, 100, 0, 0, 200, 199);
							imagejpeg($new_img, $emplacement_img);
							imagedestroy($new_img);
							imagedestroy($src);
							$req_img_path = $pdo->prepare("INSERT INTO IMAGES(ID_MEMBRES, EMPLACEMENT, NOM, DATEPHOTO) VALUES(?, ?, ?, ?)");
							$req_img_path->execute(array($_SESSION['id'], $emplacement_img, $nom_img, $date_photo));
						}
						else
						{
							// ERREUR EN JS "ECHEC DE L UPLOAD DE L IMAGE"
						}
				}
				else if ($_FILES['image']['error'] == 3)
				{
					// ERREUR EN JS "FICHIER UPLOAD PARTIELLEMENT"
				}
				else if ($_FILES['image']['error'] == 4)
				{
					// ERREUR EN JS "AUCUN FICHIER UPLOAD"
				}
			}
			else
			{
				// ERREUR EN JS "FICHIER TROP VOLUMINEUX"
			}
		}
	}
	?>

<html>
    <head>
        <title>Photomontage</title>
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

				<div class="principale">
          <div class="pm">
            <br/>
            <div align="center">
							<button class="button_avec" id="buttonAvec" onclick="avec();">Avec webcam</button>
							<button class="button_sans" id="buttonSans" onclick="sans();">Sans webcam</button>

							<div class="booth" id="avecWeb">
							<form method="POST" action="photo2.php" enctype="multipart/form-data">
                <input type="radio" name="1png" id="radio1" onclick="check1();button_photo(1);" value="1ère image">
                1ère image<img src="PNG/Png-1.png" width="75" height="50"></br>
                <input type="radio" name="1png" id="radio2" onclick="check2();button_photo(1);" value="2ème image">
                2ème image<img src="PNG/Png-2.png" width="75" height="50"></br>
                <input type="radio" name="1png" id="radio3" onclick="check3();button_photo(1);" value="3ème image">
                3ème image<img src="PNG/Png-3.png" width="75" height="50">
              </form>
						</br>




						<div style="position: relative; height: 550px;">
									<video id="video" width="400" height="300"></video>
									 <div id="preview"
									 style="margin: 0 auto; transform: translateY(-500px);">
								</div>
								<canvas class="canvas" id="canvas"
									style="position: absolute; left: 0; top: 0; z-index: 0;"></canvas>
							</div>


							</br>
              <input type="text" name="nom" id="nom" placeholder="nom de l'image">
              </br>
								<div id="takephoto">
              <button onclick="validation();" class='booth-capture-button'>Take photo</button>
								</div>
								<div id ="errors">
                </div>
              </div>
<script src="photo2.js"></script>
							<div class="booth" id="sansWeb">
								<form method="POST"  enctype="multipart/form-data">

									<input type="radio" id="radio4" name="1png" value="1ère image" onclick="button_photo(2);">
									1ère image<img src="PNG/Png-1.png" width="75" height="50"></br>
									<input type="radio" id="radio5" name="1png" value="2ème image" onclick="button_photo(2);">
									2ème image<img src="PNG/Png-2.png" width="75" height="50"></br>
									<input type="radio" id="radio6" name="1png" value="3ème image" onclick="button_photo(2);">
									3ème image<img src="PNG/Png-3.png" width="75" height="50">

									</br><input type="hidden" name="MAX_FILE_SIZE" value="5242880"></br>
									<div>
										<label for="image">Upload 5 Mo max:</label>
										<input type="file" name="image" id="image_upload"></br></br>
									</div>
										</br>
									</form>
										<input type="text" id="nom2" name="nom" placeholder="nom de l'image">
										</br>

										<div id="takephoto2">
										<button onclick="validation2();" class='booth-capture-button'>Take photo</button>
										</div>
										<div id ="errors2">
										</div>

									<?php
									if (isset($erreur))
									{
										echo '<font color="red">'.$erreur."</font>";
									}
									?>
							</div>



            </div>
          </div>
          <div id="own_pm" class="own_pm">
            <iframe scrolling="no" class="frame_side" id="own_pm_frame" src="own_pm.php"></iframe>
          </div>
        </div>
        <br/>
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
?>
