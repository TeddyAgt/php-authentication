<?php
$user = $sessionAccess->isLoggedIn();

?>

<header>
    <a href="./index.php" class="logo">Teddy</a>
    <nav>
        <?php if ($user) : ?>
            <a class="<?= $_SERVER['REQUEST_URI'] === "/profile-page.php" ? "active" : ""; ?>" href="./profile-page.php">Mon profil</a>
            <a href="./log-out.php">Deconnexion</a>
        <?php else : ?>
            <a class="<?= $_SERVER['REQUEST_URI'] === "/sign-in.php" ? "active" : ""; ?>" href="./sign-in.php">Inscription</a>
            <a class="<?= $_SERVER['REQUEST_URI'] === "/log-in.php" ? "active" : ""; ?>" href="./log-in.php">Connexion</a>
        <?php endif; ?>
    </nav>
</header>