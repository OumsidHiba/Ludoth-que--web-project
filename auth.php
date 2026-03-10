<?php
require_once "config/db.php";
require_once "includes/session.php";

$mode = $_GET["mode"] ?? "login";
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "register") {
        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $mot_de_passe = $_POST["mot_de_passe"] ?? "";
        $confirmation = $_POST["confirmation"] ?? "";

        if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($confirmation)) {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "error";
            $mode = "register";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Adresse e-mail invalide.";
            $messageType = "error";
            $mode = "register";
        } elseif ($mot_de_passe !== $confirmation) {
            $message = "Les mots de passe ne correspondent pas.";
            $messageType = "error";
            $mode = "register";
        } elseif (strlen($mot_de_passe) < 6) {
            $message = "Le mot de passe doit contenir au moins 6 caractères.";
            $messageType = "error";
            $mode = "register";
        } else {
            $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $message = "Cet e-mail est déjà utilisé.";
                $messageType = "error";
                $mode = "register";
            } else {
                $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, statut_membre, role)
                    VALUES (?, ?, ?, ?, 0, 'utilisateur')
                ");
                $stmt->execute([$nom, $prenom, $email, $hash]);

                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                $messageType = "success";
                $mode = "login";
            }
        }
    }

    if ($action === "login") {
        $email = trim($_POST["email"] ?? "");
        $mot_de_passe = $_POST["mot_de_passe"] ?? "";

        if (empty($email) || empty($mot_de_passe)) {
            $message = "Veuillez remplir tous les champs.";
            $messageType = "error";
            $mode = "login";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mot_de_passe, $user["mot_de_passe"])) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id_utilisateur"];
                $_SESSION["nom"] = $user["nom"];
                $_SESSION["prenom"] = $user["prenom"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["statut_membre"] = $user["statut_membre"];

                header("Location: compte.php");
                exit;
            } else {
                $message = "E-mail ou mot de passe incorrect.";
                $messageType = "error";
                $mode = "login";
            }
        }
    }
}

include "includes/header.php";
?>

<main class="auth-container-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-form-side">
                <div class="auth-tabs">
                    <a href="auth.php?mode=login" class="auth-tab <?= $mode === 'login' ? 'active' : '' ?>" id="tab-login">Connexion</a>
                    <a href="auth.php?mode=register" class="auth-tab <?= $mode === 'register' ? 'active' : '' ?>" id="tab-register">Inscription</a>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="message <?= htmlspecialchars($messageType) ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if ($mode === "login"): ?>
                    <div id="form-login">
                        <h2 class="auth-title">Bon retour parmi nous !</h2>
                        <p class="auth-subtitle">
                            Connectez-vous pour accéder à votre espace personnel.
                        </p>

                        <form method="POST">
                            <input type="hidden" name="action" value="login">

                            <div class="form-group">
                                <label class="form-label">Adresse e-mail</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-input"
                                    placeholder="votre.email@ece.fr"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <input
                                    type="password"
                                    name="mot_de_passe"
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>

                            <div class="auth-options">
                                <label class="remember-label">
                                    <input type="checkbox" name="remember">
                                    Se souvenir de moi
                                </label>
                                <a href="#" class="auth-link">Mot de passe oublié ?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                        </form>

                        <p class="auth-switch-text">
                            Pas encore de compte ?
                            <a href="auth.php?mode=register" class="auth-link">Créer un compte</a>
                        </p>
                    </div>
                <?php else: ?>
                    <div id="form-register">
                        <h2 class="auth-title">Rejoignez la Ludothèque !</h2>
                        <p class="auth-subtitle">
                            Créez votre compte pour profiter de tous les services.
                        </p>

                        <form method="POST">
                            <input type="hidden" name="action" value="register">

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nom</label>
                                    <input
                                        type="text"
                                        name="nom"
                                        class="form-input"
                                        placeholder="Votre nom"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Prénom</label>
                                    <input
                                        type="text"
                                        name="prenom"
                                        class="form-input"
                                        placeholder="Votre prénom"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Adresse e-mail</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-input"
                                    placeholder="votre.email@ece.fr"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <input
                                    type="password"
                                    name="mot_de_passe"
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input
                                    type="password"
                                    name="confirmation"
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label">Statut du compte</label>
                                <div class="status-info-box">
                                    <div class="status-info-title">Compte créé en non-membre</div>
                                    <div class="status-info-text">
                                        Le statut membre est attribué ultérieurement par l’association.
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Créer mon compte</button>
                        </form>

                        <p class="auth-switch-text">
                            Déjà inscrit ?
                            <a href="auth.php?mode=login" class="auth-link">Se connecter</a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="auth-info-side">
                <h2 class="auth-info-title">🎲 Ludothèque</h2>
                <p class="auth-info-text">
                    Accédez à notre collection de jeux, empruntez ou louez vos jeux préférés,
                    et participez à nos événements.
                </p>

                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">📦</div>
                        <div>
                            <div class="auth-feature-title">Emprunts & Locations</div>
                            <div class="auth-feature-desc">Réservez vos jeux en quelques clics</div>
                        </div>
                    </div>

                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">📅</div>
                        <div>
                            <div class="auth-feature-title">Événements</div>
                            <div class="auth-feature-desc">Jeu du jeudi, soirées jeux et plus</div>
                        </div>
                    </div>

                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">👤</div>
                        <div>
                            <div class="auth-feature-title">Espace personnel</div>
                            <div class="auth-feature-desc">Suivez vos demandes en temps réel</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "includes/footer.php"; ?>