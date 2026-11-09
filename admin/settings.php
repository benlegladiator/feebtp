<?php
$adminTitle = "Paramètres Généraux du Site & Coordonnées";
require_once __DIR__ . '/header.php';

$success = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_settings'])) {
    $fields = [
        'company_name', 'slogan', 'manager_name', 'manager_role', 'contract_ref',
        'address', 'city', 'country', 'phone_primary', 'phone_secondary',
        'whatsapp_number', 'email_contact', 'email_direction', 'opening_hours',
        'stats_experience', 'stats_projects', 'stats_satisfaction', 'stats_equipments',
        'about_summary'
    ];

    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value");
    // Fallback pour MySQL syntax
    $stmtMysql = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($fields as $field) {
        $val = trim($_POST[$field] ?? '');
        try {
            $stmt->execute([$field, $val]);
        } catch (Exception $e) {
            $stmtMysql->execute([$field, $val]);
        }
    }
    $success = "Les paramètres du site ont été mis à jour avec succès.";
    // Recharger
    $siteSettings = getSiteSettings($pdo);
}
?>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3><i class="fas fa-sliders-h" style="color: var(--admin-accent); margin-right: 8px;"></i> Configuration Générale de FREE BTP SARL</h3>
  </div>

  <div class="admin-card-body">
    <form action="settings.php" method="POST">
      <input type="hidden" name="save_settings" value="1">

      <!-- Section 1 : Identité & Direction -->
      <h4 style="font-size: 1.05rem; color: var(--admin-primary); margin-bottom: 15px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">
        1. Identité de l'Entreprise & Direction
      </h4>
      <div class="form-grid" style="margin-bottom: 25px;">
        <div class="form-group">
          <label>Raison Sociale</label>
          <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($siteSettings['company_name'] ?? 'FREE BTP SARL'); ?>" required>
        </div>
        <div class="form-group">
          <label>Slogan Officiel</label>
          <input type="text" name="slogan" class="form-control" value="<?php echo htmlspecialchars($siteSettings['slogan'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Nom du Gérant / Directeur Général</label>
          <input type="text" name="manager_name" class="form-control" value="<?php echo htmlspecialchars($siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE'); ?>" required>
        </div>
        <div class="form-group">
          <label>Titre / Fonction de la Direction</label>
          <input type="text" name="manager_role" class="form-control" value="<?php echo htmlspecialchars($siteSettings['manager_role'] ?? 'Gérant / Directeur Général'); ?>" required>
        </div>
        <div class="form-group">
          <label>Référence Contrat</label>
          <input type="text" name="contract_ref" class="form-control" value="<?php echo htmlspecialchars($siteSettings['contract_ref'] ?? 'CT-WEB-FREEBTP-2026-01'); ?>">
        </div>
      </div>

      <!-- Section 2 : Coordonnées Officielles -->
      <h4 style="font-size: 1.05rem; color: var(--admin-primary); margin-bottom: 15px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">
        2. Coordonnées de Contact & Localisation (Dschang)
      </h4>
      <div class="form-grid" style="margin-bottom: 25px;">
        <div class="form-group">
          <label>Adresse du Siège Social</label>
          <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($siteSettings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun'); ?>" required>
        </div>
        <div class="form-group">
          <label>Téléphone Principal (Standard)</label>
          <input type="text" name="phone_primary" class="form-control" value="<?php echo htmlspecialchars($siteSettings['phone_primary'] ?? '(+237) 691 00 64 31'); ?>" required>
        </div>
        <div class="form-group">
          <label>Téléphone Secondaire (Direction)</label>
          <input type="text" name="phone_secondary" class="form-control" value="<?php echo htmlspecialchars($siteSettings['phone_secondary'] ?? '(+237) 671 06 34 41'); ?>" required>
        </div>
        <div class="form-group">
          <label>Numéro WhatsApp (Format international sans +)</label>
          <input type="text" name="whatsapp_number" class="form-control" value="<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>" required>
        </div>
        <div class="form-group">
          <label>Email Commercial & Devis</label>
          <input type="email" name="email_contact" class="form-control" value="<?php echo htmlspecialchars($siteSettings['email_contact'] ?? 'contact@freebtp-sarl.com'); ?>" required>
        </div>
        <div class="form-group">
          <label>Email Direction</label>
          <input type="email" name="email_direction" class="form-control" value="<?php echo htmlspecialchars($siteSettings['email_direction'] ?? 'direction@freebtp-sarl.com'); ?>" required>
        </div>
        <div class="form-group">
          <label>Horaires d'Ouverture des Bureaux</label>
          <input type="text" name="opening_hours" class="form-control" value="<?php echo htmlspecialchars($siteSettings['opening_hours'] ?? 'Lun - Ven : 07h30 - 18h00 | Sam : 08h00 - 13h00'); ?>">
        </div>
      </div>

      <!-- Section 3 : Chiffres Clés -->
      <h4 style="font-size: 1.05rem; color: var(--admin-primary); margin-bottom: 15px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">
        3. Chiffres Clés & Statistiques du Site
      </h4>
      <div class="form-grid" style="margin-bottom: 25px;">
        <div class="form-group">
          <label>Années d'Expérience</label>
          <input type="text" name="stats_experience" class="form-control" value="<?php echo htmlspecialchars($siteSettings['stats_experience'] ?? '12'); ?>">
        </div>
        <div class="form-group">
          <label>Nombre de Chantiers Livrés</label>
          <input type="text" name="stats_projects" class="form-control" value="<?php echo htmlspecialchars($siteSettings['stats_projects'] ?? '165'); ?>">
        </div>
        <div class="form-group">
          <label>Taux de Satisfaction Client (%)</label>
          <input type="text" name="stats_satisfaction" class="form-control" value="<?php echo htmlspecialchars($siteSettings['stats_satisfaction'] ?? '99'); ?>">
        </div>
        <div class="form-group">
          <label>Nombre d'Engins & Équipements</label>
          <input type="text" name="stats_equipments" class="form-control" value="<?php echo htmlspecialchars($siteSettings['stats_equipments'] ?? '48'); ?>">
        </div>
      </div>

      <!-- Section 4 : Présentation synthétique -->
      <h4 style="font-size: 1.05rem; color: var(--admin-primary); margin-bottom: 15px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">
        4. Textes de Présentation
      </h4>
      <div class="form-group">
        <label>Résumé "À Propos" (Pied de page & Accueil)</label>
        <textarea name="about_summary" class="form-control" rows="3"><?php echo htmlspecialchars($siteSettings['about_summary'] ?? ''); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i> Enregistrer Tous les Paramètres
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
