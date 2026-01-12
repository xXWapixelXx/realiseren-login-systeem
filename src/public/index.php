<?php
/**
 * Index Pagina
 * Naam: Wail Said
 * Versie: 1.0
 * Datum: 12-01-2026
 * Beschrijving: Homepage met video overzicht
 */

session_start();
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$stmt = $db->query("SELECT * FROM movie ORDER BY id DESC");
$videos = $stmt->fetchAll();

$geselecteerde_video = null;
if (isset($_GET['preview']) && $_GET['preview'] != '') {
    $stmt = $db->query("SELECT * FROM movie WHERE id = ?", [$_GET['preview']]);
    $geselecteerde_video = $stmt->fetch();
}

if (!$geselecteerde_video && count($videos) > 0) {
    $geselecteerde_video = $videos[0];
}

$youtube_id = '';
if ($geselecteerde_video) {
    if (strpos($geselecteerde_video['url'], 'youtube.com/watch?v=') !== false) {
        $parts = explode('v=', $geselecteerde_video['url']);
        $youtube_id = explode('&', $parts[1])[0];
    } elseif (strpos($geselecteerde_video['url'], 'youtu.be/') !== false) {
        $parts = explode('youtu.be/', $geselecteerde_video['url']);
        $youtube_id = explode('?', $parts[1])[0];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video's - NETFISH</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .preview {
            display: flex;
            align-items: center;
            padding: 40px 50px;
            min-height: 350px;
            background-color: #111;
        }
        
        .preview-info {
            width: 400px;
            padding-right: 40px;
        }
        
        .preview-info h2 {
            color: #ffffff;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .preview-info .year {
            color: #f41313;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .preview-info .description {
            color: #cccccc;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .preview-player {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        
        .preview-player iframe {
            width: 560px;
            height: 315px;
            border: none;
        }
        
        .video-grid {
            padding: 30px 50px;
            background-color: #000;
        }
        
        .videos {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .video-card {
            min-width: 140px;
        }
        
        .video-card a {
            display: block;
        }
        
        .video-card img {
            width: 140px;
            height: 200px;
            object-fit: cover;
            border: 2px solid transparent;
            background-color: #333;
        }
        
        .video-card:hover img {
            border-color: #f41313;
        }
        
        .video-card.actief img {
            border-color: #f41313;
        }
        
        .no-videos {
            color: #cccccc;
            padding: 20px;
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
    
    <?php if ($geselecteerde_video): ?>
    <div class="preview">
        <div class="preview-info">
            <h2><?php echo $geselecteerde_video['title']; ?></h2>
            <p class="year"><?php echo $geselecteerde_video['year']; ?></p>
            <p class="description"><?php echo $geselecteerde_video['description']; ?></p>
        </div>
        <div class="preview-player">
            <iframe src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" allowfullscreen></iframe>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="video-grid">
        <?php if (count($videos) > 0): ?>
            <div class="videos">
                <?php foreach ($videos as $video): ?>
                    <div class="video-card <?php if ($geselecteerde_video && $video['id'] == $geselecteerde_video['id']) echo 'actief'; ?>">
                        <a href="index.php?preview=<?php echo $video['id']; ?>">
                            <img src="<?php echo $video['cover_url']; ?>" alt="<?php echo $video['title']; ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-videos">
                <p>Er zijn nog geen video's.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
