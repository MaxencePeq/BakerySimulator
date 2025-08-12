<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Maxence\BakerySimulator\Database\MyPdo;

if (isset($_SESSION['user_id'])) {
    header('Location: basepage.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $pdo = MyPdo::getInstance();
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Identifiants incorrects.";
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = "Identifiants incorrects.";
        } else {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // --- Restaurer session sauvegardée ---
            $stmt = $pdo->prepare("SELECT session_data FROM save_data WHERE user_id = :id");
            $stmt->execute([':id' => $user['id']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['session_data'])) {
                $savedSession = json_decode($row['session_data'], true);
                if (is_array($savedSession)) {
                    foreach ($savedSession as $key => $value) {
                        if ($key !== 'user_id' && $key !== 'username') {
                            $_SESSION[$key] = $value;
                        }
                    }
                }
            }

            header('Location: basepage.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/globalstyle.css">
</head>
<body>
<h1>Connexion à la boulangerie</h1>
<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
    <label>Nom d'utilisateur : <input type="text" name="username" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <button type="submit">Se connecter</button>
</form>
<p><a href="register.php">Créer un compte</a></p>
</body>
</html>
