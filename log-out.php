<?php
require_once __DIR__ . "/databse/db_access.php";
$sessionAccess = require_once __DIR__ . "/databse/models/db_session.php";


$user = $sessionAccess->isLoggedIn();
if ($user) {
    $sessionAccess->logOut();
}



header('Location: /');
