<?php

require '../../vendor/autoload.php';
use cetomiam\controleur\CalculateurControleur;
use cetomiam\controleur\RecetteControleur;
use \Illuminate\Database\Capsule\Manager as DB;

/* initialisation de slim,*/
$app = new \Slim\App(['settings' => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'db' => parse_ini_file('../conf/conf.ini')
    ],

]);

/* demarrage de la session */
session_start();

/* si l utilisateur n est pas connecte */
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
}
/* si l utilisateur veut se deconnecter */
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
}


$container = $app->getContainer();

//mise en place de Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);//connection a la bdd avec les parametres entrés plus haut
$capsule->setAsGlobal();
$capsule->bootEloquent();

/* stockage de differentes fonctions pour lier le nom(du callable slim) au controleur */
$container['db'] = function($container) use ($capsule){
  return $capsule;
};

$container['view'] = function($container){
  $view = new \Slim\Views\Twig('../vues', [
    'cache' => false,
  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));
  return $view;
};


$container['AccueilControleur'] = function ($container) {

 return new \cetomiam\controleurs\AccueilControleur($container);

};

$container['CalculateurControleur'] = function ($container) {

 return new \cetomiam\controleurs\CalculateurControleur($container);

};

$container['RecetteControleur'] = function ($container) {

 return new \cetomiam\controleurs\RecetteControleur($container);

};

 $container['RecetteTypeControleur'] = function ($container) {

 return new \cetomiam\controleurs\RecetteTypeControleur($container);

};

$container['LoginControleur'] = function ($container) {

 return new \cetomiam\controleurs\LoginControleur($container);
};

 $container['SignupControleur'] = function ($container) {

 return new \cetomiam\controleurs\SignupControleur($container);

};

 /* require des routes slim */
require __DIR__  . '/../public/routes.php';