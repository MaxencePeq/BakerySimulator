<?php
declare(strict_types=1);

/* Debut de la session */
session_start();

use Maxence\BakerySimulator\HTML\webpage;

$webpage = new webpage();
$webpage->setTitle("Ma Boulangerie");
$webpage->appendCssUrl('http://localhost:8000/css/globalstyle.css');



/* Initialisation des variables si première visite  */
if (!isset($_SESSION['breadAmount'])) {
    $_SESSION['breadAmount'] = 0;
}
if (!isset($_SESSION['clickMultiplication'])) {
    $_SESSION['clickMultiplication'] = 1;
}
if (!isset($_SESSION['money'])) {
    $_SESSION['money'] = 0;
}
if (!isset($_SESSION['addedAmount'])) {
    $_SESSION['addedAmount'] = 0;
}
/****************************************************/


/*  Traitement de l'action  */
if (isset($_POST['faire_pain'])) {
    $_SESSION['breadAmount'] += ( (1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication']);
}

if (isset($_POST['vendre_pain'])) {
    $_SESSION['money'] += $_SESSION['breadAmount'];
    $_SESSION['breadAmount'] = 0;
}

if (isset($_POST['Acheter_Augment'])) {
    $_SESSION['money'] -= 300;
    $_SESSION['addedAmount'] += 1;
}

/****************************/



/* Rechargement automatique de la page toutes les 1 seconde */
    /* Obligatoire pour les autoclicker sans javascript */
$webpage->appendToHead('<meta http-equiv="refresh" content="1">');



/* Affichage HTML  */
$webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="faire_pain">Faire un pain 🥖</button>
</form>
<form method="post">
    <button type="submit" name="vendre_pain">Vendre le pain </button>
</form>

<p>Pains en stock : {$_SESSION['breadAmount']}</p>
<p>Argent : {$_SESSION['money']} $</p>
HTML);

if($_SESSION['money'] > 100) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Acheter_Augment">Ameliorer l'efficacité (300$) </button>
</form>
HTML);
}

echo $webpage->toHtml();
