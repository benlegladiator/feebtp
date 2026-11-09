<?php
/**
 * API Backend pour les Formulaires de Contact & Demandes de Devis
 * Traitement avec support d'upload de pièces jointes (plans d'architectes, devis, images)
 */

session_start();
require_once __DIR__ . '/../config/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

$type = sanitize($_POST['type'] ?? 'contact');
$senderName = sanitize($_POST['name'] ?? '');
$senderEmail = sanitize($_POST['email'] ?? '');
$senderPhone = sanitize($_POST['phone'] ?? '');
$serviceRequested = sanitize($_POST['service'] ?? '');
$subject = sanitize($_POST['subject'] ?? ($type === 'devis' ? 'Demande de devis en ligne' : 'Prise de contact générale'));
$message = sanitize($_POST['message'] ?? '');

if (empty($senderName) || empty($message) || (empty($senderEmail) && empty($senderPhone))) {
    $_SESSION['form_error'] = "Veuillez renseigner votre nom, au moins un moyen de contact (téléphone ou email) et votre message.";
    header('Location: ../contact.php?type=' . urlencode($type));
    exit;
}

// Gestion des pièces jointes (plans d'architecte, documents, photos)
$attachmentPath = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/attachments/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES['attachment']['tmp_name'];
    $fileName = $_FILES['attachment']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'dwg', 'zip'];
    if (in_array($fileExt, $allowedExts)) {
        $newFileName = 'doc_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmp, $destPath)) {
            $attachmentPath = 'uploads/attachments/' . $newFileName;
        }
    }
}

$pdo = getDBConnection();
try {
    $stmt = $pdo->prepare("INSERT INTO messages (type, sender_name, sender_email, sender_phone, service_requested, subject, message, attachment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $type,
        $senderName,
        $senderEmail,
        $senderPhone,
        $serviceRequested,
        $subject,
        $message,
        $attachmentPath,
        'unread'
    ]);

    $_SESSION['form_success'] = "Votre demande a été transmise avec succès à l'équipe de FREE BTP SARL ! Nos ingénieurs vous recontacteront sous 24 à 48 heures ouvrées.";
} catch (Exception $e) {
    $_SESSION['form_error'] = "Une erreur est survenue lors de l'enregistrement de votre demande : " . $e->getMessage();
}

header('Location: ../contact.php?success=1');
exit;
