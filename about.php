<?php
$pageTitle = "À Propos de FEE BTP SARL | Direction, Histoire & Engagements à Dschang";
$pageDescription = "Découvrez FEE BTP SARL : son ancrage à Dschang, son dirigeant M. AGHOKENG TANGMO FABRICE, son parc d'engins lourds et sa politique de sécurité.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/visuals_helper.php';

$expYears = getSetting('stats_experience', '12');
$totalProjects = getSetting('stats_projects', '165');
$satisfaction = getSetting('stats_satisfaction', '99');
$equipments = getSetting('stats_equipments', '48');
$managerName = getSetting('manager_name', 'M. AGHOKENG TANGMO FABRICE');
$managerRole = getSetting('manager_role', 'Gérant / Directeur Général');

$aboutVisuals = getPageVisuals($pdo, 'about');
$aboutBanner = getVisualInfo($aboutVisuals, 'about_banner', 'images/personnel/FAB-8.jpg', 'center top');
$aboutPresentation = getVisualInfo($aboutVisuals, 'about_presentation', 'images/personnel/FAB-2.jpg', 'center top');
$teamDg = getVisualInfo($aboutVisuals, 'team_dg', 'images/team/aghokeng-fabrice.jpg', 'center top');
$teamTchoungo = getVisualInfo($aboutVisuals, 'team_tchoungo', 'images/team/tchoungo-junior.jpg', 'center top');
$teamKeibog = getVisualInfo($aboutVisuals, 'team_keibog', 'images/team/keibog-ricardo.jpg', 'center top');
$teamGuemechio = getVisualInfo($aboutVisuals, 'team_guemechio', 'images/team/guemechio-etienne.jpg', 'center top');
$teamZetsa = getVisualInfo($aboutVisuals, 'team_zetsa', 'images/team/zetsa-iriss.jpg', 'center top');
?>

<!-- BANNIÈRE EN-TÊTE -->
<section class="hero-wrap hero-wrap-2" style="background-image: url('<?= htmlspecialchars($aboutBanner['url']) ?>'); background-position: <?= htmlspecialchars($aboutBanner['focal']) ?>;" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text align-items-end justify-content-start">
			<div class="col-md-9 ftco-animate pb-5">
				<p class="breadcrumbs mb-2"><span class="mr-2"><a href="index.php">Accueil <i class="fa fa-chevron-right"></i></a></span> <span>À Propos <i class="fa fa-chevron-right"></i></span></p>
				<h1 class="mb-3 bread text-white font-weight-bold">L'Excellence & L'Ingénierie BTP à Dschang</h1>
			</div>
		</div>
	</div>
</section>

<!-- PRÉSENTATION DE L'ENTREPRISE, HISTORIQUE & VISION -->
<section class="ftco-section">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6 ftco-animate">
				<div class="img about-image rounded shadow overflow-hidden position-relative" style="background-image: url('<?= htmlspecialchars($aboutPresentation['url']) ?>'); min-height: 500px; background-size: cover; background-position: <?= htmlspecialchars($aboutPresentation['focal']) ?>;">
					<div class="badge-btp position-absolute" style="bottom: 20px; left: 20px; background: rgba(17, 20, 24, 0.85); backdrop-filter: blur(8px); color: #fff; padding: 10px 18px; border-radius: 50px; border: 1px solid rgba(249, 115, 22, 0.5);">
						<i class="fa fa-calendar-check-o text-warning mr-1"></i> Fondée en 2016 • Dschang, Cameroun
					</div>
				</div>
			</div>
			<div class="col-md-6 pl-md-5 ftco-animate mt-4 mt-md-0">
				<span class="subheading text-primary font-weight-bold" style="letter-spacing: 2px;">HISTORIQUE & VISION</span>
				<h2 class="mb-4 font-weight-bold">Bâtir l'Excellence, Transformer le Cadre de Vie</h2>
				<p class="text-muted" style="line-height: 1.8;">
					Créée en <strong>2016</strong> au Cameroun, <strong>FEE BTP SARL</strong> est une société de droit camerounais née de la volonté de ses fondateurs de contribuer activement au développement des infrastructures et à la modernisation du cadre de vie des populations. Nous concevons et réalisons des ouvrages de haute technicité, répondant aux normes les plus strictes du secteur de la construction.
				</p>
				<p class="text-muted" style="line-height: 1.8;">
					<strong>Notre Vision :</strong> Devenir l'entreprise de référence incontournable dans le BTP au Cameroun, et à long terme en <strong>Afrique Centrale</strong>. Nous transformons les paysages urbains et ruraux à travers des infrastructures fiables, modernes, accessibles et durables, tout en plaçant la qualité, la sécurité et la satisfaction client au cœur de nos chantiers.
				</p>
				<div class="p-3 mb-4 rounded" style="background: #fff7ed; border-left: 4px solid #ea580c;">
					<h6 class="font-weight-bold mb-1" style="color: #c2410c;"><i class="fa fa-globe mr-2"></i>Engagement Spécial Diaspora</h6>
					<p class="small text-muted mb-0">
						Accompagner et mettre en pleine confiance nos frères et sœurs de la diaspora dans tous leurs investissements immobiliers au Cameroun, avec une transparence absolue et des comptes rendus vidéo réguliers.
					</p>
				</div>
				<div class="row mt-4">
					<div class="col-4 mb-3">
						<div class="border-left border-primary pl-3">
							<h4 class="font-weight-bold text-dark mb-0">+<?= htmlspecialchars($expYears) ?> Ans</h4>
							<small class="text-muted">D'expérience (depuis 2016)</small>
						</div>
					</div>
					<div class="col-4 mb-3">
						<div class="border-left border-primary pl-3">
							<h4 class="font-weight-bold text-dark mb-0">+<?= htmlspecialchars($totalProjects) ?> Projets</h4>
							<small class="text-muted">Déjà réalisés avec succès</small>
						</div>
					</div>
					<div class="col-4 mb-3">
						<div class="border-left border-primary pl-3">
							<h4 class="font-weight-bold text-dark mb-0">R+3</h4>
							<small class="text-muted">Jusqu'à 1 200 m²</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- VALEURS & PROTOCOLES HSE (HYGIÈNE, SÉCURITÉ, ENVIRONNEMENT) -->
<section class="ftco-section bg-light">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-8 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold">Nos Engagements</span>
				<h2 class="mb-4">Sécurité des Hommes & Rigueur des Procédés</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 ftco-animate mb-4">
				<div class="bg-white p-4 rounded shadow-sm text-center h-100">
					<div class="icon mb-3 text-primary" style="font-size: 40px;"><i class="fa fa-hard-hat"></i></div>
					<h4 class="font-weight-bold mb-3">Normes HSE Intransigeantes</h4>
					<p class="text-muted small">Équipements de protection individuelle (EPI) obligatoires pour chaque ouvrier et technicien. Sécurisation intégrale des chantiers pour zéro incident.</p>
				</div>
			</div>
			<div class="col-md-4 ftco-animate mb-4">
				<div class="bg-white p-4 rounded shadow-sm text-center h-100">
					<div class="icon mb-3 text-primary" style="font-size: 40px;"><i class="fa fa-ruler-combined"></i></div>
					<h4 class="font-weight-bold mb-3">Contrôle Qualité des Matériaux</h4>
					<p class="text-muted small">Sélection des meilleurs agrégats, dosages de ciment scrupuleusement vérifiés, aciers normalisés et éprouvettes de compression testées en laboratoire.</p>
				</div>
			</div>
			<div class="col-md-4 ftco-animate mb-4">
				<div class="bg-white p-4 rounded shadow-sm text-center h-100">
					<div class="icon mb-3 text-primary" style="font-size: 40px;"><i class="fa fa-clock-o"></i></div>
					<h4 class="font-weight-bold mb-3">Respect des Délais Contractuels</h4>
					<p class="text-muted small">Utilisation de rétro-plannings dynamiques (diagrammes de Gantt) pour garantir une livraison de vos ouvrages sans retard ni surcoût imprévu.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- PARC MATÉRIEL & ÉQUIPEMENTS LOURDS -->
<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-8 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold">Moyens Logistiques & Techniques</span>
				<h2 class="mb-4">Un Parc d'Engins Performant & Opérationnel</h2>
				<p class="text-muted">Pour assurer l'autonomie et la rapidité d'exécution de nos chantiers de terrassement et génie civil, nous disposons d'équipements adaptés aux reliefs de l'Ouest.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-6 text-center mb-4 ftco-animate">
				<div class="p-3 border rounded shadow-sm bg-white">
					<i class="flaticon-crane display-4 text-primary mb-2"></i>
					<h6 class="font-weight-bold mb-1">Grues & Pelles</h6>
					<p class="small text-muted mb-0">Terrassements & Levage</p>
				</div>
			</div>
			<div class="col-md-3 col-6 text-center mb-4 ftco-animate">
				<div class="p-3 border rounded shadow-sm bg-white">
					<i class="flaticon-worker-1 display-4 text-primary mb-2"></i>
					<h6 class="font-weight-bold mb-1">Camions-Bennes</h6>
					<p class="small text-muted mb-0">Transport de remblais & matériaux</p>
				</div>
			</div>
			<div class="col-md-3 col-6 text-center mb-4 ftco-animate">
				<div class="p-3 border rounded shadow-sm bg-white">
					<i class="fa fa-industry display-4 text-primary mb-2"></i>
					<h6 class="font-weight-bold mb-1">Centrales & Bétonnières</h6>
					<p class="small text-muted mb-0">Coulage rapide et homogène</p>
				</div>
			</div>
			<div class="col-md-3 col-6 text-center mb-4 ftco-animate">
				<div class="p-3 border rounded shadow-sm bg-white">
					<i class="fa fa-crosshairs display-4 text-primary mb-2"></i>
					<h6 class="font-weight-bold mb-1">Niveaux Laser & Théodolites</h6>
					<p class="small text-muted mb-0">Topographie & Alerte altimétrique</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ÉQUIPE DIRIGEANTE, ARCHITECTES & CADRES TECHNIQUES -->
<section class="ftco-section bg-light" id="team-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-9 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold" style="letter-spacing: 2px;">NOTRE CAPITAL HUMAIN</span>
				<h2 class="mb-4 font-weight-bold">Direction, Bureau d'Études & Équipes Terrain</h2>
				<p class="text-muted lead">Une synergie d'ingénieurs, architectes et logisticiens passionnés, unis pour concevoir et concrétiser vos projets avec le plus haut niveau d'exigence technique.</p>
			</div>
		</div>

		<!-- 1. Direction Générale (Mise en avant d'honneur) -->
		<div class="row justify-content-center mb-5">
			<div class="col-lg-8 col-md-10 ftco-animate">
				<div class="staff bg-white rounded-xl shadow overflow-hidden d-flex flex-column flex-md-row align-items-stretch" style="border: 2px solid rgba(234, 88, 12, 0.2); border-radius: 20px;">
					<!-- Espace Photo DG -->
					<div class="col-md-5 p-0 position-relative" style="min-height: 380px;">
						<div class="w-100 h-100" style="background-image: url('<?= htmlspecialchars($teamDg['url']) ?>'); background-size: cover; background-position: <?= htmlspecialchars($teamDg['focal']) ?>;"></div>
						<div class="position-absolute" style="bottom: 15px; left: 15px;">
							<span class="badge badge-warning text-dark font-weight-bold px-3 py-1 shadow-sm">
								<i class="fa fa-star mr-1"></i> Fondateur & DG
							</span>
						</div>
					</div>
					<!-- Détails & Mission DG -->
					<div class="col-md-7 p-4 p-lg-5 d-flex flex-column justify-content-between">
						<div>
							<span class="text-uppercase font-weight-bold" style="color: #ea580c; font-size: 12px; letter-spacing: 1.5px;">Direction Générale</span>
							<h3 class="font-weight-bold text-dark mt-1 mb-2" style="font-size: 1.7rem;"><?= htmlspecialchars($managerName) ?></h3>
							<p class="badge badge-light border text-primary font-weight-bold px-3 py-1 mb-3" style="font-size: 0.9rem;">Directeur Général</p>
							<p class="text-muted" style="line-height: 1.8; font-size: 0.98rem;">
								« Mon rôle est de coordonner et superviser l'ensemble des activités de l'entreprise. Je définis les orientations stratégiques, je veille à la rigueur de la gestion administrative et financière, et je supervise directement les équipes techniques afin de garantir la parfaite exécution de vos projets de construction. »
							</p>
						</div>
						<div class="pt-3 border-top d-flex justify-content-between align-items-center">
							<small class="text-muted font-weight-bold"><i class="fa fa-map-marker text-danger mr-1"></i> Dschang & Région de l'Ouest</small>
							<a href="contact.php" class="btn btn-sm btn-outline-primary px-3 font-weight-bold">Prendre Contact</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- 2. Les Cadres Techniques & Concepteurs (4 Blocs Alignés sur la Même Ligne) -->
		<div class="row">
			<!-- Membre 1 : TCHOUNGO MBONGO JUNIOR -->
			<div class="col-lg-3 col-md-6 ftco-animate mb-4">
				<div class="staff bg-white rounded-xl shadow-sm overflow-hidden h-100 d-flex flex-column justify-content-between" style="border: 1.5px solid #e2e8f0; border-radius: 16px;">
					<div>
						<!-- Photo Réelle Optimisée Architecte Junior (Tête 100% visible) -->
						<div class="team-photo-slot position-relative" style="height: 250px; background-color: #0f172a; background-image: url('<?= htmlspecialchars($teamTchoungo['url']) ?>'); background-size: cover; background-position: <?= htmlspecialchars($teamTchoungo['focal']) ?>;">
							<div class="team-photo-overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15,23,42,0.75) 0%, rgba(0,0,0,0) 35%);"></div>
							<div class="position-absolute" style="bottom: 10px; left: 12px;">
								<span class="badge badge-light text-dark font-weight-bold px-2 py-1 shadow-sm" style="font-size: 10px;">
									<i class="fa fa-pencil-ruler text-primary mr-1"></i> Conception 3D
								</span>
							</div>
						</div>
						<div class="p-3">
							<h5 class="font-weight-bold mb-1 text-dark" style="font-size: 1.05rem;">TCHOUNGO M. JUNIOR</h5>
							<span class="text-primary font-weight-bold d-block mb-2" style="font-size: 0.85rem;">Architecte Concepteur</span>
							<p class="text-muted" style="line-height: 1.5; font-size: 0.85rem;">
								« Vos bâtiments sont une priorité car je réalise ce que votre cœur désire. » Spécialiste de la conception moderne et des volumes bioclimatiques.
							</p>
						</div>
					</div>
					<div class="p-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
						<small class="text-muted font-weight-bold" style="font-size: 11px;"><i class="fa fa-drafting-compass text-primary mr-1"></i> Bureau d'Études</small>
						<span class="badge badge-success px-2 py-1" style="font-size: 10px;">Plans 3D</span>
					</div>
				</div>
			</div>

			<!-- Membre 2 : KEIBOG RICARDO -->
			<div class="col-lg-3 col-md-6 ftco-animate mb-4">
				<div class="staff bg-white rounded-xl shadow-sm overflow-hidden h-100 d-flex flex-column justify-content-between" style="border: 1.5px solid #e2e8f0; border-radius: 16px;">
					<div>
						<!-- Photo Réelle Optimisée Architecte Ricardo (Tête 100% visible) -->
						<div class="team-photo-slot position-relative" style="height: 250px; background-color: #0f172a; background-image: url('<?= htmlspecialchars($teamKeibog['url']) ?>'); background-size: cover; background-position: <?= htmlspecialchars($teamKeibog['focal']) ?>;">
							<div class="team-photo-overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15,23,42,0.75) 0%, rgba(0,0,0,0) 35%);"></div>
							<div class="position-absolute" style="bottom: 10px; left: 12px;">
								<span class="badge badge-light text-dark font-weight-bold px-2 py-1 shadow-sm" style="font-size: 10px;">
									<i class="fa fa-building text-primary mr-1"></i> Architecture
								</span>
							</div>
						</div>
						<div class="p-3">
							<h5 class="font-weight-bold mb-1 text-dark" style="font-size: 1.05rem;">KEIBOG RICARDO</h5>
							<span class="text-primary font-weight-bold d-block mb-2" style="font-size: 0.85rem;">Architecte</span>
							<p class="text-muted" style="line-height: 1.5; font-size: 0.85rem;">
								Chargé de concevoir les espaces et les bâtiments. Il imagine les projets, réalise les plans architecturaux et veille au respect strict des règles d'urbanisme.
							</p>
						</div>
					</div>
					<div class="p-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
						<small class="text-muted font-weight-bold" style="font-size: 11px;"><i class="fa fa-university text-primary mr-1"></i> Permis</small>
						<span class="badge badge-info px-2 py-1" style="font-size: 10px;">Urbanisme</span>
					</div>
				</div>
			</div>

			<!-- Membre 3 : GUEMECHIO ETIENNE BIENVENUE -->
			<div class="col-lg-3 col-md-6 ftco-animate mb-4">
				<div class="staff bg-white rounded-xl shadow-sm overflow-hidden h-100 d-flex flex-column justify-content-between" style="border: 1.5px solid #e2e8f0; border-radius: 16px;">
					<div>
						<!-- Photo Réelle Optimisée Ingénieur Étienne Bienvenue (Tête 100% visible) -->
						<div class="team-photo-slot position-relative" style="height: 250px; background-color: #0f172a; background-image: url('<?= htmlspecialchars($teamGuemechio['url']) ?>'); background-size: cover; background-position: <?= htmlspecialchars($teamGuemechio['focal']) ?>;">
							<div class="team-photo-overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15,23,42,0.75) 0%, rgba(0,0,0,0) 35%);"></div>
							<div class="position-absolute" style="bottom: 10px; left: 12px;">
								<span class="badge badge-light text-dark font-weight-bold px-2 py-1 shadow-sm" style="font-size: 10px;">
									<i class="fa fa-hard-hat text-warning mr-1"></i> Suivi Chantier
								</span>
							</div>
						</div>
						<div class="p-3">
							<h5 class="font-weight-bold mb-1 text-dark" style="font-size: 1.05rem;">GUEMECHIO ETIENNE</h5>
							<span class="text-primary font-weight-bold d-block mb-2" style="font-size: 0.85rem;">Ingénieur Suivi Travaux</span>
							<p class="text-muted" style="line-height: 1.5; font-size: 0.85rem;">
								Études techniques de structure (descente de charges, béton armé vibré), rétroplannings et supervision rigoureuse de l'ensemble des chantiers.
							</p>
						</div>
					</div>
					<div class="p-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
						<small class="text-muted font-weight-bold" style="font-size: 11px;"><i class="fa fa-check-circle text-success mr-1"></i> Qualité Béton</small>
						<span class="badge badge-primary px-2 py-1" style="font-size: 10px;">Contrôle Terrain</span>
					</div>
				</div>
			</div>

			<!-- Membre 4 : ZETSA IRISS DUBAL -->
			<div class="col-lg-3 col-md-6 ftco-animate mb-4">
				<div class="staff bg-white rounded-xl shadow-sm overflow-hidden h-100 d-flex flex-column justify-content-between" style="border: 1.5px solid #e2e8f0; border-radius: 16px;">
					<div>
						<!-- Photo Réelle Optimisée Responsable Logistique Zetsa Iriss (Tête 100% visible) -->
						<div class="team-photo-slot position-relative" style="height: 250px; background-color: #0f172a; background-image: url('<?= htmlspecialchars($teamZetsa['url']) ?>'); background-size: cover; background-position: <?= htmlspecialchars($teamZetsa['focal']) ?>;">
							<div class="team-photo-overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(15,23,42,0.75) 0%, rgba(0,0,0,0) 35%);"></div>
							<div class="position-absolute" style="bottom: 10px; left: 12px;">
								<span class="badge badge-light text-dark font-weight-bold px-2 py-1 shadow-sm" style="font-size: 10px;">
									<i class="fa fa-truck text-danger mr-1"></i> Logistique
								</span>
							</div>
						</div>
						<div class="p-3">
							<h5 class="font-weight-bold mb-1 text-dark" style="font-size: 1.05rem;">ZETSA IRISS DUBAL</h5>
							<span class="text-primary font-weight-bold d-block mb-2" style="font-size: 0.85rem;">Responsable Logistique</span>
							<p class="text-muted" style="line-height: 1.5; font-size: 0.85rem;">
								Organisation et contrôle en temps réel du parc d'engins (pelles hydrauliques, camions-bennes, centrales à béton et approvisionnements).
							</p>
						</div>
					</div>
					<div class="p-2 px-3 bg-light border-top d-flex justify-content-between align-items-center">
						<small class="text-muted font-weight-bold" style="font-size: 11px;"><i class="fa fa-cogs text-dark mr-1"></i> Flotte Engins</small>
						<span class="badge badge-warning text-dark px-2 py-1" style="font-size: 10px;">Disponibilité 24/7</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
