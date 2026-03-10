<?php
require_once "config/db.php";
require_once "includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$userId = $_SESSION["user_id"];

/*
    Récupération des informations à jour depuis la base
    plutôt que de dépendre uniquement de la session
*/
$stmtUser = $pdo->prepare("
    SELECT id_utilisateur, nom, prenom, email, role, statut_membre, date_inscription
    FROM utilisateur
    WHERE id_utilisateur = ?
");
$stmtUser->execute([$userId]);
$utilisateur = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    session_unset();
    session_destroy();
    header("Location: auth.php?mode=login");
    exit;
}

/*
    Mise à jour de la session avec les valeurs actuelles
*/
$_SESSION["nom"] = $utilisateur["nom"];
$_SESSION["prenom"] = $utilisateur["prenom"];
$_SESSION["email"] = $utilisateur["email"];
$_SESSION["role"] = $utilisateur["role"];
$_SESSION["statut_membre"] = $utilisateur["statut_membre"];

/*
    Récupération des demandes de l'utilisateur
    (si la table demande existe déjà)
*/
$demandes = [];
$demandeError = false;

try {
    $stmtDemandes = $pdo->prepare("
        SELECT d.*, j.nom AS nom_jeu
        FROM demande d
        INNER JOIN jeu j ON d.id_jeu = j.id_jeu
        WHERE d.id_utilisateur = ?
        ORDER BY d.date_demande DESC
    ");
    $stmtDemandes->execute([$userId]);
    $demandes = $stmtDemandes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $demandeError = true;
}

$role = $utilisateur["role"];
$estMembre = (int)$utilisateur["statut_membre"] === 1;

include "includes/header.php";
?>

<main class="account-page">
    <div class="container">

        <section class="account-hero">
            <div class="account-hero-left">
                <h1>Mon compte</h1>
                <p>
                    Bienvenue <?= htmlspecialchars($utilisateur["prenom"]) ?>.
                    Retrouvez ici vos informations personnelles, votre statut,
                    ainsi que le suivi de vos demandes sur la plateforme.
                </p>
            </div>

            <div class="account-hero-badge">
                <span class="badge-label">Statut</span>
                <span class="badge-value <?= $estMembre ? 'badge-member' : 'badge-non-member' ?>">
                    <?= $estMembre ? "Membre" : "Non membre" ?>
                </span>
            </div>
        </section>

        <section class="account-grid">
            <div class="account-card">
                <h2>Informations personnelles</h2>

                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Nom</span>
                        <span class="info-value"><?= htmlspecialchars($utilisateur["nom"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Prénom</span>
                        <span class="info-value"><?= htmlspecialchars($utilisateur["prenom"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($utilisateur["email"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Rôle</span>
                        <span class="info-value"><?= htmlspecialchars($role) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Inscription</span>
                        <span class="info-value">
                            <?= !empty($utilisateur["date_inscription"]) ? date("d/m/Y", strtotime($utilisateur["date_inscription"])) : "Non renseignée" ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="account-card">
                <h2>Mes droits sur la plateforme</h2>

                <?php if (!$estMembre): ?>
                    <p class="account-text">
                        Vous êtes actuellement <strong>non-membre</strong> de l’association.
                        Vous pouvez effectuer des demandes de <strong>location</strong> de jeux.
                    </p>
                    <p class="account-subtext">
                        L’emprunt gratuit est réservé aux membres de l’association.
                    </p>
                <?php else: ?>
                    <p class="account-text">
                        Vous êtes actuellement <strong>membre</strong> de l’association.
                        Vous pouvez effectuer des demandes <strong>d’emprunt</strong> et profiter
                        des avantages réservés aux adhérents.
                    </p>
                    <p class="account-subtext">
                        Vous pouvez également faire des réservations selon les règles prévues.
                    </p>
                <?php endif; ?>

                <?php if ($role === "admin"): ?>
                    <div class="account-note">
                        Vous avez également un <strong>accès administrateur</strong> pour gérer
                        les jeux, événements et demandes des utilisateurs.
                    </div>
                <?php elseif ($role === "president"): ?>
                    <div class="account-note">
                        Vous avez un <strong>accès président</strong> avec tous les droits
                        d’administration, y compris la gestion du bureau et des administrateurs.
                    </div>
                <?php endif; ?>
            </div>

            <div class="account-card">
                <h2>Résumé de mes demandes</h2>

                <?php if ($demandeError): ?>
                    <p class="account-text">
                        Les demandes ne sont pas encore disponibles sur cette installation.
                    </p>
                    <p class="account-subtext">
                        La table ou la fonctionnalité associée n’est pas encore totalement branchée.
                    </p>
                <?php else: ?>
                    <div class="summary-stats">
                        <div class="summary-box">
                            <span class="summary-number"><?= count($demandes) ?></span>
                            <span class="summary-label">Demandes totales</span>
                        </div>

                        <div class="summary-box">
                            <span class="summary-number">
                                <?= count(array_filter($demandes, fn($d) => strtolower($d["statut"]) === "en attente")) ?>
                            </span>
                            <span class="summary-label">En attente</span>
                        </div>

                        <div class="summary-box">
                            <span class="summary-number">
                                <?= count(array_filter($demandes, fn($d) => strtolower($d["statut"]) === "validée")) ?>
                            </span>
                            <span class="summary-label">Validées</span>
                        </div>

                        <div class="summary-box">
                            <span class="summary-number">
                                <?= count(array_filter($demandes, fn($d) => strtolower($d["statut"]) === "refusée")) ?>
                            </span>
                            <span class="summary-label">Refusées</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="account-card">
                <h2>Accès rapide</h2>

                <div class="quick-links">
                    <a href="ludotheque.php" class="quick-link-card">
                        <span class="quick-link-icon">🎲</span>
                        <span class="quick-link-text">Voir la ludothèque</span>
                    </a>

                    <a href="contact.php" class="quick-link-card">
                        <span class="quick-link-icon">✉️</span>
                        <span class="quick-link-text">Contacter l’association</span>
                    </a>

                    <a href="a-propos.php" class="quick-link-card">
                        <span class="quick-link-icon">🏛️</span>
                        <span class="quick-link-text">À propos</span>
                    </a>

                    <?php if ($role === "admin" || $role === "president"): ?>
                        <a href="admin/dashboard.php" class="quick-link-card">
                            <span class="quick-link-icon">🛠️</span>
                            <span class="quick-link-text">Administration</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="account-history">
            <div class="account-history-card">
                <h2>Historique de mes demandes</h2>

                <?php if ($demandeError): ?>
                    <p class="account-text">
                        L’historique sera affiché ici une fois la gestion complète des demandes finalisée.
                    </p>
                <?php elseif (count($demandes) === 0): ?>
                    <p class="account-text">
                        Vous n’avez encore effectué aucune demande.
                    </p>
                    <p class="account-subtext">
                        Explorez la ludothèque pour faire votre première demande.
                    </p>
                <?php else: ?>
                    <div class="request-list">
                        <?php foreach ($demandes as $demande): ?>
                            <?php
                            $statutDemande = strtolower($demande["statut"]);
                            $statutClass = "request-status-default";

                            if ($statutDemande === "en attente") {
                                $statutClass = "request-status-pending";
                            } elseif ($statutDemande === "validée") {
                                $statutClass = "request-status-approved";
                            } elseif ($statutDemande === "refusée") {
                                $statutClass = "request-status-refused";
                            }
                            ?>
                            <div class="request-item">
                                <div class="request-main">
                                    <div class="request-title-row">
                                        <h3><?= htmlspecialchars($demande["nom_jeu"]) ?></h3>
                                        <span class="request-status <?= $statutClass ?>">
                                            <?= htmlspecialchars($demande["statut"]) ?>
                                        </span>
                                    </div>

                                    <p class="request-meta">
                                        Type : <strong><?= htmlspecialchars($demande["type_demande"]) ?></strong>
                                    </p>

                                    <p class="request-meta">
                                        Demandé le :
                                        <strong><?= !empty($demande["date_demande"]) ? date("d/m/Y H:i", strtotime($demande["date_demande"])) : "—" ?></strong>
                                    </p>

                                    <p class="request-meta">
                                        Du <strong><?= !empty($demande["date_debut"]) ? date("d/m/Y", strtotime($demande["date_debut"])) : "—" ?></strong>
                                        au <strong><?= !empty($demande["date_fin"]) ? date("d/m/Y", strtotime($demande["date_fin"])) : "—" ?></strong>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="account-actions">
            <a href="ludotheque.php" class="btn-main account-action-btn">Accéder à la ludothèque</a>

            <?php if ($role === "admin" || $role === "president"): ?>
                <a href="admin/dashboard.php" class="btn-secondary">Accéder à l’administration</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-danger">Se déconnecter</a>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>