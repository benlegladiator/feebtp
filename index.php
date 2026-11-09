<?php
$pageTitle = "Accueil - Bâtiment, Génie Civil, VRD & Rénovation à Dschang";
require_once __DIR__ . '/includes/header.php';

// Récupérer les services actifs
$servicesStmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6");
$services = $servicesStmt->fetchAll();

// Récupérer les projets mis en avant
$projectsStmt = $pdo->query("SELECT * FROM projects WHERE is_featured = 1 ORDER BY id DESC LIMIT 6");
$projects = $projectsStmt->fetchAll();

// Récupérer les dernières actualités
$articlesStmt = $pdo->query("SELECT * FROM articles WHERE is_published = 1 ORDER BY id DESC LIMIT 3");
$articles = $articlesStmt->fetchAll();

// Récupérer les témoignages clients
$testStmt = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 3");
$testimonials = $testStmt->fetchAll();

// Récupérer les projets avec vidéo pour la section Immersion Vidéo
$videoProjectsStmt = $pdo->query("SELECT * FROM projects WHERE video_url IS NOT NULL AND video_url != '' ORDER BY id ASC LIMIT 5");
$videoProjects = $videoProjectsStmt->fetchAll();
?>

<!-- ========================================================================
     1. HERO BANNER PRINCIPAL (BTP & Construction)
     ======================================================================== -->
<section class="hero-section">
  <div class="container">
    <div class="hero-grid">
      <!-- Côté Gauche : Texte & Accroche -->
      <div>
        <div class="hero-badge">
          <i class="fas fa-shield-alt"></i> Entreprise Agréée BTP & Génie Civil • Dschang, Cameroun
        </div>
        <h1 class="hero-title">
          Construisons Ensemble des Ouvrages <span>Durables & Solides</span>.
        </h1>
        <p class="hero-description">
          Depuis plus de 12 ans, <strong>FREE BTP SARL</strong> déploie son expertise technique, ses parcs d'engins modernes et ses équipes d'ingénieurs qualifiés pour mener à bien vos projets de <strong>gros œuvre, génie civil, voiries (VRD), second œuvre et rénovations</strong> à Dschang et dans tout le Cameroun.
        </p>
        <div class="hero-buttons">
          <a href="contact.php?type=devis" class="btn btn-primary btn-lg">
            <i class="fas fa-calculator"></i> Demander une Cotation / Devis
          </a>
          <a href="projects.php" class="btn btn-outline-white btn-lg">
            <i class="fas fa-images"></i> Nos Chantiers
          </a>
          <a href="#videos-section" class="btn btn-outline-white btn-lg" style="background: rgba(249,115,22,0.2); border-color: var(--accent); color: #fff;">
            <i class="fas fa-play-circle" style="color: var(--accent);"></i> Vidéos & Drone
          </a>
        </div>
      </div>

      <!-- Côté Droit : Boîte de Demande Rapide de Devis -->
      <div>
        <div class="hero-card">
          <div class="hero-card-header">
            <h3><i class="fas fa-drafting-compass" style="color: var(--accent); margin-right: 8px;"></i> Étude de Prix Express</h3>
            <p>Recevez une estimation chiffrée de votre projet BTP sous 48h</p>
          </div>
          <form action="api/contact.php" method="POST" class="hero-form">
            <input type="hidden" name="type" value="devis">
            <div class="form-group">
              <label><i class="fas fa-user"></i> Votre Nom ou Entreprise</label>
              <input type="text" name="name" placeholder="Ex: M. Jean Kamga / SARL..." required>
            </div>
            <div class="form-group">
              <label><i class="fas fa-phone"></i> Téléphone / WhatsApp</label>
              <input type="text" name="phone" placeholder="Ex: (+237) 691 00 00 00" required>
            </div>
            <div class="form-group">
              <label><i class="fas fa-hard-hat"></i> Prestation souhaitée</label>
              <select name="service" required>
                <option value="">Sélectionnez un domaine...</option>
                <?php foreach ($services as $s): ?>
                  <option value="<?php echo htmlspecialchars($s['title']); ?>"><?php echo htmlspecialchars($s['title']); ?></option>
                <?php endforeach; ?>
                <option value="Autre projet BTP">Autre type de chantier</option>
              </select>
            </div>
            <div class="form-group">
              <label><i class="fas fa-comment-alt"></i> Brève description du projet</label>
              <textarea name="message" rows="2" placeholder="Localisation du terrain, surface estimée, délai souhaité..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
              <i class="fas fa-paper-plane"></i> Transmettre ma demande
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     2. BANDEAU DE STATISTIQUES CLÉS & PERFORMANCE
     ======================================================================== -->
<section class="container">
  <div class="stats-strip">
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-number">+<?php echo htmlspecialchars($siteSettings['stats_experience'] ?? '12'); ?> <span>Ans</span></div>
        <div class="stat-label">D'Expérience et de Maîtrise BTP</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">+<?php echo htmlspecialchars($siteSettings['stats_projects'] ?? '165'); ?></div>
        <div class="stat-label">Chantiers Livrés avec Succès</div>
      </div>
      <div class="stat-item">
        <div class="stat-number"><?php echo htmlspecialchars($siteSettings['stats_satisfaction'] ?? '99'); ?><span>%</span></div>
        <div class="stat-label">Taux de Satisfaction Maîtres d'Ouvrage</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">+<?php echo htmlspecialchars($siteSettings['stats_equipments'] ?? '48'); ?></div>
        <div class="stat-label">Engins Lourds & Équipements de Chantier</div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     BANDEAU PARTENAIRES TECHNIQUES & FOURNISSEURS DE MATÉRIAUX
     ======================================================================== -->
<section class="brands-strip">
  <div class="container">
    <div class="brands-header">
      <span class="brands-title"><i class="fas fa-handshake" style="color: var(--accent); margin-right: 6px;"></i> Partenaires Techniques & Fournisseurs de Matériaux Agréés</span>
    </div>
    <div class="brands-grid">
      <div class="brand-item" title="Partenaire Cimenterie & Béton">
        <img src="assets/images/brands/brand-1.png" alt="Partenaire Cimenterie & Béton">
      </div>
      <div class="brand-item" title="Fournisseur Aciers & Ferraillage">
        <img src="assets/images/brands/brand-2.png" alt="Fournisseur Aciers & Ferraillage">
      </div>
      <div class="brand-item" title="Laboratoire Géotechnique & Contrôle Qualité">
        <img src="assets/images/brands/brand-3.png" alt="Laboratoire Géotechnique">
      </div>
      <div class="brand-item" title="Équipements Lourds & Carrières">
        <img src="assets/images/brands/brand-4.png" alt="Équipements Lourds & Carrières">
      </div>
      <div class="brand-item" title="Menuiserie & Finitions Haut Standing">
        <img src="assets/images/brands/brand-5.png" alt="Menuiserie & Finitions">
      </div>
      <div class="brand-item" title="Réseaux VRD & Assainissement">
        <img src="assets/images/brands/brand-6.png" alt="Réseaux VRD & Assainissement">
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     3. PRÉSENTATION DE FREE BTP SARL (DSCHANG)
     ======================================================================== -->
<section class="section">
  <div class="container">
    <div class="about-grid">
      <div>
        <div class="section-tag">Qui Sommes-Nous</div>
        <h2 class="section-title">L'Allié Stratégique de Vos Travaux de Construction & Génie Civil</h2>
        <p style="font-size: 1.05rem; color: var(--muted); margin-bottom: 20px;">
          Implantée à <strong>Dschang</strong> dans la Région de l'Ouest du Cameroun, sous la direction générale de <strong><?php echo htmlspecialchars($siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE'); ?></strong>, l'entreprise <strong>FREE BTP SARL</strong> s'impose comme un acteur incontournable du secteur de la construction.
        </p>
        <p style="font-size: 0.95rem; color: var(--muted); margin-bottom: 25px;">
          Que vous soyez un particulier souhaitant ériger une résidence d'exception, un promoteur immobilier développant un complexe commercial, ou une collectivité locale engagée dans des travaux de voirie et de VRD, nous garantissons une gestion rigoureuse, des matériaux certifiés et un strict respect du calendrier d'exécution.
        </p>

        <div class="about-features">
          <div class="feature-box">
            <i class="fas fa-check-circle"></i>
            <div>
              <h4>Respect Strict des Délais</h4>
              <p>Planning d'exécution phasé avec réunions de chantier régulières.</p>
            </div>
          </div>
          <div class="feature-box">
            <i class="fas fa-medal"></i>
            <div>
              <h4>Béton & Matériaux Certifiés</h4>
              <p>Essais de résistance en laboratoire géotechnique agréé.</p>
            </div>
          </div>
          <div class="feature-box">
            <i class="fas fa-truck-monster"></i>
            <div>
              <h4>Parc Matériel Propriétaire</h4>
              <p>Pelles mécaniques, camions-bennes, centrales à béton et échafaudages.</p>
            </div>
          </div>
          <div class="feature-box">
            <i class="fas fa-user-shield"></i>
            <div>
              <h4>Normes HSE & Sécurité</h4>
              <p>Protection intégrale des ouvriers et garantie décennale des ouvrages.</p>
            </div>
          </div>
        </div>

        <div style="margin-top: 30px;">
          <a href="about.php" class="btn btn-secondary">
            <i class="fas fa-info-circle"></i> En savoir plus sur notre histoire & parcs d'engins
          </a>
        </div>
      </div>

      <div>
        <div style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-lg); border: 2px solid var(--border);">
          <img src="assets/images/about-hq.jpg" alt="Chantier FREE BTP SARL Dschang" style="width: 100%; height: auto;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     4. NOS SERVICES ET EXPERTISES DYNAMIQUES
     ======================================================================== -->
<section class="section" style="background-color: #f1f5f9;">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Domaines d'Intervention</div>
      <h2 class="section-title">Nos Pôles d'Expertise en BTP</h2>
      <p class="section-subtitle">
        Des solutions intégrées clé en main, de l'étude géotechnique initiale jusqu'à la remise solennelle des clés.
      </p>
    </div>

    <div class="services-grid">
      <?php foreach ($services as $service): ?>
        <div class="service-card">
          <div class="service-icon">
            <i class="fas <?php echo htmlspecialchars($service['icon'] ?? 'fa-hard-hat'); ?>"></i>
          </div>
          <h3 class="service-title"><?php echo htmlspecialchars($service['title']); ?></h3>
          <p class="service-desc"><?php echo htmlspecialchars($service['short_desc']); ?></p>
          <a href="services.php#<?php echo htmlspecialchars($service['slug']); ?>" class="service-link">
            Détails de l'expertise <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 40px;">
      <a href="services.php" class="btn btn-primary btn-lg">
        <i class="fas fa-th-list"></i> Consulter l'Ensemble de Nos Services
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     NOTRE MÉTHODOLOGIE DE CHANTIER (4 ÉTAPES CLÉS)
     ======================================================================== -->
<section class="section" style="background-color: #f8fafc; border-bottom: 1px solid var(--border);">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Rigueur & Démarche Qualité</div>
      <h2 class="section-title">Notre Méthodologie d'Exécution en 4 Étapes</h2>
      <p class="section-subtitle">
        De la première esquisse jusqu'à la remise solennelle des clés, chaque phase de votre chantier est pilotée avec précision par nos ingénieurs.
      </p>
    </div>

    <div class="process-grid">
      <div class="process-card">
        <div class="process-step-num">01</div>
        <div class="process-icon">
          <i class="fas fa-search-location"></i>
        </div>
        <h3 class="process-title">1. Étude & Devis DQE</h3>
        <p class="process-desc">
          Visite terrain à Dschang ou en région, sondage géotechnique du sol et élaboration d'un Devis Quantitatif et Estimatif (DQE) transparent sans coûts imprévus.
        </p>
      </div>

      <div class="process-card">
        <div class="process-step-num">02</div>
        <div class="process-icon">
          <i class="fas fa-drafting-compass"></i>
        </div>
        <h3 class="process-title">2. Conception & Plans 3D</h3>
        <p class="process-desc">
          Modélisation architecturale 2D/3D, dimensionnement béton armé selon les normes et constitution du dossier complet pour l'obtention du permis de bâtir.
        </p>
      </div>

      <div class="process-card">
        <div class="process-step-num">03</div>
        <div class="process-icon">
          <i class="fas fa-hard-hat"></i>
        </div>
        <h3 class="process-title">3. Gros Œuvre & Suivi</h3>
        <p class="process-desc">
          Terrassement, fondations armées, coulage et élévation avec contrôle qualité des bétons. Rapports d'avancement photo/vidéo hebdomadaires pour le client.
        </p>
      </div>

      <div class="process-card">
        <div class="process-step-num">04</div>
        <div class="process-icon">
          <i class="fas fa-key"></i>
        </div>
        <h3 class="process-title">4. Finitions & Clé en Main</h3>
        <p class="process-desc">
          Revêtements de sol, menuiseries, sanitaires, électricité, peintures soignées, nettoyage minutieux et remise officielle du procès-verbal de réception.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     5. RÉALISATIONS & CHANTIERS BTP LIVRÉS
     ======================================================================== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Portfolio & Références</div>
      <h2 class="section-title">Nos Derniers Chantiers Livrés</h2>
      <p class="section-subtitle">
        Découvrez quelques-uns de nos projets récents à Dschang, Bafoussam, Yaoundé et Douala.
      </p>
    </div>

    <!-- Filtres rapides -->
    <div class="project-filters">
      <button class="filter-btn active" data-filter="all">Tous les Projets</button>
      <button class="filter-btn" data-filter="Résidentiel">Résidentiel</button>
      <button class="filter-btn" data-filter="Commercial">Commercial</button>
      <button class="filter-btn" data-filter="Travaux Publics / VRD">Travaux Publics / VRD</button>
      <button class="filter-btn" data-filter="Industriel">Industriel</button>
    </div>

    <div class="projects-grid">
      <?php foreach ($projects as $proj): ?>
        <div class="project-card" data-category="<?php echo htmlspecialchars($proj['category']); ?>">
          <div class="project-img-wrapper">
            <img src="<?php echo htmlspecialchars($proj['main_image']); ?>" alt="<?php echo htmlspecialchars($proj['title']); ?>">
            <span class="project-badge"><?php echo htmlspecialchars($proj['category']); ?></span>
            <span class="project-status <?php echo ($proj['status'] === 'Terminé') ? 'status-completed' : 'status-ongoing'; ?>">
              <i class="fas <?php echo ($proj['status'] === 'Terminé') ? 'fa-check' : 'fa-spinner fa-spin'; ?>"></i>
              <?php echo htmlspecialchars($proj['status']); ?>
            </span>
          </div>
          <div class="project-body">
            <div class="project-location">
              <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
              <?php echo htmlspecialchars($proj['location']); ?>
            </div>
            <h3 class="project-title"><?php echo htmlspecialchars($proj['title']); ?></h3>
            <p style="font-size: 0.88rem; color: var(--muted); margin-bottom: 15px; line-height: 1.5;">
              <?php echo htmlspecialchars($proj['short_desc']); ?>
            </p>
            <div class="project-meta">
              <span><strong>Maître d'ouvrage :</strong> <?php echo htmlspecialchars($proj['client_name'] ?? 'Privé'); ?></span>
              <span><strong>Délai :</strong> <?php echo htmlspecialchars($proj['duration'] ?? 'N/A'); ?></span>
            </div>
            <div style="margin-top: 15px;">
              <a href="project-details.php?id=<?php echo $proj['id']; ?>" class="btn btn-outline btn-sm" style="width: 100%;">
                <i class="fas fa-eye"></i> Voir la fiche du chantier & photos
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 40px;">
      <a href="projects.php" class="btn btn-secondary btn-lg">
        <i class="fas fa-building"></i> Explorer Toutes Nos Réalisations BTP
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     6. FOCUS AVANT / APRÈS (RÉNOVATION & TRANSFORMATION)
     ======================================================================== -->
<section class="section" style="background-color: var(--primary); color: #ffffff;">
  <div class="container">
    <div class="section-header">
      <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Transformation & Qualité</div>
      <h2 class="section-title" style="color: #ffffff;">La Différence FREE BTP : Avant & Après Travaux</h2>
      <p class="section-subtitle" style="color: #cbd5e1;">
        Visualisez la métamorphose de nos chantiers, du terrain brut ou de la bâtisse dégradée à l'ouvrage finalisé d'exception.
      </p>
    </div>

    <div class="comparison-box">
      <div class="comparison-before">
        <img src="assets/images/project-1-before.jpg" alt="Chantier Dschang Avant">
        <span class="comparison-tag tag-before">PHASE 1 : FOUILLES & FONDATIONS</span>
      </div>
      <div class="comparison-after" style="clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);">
        <img src="assets/images/project-1.jpg" alt="Chantier Dschang Après">
        <span class="comparison-tag tag-after">LIVRAISON : COMPLEXE R+3 LES FOUGÈRES</span>
      </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
      <p style="font-size: 0.95rem; color: #94a3b8;">
        Chaque chantier bénéficie d'un suivi photographique et de rapports hebdomadaires remis au maître d'ouvrage.
      </p>
    </div>
  </div>
</section>

<!-- ========================================================================
     IMMERSION VIDÉO & VUES DRONE DES CHANTIERS
     ======================================================================== -->
<?php if (!empty($videoProjects)): 
  $firstVideo = $videoProjects[0];
  $firstEmbed = parseVideoEmbed($firstVideo['video_url']);
?>
<section id="videos-section" class="video-showcase-section">
  <div class="container">
    <div class="section-header" style="text-align: center;">
      <div class="section-tag" style="background: rgba(249, 115, 22, 0.2); color: #ff9800;">Captation Aérienne & Suivi</div>
      <h2 class="section-title" style="color: #ffffff;">Nos Chantiers en Immersion Vidéo & Vues Drone</h2>
      <p class="section-subtitle" style="color: #cbd5e1;">
        Visualisez en direct l'avancement de nos travaux, les coulages de dalles et les visites aériennes de nos réalisations au Cameroun.
      </p>
    </div>

    <div class="video-showcase-grid">
      <!-- Lecteur Principal Actif -->
      <div class="main-video-wrapper">
        <div class="main-video-screen" id="mainVideoScreen">
          <?php echo $firstEmbed['embed_html'] ?? ''; ?>
        </div>
        <div class="main-video-meta">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span class="badge" style="background: var(--accent); color: #fff; font-size: 0.75rem;" id="mainVideoCategory">
              <?php echo htmlspecialchars($firstVideo['category']); ?>
            </span>
            <span style="color: #94a3b8; font-size: 0.82rem;" id="mainVideoLocation">
              <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($firstVideo['location']); ?>
            </span>
          </div>
          <h3 class="main-video-title" id="mainVideoTitle"><?php echo htmlspecialchars($firstVideo['title']); ?></h3>
          <p class="main-video-desc" id="mainVideoDesc"><?php echo htmlspecialchars($firstVideo['short_desc']); ?></p>
          <div style="margin-top: 15px;">
            <a href="project-details.php?id=<?php echo $firstVideo['id']; ?>" class="btn btn-outline-white btn-sm" id="mainVideoLink">
              <i class="fas fa-external-link-alt"></i> Consulter la fiche complète de ce chantier
            </a>
          </div>
        </div>
      </div>

      <!-- Playlist Interactive des Vidéos -->
      <div class="video-playlist-container">
        <div style="font-weight: 700; font-size: 0.95rem; color: #ffffff; margin-bottom: 5px; display: flex; align-items: center; justify-content: space-between;">
          <span><i class="fas fa-list" style="color: var(--accent); margin-right: 8px;"></i> Sélection de Reportages Vidéo</span>
          <span style="font-size: 0.8rem; color: #94a3b8;"><?php echo count($videoProjects); ?> Chantiers</span>
        </div>

        <?php foreach ($videoProjects as $idx => $vp): 
          $vEmbed = parseVideoEmbed($vp['video_url']);
          $thumb = !empty($vEmbed['thumb']) ? $vEmbed['thumb'] : $vp['main_image'];
        ?>
          <div class="video-playlist-item <?php echo ($idx === 0) ? 'active' : ''; ?>" 
               data-embed="<?php echo htmlspecialchars($vEmbed['embed_html'] ?? ''); ?>"
               data-title="<?php echo htmlspecialchars($vp['title']); ?>"
               data-desc="<?php echo htmlspecialchars($vp['short_desc']); ?>"
               data-category="<?php echo htmlspecialchars($vp['category']); ?>"
               data-location="<?php echo htmlspecialchars($vp['location']); ?>"
               data-link="project-details.php?id=<?php echo $vp['id']; ?>">
            <div class="playlist-thumb-wrapper">
              <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($vp['title']); ?>">
              <div class="playlist-thumb-play"><i class="fas fa-play"></i></div>
            </div>
            <div class="playlist-item-info">
              <h4 class="playlist-item-title"><?php echo htmlspecialchars($vp['title']); ?></h4>
              <div class="playlist-item-meta">
                <span><i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($vp['location']); ?></span>
                <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($vp['category']); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========================================================================
     7. ACTUALITÉS & VIE DE L'ENTREPRISE
     ======================================================================== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Blog & Informations</div>
      <h2 class="section-title">Actualités des Chantiers & Conseils BTP</h2>
      <p class="section-subtitle">
        Suivez les avancées de nos équipes et découvrez les bonnes pratiques de construction au Cameroun.
      </p>
    </div>

    <div class="news-grid">
      <?php foreach ($articles as $art): ?>
        <div class="news-card">
          <div class="news-img">
            <img src="<?php echo htmlspecialchars($art['image']); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>">
          </div>
          <div class="news-body">
            <div class="news-date">
              <i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($art['created_at'])); ?> • <?php echo htmlspecialchars($art['category']); ?>
            </div>
            <h3 class="news-title"><?php echo htmlspecialchars($art['title']); ?></h3>
            <p class="news-excerpt"><?php echo htmlspecialchars($art['summary']); ?></p>
            <a href="news-details.php?id=<?php echo $art['id']; ?>" class="service-link">
              Lire l'article complet <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     8. AVIS & TÉMOIGNAGES CLIENTS
     ======================================================================== -->
<section class="section" style="background-color: #f8fafc; border-top: 1px solid var(--border);">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Retours d'Expérience</div>
      <h2 class="section-title">Ce Que Disent Nos Clients</h2>
      <p class="section-subtitle">
        La satisfaction de nos partenaires et clients est le reflet direct de notre rigueur professionnelle.
      </p>
    </div>

    <div class="services-grid">
      <?php foreach ($testimonials as $t): ?>
        <div class="service-card" style="border-left: 4px solid var(--accent);">
          <div style="color: #f59e0b; margin-bottom: 12px; font-size: 1.1rem;">
            <?php for ($i = 0; $i < $t['rating']; $i++): ?>
              <i class="fas fa-star"></i>
            <?php endfor; ?>
          </div>
          <p style="font-style: italic; color: var(--dark); font-size: 0.95rem; margin-bottom: 20px; line-height: 1.6;">
            « <?php echo htmlspecialchars($t['comment']); ?> »
          </p>
          <div style="display: flex; align-items: center; gap: 12px; margin-top: auto;">
            <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
              <?php echo strtoupper(substr($t['client_name'], 0, 1)); ?>
            </div>
            <div>
              <h4 style="font-size: 0.95rem; color: var(--primary); margin-bottom: 2px;"><?php echo htmlspecialchars($t['client_name']); ?></h4>
              <span style="font-size: 0.8rem; color: var(--muted);"><?php echo htmlspecialchars($t['client_title'] ?? ''); ?> • <?php echo htmlspecialchars($t['company'] ?? ''); ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     9. BANNIÈRE D'APPEL À L'ACTION (CTA FINAL)
     ======================================================================== -->
<section style="background: linear-gradient(135deg, var(--accent), #e65100); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <h2 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 15px;">
      Vous Avez un Projet BTP à Dschang ou au Cameroun ?
    </h2>
    <p style="font-size: 1.15rem; max-width: 750px; margin: 0 auto 30px auto; color: #ffe0cc;">
      Contactez dès aujourd'hui la direction de <strong>FREE BTP SARL</strong> pour bénéficier d'une étude technique approfondie et d'un chiffrage sur mesure sans engagement.
    </p>
    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
      <a href="contact.php?type=devis" class="btn btn-secondary btn-lg">
        <i class="fas fa-file-invoice"></i> Obtenir mon Devis Gratuit
      </a>
      <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>?text=Bonjour,%20je%20souhaite%20un%20rendez-vous%20avec%20la%20direction%20FREE%20BTP." target="_blank" class="btn btn-whatsapp btn-lg">
        <i class="fab fa-whatsapp"></i> Échanger sur WhatsApp (+237 691 00 64 31)
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
