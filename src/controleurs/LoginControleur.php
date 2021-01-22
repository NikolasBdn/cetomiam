<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\Recette;
use cetomiam\modeles\User;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur de la page de connexion
*/
class LoginControleur extends Controleur{

  /*
    Fonction d'affichage de la page de connexion si l'utilisateur n'est pas connecté
  */
  public function index($resquest, $response){
    if(!isset($_SESSION['username'])){
        $etat = new EtatConnexion;
        return $this->view->render($response, 'login.twig' , ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername()]);
    }
    else{
        session_destroy();
        session_start();
        return $response->withRedirect('.');
    }
  }

  /*
    Fonction permetant de connecté un utilisateur au site si c'est information de connexion existe dans la bdd
  */
  public function login($resquest, $response){
    $errors = array(); 

    if(isset($_POST['login'])){

      //récupération des données du formulaire
      $mail = $username = $_POST['login'];
      $password = $_POST['pwd'];
      $identifiant = false;
      $row;
      
      //test si les données sont vides
      if (empty($mail)) {
        array_push($errors, "⚠️ Veuillez saisir votre identifiant ou votre email ⚠️");
      }else{
        //test si le nom ou l'adresse mail existe
        if(isset(User::getUserByEmail($mail)['email'])){
          $row = User::getUserByEmail($mail);
          $identifiant = true;
        }else if(isset(User::getUserByName(strtolower($username))['nom'])){
          $row = User::getUserByName(strtolower($username));
          $identifiant = true;
        }else{
          array_push($errors, "⚠️ Nom d'utilisateur/email et/ou mot de passe non valide ⚠️");
        }

        if($identifiant){
          if (empty($password)) {
            array_push($errors, "⚠️ Veuillez saisir votre mot de passe ⚠️");
          }else{
            $hashed_password = $row->hash;

            //test si le nom d'utilisateur et le mot de passe correspondent
            if(password_verify($password, $hashed_password)) {              
              $_SESSION['user']=$row;
                $_SESSION['username'] = $row->nom;
              $_SESSION['success'] = "Vous êtes connecté";
              array_push($errors, "good");
                $etat = new EtatConnexion;
                return $response->withRedirect('./calculateur');
            }else{
              array_push($errors, "⚠️ Nom d'utilisateur/email et/ou mot de passe non valide ⚠️");
            }
          }
        }
      }
    }
    $etat = new EtatConnexion;
    return $this->view->render($response, 'login.twig', ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername(), 'erreur' => $errors[0]]);
  }
}
