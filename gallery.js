window.onresize = resize;

function resize() {
    var frames = document.getElementsByTagName("iframe");
    var i = 0;
    while (frames[i]) {
      frames[i].style.height = 0;
      frames[i].style.height = frames[i].contentWindow.document.body.scrollHeight + 'px';
      i++;
    }
}

function resizeIframe(obj) {
  obj.style.height = 0;
obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function like(obj) {
  if (obj.src.endsWith("like-logo.png")) {
    obj.src = "Images/src/liked-logo.png";
  }
  else if (obj.src.endsWith("liked-logo.png")) {
    obj.src = "Images/src/like-logo.png";
  }
  window.location=window.location;
}

function like(obj) {
  if (obj.src.endsWith("like-logo.png")) {
    obj.src = "Images/src/liked-logo.png";
  }
  else if (obj.src.endsWith("liked-logo.png")) {
    obj.src = "Images/src/like-logo.png";
  }
  window.location=window.location;
}

function reload() {
  window.location=window.location;
}

function get_page() {
  var i = 0;
  while (document.getElementById('gallery'+i)) {
      i++;
  }
  return(i);
}

function pagination(nb_pages) {
  var page = get_page();
  var message = '<iframe style="width: 100%;" scrolling="no" class="frame_gallery" src="index_frame.php?p='+page+'" onload="resizeIframe(this)"></iframe>';
  nouveauDiv = document.createElement("div");
  nouveauDiv.className = "gallery";
  nouveauDiv.id = "gallery"+page;
  nouveauDiv.innerHTML = message;
  mon_div = document.getElementById("button_pag");
  document.body.insertBefore(nouveauDiv, mon_div);
  if (page == nb_pages) {
    document.getElementById('button_pag').style.display='none';
  }
}
