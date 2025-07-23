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
if (!isset($_SESSION['sellingChance'])) {
    $_SESSION['sellingChance'] = 100;
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
/* variables d'autoclick */
if (!isset($_SESSION['autoClickerCount'])) {
    $_SESSION['autoClickerCount'] = 0;
}
if (!isset($_SESSION['lastAutoClickTime'])) {
    $_SESSION['lastAutoClickTime'] = time();
}
/*********************/


/* Initialisation des couts */
if (!isset($_SESSION['cost_addAmount1'])) {
    $_SESSION['cost_addAmount1'] = 75;
    $_SESSION['Bought_cost_addAmount1'] = false;
}
if (!isset($_SESSION['cost_addAmount2'])) {
    $_SESSION['cost_addAmount2'] = 500;
    $_SESSION['Bought_cost_addAmount2'] = false;
}
if (!isset($_SESSION['cost_addAmount3'])) {
    $_SESSION['cost_addAmount3'] = 1250;
    $_SESSION['Bought_cost_addAmount3'] = false;
}
if (!isset($_SESSION['cost_multi1'])) {
    $_SESSION['cost_multi1'] = 1000;
    $_SESSION['Bought_cost_multi1'] = false;
}
if (!isset($_SESSION['cost_multi2'])) {
    $_SESSION['cost_multi2'] = 1500;
    $_SESSION['Bought_cost_multi2'] = false;
}
if (!isset($_SESSION['cost_multi3'])) {
    $_SESSION['cost_multi3'] = 2000;
    $_SESSION['Bought_cost_multi3'] = false;
}
if (!isset($_SESSION['cost_AutoClick1'])) {
    $_SESSION['cost_AutoClick1'] = 125;
    $_SESSION['Bought_cost_AutoClick1'] = false;
}
if (!isset($_SESSION['cost_AutoClick2'])) {
    $_SESSION['cost_AutoClick2'] = 200;
    $_SESSION['Bought_cost_AutoClick2'] = false;
}
if (!isset($_SESSION['cost_UpPrice1'])) {
    $_SESSION['cost_UpPrice1'] = 2000;
    $_SESSION['Bought_cost_UpPrice1'] = false;
}
if (!isset($_SESSION['cost_AutoSeller1'])) {
    $_SESSION['cost_AutoSeller1'] = 5000;
    $_SESSION['Bought_cost_AutoSeller1'] = false;
}
/****************************************************/


/*  Traitement de l'action  */
if (isset($_POST['faire_pain'])) {
    $gain = max(1, round((1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'], 0));
    $_SESSION['breadAmount'] += $gain;
}

if (isset($_POST['vendre_pain'])) {
    $roll = mt_rand(1, 100);
    if ($roll <= $_SESSION['sellingChance']) {
        $gain = round($_SESSION['breadAmount'] * $_SESSION['breadPrice']);
        $_SESSION['money'] += $gain;
        $_SESSION['breadAmount'] = 0;
    }
}
if (isset($_POST['Buy_addAmount1']) && $_SESSION['money'] >= $_SESSION['cost_addAmount1']) {
    $_SESSION['money'] -= $_SESSION['cost_addAmount1'];
    $_SESSION['addedAmount'] += 1;
    $_SESSION['Bought_cost_addAmount1'] = true;
}
if (isset($_POST['Buy_addAmount2']) && $_SESSION['money'] >= $_SESSION['cost_addAmount2']) {
    $_SESSION['money'] -= $_SESSION['cost_addAmount2'];
    $_SESSION['addedAmount'] += 1;
    $_SESSION['Bought_cost_addAmount2'] = true;
}
if (isset($_POST['Buy_addAmount3']) && $_SESSION['money'] >= $_SESSION['cost_addAmount3']) {
    $_SESSION['money'] -= $_SESSION['cost_addAmount3'];
    $_SESSION['addedAmount'] += 3;
    $_SESSION['Bought_cost_addAmount3'] = true;
}

if (isset($_POST['Buy_Multi1']) && $_SESSION['money'] >= $_SESSION['cost_multi1']) {
    $_SESSION['money'] -= $_SESSION['cost_multi1'];
    $_SESSION['clickMultiplication'] += 0.1;
    $_SESSION['Bought_cost_multi1'] = true;
}
if (isset($_POST['Buy_Multi2']) && $_SESSION['money'] >= $_SESSION['cost_multi2']) {
    $_SESSION['money'] -= $_SESSION['cost_multi2'];
    $_SESSION['clickMultiplication'] += 0.1;
    $_SESSION['Bought_cost_multi2'] = true;
}
if (isset($_POST['Buy_Multi3']) && $_SESSION['money'] >= $_SESSION['cost_multi3']) {
    $_SESSION['money'] -= $_SESSION['cost_multi3'];
    $_SESSION['clickMultiplication'] += 0.3;
    $_SESSION['Bought_cost_multi3'] = true;
}

if (isset($_POST['Buy_AutoClicker1']) && $_SESSION['money'] >= $_SESSION['cost_AutoClick1']) {
    $_SESSION['money'] -= $_SESSION['cost_AutoClick1'];
    $_SESSION['autoClickerCount'] += 1;
    $_SESSION['Bought_cost_AutoClick1'] = true;
}
if (isset($_POST['Buy_AutoClicker2']) && $_SESSION['money'] >= $_SESSION['cost_AutoClick2']) {
    $_SESSION['money'] -= $_SESSION['cost_AutoClick2'];
    $_SESSION['autoClickerCount'] += 3;
    $_SESSION['Bought_cost_AutoClick2'] = true;
}

if (isset($_POST['Buy_UpPrice1']) && $_SESSION['money'] >= $_SESSION['cost_UpPrice1']) {
    $_SESSION['money'] -= $_SESSION['cost_UpPrice1'];
    $_SESSION['breadPrice'] += 0.5;
    $_SESSION['Bought_cost_UpPrice1'] = true;
}

if (isset($_POST['Buy_AutoSeller1']) && $_SESSION['money'] >= $_SESSION['cost_AutoSeller1']) {
    $_SESSION['money'] -= $_SESSION['cost_AutoSeller1'];
    $_SESSION['Bought_cost_AutoSeller1'] = true;
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


/****************************/



/* Rechargement automatique de la page toutes les 1 seconde */
/* Obligatoire pour les autoclicker sans javascript, pas de refresh en debug mode  */
if (!$debugMode) {
    $webpage->appendToHead("<meta http-equiv='refresh' content='1'>");
}


/* +1 dans les pains pour affichage */
$addedBreadAmount = $_SESSION['addedAmount'] +1 ;


/* Calcul du temps pour les autoclicker (!) FAIT A L'AIDE D'IA (!) */
$currentTime = time();
$lastTime = $_SESSION['lastAutoClickTime'];
$elapsedSeconds = $currentTime - $lastTime;

if ($elapsedSeconds > 0 && $_SESSION['autoClickerCount'] > 0) {
    $autoclickGain = $elapsedSeconds * $_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']);
    $_SESSION['breadAmount'] += round($autoclickGain * $_SESSION['clickMultiplication']);
}
$_SESSION['lastAutoClickTime'] = $currentTime;
/***************************************/



/* On force le passage en entier pour assurer une valeur non flottante */
$_SESSION['money'] = (int) round($_SESSION['money']);
$_SESSION['breadAmount'] = (int) round($_SESSION['breadAmount']);
/**********************************************************************/



/* Money espacé de virgules initalisé avant affichage */
$formattedMoney = number_format($_SESSION['money'], 0, ',', ' ');
/******************************************************/



/* Calcul statique de combien de pain par seconde sont fait */
$autoBreadPerSecond = round($_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'], 0);



/* Calcul du pain par minutes (!) FAIT A L'AIDE D'IA (!)  */
$breadPerMinute = $_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'] * 60;
$currentStock = $_SESSION['breadAmount'];

if ($breadPerMinute == 0) {
    $_SESSION['sellingChance'] = 100; // Aucun autoclicker = 100%
} else {
    $ratio = $currentStock / $breadPerMinute;

    if ($ratio <= 0.5) {
        $_SESSION['sellingChance'] = 100;
    } elseif ($ratio <= 1) {
        // Entre 50% et 100% de stock : descend vers 50%
        $chance = 100 - (($ratio - 0.5) / 0.5) * 50;
        $_SESSION['sellingChance'] = round($chance);
    } else {
        // Stock > production/minute : chance diminue vers 1%
        $excessRatio = min(($ratio - 1) / 1, 1);
        $chance = 50 - $excessRatio * 49; // 50% -> 1%
        $_SESSION['sellingChance'] = max(1, round($chance));
    }
}
/**********************************/



/* Autoseller fait ici, car besoins de la $_SESSION['sellingChance']  */
if ($_SESSION['Bought_cost_AutoSeller1'] === true){
    if($_SESSION['sellingChance'] <= 50) {
        $gain = round($_SESSION['breadAmount'] * $_SESSION['breadPrice']);
        $_SESSION['money'] += $gain;
        $_SESSION['breadAmount'] = 0;
    }
}
/*********************************************************************/


/* Affichage HTML du jeu (bouton et stats)  */
$webpage->appendContent(<<<HTML
<div class="ButtonPlace">
    <button type="submit" name="AugmentPageButton">📌</button>
    <form method="post" class="MakeBread">
    <button type="submit" name="faire_pain">🥖 Faire un pain </button>
</form>
</div>

<form method="post" class="SellBread">
    <button type="submit" name="vendre_pain">Vendre le pain</button>
</form>

<div class="StatsOrder">
    <p class="stats">🥖 Pains en stock : {$_SESSION['breadAmount']}</p> 
    <p class="stats">💸 Prix unitaire du pain : {$_SESSION['breadPrice']}</p>
    <p class="stats">🚀 Chance de vente : {$_SESSION['sellingChance']}%</p> 
    <p class="stats">👆🏻 Pains par click : {$addedBreadAmount}</p>
    <p class="stats">🥖/💸  Pains par seconde : {$autoBreadPerSecond} </p>
    <p class="stats">🚀 Multiplicateur : x{$_SESSION['clickMultiplication']}</p>
    <p class="stats">🤖 Autoclickers : {$_SESSION['autoClickerCount']}</p>
    <p class="stats">💸 Argent : {$formattedMoney} $</p>
</div>
HTML);



/* Affichage des améliorations */

if(($_SESSION['money'] >= $_SESSION['cost_addAmount1']) and $_SESSION['Bought_cost_addAmount1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount1" data-price="{$_SESSION['cost_addAmount1']}$">
        🥖 Nouveau fourneau, améliore l’efficacité : +1 pain par click
    </button>
</form> 
HTML);
}

if(($_SESSION['money'] >= $_SESSION['cost_addAmount2']) and $_SESSION['Bought_cost_addAmount2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount2" data-price="{$_SESSION['cost_addAmount2']}$">
        🥖 Améliorer encore l’efficacité ! +1 pain par click 
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= $_SESSION['cost_addAmount3']) and $_SESSION['Bought_cost_addAmount3'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount3" data-price="{$_SESSION['cost_addAmount3']}$">
        🥖 Améliore ENCORE l’efficacité ! +1 pain par click 
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= $_SESSION['cost_multi1']) and $_SESSION['Bought_cost_multi1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi1" data-price="{$_SESSION['cost_multi1']}$">
        🥖 Améliorer le multiplicateur !
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= $_SESSION['cost_multi2']) and $_SESSION['Bought_cost_multi2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi2" data-price="{$_SESSION['cost_multi2']}$">
        🥖 Améliorer encore le multiplicateur !
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= $_SESSION['cost_multi3']) and $_SESSION['Bought_cost_multi3'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi3" data-price="{$_SESSION['cost_multi3']}$">
        🥖 Améliore ENCORE le multiplicateur ! + 0.3 ! 
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= $_SESSION['cost_AutoClick1']) and $_SESSION['Bought_cost_AutoClick1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoClicker1" data-price="{$_SESSION['cost_AutoClick1']}$">
        🥖 Premier employé, +1 autocliker !
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= $_SESSION['cost_AutoClick2']) and $_SESSION['Bought_cost_AutoClick2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoClicker2" data-price="{$_SESSION['cost_AutoClick2']}$">
        🥖Nouvel employé (efficace), +3 autocliker !
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= $_SESSION['cost_UpPrice1']) and $_SESSION['Bought_cost_UpPrice1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_UpPrice1" data-price="{$_SESSION['cost_UpPrice1']}$">
        🥖 Votre popularité augmente ! Le pain vaut 1.5$
    </button>
</form> 
HTML);
}

if(($_SESSION['money'] >= $_SESSION['cost_AutoSeller1']) and $_SESSION['Bought_cost_AutoSeller1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoSeller1" data-price="{$_SESSION['cost_AutoSeller1']}$">
        🥖 Un vendeur a la caisse ! Vend tout le pains si la chance de vendre < 50%
    </button>
</form> 
HTML);
}

echo $webpage->toHtml();
