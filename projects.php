<?php
$pageTitle = "Nos Réalisations & Chantiers BTP";
require_once __DIR__ . '/includes/header.php';

$projectsStmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
$projects = $projectsStmt->fetchAll();
?>

<div style="background: var(--primary); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Galerie & Références</div>
    <h1 style="font-size: 2.6rem; font-weight: 800; margin-bottom: 12px;">Nos Réalisations & Chantiers BTP</h1>
    <p style="font-size: 1.1rem; color: #cbd5e1; max-width: 720px; margin: 0 auto;">
      Explorez nos ouvrages livrés et en cours d'exécution à Dschang, dans les Régions de l'Ouest, du Centre et du Littoral.
    </p>
  </div>
</div>

<section class="section">
  <div class="container">
    <!-- Filtres par catégorie -->
    <div class="project-filters">
      <button class="filter-btn active" data-filter="all">Tous les Projets</button>
      <button class="filter-btn" data-filter="Résidentiel">Résidentiel</button>
      <button class="filter-btn" data-filter="Commercial">Commercial</button>
      <button class="filter-btn" data-filter="Travaux Publics / VRD">Travaux Publics / VRD</button>
      <button class="filter-btn" data-filter="Industriel">Industriel</button>
    </div>

    <!-- Grille de projets -->
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
            <?php if (!empty($proj['video_url'])): ?>
              <span class="project-video-tag" title="Vidéo & Drone disponible">
                <i class="fas fa-play"></i> Vidéo
              </span>
            <?php endif; ?>
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
              <span><strong>Surface :</strong> <?php echo htmlspecialchars($proj['surface'] ?? 'N/A'); ?></span>
            </div>
            
            <div style="margin-top: 15px; display: flex; gap: 8px;">
              <a href="project-details.php?id=<?php echo $proj['id']; ?>" class="btn btn-primary btn-sm" style="flex-grow: 1;">
                <i class="fas fa-eye"></i> Fiche Complète
              </a>
              <?php if (!empty($proj['video_url'])): ?>
                <a href="project-details.php?id=<?php echo $proj['id']; ?>#video" class="btn btn-sm" style="background-color: var(--accent); color: #fff;" title="Regarder la vidéo drone">
                  <i class="fas fa-play"></i> Vidéo
                </a>
              <?php endif; ?>
              <?php if (!empty($proj['before_image'])): ?>
                <a href="project-details.php?id=<?php echo $proj['id']; ?>#avant-apres" class="btn btn-outline btn-sm" title="Voir Avant/Après">
                  <i class="fas fa-columns"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
