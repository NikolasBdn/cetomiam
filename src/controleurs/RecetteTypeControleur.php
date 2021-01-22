<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\Recette;
use cetomiam\modeles\ContenuRecette;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur d'une recette type
*/
class RecetteTypeControleur extends Controleur{

/*
  Controleur de la page d'une recette type
  Affiche une recette type, ici la recette hamburger d'avocat au saumon
*/
  public function index($resquest, $response){
    $recettes = Recette::all();
    $etat = new EtatConnexion;
    return $this->view->render($response, 'recipetest.twig',['recettes' =>$recettes, 'etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername()]);
  }

}
