<?php
/**
 * Database Configuratie
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Database configuratie bestand voor PDO connectie naar MySQL database
 */

// Database gegevens
$db_host = 'localhost';
$db_name = 'netfish_login';
$db_user = 'root';
$db_pass = '';

// PDO connectie maken
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connectie fout: " . $e->getMessage());
}
