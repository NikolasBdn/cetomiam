<?php

/* page d accueil */
$app->get('/', 'AccueilControleur:index');

/* pages du calculateur  */
$app->get('/calculateur', 'CalculateurControleur:index');
$app->get('/calculateur/{a}', 'CalculateurControleur:rechercheAli');
$app->get('/calculateur/getbyid/{a}', 'CalculateurControleur:getAlimentById');
$app->get('/calculateur/getnutriment/{id}', 'CalculateurControleur:getNutriment');
$app->post('/calculateur/save-recette', 'CalculateurControleur:save');

/* pages recette */
$app->get('/recettes', 'RecetteControleur:index');
$app->get('/recettes/{r}/{c}', 'RecetteControleur:rechercheRecette');
$app->get('/recette/test', 'RecetteControleur:test');
$app->get('/recette/{r}', 'RecetteControleur:getRecette');
$app->post('/recette/{r}/remove-recette', 'RecetteControleur:removeRecette');
$app->get('/recette-type', 'RecetteTypeControleur:index');

/* pages d authentification */
$app->get('/login', 'LoginControleur:index');
$app->post('/login', 'LoginControleur:login');

/* pages d inscription*/
$app->get('/signup', 'SignupControleur:index');
$app->post('/signup', 'SignupControleur:signup');


$app->run();
