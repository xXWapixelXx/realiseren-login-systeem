<?php
/**
 * Database Class
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Database class voor database connectie en queries uitvoeren
 */

class Database
{
    public $pdo;
    public $host;
    public $name;
    public $user;
    public $pass;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->name = 'netfish_login';
        $this->user = 'root';
        $this->pass = '';
        
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->name;charset=utf8mb4",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connectie fout: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query fout: " . $e->getMessage());
        }
    }
}
