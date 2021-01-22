let listeAliments = [];
let pop = [];
const nbGene = 20;
const taillePop = 50;
const masseMax = 200;
const nbMutation = 10;
let monRatio;
let trouve = false;
let nbAliment;


/*
  Calculdu ratio d'un individue pour etablir un "score"
*/
function calculRatio(individue){
  let mL = 0.0;
  let mP = 0.0;
  let mG = 0.0;

  for (var i = 0; i < nbAliment; i++) {
    mL += (individue[i] * listeAliments[i].l) / 100;
    mP += (individue[i] * listeAliments[i].p) / 100;
    mG += (individue[i] * listeAliments[i].g) / 100;
  }

  let ratio = ((mL / (mG + mP))).toFixed(2);
  return ratio;
}

/*
  Fonction de génération d'un individue avec des quantités aléatoire pour chaque aliment
  borné par un max et seulement si ils n'ont pas été verouillés dans le calculateur
*/
function generation_individue(){
  let masseAliment = [];
  for (var i = 0; i < nbAliment; i++) {
    let m;
    if (!listeAliments[i].lock) {
      m = Math.round(Math.random() * masseMax);
      masseAliment.push((m / 10).toFixed(2) * 10); // on arrondie les quantite a la dizaine
    }else {
      m = $('#' + listeAliments[i].id).children('input').val();
      masseAliment.push(parseInt(m))
    }
  }
  return masseAliment;
}

/*
  Genereation d'une population d'individue (de recette)
*/
function generation_population(){
  let pop = [];
  for (var i = 0; i < taillePop; i++) {
    pop.push(generation_individue());
  }
  return pop;
}

/*
  Classement des individue en fonction de leur score (fonct calculerScore)
  plus le ratio est proche de celui de l'utilisateur plus l'indiv en classé haut
*/
function sortIndiv(){
  pop.sort((r1, r2) => {
    return calculerScore(r1) - calculerScore(r2);
  });
}

/*
  pseudo crossOver : nous sauvgardons les 5 meilleurs individue dans une autre liste
  pour ne pas qu'ils mutent
*/
function crossOver(){
  n_pop = [];
  for (var i = 0; i < 5; i++) {
    n_pop.push(pop[i]);
  }
}

/*
  Fonction de mutation d'un nb donnée d'individue :
  modif de l'une des quantite de l'individue (soit / 1.125  ou * 1.125)
  les aliments verouillés ne sont pas mutés
*/
function mutate(){
  for (var i = 0; i < nbMutation; i++) {
    let choix = Math.floor(Math.random()*(pop.length - n_pop.length) + n_pop.length - 1)
    let individual = pop[choix];

    let gene_to_mutate = Math.floor(Math.random() * nbAliment);
    while (individual[gene_to_mutate].lock) {
      console.log(individual);
      gene_to_mutate = Math.floor(Math.random() * nbAliment);
    }

    let operation = Math.floor(Math.random() * 2);

    if(operation == 1){
      individual[gene_to_mutate] =  Math.round(individual[gene_to_mutate] * 1.125);
    }else {
      individual[gene_to_mutate] =  Math.round(individual[gene_to_mutate] / 1.125);
    }
    pop[choix] = individual;
  }

}

/*
 reremplissage de la population avec les meilleurs indiv de l'ancienne pop
*/
function fillPop(){
  for (var i = 0; i < n_pop.length; i++) {
    pop[i] = n_pop[i];
  }
}

/*
  Fonction de calcul du score d'un indiv pour les classer
*/
function calculerScore(recette){
  return (Math.abs(monRatio - calculRatio(recette)).toFixed(2));
}

/*
  Insertion des valeurs dans le calculateur
*/
function insertValues(res){
  let i = 0;
  res.forEach((a) => {
    $('#'+listeAliments[i].id+'> input').val(a);

    let t = '#' + listeAliments[i].id;
    console.log(t);
    let p100 = $(t + ' > .p100');
    console.log(p100);
    console.log($(p100).children('.l').html() * a / 100 );

    $(t + ' > .ingredient-total > .lipide').html( ($(p100).children('.l').html() * a / 100 ).toFixed(1) );
    $(t + ' > .ingredient-total > .glucide').html( ($(p100).children('.g').html() * a / 100 ).toFixed(1) );
    $(t + ' > .ingredient-total > .proteine').html( ($(p100).children('.p').html() * a / 100 ).toFixed(1) );
    i++;
  })
}


/*
  Calcul des nutriment total de la recette
*/
function claculerNutri(indiv){
  let lt = 0;
  let gt = 0;
  let pt = 0;
  let cal=0;

for (var i = 0; i < listeAliments.length; i++) {
  lt += listeAliments[i].l * indiv[i] / 100;
  gt += listeAliments[i].g * indiv[i] / 100;
  pt += listeAliments[i].p * indiv[i] / 100;
  cal += listeAliments[i].c * indiv[i] / 100;
}

  let resultats = document.querySelectorAll(".total-nutriments input");
  resultats[0].value = lt.toFixed(1);
  resultats[1].value = pt.toFixed(1);
  resultats[2].value = gt.toFixed(1);
  resultats[3].value = cal.toFixed(1);
}


/*
  Fonction principale d'equilibrage
*/
function main(){
  for (var i = 0; i < nbGene; i++) {
    sortIndiv();
    crossOver();
    mutate();
    fillPop();
    sortIndiv();
  }

  sortIndiv();
  insertValues(pop[0]);
  claculerNutri(pop[0]);
  $('#ratio-recette').val(calculRatio(pop[0]));
}

/*
  Fontion de test de la possibilite d'equilibrer la recette :
    Il faut au moin 1 aliment avec un ratio supperieur et 1 avec un ratio inferieur a celui voulu
    pour pouvoir equilibrer une recette
*/
function testEquilibrage(){
    let indivTest = [];
    let inf = false;
    let sup = false;

    for (var i = 0; i < nbAliment; i++) {
      let r = parseFloat(listeAliments[i].l) / (parseFloat(listeAliments[i].g) + parseFloat(listeAliments[i].p));
      r = r.toFixed(2);

      if (r < monRatio) {
        inf = true;
      }else if (r > monRatio) {
        sup = true;
      }
    }

    return inf && sup;
}

/*
Recuperation des nutriments des aliment avec une requete get au serveur
*/
function getNutri(ali){
  $.ajax({
    url : 'index.php/calculateur/getnutriment/'+ali.id,
    dataType : 'json',
    async : false,
    success : function(donnee){//sauvegarde des nutriments pour 100g dans une balise
      if ($(ali).children('.p100').length == 0) {
        $(ali).append('<div class="p100"  style="visibility:hidden">' +
        '<p class="l">' + donnee["lipide"] + '</p>'+
        '<p class="p">' + donnee["proteine"] + '</p>'+
        '<p class="g">' + donnee["glucide"] + '</p>'+
        '<p class="k">' + donnee["kcal"] + '</p></div>');
      }
      }

 });
}

$('.bt-equilibrer').click(equilibrer)


/*
  Recuperation des aliments et de leurs nutriments 
*/
function equilibrer(){
  listeAliments = [];
  let aliments = document.querySelectorAll('.aliment');

  aliments.forEach((a) => {
    getNutri(a);
    let tabN =[]
    $('#' + a.id + ' > .p100').children().each(function(n){
      tabN.push($(this).html());
    });
    let lck = $(a).children('.div-supprimer-aliment').children('input').is(':checked') // etat checkboc

    listeAliments.push(
      {
        l : tabN[0],
        p : tabN[1],
        g : tabN[2],
        c : tabN[3],
        id : a.id,
        lock : lck
      }
    );
  })

  nbAliment = listeAliments.length;
  monRatio = parseFloat($('#ratio-patient').val());
  if (testEquilibrage()) {
    pop = generation_population();
    main();
    console.log(pop);

  }else {
    alert("Vérifiez que vous ayez bien rentré votre ratio.\n"+
      "\nSi c'est le cas, les aliments sélectionnés ne peuvent pas être équilibrés.\n" +
      "Ajoutez des matières grasses ou retirez les aliments les plus riches.\n")
  }

}
