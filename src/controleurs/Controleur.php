<?php
namespace cetomiam\controleurs;

class Controleur{

  protected $container;

  public function __construct($container){
    $this->container = $container;
  }


  public function __get($property){

    if ($this->container->{$property}) { //si l'attribut $property existe

      return $this->container->{$property}; //alors retourner cet attribut
      
    }

  }

}
