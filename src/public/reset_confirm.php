<?php
/**
 * Wachtwoord Reset Bevestiging Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Pagina om nieuw wachtwoord in te stellen na reset link
 */

session_start();
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Database.php';

$foutmelding = '';
$succesmelding = '';
$valid_link = false;

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$hash = isset($_GET['hash']) ? $_GET['hash'] : '';

if (!empty($user_id) && !empty($hash)) {
    $db = new Database();
    
    $stmt = $db->query("SELECT * FROM password_resets WHERE user_id = ? AND hash = ? AND used = 0 AND expires_at > NOW()", [$user_id, $hash]);
    $reset = $stmt->fetch();
    
    if ($reset) {
        $valid_link = true;
    } else {
        $foutmelding = 'Ongeldige of verlopen reset link.';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $valid_link) {
    $password = $_POST['password'];
    $password_controle = $_POST['password_controle'];
    
    if (empty($password) || empty($password_controle)) {
        $foutmelding = 'Vul alle velden in.';
    } elseif (strlen($password) < 5) {
        $foutmelding = 'Wachtwoord moet minimaal 5 tekens zijn.';
    } elseif ($password != $password_controle) {
        $foutmelding = 'Wachtwoorden komen niet overeen.';
    } else {
        $user = new User();
        $user->updatePassword($user_id, $password);
        
        $db = new Database();
        $db->query("UPDATE password_resets SET used = 1 WHERE user_id = ? AND hash = ?", [$user_id, $hash]);
        
        $succesmelding = 'Je wachtwoord is gewijzigd. Je kunt nu <a href="login.php" style="color: #f41313;">inloggen</a> met je nieuwe wachtwoord.';
        $valid_link = false;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw Wachtwoord - NETFISH</title>
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
            <h1>Nieuw wachtwoord instellen</h1>
            
            <?php if ($foutmelding): ?>
                <div class="foutmelding"><?php echo $foutmelding; ?></div>
            <?php endif; ?>
            
            <?php if ($succesmelding): ?>
                <div class="succesmelding"><?php echo $succesmelding; ?></div>
            <?php endif; ?>
            
            <?php if ($valid_link): ?>
                <form method="POST" action="" onsubmit="return valideerFormulier()">
                    <div class="form-group">
                        <label>Nieuw wachtwoord:</label>
                        <input type="password" name="password" id="password">
                    </div>
                    
                    <div class="form-group">
                        <label>Wachtwoord ter controle:</label>
                        <input type="password" name="password_controle" id="password_controle">
                    </div>
                    
                    <div class="buttons">
                        <button type="submit">[WIJZIGEN]</button>
                    </div>
                </form>
            <?php endif; ?>
            
            <div class="form-link">
                <a href="login.php">Terug naar inloggen</a>
            </div>
        </div>
    </main>
    
    <script>
        function valideerFormulier() {
            var password = document.getElementById('password').value;
            var password_controle = document.getElementById('password_controle').value;
            
            if (password == '' || password_controle == '') {
                alert('Vul alle velden in.');
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
