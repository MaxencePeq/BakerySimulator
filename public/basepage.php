<?php
declare(strict_types=1);

/* Debut de la session */
session_start();

use Maxence\BakerySimulator\HTML\webpage;

$webpage = new webpage();
$webpage->setTitle("Ma Boulangerie");
$webpage->appendCssUrl('http://localhost:8000/css/globalstyle.css');

/* Lancement du debug mode */
$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';

if ($debugMode) {
    $webpage->appendContent(<<<HTML
    <h2>🔧 Debug Mode</h2>
    <form method="post">
        <label>Définir l'argent : </label>
        <input type="number" name="debug_money" step="1">
        <button type="submit">Set 💰</button>
    </form>

    <form method="post">
        <label>Définir les pains : </label>
        <input type="number" name="debug_bread" step="1">
        <button type="submit">Set 🥖</button>
    </form>

    <form method="post">
        <label>Définir le multiplicateur : </label>
        <input type="number" name="debug_multi" step="0.1">
        <button type="submit">Set ✖️</button>
    </form>

    <form method="post">
        <label>Définir le bonus par clic : </label>
        <input type="number" name="debug_bonus" step="1">
        <button type="submit">Set ➕</button>
    </form>
    <form method="post">
        <button type="submit" name="reset_game">🔄 Réinitialiser la partie</button>
    </form>
    <form method="post">
        <button type="submit" name="ExitDebug"> Quitter le mode debug</button>
    </form>
    <hr>
HTML);
}
/***************************/


/* Traitement debug mode */
// Sécuriser les entrées debug
if (isset($_POST['debug_money'])) {
    $_SESSION['money'] = max(0, (int) $_POST['debug_money']);
}
if (isset($_POST['debug_bread'])) {
    $_SESSION['breadAmount'] = max(0, (int) $_POST['debug_bread']);
}
if (isset($_POST['debug_multi'])) {
    $_SESSION['clickMultiplication'] = max(1, (float) $_POST['debug_multi']); // min 1
}
if (isset($_POST['debug_bonus'])) {
    $_SESSION['addedAmount'] = max(0, (int) $_POST['debug_bonus']);
}



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
if (!isset($_SESSION['breadPrice'])) {
    $_SESSION['breadPrice'] = 1;
}

if (!isset($_SESSION['cost_addAmount'])) {
    $_SESSION['cost_addAmount'] = 150;
}
if (!isset($_SESSION['cost_multi'])) {
    $_SESSION['cost_multi'] = 500;
}

/****************************************************/


/*  Traitement de l'action  */
if (isset($_POST['faire_pain'])) {
    $gain = max(1, round((1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'], 0));
    $_SESSION['breadAmount'] += $gain;

}

if (isset($_POST['vendre_pain'])) {
    $_SESSION['money'] += ($_SESSION['breadAmount'] * $_SESSION['breadPrice']);
    $_SESSION['breadAmount'] = 0;
}

if (isset($_POST['Buy_addAmount']) && $_SESSION['money'] >= $_SESSION['cost_addAmount']) {
    $_SESSION['money'] -= $_SESSION['cost_addAmount'];
    $_SESSION['addedAmount'] += 1;
    $_SESSION['cost_addAmount'] = ceil($_SESSION['cost_addAmount'] * 1.15);
}


if (isset($_POST['Buy_Multi']) && $_SESSION['money'] >= $_SESSION['cost_multi']) {
    $_SESSION['money'] -= $_SESSION['cost_multi'];
    $_SESSION['clickMultiplication'] += 0.1;
    $_SESSION['cost_multi'] = ceil($_SESSION['cost_multi'] * 1.15);
}


if (isset($_POST['reset_game'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Recharge la page sans les paramètres
    exit;
}
if (isset($_POST['ExitDebug'])) {
    header("Location: http://localhost:8000/basepage.php");
    exit;
}

if (isset($_POST['Buy_AutoClicker'])) {
 /* a faire */
}

/****************************/



/* Rechargement automatique de la page toutes les 1 seconde */
/* Obligatoire pour les autoclicker sans javascript, 300 sec en debug mode */
$refreshTime = $debugMode ? 300 : 2;
$webpage->appendToHead("<meta http-equiv='refresh' content='{$refreshTime}'>");


/* +1 dans les pains pour affichage */
$addedBreadAmount = $_SESSION['addedAmount'] +1 ;





/* Affichage HTML  */
$webpage->appendContent(<<<HTML
<form method="post" class="MakeBread">
    <button type="submit" name="faire_pain">🥖 Faire un pain </button>
</form>

<form method="post" class="SellBread">
    <button type="submit" name="vendre_pain">Vendre le pain</button>
</form>


<p class="stats">🥖 Pains en stock : {$_SESSION['breadAmount']}</p> 
<p class="stats">💸 Prix unitaire du pain : {$_SESSION['breadPrice']}</p>
<p class="stats">👆🏻 Pains par click : {$addedBreadAmount}</p>
<p class="stats">🚀 Multiplicateur : x{$_SESSION['clickMultiplication']}</p>
<p class="stats">💸 Argent : {$_SESSION['money']} $</p>
HTML);


/* Affichage des premières améliorations */
if($_SESSION['money'] >= $_SESSION['cost_addAmount']) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount" data-price="{$_SESSION['cost_addAmount']}G">
        🥖 Améliorer l’efficacité
    </button>
</form>
HTML);
}


if($_SESSION['money'] >= $_SESSION['cost_multi']) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi" data-price="{$_SESSION['cost_multi']}G">
        🥖 Améliorer le multiplicateur
    </button>
</form>
HTML);
}
/******************************/

/* Fin du début de jeu, mise en place de la partie IDLE */

if($_SESSION['money'] >= $_SESSION['cost_multi']) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoClicker" data-price="???">
        🥖 +1 pain par seconde !
    </button>
</form>
HTML);
}

echo $webpage->toHtml();
