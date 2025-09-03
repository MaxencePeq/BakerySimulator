<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Maxence\BakerySimulator\Database\MyPdo;

// Vérifie que l’utilisateur est bien connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Sauvegarde l'id et le username pour les restaurer après
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// On détruit la session courante
$_SESSION = [];
session_unset();

// On recrée une session propre avec les infos d'identité
$_SESSION['user_id'] = $userId;
$_SESSION['username'] = $username;

/* -----------------------------
   Écrase la sauvegarde en BDD
------------------------------*/
$pdo = MyPDO::getInstance();
$stmt = $pdo->prepare("REPLACE INTO save_data (user_id, session_data) VALUES (:id, :session)");
$stmt->execute([
    ':id' => $userId,
    ':session' => json_encode($_SESSION)
]);

/* -----------------------------
   Variables de jeu par défaut
------------------------------*/

// Ressources
$_SESSION['bread'] = 0;
$_SESSION['flour'] = 300;
$_SESSION['money'] = 100;

// Production de base
$_SESSION['breadPerClick'] = 1;
$_SESSION['breadPrice'] = 1.5;  /* Variable changeable en fonction du prestige */

// Automatisation de base
$_SESSION['prestige'] = 1;
$_SESSION['autoClickers'] = 0; // no
$_SESSION['autoSellers'] = 0;
$_SESSION['autoFlourBuyers'] = 0;

// Améliorations (aucune achetée au départ)
$_SESSION['Bought_cost_addAmount1'] = false;
$_SESSION['Bought_cost_addAmount2'] = false;
$_SESSION['Bought_cost_addAmount3'] = false;

$_SESSION['Bought_cost_multi1'] = false;
$_SESSION['Bought_cost_multi2'] = false;
$_SESSION['Bought_cost_multi3'] = false;

$_SESSION['Bought_cost_AutoClick1'] = false;
$_SESSION['Bought_cost_AutoClick2'] = false;

$_SESSION['Bought_cost_UpPrice1'] = false;
$_SESSION['Bought_cost_AutoSeller1'] = false;
$_SESSION['Bought_cost_AutoFlourBuyer1'] = false;

header('Location: basepage.php');
exit;
