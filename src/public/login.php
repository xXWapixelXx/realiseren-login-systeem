<?php
/**
 * Login Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Login pagina voor beheerders en gebruikers
 */

session_start();
require_once __DIR__ . '/../classes/User.php';

$foutmelding = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $foutmelding = 'Vul alle velden in.';
    } else {
        $user = new User();
        
        if ($user->login($username, $password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['is_admin'] = $user->is_admin;
            
            header('Location: index.php');
            exit;
        } else {
            $foutmelding = 'Gebruikersnaam of wachtwoord is onjuist.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - NETFISH</title>
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
            <h1>Inloggen</h1>
            
            <?php if ($foutmelding): ?>
                <div class="foutmelding"><?php echo $foutmelding; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" onsubmit="return valideerFormulier()">
                <div class="form-group">
                    <label>Gebruikersnaam:</label>
                    <input type="text" name="username" id="username">
                </div>
                
                <div class="form-group">
                    <label>Wachtwoord:</label>
                    <input type="password" name="password" id="password">
                </div>
                
                <div class="buttons">
                    <button type="submit">LOGIN</button>
                </div>
            </form>
            
            <div class="form-link">
                <a href="reset_password.php">Wachtwoord Vergeten?</a>
            </div>
        </div>
    </main>
    
    <script>
        function valideerFormulier() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            
            if (username == '' || password == '') {
                alert('Vul alle velden in.');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>
