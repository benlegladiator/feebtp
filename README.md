# FREE BTP SARL - Plateforme Web Officielle & Espace d'Administration

Projet réalisé en exécution du contrat de prestation :  
**RÉFÉRENCE CONTRAT : CT-WEB-FREEBTP-2026-01**

- **Client :** FREE BTP SARL (Siège à Dschang, Région de l'Ouest, Cameroun)  
  *Représentée par M. AGHOKENG TANGMO FABRICE, Gérant / Directeur Général*
- **Développeur :** M. NDONGMO TEJIONA BEN ARTHUR (Yaoundé / Dschang, Cameroun)

---

## 🌟 Présentation de la Solution

Cette solution web d'entreprise BTP & Génie Civil a été développée sur mesure pour satisfaire à 100% les exigences du contrat d'ingénierie web. Elle s'articule autour de deux composantes interconnectées :

### 1. 🏗️ La Plateforme Publique (Frontend Responsive)
- **Page d'Accueil :** Hero banner grand format avec simulation vidéo/slider de chantier, atouts clés, chiffres de performance (+12 ans, +165 chantiers, 99% satisfaction), vitrine des services et formulaire express de cotation.
- **Page À Propos :** Historique de l'entreprise à Dschang, couverture géographique (Ouest, Centre, Littoral), normes HSE & sécurité, présentation de la direction (M. AGHOKENG TANGMO FABRICE) et catalogue des parcs de matériels lourds (pelles hydrauliques, camions-bennes, centrales à béton, laser).
- **Rubrique Nos Services :** Présentation dynamique détaillée de chaque expertise (Gros œuvre, Second œuvre, Génie Civil & VRD, Rénovation, Études techniques et plans, Conduite de travaux) avec bouton direct de cotation.
- **Rubrique Nos Réalisations BTP :** Galerie interactive avec filtres par catégorie (*Résidentiel*, *Commercial*, *Travaux Publics / VRD*, *Industriel*), fiches chantiers, comparatifs avant/après et intégration vidéo/drone.
- **Rubrique Actualités / Blog :** Articles d'information, suivi des chantiers en cours et conseils pratiques de construction en zone montagneuse et tropicale.
- **Page Contact & Localisation :** Coordonnées officielles, intégration Google Maps interactive centrée sur le siège de Dschang, et formulaire de devis avec upload de plans d'architectes et pièces jointes (PDF, DWG, images).
- **Boîte de Dialogue & WhatsApp Direct :** Widget flottant interactif permettant aux visiteurs d'échanger en direct avec le service client (avec enregistrement automatique dans la boîte de réception admin) ou de basculer instantanément sur le WhatsApp officiel (+237 691 00 64 31).

---

### 2. 🔐 L'Espace d'Administration (Dashboard / Backend)
- **Sécurité :** Authentification renforcée avec mots de passe hachés (`password_hash` BCRYPT) et session admin isolée.
- **Tableau de Bord Central :** Indicateurs de performance en temps réel (total messages, devis en attente, projets publiés, pôles d'activités), raccourcis d'actions immédiates et suivi des activités récentes.
- **Boîte de Réception & Demandes de Devis :** Consultation des messages avec filtres (Non lus, Devis, Contact, Livechat, Archivés), téléchargement des pièces jointes, historique de traitement et saisie de notes internes de suivi.
- **Médiathèque Dynamique :** Téléversement d'images HD de chantiers, intégration de vidéos (YouTube / MP4) et gestion centralisée des fichiers.
- **CMS Intégral :**
  - Gestion des Services BTP (ajout, édition, icônes FontAwesome, statut).
  - Gestion des Réalisations BTP (statut En cours/Terminé, photos avant/après, budget, maître d'ouvrage).
  - Gestion des Actualités et articles de blog.
  - Gestion des Avis et témoignages clients (étoiles de notation).
  - Paramètres de l'entreprise (coordonnées Dschang, téléphones (+237 691 00 64 31 / 671 06 34 41), emails, horaires).
  - Gestion du profil et mot de passe maître.

---

## 🔑 Identifiants d'Accès au Dashboard

- **URL d'accès :** `http://localhost/feebtp/admin/login.php`
- **Identifiant :** `admin`
- **Mot de passe par défaut :** `admin123`

*(Ce mot de passe peut être modifié à tout moment depuis le menu **Sécurité & Profil** du Dashboard)*.

---

## 🚀 Démarrage & Exécution

### Option A : Via XAMPP (Apache & MySQL)
1. Démarrez **Apache** et **MySQL** depuis le Panneau de Contrôle XAMPP (`xampp-control.exe`).
2. Ouvrez votre navigateur web sur :
   - Site public : [http://localhost/feebtp/](http://localhost/feebtp/)
   - Administration : [http://localhost/feebtp/admin/](http://localhost/feebtp/admin/)
3. Le système détecte automatiquement MySQL et configure la base `freebtp_db` sans aucune manipulation supplémentaire.

### Option B : Via le serveur PHP autonome (CLI)
Si MySQL n'est pas démarré, le système utilise automatiquement son moteur de repli SQLite intégré (`data/freebtp.sqlite`) :
```bash
cd c:\xampp\htdocs\feebtp
php -S localhost:8000
```
Puis accédez à :
- Site public : [http://localhost:8000/](http://localhost:8000/)
- Administration : [http://localhost:8000/admin/](http://localhost:8000/admin/)

---

## 🌐 Déploiement en Production (cPanel / Hébergement Web)

Conformément à l'Article 3 du contrat :
1. Créez une base de données MySQL dans votre cPanel (ex: `freebtp_db`).
2. Importez le fichier [`database.sql`](file:///c:/xampp/htdocs/feebtp/database.sql) via phpMyAdmin.
3. Ajustez les constantes de connexion dans [`config/db.php`](file:///c:/xampp/htdocs/feebtp/config/db.php) (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).
4. Téléversez les fichiers dans le dossier `public_html` de votre hébergement.
