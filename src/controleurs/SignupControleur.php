<?php
namespace cetomiam\controleurs;
use Slim\Views\Twig as View;
use cetomiam\modeles\Aliment;
use cetomiam\modeles\Recette;
use cetomiam\modeles\User;
use cetomiam\modeles\EtatConnexion;

/*
  Controleur de la page d'inscription
*/
class SignupControleur extends Controleur{

  /*
    Fonction d'affichage de la page d'inscription d'un utilisateur
    Redirige vers la page du calculateur s'il est déjà connecté
  */
  public function index($resquest, $response){
      if(!isset($_SESSION['username'])){
      $etat = new EtatConnexion;
      return $this->view->render($response, 'signup.twig', ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername()]);
    }
    else{
      return $response->withRedirect('./calculateur');
    }
  }

  /*
    Fonction d'inscription d'un utilisateur
  */
  public function signup($resquest, $response){
    $errors = array();     

    if (isset($_POST['signup'])) {

      //récupération des données du formulaire
      $username = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
      $password1 = filter_var($_POST['pwd1'], FILTER_SANITIZE_STRING);
      $password2 = filter_var($_POST['pwd2'], FILTER_SANITIZE_STRING);
      $ratio = $_POST['ratio'];

      //affichage d'erreur si champ vide
      if (empty($username)) { array_push($errors, "⚠️ Veuillez saisir un nom d'utilisateur ⚠️"); }
      if (empty($email)) { array_push($errors, "⚠️ Veuillez saisir une adresse mail ⚠️"); }
      if (empty($password1)) { array_push($errors, "⚠️ Veuillez saisir un mot de passe ⚠️"); }
      if (empty($password2)) { array_push($errors, "⚠️ Veuillez confirmer votre mot de passe ⚠️"); }
      if ($password1 != $password2) {
        array_push($errors, "⚠️ Les mots de passe ne correspondent pas ⚠️");
      }else if(strlen($password1) < 6){
      	array_push($errors, "⚠️ Votre mot de passe doit contenir au minimum 6 caractères ⚠️");
      }else{
        //test utilisateur ou email existant dans bdd
        if(User::getUserByName(strtolower($username)) != null){
          array_push($errors, "⚠️ Nom d'utilisateur déjà existant, veuillez en choisir un autre ⚠️");
        }else{
          if(User::getUserByEmail(strtolower($email)) != null){
            array_push($errors, "⚠️ Adresse email déjà existante, veuillez en choisir une autre ⚠️");
          }else{
            if($ratio == ""){
              array_push($errors, "⚠️ Veuillez entrer votre ratio ⚠️");
            }else{
              $ratio = str_replace(',', '.', $ratio);

              //création d'un nouvel utilisateur et ajout dans la bdd
              $u = new User();
              $u->nom = strtolower($username);
              $u->email = strtolower($email);
              $u->ratioUser = $ratio;
              $u->hash = password_hash($password1, PASSWORD_DEFAULT);
              $u->save();

              $_SESSION['user']=$u;
              $_SESSION['username'] = $username;
              $_SESSION['success'] = "You are now logged in";
              array_push($errors, "good");
              $etat = new EtatConnexion;
              return $response->withRedirect('./calculateur');
            }
            
          }
        }
      }  
    }
    $etat = new EtatConnexion;
    return $this->view->render($response, 'signup.twig', ['etatConnexion' => $etat->getConnexion(), 'userConnecte' => $etat->getUsername(), 'erreur' => $errors[0], 'post'=>$_POST]);
  }
}
