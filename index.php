<?php
require_once __DIR__ . "/databse/db_access.php";
$userAccess = require_once __DIR__ . "/databse/models/db_user.php";
$sessionAccess = require_once __DIR__ . "/databse/models/db_session.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once __DIR__ . "/includes/head.php" ?>

    <title>Teddy | Accueil</title>
</head>

<body>
    <div class="page-container">
        <?php require_once __DIR__ . "/includes/header.php" ?>
        <h1>Hello World !</h1>
        <?php require_once __DIR__ . "/includes/footer.php" ?>
    </div>

</body>

</html>