<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light navbar-clean-white sticky-top" id="ftco-navbar">
	<div class="container d-flex align-items-center justify-content-between">
		
		<!-- 1. Logo Officiel FEE BTP SARL (Haute Définition Calibré Pro) -->
		<a class="navbar-brand d-flex align-items-center text-decoration-none py-1" href="index.php" title="<?= htmlspecialchars($companyName ?? 'FEE BTP SARL') ?>">
			<img src="images/logo.png" alt="<?= htmlspecialchars($companyName ?? 'FEE BTP SARL') ?>" class="brand-logo-img" style="height: 58px; width: auto; max-height: 62px; object-fit: contain;">
		</a>

		<!-- Bouton Toggler Mobile -->
		<button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Menu principal">
			<span class="fa fa-bars" style="font-size: 24px; color: #1e2229;"></span>
		</button>

		<!-- 2. Liens de Navigation Modernes & Aérés (Dynamiques & Micro-Interactions) -->
		<div class="collapse navbar-collapse justify-content-center" id="ftco-nav">
			<ul class="navbar-nav mx-auto align-items-center fee-nav-magnetic-group">
				<li class="nav-item">
					<a href="index.php" class="nav-link nav-link-clean <?= in_array($currentPage, ['index.php', '']) ? 'active' : '' ?>">
						<i class="fa fa-home nav-icon-dynamic"></i>
						<span>Accueil</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="about.php" class="nav-link nav-link-clean <?= $currentPage === 'about.php' ? 'active' : '' ?>">
						<i class="fa fa-building-o nav-icon-dynamic"></i>
						<span>À Propos</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="services.php" class="nav-link nav-link-clean <?= $currentPage === 'services.php' ? 'active' : '' ?>">
						<i class="fa fa-cogs nav-icon-dynamic"></i>
						<span>Services</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="project.php" class="nav-link nav-link-clean <?= in_array($currentPage, ['project.php', 'project-details.php']) ? 'active' : '' ?>">
						<i class="fa fa-hard-hat nav-icon-dynamic"></i>
						<span>Nos Chantiers</span>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link nav-link-clean dropdown-toggle" href="#" id="navbarDropdownTools" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-magic nav-icon-dynamic"></i>
						<span>Innovations</span>
					</a>
					<div class="dropdown-menu shadow border-0" aria-labelledby="navbarDropdownTools" style="border-radius: 14px; padding: 10px; min-width: 270px; margin-top: 10px;">
						<a class="dropdown-item py-2 d-flex align-items-center" href="index.php#bim-3d-experience">
							<i class="fa fa-cube text-primary mr-3" style="font-size: 18px; width: 22px; text-align: center;"></i>
							<div>
								<strong class="d-block text-dark" style="font-size: 13px;">Studio 3D BIM</strong>
								<small class="text-muted">Maquette architecturale 360°</small>
							</div>
						</a>
						<a class="dropdown-item py-2 d-flex align-items-center" href="index.php#diaspora-tracker">
							<i class="fa fa-globe text-success mr-3" style="font-size: 18px; width: 22px; text-align: center;"></i>
							<div>
								<strong class="d-block text-dark" style="font-size: 13px;">Suivi Chantier Diaspora</strong>
								<small class="text-muted">Reporting & éprouvettes en direct</small>
							</div>
						</a>
						<a class="dropdown-item py-2 d-flex align-items-center" href="index.php#simulateur-section">
							<i class="fa fa-sliders text-warning mr-3" style="font-size: 18px; width: 22px; text-align: center;"></i>
							<div>
								<strong class="d-block text-dark" style="font-size: 13px;">Configurateur Devis</strong>
								<small class="text-muted">Chiffrage express & export PDF</small>
							</div>
						</a>
						<a class="dropdown-item py-2 d-flex align-items-center" href="index.php#cameroun-map-section">
							<i class="fa fa-map-marker text-danger mr-3" style="font-size: 18px; width: 22px; text-align: center;"></i>
							<div>
								<strong class="d-block text-dark" style="font-size: 13px;">Carte des Réalisations</strong>
								<small class="text-muted">Maillage Dschang, Ouest & Cameroun</small>
							</div>
						</a>
					</div>
				</li>
				<li class="nav-item">
					<a href="contact.php" class="nav-link nav-link-clean <?= $currentPage === 'contact.php' ? 'active' : '' ?>">
						<i class="fa fa-envelope-o nav-icon-dynamic"></i>
						<span>Contact</span>
					</a>
				</li>
			</ul>

			<!-- 3. Actions Rapides (Ligne Ingénieur BTP Pulsante + Devis Express) -->
			<div class="d-flex align-items-center flex-wrap mt-3 mt-lg-0">
				<!-- Cartouche Appel Direct Ingénieur Dynamique & Radar -->
				<a href="tel:<?= preg_replace('/[^0-9+]/', '', $phonePrimary ?? '+237691006431') ?>" class="call-card-nav d-none d-xl-flex mr-3" title="Ligne directe de l'ingénieur de permanence">
					<div class="call-card-icon position-relative">
						<span class="call-radar-ping"></span>
						<i class="fa fa-phone"></i>
					</div>
					<div class="call-card-text">
						<div class="d-flex align-items-center">
							<small>Ligne Ingénieur BTP</small>
							<span class="live-status-dot-sm ml-1"></span>
						</div>
						<strong><?= htmlspecialchars($phonePrimary ?? '(+237) 691 00 64 31') ?></strong>
					</div>
				</a>
				
				<!-- Bouton Devis Express BTP Haut Relief avec Rayon Lumineux Shimmer -->
				<a href="#" class="btn btn-devis-express-glow font-weight-bold py-2 px-3 text-white" data-toggle="modal" data-target="#modalDevisRapide">
					<i class="fa fa-calculator mr-1"></i> Devis Express
				</a>
			</div>
		</div>

	</div>
</nav>
