<?php
/**
 * Video Toevoegen Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Pagina om nieuwe video toe te voegen
 */

session_start();
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$foutmelding = '';
$succesmelding = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $year = trim($_POST['year']);
    $description = trim($_POST['description']);
    $cover_url = trim($_POST['cover_url']);
    
    if (empty($title) || empty($url)) {
        $foutmelding = 'Titel en URL zijn verplicht.';
    } else {
        $db->query("INSERT INTO movie (title, url, year, description, cover_url) VALUES (?, ?, ?, ?, ?)", [
            $title, $url, $year, $description, $cover_url
        ]);
        $succesmelding = 'Video toegevoegd!';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Toevoegen - NETFISH</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .toevoegen-container {
            padding: 30px 50px;
        }
        
        .toevoegen-container h1 {
            color: #ffffff;
            margin-bottom: 30px;
        }
        
        .toevoegen-form {
            max-width: 500px;
        }
        
        .toevoegen-form .form-group {
            margin-bottom: 15px;
        }
        
        .toevoegen-form label {
            display: block;
            color: #f41313;
            margin-bottom: 5px;
        }
        
        .toevoegen-form input,
        .toevoegen-form textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 2px solid #ffffff;
            background-color: transparent;
            color: #ffffff;
            font-family: Verdana, sans-serif;
        }
        
        .toevoegen-form textarea {
            height: 100px;
            resize: vertical;
        }
        
        .toevoegen-form button {
            background-color: transparent;
            color: #ffffff;
            border: none;
            padding: 10px 30px;
            cursor: pointer;
            font-family: Verdana, sans-serif;
            margin-top: 20px;
        }
        
        .toevoegen-form button:hover {
            color: #f41313;
        }
        
        .back-link {
            margin-top: 20px;
        }
        
        .back-link a {
            color: #f41313;
            text-decoration: none;
        }
    </style>
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
            <a href="logout.php">Uitloggen</a>
        </nav>
    </header>
    
    <div class="toevoegen-container">
        <h1>Video Toevoegen</h1>
        
        <?php if ($foutmelding): ?>
            <div class="foutmelding"><?php echo $foutmelding; ?></div>
        <?php endif; ?>
        
        <?php if ($succesmelding): ?>
            <div class="succesmelding"><?php echo $succesmelding; ?></div>
        <?php endif; ?>
        
        <form class="toevoegen-form" method="POST">
            <div class="form-group">
                <label>Titel:</label>
                <input type="text" name="title">
            </div>
            
            <div class="form-group">
                <label>Video-Url:</label>
                <input type="text" name="url" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            
            <div class="form-group">
                <label>Cover afbeelding:</label>
                <input type="text" name="cover_url" placeholder="URL naar poster afbeelding">
            </div>
            
            <div class="form-group">
                <label>Jaar:</label>
                <input type="number" name="year">
            </div>
            
            <div class="form-group">
                <label>Beschrijving:</label>
                <textarea name="description"></textarea>
            </div>
            
            <button type="submit">TOEVOEGEN</button>
        </form>
        
        <div class="back-link">
            <a href="beheer.php">← Terug naar overzicht</a>
        </div>
    </div>
</body>
</html>
