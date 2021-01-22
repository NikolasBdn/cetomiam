<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\Recette;
use cetomiam\modeles\ContenuRecette;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur des recettes
*/
class RecetteControleur extends Controleur{
 
  /*
  Fonction d'affichage de la page des recettes de l'utilisateur.
  Si l'utilisateur n'est pas connecte il est redirigé vers la page de connexion.
  */
  public function index($resquest, $response){
    $etat = new EtatConnexion;
    if(isset($_SESSION['username'])){
      $recettes = Recette::where('email', '=', $etat->getMail())->get();
      foreach ($recettes as $r)
      $r['kcal']=$this->calculCalorie($r);
      return $this->view->render($response, 'mesrecettes.twig',['recettes' =>$recettes, 'etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername()]);
    }else{
      return $response->withRedirect('./login');
    }
  }

  /*
    Fonction de récupération du contenu d'une recette dans la bdd à partir d'un token
  */
  public function getRecette($resquest, $response, $args){
    $r = Recette::where('tokenRec', '=', $args['r'])->first();
    $c = contenuRecette::join('aliment', 'aliment.idAli', '=', 'contenuRec.idAli')->where('idRec', '=', $r->idRec)->get ();
    return $this->view->render($response, 'recette.twig', ['r' => $r, 'c' => $c, 'calorie'=>$this->calculCalorie($r), 'etatConnexion' => EtatConnexion::getConnexion(), 'userConnecte' => EtatConnexion::getUsername()]);
  }

  /*
    Fonction d'afrfichage d'une recette type (non utilisé dans actuellement)
  */
  public function test($resquest, $response){
    return $this->view->render($response, 'recipetest.twig');
  }

  /*
    Fonction de suppression d'une recette à partir d'un token
  */
  public function removeRecette($resquest, $response, $args){
    $r = Recette::where('tokenRec', '=', $args['r'])->where('email', '=', $_SESSION['user']->email)->first();
    ContenuRecette::where('idRec', '=', $r->idRec)->delete();
    $r->delete();
  }


  /*
    Fonction de recherche d'une recette
    On peut rechercher une recette à partir de sa catégorie et/ou d'une partie de son nom
  */
  public function rechercheRecette($resquest, $response, $args){

    if($args != NULL) {

      if ($args['r'] == 'null') {
        if ($args['c'] == 'Catégories') {
          $listeR = Recette::where('email', '=', EtatConnexion::getMail())->get();
        }else {
          $listeR = Recette::where('email', '=', EtatConnexion::getMail())->where('typeRec', '=', $args['c'])->get();
        }

      }else{
        if ($args['c'] == 'Catégories') {
          $listeR = Recette::where('nomRec', 'like', '%'.$args['r'].'%')->where('email', '=', EtatConnexion::getMail())->get();
        }else {
          $listeR = Recette::where('nomRec', 'like', '%'.$args['r'].'%')->where('typeRec', '=', $args['c'])->where('email', '=', EtatConnexion::getMail())->get();
        }
      }

      $res = "";
      $url = str_replace('/recettes', '/recette', $_SERVER['HTTP_REFERER'].'/');
      foreach ($listeR as $r) {
        $res .= '<a id="1" class="recette" href="'.$url.$r['tokenRec'].'">
        <h4>'.$r['nomRec'].'</h4>
        <p id="categ">Catégorie : '.$r['typeRec']. '</p>
        <p><br>Ratio : '.$r['ratioRec'].'</p>
        <p>Calories : '.$this->calculCalorie($r).'</p><br>
        </a>';
      }
      return $res;
    }
  }

  /*
    Fonction permettant de calculer les calories d'une recette en les récupérant de la bdd
  */
  private function calculCalorie($recette){
    $contenu=ContenuRecette::where('idRec', '=', $recette->idRec)->get();
    $calorie=0;
    foreach($contenu as $i){
      $aliment=Aliment::where('idAli', '=', $i->idAli)->first();
      $calorie+=($i->quantite/100*$aliment->kcal);
    }
    return $calorie;
  }
}
