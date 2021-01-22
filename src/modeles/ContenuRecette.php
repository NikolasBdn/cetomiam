<?php
namespace cetomiam\modeles;

use Illuminate\Database\Eloquent\Model;

//Liaison entre la base de données et le serveur web pour la table ContenuRecette
//désactive les colonnes created_at et updated_at 

class ContenuRecette extends Model{

  protected $table = 'contenuRec';
  // protected $primaryKey = 'idRec';
  public $timestamps=false;
}
