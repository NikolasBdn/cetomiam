<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\User;
use cetomiam\modeles\Recette;
use cetomiam\modeles\ContenuRecette;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur du calculateur
*/

class CalculateurControleur extends Controleur{

  /*
  Fonction d'affichage de la page du CALCULATEUR.
  Si l'utilisateur n'est pas connecte il est redirigé vers la page de connexion.
  */
  public function index($resquest, $response){
    $etat = new EtatConnexion;
    if(isset($_SESSION['username'])){
      return $this->view->render($response, 'calculateur.twig', ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername(), 'userRatio' => $etat->getRatio()]);
    }else{
      return $response->withRedirect('./login');
    }
  }

  /*
  Function de recherche d'un aliment dans le calculateur
  retourne une / des balises html <option> avec le nom des Aliments
  commencant par le lettre passé en argument de la requete
  */
  public function rechercheAli($resquest, $response, $args){
    if ($args != NULL) {
      $aliment=(String)trim($args["a"]);
      $listeA = Aliment::where('nomAli', 'like',$aliment.'%')->get();
      $aliments = "";
      $i = 0;

      foreach ($listeA as $a) {
        $aliments.="<option value='$i' id='$a->idAli' >$a->nomAli</option><br>";
        $i++;
      }
      return $aliments;
    }
  }

  /*
  Fonction de recupération d'un aliment apres l'avoir selectionné dans le CALCULATEUR
  retourneun tableau avec toutes les informations de l'aliment en question
  */
  public function getNutriment($request, $response, $args){
    if ($args != NULL) {
      $id = $args["id"];
      $res = Aliment::where('idAli', '=', $id)->first();
      $tabNutriment = array(
        'lipide' => $res->lipideAli,
        'proteine' => $res->proteineAli,
        'glucide' => $res->glucideAli,
        'kcal' => $res->kcal,
      );
      return json_encode($tabNutriment, JSON_UNESCAPED_UNICODE);
    }
  }


  /*
  Fonction de sauvegarde d'une recettes depuis le CALCULATEUR
  Enregistrement de la recette dans la BDD
  */
  public function save($request, $response){
    $etat = new EtatConnexion;
    $r = new Recette();
    $r->nomRec = $_POST['n'];
    $r->ratioRec = $_POST['r'];
    $r->typeRec = $_POST['t'];
    $r->email = $etat->getMail();
    $r->tokenRec = uniqid();
    $r->save();

    $idR = $r->idRec;


    $p = "";
    for ($i=1; $i <= $_POST['nbAli']; $i++) {
      $p.=$_POST['a'. $i];
      $p.=' ';
      $c = new ContenuRecette();
      $c->idRec = $idR;
      $c->idAli = $_POST['a'. $i];
      if (isset($_POST['nbp']) && $_POST['nbp'] > 0) {
        $c->quantite = $_POST['q'. $i] / $_POST['nbp'];
      }else {
        $c->quantite = $_POST['q'. $i];
      }

      $c->save();
    }
  return $request->getParam('r') ;
  }

}
