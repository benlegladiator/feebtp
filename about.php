<?php
$pageTitle = "À Propos - Histoire, Direction & Parcs d'Engins";
require_once __DIR__ . '/includes/header.php';
?>

<div style="background: var(--primary); color: #ffffff; padding: 60px 0; text-align: center;">
  <div class="container">
    <div class="section-tag" style="background: rgba(243,108,33,0.2); color: #ff9800;">Présentation Officielle</div>
    <h1 style="font-size: 2.6rem; font-weight: 800; margin-bottom: 12px;">À Propos de FREE BTP SARL</h1>
    <p style="font-size: 1.1rem; color: #cbd5e1; max-width: 700px; margin: 0 auto;">
      Bâtisseur de confiance à Dschang, dans la Région de l'Ouest et sur l'ensemble du territoire camerounais.
    </p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="about-grid">
      <div>
        <div class="section-tag">Notre Histoire & Implantation</div>
        <h2 class="section-title">Plus d'une Décennie au Service du Développement Urbain et BTP</h2>
        <p style="color: var(--muted); margin-bottom: 18px; font-size: 1.02rem; line-height: 1.7;">
          Fondée avec la volonté résolue de combler le déficit d'infrastructures pérennes et conformes aux règles de l'art dans les hauts plateaux de l'Ouest et au-delà, <strong>FREE BTP SARL</strong> s'est développée autour de valeurs d'intégrité, de rigueur technique et de respect inconditionnel des engagements contractuels.
        </p>
        <p style="color: var(--muted); margin-bottom: 18px; font-size: 1.02rem; line-height: 1.7;">
          Depuis son siège historique à <strong>Dschang</strong>, notre société coordonne des chantiers d'envergure : construction de résidences modernes, complexes scolaires et universitaires, hangars industriels et plateformes logistiques, voiries urbaines pavées et réseaux d'assainissement (VRD).
        </p>
        <p style="color: var(--muted); margin-bottom: 25px; font-size: 1.02rem; line-height: 1.7;">
          Notre rayonnement géographique s'étend aujourd'hui des départements de la Menoua et des Bamboutos jusqu'aux métropoles économiques de <strong>Yaoundé</strong>, <strong>Douala</strong>, <strong>Bafoussam</strong> et <strong>Kribi</strong>.
        </p>
        
        <div style="background: #f8fafc; padding: 20px; border-left: 4px solid var(--accent); border-radius: 6px;">
          <h4 style="color: var(--primary); margin-bottom: 5px;"><i class="fas fa-quote-left" style="color: var(--accent); margin-right: 8px;"></i> Notre Raison d'Être</h4>
          <p style="font-style: italic; color: var(--dark); font-size: 0.95rem;">
            « Concevoir et bâtir des ouvrages sûrs, durables et élégants qui traversent les générations, en garantissant à chaque maître d'ouvrage une tranquillité d'esprit absolue du terrassement jusqu'à la réception. »
          </p>
        </div>
      </div>

      <div>
        <div style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-lg); border: 1px solid var(--border);">
          <img src="assets/images/service-pilotage.jpg" alt="Siège FREE BTP SARL Dschang" style="width: 100%; height: auto;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     DIRECTION GÉNÉRALE & VALEURS DU MANAGEMENT
     ======================================================================== -->
<section class="section" style="background-color: #f1f5f9;">
  <div class="container">
    <div class="about-grid" style="grid-template-columns: 0.8fr 1.2fr;">
      <div>
        <div style="background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: var(--shadow-md); text-align: center; border-top: 5px solid var(--accent);">
          <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--primary); color: #fff; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
            <i class="fas fa-user-tie"></i>
          </div>
          <h3 style="color: var(--primary); font-size: 1.35rem; margin-bottom: 4px;"><?php echo htmlspecialchars($siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE'); ?></h3>
          <span style="color: var(--accent); font-weight: 700; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <?php echo htmlspecialchars($siteSettings['manager_role'] ?? 'Gérant / Directeur Général'); ?>
          </span>
          <p style="color: var(--muted); font-size: 0.88rem; margin-top: 15px; line-height: 1.6;">
            Leader pragmatique et visionnaire, M. AGHOKENG insuffle une culture d'exigence technique, de transparence financière et d'innovation opérationnelle sur tous les sites gérés par FREE BTP SARL.
          </p>
          <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border); font-size: 0.85rem; color: var(--muted);">
            <div><i class="fas fa-phone-alt" style="color: var(--accent);"></i> (+237) 691 00 64 31 / 671 06 34 41</div>
            <div style="margin-top: 5px;"><i class="fas fa-envelope" style="color: var(--accent);"></i> direction@freebtp-sarl.com</div>
          </div>
        </div>
      </div>

      <div>
        <div class="section-tag">Gouvernance & Valeurs</div>
        <h2 class="section-title">Les 4 Piliers Inébranlables de FREE BTP</h2>
        <p style="color: var(--muted); margin-bottom: 25px;">
          Notre démarche de management repose sur une responsabilité contractuelle rigoureuse et une conformité sans compromis avec les normes techniques internationales.
        </p>

        <div style="display: flex; flex-direction: column; gap: 20px;">
          <div style="background: #ffffff; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary); display: flex; gap: 15px; align-items: flex-start;">
            <div style="font-size: 1.5rem; color: var(--accent);"><i class="fas fa-ruler-combined"></i></div>
            <div>
              <h4 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 4px;">1. Précision Technique et Études Préalables</h4>
              <p style="color: var(--muted); font-size: 0.9rem;">Aucun ouvrage n'est exécuté sans sondage de sol géotechnique, calcul de résistance des matériaux et plans de ferraillage validés.</p>
            </div>
          </div>

          <div style="background: #ffffff; padding: 20px; border-radius: 8px; border-left: 4px solid var(--accent); display: flex; gap: 15px; align-items: flex-start;">
            <div style="font-size: 1.5rem; color: var(--accent);"><i class="fas fa-calendar-check"></i></div>
            <div>
              <h4 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 4px;">2. Respect Inviolable des Échéances</h4>
              <p style="color: var(--muted); font-size: 0.9rem;">Chaque semaine donne lieu à un point de contrôle et à l'actualisation du diagramme de Gantt pour proscrire tout glissement de calendrier.</p>
            </div>
          </div>

          <div style="background: #ffffff; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary); display: flex; gap: 15px; align-items: flex-start;">
            <div style="font-size: 1.5rem; color: var(--accent);"><i class="fas fa-hard-hat"></i></div>
            <div>
              <h4 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 4px;">3. Politique Hygiène, Sécurité et Environnement (HSE)</h4>
              <p style="color: var(--muted); font-size: 0.9rem;">Équipements de protection individuelle (EPI) obligatoires sur site, balisage sécuritaire et gestion responsable des déchets de chantier.</p>
            </div>
          </div>

          <div style="background: #ffffff; padding: 20px; border-radius: 8px; border-left: 4px solid var(--accent); display: flex; gap: 15px; align-items: flex-start;">
            <div style="font-size: 1.5rem; color: var(--accent);"><i class="fas fa-handshake"></i></div>
            <div>
              <h4 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 4px;">4. Clarté et Transparence Financière</h4>
              <p style="color: var(--muted); font-size: 0.9rem;">Devis détaillés ligne par ligne (quantitatif et estimatif), sans coûts cachés, avec échéancier de facturation lié aux jalons physiques constatés.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     PARCS DE MATÉRIELS & ENGINS LOURDS BTP
     ======================================================================== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Puissance Opérationnelle</div>
      <h2 class="section-title">Nos Parcs d'Engins & Équipements BTP</h2>
      <p class="section-subtitle">
        L'autonomie matérielle est la clé de notre réactivité. FREE BTP possède et entretient sa propre flotte d'engins lourds.
      </p>
    </div>

    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon"><i class="fas fa-truck-monster"></i></div>
        <h3 class="service-title">Terrassement & Fouilles</h3>
        <p class="service-desc">
          Pelles hydrauliques sur chenilles, tractopelles mixtes, chargeuses compactes et niveleuses pour excavations massives et déblais/remblais.
        </p>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-truck-moving"></i></div>
        <h3 class="service-title">Transport Logistique & Bennes</h3>
        <p class="service-desc">
          Flotte de camions bennes 6x4 et 8x4 pour l'acheminement direct des agrégats, sable de carrière, ciment et évacuation des gravats.
        </p>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-cubes"></i></div>
        <h3 class="service-title">Production & Coulage de Béton</h3>
        <p class="service-desc">
          Bétonnières thermiques haute capacité, vibreurs pneumatiques à béton, bennes de grutage et pompes à béton pour dalles et poteaux.
        </p>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-road"></i></div>
        <h3 class="service-title">Compactage & Voiries (VRD)</h3>
        <p class="service-desc">
          Rouleaux compacteurs tandem vibrants, plaques vibrantes et dames sauteuses garantissant un indice CBR optimal pour chaussées et dallages.
        </p>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-drafting-compass"></i></div>
        <h3 class="service-title">Topographie & Contrôle Laser</h3>
        <p class="service-desc">
          Stations totales électroniques, niveaux de chantier haute précision, mires laser et récepteurs GPS différentiels pour implantations millimétriques.
        </p>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-layer-group"></i></div>
        <h3 class="service-title">Échafaudages & Sécurité en Hauteur</h3>
        <p class="service-desc">
          Plus de 3 000 m² d'échafaudages tubulaires métalliques certifiés, étais télescopiques lourds, coffrages métalliques et filets de protection.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     ÉQUIPE TECHNIQUE & ENCADREMENT DE CHANTIER
     ======================================================================== -->
<section class="section" style="background-color: #f8fafc; border-top: 1px solid var(--border);">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Compétences Humaines</div>
      <h2 class="section-title">Notre Équipe Technique & Encadrement</h2>
      <p class="section-subtitle">
        Des ingénieurs chevronnés, des conducteurs de travaux rigoureux et des chefs de chantier engagés pour l'excellence de vos réalisations.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
      <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); text-align: center;">
        <div style="height: 280px; overflow: hidden;">
          <img src="assets/images/team-1.jpg" alt="Ingénieur Génie Civil FREE BTP" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 20px;">
          <h3 style="color: var(--primary-dark); font-size: 1.2rem; margin-bottom: 5px;">Ing. Emmanuel T.</h3>
          <span style="color: var(--accent); font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">Directeur des Travaux & Gros Œuvre</span>
          <p style="color: var(--muted); font-size: 0.88rem; margin-top: 10px;">14 ans d'expérience en calculs de structures béton armé et coordination des chantiers multi-étages.</p>
        </div>
      </div>

      <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); text-align: center;">
        <div style="height: 280px; overflow: hidden;">
          <img src="assets/images/team-2.jpg" alt="Conducteur de Travaux VRD" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 20px;">
          <h3 style="color: var(--primary-dark); font-size: 1.2rem; margin-bottom: 5px;">Alain D.</h3>
          <span style="color: var(--accent); font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">Chef de Projet VRD & Travaux Publics</span>
          <p style="color: var(--muted); font-size: 0.88rem; margin-top: 10px;">Spécialiste de la topographie, des terrassements complexes et du dimensionnement des chaussées.</p>
        </div>
      </div>

      <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); text-align: center;">
        <div style="height: 280px; overflow: hidden;">
          <img src="assets/images/team-3.jpg" alt="Responsable Qualité & Sécurité HSE" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 20px;">
          <h3 style="color: var(--primary-dark); font-size: 1.2rem; margin-bottom: 5px;">Sandrine M.</h3>
          <span style="color: var(--accent); font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">Responsable Contrôle Qualité & HSE</span>
          <p style="color: var(--muted); font-size: 0.88rem; margin-top: 10px;">Garante du respect des normes de sécurité chantier, des essais béton et de la traçabilité des matériaux.</p>
        </div>
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
