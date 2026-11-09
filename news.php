<?php
$pageTitle = "Actualités & Conseils BTP";
require_once __DIR__ . '/includes/header.php';

$articlesStmt = $pdo->query("SELECT * FROM articles WHERE is_published = 1 ORDER BY id DESC");
$articles = $articlesStmt->fetchAll();
?>

<div style="background: var(--primary); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Blog & Chantiers</div>
    <h1 style="font-size: 2.6rem; font-weight: 800; margin-bottom: 12px;">Actualités & Conseils de Construction</h1>
    <p style="font-size: 1.1rem; color: #cbd5e1; max-width: 720px; margin: 0 auto;">
      Suivez les projets en cours chez FREE BTP SARL et profitez de l'expertise de nos ingénieurs en génie civil.
    </p>
  </div>
</div>

<section class="section">
  <div class="container">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
