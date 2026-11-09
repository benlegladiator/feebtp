<?php
/**
 * API Backend Intelligente pour le Live Chat / Boîte de Dialogue
 * Moteur NLP / Traitement de Langage Naturel & Intentions BTP
 * FREE BTP SARL - Dschang, Cameroun
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
    exit;
}

$rawInput = file_get_contents('php://input');
if (empty($rawInput)) {
    $rawInput = @file_get_contents('php://stdin');
}
$data = json_decode($rawInput, true);
$userMessage = trim($data['message'] ?? $_POST['message'] ?? '');

if (empty($userMessage)) {
    echo json_encode(['status' => 'error', 'message' => 'Message vide']);
    exit;
}

$pdo = getDBConnection();
$settings = getSiteSettings($pdo);

$phonePrimary = $settings['phone_primary'] ?? '(+237) 691 00 64 31';
$phoneSecondary = $settings['phone_secondary'] ?? '(+237) 671 06 34 41';
$whatsappNum = $settings['whatsapp_number'] ?? '237691006431';
$emailContact = $settings['email_contact'] ?? 'contact@freebtp-sarl.com';
$managerName = $settings['manager_name'] ?? 'M. AGHOKENG TANGMO FABRICE';
$addressDschang = $settings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun';

// 1. Sauvegarde dans la table des messages pour l'Admin
try {
    $stmt = $pdo->prepare("INSERT INTO messages (type, sender_name, sender_email, sender_phone, subject, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        'livechat',
        'Visiteur Web (Live Chat)',
        'contact@visiteur-web.cm',
        '(Non précisé)',
        'Échange en direct via boîte de dialogue',
        $userMessage,
        'unread'
    ]);
} catch (Exception $e) {
    // Continuer malgré l'erreur
}

// 2. Normalisation du texte utilisateur
function normalizeInput($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $replacements = [
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'à' => 'a', 'â' => 'a', 'ä' => 'a',
        'î' => 'i', 'ï' => 'i',
        'ô' => 'o', 'ö' => 'o',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c'
    ];
    $str = strtr($str, $replacements);
    $str = preg_replace('/[^\w\s]/u', ' ', $str);
    return trim(preg_replace('/\s+/', ' ', $str));
}

$norm = normalizeInput($userMessage);
$reply = "";
$suggestions = [];

// ============================================================================
// 3. MOTEUR DE CORRESPONDANCE D'INTENTIONS CONVERSATIONNELLES BTP
// ============================================================================

// 1. Intervention HORS DU CAMEROUN / À L'ÉTRANGER / SOUS-RÉGION / CEMAC / DIASPORA
if (preg_match('/\b(hors du cameroun|hors cameroun|hors du pays|en dehors du cameroun|etranger|l etranger|international|autre pays|autres pays|sous region|cemac|afrique centrale|tchad|gabon|congo|centrafrique|rca|guinee equatoriale|exterieur|diaspora|france|europe|usa|etats unis|canada|belgique|suisse|allemagne|italie|angleterre|construire au pays)\b/u', $norm)) {
    $reply = "Concernant nos interventions <strong>hors du Cameroun</strong> et pour les Camerounais de la <strong>diaspora</strong> : 🌍<br><br>"
           . "1️⃣ <strong>Vous résidez à l'étranger (France, Europe, USA, Canada...) :</strong> Nous gérons la construction complète de votre villa, duplex ou immeuble au Cameroun en toute confiance et transparence. Vous bénéficiez de réunions de chantier par visio, de rapports photos et vidéos immersives par drone chaque semaine, et de paiements échelonnés sécurisés.<br><br>"
           . "2️⃣ <strong>Pour des chantiers physiques hors du Cameroun (CEMAC / Afrique Centrale) :</strong> Nos parcs de matériels lourds sont basés au Cameroun. Dans la sous-région (Tchad, RCA, Gabon, Congo, Guinée Équatoriale), nous intervenons principalement sur missions d'<strong>Assistance à Maîtrise d'Ouvrage (AMO)</strong>, études de structures et supervision technique.<br><br>"
           . "Où résidez-vous actuellement et où se situe votre projet ?";
    $suggestions = ["🇨🇲 Construire au Cameroun pour la diaspora", "📐 Étude technique / Maîtrise d'œuvre", "💬 Discuter sur WhatsApp (+237 691 00 64 31)"];

// 2. Délais, Durée de construction (Vérifié avant 'devis' pour intercepter 'combien de temps')
} elseif (preg_match('/\b(delai|delais|duree|combien de temps|combien de mois|combien de jours|temps de construction)\b/u', $norm)) {
    $reply = "Le <strong>respect strict des délais</strong> est une clause contractuelle fondamentale chez FREE BTP SARL. ⏱️<br><br>"
           . "À titre indicatif :<br>"
           . "• Villa individuelle moderne : <strong>4 à 6 mois</strong>.<br>"
           . "• Immeuble résidentiel R+2 à R+4 : <strong>8 à 12 mois</strong>.<br>"
           . "• Hangar logistique industriel : <strong>4 à 6 mois</strong>.<br>"
           . "• Travaux de voirie pavée et assainissement : <strong>3 à 5 mois</strong> selon le linéaire.<br><br>"
           . "Chaque marché est encadré par un planning prévisionnel (diagramme de Gantt) validé conjointement avec le maître d'ouvrage.";
    $suggestions = ["📊 Chiffrer mon projet", "💬 WhatsApp Direction", "📞 Appeler le (+237) 691 00 64 31"];

// 3. Ouvrages d'art, Ponts, Dalots, Buses, Passerelles
} elseif (preg_match('/\b(pont|ponts|passerelle|passerelles|dalot|dalots|buse|buses|viaduc|ouvrages? d art)\b/u', $norm)) {
    $reply = "Oui, absolument ! Le <strong>Génie Civil</strong> et la construction d'<strong>ouvrages d'art</strong> constituent l'un des piliers majeurs de <strong>FREE BTP SARL</strong>. 🌉<br><br>"
           . "Nos équipes d'ingénieurs conçoivent et réalisent :<br>"
           . "• Des ponts à poutres et tabliers en béton armé.<br>"
           . "• Des dalots simples et multiples pour le franchissement de cours d'eau.<br>"
           . "• Des buses de drainage des eaux pluviales et canalisations lourdes.<br>"
           . "• Des radiers submersibles et murs de soutènement en béton banché.<br><br>"
           . "Avez-vous déjà une étude topographique ou hydraulique du site ?";
    $suggestions = ["📊 Demander une étude technique", "💬 Échanger sur WhatsApp (+237 691 00 64 31)", "🏗️ Voir nos projets VRD & Voiries"];

// 3. Terrains, Parcelles, Vente / Achat foncier
} elseif (preg_match('/\b(terrain|terrains|parcelle|parcelles|titre foncier|vendez des terrains?|achat de terrain|vendre un terrain)\b/u', $norm)) {
    $reply = "<strong>FREE BTP SARL</strong> est une société d'ingénierie, de <strong>Bâtiment et Travaux Publics</strong> et non une agence foncière : nous ne vendons donc pas directement de terrains. 🏗️<br><br>"
           . "En revanche, <strong>dès que votre parcelle est acquise</strong>, nous intervenons immédiatement :<br>"
           . "1️⃣ Étude géotechnique de sol & relevé topographique.<br>"
           . "2️⃣ Terrassement mécanique, nivellement et fouilles.<br>"
           . "3️⃣ Clôture maçonnée sécurisée et portail.<br>"
           . "4️⃣ Conception des plans architecturaux et construction intégrale clé en main.<br><br>"
           . "Disposez-vous déjà d'un terrain à bâtir ?";
    $suggestions = ["📐 Études de sol & Plans", "🧱 Gros Œuvre & Maçonnerie", "📞 Joindre un ingénieur"];

// 4. Remerciements, Courtoisie, Clôture
} elseif (preg_match('/\b(merci|remercie|thanks|thx|super|parfait|c est gentil|impeccable|tres bien|d accord|daccord|ok|c est note)\b/u', $norm)) {
    $reply = "Je vous en prie ! C'est un réel plaisir de vous accompagner. 😊<br><br>"
           . "Avez-vous une autre question concernant un chantier, des délais, des matériaux ou souhaitez-vous échanger directement avec un de nos conducteurs de travaux ?";
    $suggestions = ["📊 Demander un devis gratuit", "📍 Où se trouve votre siège ?", "💬 Parler sur WhatsApp (+237 691 00 64 31)"];

// 5. Types de bâtiments (Villa, Duplex, Immeuble, Hangar, École, Hôtel...)
} elseif (preg_match('/\b(villa|villas|duplex|immeuble|immeubles|r\+1|r\+2|r\+3|r\+4|r\+5|hangar|hangars|entrepot|entrepots|magasin|hotel|ecole|clinique|hopital|residence)\b/u', $norm)) {
    $reply = "C'est l'un de nos cœurs de métier ! 🏢<br><br>"
           . "Que ce soit pour une <strong>villa moderne haut standing</strong>, un <strong>duplex</strong>, un <strong>immeuble résidentiel R+1 à R+4</strong> ou un <strong>hangar industriel</strong>, FREE BTP SARL garantit :<br>"
           . "• Des fondations dimensionnées aux règles de l'art parasismiques.<br>"
           . "• Des structures en béton armé certifié (essais laboratoire).<br>"
           . "• Des finitions irréprochables (carrelage, électricité, plomberie, peinture).<br>"
           . "• Le strict respect des délais contractuels.<br><br>"
           . "Avez-vous déjà des plans d'architecte ou souhaitez-vous que notre bureau d'études les conçoive pour vous ?";
    $suggestions = ["📊 Demander une estimation de coût", "📐 Bureau d'études & Plans 3D", "💬 Échanger sur WhatsApp (+237 691 00 64 31)"];

// 6. Devis, Tarifs, Coûts, Prix, Budget, Estimation (DQE)
} elseif (preg_match('/\b(devis|prix|tarif|tarifs|cout|couts|combien|estimation|chiffrage|cotation|proforma|budget|dqe)\b/u', $norm)) {
    $reply = "Pour obtenir un <strong>devis quantitatif et estimatif détaillé (DQE)</strong>, rapide et transparent, voici la démarche :<br><br>"
           . "1️⃣ Remplir directement notre <strong>formulaire de devis en ligne</strong> (vous pouvez y joindre vos plans ou croquis PDF/image).<br>"
           . "2️⃣ Ou nous envoyer directement les informations par <strong>WhatsApp au " . $phonePrimary . "</strong>.<br><br>"
           . "Nos études de prix sont <strong>100% gratuites</strong> et établies par nos ingénieurs chiffreurs sous 24 à 48 heures !";
    $suggestions = ["📝 Remplir le formulaire de devis", "💬 Envoyer mes plans sur WhatsApp", "📞 Appeler le (+237) 691 00 64 31"];

// 7. Modalités de paiement, Tranches, Avance, Échéancier
} elseif (preg_match('/\b(paiement|paiements|tranches?|avance|echeance|echeancier|reglement|versement|acompte|facturation)\b/u', $norm)) {
    $reply = "Chez <strong>FREE BTP SARL</strong>, la transparence financière est totale. Les règlements s'effectuent par <strong>tranches liées aux étapes physiques du chantier</strong> : 💳<br><br>"
           . "• <strong>1ère Tranche :</strong> Avance de démarrage à la signature du contrat (approvisionnement matériaux et implantation).<br>"
           . "• <strong>2ème Tranche :</strong> À l'achèvement du gros œuvre (coulage dalle, élévation).<br>"
           . "• <strong>3ème Tranche :</strong> À la livraison définitive et réception des clés sans réserve.<br><br>"
           . "Moyens acceptés : Virement bancaire, chèque ou Mobile Money sécurisé au (+237) 691 67 06 26.";
    $suggestions = ["📊 Obtenir un devis avec échéancier", "💬 Contacter la direction"];

// 8. Forages, Puits, Assainissement, Fosse septique
} elseif (preg_match('/\b(forage|forages|puits|eau|adduction|fosse|fosses|septique|puisard|drainage|caniveau|caniveaux)\b/u', $norm)) {
    $reply = "Oui ! Nous prenons en charge l'ensemble des travaux d'<strong>hydraulique et assainissement</strong> : 💧<br><br>"
           . "• Réalisation de fosses septiques étanches ou écologiques et puisards filtrants.<br>"
           . "• Pose de caniveaux en béton armé et dalots d'évacuation des eaux pluviales.<br>"
           . "• Réseaux d'adduction en eau potable et raccordement aux forages et châteaux d'eau.";
    $suggestions = ["📊 Chiffrer un assainissement", "💬 WhatsApp Direct"];

// 9. Localisation, Siège, Adresse, Où se trouve l'entreprise
} elseif (preg_match('/\b(ou|adresse|situe|situee|situez|localisation|trouver|siege|bureaux?|implantation)\b/u', $norm)) {
    $reply = "Le siège social officiel de <strong>FREE BTP SARL</strong> est établi à <strong>" . $addressDschang . "</strong>. 📍<br><br>"
           . "📍 Vous pouvez localiser notre siège directement sur la carte Google Maps disponible sur notre page Contact.<br>"
           . "🌐 Nous disposons d'une <strong>couverture nationale</strong> et intervenons avec nos équipes et engins lourds sur l'ensemble du Cameroun : <strong>Dschang, Bafoussam, Yaoundé, Douala, Kribi, etc.</strong><br><br>"
           . "Nos bureaux vous accueillent du Lundi au Vendredi de 07h30 à 18h00, et le Samedi de 08h00 à 13h00.";
    $suggestions = ["🗺️ Voir sur Google Maps", "📞 Appeler le standard (+237 691 00 64 31)", "🏗️ Nos chantiers livrés"];

// 10. Salutations & Entrée en matière
} elseif (preg_match('/\b(bonjour|bonsoir|salut|hello|coucou|bjr|bnsr|allo|bonjournee)\b/u', $norm)) {
    $reply = "Bonjour et bienvenue chez <strong>FREE BTP SARL</strong> ! 🏗️<br><br>"
           . "Nous sommes à votre disposition pour vous orienter sur vos travaux de <strong>Gros Œuvre, Génie Civil & VRD, Second Œuvre ou Rénovation</strong>.<br><br>"
           . "Quel est votre projet aujourd'hui ?";
    $suggestions = ["📊 Demander un devis", "🌉 Ouvrages d'art & Ponts", "🏗️ Quels sont vos services ?", "📍 Où êtes-vous situés ?"];

// 11. Services & Expertises générales
} elseif (preg_match('/\b(services?|expertises?|metiers?|que faites vous|vous faites quoi|activites?|competences?)\b/u', $norm)) {
    $reply = "Chez <strong>FREE BTP SARL</strong>, nous couvrons 6 pôles majeurs d'ingénierie et de travaux :<br><br>"
           . "1️⃣ <strong>Gros Œuvre & Maçonnerie :</strong> Fondations renforcées, béton armé, élévation, dalles et charpentes.<br>"
           . "2️⃣ <strong>Second Œuvre & Finitions :</strong> Électricité, plomberie, carrelage grand format, étanchéité, staff & peinture.<br>"
           . "3️⃣ <strong>Génie Civil & VRD :</strong> Voiries pavées/bitumées, caniveaux, dalots, ponts et assainissement.<br>"
           . "4️⃣ <strong>Rénovation Immobilière :</strong> Reprise en sous-œuvre, modernisation de façades et surélévations.<br>"
           . "5️⃣ <strong>Bureau d'Études & Architecture :</strong> Plans 2D/3D, calculs de structures, devis estimatifs (DQE).<br>"
           . "6️⃣ <strong>Pilotage de Chantier (OPC) :</strong> Supervision rigoureuse et contrôle qualité HSE.<br><br>"
           . "Lequel de ces domaines vous intéresse ?";
    $suggestions = ["🧱 Gros Œuvre", "🛣️ Génie Civil & VRD", "🎨 Second Œuvre & Finitions", "📊 Devis rapide"];

// 12. Direction & Gérance
} elseif (preg_match('/\b(gerant|directeur|patron|responsable|dirigeant|fondateur|aghokeng|fabrice|qui dirige)\b/u', $norm)) {
    $reply = "L'entreprise <strong>FREE BTP SARL</strong> est dirigée par <strong>" . $managerName . "</strong>, en sa qualité de Gérant / Directeur Général. 👔<br><br>"
           . "Pour convenir d'un rendez-vous professionnel, d'un partenariat ou d'une visite de chantier, vous pouvez contacter directement le secrétariat de direction au <strong>" . $phonePrimary . "</strong> ou par email à <strong>" . ($settings['email_direction'] ?? 'direction@freebtp-sarl.com') . "</strong>.";
    $suggestions = ["📞 Contacter la Direction", "💬 Écrire sur WhatsApp", "📄 À Propos de l'entreprise"];

// 13. Villes camerounaises (Yaoundé, Douala, Bafoussam, Kribi...)
} elseif (preg_match('/\b(yaounde|douala|bafoussam|kribi|bamenda|garoua|maroua|bertoua|ebolowa|ouest|littoral|centre|deplacement|partout)\b/u', $norm)) {
    $reply = "Tout à fait ! Bien que notre siège historique soit établi à <strong>Dschang</strong> (Région de l'Ouest), nos équipes d'ingénieurs et nos parcs d'engins opèrent sur <strong>tout le territoire camerounais</strong> (Yaoundé, Douala, Bafoussam, Kribi, etc.). 🇨🇲<br><br>"
           . "Nos équipes logistiques assurent le transport des matériaux et des engins directement sur votre site.";
    $suggestions = ["📊 Confier un projet", "💬 Discuter sur WhatsApp", "🏗️ Voir nos réalisations"];

// 14. Parcs d'engins, Matériel, Pelles, Camions, Location
} elseif (preg_match('/\b(materiel|materiels|engins?|pelle|pelleteuse|camion|camions|benne|tractopelle|compacteur|betonniere|location)\b/u', $norm)) {
    $reply = "FREE BTP SARL dispose de son propre <strong>parc matériel moderne et autonome</strong> : 🚜<br><br>"
           . "• Pelles hydrauliques sur chenilles et tractopelles pour terrassements massifs.<br>"
           . "• Flotte de camions bennes 6x4 et 8x4 pour transport d'agrégats et évacuation.<br>"
           . "• Centrales et bétonnières haute cadence pour coulage continu.<br>"
           . "• Rouleaux compacteurs tandem vibrants et dames sauteuses.<br>"
           . "• Stations totales électroniques et lasers pour implantations millimétriques.<br><br>"
           . "Ce parc nous confère une réactivité totale sur tous nos chantiers !";
    $suggestions = ["🚜 Découvrir nos équipements", "📊 Chiffrer un terrassement", "📞 Joindre la logistique"];

// 15. Délais, Durée de construction
} elseif (preg_match('/\b(delai|delais|duree|combien de temps|combien de mois|temps de construction)\b/u', $norm)) {
    $reply = "Le <strong>respect strict des délais</strong> est une clause contractuelle d'honneur chez FREE BTP SARL. ⏱️<br><br>"
           . "À titre indicatif :<br>"
           . "• Villa individuelle moderne : <strong>4 à 6 mois</strong>.<br>"
           . "• Immeuble résidentiel R+2 à R+4 : <strong>8 à 12 mois</strong>.<br>"
           . "• Hangar logistique industriel : <strong>4 à 6 mois</strong>.<br>"
           . "• Travaux de voirie pavée et assainissement : <strong>3 à 5 mois</strong> selon le linéaire.<br><br>"
           . "Chaque marché est encadré par un planning prévisionnel (diagramme de Gantt) validé conjointement avec le maître d'ouvrage.";
    $suggestions = ["📊 Chiffrer mon projet", "💬 WhatsApp Direction", "📞 Appeler le (+237) 691 00 64 31"];

// 16. Normes, Sécurité, Assurance, Garantie Décennale
} elseif (preg_match('/\b(normes?|garantie|garanties|decennale|assurance|securite|hse|qualite|laboratoire)\b/u', $norm)) {
    $reply = "La sécurité et la pérennité de vos investissements sont au cœur de nos engagements :<br><br>"
           . "🛡️ <strong>Garantie Décennale :</strong> Protection complète de la structure et du gros œuvre.<br>"
           . "🧪 <strong>Contrôle Laboratoire :</strong> Essais d'écrasement du béton et analyses géotechniques des sols.<br>"
           . "🦺 <strong>Normes HSE :</strong> Équipements de protection certifiés (EPI) et zéro accident toléré sur nos chantiers.";
    $suggestions = ["🏗️ Voir nos réalisations", "📊 Demande de devis", "💬 WhatsApp Direct"];

// 17. Recrutement, Stage, Emploi
} elseif (preg_match('/\b(recrutement|stage|stagiaire|emploi|job|embauche|postuler|cv|candidature)\b/u', $norm)) {
    $reply = "FREE BTP SARL accueille régulièrement de nouveaux talents (ingénieurs génie civil, conducteurs de travaux, techniciens topographes et ouvriers qualifiés). 👷‍♂️<br><br>"
           . "Pour soumettre votre candidature (emploi ou stage), envoyez votre CV et lettre de motivation à :<br>"
           . "📧 <strong>" . $emailContact . "</strong> avec pour objet <em>« Candidature - Votre Spécialité »</em>, ou déposez votre dossier physique à notre siège de Dschang.";
    $suggestions = ["📧 Écrire à contact@freebtp-sarl.com", "📍 Adresse du siège"];

// 18. Réponse par défaut intelligente
} else {
    $reply = "Merci pour votre question ! Pour vous apporter une orientation technique et tarifaire sur-mesure :<br><br>"
           . "👉 Précisez-nous votre besoin (construction neuve, gros œuvre, voirie & pont, rénovation ou devis).<br>"
           . "👉 Ou contactez directement nos ingénieurs au <strong>" . $phonePrimary . "</strong> / <strong>" . $phoneSecondary . "</strong>.<br>"
           . "👉 Vous pouvez également nous écrire sur WhatsApp pour une réponse instantanée.";
    $suggestions = ["📊 Demander un devis", "🌉 Pouvez-vous construire un pont ?", "🌍 Travaux hors du Cameroun ?", "💬 WhatsApp Direct (+237 691 00 64 31)"];
}

echo json_encode([
    'status' => 'success',
    'reply' => $reply,
    'suggestions' => $suggestions
]);
