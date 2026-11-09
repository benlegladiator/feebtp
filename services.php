<?php
$pageTitle = "Nos Services & Expertises BTP";
require_once __DIR__ . '/includes/header.php';

$servicesStmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
$services = $servicesStmt->fetchAll();
?>

<div style="background: var(--primary); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Savoir-Faire & Métiers</div>
    <h1 style="font-size: 2.6rem; font-weight: 800; margin-bottom: 12px;">Nos Services & Expertises BTP</h1>
    <p style="font-size: 1.1rem; color: #cbd5e1; max-width: 720px; margin: 0 auto;">
      De la conception technique à la réalisation sur le terrain, découvrez l'éventail complet de nos compétences au Cameroun.
    </p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div style="display: flex; flex-direction: column; gap: 60px;">
      <?php foreach ($services as $index => $s): ?>
        <div id="<?php echo htmlspecialchars($s['slug']); ?>" style="background: #ffffff; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
          <div style="display: grid; grid-template-columns: <?php echo ($index % 2 === 0) ? '1fr 1.2fr' : '1.2fr 1fr'; ?>; gap: 0; align-items: center;">
            
            <?php if ($index % 2 === 0): ?>
              <div style="height: 100%; min-height: 320px; background-color: var(--primary);">
                <img src="<?php echo htmlspecialchars($s['image'] ?? 'assets/images/service-gros-oeuvre.jpg'); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
            <?php endif; ?>

            <div style="padding: 40px;">
              <div style="width: 52px; height: 52px; background-color: var(--accent-light); color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px;">
                <i class="fas <?php echo htmlspecialchars($s['icon'] ?? 'fa-hard-hat'); ?>"></i>
              </div>
              <h2 style="color: var(--primary); font-size: 1.6rem; font-weight: 700; margin-bottom: 15px;"><?php echo htmlspecialchars($s['title']); ?></h2>
              <p style="color: var(--muted); font-size: 1rem; line-height: 1.7; margin-bottom: 20px;">
                <?php echo htmlspecialchars($s['full_desc'] ?? $s['short_desc']); ?>
              </p>
              
              <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="contact.php?type=devis&service=<?php echo urlencode($s['title']); ?>" class="btn btn-primary">
                  <i class="fas fa-calculator"></i> Demander une cotation pour ce service
                </a>
                <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>?text=Bonjour,%20je%20souhaite%20des%20informations%20sur%20le%20service%20:<?php echo urlencode($s['title']); ?>" target="_blank" class="btn btn-whatsapp">
                  <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
              </div>
            </div>

            <?php if ($index % 2 !== 0): ?>
              <div style="height: 100%; min-height: 320px; background-color: var(--primary);">
                <img src="<?php echo htmlspecialchars($s['image'] ?? 'assets/images/service-second-oeuvre.jpg'); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
            <?php endif; ?>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     NOTRE MÉTHODOLOGIE DE CHANTIER (4 ÉTAPES CLÉS)
     ======================================================================== -->
<section class="section" style="background-color: #f8fafc; border-top: 1px solid var(--border);">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Rigueur & Démarche Qualité</div>
      <h2 class="section-title">Comment Nous Réalisons Vos Travaux</h2>
      <p class="section-subtitle">
        Une méthodologie éprouvée pour garantir la solidité, la conformité technique et le respect des budgets convenus.
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
