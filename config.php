<?php
$host = 'localhost';
$dbname = 'smarttech_db';
$username = 'root';  // Utilisateur par défaut de MySQL sous XAMPP
$password = '';      // Mot de passe vide par défaut sous XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
