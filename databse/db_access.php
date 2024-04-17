<?php

$dns = "mysql:host=localhost;dbname=development";
$usr = "root";
$pwd = "Octopus!127";

try {
    $pdo = new PDO($dns, $usr, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

return $pdo;
