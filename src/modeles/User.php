<?php
namespace cetomiam\modeles;

use Illuminate\Database\Eloquent\Model;

//Liaison entre la base de données et le serveur web pour la table User
//Clé primaire --> iduser (id de l'utilisateur)
//désactive les colonnes created_at et updated_at 

class User extends Model{

  protected $table = 'user';
  protected $primaryKey = 'idUser';
  public $timestamps=false;

  public static function getUserByEmail($email){
      //filtre l email pour eviter les modif bdd
      $mail=filter_var($email, FILTER_SANITIZE_EMAIL);
      return User::where("email", "=", $mail)->first();
  }
  
  public static function getUserByName($name){
      //retourne le nom d'utilisateur pour eviter les modif bdd
      $pseudo=filter_var($name, FILTER_SANITIZE_STRING);
      return User::where("nom", "=", $pseudo)->first();
  }
  
}
