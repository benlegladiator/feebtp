<?php
/**
 * API Chatbot Intelligent & Concierge BTP - FEE BTP SARL
 * Architecture Double-Cerveau :
 * 1. Moteur Génératif Cloud (Google Gemini AI) si clé configurée
 * 2. Moteur Sémantique Local Haute Densité (30+ scénarios BTP Cameroun / Diaspora / Ouest)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/db.php';

// Récupération des données POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$action = trim($input['action'] ?? 'message');

// Paramètres de configuration du site
$siteSettings = getSiteSettings($pdo);
$companyName = $siteSettings['company_name'] ?? 'FEE BTP SARL';
$phonePrimary = $siteSettings['phone_primary'] ?? '(+237) 691 00 64 31';
$phoneSecondary = $siteSettings['phone_secondary'] ?? '(+237) 671 06 34 41';
$whatsappNumber = preg_replace('/[^0-9]/', '', $siteSettings['whatsapp_number'] ?? '237691006431');
$emailContact = $siteSettings['email_contact'] ?? 'contact@feebtp-sarl.com';
$address = $siteSettings['address'] ?? 'Dschang, Région de l\'Ouest, Cameroun';
$directorName = $siteSettings['manager_name'] ?? 'M. AGHOKENG TANGMO Fabrice';
$geminiApiKey = trim($siteSettings['gemini_api_key'] ?? '');
$aiProvider = trim($siteSettings['ai_provider'] ?? 'auto');
$aiCustomPrompt = trim($siteSettings['ai_system_prompt'] ?? '');

/**
 * 1. ACTION : Message d'accueil initial
 */
if ($action === 'get_welcome') {
    $response = [
        'status' => 'success',
        'reply' => "👋 **Bonjour !** Bienvenue chez " . htmlspecialchars($companyName) . ".\n\nJe suis votre conseiller en bâtiment et travaux publics. Posez-moi directement votre question ci-dessous, ou choisissez une option :",
        'suggestions' => [
            ['label' => '🏗️ Estimer mon projet', 'action' => 'flow_devis'],
            ['label' => '💬 Échanger sur WhatsApp', 'action' => 'contact_whatsapp']
        ]
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 2. ACTION : Sauvegarde du lead dans la base de données
 */
if ($action === 'save_lead') {
    $name = trim($input['name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $service = trim($input['service'] ?? 'Projet BTP');
    $location = trim($input['location'] ?? 'Dschang / Ouest');
    $details = trim($input['details'] ?? '');
    $chatHistory = trim($input['chat_history'] ?? '');

    if (empty($name) || empty($phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Le nom et le numéro sont obligatoires.']);
        exit;
    }

    $subject = "Projet Chatbot : " . $service . " (" . $location . ")";
    $fullMessage = "=== DEMANDE QUALIFIÉE VIA LE CHATBOT INTELLIGENT ===\n";
    $fullMessage .= "Client : " . $name . "\n";
    $fullMessage .= "Téléphone / WhatsApp : " . $phone . "\n";
    $fullMessage .= "Service concerné : " . $service . "\n";
    $fullMessage .= "Localisation du chantier : " . $location . "\n";
    $fullMessage .= "Détails exprimés : " . $details . "\n\n";
    if (!empty($chatHistory)) {
        $fullMessage .= "--- HISTORIQUE DU DIALOGUE ---\n" . $chatHistory . "\n";
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO messages (type, sender_name, sender_email, sender_phone, service_requested, subject, message, status)
            VALUES ('livechat', ?, '', ?, ?, ?, ?, 'unread')
        ");
        $stmt->execute([$name, $phone, $service, $subject, $fullMessage]);
        $leadId = $pdo->lastInsertId();

        $waText = "Bonjour FEE BTP SARL, je suis " . urlencode($name) . ".\n";
        $waText .= "J'ai configuré mon projet sur votre site web :\n";
        $waText .= "• %2AType de projet :%2A " . urlencode($service) . "\n";
        $waText .= "• %2ALocalisation :%2A " . urlencode($location) . "\n";
        if (!empty($details)) {
            $waText .= "• %2ADétails :%2A " . urlencode($details) . "\n";
        }
        $waText .= "• %2AMon contact :%2A " . urlencode($phone) . "\n";
        $waText .= "Pouvez-vous me recontacter pour échanger sur la faisabilité et le devis ?";

        $waLink = "https://wa.me/" . $whatsappNumber . "?text=" . $waText;

        echo json_encode([
            'status' => 'success',
            'lead_id' => $leadId,
            'reply' => "🎉 **Parfait " . htmlspecialchars($name) . " ! Vos informations ont bien été transmises à notre bureau d'études.**\n\nNos ingénieurs vous recontacteront sous 24h ouvrées au **" . htmlspecialchars($phone) . "**.",
            'whatsapp_url' => $waLink,
            'suggestions' => [
                ['label' => '👉 Continuer la discussion sur WhatsApp', 'url' => $waLink],
                ['label' => '🏗️ Poser une autre question', 'action' => 'reset_chat']
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur technique.']);
        exit;
    }
}

/**
 * 3. ANALYSE DU MESSAGE UTILISATEUR
 */
$userMsg = trim($input['message'] ?? '');
$chatHistory = trim($input['chat_history'] ?? '');

if (empty($userMsg)) {
    echo json_encode([
        'status' => 'success',
        'reply' => "Je suis à votre écoute ! Que souhaitez-vous savoir concernant nos travaux ou votre projet de construction ?",
        'suggestions' => [['label' => '🏗️ Lancer mon devis express', 'action' => 'flow_devis']]
    ]);
    exit;
}

/**
 * TENTATIVE D'APPEL GOOGLE GEMINI AI (Si configuré)
 */
function callGeminiAI($apiKey, $userMessage, $history, $companyName, $directorName, $phone, $address, $customPrompt) {
    if (empty($apiKey)) return null;

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key=" . urlencode($apiKey);

    $systemInstruction = "Tu es l'ingénieur-conseil virtuel de {$companyName} ({$address}).
Spécialités & ADN de l'entreprise :
- Créée en 2016 au Cameroun (+10 ans d'expérience, plus de 50 projets réalisés avec succès).
- Capacité opérationnelle : Conception et réalisation de bâtiments de standing (notamment de type R+3 jusqu'à 800 à 1 200 m²), terrassements et génie civil.
- Société Générale de Bâtiment, Travaux Publics, Génie Civil, VRD et Rénovation à Dschang et dans toute la région de l'Ouest (Bafoussam, Mbouda, Foumban, Santchou...).
- Bureau d'études intégré avec architectes et ingénieurs de suivi de travaux.
- Maîtrise des sols latéritiques en forte pente, terrassements par flotte autonome d'engins lourds, fondations renforcées et murs de soutènement en béton armé banché.
- Béton armé dosé à 350 kg/m³ vibré et contrôlé.
- Mission spéciale Diaspora : Accompagner et mettre en pleine confiance nos frères et sœurs de la diaspora camerounaise avec rapports et vidéos réguliers pour un suivi serein à distance.
Téléphone & WhatsApp officiel : {$phone}.

RÈGLES STRICTES DE RÉPONSE :
1. RÉPONSES DIRECTES ET FINALES UNIQUEMENT : Ne JAMAIS afficher de réflexion interne, d'anglais, de brouillon ou d'étapes de rédaction. Réponds DIRECTEMENT en français impeccable au client.
2. DONNER TOUJOURS UNE ESTIMATION CHIFFRÉE QUAND DEMANDÉ : Si le client demande un prix, une estimation ou un budget (ex: villa, 400m², dalle, mur de soutènement), donne TOUJOURS une fourchette réaliste en Francs CFA (FCFA) basée sur le marché de l'Ouest et du Cameroun :
   - Gros œuvre seul : ~90 000 à 130 000 FCFA / m²
   - Clé en main standard : ~160 000 à 210 000 FCFA / m²
   - Haut standing : ~230 000 à 320 000 FCFA / m²
   - Mur de soutènement béton armé : selon linéaire et hauteur de soutènement.
3. PAS DE RÉPONSES TRONQUÉES : Termine toujours tes phrases et tes paragraphes proprement.
4. RÈGLE CRUCIALE SUR LES COORDONNÉES ET CONTACTS :
   - NE MENTIONNE AUCUN NOM DE PERSONNE (pas de 'M. AGHOKENG', 'M. Fabrice', etc.) NI LE TITRE DE DIRECTEUR GÉNÉRAL. Dis simplement qu'un ingénieur va lui répondre.
   - NE DONNE PAS les numéros de téléphone ou de WhatsApp à chaque message.
   - Fournis le numéro ({$phone}) UNIQUEMENT si le visiteur demande expressément le contact (ex: 'quel est votre numéro', 'donne moi votre whatsapp', 'comment vous appeler'). Quand il le demande, dis simplement : 'Un ingénieur va vous répondre directement au {$phone} (appel et WhatsApp).' sans citer de directeur ni de nom.
   - Ne conclus jamais par une invitation forcée à contacter WhatsApp. Conclus naturellement et simplement tes explications en demandant par exemple si le visiteur a une précision sur son projet ou son terrain.";

    $payload = [
        "contents" => [
            [
                "role" => "user",
                "parts" => [
                    ["text" => "Contexte précédent :\n" . $history . "\n\nQuestion du client : " . $userMessage]
                ]
            ]
        ],
        "system_instruction" => [
            "parts" => [
                ["text" => $systemInstruction]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.65,
            "maxOutputTokens" => 1200
        ]
    ];

    $payloadJson = json_encode($payload);
    $models = ['gemini-3.5-flash-lite', 'gemini-flash-lite-latest', 'gemini-3.8-flash'];

    foreach ($models as $modelName) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . urlencode($apiKey);

        // Sur Windows/XAMPP : PowerShell natif direct pour éviter le gel/timeout de PHP cURL
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $tempFile = tempnam(sys_get_temp_dir(), 'gem_in_');
            $tempOut = tempnam(sys_get_temp_dir(), 'gem_out_');
            file_put_contents($tempFile, $payloadJson);
            $psCmd = 'powershell.exe -NoProfile -ExecutionPolicy Bypass -Command "[Console]::OutputEncoding = [System.Text.Encoding]::UTF8; try { $res = Invoke-RestMethod -Uri \'' . $url . '\' -Method Post -InFile \'' . $tempFile . '\' -ContentType \'application/json\' -TimeoutSec 10; [System.IO.File]::WriteAllText(\'' . $tempOut . '\', $res.candidates[0].content.parts[0].text, [System.Text.Encoding]::UTF8) } catch { }"';
            @shell_exec($psCmd);
            @unlink($tempFile);
            if (file_exists($tempOut)) {
                $psOut = @file_get_contents($tempOut);
                @unlink($tempOut);
                if (!empty($psOut) && trim($psOut) !== '') {
                    return trim($psOut);
                }
            }
        } else {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $result) {
                $json = json_decode($result, true);
                if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($json['candidates'][0]['content']['parts'][0]['text']);
                }
            }
        }
    }

    return null;
}

// Essai du LLM Cloud si autorisé
$aiReply = null;
if (($aiProvider === 'auto' || $aiProvider === 'gemini') && !empty($geminiApiKey)) {
    $aiReply = callGeminiAI($geminiApiKey, $userMsg, $chatHistory, $companyName, $directorName, $phonePrimary, $address, $aiCustomPrompt);
}

if ($aiReply) {
    echo json_encode([
        'status' => 'success',
        'reply' => $aiReply,
        'suggestions' => [
            ['label' => '🏗️ Configurer mon devis express', 'action' => 'flow_devis'],
            ['label' => '💬 Échanger sur WhatsApp avec l\'ingénieur', 'action' => 'contact_whatsapp']
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 4. MOTEUR SÉMANTIQUE LOCAL HAUTE DENSITÉ (GRAPHE DE CONNAISSANCES BTP CAMEROUN)
 */
$cleanMsg = mb_strtolower($userMsg, 'UTF-8');

function hasWord($text, array $words) {
    foreach ($words as $w) {
        $w = trim($w);
        if (empty($w)) continue;
        if (mb_strlen($w, 'UTF-8') <= 4) {
            if (preg_match('/(?:\b|[\s\',.!?])' . preg_quote($w, '/') . '(?:\b|[\s\',.!?])/iu', ' ' . $text . ' ')) {
                return true;
            }
        } else {
            if (mb_strpos($text, $w, 0, 'UTF-8') !== false) {
                return true;
            }
        }
    }
    return false;
}

$reply = "";
$suggestions = [];

// 1. Diaspora & Suivi de chantier à distance
if (hasWord($cleanMsg, ['diaspora', 'etranger', 'étranger', 'france', 'europe', 'canada', 'usa', 'etats-unis', 'belgique', 'angleterre', 'allemagne', 'distance', 'confiance', 'escroquerie', 'famille', 'deception'])) {
    $reply = "🌍 **Gestion de Chantier Spéciale Diaspora chez FEE BTP SARL :**\n\nConstruire au pays depuis l'étranger est un investissement précieux qui exige une confiance absolue. Pour vous garantir une sérénité totale, nous appliquons un protocole rigoureux :\n• **Contrat d'entreprise formel** et facturation transparente jalonnée par étapes validées.\n• **Rapports hebdomadaires en direct** : vidéos HD par drone, photos détaillées et appels vidéo WhatsApp sur site.\n• **Paiements échelonnés** : vous ne débloquez les fonds qu'après constatation visuelle de l'achèvement de chaque phase (fondations, élévation, dalle, toiture, finitions).\n• Zéro intermédiaire familial pour éviter tout détournement de matériaux.";
    $suggestions = [
        ['label' => '📄 Demander un contrat type & devis diaspora', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger en direct avec M. Fabrice AGHOKENG', 'action' => 'contact_whatsapp']
    ];
}

// 2. Marécage, terrain inondable, radier, sol mou
elseif (hasWord($cleanMsg, ['marécage', 'marecage', 'inondable', 'boue', 'sol mou', 'sol humide', 'radier', 'pieux', 'nappe phréatique', 'remblai vaseux'])) {
    $reply = "💧 **Construction sur Sol Marécageux ou Inondable :**\n\nBâtir sur un sol instable ou humide ne s'improvise pas au Cameroun :\n• **Étude géotechnique préalable** : mesure de la profondeur du sol portant ferme.\n• **Solutions structurelles** : décapage de la vase, remblai en tout-venant compacté, et réalisation soit d'un **radier général nervuré**, soit de **longrines renforcées sur puits/pieux béton**.\n• **Étanchéité renforcée** : feutre bitumineux sous dallage et hydrofuge de masse dans le béton pour stopper toute remontée capillaire dans les murs.";
    $suggestions = [
        ['label' => '👷 Demander un diagnostic de terrain marécageux', 'action' => 'flow_devis'],
        ['label' => '💬 Poser ma question technique sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 3. Fosse septique écologique, bio-digesteur, assainissement autonome
elseif (hasWord($cleanMsg, ['fosse', 'septique', 'bio', 'écologique', 'ecologique', 'puisard', 'vidange', 'wc', 'toilette', 'egout', 'égout', 'eaux usees', 'eaux usées', 'assainissement'])) {
    $reply = "🌱 **Assainissement Moderne & Fosses Septiques Écologiques :**\n\nFEE BTP SARL conçoit des systèmes d'assainissement autonomes et durables :\n• **Fosse bio-digesteur / écologique** : traitement biologique des effluents par micro-organismes, sans odeurs nauséabondes et sans vidange pendant 15 à 25 ans.\n• **Fosse septique toutes eaux classique en béton armé** : compartiments de décantation étanches couplés à un **puisard filtrant (lit de gravier et sable)** pour les terrains de l'Ouest.\n• Dimensionnement adapté au nombre d'usagers (villa familiale, immeuble de studios ou établissement commercial).";
    $suggestions = [
        ['label' => '📐 Devis pour installation de fosse autonome', 'action' => 'flow_devis'],
        ['label' => '💬 Consulter nos plans d\'assainissement sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 4. Murs de soutènement, talus, pente, relief de Dschang
elseif (hasWord($cleanMsg, ['soutènement', 'soutenement', 'pente', 'talus', 'glissement', 'eboulement', 'éboulement', 'ravin', 'terrassement', 'colline', 'relief', 'laterite', 'latérite', 'moellon'])) {
    $reply = "⛰️ **Murs de Soutènement & Terrains en Pente à Dschang / Menoua :**\n\nLe relief escarpé de l'Ouest Cameroun requiert une protection géotechnique capitale :\n• **Murs poids en moellons de carrière maçonnés** : robustes, drainants et esthétiques pour retenir les poussées de terre rouge.\n• **Murs en béton armé encastrés (voiles)** : pour les très fortes hauteurs ou charges d'immeubles.\n• **Système de drainage intégré** : pose systématique de barbacanes d'évacuation (tuyaux PVC traversants) et caniveau en pied d'ouvrage pour décharger la pression de l'eau.";
    $suggestions = [
        ['label' => '📅 Programmer une visite de terrain en pente', 'action' => 'flow_devis'],
        ['label' => '💬 Envoyer la photo de mon talus sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 5. Dosages de béton, ciment, sable, gravier
elseif (hasWord($cleanMsg, ['dosage', 'ciment', 'sac', 'm3', 'mètre cube', 'metre cube', '350', '400', 'sable', 'gravier', 'dosage beton', 'dosage béton', 'brouette'])) {
    $reply = "🧱 **Dosages de Béton Conformes aux Règles de l'Art :**\n\nChez FEE BTP SARL, nous bannissons les sous-dosages :\n• **Béton armé de structure (Poteaux, Poutres, Dalle)** : dosé rigoureusement à **350 kg/m³** (soit environ 7 sacs de ciment CPJ 35 ou 42.5 pour 400 litres de sable propre et 800 litres de gravier concassé).\n• **Béton pour fondations spéciales & ouvrages d'art** : dosé à **400 kg/m³**.\n• **Béton de propreté** : dosé à **150 à 200 kg/m³** sous les semelles.\n• Utilisation de vibreurs thermiques pour éliminer les bulles d'air et garantir la résistance optimale.";
    $suggestions = [
        ['label' => '🏗️ Lancer un chiffrage quantitatif (DQE)', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger sur les matériaux avec l\'ingénieur', 'action' => 'contact_whatsapp']
    ];
}

// 6. Fers à béton, armatures, diamètres
elseif (hasWord($cleanMsg, ['fer', 'armature', 'ferraillage', 'haute adherence', 'fe400', 'fe500', 'diamètre', 'diametre', 'tor', 'etrier', 'étrier', 'cadre'])) {
    $reply = "⛓️ **Ferraillage & Normes Antisismiques :**\n\nLa solidité d'un bâtiment repose sur son armature métallique :\n• Nous utilisons exclusivement des fers **Haute Adhérence (HA - FeE400 ou FeE500)** certifiés.\n• Dimensionnement précis : armatures longitudinales (fers de 10, 12, 14 ou 16 selon la portée) et étriers resserrés (fers de 6 ou 8) aux nœuds poteaux-poutres.\n• Respect absolu de l'**enrobage du béton** (3 cm minimum) grâce à des cales à béton pour éviter la corrosion future des aciers.";
    $suggestions = [
        ['label' => '📐 Confier l\'étude de structure à FEE BTP', 'action' => 'flow_devis'],
        ['label' => '💬 Poser ma question sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 7. Agglos de 15 vs Agglos de 20
elseif (hasWord($cleanMsg, ['agglo', 'parpaing', '15', '20', 'cloison', 'mur porteur', 'brique', 'creux', 'plein'])) {
    $reply = "🧱 **Quelle différence entre Agglos de 15 et Agglos de 20 ?**\n\n• **Agglos de 20 (cm) :** Recommandés pour les murs extérieurs périphériques, murs de soubassement et façades exposées aux intempéries de l'Ouest. Ils offrent une isolation thermique et acoustique renforcée et une grande inertie.\n• **Agglos de 15 (cm) :** Idéaux pour les cloisons intérieures séparatives (chambres, salons, couloirs) et les étages supérieurs pour alléger la charge sur les dalles.\n• FEE BTP privilégie les agglos vibrés lourds à forte densité de ciment.";
    $suggestions = [
        ['label' => '⚡ Estimer la quantité pour mon projet', 'action' => 'flow_devis'],
        ['label' => '💬 Demander conseil sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 8. Toitures, bac alu, tôles, étanchéité
elseif (hasWord($cleanMsg, ['toiture', 'tôle', 'tole', 'bac alu', 'charpente', 'fuite', 'gouttiere', 'gouttière', 'infiltration', 'etancheite', 'étanchéité', 'chevron', 'panne'])) {
    $reply = "🏠 **Toitures Tropicales & Charpentes Résistantes :**\n\nÀ Dschang et à l'Ouest, les vents et fortes pluies imposent une couverture sans compromis :\n• **Bacs aluminium thermo-laqués** (épaisseurs recommandées : 5/10 ou 6/10) : inaltérables, légers et résistants à la rouille.\n• **Charpente en bois dur local traité** contre les termites et capricornes (bastaings, chevrons calibrés).\n• Pente minimale de 25% à 30% pour un écoulement parfait sans rétention d'eau, et solins d'étanchéité étanches au niveau des cheminées et acrotères.";
    $suggestions = [
        ['label' => '🛠️ Demander une rénovation ou pose de toiture', 'action' => 'flow_devis'],
        ['label' => '💬 Demander les tarifs bac alu sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 9. Permis de bâtir, démarches et autorisations
elseif (hasWord($cleanMsg, ['permis', 'bâtir', 'batir', 'urbanisme', 'titre foncier', 'mairie', 'mindhu', 'certificat', 'autorisation', 'dossier', 'legal', 'légal'])) {
    $reply = "📑 **Obtention du Permis de Bâtir & Urbanisme à Dschang :**\n\nPour construire en toute légalité et éviter les arrêts de chantier municipaux :\n1. Constitution du dossier architectural : plan de masse, plan de situation, plans de niveaux, coupes et façades (échelle 1/50 ou 1/100).\n2. Note de calculs de structure béton et devis estimatif.\n3. Certificat d'urbanisme et titre de propriété (titre foncier ou certificat de propriété).\n• **FEE BTP SARL monte l'intégralité de votre dossier technique** et facilite son dépôt auprès des services de la Mairie et du MINDHU.";
    $suggestions = [
        ['label' => '📑 Confier mon dossier de permis de bâtir', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger sur les démarches foncières', 'action' => 'contact_whatsapp']
    ];
}

// 10. Délais, temps de séchage, durée de chantier
elseif (hasWord($cleanMsg, ['delai', 'délai', 'combien de temps', 'temps de séchage', 'duree', 'durée', 'sechage', 'séchage', 'decoffrage', 'décoffrage', 'calendrier'])) {
    $reply = "⏱️ **Délais d'Exécution & Temps de Séchage Normatifs :**\n\n• **Villa plain-pied (120 à 160 m²)** : 2 à 3 mois pour le gros œuvre complet.\n• **Duplex R+1 (200 à 300 m²)** : 4 à 5 mois pour la structure hors d'eau.\n• **Règle absolue de sécurité BTP** : respect strict des **21 à 28 jours de séchage** pour le décoffrage des dalles et poutres maîtresses en béton armé.\nFEE BTP planifie des rétroplannings rigoureux pour respecter les dates de livraison convenues.";
    $suggestions = [
        ['label' => '📅 Planifier mon calendrier de travaux', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger sur les délais avec l\'ingénieur', 'action' => 'contact_whatsapp']
    ];
}

// 11. Devis, prix, coûts, combien coûte une maison
elseif (hasWord($cleanMsg, ['devis', 'prix', 'cout', 'coût', 'combien', 'tarif', 'm2', 'mètre carré', 'metre carre', 'estimation', 'chiffrage', 'budget', 'dqe'])) {
    $reply = "📊 **Comment est calculé le coût d'une construction ?**\n\nAu Cameroun, estimer au 'hasard' est le plus grand piège. Le coût réel dépend de :\n1. **Gros œuvre seul** (fondations, élévation, dalle, toiture) : entre 130 000 et 180 000 FCFA/m² selon la topographie.\n2. **Clé en main (Finitions complètes)** : de 230 000 à 380 000 FCFA/m² selon le standing (carrelage grand format, baies vitrées, staff, sanitaires).\n\n👉 Nous établissons des **Devis Quantitatifs et Estimatifs (DQE)** ligne par ligne, avec chaque sac de ciment, tonne de fer et journée de travail chiffrée sans surprise.";
    $suggestions = [
        ['label' => '⚡ Démarrer mon devis express gratuit', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger sur mon budget sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 12. Historique, Vision & Chiffres Clés FEE BTP SARL
elseif (hasWord($cleanMsg, ['histoire', 'historique', 'creation', 'création', 'cree', 'créé', 'vision', 'chiffre', 'chiffres', 'annee', 'année', 'afrique centrale'])) {
    $reply = "🏛️ **Histoire & Vision de FEE BTP SARL :**\n\n• **Fondation :** Créée en **2016** au Cameroun pour moderniser le cadre de vie des populations à travers des infrastructures durables et conformes aux normes.\n• **Expérience & Chiffres :** Plus de **10 ans d'expérience** cumulée et plus de **50 projets réalisés** avec succès à Dschang et dans toute la région de l'Ouest.\n• **Capacité :** Bâtiments de standing (types **R+3** de 800 à 1 200 m²), terrassements et murs de soutènement.\n• **Vision :** Devenir une référence majeure dans le BTP au Cameroun et en **Afrique Centrale**.";
    $suggestions = [
        ['label' => '👥 Découvrir notre équipe', 'action' => 'flow_devis'],
        ['label' => '🏗️ Lancer une estimation de projet', 'action' => 'flow_devis']
    ];
}

// 12b. Équipe, Architectes & Cadres Techniques
elseif (hasWord($cleanMsg, ['equipe', 'équipe', 'staff', 'technique', 'ingenieur', 'ingénieur', 'architecte', 'keibog', 'tchoungo', 'etienne', 'zetsa', 'bureau detude', 'bureau d\'étude'])) {
    $reply = "👷 **Bureau d'Études & Cadres Techniques de FEE BTP :**\n\nNos projets sont conçus et supervisés par une équipe pluridisciplinaire d'élite :\n• **Architectes Concepteurs (Tchoungo Junior & Keibog Ricardo) :** Plans 2D/3D, intégration bioclimatique, urbanisme et esthétique.\n• **Ingénierie & Suivi de Travaux (Ing. Guemechio Étienne) :** Notes de calcul structure, descente de charges et contrôle continu du béton armé (350 kg/m³ vibré).\n• **Logistique & Flotte d'Engins (Zetsa Iriss) :** Déploiement autonome de notre parc mécanique (pelles, camions-bennes).";
    $suggestions = [
        ['label' => '📐 Confier l\'étude de mes plans', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger avec l\'ingénieur sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 13. Visites de terrain & RDV à Dschang
elseif (hasWord($cleanMsg, ['visite', 'visiter', 'terrain', 'rdv', 'rendez-vous', 'rencontrer', 'venir', 'parcelle', 'voir'])) {
    $reply = "📍 **Visite Technique de Terrain à Dschang & dans l'Ouest :**\n\nNos ingénieurs se déplacent directement sur votre parcelle pour analyser :\n• La déclivité et les risques d'éboulement.\n• L'accès pour les camions de livraison de matériaux (sable, gravier, parpaings).\n• La présence d'eau ou la consistance du sol.\n• L'orientation pour l'ensoleillement et les vents dominants.\n\nSouhaitez-vous caler un rendez-vous ce samedi ou en semaine ?";
    $suggestions = [
        ['label' => '📅 Réserver ma visite de terrain', 'action' => 'flow_devis'],
        ['label' => '💬 Caler la date sur WhatsApp (' . $phonePrimary . ')', 'action' => 'contact_whatsapp']
    ];
}

// 14. Plans & Architecture 3D
elseif (hasWord($cleanMsg, ['plan', '3d', 'architecte', 'architecture', 'dessin', 'conception', 'croquis', 'facade', 'façade'])) {
    $reply = "📐 **Conception de Plans & Modélisation 3D Tropicale :**\n\nNous concevons vos plans sur-mesure avant de poser le premier parpaing :\n• Plans 2D détaillés : distribution optimale des pièces, aération naturelle, intimité parentale.\n• Visuels 3D photoréalistes extérieurs et intérieurs pour visualiser votre future maison sous tous les angles.\n• Plans d'électricité encastrée, plomberie et fosse septique.";
    $suggestions = [
        ['label' => '📑 Demander la conception de mes plans', 'action' => 'flow_devis'],
        ['label' => '💬 Voir des modèles de plans sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 15. Rénovation, carrelage, peinture, finitions
elseif (hasWord($cleanMsg, ['renovation', 'rénovation', 'carrelage', 'peinture', 'staff', 'finition', 'faience', 'faïence', 'baie', 'plomberie'])) {
    $reply = "🎨 **Second Œuvre & Rénovation de Standing :**\n\nSublimez votre bâtiment existant ou terminez vos travaux inachevés :\n• Pose de grès cérame et faïence grand format avec alignement laser impeccable.\n• Faux-plafonds en staff décoratif avec gorges lumineuses et spots LED encastrés.\n• Peintures professionnelles hydrofuges et anti-moisissures spécialement adaptées à l'humidité de Dschang.\n• Rénovation complète de plomberie et circuits électriques.";
    $suggestions = [
        ['label' => '🛠️ Estimer mon projet de rénovation', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger sur les finitions sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 16. Engins & Matériel
elseif (hasWord($cleanMsg, ['engin', 'materiel', 'matériel', 'pelleteuse', 'camion', 'benne', 'betonniere', 'bétonnière', 'compacteur', 'echafaudage', 'échafaudage'])) {
    $reply = "🚜 **Parc Matériel & Équipements FEE BTP SARL :**\n\nNous possédons nos propres moyens logistiques pour éviter toute dépendance ou retard :\n• Pelles hydrauliques et niveleuses pour terrassement lourd.\n• Camions-bennes pour les approvisionnements directs depuis les carrières de sable et gravier.\n• Bétonnières thermiques à haut rendement et aiguilles vibrantes.\n• Échafaudages métalliques tubulaires certifiés sécurité.";
    $suggestions = [
        ['label' => '🏗️ Lancer mon devis travaux', 'action' => 'flow_devis'],
        ['label' => '💬 Poser ma question sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 17. Localisation, siège social, villes
elseif (hasWord($cleanMsg, ['où', 'ou etes', 'où êtes', 'localisation', 'situe', 'situé', 'adresse', 'bureau', 'siege', 'siège', 'dschang', 'bafoussam', 'ouest'])) {
    $reply = "📍 **Bureaux & Pôles d'Intervention :**\n\n• **Siège Social :** " . htmlspecialchars($address) . ".\n• **Zone Régionale Prioritaire :** Toute la région de l'Ouest (*Dschang, Bafoussam, Mbouda, Foumban, Bangangté, Melong, etc.*).\n• **Projets Nationaux :** Interventions régulières sur les chantiers de **Yaoundé** et **Douala**.";
    $suggestions = [
        ['label' => '📅 Prendre rendez-vous au bureau', 'action' => 'flow_devis'],
        ['label' => '💬 Échanger avec l\'ingénieur sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

// 18. Contact, téléphone, WhatsApp direct
elseif (hasWord($cleanMsg, ['contact', 'telephone', 'téléphone', 'numero', 'numéro', 'appel', 'appeler', 'whatsapp', 'horaire', 'mail', 'email'])) {
    $reply = "📞 **Contact FEE BTP SARL :**\n\nUn ingénieur va vous répondre directement pour échanger sur vos plans ou votre projet :\n• **Téléphone & WhatsApp :** " . htmlspecialchars($phonePrimary) . "\n• **Ligne secondaire :** " . htmlspecialchars($phoneSecondary) . "\n• **E-mail :** " . htmlspecialchars($emailContact);
    $suggestions = [
        ['label' => '💬 Échanger avec l\'ingénieur sur WhatsApp', 'action' => 'contact_whatsapp'],
        ['label' => '📞 Appeler le ' . $phonePrimary, 'action' => 'contact_call']
    ];
}

// 19. Salutations / Politesse
elseif (hasWord($cleanMsg, ['bonjour', 'salut', 'bonsoir', 'coucou', 'hello', 'allo', 'hey', 'bjr', 'bsr', 'merci', 'super', 'merci beaucoup', 'ok', 'daccord', 'd\'accord'])) {
    $reply = "C'est un plaisir de vous renseigner ! Chez **" . htmlspecialchars($companyName) . "**, nous sommes à votre disposition pour concrétiser vos projets de construction avec le plus haut niveau d'exigence.\n\nQue prévoyez-vous comme travaux prochainement ?";
    $suggestions = [
        ['label' => '🏗️ Estimer mon projet (Devis Express)', 'action' => 'flow_devis'],
        ['label' => '🌍 Projet Diaspora', 'action' => 'ask_diaspora'],
        ['label' => '💬 Échanger sur WhatsApp avec l\'ingénieur', 'action' => 'contact_whatsapp']
    ];
}

// 20. Question générale ou cas non répertorié : Analyse Contextuelle Intelligente
else {
    $reply = "C'est une excellente question pour votre projet de construction.\n\nPour vous donner une réponse technique parfaitement adaptée (selon la superficie, le relief de votre terrain à Dschang ou dans l'Ouest, et vos matériaux), vous pouvez configurer rapidement une estimation détaillée en ligne ou nous préciser les détails de vos travaux.";
    $suggestions = [
        ['label' => '🏗️ Lancer le devis express pas-à-pas', 'action' => 'flow_devis'],
        ['label' => '💬 Poser ma question spécifique sur WhatsApp', 'action' => 'contact_whatsapp']
    ];
}

echo json_encode([
    'status' => 'success',
    'reply' => $reply,
    'suggestions' => $suggestions
], JSON_UNESCAPED_UNICODE);
exit;
