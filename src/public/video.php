<?php
/**
 * Video Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Pagina om een video af te spelen
 */

session_start();
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$video_id = isset($_GET['id']) ? $_GET['id'] : 0;

$db = new Database();
$stmt = $db->query("SELECT * FROM movie WHERE id = ?", [$video_id]);
$video = $stmt->fetch();

if (!$video) {
    header('Location: index.php');
    exit;
}

$youtube_id = '';
if (strpos($video['url'], 'youtube.com/watch?v=') !== false) {
    $parts = explode('v=', $video['url']);
    $youtube_id = explode('&', $parts[1])[0];
} elseif (strpos($video['url'], 'youtu.be/') !== false) {
    $parts = explode('youtu.be/', $video['url']);
    $youtube_id = explode('?', $parts[1])[0];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video['title']; ?> - NETFISH</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .video-container {
            padding: 20px 30px;
        }
        
        .video-header {
            margin-bottom: 20px;
        }
        
        .video-header h1 {
            color: #ffffff;
            margin-bottom: 5px;
        }
        
        .video-header .year {
            color: #f41313;
            margin-bottom: 15px;
        }
        
        .video-player {
            max-width: 800px;
            margin-bottom: 20px;
        }
        
        .video-player iframe {
            width: 100%;
            height: 450px;
            border: none;
        }
        
        .video-description {
            max-width: 800px;
            color: #cccccc;
            line-height: 1.6;
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
            <?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="beheer.php">Beheer</a>
            <?php endif; ?>
            <a href="logout.php">Uitloggen</a>
        </nav>
    </header>
    
    <div class="video-container">
        <div class="video-header">
            <h1><?php echo $video['title']; ?></h1>
            <p class="year"><?php echo $video['year']; ?></p>
        </div>
        
        <div class="video-player">
            <?php if ($youtube_id != ''): ?>
                <iframe src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" allowfullscreen></iframe>
            <?php else: ?>
                <p style="color: #cccccc;">Video URL: <?php echo $video['url']; ?></p>
            <?php endif; ?>
        </div>
        
        <div class="video-description">
            <p><?php echo $video['description']; ?></p>
        </div>
        
        <div class="back-link">
            <a href="index.php">← Terug naar overzicht</a>
        </div>
    </div>
</body>
</html>
