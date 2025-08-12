<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Maxence\BakerySimulator\Database\MyPdo;


if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

// Cloner la session sans les données de session liées à l'identité
$sessionToSave = $_SESSION;
unset($sessionToSave['user_id'], $sessionToSave['username']);

$pdo = MyPDO::getInstance();
$stmt = $pdo->prepare("REPLACE INTO save_data (user_id, session_data) VALUES (:id, :session)");
$stmt->execute([
    ':id' => $_SESSION['user_id'],
    ':session' => json_encode($sessionToSave)
]);

echo "Session sauvegardée.";
