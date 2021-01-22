<?php
header( 'content-type: text/html; charset=utf-8' );
// Connexion et sélection de la base
// Create connection
$conn = new mysqli("localhost", "root", "am7h88", "cetofacile");
if ($conn->connect_error) {
    die("Connection echoué: " . $conn->connect_error);
}

/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    echo "Échec de la connexion " . mysqli_connect_error();
    exit();
}

echo 'Jeu de caractère initial'. $conn->character_set_name();

/* Modification du jeu de résultats en utf8 */
if (!$conn->set_charset("utf8")) {
    echo 'Erreur lors du chargement du jeu de caractères utf8' . $conn->error;
    exit();
} else {
    echo 'Jeu de caractères courant'.$conn->character_set_name();
}


$fichierAliments="donnees/aliments.xml";
$fichierGroupes="donnees/aliments_grp.xml";
$fichierComposition="donnees/aliments_compo.xml";
$fichierNutriments="donnees/nutriments.xml";

$alimentsXml = simplexml_load_file($fichierAliments);
$groupesXml = simplexml_load_file($fichierGroupes);
$compoXml = simplexml_load_file($fichierComposition);
$nutrimentsXMl = simplexml_load_file($fichierNutriments);

$content="";
$i=0;
$e=0;
  foreach ($alimentsXml as $aliment)
  {

    $content="";
    $content.="[".$aliment->alim_code."] ".$aliment->alim_nom_fr."<br>";
    $alimNom=trim($aliment->alim_nom_fr);

    foreach ($compoXml as $composant) {
      if ($composant->alim_code==intval($aliment->alim_code)) {

        foreach ($nutrimentsXMl as $nutriment) {

          if ($nutriment->const_code==intval($composant->const_code)){

            if ($nutriment->const_code==31000) {
              $alimGluc=trim(floatval(str_replace('-','0',str_replace('<','',str_replace(',','.',$composant->teneur)))));
            }elseif ($nutriment->const_code==40000) {
              $alimLipi=trim(floatval(str_replace('-','0',str_replace('<','',str_replace(',','.',$composant->teneur)))));
            }elseif ($nutriment->const_code==25000) {
              $alimProt=trim(floatval(str_replace('-','0',str_replace('<','',str_replace(',','.',$composant->teneur)))));
            }elseif($nutriment->const_code==328) {
              $alimKcal=trim(str_replace('-','0',str_replace('<','',str_replace(',','.',$composant->teneur))));
            }

          }

        }
      }
    }
    $sql = "insert into aliment (nomAli, kcal, lipideAli, glucideAli, proteineAli)
    values ('$alimNom',$alimKcal,$alimLipi,$alimGluc,$alimProt);";
    echo "\ninsert : ".$sql;
    echo $conn->query($sql);
    // if ($conn->query($sql) == TRUE) {
    //     echo "New record created successfully<br>";
    // } else {
    //     echo "Error: " . $sql.$conn->error.'<br>';
    //     // echo $e.'\n';
    //     $e++;
    // }

    echo $alimGluc;
     echo $alimLipi;
    echo $alimProt;
    $i++;
  }


  echo "IL Y A $e ERREURS !";
