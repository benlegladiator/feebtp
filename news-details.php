<?php
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: news.php');
    exit;
}

// Incrémenter les vues
$pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$id]);

$pageTitle = $article['title'];
require_once __DIR__ . '/includes/header.php';
?>

<div style="background: var(--primary); color: #ffffff; padding: 50px 0;">
  <div class="container">
    <div style="margin-bottom: 12px;">
      <a href="news.php" style="color: #cbd5e1; font-size: 0.85rem;"><i class="fas fa-arrow-left"></i> Retour aux actualités</a>
    </div>
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;"><?php echo htmlspecialchars($article['category']); ?></div>
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; max-width: 900px;"><?php echo htmlspecialchars($article['title']); ?></h1>
    <div style="color: #cbd5e1; font-size: 0.9rem;">
      <span><i class="fas fa-calendar-alt" style="color: var(--accent);"></i> Publié le <?php echo date('d F Y', strtotime($article['created_at'])); ?></span>
      <span style="margin: 0 10px;">•</span>
      <span><i class="fas fa-user-edit" style="color: var(--accent);"></i> Par <?php echo htmlspecialchars($article['author'] ?? 'Direction FREE BTP'); ?></span>
      <span style="margin: 0 10px;">•</span>
      <span><i class="fas fa-eye" style="color: var(--accent);"></i> <?php echo $article['views'] + 1; ?> lectures</span>
    </div>
  </div>
</div>

<section class="section">
  <div class="container" style="max-width: 860px;">
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-sm); padding: 35px;">
      
      <div style="margin-bottom: 30px; border-radius: 8px; overflow: hidden;">
        <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="width: 100%; height: auto;">
      </div>

      <div style="font-size: 1.15rem; font-weight: 600; color: var(--primary); margin-bottom: 25px; line-height: 1.6; border-left: 4px solid var(--accent); padding-left: 18px;">
        <?php echo htmlspecialchars($article['summary']); ?>
      </div>

      <div style="color: var(--dark); line-height: 1.8; font-size: 1.02rem;">
        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
      </div>

      <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <a href="news.php" class="btn btn-outline btn-sm">
          <i class="fas fa-arrow-left"></i> Toutes les actualités
        </a>
        <a href="contact.php?type=devis" class="btn btn-primary btn-sm">
          <i class="fas fa-hard-hat"></i> Parler de votre projet à notre équipe
        </a>
      </div>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
