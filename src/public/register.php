<?php
/**
 * Registratie Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Registratie pagina voor nieuwe gebruikers
 */

session_start();
require_once __DIR__ . '/../classes/User.php';

$foutmelding = '';
$succesmelding = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = trim($_POST['naam']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_controle = $_POST['password_controle'];
    
    if (empty($naam) || empty($username) || empty($email) || empty($password) || empty($password_controle)) {
        $foutmelding = 'Vul alle velden in.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $foutmelding = 'Gebruikersnaam moet tussen 3 en 50 tekens zijn.';
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $foutmelding = 'Gebruikersnaam mag alleen letters en cijfers bevatten.';
    } elseif (strlen($password) < 5) {
        $foutmelding = 'Wachtwoord moet minimaal 5 tekens zijn.';
    } elseif ($password != $password_controle) {
        $foutmelding = 'Wachtwoorden komen niet overeen.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $foutmelding = 'Voer een geldig e-mailadres in.';
    } else {
        $user = new User();
        
        try {
            $user->register($username, $email, $password);
            $succesmelding = 'Registratie gelukt! Je kunt nu inloggen.';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $foutmelding = 'Gebruikersnaam of e-mailadres is al in gebruik.';
            } else {
                $foutmelding = 'Er is een fout opgetreden. Probeer het opnieuw.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - NETFISH</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../assets/fishnet_logo.png" alt="NETFISH Logo">
            <span class="logo-text">NETFISH</span>
        </div>
        <nav>
            <a href="index.php">Video's</a>
            <a href="beheer.php">Beheer</a>
            <a href="login.php">Inloggen</a>
        </nav>
    </header>
    
    <main>
        <div class="form-container">
            <h1>Registreren als klant</h1>
            
            <?php if ($foutmelding): ?>
                <div class="foutmelding"><?php echo $foutmelding; ?></div>
            <?php endif; ?>
            
            <?php if ($succesmelding): ?>
                <div class="succesmelding"><?php echo $succesmelding; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" onsubmit="return valideerFormulier()">
                <div class="form-group">
                    <label>Naam:</label>
                    <input type="text" name="naam" id="naam">
                </div>
                
                <div class="form-group">
                    <label>Gebruikersnaam:</label>
                    <input type="text" name="username" id="username">
                </div>
                
                <div class="form-group">
                    <label>E-mail adres:</label>
                    <input type="email" name="email" id="email">
                </div>
                
                <div class="form-group">
                    <label>Wachtwoord:</label>
                    <input type="password" name="password" id="password">
                </div>
                
                <div class="form-group">
                    <label>Wachtwoord ter controle:</label>
                    <input type="password" name="password_controle" id="password_controle">
                </div>
                
                <div class="buttons">
                    <button type="submit">REGISTREER</button>
                    <button type="reset">RESET</button>
                </div>
            </form>
            
            <div class="form-link">
                <a href="login.php">Al een account? Inloggen</a>
            </div>
        </div>
    </main>
    
    <script>
        function valideerFormulier() {
            var naam = document.getElementById('naam').value;
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var password_controle = document.getElementById('password_controle').value;
            
            if (naam == '' || username == '' || email == '' || password == '' || password_controle == '') {
                alert('Vul alle velden in.');
                return false;
            }
            
            if (username.length < 3 || username.length > 50) {
                alert('Gebruikersnaam moet tussen 3 en 50 tekens zijn.');
                return false;
            }
            
            var usernameRegex = /^[a-zA-Z0-9]+$/;
            if (!usernameRegex.test(username)) {
                alert('Gebruikersnaam mag alleen letters en cijfers bevatten.');
                return false;
            }
            
            if (password.length < 5) {
                alert('Wachtwoord moet minimaal 5 tekens zijn.');
                return false;
            }
            
            if (password != password_controle) {
                alert('Wachtwoorden komen niet overeen.');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>
