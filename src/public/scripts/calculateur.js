/*
  Gestion du calculateur :
    - Ajout / Supp d'aliment
    - Calcule de nutriment
*/

/*
  Envoie d'un requete POST au serveur (sur la fonction rechercheAli de CalculateurControleur)
  pour recuperer les aliments avec le nom commencant par la chaine tapé dans la barre de recherche
*/
document.querySelector('#rechercher-aliment').addEventListener('keyup', function(e) {
  let nomALiment = document.getElementById("rechercher-aliment").value;

  if (nomALiment!="") {
    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = recuperationDonnees;
    httpRequest.open('GET','index.php/calculateur/'+nomALiment);
    httpRequest.send();
  }

  function recuperationDonnees(){
    if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status==200 ) {
      document.querySelector('.select-aliment').innerHTML=httpRequest.responseText;
    }
  }

});

/*
  Gestion de l'ajout d'un aliment à la recette
*/

document.querySelector('.select-aliment').addEventListener("change", function(evt){

  let listeAliments = document.querySelector(".aliments-ajout");
  if ( evt.target.value != 'Aucun aliment trouvé') {
    let nomAli = evt.target.options[evt.target.value].text;
    let idAli = evt.target.options[evt.target.value].id;
    let newAliment = addAliment(nomAli, idAli);//ajout de l'aliment selectionné a la liste
    listeAliments.appendChild(newAliment);//ajout du nouvel aliment a la liste
    let masseInputAli = newAliment.childNodes[2];
    let btSuppr = newAliment.childNodes[3].firstChild;
    btSuppr.addEventListener('click', function(evt){evt.target.parentNode.parentNode.remove()})

    masseInputAli.addEventListener("keyup", function(e){//listener de modif de quantite d'aliment
      let quantite = e.target.value;
      let nutriments = e.target.parentNode.childNodes[1].childNodes;

      getNutriments(nutriments, idAli, quantite);
      calculeTotalNutriments();
      calculerRatio();
    });
  }
});

/*
fonction de calcule du ratio total de la recette
*/
function calculerRatio(){
  let ratio = 0;
  let lipideTotal = parseFloat(document.querySelector(".total-nutriments > section > #lipide").value);
  let proteineTotal = parseFloat(document.querySelector(".total-nutriments > section > #proteine").value);
  let glucideTotal = parseFloat(document.querySelector(".total-nutriments > section > #glucide").value);

  ratio = lipideTotal / (proteineTotal + glucideTotal);
  document.querySelector("#ratio-recette").value = ratio.toFixed(2);
  parseFloat(ratio).toFixed(2)
}

/*
  Fonction de calcule de la quantité totale de chaque nutriment pour la recette
*/
function calculeTotalNutriments(){
  let aliments = document.querySelectorAll(".aliment");
  let lipideT = 0;
  let proteineT = 0;
  let glucideT = 0;

  for (const aliment of aliments) {
    lipideT += parseFloat(aliment.childNodes[1].childNodes[0].innerHTML);
    proteineT += parseFloat(aliment.childNodes[1].childNodes[1].innerHTML);
    glucideT += parseFloat(aliment.childNodes[1].childNodes[2].innerHTML);
  }

  let resultats = document.querySelectorAll(".total-nutriments input");
  resultats[0].value = (parseFloat(lipideT)).toFixed(3);
  resultats[1].value = (parseFloat(proteineT)).toFixed(3);
  resultats[2].value = (parseFloat(glucideT)).toFixed(3);
}


/*
Fonction de recuperation des nutriments de l'aliment ajouté via un requet get ajax
*/
function getNutriments(nutriments, id, m){

  $.ajax({
    url : 'index.php/calculateur/getnutriment/'+id,
    dataType : 'json',
    async : false,
    success : function(donnee){
      nutriments[0].innerHTML = ((donnee["lipide"] * m) / 100).toFixed(3);
      nutriments[1].innerHTML = ((donnee["proteine"] * m) / 100).toFixed(3);
      nutriments[2].innerHTML = ((donnee["glucide"] * m) / 100).toFixed(3);
    }
  });


}

/*
  Ajout d'un aliment a la recette avec un blac html
*/
function addAliment(nom, id){

  let blockAliment = document.createElement("div");
  blockAliment.setAttribute('id',id+'_');
  blockAliment.setAttribute('class', 'aliment');


  let titreAli = document.createElement("h4");
  titreAli.setAttribute('class', 'titre-aliment');
  let nomAli = nom;
  let titreNode = document.createTextNode(nomAli);
  titreAli.appendChild(titreNode);

  blockAliment.appendChild(titreAli);





  let masseInputAli = document.createElement("input");//ajout de l'input de quantite a la div
  masseInputAli.setAttribute('type', 'text');
  masseInputAli.setAttribute('placeholder', 'quantité en gramme');
  masseInputAli.setAttribute('type', 'number');
  masseInputAli.setAttribute('step',"1");
  masseInputAli.setAttribute('min',"0");

  masseInputAli.setAttribute('class', 'aliment-input-quantite');

  blockAliment.appendChild(masseInputAli);

  let divNutriments = document.createElement("div")
  divNutriments.setAttribute('class', 'ingredient-total');


  let LipideTotal = document.createElement("p");
  LipideTotal.setAttribute('class', 'lipide');
  LipideTotal.appendChild(document.createTextNode('0'));

  let ProteineTotal = document.createElement("p");
  ProteineTotal.setAttribute('class', 'proteine');
  ProteineTotal.appendChild(document.createTextNode('0'));

  let GlucideTotal = document.createElement("p");
  GlucideTotal.setAttribute('class', 'glucide');
  GlucideTotal.appendChild(document.createTextNode('0'));



  let nomNutrimentL = document.createElement("p");
  nomNutrimentL.setAttribute('id', 'nutriment-titre-lipide');
  nomNutrimentL.appendChild(document.createTextNode('Lipides (g) :'));

  let nomNutrimentP = document.createElement("p");
  nomNutrimentP.setAttribute('id', 'nutriment-titre-proteine#n');
  nomNutrimentP.appendChild(document.createTextNode('Proteines (g) :'));

  let nomNutrimentG = document.createElement("p");
  nomNutrimentG.setAttribute('id', 'nutriment-titre-glucide');
  nomNutrimentG.appendChild(document.createTextNode('Glucides (g) :'));



  divNutriments.appendChild(LipideTotal);

  divNutriments.appendChild(ProteineTotal);

  divNutriments.appendChild(GlucideTotal);


  divNutriments.appendChild(nomNutrimentL);
  divNutriments.appendChild(nomNutrimentP);
  divNutriments.appendChild(nomNutrimentG);

  blockAliment.appendChild(divNutriments);
  blockAliment.appendChild(masseInputAli);

  let divBtSuppr = document.createElement("div");
  divBtSuppr.setAttribute("class", "div-supprimer-aliment");

  let btSuppr = document.createElement("p");
  btSuppr.setAttribute("class", "supprimer-aliment");
  let btSupprNode = document.createTextNode("🗑️");
  btSuppr.appendChild(btSupprNode);

  divBtSuppr.appendChild(btSuppr);


  blockAliment.appendChild(divBtSuppr);

  $(divBtSuppr).append('<input type="checkbox" name="verrou" class="lock-aliment"> <label id="verrouAlim" for="verrou"> 🔒 Verrouiller l\'aliment avec cette quantité</label>');

  return blockAliment;
}


/*
  Gestion de la box de sauvegarde de la recette
*/
var modal = document.querySelector("#myModal");
var btn = document.querySelector(".bt-save");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
