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

/*  Traitement de l'action  */
if (isset($_POST['faire_pain'])) {
    $_SESSION['breadAmount'] += $_SESSION['clickMultiplication'];
}

/* Rechargement automatique de la page toutes les 1 seconde */
    /* Obligatoire pour les autoclicker sans javascript */
$webpage->appendToHead('<meta http-equiv="refresh" content="1">');

/* Affichage HTML  */
$webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="faire_pain">Faire un pain 🥖</button>
</form>

<p>Pains en stock : {$_SESSION['breadAmount']}</p>
HTML);

echo $webpage->toHtml();
