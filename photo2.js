var video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');

navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(function(stream) {
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        console.log("An error occured! " + err);
    });

function streamWebCam (stream)
{
	video.setAttribute('width', 600);
	video.setAttribute('height', 500);
	canvas.setAttribute('width', 600);
	canvas.setAttribute('height', 400);
	video.src = window.URL.createObjectURL(stream);
	video.play();
}

function throwError (e)
{
	alert(e.name);
}

function snap ()
{
	canvas.width = 600;
	canvas.height = 400;
	context.drawImage(video, 0, 0, 600, 400);
}

function createXmlHttpRequestObject()
{
	var xmlHttp;
	try {
		xmlHttp = new XMLHttpRequest();
	} catch(e){
		try {
			xmlHttp = new ActiveXObject(Microsoft.XMLHTTP);
		} catch(e) {
			alert("Erreur lors de la création de l'objet XMLHttpRequest");
		}
	}
	if (!xmlHttp){
		alert('Erreur lors de la création de l\'objet XMLHttpRequest');
	} else {
		return xmlHttp;
	}
}

function button_photo(num) {
	if (num === 1)
		document.getElementById('takephoto').style.display='block';
	if (num === 2)
		document.getElementById('takephoto2').style.display='block';
}

function check1()
{
	var radio1 = document.getElementById('radio1');
	var radio2 = document.getElementById('radio2');
	var radio3 = document.getElementById('radio3');

	radio1.checked = true;
	radio2.checked = false;
	radio3.checked = false;
	document.getElementById('preview').style.background = "url(PNG/Png-1.png) no-repeat center center";
}

function check2()
{
	var radio1 = document.getElementById('radio1');
	var radio2 = document.getElementById('radio2');
	var radio3 = document.getElementById('radio3');

	radio2.checked = true;
	radio1.checked = false;
	radio3.checked = false;
	document.getElementById('preview').style.background = "url(PNG/Png-2.png) no-repeat center center";
}

function check3()
{
	var radio1 = document.getElementById('radio1');
	var radio2 = document.getElementById('radio2');
	var radio3 = document.getElementById('radio3');

	radio3.checked = true;
	radio2.checked = false;
	radio1.checked = false;
	document.getElementById('preview').style.background = "url(PNG/Png-3.png) no-repeat center center";

}


function save ()
{
			var canvasData = canvas.toDataURL('image/jpeg');
			var nom = document.getElementById('nom').value;
			var radio1 = document.getElementById('radio1');
			var radio2 = document.getElementById('radio2');
			var radio3 = document.getElementById('radio3');

			if (radio1.checked == true) {
				var returnValue = "data="+canvasData+"&nom="+nom+"&1png="+radio1.value;
			}
			else if (radio2.checked == true) {
				var returnValue = "data="+canvasData+"&nom="+nom+"&1png="+radio2.value;
			}
			else if (radio3.checked == true) {
				var returnValue = "data="+canvasData+"&nom="+nom+"&1png="+radio3.value;
			}
			else {
				var returnValue = "data="+canvasData+"&nom="+nom;
			}
			var requete = createXmlHttpRequestObject();
			requete.open('POST', "photo2.php", true);
			requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			requete.send(returnValue);
}

function save2 ()
{
			var nom2 = document.getElementById('nom2').value;
			var radio1 = document.getElementById('radio4');
			var radio2 = document.getElementById('radio5');
			var radio3 = document.getElementById('radio6');
			var formData = new FormData();

			if (radio1.checked == true) {
				formData.append('1png', radio1.value);
			}
			else if (radio2.checked == true) {
				formData.append('1png', radio2.value);
			}
			else if (radio3.checked == true) {
				formData.append('1png', radio3.value);
			}
			else {
				formData.append('1png', "");
			}

			formData.append('image', document.getElementById('image_upload').files[0]);
			formData.append('nom2', nom2);
			formData.append('verif', "ok");

			var xhr = createXmlHttpRequestObject();
			xhr.open('POST', "photo2.php", true);
			xhr.send(formData);
//			var iframe = document.getElementById('own_pm_frame');
//			var tmp_src = iframe.src;
//			iframe.src = '';
//			iframe.src = tmp_src;
}

function validation(){
    if(document.getElementById("nom").value.length < 5 || document.getElementById("nom").value.length >= 30){
         document.getElementById('errors').innerHTML="Le nom de l'image doit etre compris entre 5 et 30 caractères !";
    }
		else if(document.getElementById('radio1').checked == false && document.getElementById('radio2').checked == false && document.getElementById('radio3').checked == false){
         document.getElementById('errors').innerHTML="Choisissez un filtre pour enregistrer la photo !";
    }
		else {
			document.getElementById('errors').innerHTML="";
			snap();
			save();
			document.getElementById('own_pm_frame').contentWindow.location.reload();
		}
  }

function validation2(){
	  if(!document.getElementById('image_upload').value)
		{
			document.getElementById('errors2').innerHTML="Merci de choisir un fichier";
		}
		else if(document.getElementById('nom2').value.length < 5 || document.getElementById("nom2").value.length >= 30){
       document.getElementById('errors2').innerHTML="Le nom de l image doit etre compris entre 5 et 30 caractères !";
    }
		else {

				document.getElementById('errors2').innerHTML="";
				save2 ();
				setTimeout(function(){ document.getElementById('own_pm_frame').contentWindow.location.reload(); }, 1000);
			}
}

function avec ()
{
	document.getElementById('avecWeb').style.display = "inline";
	document.getElementById('buttonAvec').style.display = "none";

	document.getElementById('sansWeb').style.display = "none";
	document.getElementById('buttonSans').style.display = "inline";
}
function sans ()
{
	document.getElementById('sansWeb').style.display = "inline";
	document.getElementById('buttonSans').style.display = "none";

	document.getElementById('avecWeb').style.display = "none";
	document.getElementById('buttonAvec').style.display = "inline";
}
