<?php
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: projects.php');
    exit;
}

$pageTitle = $project['title'] . " - Fiche Chantier BTP";
require_once __DIR__ . '/includes/header.php';
?>

<div style="background: var(--primary); color: #ffffff; padding: 50px 0;">
  <div class="container">
    <div style="margin-bottom: 12px;">
      <a href="projects.php" style="color: #cbd5e1; font-size: 0.85rem;"><i class="fas fa-arrow-left"></i> Retour à toutes les réalisations</a>
    </div>
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;"><?php echo htmlspecialchars($project['category']); ?></div>
    <h1 style="font-size: 2.4rem; font-weight: 800; margin-bottom: 10px;"><?php echo htmlspecialchars($project['title']); ?></h1>
    <div style="display: flex; gap: 20px; color: #cbd5e1; font-size: 0.95rem; flex-wrap: wrap;">
      <span><i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($project['location']); ?></span>
      <span><i class="fas fa-calendar-alt" style="color: var(--accent);"></i> Durée : <?php echo htmlspecialchars($project['duration'] ?? 'N/A'); ?></span>
      <span class="badge <?php echo ($project['status'] === 'Terminé') ? 'status-completed' : 'status-ongoing'; ?>">
        <?php echo htmlspecialchars($project['status']); ?>
      </span>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="about-grid" style="align-items: flex-start; margin-bottom: 50px;">
      
      <!-- Colonne Visuel Principal -->
      <div>
        <div style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-lg); border: 1px solid var(--border); margin-bottom: 25px;">
          <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" style="width: 100%; height: auto;">
        </div>

        <?php if (!empty($project['video_url'])): 
          $videoData = parseVideoEmbed($project['video_url']);
        ?>
          <?php if ($videoData): ?>
            <div id="video" style="background: #ffffff; padding: 20px; border-radius: 10px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 25px;">
              <h4 style="color: var(--primary-dark); font-size: 1.1rem; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="fas fa-video" style="color: var(--accent); margin-right: 8px;"></i> Immersion Vidéo & Vues Drone</span>
                <span class="badge" style="background: var(--accent-light); color: var(--accent); font-size: 0.75rem;">HD 1080p</span>
              </h4>
              <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; background: #000;">
                <?php echo $videoData['embed_html']; ?>
              </div>
              <p style="font-size: 0.82rem; color: var(--muted); margin-top: 10px; margin-bottom: 0;">
                <i class="fas fa-info-circle"></i> Captation aérienne et suivi régulier de l'ouvrage par l'équipe FREE BTP SARL.
              </p>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Colonne Fiche Synthétique & Détails -->
      <div>
        <!-- Fiche technique -->
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid var(--border); padding: 30px; box-shadow: var(--shadow-sm); margin-bottom: 30px;">
          <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 20px;">
            <i class="fas fa-clipboard-list" style="color: var(--accent); margin-right: 8px;"></i> Caractéristiques du Projet
          </h3>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 0.92rem;">
            <div>
              <span style="color: var(--muted); display: block;">Maître d'Ouvrage :</span>
              <strong><?php echo htmlspecialchars($project['client_name'] ?? 'Client Privé'); ?></strong>
            </div>
            <div>
              <span style="color: var(--muted); display: block;">Localisation :</span>
              <strong><?php echo htmlspecialchars($project['location']); ?></strong>
            </div>
            <div>
              <span style="color: var(--muted); display: block;">Surface Bâtie :</span>
              <strong><?php echo htmlspecialchars($project['surface'] ?? 'N/A'); ?></strong>
            </div>
            <div>
              <span style="color: var(--muted); display: block;">Délai d'Exécution :</span>
              <strong><?php echo htmlspecialchars($project['duration'] ?? 'N/A'); ?></strong>
            </div>
            <div>
              <span style="color: var(--muted); display: block;">Budget Indicatif :</span>
              <strong style="color: var(--accent);"><?php echo htmlspecialchars($project['budget'] ?? 'Confidentiel'); ?></strong>
            </div>
            <div>
              <span style="color: var(--muted); display: block;">Statut de l'Ouvrage :</span>
              <strong><?php echo htmlspecialchars($project['status']); ?></strong>
            </div>
          </div>

          <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--border);">
            <a href="contact.php?type=devis&projet=<?php echo urlencode($project['title']); ?>" class="btn btn-primary" style="width: 100%;">
              <i class="fas fa-calculator"></i> Demander une étude similaire pour mon terrain
            </a>
          </div>
        </div>

        <!-- Descriptif détaillé -->
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid var(--border); padding: 30px; box-shadow: var(--shadow-sm);">
          <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-bottom: 15px;">
            Descriptif Technique des Travaux
          </h3>
          <p style="color: var(--muted); line-height: 1.8; font-size: 0.98rem;">
            <?php echo nl2br(htmlspecialchars($project['description'] ?? $project['short_desc'])); ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Comparatif Avant / Après si disponible -->
    <?php if (!empty($project['before_image'])): ?>
      <div id="avant-apres" style="margin-top: 40px; padding: 40px; background: #ffffff; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <div class="section-header" style="margin-bottom: 30px;">
          <div class="section-tag">Évolution du Chantier</div>
          <h2 class="section-title">Comparatif Avant & Après Exécution</h2>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div>
            <div style="font-weight: 700; color: var(--primary); margin-bottom: 8px;"><i class="fas fa-history" style="color: var(--muted);"></i> État Initial / Démarrage</div>
            <img src="<?php echo htmlspecialchars($project['before_image']); ?>" alt="Avant travaux" style="width: 100%; border-radius: 8px;">
          </div>
          <div>
            <div style="font-weight: 700; color: var(--accent); margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: var(--accent);"></i> Ouvrage Réalisé par FREE BTP</div>
            <img src="<?php echo htmlspecialchars($project['after_image'] ?? $project['main_image']); ?>" alt="Après travaux" style="width: 100%; border-radius: 8px;">
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
