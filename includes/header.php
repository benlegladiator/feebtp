<?php
require_once __DIR__ . '/../config/db.php';
$pdo = getDBConnection();
$siteSettings = getSiteSettings($pdo);

// Déterminer la page courante pour la classe 'active'
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . htmlspecialchars($siteSettings['company_name'] ?? 'FREE BTP SARL') : htmlspecialchars($siteSettings['company_name'] ?? 'FREE BTP SARL') . ' - Bâtiment, Génie Civil & Travaux Publics à Dschang'; ?></title>
  <meta name="description" content="FREE BTP SARL - Entreprise de BTP, Construction, Génie Civil, VRD et Rénovation à Dschang et au Cameroun. Direction M. AGHOKENG TANGMO Fabrice.">
  
  <!-- Font Awesome 6 CDN pour icônes professionnelles BTP -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- CSS Principal -->
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
</head>
<body>

  <!-- Barre Supérieure d'Informations (Top Bar) -->
  <div class="top-bar">
    <div class="container">
      <div class="top-info">
        <span><i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($siteSettings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun'); ?></span>
        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $siteSettings['phone_primary'] ?? '+237691006431'); ?>"><i class="fas fa-phone-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($siteSettings['phone_primary'] ?? '(+237) 691 00 64 31'); ?></a>
        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $siteSettings['phone_secondary'] ?? '+237671063441'); ?>"><i class="fas fa-phone" style="color: var(--accent);"></i> <?php echo htmlspecialchars($siteSettings['phone_secondary'] ?? '(+237) 671 06 34 41'); ?></a>
        <a href="mailto:<?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?>"><i class="fas fa-envelope" style="color: var(--accent);"></i> <?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?></a>
      </div>
      <div class="top-actions">
        <span><i class="fas fa-clock" style="color: var(--accent);"></i> <?php echo htmlspecialchars($siteSettings['opening_hours'] ?? '07h30 - 18h00'); ?></span>
        <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>?text=Bonjour%20FREE%20BTP,%20je%20souhaite%20des%20informations%20sur%20vos%20travaux." target="_blank" class="btn btn-whatsapp btn-sm">
          <i class="fab fa-whatsapp"></i> WhatsApp Direct
        </a>
      </div>
    </div>
  </div>

  <!-- En-tête Principal & Navigation -->
  <header class="main-header">
    <div class="container">
      <nav class="navbar">
        <a href="index.php" class="brand-logo">
          <div class="logo-icon">
            <i class="fas fa-hard-hat"></i>
          </div>
          <div class="logo-text">
            <span class="logo-title">FREE <span>BTP</span> SARL</span>
            <span class="logo-subtitle">Génie Civil & Bâtiment • Dschang</span>
          </div>
        </a>

        <!-- Liens de navigation -->
        <div class="nav-links">
          <a href="index.php" class="nav-link <?php echo ($currentPage === 'index') ? 'active' : ''; ?>">Accueil</a>
          <a href="about.php" class="nav-link <?php echo ($currentPage === 'about') ? 'active' : ''; ?>">À Propos</a>
          <a href="services.php" class="nav-link <?php echo ($currentPage === 'services') ? 'active' : ''; ?>">Nos Services</a>
          <a href="projects.php" class="nav-link <?php echo ($currentPage === 'projects' || $currentPage === 'project-details') ? 'active' : ''; ?>">Nos Réalisations</a>
          <a href="news.php" class="nav-link <?php echo ($currentPage === 'news' || $currentPage === 'news-details') ? 'active' : ''; ?>">Actualités</a>
          <a href="contact.php" class="nav-link <?php echo ($currentPage === 'contact') ? 'active' : ''; ?>">Contact</a>
          <a href="admin/login.php" class="btn btn-secondary btn-sm" style="margin-left: 10px;">
            <i class="fas fa-lock"></i> Espace Admin
          </a>
        </div>

        <div class="nav-cta">
          <a href="contact.php?type=devis" class="btn btn-primary">
            <i class="fas fa-file-signature"></i> Devis Gratuit
          </a>
          <button class="mobile-menu-btn" aria-label="Menu Mobile">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </nav>
    </div>
  </header>
