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

function formatWithSpaces(int|float $number): string {
    return number_format($number, 0, '', ' ');
}


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
        <label>Définir le montant de farine : </label>
        <input type="number" name="debug_flour" step="1">
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
if (isset($_POST['debug_flour'])) {
    $_SESSION['flourAmount'] = max(0, (int) $_POST['debug_flour']);
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
if (!isset($_SESSION['flourAmount'])) {
    $_SESSION['flourAmount'] = 300;
}
if (!isset($_SESSION['addedAmount'])) {
    $_SESSION['addedAmount'] = 0;
}
if (!isset($_SESSION['breadPrice'])) {
    $_SESSION['breadPrice'] = 1;
}
/* Prix des g de farine */
if (!isset($_SESSION['flourPrice'])){
    $_SESSION['flourPrice'] = 0.1;
}
if (!isset($_SESSION['100gflourPrice'])){
    $_SESSION['100gflourPrice'] = $_SESSION['flourPrice'] * 100;
}
if (!isset($_SESSION['1KflourPrice'])){
    $_SESSION['1KflourPrice'] = $_SESSION['flourPrice'] * 1000;
}
if (!isset($_SESSION['10KflourPrice'])){
    $_SESSION['10KflourPrice'] = $_SESSION['flourPrice'] * 10000;
}
if (!isset($_SESSION['100KflourPrice'])){
    $_SESSION['100KflourPrice'] = $_SESSION['flourPrice'] * 100000;
}
if (!isset($_SESSION['1TflourPrice'])){
    $_SESSION['1TflourPrice'] = $_SESSION['flourPrice'] * 1000000;
}
/***********************/

if (!isset($_SESSION['lastFlourPriceChangeTime'])) {
    $_SESSION['lastFlourPriceChangeTime'] = time();
}
if (!isset($_SESSION['lastFlourAutoBuyTime'])) {
    $_SESSION['lastFlourAutoBuyTime'] = time();
}

/* variables d'autoclick */
if (!isset($_SESSION['autoClickerCount'])) {
    $_SESSION['autoClickerCount'] = 0;
}
if (!isset($_SESSION['lastAutoClickTime'])) {
    $_SESSION['lastAutoClickTime'] = time();
}
/*********************/
if (!isset($_SESSION['showAugment'])) {
    $_SESSION['showAugment'] = false;
}
if (!isset($_SESSION['showStats'])) {
    $_SESSION['showStats'] = false;
}
if (!isset($_SESSION['showFlour'])) {
    $_SESSION['showFlour'] = false;
}
if (!isset($_SESSION['showHelpPageButton'])) {
    $_SESSION['showHelpPageButton'] = true;
}




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
if (!isset($_SESSION['cost_AutoFlourBuyer1'])){
    $_SESSION['cost_AutoFlourBuyer1'] = 5000;
    $_SESSION['Bought_cost_AutoFlourBuyer1'] = false;
}

if(!isset($_SESSION['min'])){
    $_SESSION['min'] =0.1;
}
if(!isset($_SESSION['max'])){
    $_SESSION['max'] = 0.5;
}
/****************************************************/


/*  Traitement de l'action  */
if (isset($_POST['faire_pain'])) {

    if ( $_SESSION['flourAmount'] > (1 + $_SESSION['addedAmount']) ){  // Vérifie que le nombre de pain produit > au nombre de farine en stock
        $gain = max(1, round((1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'], 0));
        $_SESSION['breadAmount'] += $gain;
        $_SESSION['flourAmount'] -= $gain;
    }
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
    $_SESSION['min'] = 0.3;
    $_SESSION['max'] = 1;
}
if (isset($_POST['Buy_addAmount3']) && $_SESSION['money'] >= $_SESSION['cost_addAmount3']) {
    $_SESSION['money'] -= $_SESSION['cost_addAmount3'];
    $_SESSION['addedAmount'] += 3;
    $_SESSION['Bought_cost_addAmount3'] = true;
    $_SESSION['min'] = 0.6;
    $_SESSION['max'] = 1.5;
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
if (isset($_POST['Buy_AutoFlourBuyer1']) && $_SESSION['money'] >= $_SESSION['cost_AutoFlourBuyer1']) {
    $_SESSION['money'] -= $_SESSION['cost_AutoFlourBuyer1'];
    $_SESSION['Bought_cost_AutoFlourBuyer1'] = true;
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

if (isset($_POST['AugmentPageButton'])){
    if($_SESSION['showAugment'] === true){
        $_SESSION['showAugment'] = false;
    }else{
        $_SESSION['showAugment'] = true;
    }
}
if (isset($_POST['StatsPageButton'])){
    if($_SESSION['showStats'] === true){
        $_SESSION['showStats'] = false;
    }else{
        $_SESSION['showStats'] = true;
    }
}
if (isset($_POST['FlourPageButton'])){
    if($_SESSION['showFlour'] === true){
        $_SESSION['showFlour'] = false;
    }else{
        $_SESSION['showFlour'] = true;
    }
}
if (isset($_POST['HelpPageButton'])){
    if($_SESSION['showHelpPageButton'] === true){
        $_SESSION['showHelpPageButton'] = false;
    }else{
        $_SESSION['showHelpPageButton'] = true;
    }
}

if (isset($_POST['100gFlourBuyingButton'])){
    if($_SESSION['money'] >= $_SESSION['100gflourPrice']){
        $_SESSION['money'] -= $_SESSION['100gflourPrice'];
        $_SESSION['flourAmount'] += 100;
    }
}
if (isset($_POST['1KFlourBuyingButton'])){
    if($_SESSION['money'] >= $_SESSION['1KflourPrice']){
        $_SESSION['money'] -= $_SESSION['1KflourPrice'];
        $_SESSION['flourAmount'] += 1000;
    }
}
if (isset($_POST['10KFlourBuyingButton'])){
    if($_SESSION['money'] >= $_SESSION['10KflourPrice']){
        $_SESSION['money'] -= $_SESSION['10KflourPrice'];
        $_SESSION['flourAmount'] += 10000;
    }
}
if (isset($_POST['100KFlourBuyingButton'])){
    if($_SESSION['money'] >= $_SESSION['100KflourPrice']){
        $_SESSION['money'] -= $_SESSION['100KflourPrice'];
        $_SESSION['flourAmount'] += 100000;
    }
}
if (isset($_POST['1TFlourBuyingButton'])){
    if($_SESSION['money'] >= $_SESSION['1TflourPrice']){
        $_SESSION['money'] -= $_SESSION['1TflourPrice'];
        $_SESSION['flourAmount'] += 1000000;
    }
}

/****************************/



/* Rechargement automatique de la page toutes les 1 seconde */
/* Obligatoire pour les autoclicker sans javascript, pas de refresh en debug mode  */
if (!$debugMode) {
    $webpage->appendToHead("<meta http-equiv='refresh' content='1'>");
}


/* +1 dans les pains pour affichage */
$addedBreadAmount = $_SESSION['addedAmount'] +1 ;


/* Calcul du temps (meta refresh + tick) */
$currentTime = time();
$lastTime = $_SESSION['lastAutoClickTime'];
$elapsedSeconds = $currentTime - $lastTime;

$TimeUntilFlourPriceChange = 0;

if (($currentTime - $_SESSION['lastFlourPriceChangeTime']) >= 3) {
    $random = $_SESSION['min'] + mt_rand() / mt_getrandmax() * ($_SESSION['max'] - $_SESSION['min']);
    $_SESSION['flourPrice'] = round($random, 1);

    $_SESSION['lastFlourPriceChangeTime'] = $currentTime;

    $_SESSION['100gflourPrice'] = round($_SESSION['flourPrice'] * 100, 1);
    $_SESSION['1KflourPrice'] = round($_SESSION['flourPrice'] * 1000, 1);
    $_SESSION['10KflourPrice'] = round($_SESSION['flourPrice'] * 10000, 1);
    $_SESSION['100KflourPrice'] = round($_SESSION['flourPrice'] * 100000, 1);
    $_SESSION['1TflourPrice'] = round($_SESSION['flourPrice'] * 1000000, 1);

}

/* Calcul du temps pour les autoclicker (!) FAIT A L'AIDE D'IA (!) */
if ($elapsedSeconds > 0 && $_SESSION['autoClickerCount'] > 0) {
    $autoclickGain = $elapsedSeconds * $_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']);

    /* Ajout des pains si le montant de farine est >= au gain */
    if ($_SESSION['flourAmount'] >= $autoclickGain) {
        $_SESSION['breadAmount'] += round($autoclickGain * $_SESSION['clickMultiplication']);
        $_SESSION['flourAmount'] -= round($autoclickGain * $_SESSION['clickMultiplication']);
    }
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
$moneyPerSecond = $autoBreadPerSecond * $_SESSION['breadPrice'];


/* Calcul du pain par minutes  */
$breadPerMinute = $_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'] * 60;
$currentStock = $_SESSION['breadAmount'];
$moneyPerMinute= $breadPerMinute * $_SESSION['breadPrice'];

/*Calcul du pain + $ par heures*/
$breadPerHour = $breadPerMinute * 60;
$moneyPerHour = $breadPerHour * $_SESSION['breadPrice'];

/* Calcul des chances de vente (!) FAIT A L'AIDE D'IA (!) */
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

/* Calcul de l'autoFlourBuyer pour la prochaine minutes */
$elapsedSinceLastBuy = $currentTime - $_SESSION['lastFlourAutoBuyTime'];

if (($elapsedSinceLastBuy >= 30 && $_SESSION['flourPrice'] <= 0.8) && $_SESSION['Bought_cost_AutoFlourBuyer1'] === true) {

    // Quantité de farine nécessaire pour 1 minute de production
    $flourNeeded = $_SESSION['autoClickerCount'] * (1 + $_SESSION['addedAmount']) * $_SESSION['clickMultiplication'] * 60;

    // Quantité manquante par rapport au stock actuel
    $missingFlour = max(0, $flourNeeded - $_SESSION['flourAmount']);

    // Coût de la quantité manquante
    $cost = $missingFlour * $_SESSION['flourPrice'];

    // On achète uniquement si on a l'argent
    if ($_SESSION['money'] >= $cost) {
        $_SESSION['money'] -= $cost;
        $_SESSION['flourAmount'] += floor($missingFlour);
    }

    $_SESSION['lastFlourAutoBuyTime'] = $currentTime;
}



/* Formatage de tous les chiffres avant affichages : */
$FlourPrice100g = formatWithSpaces($_SESSION['100gflourPrice']);
$FlourPrice1K = formatWithSpaces($_SESSION['1KflourPrice']);
$FlourPrice10K = formatWithSpaces($_SESSION['10KflourPrice']);
$FlourPrice100K = formatWithSpaces($_SESSION['100KflourPrice']);
$FlourPrice1T = formatWithSpaces($_SESSION['1TflourPrice']);
$EarnedBread = formatWithSpaces($_SESSION['breadAmount']);
$EarnedFlour = formatWithSpaces($_SESSION['flourAmount']);


/* Affichage HTML du jeu (bouton et stats)  */
$webpage->appendContent(<<<HTML
<div class="page">
<div class="FirstPagePart">
HTML);

if($_SESSION['showFlour']){
    $webpage->appendContent(<<<HTML
<div class="FlourPagePart">
    <p class="stats">Prix de 1g de farine : {$_SESSION['flourPrice']}$</p>
    
    <form method="post" class="FlourBuyingButton">
        <button type="submit" name="100gFlourBuyingButton" data-price="{$FlourPrice100g}$">Acheter 100g de farine</button>
    </form>
    <form method="post" class="FlourBuyingButton">
        <button type="submit" name="1KFlourBuyingButton" data-price="{$FlourPrice1K}$">Acheter 1k de farine </button>
    </form>
    <form method="post" class="FlourBuyingButton">
        <button type="submit" name="10KFlourBuyingButton" data-price="{$FlourPrice10K}$">Acheter 10k de farine </button>
    </form>
    <form method="post" class="FlourBuyingButton">
        <button type="submit" name="100KFlourBuyingButton" data-price="{$FlourPrice100K}$">Acheter 100k de farine </button>
    </form>
    <form method="post" class="FlourBuyingButton">
        <button type="submit" name="1TFlourBuyingButton" data-price="{$FlourPrice1T}$">Acheter 1T de farine </button>
    </form>
</div>
HTML);
    if ($_SESSION['Bought_cost_AutoFlourBuyer1'] === true){
        $webpage->appendContent(<<<HTML
    <div class="timer">
        <p>Temps écoulé depuis le dernier achat <br> automatique de farine : {$elapsedSinceLastBuy}s</p> 
    </div>
HTML);
    }

}



if($_SESSION['showHelpPageButton']){
    $webpage->appendContent(<<<HTML
<div class="helpPage">
    <p class="helpPageText">Bienvenue sur la page d'aide !</p>
    <p class="helpPageText"><em><u>La vente </em></u>: Vous avez 100% de chances de vendre avant l'achat d'autoclicker. Ensuite, les chances diminuent, donc pensez à vendre. <br> Si c'est le vendeur qui vend, vous ne bénéficiez pas des bonus de popularité (augmentation du prix unitaire du pain)</p>
    <p class="helpPageText"><em><u>La farine</em></u> : Le prix oscille entre 0,10$ et 0,50$ au départ. Il évolue en fonction des niveaux de fourneau !</p>
    <p class="helpPageText"><em><u>Les améliorations</em></u> : Une amélioration apparaît si vous avez l'argent pour l’acheter et l'argent pour acheter 100 g de farine.</p>
    <p class="helpPageText"><em><u>Les autoclickers</em></u> : Ils cliquent à votre place et prennent en compte tous vos bonus de clic et de multiplicateur.</p>
</div>
HTML);
}


$webpage->appendContent(<<<HTML
</div>
HTML);

$webpage->appendContent(<<<HTML
<div class="MiddlePagePart">

<div class="ButtonPlace">
    <form method="post">
        <button type="submit" name="StatsPageButton">📊</button>
    </form>
    <form method="post" class="MakeBread">
        <button type="submit" name="faire_pain">🥖 Faire un pain </button>
    </form>
    <form method="post">
        <button type="submit" name="FlourPageButton">🌾</button>
    </form>
</div>

<div class="ButtonPlace2">
    <form method="post">
        <button type="submit" name="AugmentPageButton">📌</button>
    </form>
    <form method="post" class="SellBread">
        <button type="submit" name="vendre_pain">Vendre le pain</button>
    </form>
    <form method="post">
        <button type="submit" name="HelpPageButton">💡</button>
    </form>
</div>

<div class="StatsOrder">
    <div class="StatsOrderRight">
        <p class="stats">🥖 Pains en stock : {$EarnedBread}</p> 
        <p class="stats">🌾 Farine en stock : {$EarnedFlour}</p>
        <p class="stats">💸 Argent : {$formattedMoney} $</p>
        <p class="stats">🍀 Chance de vente : {$_SESSION['sellingChance']}%</p> 
    </div>
    <div class="StatsOrderLeft">
        <p class="stats">💸 Prix unitaire du pain : {$_SESSION['breadPrice']}</p>
        <p class="stats">👆🏻 Pains par click : {$addedBreadAmount}</p>
        <p class="stats">🚀 Multiplicateur : x{$_SESSION['clickMultiplication']}</p>
        <p class="stats">🤖 Autoclickers : {$_SESSION['autoClickerCount']}</p>
        </div>
</div>
HTML);

/* Affichage des améliorations */
if(($_SESSION['money'] >= ($_SESSION['cost_addAmount1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_addAmount1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount1" data-price="{$_SESSION['cost_addAmount1']}$">
        🥖 Fourneau lvl 1 ! améliore l’efficacité : +1 pain par click
    </button>
</form> 
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_addAmount2']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_addAmount2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount2" data-price="{$_SESSION['cost_addAmount2']}$">
        🥖 Fourneau lvl 2 ! +1 pain par click (légère augmentation du prix de la farine...)
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_addAmount3']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_addAmount3'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_addAmount3" data-price="{$_SESSION['cost_addAmount3']}$">
        🥖 Fourneau lvl 3 ! +3 pain par click (Encore légère augmentation du prix de la farine...)
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= ($_SESSION['cost_multi1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_multi1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi1" data-price="{$_SESSION['cost_multi1']}$">
        🥖 Marketing lvl 1 : Améliorer le multiplicateur ! (+ x0.1)
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_multi2']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_multi2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi2" data-price="{$_SESSION['cost_multi2']}$">
        🥖 Marketing lvl 2 : (+ x0.1)
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_multi3']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_multi3'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_Multi3" data-price="{$_SESSION['cost_multi3']}$">
        🥖 Marketing lvl 3 : (+ x0.3) 
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= ($_SESSION['cost_AutoClick1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_AutoClick1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoClicker1" data-price="{$_SESSION['cost_AutoClick1']}$">
        🥖 Premier employé, +1 autocliker !
    </button>
</form>
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_AutoClick2']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_AutoClick2'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoClicker2" data-price="{$_SESSION['cost_AutoClick2']}$">
        🥖Nouvel employé (efficace), +3 autocliker !
    </button>
</form>
HTML);
}

if(($_SESSION['money'] >= ($_SESSION['cost_UpPrice1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_UpPrice1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_UpPrice1" data-price="{$_SESSION['cost_UpPrice1']}$">
        🥖 Votre popularité augmente ! Le pain vaut 1.5$
    </button>
</form> 
HTML);
}

if(($_SESSION['money'] >= ($_SESSION['cost_AutoSeller1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_AutoSeller1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoSeller1" data-price="{$_SESSION['cost_AutoSeller1']}$">
        🥖 Un vendeur a la caisse ! Vend tout le pains si la chance de vendre < 50%
    </button>
</form> 
HTML);
}
if(($_SESSION['money'] >= ($_SESSION['cost_AutoFlourBuyer1']) + $_SESSION['100gflourPrice']) and $_SESSION['Bought_cost_AutoFlourBuyer1'] === false) {
    $webpage->appendContent(<<<HTML
<form method="post">
    <button type="submit" name="Buy_AutoFlourBuyer1" data-price="{$_SESSION['cost_AutoFlourBuyer1']}$">
        🥖 Producteur local ! Achete automatiquement de la farine pour la prochaine minutes de production si le prix de la farine est a prix bas ! 
    </button>
</form> 
HTML);
}


/* Fermeture de la divs de milieu de page */
$webpage->appendContent(<<<HTML
</div>
HTML
);

$webpage->appendContent(<<<HTML
<div class="LastPagePart">
HTML);
if($_SESSION['showStats']){

    $webpage->appendContent(<<<HTML
<div class="ShowStats">
    <div class="TimeBread">
        <p class="stats">💵  Pains par seconde : {$autoBreadPerSecond} </p>
        <p class="stats">📈  Pains par minutes : {$breadPerMinute} </p>
        <p class="stats">🏦  Pains par heures :  {$breadPerHour} </p>
    </div>
    <div class="TimeMoney">
        <p class="stats">🌾  $ par seconde : {$moneyPerSecond} </p>
        <p class="stats">🍞  $ par minutes : {$moneyPerMinute} </p>
        <p class="stats">🥖  $ par heures :  {$moneyPerHour} </p>
    </div>
</div>
HTML);
}
if($_SESSION['showAugment']){;

    $webpage->appendContent(<<<HTML
        <div class="ShowTotalAugment">
        <div class="ShowAddAmountAugment"> 
HTML);
    if($_SESSION['Bought_cost_addAmount1'] === true){
        $webpage->appendContent(<<<HTML
            <h3>Fourneau lvl 1 : +1 pain par click  <em>({$_SESSION['cost_addAmount1']}$)</em></h3>  
HTML);
    }
    if($_SESSION['Bought_cost_addAmount2'] === true){
        $webpage->appendContent(<<<HTML
            <h3>Fourneau lvl 2 : +1 pain par click  <em>({$_SESSION['cost_addAmount2']}$)</em></h3>  
HTML);
    }
    if($_SESSION['Bought_cost_addAmount3'] === true){
        $webpage->appendContent(<<<HTML
            <h3>Fourneau lvl 3 : +3 pain par click  <em>({$_SESSION['cost_addAmount3']}$)</em></h3>  
HTML);
    }
    $webpage->appendContent(<<<HTML
        </div> 
HTML);

    $webpage->appendContent(<<<HTML
        <div class="ShowMultiAugment"> 
HTML);
    if($_SESSION['Bought_cost_multi1'] === true){
        $webpage->appendContent(<<<HTML
            <h3> Marketing lvl 1 : (+ x0.1)  <em>({$_SESSION['cost_multi1']}$)</em></h3>  
HTML);
    }
    if($_SESSION['Bought_cost_multi2'] === true){
        $webpage->appendContent(<<<HTML
            <h3> Marketing lvl 2 : (+ x0.1)  <em>({$_SESSION['cost_multi2']}$)</em></h3>  
HTML);
    }
    if($_SESSION['Bought_cost_multi3'] === true){
        $webpage->appendContent(<<<HTML
            <h3> Marketing lvl 3 : (+ x0.3)  <em>({$_SESSION['cost_multi3']}$)</em></h3>  
HTML);
    }
    $webpage->appendContent(<<<HTML
        </div> 
HTML);

    $webpage->appendContent(<<<HTML
        <div class="ShowAutoClickAugment"> 
HTML);
    if($_SESSION['Bought_cost_AutoClick1'] === true){
        $webpage->appendContent(<<<HTML
            <h3> Employé lvl 1 : +1 autoclicker  <em>({$_SESSION['cost_AutoClick1']}$)</em> </h3>  
HTML);
    }
    if($_SESSION['Bought_cost_AutoClick2'] === true){
        $webpage->appendContent(<<<HTML
                <h3> Employé lvl 2 : +3 autoclicker  <em>({$_SESSION['cost_AutoClick2']}$)</em></h3>  
    HTML);
    }
    $webpage->appendContent(<<<HTML
        </div> 
HTML);

    $webpage->appendContent(<<<HTML
        <div class="ShowCostUpAugment"> 
HTML);
    if($_SESSION['Bought_cost_UpPrice1'] === true){
        $webpage->appendContent(<<<HTML
                <h3> Votre popularité augmente ! Le pain vaut 1.5$  <em>({$_SESSION['cost_UpPrice1']}$)</em></h3>  
    HTML);
    }
    $webpage->appendContent(<<<HTML
        </div> 
HTML);

    $webpage->appendContent(<<<HTML
        <div class="ShowAutoSellerAugment"> 
HTML);
    if ($_SESSION['Bought_cost_AutoSeller1'] === true){
        $webpage->appendContent(<<<HTML
                <h3>Un vendeur a la caisse ! Vend les pains si chance de vendre < 50%  <em>({$_SESSION['cost_AutoSeller1']}$)</em></h3>
HTML);
    }
    $webpage->appendContent(<<<HTML
        </div>
HTML);

    $webpage->appendContent(<<<HTML
        <div class="ShowAutoFlourBuyer1Augment"> 
HTML);
    if ($_SESSION['Bought_cost_AutoFlourBuyer1'] === true){
        $webpage->appendContent(<<<HTML
                <h3>Producteur local ! Achete automatiquement de la farine pour la prochaine minutes de production si le prix de la farine est a prix bas !   <em>({$_SESSION['cost_AutoFlourBuyer1']}$)</em></h3>
HTML);
    }
    $webpage->appendContent(<<<HTML
    </div> 
    </div> 
</div>
</div>
HTML);

}

echo $webpage->toHtml();