<?php
$pageTitle = "FEE BTP SARL | Bâtiment, Travaux Publics, Génie Civil & Rénovation à Dschang";
$pageDescription = "FEE BTP SARL : Entreprise de référence en BTP et Génie Civil à Dschang (Cameroun). Gros œuvre, VRD, second œuvre, études architecturales et conduite de travaux.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Récupérer les services
$servicesStmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6");
$services = $servicesStmt->fetchAll();

// Récupérer les projets mis en avant
$projectsStmt = $pdo->query("SELECT * FROM projects WHERE is_featured = 1 ORDER BY id DESC LIMIT 6");
$projects = $projectsStmt->fetchAll();

// Récupérer les témoignages
$testimonialsStmt = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 4");
$testimonials = $testimonialsStmt->fetchAll();

// Récupérer les articles récents
$articlesStmt = $pdo->query("SELECT * FROM articles WHERE is_published = 1 ORDER BY id DESC LIMIT 3");
$articles = $articlesStmt->fetchAll();

// Chiffres clés
$expYears = getSetting('stats_experience', '12');
$totalProjects = getSetting('stats_projects', '165');
$satisfaction = getSetting('stats_satisfaction', '99');
$equipments = getSetting('stats_equipments', '48');
?>

<!-- Alertes Flash -->
<?php if (!empty($_SESSION['flash_success'])): ?>
	<div class="container mt-3">
		<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
			<i class="fa fa-check-circle mr-2"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
	<?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
	<div class="container mt-3">
		<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
			<i class="fa fa-exclamation-triangle mr-2"></i> <?= htmlspecialchars($_SESSION['flash_error']) ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
	<?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<!-- ========================================================================
     1. HERO BANNER CINÉMATIQUE HAUT DE GAMME (EFFET KEN BURNS & ROTATION)
     ======================================================================== -->
<div id="heroHomeCarousel" class="carousel slide carousel-fade position-relative overflow-hidden" data-ride="carousel" data-interval="7500">
	<!-- Canvas 3D Interactif : Maillage de Structure & Poutres BTP en Fil de Fer Réactif -->
	<canvas id="feeHero3DCanvas"></canvas>

	<!-- Indicateurs -->
	<ol class="carousel-indicators" style="bottom: 30px; z-index: 10;">
		<li data-target="#heroHomeCarousel" data-slide-to="0" class="active"></li>
		<li data-target="#heroHomeCarousel" data-slide-to="1"></li>
		<li data-target="#heroHomeCarousel" data-slide-to="2"></li>
	</ol>

	<div class="carousel-inner">
		<!-- SLIDE 1 : FAB-6 (Direction & Ingénierie - Vidéo + Maillage 3D) -->
		<div class="carousel-item active">
			<section class="hero-wrap js-fullheight kenburns-slide position-relative">
				<?php if (file_exists(__DIR__ . '/videos/chantier-btp-4k.mp4')): ?>
				<div class="hero-video-container">
					<video id="feeHeroCinematicVideo" class="hero-video-bg" autoplay muted loop playsinline preload="auto" poster="images/personnel/FAB-6.jpg">
						<source src="videos/chantier-btp-4k.mp4" type="video/mp4">
					</video>
				</div>
				<?php endif; ?>
				<!-- Fond Image Haute Fidélité FAB-6.jpg -->
				<div class="kenburns-bg" style="background-image: url('images/personnel/FAB-6.jpg');"></div>
				<div class="cinematic-overlay" style="background: radial-gradient(circle at 75% 35%, rgba(30, 34, 41, 0.2) 0%, rgba(17, 20, 24, 0.65) 85%), linear-gradient(90deg, rgba(17, 20, 24, 0.78) 0%, rgba(17, 20, 24, 0.45) 55%, rgba(17, 20, 24, 0.15) 100%);"></div>
				<div class="container h-100" style="position: relative; z-index: 4;">
					<div class="row no-gutters slider-text js-fullheight align-items-center">
						<div class="col-lg-10 col-xl-9 ftco-animate">
							<div class="mt-5">
								<div class="mb-3 d-flex align-items-center flex-wrap gap-2">
									<div class="badge-prestige mr-2">
										<span class="badge-prestige-dot"></span>
										<span>Direction Générale & Ingénierie BTP • Dschang</span>
									</div>
									<div class="hero-video-badge-live">
										<span class="live-pulse-indicator"></span>
										<span>Immersion Chantier 4K & 3D</span>
									</div>
								</div>
								<h1 class="mb-4 text-white font-weight-bold" style="font-size: 3.3rem; line-height: 1.15; text-shadow: 0 4px 20px rgba(0,0,0,0.6);">
									L'Art de Bâtir des Ouvrages <br>
									<span class="text-gradient-gold">Durables, Puissants & Modernes</span>
								</h1>
								<p class="mb-5 text-white-50 lead" style="max-width: 780px; font-size: 1.15rem; line-height: 1.7;">
									Depuis plus de <?= htmlspecialchars($expYears) ?> ans, <strong><?= htmlspecialchars($companyName) ?></strong> combine rigueur géotechnique, parcs d'engins lourds et pilotage d'élite pour concrétiser vos plus grands projets de construction et de génie civil.
								</p>
								<div class="d-flex flex-wrap gap-3">
									<a href="contact.php?type=devis" class="btn btn-gold-glow mr-3 mb-2">
										<i class="fa fa-calculator mr-2"></i> Étude de Prix Express
									</a>
									<a href="project.php" class="btn btn-glass-outline mb-2">
										<i class="fa fa-eye mr-2"></i> Explorer Nos Chantiers BTP
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!-- SLIDE 2 : FAB-8 (Opérations de Terrain & Cadence Chantier) -->
		<div class="carousel-item">
			<section class="hero-wrap js-fullheight kenburns-slide">
				<div class="kenburns-bg" style="background-image: url('images/personnel/FAB-8.jpg');"></div>
				<div class="cinematic-overlay"></div>
				<div class="container h-100">
					<div class="row no-gutters slider-text js-fullheight align-items-center">
						<div class="col-lg-10 col-xl-9 ftco-animate">
							<div class="mt-5">
								<div class="mb-3">
									<div class="badge-prestige">
										<span class="badge-prestige-dot"></span>
										<span>Cadence Terrain & Sécurité HSE Zéro Incident</span>
									</div>
								</div>
								<h1 class="mb-4 text-white font-weight-bold" style="font-size: 3.2rem; line-height: 1.15;">
									Maîtrise Opérationnelle <br>
									<span class="text-gradient-gold">& Cadence de Chantier Garantie</span>
								</h1>
								<p class="mb-5 text-white-50 lead" style="max-width: 780px; font-size: 1.15rem; line-height: 1.7;">
									Sur le terrain, nos ingénieurs et conducteurs de travaux veillent au respect strict des rétro-plannings, à la qualité irréprochable des bétons et à la sécurité de vos équipes.
								</p>
								<div class="d-flex flex-wrap gap-3">
									<a href="project.php" class="btn btn-gold-glow mr-3 mb-2">
										<i class="fa fa-images mr-2"></i> Nos Réalisations en Images
									</a>
									<a href="about.php" class="btn btn-glass-outline mb-2">
										<i class="fa fa-users mr-2"></i> Découvrir l'Équipe
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!-- SLIDE 3 : FAB-4 (Études Techniques & Rigueur de Contrôle) -->
		<div class="carousel-item">
			<section class="hero-wrap js-fullheight kenburns-slide">
				<div class="kenburns-bg" style="background-image: url('images/personnel/FAB-4.jpg');"></div>
				<div class="cinematic-overlay"></div>
				<div class="container h-100">
					<div class="row no-gutters slider-text js-fullheight align-items-center">
						<div class="col-lg-10 col-xl-9 ftco-animate">
							<div class="mt-5">
								<div class="mb-3">
									<div class="badge-prestige">
										<span class="badge-prestige-dot"></span>
										<span>Métrés Rigoureux & Maîtrise des Budgets</span>
									</div>
								</div>
								<h1 class="mb-4 text-white font-weight-bold" style="font-size: 3.2rem; line-height: 1.15;">
									Transparence Budgétaire <br>
									<span class="text-gradient-gold">& Respect Scrupuleux des Coûts</span>
								</h1>
								<p class="mb-5 text-white-50 lead" style="max-width: 780px; font-size: 1.15rem; line-height: 1.7;">
									De l'analyse d'avant-projet jusqu'au décompte général définitif, notre pôle administratif et financier vous garantit une gestion claire, sans dépassement ni frais imprévus.
								</p>
								<div class="d-flex flex-wrap gap-3">
									<a href="services.php" class="btn btn-gold-glow mr-3 mb-2">
										<i class="fa fa-th-list mr-2"></i> Nos Domaines d'Expertise
									</a>
									<a href="contact.php?type=devis" class="btn btn-glass-outline mb-2">
										<i class="fa fa-paper-plane mr-2"></i> Demander une Cotation
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<!-- Flèches de navigation Carousel -->
	<a class="carousel-control-prev" href="#heroHomeCarousel" role="button" data-slide="prev" style="width: 70px; z-index: 10;">
		<span class="fa fa-chevron-left text-white p-3 rounded-circle shadow" style="background: rgba(17, 20, 24, 0.7); border: 1px solid rgba(255,153,0,0.5); font-size: 18px;" aria-hidden="true"></span>
		<span class="sr-only">Précédent</span>
	</a>
	<a class="carousel-control-next" href="#heroHomeCarousel" role="button" data-slide="next" style="width: 70px; z-index: 10;">
		<span class="fa fa-chevron-right text-white p-3 rounded-circle shadow" style="background: rgba(17, 20, 24, 0.7); border: 1px solid rgba(255,153,0,0.5); font-size: 18px;" aria-hidden="true"></span>
		<span class="sr-only">Suivant</span>
	</a>
</div>

<!-- ========================================================================
     2. BANDEAU DE CERTIFICATIONS & GARANTIES INSTITUTIONNELLES BTP
     ======================================================================== -->
<div class="fee-reassurance-ribbon py-3" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-6 col-lg-3 py-2 px-3">
				<div class="fee-reassurance-item">
					<div class="fee-reassurance-icon">
						<i class="fa fa-shield"></i>
					</div>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 13.5px; line-height: 1.25;">Agrément BTP & Normes</div>
						<small class="text-muted" style="font-size: 11px;">Calculs Eurocodes & MINTP</small>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-3 py-2 px-3">
				<div class="fee-reassurance-item">
					<div class="fee-reassurance-icon">
						<i class="fa fa-truck-monster"></i>
					</div>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 13.5px; line-height: 1.25;">Flotte d'Engins en Propre</div>
						<small class="text-muted" style="font-size: 11px;">Pelles, compacteurs & bennes</small>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-3 py-2 px-3">
				<div class="fee-reassurance-item">
					<div class="fee-reassurance-icon">
						<i class="fa fa-drafting-compass"></i>
					</div>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 13.5px; line-height: 1.25;">Bureau d'Études Intégré</div>
						<small class="text-muted" style="font-size: 11px;">Plans 2D/3D & géotechnique</small>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-3 py-2 px-3">
				<div class="fee-reassurance-item">
					<div class="fee-reassurance-icon">
						<i class="fa fa-handshake-o"></i>
					</div>
					<div>
						<div class="font-weight-bold text-dark" style="font-size: 13.5px; line-height: 1.25;">Partenaire Diaspora</div>
						<small class="text-muted" style="font-size: 11px;">Reporting vidéo & transparence</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ========================================================================
     2.1 STUDIO 3D BIM & MAQUETTE DIGITALE TEMPS RÉEL (THREE.JS NEXT-GEN)
     ======================================================================== -->
<section class="fee-bim-section" id="bim-3d-experience">
	<div class="container">
		<div class="row justify-content-center text-center mb-5">
			<div class="col-lg-10 ftco-animate">
				<div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: rgba(234, 88, 12, 0.15); border: 1.5px solid rgba(234, 88, 12, 0.4); color: #fb923c; font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
					<i class="fa fa-cube mr-1"></i> INNOVATION TECHNOLOGIQUE & INGÉNIERIE NUMÉRIQUE
				</div>
				<h2 class="font-weight-bold mb-3 text-white" style="font-size: 2.3rem; letter-spacing: -0.02em;">
					Studio 3D BIM : Explorez l'Ouvrage de l'Intérieur
				</h2>
				<p class="lead mx-auto" style="max-width: 820px; color: #94a3b8; font-size: 1.1rem; line-height: 1.7;">
					Faites pivoter la maquette à la souris ou au doigt, décomposez les niveaux avec la <strong class="text-white">vue écorchée par étage</strong> et inspectez les fondations calculées selon les normes géotechniques de Dschang.
				</p>
			</div>
		</div>

		<div class="fee-bim-container">
			<div class="fee-bim-canvas-wrap" id="feeBimCanvasWrap">
				<div class="fee-bim-grid-overlay"></div>
				<canvas id="feeBim3DCanvas"></canvas>

				<!-- HUD flottant d'ingénierie -->
				<div class="fee-bim-hud d-none d-md-block">
					<div class="fee-bim-hud-title">
						<span class="fee-bim-hud-badge"></span>
						<span>Maquette BIM R+2 Structurale</span>
					</div>
					<div class="fee-bim-hud-specs">
						&bull; Calcul : <span>Eurocodes 2 & DTU</span><br>
						&bull; Béton armé : <span>Dosage 350 kg/m³</span><br>
						&bull; Armatures : <span>Acier FeE500 HA</span><br>
						&bull; Baies & Portes : <span id="feeBimDoorLabel">Fermées</span>
					</div>
				</div>

				<!-- Barre d'outils de contrôle 3D -->
				<div class="fee-bim-toolbar">
					<button type="button" class="fee-bim-btn active" data-bim-mode="prestige" title="Rendu architectural fini">
						<i class="fa fa-eye"></i> <span class="d-none d-sm-inline">Rendu Réaliste</span>
					</button>
					<button type="button" class="fee-bim-btn" data-bim-mode="blueprint" title="Structure filaire d'ingénierie">
						<i class="fa fa-drafting-compass"></i> <span class="d-none d-sm-inline">Filaire Blueprint</span>
					</button>
					<button type="button" class="fee-bim-btn" data-bim-mode="concrete" title="Béton brut et armatures">
						<i class="fa fa-cubes"></i> <span class="d-none d-sm-inline">Structure Béton</span>
					</button>
					<button type="button" class="fee-bim-btn" id="feeBtnBimOpenDoors" title="Ouvrir / Fermer les baies vitrées et portes">
						<i class="fa fa-door-open"></i> <span>Ouvrir les Baies</span>
					</button>
					<button type="button" class="fee-bim-btn" id="feeBtnBimExplode" title="Décomposer les étages">
						<i class="fa fa-layer-group"></i> <span>Vue Écorchée</span>
					</button>
					<button type="button" class="fee-bim-btn" id="feeBtnBimNight" title="Bascule Ambiance Solaire / Nuit VIP">
						<i class="fa fa-moon"></i> <span class="d-none d-sm-inline">Mode Nuit</span>
					</button>
					<button type="button" class="fee-bim-btn active" id="feeBtnBimRotate" title="Activer/Pause rotation">
						<i class="fa fa-repeat"></i>
					</button>
					<button type="button" class="fee-bim-btn" id="feeBtnBimReset" title="Recentrer la caméra">
						<i class="fa fa-crosshairs"></i>
					</button>
				</div>
			</div>
		</div>

		<div class="text-center mt-4">
			<span class="small" style="color: #64748b;">
				<i class="fa fa-mouse-pointer mr-1 text-warning"></i> Cliquez et glissez pour tourner à 360° &bull; Molette pour zoomer &bull; Cliquez sur <strong>« Vue Écorchée »</strong> pour séparer les dalles
			</span>
		</div>
	</div>
</section>

<!-- ========================================================================
     3. EXPERTISE TOPOGRAPHIQUE & GÉNIE CIVIL DE TERRAIN (DSCHANG & OUEST)
     ======================================================================== -->
<section class="fee-terrain-section position-relative">
	<div class="container position-relative" style="z-index: 2;">
		<!-- En-tête Prestigieux FEE BTP SARL -->
		<div class="row justify-content-center text-center mb-5">
			<div class="col-lg-10 ftco-animate">
				<span class="fee-terrain-badge">
					<i class="fa fa-mountain mr-1"></i> SPÉCIFICITÉ TOPOGRAPHIQUE & HAUTE RÉSISTANCE
				</span>
				<h2 class="fee-terrain-title">
					La Maîtrise des Reliefs Complexes et des Sols de l'Ouest
				</h2>
				<p class="fee-terrain-subtitle">
					Sous la direction de <strong class="text-dark">M. Fabrice AGHOKENG TANGMO</strong>, <strong style="color: #ea580c;">FEE BTP SARL</strong> déploie des solutions de pointe pour viabiliser, stabiliser et bâtir durablement sur les terrains pentus et latéritiques de Dschang et de sa région.
				</p>
			</div>
		</div>

		<div class="row align-items-stretch">
			<!-- Pilier 1 : Murs de soutènement & Pentes -->
			<div class="col-lg-4 col-md-6 d-flex ftco-animate mb-4 mb-lg-0">
				<div class="fee-terrain-card w-100">
					<!-- Photo En-tête Réelle Chantier -->
					<div class="fee-terrain-photo-wrap" style="background-image: url('images/IMG-20261015-WA0073.jpg'); background-position: center top !important;">
						<div class="fee-terrain-photo-overlay"></div>
						<span class="fee-terrain-badge-tag"><i class="fa fa-shield text-warning mr-1"></i> EXPERTISE 01 • STABILISATION</span>
						<div class="fee-terrain-icon-float">
							<i class="fa fa-layer-group"></i>
						</div>
					</div>

					<div class="fee-terrain-body">
						<h3 class="fee-terrain-card-title">Murs de Soutènement & Talus</h3>
						<p class="fee-terrain-desc">
							Ingénierie spécialisée pour terrains accidentés : calculs de poussée des terres, murs en béton armé banché et enrochements drainés anti-glissement.
						</p>

						<ul class="fee-terrain-list">
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Étude géotechnique de talus et descentes de charges critiques</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Murs banchés calculés avec barbacanes et drains d'évacuation d'eau</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Sécurisation définitive contre les éboulements en saison des pluies</span>
							</li>
						</ul>
					</div>

					<div class="fee-terrain-footer">
						<span><i class="fa fa-shield text-danger mr-1"></i> Sécurité Anti-Glissement Garantie</span>
						<i class="fa fa-arrow-right text-muted"></i>
					</div>
				</div>
			</div>

			<!-- Pilier 2 : Parc d'Engins Lourds (Mis en avant) -->
			<div class="col-lg-4 col-md-6 d-flex ftco-animate mb-4 mb-lg-0">
				<div class="fee-terrain-card terrain-featured w-100">
					<!-- Photo En-tête Réelle Chantier Engins -->
					<div class="fee-terrain-photo-wrap" style="background-image: url('images/IMG-20261029-WA0105.jpg'); background-position: center top !important;">
						<div class="fee-terrain-photo-overlay"></div>
						<span class="fee-terrain-badge-tag" style="background: rgba(234, 88, 12, 0.9); color: #fff; border-color: rgba(255,255,255,0.4);"><i class="fa fa-fire mr-1"></i> EXPERTISE 02 • PUISSANCE CHANTIER</span>
						<div class="fee-terrain-icon-float" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
							<i class="fa fa-truck-monster"></i>
						</div>
					</div>

					<div class="fee-terrain-body">
						<h3 class="fee-terrain-card-title" style="color: #ea580c;">Terrassement & Engins Lourds</h3>
						<p class="fee-terrain-desc">
							Une force de frappe mécanique autonome en propriété directe à Dschang : décapage, nivellement laser et terrassements de masse sans sous-traitance.
						</p>

						<ul class="fee-terrain-list">
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle" style="color: #ea580c;"></i>
								<span>Flotte de pelles hydrauliques, compacteurs vibrants & camions-bennes</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle" style="color: #ea580c;"></i>
								<span>Ouverture de voies d'accès et plateformes viabilisées prêtes à bâtir</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle" style="color: #ea580c;"></i>
								<span>Exécution accélérée : vos fondations prêtes en un temps record</span>
							</li>
						</ul>
					</div>

					<div class="fee-terrain-footer" style="background: #fff7ed; border-color: #fdba74; color: #c2410c;">
						<span><i class="fa fa-cogs mr-1" style="color: #ea580c;"></i> Flotte Mécanique Autonome Déployable</span>
						<i class="fa fa-star" style="color: #ea580c;"></i>
					</div>
				</div>
			</div>

			<!-- Pilier 3 : Béton Armé & Rigueur Diasporas -->
			<div class="col-lg-4 col-md-6 d-flex ftco-animate">
				<div class="fee-terrain-card w-100">
					<!-- Photo En-tête Réelle Chantier Béton -->
					<div class="fee-terrain-photo-wrap" style="background-image: url('images/IMG-20261015-WA0054.jpg'); background-position: center top !important;">
						<div class="fee-terrain-photo-overlay"></div>
						<span class="fee-terrain-badge-tag"><i class="fa fa-cubes text-warning mr-1"></i> EXPERTISE 03 • DURABILITÉ</span>
						<div class="fee-terrain-icon-float">
							<i class="fa fa-cubes"></i>
						</div>
					</div>

					<div class="fee-terrain-body">
						<h3 class="fee-terrain-card-title">Béton Armé & Suivi Diaspora</h3>
						<p class="fee-terrain-desc">
							Dosage normé à 350 kg/m³ d'agrégats sélectionnés et acier haute adhérence (HA). Reporting vidéo transparent pour la diaspora camerounaise.
						</p>

						<ul class="fee-terrain-list">
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Contrôles systématiques de vibration et cures de séchage</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Semelles isolées ou filantes renforcées selon la portance du sol</span>
							</li>
							<li class="fee-terrain-item">
								<i class="fa fa-check-circle"></i>
								<span>Rapports vidéo et photos hebdomadaires envoyés sur WhatsApp</span>
							</li>
						</ul>
					</div>

					<div class="fee-terrain-footer">
						<span><i class="fa fa-video text-warning mr-1"></i> Suivi Vidéo Transparent à Distance</span>
						<i class="fa fa-arrow-right text-muted"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     4. PRÉSENTATION & MOT DU DIRECTEUR GÉNÉRAL (M. AGHOKENG TANGMO FABRICE)
     ======================================================================== -->
<section class="ftco-section" id="about-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-10 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold" style="letter-spacing: 2px;">HISTORIQUE & ENGAGEMENT</span>
				<h2 class="mb-4 font-weight-bold" style="font-size: 2.3rem;">Bâtir Durable Depuis 2016 au Cameroun</h2>
				<p class="text-muted lead">
					Créée en <strong>2016</strong> au Cameroun, <strong>FEE BTP SARL</strong> est née de la volonté de contribuer au développement des infrastructures et à la modernisation du cadre de vie à travers des ouvrages aux normes techniques rigoureuses.
				</p>
			</div>
		</div>
		<div class="row align-items-center">
			<div class="col-md-6 ftco-animate">
				<div class="dg-3d-card-wrapper position-relative" data-3d-tilt style="perspective: 1200px;">
					<!-- Carte DG avec Effet de Profondeur 3D et Cadre Architecte -->
					<div class="img img-video d-flex align-items-end justify-content-center position-relative rounded shadow-lg overflow-hidden dg-3d-card" style="background-image: url('images/team/aghokeng-fabrice.jpg'); min-height: 520px; background-size: cover; background-position: center top; border: 4px solid #fff; border-radius: 18px !important; box-shadow: 0 30px 60px rgba(30, 34, 41, 0.28) !important;">
						<div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(to top, rgba(17, 20, 24,0.95) 0%, rgba(17, 20, 24,0.3) 50%, rgba(0,0,0,0) 80%);"></div>
						
						<!-- Badge Flottant en relief 3D (Z-Index surélevé) -->
						<div class="dg-floating-badge" style="position: absolute; top: 22px; right: 22px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 8px 16px; border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border: 1px solid rgba(249, 115, 22, 0.4); transform: translateZ(40px); z-index: 10;">
							<div class="d-flex align-items-center">
								<span class="live-dot-green mr-2"></span>
								<small class="font-weight-bold text-dark" style="letter-spacing: 0.5px;">Direction Sur Site Actif</small>
							</div>
						</div>

						<div class="p-4 text-center text-white position-relative w-100" style="z-index: 2; transform: translateZ(35px);">
							<h4 class="text-white mb-1 font-weight-bold" style="font-size: 1.5rem; letter-spacing: -0.3px;">M. AGHOKENG TANGMO FABRICE</h4>
							<p class="badge badge-warning text-dark px-3 py-1 font-weight-bold mb-1" style="font-size: 13px; letter-spacing: 0.5px;">Gérant / Directeur Général</p>
							<p class="small text-white-50 mb-0"><?= htmlspecialchars($companyName) ?> • Dschang, Cameroun</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 pl-md-5 ftco-animate mt-4 mt-md-0">
				<span class="badge badge-primary px-3 py-1 mb-2 font-weight-bold">Notre Ambition</span>
				<h3 class="font-weight-bold mb-3" style="font-size: 1.8rem;">Une Référence au Cameroun & en Afrique Centrale</h3>
				<p class="text-muted" style="line-height: 1.8;">
					Sous la supervision de <strong>M. AGHOKENG TANGMO FABRICE</strong>, notre mission est de proposer des solutions de construction innovantes, durables et adaptées aux réalités économiques et environnementales de notre pays.
				</p>
				<p class="text-muted" style="line-height: 1.8;">
					Avec un bureau d'études pluridisciplinaire réunissant des <strong>architectes concepteurs</strong> et des <strong>ingénieurs de suivi de travaux</strong>, nous prenons en charge vos bâtiments de standing (R+3, 800 à 1 200 m²), vos terrassements et vos fondations complexes.
				</p>
				<div class="p-3 mb-4 rounded" style="background: #fff7ed; border-left: 4px solid #ea580c;">
					<h6 class="font-weight-bold mb-1" style="color: #c2410c;"><i class="fa fa-handshake-o mr-2"></i>Partenaire Privilégié de la Diaspora</h6>
					<p class="small text-muted mb-0">
						Mettre en confiance nos frères et sœurs de la diaspora dans tous leurs investissements immobiliers au pays, avec transparence financière totale et suivi vidéo régulier.
					</p>
				</div>
				<div class="d-flex flex-wrap gap-2">
					<a href="about.php#team-section" class="btn btn-primary py-3 px-4 font-weight-bold mr-2 mb-2">
						Découvrir Notre Équipe & Architectes <i class="fa fa-arrow-right ml-1"></i>
					</a>
					<a href="contact.php" class="btn btn-outline-dark py-3 px-4 font-weight-bold mb-2">
						Nous Rencontrer à Dschang
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     4.1 VOS PROJETS LIVRÉS CLÉS EN MAIN & SUIVI DIASPORA EN DIRECT
     ======================================================================== -->
<section class="fee-tracker-section py-5" id="diaspora-tracker" style="background: #ffffff; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
	<div class="container">
		<div class="row justify-content-center text-center mb-5">
			<div class="col-lg-10 ftco-animate">
				<div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: #fff7ed; border: 1.5px solid #fed7aa; color: #ea580c; font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
					<i class="fa fa-trophy mr-1"></i> RÉSULTATS CONCRETS & LIVRAISON CLÉS EN MAIN
				</div>
				<h2 class="font-weight-bold mb-3" style="font-size: 2.3rem; color: #0f172a; letter-spacing: -0.01em;">
					Des Chantiers Livrés dans les Délais, Zéro Stress à Distance
				</h2>
				<p class="text-muted lead mx-auto" style="max-width: 820px; font-size: 1.1rem; line-height: 1.7;">
					Construire au Cameroun depuis l'étranger n'a jamais été aussi serein. Découvrez nos <strong>derniers chantiers livrés clés en main</strong> avec procès-verbaux de réception, respect strict des budgets et reporting vidéo en direct.
				</p>
			</div>
		</div>

		<!-- Grille des Résultats Livrés (Photos HD & Réalisations Concrètes) -->
		<div class="row g-4 mb-5">
			<!-- Projet 1 -->
			<div class="col-lg-4 col-md-6 mb-4 ftco-animate">
				<div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0 !important; transition: transform 0.3s ease, box-shadow 0.3s ease;">
					<div style="height: 230px; position: relative; overflow: hidden; background: #0f172a;">
						<img src="images/wa0062-framed-16x9.jpg" alt="Villa Duplex Contemporaine R+1" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover; display: block;">
						<span class="badge badge-success px-3 py-2 font-weight-bold" style="position: absolute; top: 15px; left: 15px; font-size: 11px; border-radius: 50px; box-shadow: 0 4px 10px rgba(16,185,129,0.3); z-index: 2;">
							<i class="fa fa-check-circle mr-1"></i> 100% LIVRÉ & HABITÉ
						</span>
						<span class="badge badge-dark px-3 py-1" style="position: absolute; bottom: 15px; right: 15px; font-size: 11px; border-radius: 50px; background: rgba(15,23,42,0.85); color: #fff; z-index: 2;">
							<i class="fa fa-map-marker text-warning mr-1"></i> Foréké-Dschang
						</span>
					</div>
					<div class="card-body p-4 text-left">
						<h5 class="font-weight-bold text-dark mb-2">Villa Duplex Contemporaine R+1</h5>
						<p class="text-muted small mb-3">Maître d'Ouvrage : <strong>M. Eric K. (Diaspora Paris)</strong></p>
						<ul class="list-unstyled small mb-4 text-secondary" style="line-height: 2;">
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Délai tenu :</strong> Livrée clé en main en 7 mois</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Budget maîtrisé :</strong> 0 surcoût imprévu</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Suivi à distance :</strong> 28 rapports vidéo WhatsApp</li>
						</ul>
						<a href="https://wa.me/237691006431?text=Bonjour%20FEE%20BTP,%20je%20souhaite%20voir%20les%20vid%C3%A9os%20de%20la%20Villa%20Duplex%20For%C3%A9k%C3%A9." target="_blank" rel="noopener" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 12px; font-size: 13px;">
							<i class="fab fa-whatsapp text-success mr-1"></i> Voir la Vidéo du Résultat
						</a>
					</div>
				</div>
			</div>

			<!-- Projet 2 -->
			<div class="col-lg-4 col-md-6 mb-4 ftco-animate">
				<div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0 !important; transition: transform 0.3s ease, box-shadow 0.3s ease;">
					<div style="height: 230px; position: relative; overflow: hidden; background: #0f172a;">
						<img src="images/IMG-20261015-WA0062.jpg" alt="Immeuble Mixte Résidentiel R+3" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover; display: block;">
						<span class="badge badge-success px-3 py-2 font-weight-bold" style="position: absolute; top: 15px; left: 15px; font-size: 11px; border-radius: 50px; box-shadow: 0 4px 10px rgba(16,185,129,0.3); z-index: 2;">
							<i class="fa fa-check-circle mr-1"></i> 100% LIVRÉ & EXPLOITÉ
						</span>
						<span class="badge badge-dark px-3 py-1" style="position: absolute; bottom: 15px; right: 15px; font-size: 11px; border-radius: 50px; background: rgba(15,23,42,0.85); color: #fff; z-index: 2;">
							<i class="fa fa-map-marker text-warning mr-1"></i> Dschang Centre
						</span>
					</div>
					<div class="card-body p-4 text-left">
						<h5 class="font-weight-bold text-dark mb-2">Immeuble Mixte Résidentiel R+3</h5>
						<p class="text-muted small mb-3">Maître d'Ouvrage : <strong>Mme Chantal T. (Montréal, Canada)</strong></p>
						<ul class="list-unstyled small mb-4 text-secondary" style="line-height: 2;">
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Résultat :</strong> 8 appartements + commerces loués</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Gros œuvre certifié :</strong> Fondations sol accidenté</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Finitions :</strong> Grès cérame poli et menuiserie alu</li>
						</ul>
						<a href="https://wa.me/237691006431?text=Bonjour%20FEE%20BTP,%20je%20souhaite%20des%20infos%20sur%20l%27immeuble%20R%2B3%20Dschang%20Centre." target="_blank" rel="noopener" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 12px; font-size: 13px;">
							<i class="fab fa-whatsapp text-success mr-1"></i> Voir la Fiche Chantier
						</a>
					</div>
				</div>
			</div>

			<!-- Projet 3 -->
			<div class="col-lg-4 col-md-6 mb-4 ftco-animate">
				<div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0 !important; transition: transform 0.3s ease, box-shadow 0.3s ease;">
					<div style="height: 230px; position: relative; overflow: hidden; background: #0f172a;">
						<img src="images/IMG-20261015-WA0073.jpg" alt="Murs de Soutènement & Sécurisation" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover; display: block;">
						<span class="badge badge-warning text-dark px-3 py-2 font-weight-bold" style="position: absolute; top: 15px; left: 15px; font-size: 11px; border-radius: 50px; z-index: 2;">
							<i class="fa fa-shield mr-1"></i> OUVRAGE GARANTI 10 ANS
						</span>
						<span class="badge badge-dark px-3 py-1" style="position: absolute; bottom: 15px; right: 15px; font-size: 11px; border-radius: 50px; background: rgba(15,23,42,0.85); color: #fff; z-index: 2;">
							<i class="fa fa-map-marker text-warning mr-1"></i> Ouest Cameroun
						</span>
					</div>
					<div class="card-body p-4 text-left">
						<h5 class="font-weight-bold text-dark mb-2">Murs de Soutènement & Sécurisation</h5>
						<p class="text-muted small mb-3">Maître d'Ouvrage : <strong>Projet Privé Résidentiel</strong></p>
						<ul class="list-unstyled small mb-4 text-secondary" style="line-height: 2;">
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Résultat :</strong> Terrain en pente 100% stabilisé</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Technique :</strong> Murs banchés et barbacanes</li>
							<li><i class="fa fa-check text-success mr-2"></i> <strong>Flotte :</strong> Terrassement par nos propres pelles</li>
						</ul>
						<a href="https://wa.me/237691006431?text=Bonjour%20FEE%20BTP,%20je%20souhaite%20s%C3%A9curiser%20un%20terrain%20en%20pente%20%C3%A0%20l%27Ouest." target="_blank" rel="noopener" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 12px; font-size: 13px;">
							<i class="fab fa-whatsapp text-success mr-1"></i> Demander une Expertise Sol
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Bannière des 3 Engagements Clés en Main (Résultats Garantis) -->
		<div class="p-4 p-md-5 rounded-4 shadow-sm text-left" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 24px; color: #ffffff;">
			<div class="row align-items-center">
				<div class="col-lg-4 mb-3 mb-lg-0 border-right-md border-secondary">
					<div class="d-flex align-items-start gap-3">
						<div class="rounded-circle p-3 mr-3" style="background: rgba(234, 88, 12, 0.15); color: #ea580c; font-size: 22px;">
							<i class="fab fa-whatsapp"></i>
						</div>
						<div>
							<h6 class="font-weight-bold text-white mb-1">Vidéos Chaque Vendredi</h6>
							<p class="text-white-50 small mb-0">Rapport vidéo HD & photos drone sur votre téléphone. Vous suivez tout en direct.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 mb-3 mb-lg-0 border-right-md border-secondary">
					<div class="d-flex align-items-start gap-3">
						<div class="rounded-circle p-3 mr-3" style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 22px;">
							<i class="fa fa-shield"></i>
						</div>
						<div>
							<h6 class="font-weight-bold text-white mb-1">Paiement par Tranches</h6>
							<p class="text-white-50 small mb-0">Vous ne réglez qu'après vérification vidéo de chaque étape réellement exécutée.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="d-flex align-items-start gap-3">
						<div class="rounded-circle p-3 mr-3" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 22px;">
							<i class="fa fa-key"></i>
						</div>
						<div>
							<h6 class="font-weight-bold text-white mb-1">Remise des Clés Certifiée</h6>
							<p class="text-white-50 small mb-0">Contrat ferme, procès-verbal de parfait achèvement et garantie décennale.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     5. LE MODULE INTERACTIF "AVANT / APRÈS" EN DIRECT (EFFET WAOUH EN GRANDE LARGEUR 90%+)
     ======================================================================== -->
<section class="ftco-section py-5" style="background: #f8fafc !important; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
	<div class="container-fluid px-2 px-md-4" style="width: 92%; max-width: 1750px;">
		<div class="row justify-content-center mb-5 text-center">
			<div class="col-lg-10 ftco-animate">
				<span class="subheading font-weight-bold" style="letter-spacing: 2px; color: #ea580c;">DÉMONSTRATION INTERACTIVE</span>
				<h2 class="mb-3 font-weight-bold" style="font-size: 2.2rem; color: #0f172a;">Faites Glisser : Du Terrain Brut à l'Ouvrage d'Exception</h2>
				<p class="text-muted lead">Déplacez le curseur central avec votre souris ou votre doigt pour comparer l'état initial du chantier avec l'ouvrage livré par nos équipes.</p>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-12 ftco-animate">
				<!-- Widget Comparatif Avant/Après Interactif avec Effet 3D Profondeur (Envergure 90%+) -->
				<div class="interactive-ba-3d-frame p-2 p-md-3" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08); border-radius: 20px; width: 100%;">
					<div class="interactive-ba-wrapper" id="homeInteractiveBA" style="border-radius: 14px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.15); width: 100%; max-width: 100%;">
						<!-- Image Après (Fond - Cadrée 16:9 sans sur-agrandissement) -->
						<div class="ba-image-layer ba-after-layer" style="background-image: url('images/wa0062-framed-16x9.jpg');"></div>
						<span class="ba-tag ba-tag-after"><i class="fa fa-check-circle mr-1"></i> Ouvrage Livré (Après)</span>

						<!-- Image Avant (Recouvrement) -->
						<div class="ba-image-layer ba-before-layer" id="baBeforeLayer" style="background-image: url('images/about.jpg');"></div>
						<span class="ba-tag ba-tag-before"><i class="fa fa-history mr-1"></i> État Initial (Avant)</span>

						<!-- Poignée de Glissement -->
						<div class="ba-handle" id="baHandle">
							<i class="fa fa-arrows-h"></i>
						</div>

						<!-- Range Slider Invisible mais Contrôlable -->
						<input type="range" min="0" max="100" value="50" class="ba-range-slider" id="baRangeSlider" aria-label="Comparer avant et après">
					</div>
				</div>

				<div class="text-center mt-3">
					<span class="text-muted small"><i class="fa fa-hand-pointer-o mr-1" style="color: #ea580c;"></i> <em>Faites glisser horizontalement pour dévoiler la transformation</em></span>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     6. CHIFFRES CLÉS & STATISTIQUES (ANIMÉES - HALO LUMINEUX PRESTIGE)
     ======================================================================== -->
<section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url('images/IMG-20261015-WA0062.jpg');" data-stellar-background-ratio="0.5">
	<div class="overlay" style="background: linear-gradient(135deg, rgba(30, 34, 41, 0.84) 0%, rgba(234, 88, 12, 0.55) 100%);"></div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">
				<div class="row">
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<strong class="number" data-number="<?= htmlspecialchars($expYears) ?>">0</strong>
								<span class="text-white font-weight-bold">Années d'Expérience</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<strong class="number" data-number="<?= htmlspecialchars($totalProjects) ?>">0</strong>
								<span class="text-white font-weight-bold">Chantiers Réalisés</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<strong class="number" data-number="<?= htmlspecialchars($satisfaction) ?>">0</strong>
								<span class="text-white font-weight-bold">% Clients Satisfaits</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<strong class="number" data-number="<?= htmlspecialchars($equipments) ?>">0</strong>
								<span class="text-white font-weight-bold">Engins & Équipements</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     7. NOS SERVICES DYNAMIQUES (CARTES 3D HOVER)
     ======================================================================== -->
<section class="ftco-section bg-light" id="services-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-8 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold" style="letter-spacing: 2px;">NOS MÉTIERS & PÔLES</span>
				<h2 class="mb-4 font-weight-bold">Des Solutions BTP Clés en Main</h2>
				<p class="text-muted">De la conception des plans jusqu'à la remise solennelle des clés, notre expertise couvre l'ensemble du cycle de construction.</p>
			</div>
		</div>
		<div class="row">
			<?php 
			$serviceIndex = 1;
			foreach ($services as $srv): 
				$srvImg = resolveImage($srv['image'] ?? '', 'images/services-' . $serviceIndex . '.jpg');
			?>
				<div class="col-md-4 d-flex ftco-animate mb-4">
					<div class="card-wow-service w-100 d-flex flex-column justify-content-between">
						<div>
							<div style="height: 240px; background-image: url('<?= htmlspecialchars($srvImg) ?>'); background-size: cover; background-position: top center; position: relative;">
								<div class="service-icon-box position-absolute" style="top: 15px; left: 15px; width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(234,88,12,0.4);">
									<i class="fa <?= htmlspecialchars($srv['icon'] ?? 'fa-building') ?>"></i>
								</div>
							</div>
							<div class="p-4">
								<h5 class="font-weight-bold mb-3 text-dark"><?= htmlspecialchars($srv['title']) ?></h5>
								<p class="text-muted small mb-0">
									<?= htmlspecialchars($srv['short_desc'] ?? '') ?>
								</p>
							</div>
						</div>
						<div class="p-4 pt-0">
							<a href="contact.php?type=devis&service=<?= urlencode($srv['title']) ?>" class="btn btn-outline-primary btn-sm btn-block font-weight-bold py-2">
								<i class="fa fa-calculator mr-1"></i> Cotation pour ce corps d'état
							</a>
						</div>
					</div>
				</div>
			<?php 
				$serviceIndex++;
				if ($serviceIndex > 6) $serviceIndex = 1;
			endforeach; 
			?>
		</div>
		<div class="text-center mt-3">
			<a href="services.php" class="btn btn-primary py-3 px-5 font-weight-bold">
				Voir Tous Nos Domaines d'Intervention <i class="fa fa-arrow-right ml-1"></i>
			</a>
		</div>
	</div>
</section>

<!-- ========================================================================
     8. SIMULATEUR EXPRESS CLÉS EN MAIN (RÉSULTAT CHIFFRÉ IMMÉDIAT EN 1 CLIC)
     ======================================================================== -->
<section class="fee-wizard-section py-5" id="simulateur-section" style="background: #ffffff; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
	<div class="container">
		<div class="row justify-content-center text-center mb-5">
			<div class="col-lg-10 ftco-animate">
				<div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: #fff7ed; border: 1.5px solid #fed7aa; color: #ea580c; font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
					<i class="fa fa-calculator mr-1"></i> ESTIMATEUR EXPRESS CLÉS EN MAIN
				</div>
				<h2 class="font-weight-bold mb-3" style="font-size: 2.3rem; color: #0f172a; letter-spacing: -0.01em;">
					Calculez Votre Projet & Visualisez le Résultat Immédiatement
				</h2>
				<p class="text-muted lead mx-auto" style="max-width: 820px; font-size: 1.1rem; line-height: 1.7;">
					Ajustez le type d'ouvrage et la superficie souhaitée : le <strong>budget clés en main</strong> et le <strong>délai de réalisation</strong> s'affichent instantanément en temps réel.
				</p>
			</div>
		</div>

		<div class="p-4 p-lg-5 rounded-4 shadow-sm" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 24px;">
			<div class="row align-items-center">
				<!-- Colonne Gauche : Paramètres Visuels Simples -->
				<div class="col-lg-7 pr-lg-5 mb-4 mb-lg-0 text-left">
					<!-- 1. Type d'Ouvrage -->
					<label class="font-weight-bold text-dark small text-uppercase mb-2 d-block" style="letter-spacing: 1px;">
						1. Choisissez Votre Ouvrage :
					</label>
					<div class="row g-2 mb-4">
						<div class="col-6 col-sm-3 mb-2">
							<div class="fee-wizard-card selected p-3 text-center h-100" data-wiz-type="villa" style="cursor: pointer; border-radius: 14px;">
								<i class="fa fa-home text-warning mb-2" style="font-size: 24px;"></i>
								<div class="font-weight-bold text-dark" style="font-size: 13px;">Villa Duplex</div>
								<small class="text-muted d-block" style="font-size: 11px;">Résidentiel</small>
							</div>
						</div>
						<div class="col-6 col-sm-3 mb-2">
							<div class="fee-wizard-card p-3 text-center h-100" data-wiz-type="building" style="cursor: pointer; border-radius: 14px;">
								<i class="fa fa-building text-warning mb-2" style="font-size: 24px;"></i>
								<div class="font-weight-bold text-dark" style="font-size: 13px;">Immeuble R+2/3</div>
								<small class="text-muted d-block" style="font-size: 11px;">Locatif</small>
							</div>
						</div>
						<div class="col-6 col-sm-3 mb-2">
							<div class="fee-wizard-card p-3 text-center h-100" data-wiz-type="retaining" style="cursor: pointer; border-radius: 14px;">
								<i class="fa fa-shield text-warning mb-2" style="font-size: 24px;"></i>
								<div class="font-weight-bold text-dark" style="font-size: 13px;">Soutènement</div>
								<small class="text-muted d-block" style="font-size: 11px;">Anti-glissement</small>
							</div>
						</div>
						<div class="col-6 col-sm-3 mb-2">
							<div class="fee-wizard-card p-3 text-center h-100" data-wiz-type="industrial" style="cursor: pointer; border-radius: 14px;">
								<i class="fa fa-industry text-warning mb-2" style="font-size: 24px;"></i>
								<div class="font-weight-bold text-dark" style="font-size: 13px;">Hangar</div>
								<small class="text-muted d-block" style="font-size: 11px;">Entrepôt métal</small>
							</div>
						</div>
					</div>

					<!-- 2. Superficie -->
					<div class="mb-4">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<label class="font-weight-bold text-dark small text-uppercase mb-0" style="letter-spacing: 1px;">
								2. Superficie Estimée :
							</label>
							<span class="badge badge-warning text-dark font-weight-bold px-3 py-1" style="font-size: 15px;" id="feewizSurfaceVal">200 m²</span>
						</div>
						<input type="range" min="60" max="800" step="10" value="200" class="form-control-range" id="feewizSurfaceRange">
						<div class="d-flex justify-content-between text-muted small mt-1" style="font-size: 11px;">
							<span>60 m² (Villa standard)</span>
							<span>300 m² (Grand duplex)</span>
							<span>800 m² (Immeuble / Hangar)</span>
						</div>
					</div>

					<!-- 3. Standing / Finition -->
					<label class="font-weight-bold text-dark small text-uppercase mb-2 d-block" style="letter-spacing: 1px;">
						3. Niveau de Finition Souhaité :
					</label>
					<div class="row g-2 mb-3">
						<div class="col-4">
							<div class="fee-wizard-card p-2 text-center h-100" data-wiz-standing="standard" style="cursor: pointer; border-radius: 12px;">
								<div class="font-weight-bold text-dark" style="font-size: 12px;">Éco-Rigueur</div>
								<small class="text-muted d-block" style="font-size: 10px;">Gros œuvre + base</small>
							</div>
						</div>
						<div class="col-4">
							<div class="fee-wizard-card selected p-2 text-center h-100" data-wiz-standing="comfort" style="cursor: pointer; border-radius: 12px;">
								<div class="font-weight-bold text-dark" style="font-size: 12px;">Grand Confort</div>
								<small class="text-muted d-block" style="font-size: 10px;">Clés en main moderne</small>
							</div>
						</div>
						<div class="col-4">
							<div class="fee-wizard-card p-2 text-center h-100" data-wiz-standing="prestige" style="cursor: pointer; border-radius: 12px;">
								<div class="font-weight-bold text-dark" style="font-size: 12px;">Prestige VIP</div>
								<small class="text-muted d-block" style="font-size: 10px;">Finition luxe diaspora</small>
							</div>
						</div>
					</div>
				</div>

				<!-- Colonne Droite : RÉSULTAT IMMÉDIAT EN TEMPS RÉEL -->
				<div class="col-lg-5">
					<div class="fee-wizard-summary-box text-left p-4 p-md-4 shadow-lg" style="background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; border: 1px solid #334155;">
						<div class="d-flex align-items-center justify-content-between mb-2">
							<span class="badge badge-warning text-dark font-weight-bold px-3 py-1" style="font-size: 11px; border-radius: 50px;">
								<i class="fa fa-flash mr-1"></i> RÉSULTAT CHIFFRÉ EN TEMPS RÉEL
							</span>
							<span class="text-white-50 small"><i class="fa fa-calendar-check-o mr-1"></i> Clés en main</span>
						</div>

						<small class="text-uppercase font-weight-bold text-white-50 d-block mt-2" style="font-size: 11px; letter-spacing: 1px;">
							Fourchette Estimative Totale :
						</small>
						<div class="fee-wizard-price-big my-2 font-weight-bold text-warning" id="feewizPriceFcfa" style="font-size: 26px; line-height: 1.2;">
							35 000 000 - 45 000 000 FCFA
						</div>
						<div class="fee-wizard-price-eur mb-3 text-white-50" id="feewizPriceEur" style="font-size: 14px;">
							(~ 53 000 € à 68 000 €)
						</div>

						<!-- Délais & Prestations Incluses -->
						<div class="border-top border-secondary pt-3 mb-3">
							<div class="row text-center text-white-50 small mb-3">
								<div class="col-4 border-right border-secondary">
									<strong class="d-block text-white" id="feewizDuration" style="font-size: 16px;">5 Mois</strong>
									<span style="font-size: 11px;">Délai Garanti</span>
								</div>
								<div class="col-4 border-right border-secondary">
									<strong class="d-block text-white" id="feewizCement" style="font-size: 16px;">~ 1300 Sacs</strong>
									<span style="font-size: 11px;">Ciment Normé</span>
								</div>
								<div class="col-4">
									<strong class="d-block text-white" id="feewizSteel" style="font-size: 16px;">~ 9 T</strong>
									<span style="font-size: 11px;">Acier FeE500</span>
								</div>
							</div>

							<ul class="list-unstyled text-white-50 small mb-0" style="line-height: 1.8; font-size: 11.5px;">
								<li><i class="fa fa-check text-success mr-2"></i> Étude de sol & dimensionnement des structures</li>
								<li><i class="fa fa-check text-success mr-2"></i> Gros œuvre, plomberie, électricité & finitions</li>
								<li><i class="fa fa-check text-success mr-2"></i> Rapport vidéo chaque vendredi sur WhatsApp</li>
								<li><i class="fa fa-check text-success mr-2"></i> Remise des clés & garantie décennale certifiée</li>
							</ul>
						</div>

						<!-- Boutons d'Action 1 Clic -->
						<div class="d-grid gap-2">
							<a href="#" class="btn btn-success btn-block font-weight-bold py-3 mb-2" id="feewizBtnWhatsapp" target="_blank" rel="noopener" style="border-radius: 14px; font-size: 14px; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
								<i class="fab fa-whatsapp mr-2" style="font-size: 18px;"></i> Recevoir ce Devis sur WhatsApp
							</a>
							<button type="button" class="btn btn-outline-light btn-block font-weight-bold py-2" id="feewizBtnPdf" style="border-radius: 12px; font-size: 13px;">
								<i class="fa fa-file-pdf mr-2 text-warning"></i> Télécharger la Fiche Récapitulative PDF
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     9. CHANTIERS & RÉALISATIONS PHARES
     ======================================================================== -->
<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-8 text-center heading-section ftco-animate">
				<span class="subheading text-primary font-weight-bold" style="letter-spacing: 2px;">PORTFOLIO BTP</span>
				<h2 class="mb-4 font-weight-bold">Nos Dernières Réalisations de Chantiers</h2>
				<p class="text-muted">Découvrez un aperçu de nos projets menés avec succès à Dschang et dans toute la région de l'Ouest.</p>
			</div>
		</div>
		<div class="row">
			<?php 
			// Résolution automatique et rigoureuse des photos réelles de chantiers FEE BTP SARL
			function resolveProjectCardImage($proj, $index = 1) {
				$title = mb_strtolower($proj['title'] ?? '');
				$loc = mb_strtolower($proj['location'] ?? '');

				// 1. Détection intelligente basée sur les mots-clés du projet
				if (strpos($title, 'villa') !== false || strpos($title, 'duplex') !== false || strpos($loc, 'keleng') !== false) {
					return 'images/IMG-20261015-WA0079.jpg';
				}
				if (strpos($title, 'immeuble') !== false || strpos($title, 'standing') !== false || strpos($loc, 'foréké') !== false) {
					return 'images/IMG-20261015-WA0129.jpg';
				}
				if (strpos($title, 'soutènement') !== false || strpos($title, 'banché') !== false || strpos($loc, 'falaise') !== false || strpos($loc, 'esplanade') !== false) {
					return 'images/IMG-20261015-WA0070.jpg';
				}
				if (strpos($title, 'terrassement') !== false || strpos($title, 'nivellement') !== false || strpos($title, 'plateforme') !== false || strpos($loc, 'bafoussam') !== false) {
					return 'images/IMG-20261029-WA0105.jpg';
				}
				if (strpos($title, 'voirie') !== false || strpos($title, 'pavée') !== false || strpos($title, 'pluviale') !== false || strpos($loc, 'axe urbain') !== false) {
					return 'images/IMG-20261015-WA0062.jpg';
				}
				if (strpos($title, 'contrôle') !== false || strpos($title, 'supervision') !== false) {
					return 'images/IMG-20261015-WA0120.jpg';
				}

				// 2. Si le champ main_image pointe vers un fichier existant
				if (!empty($proj['main_image'])) {
					$resolved = resolveImage($proj['main_image'], '');
					if (!empty($resolved)) return $resolved;
				}

				// 3. Fallbacks de photos réelles haute qualité par index
				$defaults = [
					1 => 'images/IMG-20261015-WA0062.jpg',
					2 => 'images/IMG-20261015-WA0079.jpg',
					3 => 'images/IMG-20261029-WA0105.jpg',
					4 => 'images/IMG-20261015-WA0070.jpg',
					5 => 'images/IMG-20261015-WA0129.jpg',
					6 => 'images/IMG-20261015-WA0073.jpg',
				];
				$idxMod = (($index - 1) % 6) + 1;
				return $defaults[$idxMod] ?? 'images/IMG-20261015-WA0079.jpg';
			}

			$projIdx = 1;
			foreach ($projects as $proj): 
				$projImg = resolveProjectCardImage($proj, $projIdx);
			?>
				<div class="col-lg-4 col-md-6 ftco-animate mb-4">
					<div class="project-entry project-card-3d rounded shadow-sm overflow-hidden" style="position: relative; background: #fff;">
						<!-- Zone Image Optimisée : Cadrage "center top" évitant toute coupure en haut -->
						<div class="project-img position-relative" style="height: 275px; background: #0f172a url('<?= htmlspecialchars($projImg) ?>?v=2') center top / cover no-repeat !important;">
							<span class="badge badge-primary position-absolute" style="top: 15px; left: 15px; padding: 6px 10px; font-size: 11px;">
								<?= htmlspecialchars($proj['category'] ?? 'BTP') ?>
							</span>
							<?php if (!empty($proj['video_url'])): ?>
								<span class="badge badge-danger position-absolute" style="top: 15px; right: 15px; padding: 6px 10px; font-size: 11px;">
									<i class="fa fa-video-camera mr-1"></i> Vidéo
								</span>
							<?php endif; ?>
							<a href="<?= htmlspecialchars($projImg) ?>" class="image-popup d-flex justify-content-center align-items-center" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(30, 34, 41,0.5); opacity:0; transition: all 0.3s ease;">
								<span class="fa fa-expand text-white" style="font-size: 26px;"></span>
							</a>
						</div>
						<div class="p-3">
							<h5 class="font-weight-bold mb-1">
								<a href="project-details.php?id=<?= $proj['id'] ?>" class="text-dark">
									<?= htmlspecialchars($proj['title']) ?>
								</a>
							</h5>
							<p class="small text-muted mb-2">
								<i class="fa fa-map-marker text-primary mr-1"></i> <?= htmlspecialchars($proj['location'] ?? 'Dschang') ?>
								• <span class="badge badge-success-btp"><?= htmlspecialchars($proj['status'] ?? 'Terminé') ?></span>
							</p>
							<a href="project-details.php?id=<?= $proj['id'] ?>" class="small font-weight-bold text-primary">
								Voir la fiche complète & photos Avant/Après <i class="fa fa-angle-right ml-1"></i>
							</a>
						</div>
					</div>
				</div>
			<?php 
				$projIdx++;
				if ($projIdx > 6) $projIdx = 1;
			endforeach; 
			?>
		</div>
		<div class="text-center mt-3">
			<a href="project.php" class="btn btn-outline-primary py-3 px-5 font-weight-bold">
				Consulter la Galerie Complète des Chantiers <i class="fa fa-images ml-1"></i>
			</a>
		</div>
	</div>
</section>

<!-- ========================================================================
     9.1 CARTE INTERACTIVE DES RÉALISATIONS AU CAMEROUN (LEAFLET)
     ======================================================================== -->
<section class="fee-map-section" id="cameroun-map-section">
	<div class="container">
		<div class="row justify-content-center text-center mb-5">
			<div class="col-lg-10 ftco-animate">
				<div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: #f1f5f9; border: 1.5px solid #cbd5e1; color: #0f172a; font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
					<i class="fa fa-map mr-1" style="color: #ea580c;"></i> MAILLAGE GÉOGRAPHIQUE & RAYONNEMENT
				</div>
				<h2 class="font-weight-bold mb-3" style="font-size: 2.3rem; color: #0f172a; letter-spacing: -0.02em;">
					Nos Ouvrages d'Ingénierie à Travers le Cameroun
				</h2>
				<p class="text-muted lead mx-auto" style="max-width: 820px; font-size: 1.1rem; line-height: 1.7;">
					Explorez la répartition géographique de nos chantiers de construction, murs de soutènement et projets VRD. Cliquez sur les marqueurs pour consulter les fiches détaillées.
				</p>
			</div>
		</div>

		<div class="fee-map-wrapper ftco-animate">
			<div class="fee-map-header">
				<div>
					<h5 class="font-weight-bold mb-0 text-dark">
						<i class="fa fa-crosshairs text-danger mr-2"></i> Géolocalisation des Chantiers FEE BTP
					</h5>
					<small class="text-muted">Filtrez par pôle urbain pour zoomer sur les réalisations</small>
				</div>
				<div class="fee-map-filters">
					<button type="button" class="fee-map-filter-btn active" data-city="all">Tous les Chantiers</button>
					<button type="button" class="fee-map-filter-btn" data-city="Dschang">Dschang & Environs</button>
					<button type="button" class="fee-map-filter-btn" data-city="Bafoussam">Bafoussam & Ouest</button>
					<button type="button" class="fee-map-filter-btn" data-city="national">Douala & Yaoundé</button>
				</div>
			</div>
			<div id="feeCamerounMap"></div>
		</div>
	</div>
</section>

<!-- ========================================================================
     10. ATTESTATIONS D'EXÉCUTION & CONFIANCE CLIENTS (ÉDITION PRESTIGE)
     ======================================================================== -->
<?php if (!empty($testimonials)): ?>
<section class="ftco-section fee-testimonials-section py-5" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
	<div class="container">
		<!-- En-tête Prestigieux & Confiance -->
		<div class="row justify-content-center mb-5 text-center">
			<div class="col-lg-9 ftco-animate">
				<div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: #fff7ed; border: 1.5px solid #fed7aa; color: #ea580c; font-size: 11.5px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
					<i class="fa fa-certificate mr-1"></i> ATTESTATIONS D'EXÉCUTION & CONFIANCE BTP
				</div>
				<h2 class="font-weight-bold mb-3" style="font-size: 2.3rem; color: #0f172a; letter-spacing: -0.02em;">
					La Confiance Vérifiée de Nos Maîtres d'Ouvrage
				</h2>
				<p class="text-muted lead mx-auto" style="max-width: 780px; font-size: 1.05rem; line-height: 1.7;">
					Promoteurs immobiliers, institutions et familles de la diaspora témoignent de notre rigueur d'ingénierie, du respect strict des calendriers et de la transparence de nos chiffrages.
				</p>
			</div>
		</div>

		<!-- Grille des Attestations (3 Blocs Exécutifs Haut de Gamme) -->
		<div class="row align-items-stretch">
			<?php 
			foreach ($testimonials as $test): 
				$nameParts = explode(' ', trim($test['client_name'] ?? 'Client'));
				$initials = '';
				foreach ($nameParts as $part) {
					$clean = strtolower(trim($part));
					if (!empty($part) && !in_array($clean, ['dr.', 'dr', 'mme', 'm.', 'ing.', 'ing', 'mr', 'mr.', 'pr', 'pr.'])) {
						$initials .= strtoupper(substr($part, 0, 1));
					}
				}
				$initials = substr($initials, 0, 2);
				if (empty($initials)) $initials = 'FT';
			?>
				<div class="col-lg-4 col-md-6 d-flex ftco-animate mb-4 mb-lg-0">
					<div class="fee-testimony-card w-100 d-flex flex-column justify-content-between p-4 rounded-xl" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); transition: all 0.35s ease;">
						
						<div>
							<!-- Haut de Carte : Étoiles + Badge Chantier Réceptionné -->
							<div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: #f1f5f9 !important;">
								<div class="d-flex align-items-center" style="gap: 3px;">
									<?php for ($i = 0; $i < ($test['rating'] ?? 5); $i++): ?>
										<i class="fa fa-star" style="color: #f59e0b; font-size: 13.5px;"></i>
									<?php endfor; ?>
									<span class="ml-1 font-weight-bold text-dark small" style="font-size: 12.5px;">5.0</span>
								</div>
								<span class="badge px-2 py-1" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px; font-weight: 700; border-radius: 50px;">
									<i class="fa fa-shield mr-1"></i> Chantier Livré
								</span>
							</div>

							<!-- Citation Témoignage -->
							<p class="fee-testimony-quote mb-4" style="color: #334155; font-size: 0.95rem; line-height: 1.7; font-weight: 450;">
								« <?= htmlspecialchars($test['comment']) ?> »
							</p>
						</div>

						<!-- Bas de Carte : Auteur, Rôle & Projet -->
						<div class="pt-3 border-top d-flex align-items-center" style="border-color: #f1f5f9 !important;">
							<div class="fee-testimony-avatar mr-3">
								<?= htmlspecialchars($initials) ?>
							</div>
							<div class="overflow-hidden">
								<h6 class="font-weight-bold mb-0 text-dark" style="font-size: 14.5px;"><?= htmlspecialchars($test['client_name']) ?></h6>
								<small class="text-muted d-block" style="font-size: 12px;"><?= htmlspecialchars($test['client_title'] ?? 'Maître d\'Ouvrage') ?></small>
								<small class="d-block font-weight-bold mt-1 text-truncate" style="color: #ea580c; font-size: 11.5px;">
									<i class="fa fa-map-marker mr-1"></i> <?= htmlspecialchars($test['company'] ?? 'Dschang') ?>
								</small>
							</div>
						</div>

					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Bandeau d'Engagement Qualité BTP (Reassurance Metrics Bar) -->
		<div class="row mt-5">
			<div class="col-12 ftco-animate">
				<div class="p-4 rounded-xl d-flex flex-wrap align-items-center justify-content-around text-center" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); gap: 15px;">
					<div class="px-3 py-1">
						<div class="font-weight-bold" style="font-size: 1.75rem; color: #ea580c; line-height: 1.1;">100%</div>
						<small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Délais Contractuels Tenus</small>
					</div>
					<div class="border-left d-none d-lg-block" style="height: 38px; border-color: #cbd5e1 !important;"></div>
					<div class="px-3 py-1">
						<div class="font-weight-bold" style="font-size: 1.75rem; color: #0f172a; line-height: 1.1;">350 kg/m³</div>
						<small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Dosage Béton Normé & Éprouvettes</small>
					</div>
					<div class="border-left d-none d-lg-block" style="height: 38px; border-color: #cbd5e1 !important;"></div>
					<div class="px-3 py-1">
						<div class="font-weight-bold" style="font-size: 1.75rem; color: #ea580c; line-height: 1.1;">0 Avenant</div>
						<small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Transparence Budgétaire Totale</small>
					</div>
					<div class="border-left d-none d-lg-block" style="height: 38px; border-color: #cbd5e1 !important;"></div>
					<div class="px-3 py-1">
						<div class="font-weight-bold" style="font-size: 1.75rem; color: #0f172a; line-height: 1.1;">24/7</div>
						<small class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Reporting Vidéo pour la Diaspora</small>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
<?php endif; ?>

<!-- ========================================================================
     12. CALL TO ACTION FINAL HAUTE VISIBILITÉ (GRADIENT SOLAIRE ORANGE BTP)
     ======================================================================== -->
<section class="ftco-intro py-5" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);">
	<div class="container">
		<div class="row justify-content-between align-items-center">
			<div class="col-md-8 text-white mb-3 mb-md-0">
				<h2 class="text-white font-weight-bold mb-2">Prêt à Démarrer Votre Projet avec FEE BTP ?</h2>
				<p class="mb-0" style="color: rgba(255,255,255,0.92);">Nos ingénieurs et métreurs à Dschang examinent vos plans et vous remettent une étude technique sous 24h.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="contact.php?type=devis" class="btn btn-white text-dark py-3 px-4 font-weight-bold" style="background: #ffffff; color: #c2410c !important; border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
					<i class="fa fa-calculator mr-2" style="color: #ea580c;"></i> Demander une Cotation Gratuite
				</a>
			</div>
		</div>
	</div>
</section>

<!-- ========================================================================
     SCRIPTS INTERACTIFS (SLIDER AVANT/APRÈS)
     ======================================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Script Comparatif Avant/Après Interactif
	const slider = document.getElementById('baRangeSlider');
	const beforeLayer = document.getElementById('baBeforeLayer');
	const handle = document.getElementById('baHandle');

	if (slider && beforeLayer && handle) {
		function updateBA() {
			const val = slider.value;
			beforeLayer.style.width = val + '%';
			handle.style.left = val + '%';
		}
		slider.addEventListener('input', updateBA);
		slider.addEventListener('change', updateBA);
		updateBA();
	}
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
