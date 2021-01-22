
/*
  Gestion de l'enregistrement d'une recette depuis le calculateur.
  ENvoie d'une requete POST au serveur (fonction save de CalculateurControleur pour sauvegarder la recette.
  On passe en param de la requete la variable "d"
  qui contient le nom, le ratio, le nb d'aliment, le nb de personne et le type de la recette
*/

document.querySelector('.bt-confirmsave').addEventListener('click', () => {
  let d = '';
  let i = 0;
  document.querySelectorAll('.aliment').forEach((a) => {
    let aliId = a.id.replace('_','');
    d+='a'+ ++i + '=' + aliId +'&q'+ i +'=' + a.childNodes[2].value + "&";
  });

  d+= 'n=' + document.querySelector('#nom-recette').value + '&r=' + document.querySelector('.ratio-recette > input:nth-child(2)').value;
  d+= '&nbAli=' + i;
  let selectType = document.querySelector('.type-recette');
  d+= '&t=' + selectType.options[selectType.selectedIndex].text;
  d+= '&nbp=' + document.querySelector('#nbpers-recette').value;

  /* verifie que le nombre d aliments est >1 et que le nom est valide */
  if(document.querySelectorAll('.aliment').length>1 && document.querySelector('#nom-recette').value != ''){

  $.post('index.php/calculateur/save-recette', d, function(data, status){
    console.log(data + " " + status);
    console.log('dara');
  });

  alert("La recette a été enregistée avec succès sur la page 'Mes recettes'");
}else{
    alert("La recette n'est pas valide et n'a pas été enregistée");
    console.log("enregistrement annule, argument(s) invalide");
  }
  document.querySelector('span.close').click();
})
