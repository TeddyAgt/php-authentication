<?php
require_once __DIR__ . "/databse/db_access.php";
$userAccess = require_once __DIR__ . "/databse/models/db_user.php";
$sessionAccess = require_once __DIR__ . "/databse/models/db_session.php";


const ERROR_REQUIRED = "Ce champs est requis";
const ERROR_USERNAME_TOO_SHORT = "Le nom d'utilisateur doit faire 5 caractères minimum";
const ERROR_EMAIL_INVALID = "L'adresse mail n'est pas valide";
const ERROR_PASSWORD_TOO_SHORT = "Le mot de passe doit faire 8 caractères minimum";
const ERROR_PASSWORD_WRONG_CONFIRMATION = "Le mot de passe de confirmation ne correspond pas";

$errors = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirmation' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $presentation = filter_input(INPUT_POST, 'presentation', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES) ?? '';
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if (!$username) {
        $errors['username'] = ERROR_REQUIRED;
    } elseif (mb_strlen($username) < 5) {
        $errors['username'] = ERROR_USERNAME_TOO_SHORT;
    }

    if (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['username'] = ERROR_EMAIL_INVALID;
    }

    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 8) {
        $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
    }

    if (!$confirmation) {
        $errors['confirmation'] = ERROR_REQUIRED;
    } elseif ($confirmation !== $password) {
        $errors['confirmation'] = ERROR_PASSWORD_WRONG_CONFIRMATION;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $userAccess->createUser([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'presentation' => $presentation
        ]);
    }
    header('Location: /log-in.php');
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once __DIR__ . "/includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/user-form.css">
    <title>Teddy | Inscription</title>
</head>

<body>
    <div class="page-container">
        <?php require_once __DIR__ . "/includes/header.php" ?>
        <main>
            <h1>Inscription</h1>
            <form action="/sign-in.php" method="POST">
                <div class="input-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" value=<?= $username ?? '' ?>>
                    <?php if ($errors['username']) : ?>
                        <p class="error"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value=<?= $email ?? '' ?>>
                    <?php if ($errors['email']) : ?>
                        <p class="error"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password">
                    <?php if ($errors['password']) : ?>
                        <p class="error"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label for="confirmation">Confirmez le mot de passe:</label>
                    <input type="password" id="confirmation" name="confirmation">
                    <?php if ($errors['confirmation']) : ?>
                        <p class="error"><?= $errors['confirmation'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <label for="presentation">Présentation</label>
                    <textarea id="presentation" name="presentation"><?= $presentation ?? '' ?></textarea>
                </div>
                <button class="btn btn-blue" type="submit">Valider</button>
            </form>
        </main>
        <?php require_once __DIR__ . "/includes/footer.php" ?>
    </div>

</body>

</html>