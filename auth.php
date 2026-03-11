<?php
require_once "config/db.php";
require_once "includes/session.php";

/*
    Empêche un utilisateur déjà connecté
    d'accéder à la page de connexion/inscription
*/
if (isset($_SESSION["user_id"])) {
    header("Location: compte.php");
    exit;
}

$mode = $_GET["mode"] ?? "login";
$mode = ($mode === "register") ? "register" : "login";

$message = "";
$messageType = "";

/*
    Valeurs conservées après erreur
*/
$registerNom = "";
$registerPrenom = "";
$registerEmail = "";
$loginEmail = "";

/*
    Fonction utilitaire
*/
function nettoyerTexte(string $valeur): string
{
    return trim(preg_replace('/\s+/', ' ', $valeur));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    /*
        =========================
        INSCRIPTION
        =========================
    */
    if ($action === "register") {
        $mode = "register";

        $registerNom = nettoyerTexte($_POST["nom"] ?? "");
        $registerPrenom = nettoyerTexte($_POST["prenom"] ?? "");
        $registerEmail = strtolower(trim($_POST["email"] ?? ""));
        $mot_de_passe = $_POST["mot_de_passe"] ?? "";
        $confirmation = $_POST["confirmation"] ?? "";

        if (
            empty($registerNom) ||
            empty($registerPrenom) ||
            empty($registerEmail) ||
            empty($mot_de_passe) ||
            empty($confirmation)
        ) {
            $message = "Tous les champs sont obligatoires.";
            $messageType = "error";
        } elseif (!filter_var($registerEmail, FILTER_VALIDATE_EMAIL)) {
            $message = "Adresse e-mail invalide.";
            $messageType = "error";
        } elseif (strlen($registerNom) < 2 || strlen($registerNom) > 50) {
            $message = "Le nom doit contenir entre 2 et 50 caractères.";
            $messageType = "error";
        } elseif (strlen($registerPrenom) < 2 || strlen($registerPrenom) > 50) {
            $message = "Le prénom doit contenir entre 2 et 50 caractères.";
            $messageType = "error";
        } elseif (!preg_match("/^[\p{L}\s\-']+$/u", $registerNom)) {
            $message = "Le nom contient des caractères non autorisés.";
            $messageType = "error";
        } elseif (!preg_match("/^[\p{L}\s\-']+$/u", $registerPrenom)) {
            $message = "Le prénom contient des caractères non autorisés.";
            $messageType = "error";
        } elseif ($mot_de_passe !== $confirmation) {
            $message = "Les mots de passe ne correspondent pas.";
            $messageType = "error";
        } elseif (strlen($mot_de_passe) < 8) {
            $message = "Le mot de passe doit contenir au moins 8 caractères.";
            $messageType = "error";
        } elseif (!preg_match('/[A-Z]/', $mot_de_passe)) {
            $message = "Le mot de passe doit contenir au moins une majuscule.";
            $messageType = "error";
        } elseif (!preg_match('/[a-z]/', $mot_de_passe)) {
            $message = "Le mot de passe doit contenir au moins une minuscule.";
            $messageType = "error";
        } elseif (!preg_match('/[0-9]/', $mot_de_passe)) {
            $message = "Le mot de passe doit contenir au moins un chiffre.";
            $messageType = "error";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
                $stmt->execute([$registerEmail]);

                if ($stmt->fetch()) {
                    $message = "Cet e-mail est déjà utilisé.";
                    $messageType = "error";
                } else {
                    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("
                        INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, statut_membre, role, date_inscription)
                        VALUES (?, ?, ?, ?, 0, 'utilisateur', NOW())
                    ");
                    $stmt->execute([$registerNom, $registerPrenom, $registerEmail, $hash]);

                    $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                    $messageType = "success";
                    $mode = "login";

                    $registerNom = "";
                    $registerPrenom = "";
                    $registerEmail = "";
                }
            } catch (PDOException $e) {
                $message = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                $messageType = "error";
            }
        }
    }

    /*
        =========================
        CONNEXION
        =========================
    */
    if ($action === "login") {
        $mode = "login";

        $loginEmail = strtolower(trim($_POST["email"] ?? ""));
        $mot_de_passe = $_POST["mot_de_passe"] ?? "";

        if (empty($loginEmail) || empty($mot_de_passe)) {
            $message = "Veuillez remplir tous les champs.";
            $messageType = "error";
        } elseif (!filter_var($loginEmail, FILTER_VALIDATE_EMAIL)) {
            $message = "Adresse e-mail invalide.";
            $messageType = "error";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
                $stmt->execute([$loginEmail]);
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
                }
            } catch (PDOException $e) {
                $message = "Une erreur est survenue lors de la connexion. Veuillez réessayer.";
                $messageType = "error";
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

                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="login">

                            <div class="form-group">
                                <label class="form-label" for="login_email">Adresse e-mail</label>
                                <input
                                    type="email"
                                    id="login_email"
                                    name="email"
                                    class="form-input"
                                    placeholder="votre.email@ece.fr"
                                    value="<?= htmlspecialchars($loginEmail) ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="login_password">Mot de passe</label>
                                <input
                                    type="password"
                                    id="login_password"
                                    name="mot_de_passe"
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>

                            <div class="auth-options">
                                <label class="remember-label">
                                    <input type="checkbox" name="remember" disabled>
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

                        <form method="POST" novalidate>
                            <input type="hidden" name="action" value="register">

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="register_nom">Nom</label>
                                    <input
                                        type="text"
                                        id="register_nom"
                                        name="nom"
                                        class="form-input"
                                        placeholder="Votre nom"
                                        value="<?= htmlspecialchars($registerNom) ?>"
                                        maxlength="50"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="register_prenom">Prénom</label>
                                    <input
                                        type="text"
                                        id="register_prenom"
                                        name="prenom"
                                        class="form-input"
                                        placeholder="Votre prénom"
                                        value="<?= htmlspecialchars($registerPrenom) ?>"
                                        maxlength="50"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="register_email">Adresse e-mail</label>
                                <input
                                    type="email"
                                    id="register_email"
                                    name="email"
                                    class="form-input"
                                    placeholder="votre.email@ece.fr"
                                    value="<?= htmlspecialchars($registerEmail) ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="register_password">Mot de passe</label>
                                <input
                                    type="password"
                                    id="register_password"
                                    name="mot_de_passe"
                                    class="form-input"
                                    placeholder="••••••••"
                                    required
                                >
                                <small class="auth-helper-text">
                                    8 caractères minimum, avec une majuscule, une minuscule et un chiffre.
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="register_confirmation">Confirmer le mot de passe</label>
                                <input
                                    type="password"
                                    id="register_confirmation"
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
                <div class="auth-info-badge">Association étudiante</div>

                <div class="auth-info-header">
                    <div class="auth-brand-icon">
                        <img src="assets/img/auth/dice.png" alt="Ludothèque">
                    </div>

                    <div>
                        <h2 class="auth-info-title">Ludothèque</h2>
                        <p class="auth-info-subtitle">
                            Un espace simple pour découvrir, emprunter, louer et suivre vos activités en ligne.
                        </p>
                    </div>
                </div>

                <div class="auth-info-visual">
                    <img src="assets/img/auth/board-games.png" alt="Jeux de société">
                </div>

                <div class="auth-feature-list auth-feature-list-modern">
                    <div class="auth-feature-card">
                        <div class="auth-feature-icon">
                            <img src="assets/img/auth/box.png" alt="Emprunts et locations">
                        </div>
                        <div>
                            <div class="auth-feature-title">Emprunts & Locations</div>
                            <div class="auth-feature-desc">Accédez aux jeux disponibles selon votre statut.</div>
                        </div>
                    </div>

                    <div class="auth-feature-card">
                        <div class="auth-feature-icon">
                            <img src="assets/img/auth/calendar.png" alt="Événements">
                        </div>
                        <div>
                            <div class="auth-feature-title">Événements</div>
                            <div class="auth-feature-desc">Retrouvez le Jeu du jeudi, les soirées jeux et les animations.</div>
                        </div>
                    </div>

                    <div class="auth-feature-card">
                        <div class="auth-feature-icon">
                            <img src="assets/img/auth/user-space.png" alt="Espace personnel">
                        </div>
                        <div>
                            <div class="auth-feature-title">Espace personnel</div>
                            <div class="auth-feature-desc">Suivez vos demandes et consultez votre historique en temps réel.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "includes/footer.php"; ?>