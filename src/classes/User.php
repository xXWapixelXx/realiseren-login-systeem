<?php
/**
 * User Class
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: User class voor gebruikers registratie, login en wachtwoord reset
 */

require_once __DIR__ . '/Database.php';

class User
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $is_admin;
    public $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)";
        $this->db->query($sql, [$username, $email, $hashed_password]);
        
        return true;
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->query($sql, [$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->is_admin = $user['is_admin'];
            return true;
        }
        
        return false;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->is_admin = $user['is_admin'];
            return true;
        }
        
        return false;
    }

    public function updatePassword($user_id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $this->db->query($sql, [$hashed_password, $user_id]);
        
        return true;
    }
}
