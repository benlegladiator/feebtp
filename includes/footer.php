<?php
// Footer Reusable Component
?>
  <!-- Pied de Page (Footer) -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1 : Entreprise -->
        <div class="footer-col">
          <h4>FREE BTP SARL</h4>
          <p style="color: #cbd5e1; font-size: 0.92rem; margin-bottom: 18px; line-height: 1.6;">
            Entreprise de référence dans le Bâtiment, les Travaux Publics, le Génie Civil, les Voiries (VRD) et la Rénovation immobilière au Cameroun. Siège social basé à Dschang, avec rayonnement sur tout le territoire national.
          </p>
          <div style="display: flex; gap: 10px;">
            <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>" target="_blank" class="btn btn-whatsapp btn-sm">
              <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="contact.php" class="btn btn-outline-white btn-sm">
              <i class="fas fa-envelope"></i> Nous écrire
            </a>
          </div>
        </div>

        <!-- Col 2 : Expertises -->
        <div class="footer-col">
          <h4>Nos Expertises</h4>
          <ul>
            <li><a href="services.php#gros-oeuvre"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Gros Œuvre & Maçonnerie</a></li>
            <li><a href="services.php#second-oeuvre"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Second Œuvre & Finitions</a></li>
            <li><a href="services.php#genie-civil"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Génie Civil & VRD</a></li>
            <li><a href="services.php#renovation"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Rénovation Immobilière</a></li>
            <li><a href="services.php#etudes"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Études & Architecture</a></li>
            <li><a href="services.php#pilotage"><i class="fas fa-angle-right" style="color: var(--accent); margin-right: 6px;"></i> Conduite de Travaux</a></li>
          </ul>
        </div>

        <!-- Col 3 : Liens Rapides -->
        <div class="footer-col">
          <h4>Navigation</h4>
          <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="about.php">À Propos de l'Entreprise</a></li>
            <li><a href="projects.php">Nos Réalisations & Chantiers</a></li>
            <li><a href="news.php">Actualités & Blog BTP</a></li>
            <li><a href="contact.php?type=devis">Demander un Devis en Ligne</a></li>
            <li><a href="contact.php">Localisation Siège (Dschang)</a></li>
            <li><a href="admin/login.php"><i class="fas fa-user-shield" style="color: var(--accent); margin-right: 6px;"></i> Espace d'Administration</a></li>
          </ul>
        </div>

        <!-- Col 4 : Coordonnées Officielles -->
        <div class="footer-col">
          <h4>Siège Social & Contact</h4>
          <p style="font-size: 0.9rem; margin-bottom: 8px;">
            <i class="fas fa-map-marker-alt" style="color: var(--accent); margin-right: 8px;"></i>
            <?php echo htmlspecialchars($siteSettings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun'); ?>
          </p>
          <p style="font-size: 0.9rem; margin-bottom: 8px;">
            <i class="fas fa-user-tie" style="color: var(--accent); margin-right: 8px;"></i>
            <strong>Gérant :</strong> <?php echo htmlspecialchars($siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE'); ?>
          </p>
          <p style="font-size: 0.9rem; margin-bottom: 8px;">
            <i class="fas fa-phone-alt" style="color: var(--accent); margin-right: 8px;"></i>
            <?php echo htmlspecialchars($siteSettings['phone_primary'] ?? '(+237) 691 00 64 31'); ?>
          </p>
          <p style="font-size: 0.9rem; margin-bottom: 8px;">
            <i class="fas fa-phone" style="color: var(--accent); margin-right: 8px;"></i>
            <?php echo htmlspecialchars($siteSettings['phone_secondary'] ?? '(+237) 671 06 34 41'); ?>
          </p>
          <p style="font-size: 0.9rem; margin-bottom: 8px;">
            <i class="fas fa-envelope" style="color: var(--accent); margin-right: 8px;"></i>
            <?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?>
          </p>
          <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 10px;">
            <i class="fas fa-clock" style="color: var(--accent); margin-right: 6px;"></i>
            <?php echo htmlspecialchars($siteSettings['opening_hours'] ?? 'Lun-Ven: 07h30-18h00 | Sam: 08h00-13h00'); ?>
          </p>
        </div>
      </div>

      <!-- Bas de page -->
      <div class="footer-bottom">
        <div>
          &copy; <?php echo date('Y'); ?> <strong>FREE BTP SARL</strong>. Tous droits réservés. Réf : <?php echo htmlspecialchars($siteSettings['contract_ref'] ?? 'CT-WEB-FREEBTP-2026-01'); ?>.
        </div>
        <div>
          Conception & Développement Web : <a href="tel:+237691670626"><strong>M. NDONGMO TEJIONA BEN ARTHUR</strong></a> (+237 691 67 06 26).
        </div>
      </div>
    </div>
  </footer>

  <!-- Inclusion du Widget Boîte de Dialogue & WhatsApp -->
  <?php require_once __DIR__ . '/chat-widget.php'; ?>

  <!-- Scripts JavaScript -->
  <script src="assets/js/main.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/main.js'); ?>"></script>
</body>
</html>
