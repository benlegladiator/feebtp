<?php
/**
 * Configuration de la Base de Données - FREE BTP SARL
 * Support hybride : MySQL (prioritaire pour XAMPP/cPanel) avec repli automatique sur SQLite
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Paramètres de configuration
define('SITE_NAME', 'FREE BTP SARL');
define('SITE_SLOGAN', 'L\'Excellence et la Rigueur au Cœur de Vos Bâtiments & Travaux Publics');
define('SITE_URL', 'http://localhost/feebtp');
define('ADMIN_EMAIL', 'contact@freebtp-sarl.com');

// Configuration Base de Données MySQL (Défaut XAMPP)
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'freebtp_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Chemin de repli SQLite
define('SQLITE_FILE', __DIR__ . '/../data/freebtp.sqlite');

function getDBConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $driver = 'sqlite';
    $canConnectMysql = false;

    // Test rapide du port MySQL (0.2s max) pour ne pas ralentir l'affichage si MySQL n'est pas démarré
    $socket = @fsockopen(DB_HOST, (int)DB_PORT, $errno, $errstr, 0.2);
    if ($socket) {
        fclose($socket);
        $canConnectMysql = true;
    }

    if ($canConnectMysql) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $tempPdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 2
            ]);
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $driver = 'mysql';
        } catch (Exception $e) {
            $pdo = null;
        }
    }

    if ($pdo === null) {
        try {
            $pdo = new PDO("sqlite:" . SQLITE_FILE, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $driver = 'sqlite';
        } catch (Exception $sqe) {
            die("Erreur critique de base de données : " . $sqe->getMessage());
        }
    }

    // Initialiser les tables si nécessaire
    initializeDatabase($pdo, $driver);

    return $pdo;
}

function initializeDatabase($pdo, $driver) {
    $autoInc = ($driver === 'mysql') ? "INT AUTO_INCREMENT PRIMARY KEY" : "INTEGER PRIMARY KEY AUTOINCREMENT";
    $textType = "TEXT";
    $timestamp = ($driver === 'mysql') ? "DATETIME DEFAULT CURRENT_TIMESTAMP" : "DATETIME DEFAULT CURRENT_TIMESTAMP";

    $queries = [
        // Table Utilisateurs Admin
        "CREATE TABLE IF NOT EXISTS users (
            id $autoInc,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(150) NOT NULL,
            full_name VARCHAR(150),
            role VARCHAR(50) DEFAULT 'admin',
            last_login DATETIME,
            created_at $timestamp
        )",

        // Table Paramètres du Site (CMS Global)
        "CREATE TABLE IF NOT EXISTS settings (
            id $autoInc,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value $textType
        )",

        // Table Services BTP
        "CREATE TABLE IF NOT EXISTS services (
            id $autoInc,
            slug VARCHAR(120) NOT NULL UNIQUE,
            title VARCHAR(200) NOT NULL,
            short_desc $textType,
            full_desc $textType,
            icon VARCHAR(80) DEFAULT 'fa-hard-hat',
            image VARCHAR(255),
            sort_order INT DEFAULT 0,
            is_active INT DEFAULT 1,
            created_at $timestamp
        )",

        // Table Projets / Réalisations
        "CREATE TABLE IF NOT EXISTS projects (
            id $autoInc,
            slug VARCHAR(150) NOT NULL UNIQUE,
            title VARCHAR(250) NOT NULL,
            category VARCHAR(100) NOT NULL,
            client_name VARCHAR(200),
            location VARCHAR(200) DEFAULT 'Dschang, Cameroun',
            duration VARCHAR(100),
            surface VARCHAR(100),
            budget VARCHAR(100),
            status VARCHAR(50) DEFAULT 'Terminé',
            short_desc $textType,
            description $textType,
            main_image VARCHAR(255),
            before_image VARCHAR(255),
            after_image VARCHAR(255),
            video_url VARCHAR(255),
            is_featured INT DEFAULT 1,
            created_at $timestamp
        )",

        // Table Photos de galerie des projets
        "CREATE TABLE IF NOT EXISTS project_gallery (
            id $autoInc,
            project_id INT NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            caption VARCHAR(255),
            created_at $timestamp
        )",

        // Table Actualités / Blog BTP
        "CREATE TABLE IF NOT EXISTS articles (
            id $autoInc,
            slug VARCHAR(150) NOT NULL UNIQUE,
            title VARCHAR(250) NOT NULL,
            category VARCHAR(100) DEFAULT 'Actualités Chantiers',
            author VARCHAR(100) DEFAULT 'Direction FREE BTP',
            summary $textType,
            content $textType,
            image VARCHAR(255),
            views INT DEFAULT 0,
            is_published INT DEFAULT 1,
            created_at $timestamp
        )",

        // Table Avis & Témoignages Clients
        "CREATE TABLE IF NOT EXISTS testimonials (
            id $autoInc,
            client_name VARCHAR(150) NOT NULL,
            client_title VARCHAR(150),
            company VARCHAR(150),
            comment $textType,
            rating INT DEFAULT 5,
            photo VARCHAR(255),
            is_active INT DEFAULT 1,
            created_at $timestamp
        )",

        // Table Messages, Demandes de Devis et Boîte de Dialogue
        "CREATE TABLE IF NOT EXISTS messages (
            id $autoInc,
            type VARCHAR(50) DEFAULT 'contact', -- 'contact', 'devis', 'livechat'
            sender_name VARCHAR(150) NOT NULL,
            sender_email VARCHAR(150),
            sender_phone VARCHAR(50),
            service_requested VARCHAR(150),
            subject VARCHAR(250),
            message $textType,
            attachment VARCHAR(255),
            status VARCHAR(50) DEFAULT 'unread', -- 'unread', 'read', 'replied', 'archived'
            admin_notes $textType,
            created_at $timestamp
        )",

        // Table Médiathèque dynamique
        "CREATE TABLE IF NOT EXISTS media (
            id $autoInc,
            title VARCHAR(200),
            media_type VARCHAR(50) DEFAULT 'image', -- 'image', 'video'
            file_url VARCHAR(255) NOT NULL,
            file_size VARCHAR(50),
            category VARCHAR(100) DEFAULT 'chantier',
            created_at $timestamp
        )"
    ];

    foreach ($queries as $q) {
        $pdo->exec($q);
    }

    // Insérer les données initiales de démonstration
    seedInitialData($pdo);
}

function seedInitialData($pdo) {
    // 1. Administrateur par défaut (admin / admin123)
    if ((int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $hashedPassword, 'contact@freebtp-sarl.com', 'M. AGHOKENG TANGMO Fabrice', 'superadmin']);
    }

    // 2. Paramètres du site
    if ((int)$pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn() === 0) {
        $settings = [
            'company_name' => 'FREE BTP SARL',
            'slogan' => 'Excellence, Rigueur et Durabilité dans le BTP au Cameroun',
            'manager_name' => 'M. AGHOKENG TANGMO FABRICE',
            'manager_role' => 'Gérant / Directeur Général',
            'contract_ref' => 'CT-WEB-FREEBTP-2026-01',
            'address' => 'Dschang, Région de l\'Ouest, Cameroun',
            'city' => 'Dschang',
            'country' => 'Cameroun',
            'phone_primary' => '(+237) 691 00 64 31',
            'phone_secondary' => '(+237) 671 06 34 41',
            'whatsapp_number' => '237691006431',
            'email_contact' => 'contact@freebtp-sarl.com',
            'email_direction' => 'direction@freebtp-sarl.com',
            'opening_hours' => 'Lun - Ven : 07h30 - 18h00 | Sam : 08h00 - 13h00',
            'stats_experience' => '12',
            'stats_projects' => '165',
            'stats_satisfaction' => '99',
            'stats_equipments' => '48',
            'about_summary' => 'Basée à Dschang dans la Région de l\'Ouest du Cameroun, FREE BTP SARL est une entreprise leader dans le Bâtiment, les Travaux Publics, le Génie Civil et la Rénovation.',
            'developer_credit' => 'M. NDONGMO TEJIONA BEN ARTHUR (+237 691 67 06 26)'
        ];

        $stmtSet = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
        foreach ($settings as $k => $v) {
            $stmtSet->execute([$k, $v]);
        }
    }

    // 3. Services BTP
    if ((int)$pdo->query("SELECT COUNT(*) FROM services")->fetchColumn() === 0) {
        $services = [
        [
            'slug' => 'gros-oeuvre-maconnerie',
            'title' => 'Gros Œuvre & Maçonnerie Générale',
            'short_desc' => 'Fondations renforcées, structures en béton armé, élévation de murs et charpentes aux normes parasismiques et de résistance.',
            'full_desc' => 'Le gros œuvre constitue la colonne vertébrale de tout bâtiment. FREE BTP SARL met à profit plus de 12 ans d\'expertise pour assurer le terrassement, le ferraillage certifié, le coulage de dalles et semelles, ainsi que les structures porteuses capables de défier le temps et les contraintes géotechniques de la région.',
            'icon' => 'fa-cubes',
            'image' => 'assets/images/service-gros-oeuvre.jpg',
            'sort_order' => 1
        ],
        [
            'slug' => 'second-oeuvre-finitions',
            'title' => 'Second Œuvre & Finitions Haut Standing',
            'short_desc' => 'Électricité générale, plomberie sanitaire, pose de carrelage grand format, étanchéité, staff et peinture décorative.',
            'full_desc' => 'Sublimez vos espaces de vie et de travail grâce à nos équipes de spécialistes du second œuvre. Nous réalisons l\'installation des réseaux électriques sécurisés, plomberie moderne, étanchéité des terrasses et toitures, staff décoratif moderne et revêtements haut de gamme.',
            'icon' => 'fa-paint-roller',
            'image' => 'assets/images/service-second-oeuvre.jpg',
            'sort_order' => 2
        ],
        [
            'slug' => 'genie-civil-vrd',
            'title' => 'Génie Civil & VRD (Voiries et Réseaux)',
            'short_desc' => 'Aménagement de voiries urbaines et interurbaines, ponts, dalots, assainissement, caniveaux et réseaux divers.',
            'full_desc' => 'Notre département Génie Civil conçoit et réalise les infrastructures indispensables au développement économique : terrassements massifs, voiries pavées ou bitumées, buses, caniveaux de drainage des eaux pluviales et réseaux d\'adduction d\'eau.',
            'icon' => 'fa-road',
            'image' => 'assets/images/service-vrd.jpg',
            'sort_order' => 3
        ],
        [
            'slug' => 'renovation-rehabilitation',
            'title' => 'Rénovation & Réhabilitation Immobilière',
            'short_desc' => 'Reprise en sous-œuvre, modernisation de façades, restructuration d\'immeubles anciens et surélévations sécurisées.',
            'full_desc' => 'Donnez une seconde jeunesse à vos patrimoines immobiliers. FREE BTP effectue des diagnostics de structure approfondis pour renforcer les fondations, moderniser les intérieurs et valoriser vos biens avec des matériaux durables et économiques.',
            'icon' => 'fa-tools',
            'image' => 'assets/images/service-renovation.jpg',
            'sort_order' => 4
        ],
        [
            'slug' => 'etudes-techniques-architecture',
            'title' => 'Études Techniques, Plans & Architecture',
            'short_desc' => 'Conception de plans architecturaux 2D/3D, calculs de structures béton/métallique, devis quantitatifs et estimatifs (DQE).',
            'full_desc' => 'Avant chaque coup de pioche, notre bureau d\'études intégré analyse les sols, optimise la conception bioclimatique, calcule les descentes de charges et rédige les cahiers des charges pour garantir la maîtrise des coûts et des délais.',
            'icon' => 'fa-drafting-compass',
            'image' => 'assets/images/service-etudes.jpg',
            'sort_order' => 5
        ],
        [
            'slug' => 'conduite-travaux-pilotage',
            'title' => 'Conduite de Travaux & Pilotage de Chantier (OPC)',
            'short_desc' => 'Supervision rigoureuse, contrôle qualité des matériaux, respect des délais de livraison et normes de sécurité HSE.',
            'full_desc' => 'Assurez-vous d\'une tranquillité totale grâce à nos ingénieurs et conducteurs de travaux dédiés sur site. Rapports d\'étape hebdomadaires, suivi budgétaire strict et réception sans réserve de vos chantiers.',
            'icon' => 'fa-clipboard-check',
            'image' => 'assets/images/service-pilotage.jpg',
            'sort_order' => 6
        ]
    ];

    $stmtSvc = $pdo->prepare("INSERT INTO services (slug, title, short_desc, full_desc, icon, image, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($services as $s) {
        $stmtSvc->execute([$s['slug'], $s['title'], $s['short_desc'], $s['full_desc'], $s['icon'], $s['image'], $s['sort_order']]);
    }
    }

    // 4. Projets BTP Réalisés
    if ((int)$pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn() === 0) {
    $projects = [
        [
            'slug' => 'residence-les-fougères-dschang',
            'title' => 'Immeuble Résidentiel R+3 « Les Fougères »',
            'category' => 'Résidentiel',
            'client_name' => 'Promotion Privée M. Kamga',
            'location' => 'Dschang, Quartier Foréké',
            'duration' => '8 Mois',
            'surface' => '1 200 m²',
            'budget' => '145 000 000 FCFA',
            'status' => 'Terminé',
            'short_desc' => 'Construction intégrale gros œuvre et second œuvre haut standing d\'un complexe résidentiel de 12 appartements modernes.',
            'description' => 'Ce projet résidentiel haut de gamme combine architecture moderne et respect du relief accidenté de Dschang. Fondations profondes sur pieux en béton armé, double vitrage thermique, système de récupération des eaux pluviales et parkings sécurisés en sous-sol.',
            'main_image' => 'assets/images/project-1.jpg',
            'before_image' => 'assets/images/project-1-before.jpg',
            'after_image' => 'assets/images/project-1.jpg',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_featured' => 1
        ],
        [
            'slug' => 'complexe-commercial-le-mont-bafoussam',
            'title' => 'Centre Commercial & Bureaux « Le Mont »',
            'category' => 'Commercial',
            'client_name' => 'Société Ouest Invest',
            'location' => 'Bafoussam, Centre Urbain',
            'duration' => '11 Mois',
            'surface' => '2 400 m²',
            'budget' => '280 000 000 FCFA',
            'status' => 'Terminé',
            'short_desc' => 'Édification d\'une structure mixte béton-métal avec verrière contemporaine et 28 boutiques climatisées.',
            'description' => 'Réalisation d\'un carrefour commercial d\'envergure comprenant des plateaux de bureaux modulables, sécurité incendie aux normes internationales et escalators.',
            'main_image' => 'assets/images/project-2.jpg',
            'before_image' => 'assets/images/project-2-before.jpg',
            'after_image' => 'assets/images/project-2.jpg',
            'video_url' => '',
            'is_featured' => 1
        ],
        [
            'slug' => 'amenagement-voirie-drainage-dschang',
            'title' => 'Voirie Urbaine Pavée & Caniveaux de Drainage',
            'category' => 'Travaux Publics / VRD',
            'client_name' => 'Communauté Urbaine / Partenaires',
            'location' => 'Dschang, Axe Église du Centenaire',
            'duration' => '5 Mois',
            'surface' => '3,5 km de linéaire',
            'budget' => '95 000 000 FCFA',
            'status' => 'Terminé',
            'short_desc' => 'Pavage autobloquant haute résistance, construction de dalots et buses pour l\'évacuation rapide des eaux de ruissellement.',
            'description' => 'FREE BTP a réhabilité cet axe névralgique en installant des pavés autobloquants de 8 cm posés sur lit de sable stabilisé et des fossés trapézoïdaux en maçonnerie de moellons.',
            'main_image' => 'assets/images/project-3.jpg',
            'before_image' => 'assets/images/project-3-before.jpg',
            'after_image' => 'assets/images/project-3.jpg',
            'video_url' => '',
            'is_featured' => 1
        ],
        [
            'slug' => 'villa-contemporaine-bastos-yaounde',
            'title' => 'Villa de Maître Contemporaine avec Piscine',
            'category' => 'Résidentiel',
            'client_name' => 'Particulier VIP',
            'location' => 'Yaoundé, Quartier Bastos',
            'duration' => '7 Mois',
            'surface' => '650 m²',
            'budget' => '115 000 000 FCFA',
            'status' => 'Terminé',
            'short_desc' => 'Architecture cubique épurée, baie vitrée panoramique, toiture-terrasse étanchée et piscine à débordement.',
            'description' => 'Finition d\'orfèvre avec carrelage italien en grès cérame 120x60, domotique d\'éclairage et menuiserie aluminium anodisé noire.',
            'main_image' => 'assets/images/project-4.jpg',
            'before_image' => 'assets/images/project-4-before.jpg',
            'after_image' => 'assets/images/project-4.jpg',
            'video_url' => '',
            'is_featured' => 1
        ],
        [
            'slug' => 'entrepot-logistique-douala-bassa',
            'title' => 'Hangar & Entrepôt Logistique Métallique',
            'category' => 'Industriel',
            'client_name' => 'Trans-Cameroon Logistics',
            'location' => 'Douala, Zone Industrielle Bassa',
            'duration' => '6 Mois',
            'surface' => '3 000 m²',
            'budget' => '190 000 000 FCFA',
            'status' => 'Terminé',
            'short_desc' => 'Charpente métallique grande portée sans poteau intermédiaire avec dallage industriel renforcé quartz.',
            'description' => 'Conçu pour supporter des charges lourdes de chariots élévateurs avec résistance de 5 tonnes/m². Bardage en tôles prélaquées anti-corrosion marine.',
            'main_image' => 'assets/images/project-5.jpg',
            'before_image' => '',
            'after_image' => '',
            'video_url' => '',
            'is_featured' => 0
        ],
        [
            'slug' => 'rehabilitation-faculte-sciences-dschang',
            'title' => 'Réhabilitation & Extension Amphithéâtre',
            'category' => 'Travaux Publics / VRD',
            'client_name' => 'Établissement Public Universitaire',
            'location' => 'Dschang, Campus Universitaire',
            'duration' => '4 Mois',
            'surface' => '800 m²',
            'budget' => '75 000 000 FCFA',
            'status' => 'En cours',
            'short_desc' => 'Rénovation acoustique, réfection complète de la toiture bac alu, ventilation et modernisation de 500 places assises.',
            'description' => 'Chantier mené en site occupé avec contraintes horaires strictes pour préserver les activités académiques. Livraison prévue avant la rentrée.',
            'main_image' => 'assets/images/project-6.jpg',
            'before_image' => 'assets/images/project-6-before.jpg',
            'after_image' => 'assets/images/project-6.jpg',
            'video_url' => '',
            'is_featured' => 1
        ]
    ];

    $stmtProj = $pdo->prepare("INSERT INTO projects (slug, title, category, client_name, location, duration, surface, budget, status, short_desc, description, main_image, before_image, after_image, video_url, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $p) {
        $stmtProj->execute([
            $p['slug'], $p['title'], $p['category'], $p['client_name'], $p['location'],
            $p['duration'], $p['surface'], $p['budget'], $p['status'], $p['short_desc'],
            $p['description'], $p['main_image'], $p['before_image'], $p['after_image'],
            $p['video_url'], $p['is_featured']
        ]);
    }
    }

    // 5. Articles d'actualités BTP
    if ((int)$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn() === 0) {
    $articles = [
        [
            'slug' => 'acquisition-nouveaux-engins-tp-freebtp-2026',
            'title' => 'FREE BTP renforce son parc matériel avec 3 nouvelles pelleteuses et camions bennes',
            'category' => 'Vie de l\'entreprise',
            'summary' => 'Afin de répondre à la demande croissante sur les chantiers de la région de l\'Ouest et du Littoral, FREE BTP investit dans du matériel moderne de terrassement.',
            'content' => 'Dans le cadre de son plan de modernisation 2026, FREE BTP SARL vient d\'accueillir sur sa base logistique de Dschang de nouveaux engins de terrassement et de levage de dernière génération. Cet investissement stratégique permet d\'accroître nos cadences d\'exécution et de garantir une réactivité exemplaire pour tous nos maîtres d\'ouvrage.',
            'image' => 'assets/images/news-1.jpg',
            'views' => 142
        ],
        [
            'slug' => 'guide-reussir-fondations-zone-montagneuse-ouest-cameroun',
            'title' => 'Comment réussir les fondations d\'un bâtiment en zone de pente à Dschang et à l\'Ouest ?',
            'category' => 'Conseils & Expertise BTP',
            'summary' => 'Étude de sol géotechnique, murs de soutènement et drainage : découvrez les règles d\'art pour bâtir en toute sécurité sur un terrain en dénivelé.',
            'content' => 'Construire sur les collines verdoyantes de Dschang ou dans les reliefs de l\'Ouest Cameroun requiert une rigueur d\'ingénierie spécifique. L\'humidité et les risques de glissement imposent des semelles isolées ou filantes ancrées sur le bon sol, complétées par des murs de soutènement en béton banché et un réseau de barbacanes.',
            'image' => 'assets/images/news-2.jpg',
            'views' => 289
        ],
        [
            'slug' => 'respect-normes-hse-chantiers-btp-cameroun',
            'title' => 'Sécurité et Qualité : Pourquoi FREE BTP fait de la politique HSE sa priorité absolue',
            'category' => 'Qualité & Sécurité',
            'summary' => 'Casques, harnais, audits réguliers et respect strict de l\'environnement : notre engagement pour le zéro accident sur tous nos chantiers.',
            'content' => 'La sécurité des ouvriers et des riverains est au sommet de nos exigences contractuelles. Chaque conducteur de travaux applique la charte Hygiène, Sécurité et Environnement (HSE), assurant une protection optimale à chaque phase du gros œuvre jusqu\'aux finitions.',
            'image' => 'assets/images/news-3.jpg',
            'views' => 97
        ]
    ];

    $stmtArt = $pdo->prepare("INSERT INTO articles (slug, title, category, summary, content, image, views) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($articles as $a) {
        $stmtArt->execute([$a['slug'], $a['title'], $a['category'], $a['summary'], $a['content'], $a['image'], $a['views']]);
    }
    }

    // 6. Témoignages clients
    if ((int)$pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn() === 0) {
    $testimonials = [
        [
            'client_name' => 'Dr. Emmanuel TCHOUPO',
            'client_title' => 'Promoteur Immobilier',
            'company' => 'Résidence Les Palmiers - Dschang',
            'comment' => 'FREE BTP SARL a dirigé notre chantier de bout en bout avec un professionnalisme exemplaire. Les délais ont été respectés au jour près et les finitions sont irréprochables.',
            'rating' => 5,
            'photo' => 'assets/images/client-1.jpg'
        ],
        [
            'client_name' => 'Mme Sandrine FOTSO',
            'client_title' => 'Directrice Générale',
            'company' => 'Hôtel Panorama - Bafoussam',
            'comment' => 'Une équipe d\'ingénieurs réactive et disponible. Leur capacité à anticiper les contraintes de sol et leur transparence sur les coûts nous ont fait gagner un temps précieux.',
            'rating' => 5,
            'photo' => 'assets/images/client-2.jpg'
        ],
        [
            'client_name' => 'Ing. Patrick MBIDA',
            'client_title' => 'Maître d\'Ouvrage Délégué',
            'company' => 'Projet Voirie & Drainage',
            'comment' => 'Qualité de béton irréprochable, matériel lourd adapté et suivi rigoureux avec le laboratoire géotechnique. Nous retravaillerons avec FREE BTP sans hésiter.',
            'rating' => 5,
            'photo' => 'assets/images/client-3.jpg'
        ]
    ];

    $stmtTest = $pdo->prepare("INSERT INTO testimonials (client_name, client_title, company, comment, rating, photo) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($testimonials as $t) {
        $stmtTest->execute([$t['client_name'], $t['client_title'], $t['company'], $t['comment'], $t['rating'], $t['photo']]);
    }
    }

    // 7. Messages de démonstration dans la boîte de réception
    if ((int)$pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn() === 0) {
    $messages = [
        [
            'type' => 'devis',
            'sender_name' => 'M. Bertrand KENFACK',
            'sender_email' => 'b.kenfack@yahoo.fr',
            'sender_phone' => '(+237) 677 45 12 89',
            'service_requested' => 'Gros Œuvre & Maçonnerie Générale',
            'subject' => 'Demande de devis pour construction villa R+1 à Dschang',
            'message' => 'Bonjour M. AGHOKENG, je dispose d\'un terrain de 600m² à Dschang (vers le lac municipal) et je souhaite obtenir un devis quantitatif et estimatif pour le gros œuvre d\'une villa duplex R+1. Les plans d\'architecte sont disponibles.',
            'status' => 'unread'
        ],
        [
            'type' => 'contact',
            'sender_name' => 'Société Ouest Carrière & Béton',
            'sender_email' => 'commercial@ouest-carriere.cm',
            'sender_phone' => '(+237) 699 88 77 66',
            'service_requested' => 'Partenariat Fournisseur',
            'subject' => 'Proposition de fourniture d\'agrégats et graviers concassés',
            'message' => 'Nous souhaitons proposer notre catalogue de graviers 5/15 et 15/25 livrés directement sur vos chantiers de Dschang et Bafoussam à tarif préférentiel BTP.',
            'status' => 'read'
        ],
        [
            'type' => 'livechat',
            'sender_name' => 'Visiteur - Chantier Kribi',
            'sender_email' => 'client@gmail.com',
            'sender_phone' => '(+237) 691 22 33 44',
            'service_requested' => 'Génie Civil & VRD',
            'subject' => 'Question via boîte de dialogue interactive',
            'message' => 'Bonjour, intervenez-vous également pour des travaux de voirie et terrassement dans la région du Littoral ou du Sud ?',
            'status' => 'unread'
        ]
    ];

    $stmtMsg = $pdo->prepare("INSERT INTO messages (type, sender_name, sender_email, sender_phone, service_requested, subject, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($messages as $m) {
        $stmtMsg->execute([$m['type'], $m['sender_name'], $m['sender_email'], $m['sender_phone'], $m['service_requested'], $m['subject'], $m['message'], $m['status']]);
    }
    }
}

// Fonction utilitaire pour récupérer les paramètres du site
function getSiteSettings($pdo) {
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }
    $settings = [];
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Fonction de nettoyage des entrées utilisateurs
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Convertit n'importe quelle URL vidéo (YouTube, Vimeo, MP4 direct) en code embed sécurisé
 */
function parseVideoEmbed($url) {
    if (empty($url)) return null;
    $url = trim($url);

    // Direct MP4 / WebM
    if (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
        return [
            'type' => 'html5',
            'url' => $url,
            'thumb' => '',
            'embed_html' => '<video controls playsinline preload="metadata" style="width:100%; height:100%; object-fit:cover; border-radius:8px; background:#000;"><source src="' . htmlspecialchars($url) . '" type="video/mp4">Votre navigateur ne supporte pas la vidéo HTML5.</video>'
        ];
    }

    // YouTube (watch?v=, youtu.be/, embed/, shorts/)
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
            'embed_html' => '<iframe src="' . $embedUrl . '" title="Vidéo Chantier FREE BTP" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
        ];
    }

    // Vimeo
    if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/(?:$|\/|\?)/', $url, $matches)) {
        $vimeoId = end($matches);
        $embedUrl = "https://player.vimeo.com/video/" . $vimeoId;
        return [
            'type' => 'vimeo',
            'id' => $vimeoId,
            'url' => $url,
            'embed_url' => $embedUrl,
            'thumb' => '',
            'embed_html' => '<iframe src="' . $embedUrl . '" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;" allowfullscreen></iframe>'
        ];
    }

    // Fallback URL direct
    return [
        'type' => 'iframe',
        'url' => $url,
        'embed_url' => $url,
        'thumb' => '',
        'embed_html' => '<iframe src="' . htmlspecialchars($url) . '" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;" allowfullscreen></iframe>'
    ];
}
