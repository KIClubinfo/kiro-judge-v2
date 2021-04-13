var depose=document.getElementById("depose");
/* Gestion du clic */
depose.addEventListener("click", function(evt) {
  evt.preventDefault();
  document.getElementById("file").click();
});
/* Gestion du DRAG AND DROP */
depose.addEventListener("dragover", function(evt) {
  evt.preventDefault(); /* Pour autoriser le drop par JS */
});
depose.addEventListener("dragenter", function(evt) {
  this.className="onDropZone"; /* Passe en surbrillance */
});
depose.addEventListener("dragleave", function(evt) {
  this.className=""; /* La surbrillance s'efface */
}); 
depose.addEventListener("drop", function(evt) {
  evt.preventDefault();
  /* Tranfert de la liste des fichiers du drag and drop dans input file */
  document.getElementById("file").files=evt.dataTransfer.files;
  this.className=""; /* Surbrillance supprimée */
});
document.getElementById("file").addEventListener("change", function(evt){
  var p=document.getElementById("preview"); /* Bloc d'affichage de la liste des fichiers */
  p.innerHTML=""; /* Effacer le contenu initial de #preview */
  for (var i=0; i<this.files.length; i++) {
    var f=this.files[i];
    var div=document.createElement("div");
    div.className="fichier";
    var span=document.createElement("span");
    span.innerHTML=f.name+" ("+getHumanSize(f.size)+")";
    var vignette=document.createElement("img");
    vignette.src = window.URL.createObjectURL(f); 
    /* Attacher les élements HTML au DOM */
    div.appendChild(vignette);
    div.appendChild(span);
    p.appendChild(div);
  }
  p.style.display="block";  
});
/* Retourne une taille de fichier en mode lisible par un humain */
function getHumanSize(s) {
  s=parseInt(s); /* Pour s'assurer que le paramètre d'entrée est entier */
  if (s<1024) {
    return s+" o";  
  } else if (s<1024*1024) {
    return (s/1024).toFixed(1)+" ko";  
  } else if (s<1024*1024*1024) {
    return (s/1024/1024).toFixed(1)+" Mo";  
  } else {
    return (s/1024/1024/1024).toFixed(1)+" Go";  
  }
}
