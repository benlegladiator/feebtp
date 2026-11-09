<?php
/**
 * Configuration Base de Données - FEE BTP SARL
 * Compatible Local XAMPP & Hébergement en ligne InfinityFree
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir le fuseau horaire du Cameroun (UTC+1)
date_default_timezone_set('Africa/Douala');

define('SITE_NAME', 'FEE BTP SARL');
define('SITE_SLOGAN', 'Excellence, Rigueur et Durabilité dans le BTP au Cameroun');
define('ADMIN_EMAIL', 'contact@feebtp-sarl.com');

// ====================================================================
// CONFIGURATION BASE DE DONNÉES - FEE BTP SARL
// Compatible : Local (XAMPP), Hostinger (hPanel / cPanel) & InfinityFree
// ====================================================================

// --- VOS IDENTIFIANTS HOSTINGER (À RENSEIGNER LORS DU DÉPLOIEMENT) ---
// Sur Hostinger hPanel > Bases de données MySQL :
define('HOSTINGER_DB_HOST', 'localhost');                    // En général 'localhost' sur Hostinger
define('HOSTINGER_DB_NAME', 'u123456789_feebtp');            // Nom de votre base créée sur Hostinger
define('HOSTINGER_DB_USER', 'u123456789_feebtp');            // Nom d'utilisateur MySQL Hostinger
define('HOSTINGER_DB_PASS', 'VOTRE_MOT_DE_PASSE_HOSTINGER'); // Mot de passe MySQL Hostinger

// Détection automatique de l'environnement (Local vs En Ligne)
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$is_local = (
    strpos($http_host, 'localhost') !== false ||
    strpos($http_host, '127.0.0.1') !== false ||
    (file_exists('C:/xampp') && PHP_OS_FAMILY === 'Windows')
);

$is_infinityfree = (
    strpos($http_host, 'free.nf') !== false ||
    strpos($http_host, 'infinityfree') !== false ||
    strpos($http_host, 'epizy.com') !== false ||
    strpos($http_host, 'rf.gd') !== false ||
    strpos($http_host, 'gt.tc') !== false ||
    strpos($http_host, 'ct.ws') !== false ||
    strpos($http_host, 'likesyou.org') !== false
);

if ($is_local) {
    // Environnement Local (XAMPP)
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'freebtp_db');
    define('SITE_URL', 'http://localhost/rmcbtpsarl/feebtpsarl');
} elseif ($is_infinityfree) {
    // Environnement InfinityFree
    define('DB_HOST', 'sql113.infinityfree.com');
    define('DB_USER', 'if0_39882531');
    define('DB_PASS', 'cmTJtR2Z2yq8MO');
    define('DB_NAME', getenv('DB_NAME') ?: 'if0_39882531_feebtp');
    define('SITE_URL', 'http://' . ($http_host ?: 'feebtp.free.nf'));
} else {
    // Environnement Hostinger ou Domaine Personnalisé
    define('DB_HOST', getenv('DB_HOST') ?: HOSTINGER_DB_HOST);
    define('DB_USER', getenv('DB_USER') ?: HOSTINGER_DB_USER);
    define('DB_PASS', getenv('DB_PASS') ?: HOSTINGER_DB_PASS);
    define('DB_NAME', getenv('DB_NAME') ?: HOSTINGER_DB_NAME);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443 ? 'https://' : 'http://';
    define('SITE_URL', $protocol . $http_host);
}

function getDBConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    global $is_local, $is_infinityfree;

    $possibleDbNames = $is_infinityfree
        ? [DB_NAME, 'if0_39882531_feebtp', 'if0_39882531_freebtp', 'if0_39882531_free', 'if0_39882531_btp']
        : [DB_NAME];

    $connected = false;
    foreach ($possibleDbNames as $dbname) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . $dbname . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 4,
            ]);
            $connected = true;
            break;
        } catch (PDOException $e) {
            continue;
        }
    }

    if (!$connected) {
        // Fallback local automatique si exécuté sur XAMPP
        if ($is_local) {
            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=freebtp_db;charset=utf8mb4", "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 4,
                ]);
            } catch (PDOException $e2) {
                die("Erreur de connexion MySQL locale (XAMPP) : " . $e2->getMessage());
            }
        } elseif ($is_infinityfree) {
            die("<div style='max-width:600px;margin:50px auto;font-family:sans-serif;padding:25px;border:1px solid #fed7aa;border-radius:12px;background:#fffaf0;text-align:center;'>
                <h3 style='color:#ea580c;'>⚙️ Initialisation Base de Données InfinityFree (FEE BTP)</h3>
                <p style='color:#4b5563;'>Vérifiez que la base MySQL existe dans votre panneau de contrôle InfinityFree (ex: <code>if0_39882531_feebtp</code>).</p>
            </div>");
        } else {
            // Message d'aide guidé pour Hostinger
            die("
            <div style='max-width: 650px; margin: 60px auto; font-family: sans-serif; padding: 30px; border-radius: 12px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-top: 6px solid #ea580c; text-align: left;'>
                <h2 style='color: #1e2229; margin-top: 0;'>⚙️ Configuration Base de Données Requise (Hostinger)</h2>
                <p style='color: #4b5563; font-size: 15px; line-height: 1.6;'>
                    Votre site <strong>FEE BTP SARL</strong> est en ligne, mais il doit être relié à votre base de données MySQL Hostinger.
                </p>
                <div style='background: #f8fafc; border-left: 4px solid #ea580c; padding: 15px; margin: 20px 0;'>
                    <strong style='color: #1e2229;'>Action à réaliser :</strong>
                    <ol style='color: #374151; font-size: 14px; margin: 10px 0 0 20px; line-height: 1.8;'>
                        <li>Allez dans votre <strong>hPanel Hostinger</strong> > <em>Bases de données MySQL</em>.</li>
                        <li>Créez une base de données et notez le nom, l'utilisateur et le mot de passe.</li>
                        <li>Ouvrez le fichier <code>config/db.php</code> via le gestionnaire de fichiers Hostinger.</li>
                        <li>Renseignez <code>HOSTINGER_DB_NAME</code>, <code>HOSTINGER_DB_USER</code> et <code>HOSTINGER_DB_PASS</code>.</li>
                        <li>Importez le fichier <code>freebtp_db_export.sql</code> dans phpMyAdmin.</li>
                    </ol>
                </div>
                <p style='font-size: 13px; color: #9ca3af;'>Erreur technique retournée : " . htmlspecialchars($e->getMessage()) . "</p>
            </div>
            ");
        }
    }

    // Synchroniser l'heure MySQL avec le fuseau horaire du Cameroun (UTC+1)
    try {
        $pdo->exec("SET time_zone = '+01:00'");
    } catch (Exception $e) {}

    // Auto-installation si la base en ligne est encore vide
    try {
        $check = $pdo->query("SHOW TABLES LIKE 'settings'");
        if (!$check->fetch()) {
            $dumpFile = __DIR__ . '/../freebtp_db_export.sql';
            if (file_exists($dumpFile)) {
                $sql = file_get_contents($dumpFile);
                $pdo->exec($sql);
            }
        }
    } catch (Exception $e) {}

    return $pdo;
}

$pdo = getDBConnection();

/**
 * Récupérer un paramètre dans la table settings avec valeur par défaut
 */
function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Fonction de récupération de tous les paramètres du site sous forme de tableau associatif
 */
function getSiteSettings($pdo) {
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }
    $settings = [];
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        // En cas d'erreur
    }
    return $settings;
}

/**
 * Fonction de nettoyage des entrées utilisateurs
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

/**
 * Convertit une URL vidéo (YouTube, Vimeo, MP4 direct) en structure embed
 */
function parseVideoEmbed($url) {
    if (empty($url)) return null;
    $url = trim($url);

    // YouTube
    $youtubeId = null;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i', $url, $matches)) {
        $youtubeId = $matches[1];
    }

    if ($youtubeId) {
        $embedUrl = "https://www.youtube-nocookie.com/embed/" . $youtubeId . "?rel=0&modestbranding=1";
        return [
            'type' => 'youtube',
            'id' => $youtubeId,
            'url' => $url,
            'embed_url' => $embedUrl,
            'thumb' => "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg",
            'embed_html' => '<iframe src="' . $embedUrl . '" title="Vidéo Chantier FEE BTP" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
        ];
    }

    // Direct MP4
    if (preg_match('/\.(mp4|webm|ogg)$/i', $url) || strpos($url, 'uploads/') !== false) {
        return [
            'type' => 'local',
            'url' => $url,
            'embed_url' => $url,
            'thumb' => '',
            'embed_html' => '<video controls playsinline preload="metadata" style="width:100%; height:100%; object-fit:cover; border-radius:8px; background:#000;"><source src="' . htmlspecialchars($url) . '" type="video/mp4">Votre navigateur ne supporte pas la vidéo HTML5.</video>'
        ];
    }

    return [
        'type' => 'iframe',
        'url' => $url,
        'embed_url' => $url,
        'thumb' => '',
        'embed_html' => '<iframe src="' . htmlspecialchars($url) . '" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;" allowfullscreen></iframe>'
    ];
}

/**
 * Fonction de résolution d'image
 */
function resolveImage($path, $fallback = 'images/bg_1.jpg') {
    if (empty($path)) {
        return $fallback;
    }
    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }
    $cleaned = preg_replace('#^assets/images/#', 'images/', $path);
    if (file_exists(__DIR__ . '/../' . $cleaned)) {
        return $cleaned;
    }
    if (file_exists(__DIR__ . '/../' . $path)) {
        return $path;
    }
    $base = basename($path);
    if (file_exists(__DIR__ . '/../images/' . $base)) {
        return 'images/' . $base;
    }
    return $fallback;
}

// Module Gestionnaire de Visuels de Repère par Page & Section
require_once __DIR__ . '/visuals_helper.php';

