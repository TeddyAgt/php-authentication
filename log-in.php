<?php
require_once __DIR__ . "/databse/db_access.php";
$userAccess = require_once __DIR__ . "/databse/models/db_user.php";
$sessionAccess = require_once __DIR__ . "/databse/models/db_session.php";

const ERROR_REQUIRED = "Ce champs est requis";
const ERROR_USERNAME_UNKNOWN = "Le nom d'utilisateur est inconnu";
const ERROR_PASSWORD_WRONG = "Le mot de passe est incorrect";

$errors = [
    'username' => '',
    'password' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username) {
        $errors['username'] = ERROR_REQUIRED;
    }

    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $user = $userAccess->getUserByUserName($username);

        if (!$user) {
            $errors['username'] = ERROR_USERNAME_UNKNOWN;
        } elseif (!password_verify($password, $user['password'])) {
            $errors['password'] = ERROR_PASSWORD_WRONG;
        } else {
            $sessionAccess->createSession($user['user_id']);
            header('Location: /');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once __DIR__ . "/includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/user-form.css">
    <title>Teddy | Connexion</title>
</head>

<body>
    <div class="page-container">
        <?php require_once __DIR__ . "/includes/header.php" ?>
        <main>
            <h1>Connexion</h1>
            <form action="/log-in.php" method="POST">
                <div class="input-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" value=<?= $username ?? '' ?>>
                    <?php if ($errors['username']) : ?>
                        <p class="error"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password">
                    <?php if ($errors['password']) : ?>
                        <p class="error"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <button class="btn btn-blue" type="submit">Valider</button>
            </form>
        </main>
        <?php require_once __DIR__ . "/includes/footer.php" ?>
    </div>

</body>

</html>