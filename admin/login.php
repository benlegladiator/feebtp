<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$pdo = getDBConnection();
$siteSettings = getSiteSettings($pdo);

$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_fullname'] = $user['full_name'];
            $_SESSION['admin_role'] = $user['role'];

            // Mettre à jour last_login
            $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?")->execute([$user['id']]);

            header('Location: index.php');
            exit;
        } else {
            $error = "Identifiants invalides. Veuillez vérifier le nom d'utilisateur et le mot de passe.";
        }
    } else {
        $error = "Veuillez saisir votre nom d'utilisateur et votre mot de passe.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Administration | FREE BTP SARL</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-body">

  <div class="login-card">
    <div class="login-header">
      <div style="width: 50px; height: 50px; background: var(--admin-accent); border-radius: 10px; margin: 0 auto 12px auto; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff;">
        <i class="fas fa-hard-hat"></i>
      </div>
      <h2 style="font-size: 1.3rem; margin-bottom: 4px;">FREE BTP SARL</h2>
      <p style="font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Tableau de Bord Administrateur Sécurisé</p>
    </div>

    <div class="login-body-content">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="login.php" method="POST">
        <div style="margin-bottom: 20px;">
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--admin-text); margin-bottom: 6px;">
            <i class="fas fa-user" style="color: var(--admin-muted); margin-right: 4px;"></i> Identifiant Administrateur
          </label>
          <input type="text" name="username" value="admin" required style="width: 100%; padding: 12px 14px; border: 1px solid var(--admin-border); border-radius: 6px; font-size: 0.95rem;">
        </div>

        <div style="margin-bottom: 25px;">
          <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--admin-text); margin-bottom: 6px;">
            <i class="fas fa-lock" style="color: var(--admin-muted); margin-right: 4px;"></i> Mot de passe
          </label>
          <input type="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 12px 14px; border: 1px solid var(--admin-border); border-radius: 6px; font-size: 0.95rem;">
          <small style="color: var(--admin-muted); font-size: 0.78rem; margin-top: 6px; display: block;">Identifiant par défaut : <strong>admin</strong> | Mot de passe : <strong>admin123</strong></small>
        </div>

        <button type="submit" style="width: 100%; background: var(--admin-accent); color: #fff; border: none; padding: 12px; border-radius: 6px; font-size: 1rem; font-weight: 700; cursor: pointer;">
          <i class="fas fa-sign-in-alt"></i> Se Connecter au Dashboard
        </button>
      </form>

      <div style="margin-top: 25px; text-align: center; border-top: 1px solid var(--admin-border); padding-top: 15px;">
        <a href="../index.php" style="color: var(--admin-muted); font-size: 0.85rem; text-decoration: none;">
          <i class="fas fa-arrow-left"></i> Retour au site public FREE BTP
        </a>
      </div>
    </div>
  </div>

</body>
</html>
