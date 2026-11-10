<?php
/**
 * Gestionnaire Exhaustif de Visuels de Repère par Page & Section - FEE BTP SARL
 * Permet d'administrer 100% des images, bannières, logos, cadres et engins du site.
 */

function initPageVisualsTable($pdo) {
    static $initialized = false;
    if ($initialized) return;

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS page_visuals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_slug VARCHAR(50) NOT NULL,
            section_key VARCHAR(50) NOT NULL,
            section_name VARCHAR(120) NOT NULL,
            title VARCHAR(150) NOT NULL,
            description VARCHAR(255) NULL,
            image_url VARCHAR(255) NOT NULL,
            default_url VARCHAR(255) NOT NULL,
            focal_point VARCHAR(30) DEFAULT 'center top',
            sort_order INT DEFAULT 0,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_page_sec (page_slug, section_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Nettoyage complet des résidus éventuels de RMC BTP non conformes à FEE BTP
        $pdo->exec("DELETE FROM page_visuals WHERE section_key IN ('ecosystem_btp', 'ecosystem_quincaillerie', 'ecosystem_decor') 
            OR default_url LIKE '%_JMP6225%' 
            OR default_url LIKE '%rodrigue-djomo%' 
            OR default_url LIKE '%IMG-20261029-WA0099%' 
            OR default_url LIKE '%IMG-20261029-WA0098%' 
            OR default_url LIKE '%IMG-20261029-WA0097%'
            OR title LIKE '%RMC BTP%'");

        // Liste exhaustive des visuels de repère fondamentaux réels de FEE BTP SARL
        $defaults = [
            // ==========================================
            // 0. IDENTITÉ VISUELLE & LOGOS (header / footer / favicon)
            // ==========================================
            [
                'page_slug' => 'identity',
                'section_key' => 'site_logo',
                'section_name' => "Identité Visuelle & Navigation",
                'title' => "Logo Principal du Site (En-tête & Barre de Navigation)",
                'description' => "Logo officiel FEE BTP affiché dans la barre de navigation sur fond blanc.",
                'image_url' => 'images/logo.png',
                'default_url' => 'images/logo.png',
                'focal_point' => 'center center',
                'sort_order' => 1
            ],
            [
                'page_slug' => 'identity',
                'section_key' => 'site_logo_white',
                'section_name' => "Identité Visuelle & Navigation",
                'title' => "Logo Pied de Page (Footer sur Fond Sombre)",
                'description' => "Logo contrasté pour le bas de page officiel FEE BTP.",
                'image_url' => 'images/logo-white.png',
                'default_url' => 'images/logo-white.png',
                'focal_point' => 'center center',
                'sort_order' => 2
            ],
            [
                'page_slug' => 'identity',
                'section_key' => 'site_favicon',
                'section_name' => "Identité Visuelle & Navigation",
                'title' => "Favicon du Site (Icône d'onglet)",
                'description' => "Petite icône carrée représentant FEE BTP dans les onglets de navigateur.",
                'image_url' => 'images/favicon-32x32.png',
                'default_url' => 'images/favicon-32x32.png',
                'focal_point' => 'center center',
                'sort_order' => 3
            ],

            // ==========================================
            // 1. PAGE D'ACCUEIL (index.php)
            // ==========================================
            [
                'page_slug' => 'home',
                'section_key' => 'hero_slide_1',
                'section_name' => "Section A : Hero Slider Principal",
                'title' => "Slide 1 : Direction Générale & Conduite de Projets BTP",
                'description' => "Grand visuel d'ouverture montrant la supervision terrain.",
                'image_url' => 'images/personnel/FAB-6.jpg',
                'default_url' => 'images/personnel/FAB-6.jpg',
                'focal_point' => 'center top',
                'sort_order' => 10
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'hero_slide_2',
                'section_name' => "Section A : Hero Slider Principal",
                'title' => "Slide 2 : Maîtrise du Béton Armé & Dalles Nervurées",
                'description' => "Deuxième arrière-plan du carrousel de bienvenue.",
                'image_url' => 'images/personnel/FAB-8.jpg',
                'default_url' => 'images/personnel/FAB-8.jpg',
                'focal_point' => 'center top',
                'sort_order' => 20
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'hero_slide_3',
                'section_name' => "Section A : Hero Slider Principal",
                'title' => "Slide 3 : Flotte Mécanique & Terrassement en Montagne",
                'description' => "Troisième arrière-plan du carrousel de bienvenue.",
                'image_url' => 'images/personnel/FAB-4.jpg',
                'default_url' => 'images/personnel/FAB-4.jpg',
                'focal_point' => 'center top',
                'sort_order' => 25
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'pillar_terrain_1',
                'section_name' => "Section B : Spécificité Reliefs & Sols de l'Ouest",
                'title' => "Expertise 1 : Murs de Soutènement & Sécurisation de Talus",
                'description' => "Illustration de la stabilisation et soutènement sur terrain en pente.",
                'image_url' => 'images/IMG-20261015-WA0073.jpg',
                'default_url' => 'images/IMG-20261015-WA0073.jpg',
                'focal_point' => 'center top',
                'sort_order' => 30
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'pillar_terrain_2',
                'section_name' => "Section B : Spécificité Reliefs & Sols de l'Ouest",
                'title' => "Expertise 2 : Terrassement & Flotte d'Engins Lourds en Propre",
                'description' => "Illustration des pelles hydrauliques et compacteurs de chantier.",
                'image_url' => 'images/IMG-20261029-WA0105.jpg',
                'default_url' => 'images/IMG-20261029-WA0105.jpg',
                'focal_point' => 'center top',
                'sort_order' => 40
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'pillar_terrain_3',
                'section_name' => "Section B : Spécificité Reliefs & Sols de l'Ouest",
                'title' => "Expertise 3 : Béton Armé 350 kg/m³ & Suivi Rigoureux Diaspora",
                'description' => "Illustration des ouvrages en béton armé vibré et dalles de compression.",
                'image_url' => 'images/IMG-20261015-WA0054.jpg',
                'default_url' => 'images/IMG-20261015-WA0054.jpg',
                'focal_point' => 'center top',
                'sort_order' => 50
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'showcase_card_1',
                'section_name' => "Section B.2 : Résultats Concrets Livrés (Clés en Main)",
                'title' => "Projet Livré 1 : Villa Duplex Contemporaine R+1 (Foréké-Dschang)",
                'description' => "Photo de la villa livrée clé en main en 7 mois avec finitions complètes.",
                'image_url' => 'images/wa0062-framed-16x9.jpg',
                'default_url' => 'images/wa0062-framed-16x9.jpg',
                'focal_point' => 'center center',
                'sort_order' => 52
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'showcase_card_2',
                'section_name' => "Section B.2 : Résultats Concrets Livrés (Clés en Main)",
                'title' => "Projet Livré 2 : Immeuble Mixte Résidentiel R+3 (Dschang Centre)",
                'description' => "Photo de l'immeuble d'appartements et commerces livré pour investisseur.",
                'image_url' => 'images/IMG-20261015-WA0062.jpg',
                'default_url' => 'images/IMG-20261015-WA0062.jpg',
                'focal_point' => 'center center',
                'sort_order' => 54
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'showcase_card_3',
                'section_name' => "Section B.2 : Résultats Concrets Livrés (Clés en Main)",
                'title' => "Projet Livré 3 : Murs de Soutènement & Sécurisation de Terrains",
                'description' => "Photo de l'ouvrage de soutènement banché garanti 10 ans sur terrain difficile.",
                'image_url' => 'images/IMG-20261015-WA0073.jpg',
                'default_url' => 'images/IMG-20261015-WA0073.jpg',
                'focal_point' => 'center center',
                'sort_order' => 56
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'about_photo',
                'section_name' => "Section C : À Propos & Direction",
                'title' => "Photo Institutionnelle Présentation & Équipes FEE BTP",
                'description' => "Image de présentation générale de l'entreprise sur l'accueil.",
                'image_url' => 'images/about.jpg',
                'default_url' => 'images/about.jpg',
                'focal_point' => 'center top',
                'sort_order' => 60
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'dg_portrait',
                'section_name' => "Section C : À Propos & Direction",
                'title' => "Portrait Direction Générale (M. Fabrice AGHOKENG TANGMO)",
                'description' => "Photo officielle du fondateur et Directeur Général de FEE BTP.",
                'image_url' => 'images/team/aghokeng-fabrice.jpg',
                'default_url' => 'images/team/aghokeng-fabrice.jpg',
                'focal_point' => 'center top',
                'sort_order' => 65
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'ba_before',
                'section_name' => "Section C.1 : Comparateur Avant / Après Travaux",
                'title' => "Visuel AVANT : État Initial du Terrain ou Gros Œuvre Brut",
                'description' => "Image du chantier avant les interventions de nivellement et fondations.",
                'image_url' => 'images/IMG-20261015-WA0073.jpg',
                'default_url' => 'images/IMG-20261015-WA0073.jpg',
                'focal_point' => 'center center',
                'sort_order' => 67
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'ba_after',
                'section_name' => "Section C.1 : Comparateur Avant / Après Travaux",
                'title' => "Visuel APRÈS : Rendu Final de l'Immeuble Livré Clé en Main",
                'description' => "Image de la réalisation achevée avec finitions complètes.",
                'image_url' => 'images/IMG-20261015-WA0062.jpg',
                'default_url' => 'images/IMG-20261015-WA0062.jpg',
                'focal_point' => 'center center',
                'sort_order' => 68
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'counter_bg',
                'section_name' => "Section D : Statistiques & Chiffres Clés",
                'title' => "Arrière-plan des Chiffres & Indicateurs BTP",
                'description' => "Image de fond sous les compteurs de chantiers réalisés.",
                'image_url' => 'images/bg_2.jpg',
                'default_url' => 'images/bg_2.jpg',
                'focal_point' => 'center center',
                'sort_order' => 70
            ],
            [
                'page_slug' => 'home',
                'section_key' => 'cta_bg',
                'section_name' => "Section E : Appel à l'Action & Contact Final",
                'title' => "Bannière d'Appel à l'Action (Devis Express)",
                'description' => "Bannière en pied de page d'accueil invitant à soumettre un projet.",
                'image_url' => 'images/bg_1.jpg',
                'default_url' => 'images/bg_1.jpg',
                'focal_point' => 'center center',
                'sort_order' => 80
            ],

            // ==========================================
            // 2. PAGE À PROPOS (about.php)
            // ==========================================
            [
                'page_slug' => 'about',
                'section_key' => 'about_banner',
                'section_name' => "En-tête de la Page À Propos",
                'title' => "Bannière Hero Page À Propos",
                'description' => "Grande bannière en haut de la page À Propos.",
                'image_url' => 'images/personnel/FAB-8.jpg',
                'default_url' => 'images/personnel/FAB-8.jpg',
                'focal_point' => 'center top',
                'sort_order' => 110
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'about_presentation',
                'section_name' => "Présentation & Historique 2016",
                'title' => "Photo d'Illustration Présentation Institutionnelle",
                'description' => "Photo illustrant l'ancrage de FEE BTP depuis 2016 à Dschang.",
                'image_url' => 'images/personnel/FAB-2.jpg',
                'default_url' => 'images/personnel/FAB-2.jpg',
                'focal_point' => 'center top',
                'sort_order' => 120
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'team_dg',
                'section_name' => "Capital Humain & Direction",
                'title' => "Portrait Direction Générale : M. AGHOKENG TANGMO FABRICE",
                'description' => "Photo officielle du Directeur Général de FEE BTP SARL.",
                'image_url' => 'images/team/aghokeng-fabrice.jpg',
                'default_url' => 'images/team/aghokeng-fabrice.jpg',
                'focal_point' => 'center top',
                'sort_order' => 130
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'team_tchoungo',
                'section_name' => "Capital Humain & Direction",
                'title' => "Portrait Architecte Concepteur : TCHOUNGO M. JUNIOR",
                'description' => "Photo de l'architecte 3D et concepteur bioclimatique.",
                'image_url' => 'images/team/tchoungo-junior.jpg',
                'default_url' => 'images/team/tchoungo-junior.jpg',
                'focal_point' => 'center top',
                'sort_order' => 140
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'team_keibog',
                'section_name' => "Capital Humain & Direction",
                'title' => "Portrait Architecte : KEIBOG RICARDO",
                'description' => "Photo de l'architecte chargé des permis et de l'urbanisme.",
                'image_url' => 'images/team/keibog-ricardo.jpg',
                'default_url' => 'images/team/keibog-ricardo.jpg',
                'focal_point' => 'center top',
                'sort_order' => 150
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'team_guemechio',
                'section_name' => "Capital Humain & Direction",
                'title' => "Portrait Ingénieur Suivi Travaux : GUEMECHIO ETIENNE",
                'description' => "Photo de l'ingénieur chargé des calculs de structure et du contrôle béton.",
                'image_url' => 'images/team/guemechio-etienne.jpg',
                'default_url' => 'images/team/guemechio-etienne.jpg',
                'focal_point' => 'center top',
                'sort_order' => 160
            ],
            [
                'page_slug' => 'about',
                'section_key' => 'team_zetsa',
                'section_name' => "Capital Humain & Direction",
                'title' => "Portrait Responsable Logistique : ZETSA IRISS DUBAL",
                'description' => "Photo du responsable de la flotte d'engins et approvisionnements.",
                'image_url' => 'images/team/zetsa-iriss.jpg',
                'default_url' => 'images/team/zetsa-iriss.jpg',
                'focal_point' => 'center top',
                'sort_order' => 170
            ],

            // ==========================================
            // 3. PAGE SERVICES (services.php)
            // ==========================================
            [
                'page_slug' => 'services',
                'section_key' => 'services_banner',
                'section_name' => "En-tête de la Page Services",
                'title' => "Bannière Hero Page Services",
                'description' => "Grande bannière en haut de la page des prestations.",
                'image_url' => 'images/bg_1.jpg',
                'default_url' => 'images/bg_1.jpg',
                'focal_point' => 'center top',
                'sort_order' => 210
            ],

            // ==========================================
            // 4. PAGE PROJETS (project.php)
            // ==========================================
            [
                'page_slug' => 'projects',
                'section_key' => 'projects_banner',
                'section_name' => "En-tête du Portfolio de Chantiers",
                'title' => "Bannière Hero Page Projets & Réalisations",
                'description' => "Grande bannière en haut de la page Projets.",
                'image_url' => 'images/bg_1.jpg',
                'default_url' => 'images/bg_1.jpg',
                'focal_point' => 'center top',
                'sort_order' => 310
            ],

            // ==========================================
            // 5. PAGE CONTACT (contact.php)
            // ==========================================
            [
                'page_slug' => 'contact',
                'section_key' => 'contact_banner',
                'section_name' => "En-tête de la Page Contact",
                'title' => "Bannière Hero Page Contact & Devis",
                'description' => "Grande bannière en haut de la page Contact.",
                'image_url' => 'images/IMG-20261015-WA0079.jpg',
                'default_url' => 'images/IMG-20261015-WA0079.jpg',
                'focal_point' => 'center top',
                'sort_order' => 410
            ],
            [
                'page_slug' => 'contact',
                'section_key' => 'contact_office',
                'section_name' => "Bureau Technique & Siège à Dschang",
                'title' => "Photo du Bureau Technique / Siège Social",
                'description' => "Photo officielle du siège social de Dschang affichée au-dessus du plan de localisation.",
                'image_url' => 'images/about.jpg',
                'default_url' => 'images/about.jpg',
                'focal_point' => 'center center',
                'sort_order' => 420
            ],

            // ==========================================
            // 6. PAGE BLOG (blog.php)
            // ==========================================
            [
                'page_slug' => 'blog',
                'section_key' => 'blog_banner',
                'section_name' => "En-tête du Blog & Actualités",
                'title' => "Bannière Hero Page Blog & Chantiers en Direct",
                'description' => "Grande bannière en haut de la page des actualités et conseils d'ingénierie.",
                'image_url' => 'images/bg_1.jpg',
                'default_url' => 'images/bg_1.jpg',
                'focal_point' => 'center center',
                'sort_order' => 510
            ]
        ];

        $stmt = $pdo->prepare("INSERT IGNORE INTO page_visuals 
            (page_slug, section_key, section_name, title, description, image_url, default_url, focal_point, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($defaults as $d) {
            $stmt->execute([
                $d['page_slug'],
                $d['section_key'],
                $d['section_name'],
                $d['title'],
                $d['description'],
                $d['image_url'],
                $d['default_url'],
                $d['focal_point'],
                $d['sort_order']
            ]);
        }

        $initialized = true;
    } catch (Exception $e) {
        // En cas d'erreur de schéma, on ne bloque pas le site
    }
}

/**
 * Récupère tous les visuels d'une page donnée sous forme de tableau indexé par section_key
 */
function getPageVisuals($pdo, $pageSlug) {
    if (!$pdo) return [];
    initPageVisualsTable($pdo);
    static $cache = [];
    if (isset($cache[$pageSlug])) {
        return $cache[$pageSlug];
    }

    $visuals = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM page_visuals WHERE page_slug = ? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$pageSlug]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $visuals[$row['section_key']] = $row;
        }
    } catch (Exception $e) {}

    $cache[$pageSlug] = $visuals;
    return $visuals;
}

/**
 * Helper pratique pour récupérer l'URL et le cadrage d'un visuel
 */
function getVisualInfo($pageVisuals, $sectionKey, $defaultUrl = '', $defaultFocal = 'center top') {
    if (isset($pageVisuals[$sectionKey]) && !empty($pageVisuals[$sectionKey]['image_url'])) {
        return [
            'url' => $pageVisuals[$sectionKey]['image_url'],
            'focal' => $pageVisuals[$sectionKey]['focal_point'] ?: $defaultFocal,
            'title' => $pageVisuals[$sectionKey]['title'] ?? ''
        ];
    }
    return [
        'url' => $defaultUrl,
        'focal' => $defaultFocal,
        'title' => ''
    ];
}
