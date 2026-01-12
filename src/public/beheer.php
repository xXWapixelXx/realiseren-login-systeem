<?php
/**
 * Beheer Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Beheer pagina met overzicht van video's
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
$succesmelding = '';

if (isset($_GET['verwijder'])) {
    $video_id = $_GET['verwijder'];
    $db->query("DELETE FROM movie WHERE id = ?", [$video_id]);
    $succesmelding = 'Video verwijderd!';
}

$stmt = $db->query("SELECT * FROM movie ORDER BY id DESC");
$videos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer - NETFISH</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .beheer {
            padding: 30px 50px;
        }
        
        .beheer h1 {
            color: #ffffff;
            margin-bottom: 20px;
        }
        
        .toevoegen-link {
            margin-bottom: 30px;
        }
        
        .toevoegen-link a {
            color: #f41313;
            text-decoration: none;
        }
        
        .lijst-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        
        .lijst-row span {
            color: #ffffff;
        }
        
        .col-jaar {
            width: 100px;
        }
        
        .col-titel {
            flex: 1;
        }
        
        .col-actie {
            width: 200px;
        }
        
        .col-actie a {
            color: #ffffff;
            text-decoration: none;
        }
        
        .col-actie a:hover {
            color: #f41313;
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
    
    <div class="beheer">
        <h1>Beheer - Overzicht</h1>
        
        <?php if ($succesmelding): ?>
            <div class="succesmelding"><?php echo $succesmelding; ?></div>
        <?php endif; ?>
        
        <div class="toevoegen-link">
            <a href="video_toevoegen.php">+ Video toevoegen</a>
        </div>
        
        <div class="lijst">
            <div class="lijst-row">
                <span class="col-jaar"><strong>Jaar</strong></span>
                <span class="col-titel"><strong>Titel</strong></span>
                <span class="col-actie"><strong>Acties</strong></span>
            </div>
            
            <?php foreach ($videos as $video): ?>
                <div class="lijst-row">
                    <span class="col-jaar"><?php echo $video['year']; ?></span>
                    <span class="col-titel"><?php echo $video['title']; ?></span>
                    <span class="col-actie">
                        <a href="video_bewerken.php?id=<?php echo $video['id']; ?>">bewerken</a> | 
                        <a href="?verwijder=<?php echo $video['id']; ?>" onclick="return confirm('Verwijderen?')">verwijderen</a>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
