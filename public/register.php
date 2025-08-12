<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Maxence\BakerySimulator\Database\MyPdo;

if (isset($_SESSION['user_id'])) {
    header('Location: http://localhost:8888/BakerySimulator/public/basepage.php'); // Déjà connecté
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username && $password && $confirm) {
        if ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas.";
        } elseif (strlen($password) < 6) {
            $error = "Le mot de passe doit faire au moins 6 caractères.";
        } else {
            var_dump(getenv('APP_DIR'));
            var_dump(realpath('.'));

            $pdo = MyPDO::getInstance();
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);

            if ($stmt->fetch()) {
                $error = "Ce nom d'utilisateur est déjà pris.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password)");
                $stmt->execute([':username' => $username, ':password' => $hash]);

                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            }
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/globalstyle.css">
</head>
<body>
<h1>Créer un compte</h1>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
    <p><a href="login.php">Se connecter</a></p>
<?php else: ?>
    <form method="post">
        <label>Nom d'utilisateur : <input type="text" name="username" required></label><br>
        <label>Mot de passe : <input type="password" name="password" required></label><br>
        <label>Confirmer le mot de passe : <input type="password" name="confirm_password" required></label><br>
        <button type="submit">Créer mon compte</button>
    </form>
    <p><a href="login.php">Déjà inscrit ? Connexion</a></p>
<?php endif; ?>
</body>
</html>
