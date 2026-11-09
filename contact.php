<?php
$pageTitle = "Contact & Localisation du Siège à Dschang";
require_once __DIR__ . '/includes/header.php';

$requestedType = $_GET['type'] ?? 'contact';
$prefillService = $_GET['service'] ?? '';
$prefillProject = $_GET['projet'] ?? '';
$hasSuccess = isset($_GET['success']);
?>

<div style="background: var(--primary); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Échange Direct</div>
    <h1 style="font-size: 2.6rem; font-weight: 800; margin-bottom: 12px;">Contactez FREE BTP SARL</h1>
    <p style="font-size: 1.1rem; color: #cbd5e1; max-width: 720px; margin: 0 auto;">
      Nos bureaux d'études et notre direction générale vous accueillent à Dschang. Envoyez-nous votre demande de cotation ou vos plans.
    </p>
  </div>
</div>

<section class="section">
  <div class="container">

    <?php if ($hasSuccess && isset($_SESSION['form_success'])): ?>
      <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 18px 24px; border-radius: 8px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-check-circle" style="font-size: 1.6rem;"></i>
        <div>
          <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 2px;">Demande Enregistrée avec Succès !</h4>
          <p style="font-size: 0.92rem;"><?php echo htmlspecialchars($_SESSION['form_success']); unset($_SESSION['form_success']); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['form_error'])): ?>
      <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 18px 24px; border-radius: 8px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 1.6rem;"></i>
        <div>
          <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 2px;">Attention</h4>
          <p style="font-size: 0.92rem;"><?php echo htmlspecialchars($_SESSION['form_error']); unset($_SESSION['form_error']); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <div class="contact-grid">
      <!-- Colonne Coordonnées Officielles -->
      <div>
        <div class="contact-info-card">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 12px;">
            Coordonnées Officielles du Siège
          </h3>

          <div class="contact-info-item">
            <i class="fas fa-building"></i>
            <div>
              <h4>FREE BTP SARL</h4>
              <p>Société à Responsabilité Limitée au capital de BTP</p>
              <p>Réf Contrat : <?php echo htmlspecialchars($siteSettings['contract_ref'] ?? 'CT-WEB-FREEBTP-2026-01'); ?></p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fas fa-user-tie"></i>
            <div>
              <h4>Direction Générale</h4>
              <p><?php echo htmlspecialchars($siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE'); ?></p>
              <p style="font-size: 0.8rem; color: #cbd5e1;"><?php echo htmlspecialchars($siteSettings['manager_role'] ?? 'Gérant / Directeur Général'); ?></p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fas fa-map-marker-alt"></i>
            <div>
              <h4>Adresse & Localisation</h4>
              <p><?php echo htmlspecialchars($siteSettings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun'); ?></p>
              <p style="font-size: 0.8rem; color: #cbd5e1;">Intervention : Ouest, Centre, Littoral et Sud</p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fas fa-phone-alt"></i>
            <div>
              <h4>Lignes Téléphoniques</h4>
              <p><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $siteSettings['phone_primary'] ?? '+237691006431'); ?>"><?php echo htmlspecialchars($siteSettings['phone_primary'] ?? '(+237) 691 00 64 31'); ?></a></p>
              <p><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $siteSettings['phone_secondary'] ?? '+237671063441'); ?>"><?php echo htmlspecialchars($siteSettings['phone_secondary'] ?? '(+237) 671 06 34 41'); ?></a></p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fas fa-envelope"></i>
            <div>
              <h4>Courriers Électroniques</h4>
              <p><a href="mailto:<?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?>"><?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?></a></p>
              <p><a href="mailto:<?php echo htmlspecialchars($siteSettings['email_direction'] ?? 'direction@freebtp-sarl.com'); ?>"><?php echo htmlspecialchars($siteSettings['email_direction'] ?? 'direction@freebtp-sarl.com'); ?></a></p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fab fa-whatsapp"></i>
            <div>
              <h4>WhatsApp Commercial & Direction</h4>
              <p><a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>" target="_blank" style="color: #25d366; font-weight: 700;">+237 691 00 64 31 (Messagerie directe)</a></p>
            </div>
          </div>

          <div class="contact-info-item">
            <i class="fas fa-clock"></i>
            <div>
              <h4>Horaires d'Ouverture</h4>
              <p><?php echo htmlspecialchars($siteSettings['opening_hours'] ?? 'Lun - Ven : 07h30 - 18h00 | Sam : 08h00 - 13h00'); ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Colonne Formulaire de Contact & Devis avec Pièces Jointes -->
      <div>
        <div class="contact-form-card">
          <div style="margin-bottom: 25px;">
            <h3 style="color: var(--primary); font-size: 1.35rem; font-weight: 700; margin-bottom: 6px;">
              <?php echo ($requestedType === 'devis') ? 'Formulaire de Demande de Devis Détaillé' : 'Envoyer un Message à FREE BTP'; ?>
            </h3>
            <p style="color: var(--muted); font-size: 0.9rem;">
              Joignez vos plans architecturaux, devis existants ou photos du terrain au format PDF ou image.
            </p>
          </div>

          <form action="api/contact.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($requestedType); ?>">

            <div class="form-group">
              <label><i class="fas fa-user"></i> Nom Complet / Entreprise <span style="color:red;">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="M. / Mme / Société" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
              <div class="form-group">
                <label><i class="fas fa-phone"></i> Téléphone / WhatsApp <span style="color:red;">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="(+237) 6XX XX XX XX" required>
              </div>
              <div class="form-group">
                <label><i class="fas fa-envelope"></i> Adresse Email</label>
                <input type="email" name="email" class="form-control" placeholder="votre-email@domaine.cm">
              </div>
            </div>

            <div class="form-group">
              <label><i class="fas fa-hard-hat"></i> Service ou Expertise BTP concernée</label>
              <select name="service" class="form-control">
                <option value="">Sélectionnez un service...</option>
                <option value="Gros Œuvre & Maçonnerie Générale" <?php echo ($prefillService === 'Gros Œuvre & Maçonnerie Générale') ? 'selected' : ''; ?>>Gros Œuvre & Maçonnerie Générale</option>
                <option value="Second Œuvre & Finitions Haut Standing" <?php echo ($prefillService === 'Second Œuvre & Finitions Haut Standing') ? 'selected' : ''; ?>>Second Œuvre & Finitions Haut Standing</option>
                <option value="Génie Civil & VRD (Voiries et Réseaux)" <?php echo ($prefillService === 'Génie Civil & VRD (Voiries et Réseaux)') ? 'selected' : ''; ?>>Génie Civil & VRD (Voiries et Réseaux)</option>
                <option value="Rénovation & Réhabilitation Immobilière" <?php echo ($prefillService === 'Rénovation & Réhabilitation Immobilière') ? 'selected' : ''; ?>>Rénovation & Réhabilitation Immobilière</option>
                <option value="Études Techniques, Plans & Architecture" <?php echo ($prefillService === 'Études Techniques, Plans & Architecture') ? 'selected' : ''; ?>>Études Techniques, Plans & Architecture</option>
                <option value="Conduite de Travaux & Pilotage de Chantier" <?php echo ($prefillService === 'Conduite de Travaux & Pilotage de Chantier') ? 'selected' : ''; ?>>Conduite de Travaux & Pilotage de Chantier</option>
                <option value="Autre demande">Autre prestation</option>
              </select>
            </div>

            <div class="form-group">
              <label><i class="fas fa-tag"></i> Objet du Message</label>
              <input type="text" name="subject" class="form-control" value="<?php echo !empty($prefillProject) ? 'Étude similaire au projet : ' . htmlspecialchars($prefillProject) : ''; ?>" placeholder="Ex: Devis construction villa R+1 à Dschang">
            </div>

            <div class="form-group">
              <label><i class="fas fa-comment-dots"></i> Descriptif de votre besoin <span style="color:red;">*</span></label>
              <textarea name="message" class="form-control" rows="4" placeholder="Précisez la situation géographique du terrain, la superficie approximative, les délais envisagés..." required></textarea>
            </div>

            <div class="form-group" style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px dashed var(--border);">
              <label><i class="fas fa-paperclip"></i> Joindre un fichier (Plan 2D/3D, Devis, Cahier des charges, Photo)</label>
              <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.dwg,.zip">
              <small style="color: var(--muted); font-size: 0.78rem; display: block; margin-top: 4px;">Formats acceptés : PDF, Images (JPG, PNG), Word, AutoCAD (DWG) - Taille max : 10 Mo.</small>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
              <i class="fas fa-paper-plane"></i> Envoyer ma demande à FREE BTP SARL
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Carte Google Maps Intégrée (Dschang, Ouest Cameroun) -->
    <div style="margin-top: 50px; background: #ffffff; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-sm);">
      <div style="padding: 20px 25px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
        <h4 style="color: var(--primary); font-size: 1.1rem; font-weight: 700;">
          <i class="fas fa-map-marked-alt" style="color: var(--accent); margin-right: 8px;"></i>
          Localisation Géographique - Siège Social à Dschang (Cameroun)
        </h4>
        <span style="font-size: 0.85rem; color: var(--muted);">Dschang, Menoua, Région de l'Ouest</span>
      </div>
      <div style="width: 100%; height: 380px;">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31792.83188981604!2d10.03816654261763!3d5.449645938448981!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x105fc49c81912f27%3A0xe6bf4b693e46ca79!2sDschang!5e0!3m2!1sfr!2scm!4v1700000000000!5m2!1sfr!2scm" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
