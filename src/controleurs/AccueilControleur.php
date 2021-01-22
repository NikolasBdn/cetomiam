<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur de la page d'accueil
*/
class AccueilControleur extends Controleur{

  /*
  function d'affichage de la page d'accueil.
  */
  public function index($resquest, $response){
  	$etat = new EtatConnexion;
    return $this->view->render($response, 'home.twig', ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername()]);
  }

}
