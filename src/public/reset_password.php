<?php
/**
 * Wachtwoord Reset Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Pagina voor wachtwoord vergeten functionaliteit
 */

session_start();
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Database.php';

$foutmelding = '';
$succesmelding = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_of_username = trim($_POST['email_of_username']);
    
    if (empty($email_of_username)) {
        $foutmelding = 'Vul je gebruikersnaam of e-mailadres in.';
    } else {
        $db = new Database();
        
        $stmt = $db->query("SELECT * FROM users WHERE email = ? OR username = ?", [$email_of_username, $email_of_username]);
        $user = $stmt->fetch();
        
        if ($user) {
            $hash = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $db->query("INSERT INTO password_resets (user_id, hash, expires_at) VALUES (?, ?, ?)", [
                $user['id'],
                $hash,
                $expires_at
            ]);
            
            $reset_link = "http://localhost/realiseren-login-systeem/src/public/reset_confirm.php?user_id=" . $user['id'] . "&hash=" . $hash;
            
            $succesmelding = 'Er is een reset link aangemaakt. In een echt systeem zou deze per e-mail worden verstuurd.<br><br>
                             <strong>Reset link:</strong><br>
                             <a href="' . $reset_link . '" style="color: #f41313; word-break: break-all;">' . $reset_link . '</a>';
        } else {
            $foutmelding = 'Gebruikersnaam of e-mailadres niet gevonden.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Vergeten - NETFISH</title>
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
            <h1>Wachtwoord vergeten</h1>
            
            <?php if ($foutmelding): ?>
                <div class="foutmelding"><?php echo $foutmelding; ?></div>
            <?php endif; ?>
            
            <?php if ($succesmelding): ?>
                <div class="succesmelding"><?php echo $succesmelding; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" onsubmit="return valideerFormulier()">
                <div class="form-group">
                    <label>Gebruikersnaam of e-mail adres:</label>
                    <input type="text" name="email_of_username" id="email_of_username">
                </div>
                
                <div class="buttons">
                    <button type="submit">RESET</button>
                </div>
            </form>
            
            <div class="form-link">
                <a href="login.php">Terug naar inloggen</a>
            </div>
        </div>
    </main>
    
    <script>
        function valideerFormulier() {
            var input = document.getElementById('email_of_username').value;
            
            if (input == '') {
                alert('Vul je gebruikersnaam of e-mailadres in.');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>
