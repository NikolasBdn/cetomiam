<?php
namespace cetomiam\modeles;

use Illuminate\Database\Eloquent\Model;

//Liaison entre la base de données et le serveur web pour la table Aliment
//Clé primaire --> idAli (id de l'aliment)
//désactive les colonnes created_at et updated_at 

class Aliment extends Model{

  protected $table = 'aliment';
  protected $primaryKey = 'idAli';
  public $timestamps=false;
}
