<?php
namespace cetomiam\modeles;

use cetomiam\modeles\User;

//Liaison entre la base de données et le serveur web pour la gestion de la connection


class EtatConnexion {

  private $etatConnexion;

  //Vérification de l'existence d'une session
  public static function getConnexion(){
    if (isset($_SESSION['username'])){
      $etatConnexion = 'Déconnexion';
    }else{
      $etatConnexion = 'Connexion';
    }
    return $etatConnexion;
  }

  //Récupération de nom d'utilisateur à partir de la base de données User
  public static function getUsername(){
    if (isset($_SESSION['username'])){
      $username = '('.ucfirst(strtolower($_SESSION['username'])).')';
    }else{
      $username = '';
    }
    return $username;
  }

  //Récupération du ratio de l'utilisateur à partir de la base de données User
  public static function getRatio(){
    if (isset($_SESSION['username'])){
      $ratio = User::getUserByName($_SESSION['username'])['ratioUser'];
      if(strpos($ratio, ".") == false){
         $ratio = $ratio.".0";
      }
    }else{
      $ratio = "0.0";      
    }
    return $ratio;
  }

  //Récupération de l'email de l'utilisateur à partir de la base de données User
  public static function getMail(){
    if (isset($_SESSION['username'])){
      $email = User::getUserByName($_SESSION['username'])['email'];
    }else{
      $email = '';
    }
    return $email;
  }  
}
