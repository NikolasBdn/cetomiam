<?php
namespace cetomiam\modeles;

use Illuminate\Database\Eloquent\Model;

//Liaison entre la base de données et le serveur web pour la table Recette
//Clé primaire --> idRec (id de la recette)
//désactive les colonnes created_at et updated_at 

class Recette extends Model{

  protected $table = 'recette';
  protected $primaryKey = 'idRec';
  public $timestamps=false;

}
