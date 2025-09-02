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

// Redirection vers la partie
header('Location: index.php');
exit;
