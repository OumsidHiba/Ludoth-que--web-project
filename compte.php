<?php
require_once "config/db.php";
require_once "includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$userId = $_SESSION["user_id"];

/*
    Récupération de l'utilisateur connecté
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
    Mise à jour session
*/
$_SESSION["nom"] = $utilisateur["nom"];
$_SESSION["prenom"] = $utilisateur["prenom"];
$_SESSION["email"] = $utilisateur["email"];
$_SESSION["role"] = $utilisateur["role"];
$_SESSION["statut_membre"] = $utilisateur["statut_membre"];

$role = $utilisateur["role"];
$estMembre = (int)$utilisateur["statut_membre"] === 1;
$estAdmin = ($role === "admin");
$estPresident = ($role === "president");
$estAdminOuPresident = $estAdmin || $estPresident;

/*
    Données utilisateur : demandes / activités
*/
$demandes = [];
$activitesEnCours = [];
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

    /*
        Activités en cours = demandes validées
        On peut adapter plus tard si vous affinez la logique métier
    */
    $activitesEnCours = array_filter($demandes, function ($d) {
        return strtolower($d["statut"]) === "validée";
    });
} catch (PDOException $e) {
    $demandeError = true;
}

/*
    Tableau de bord admin/president
*/
$statsDashboard = [
    "jeux_total" => 0,
    "jeux_en_stock" => 0,
    "jeux_empruntes" => 0,
    "jeux_loues" => 0,
    "demandes_en_attente" => 0,
    "evenements_a_venir" => 0
];
$dashboardError = false;

if ($estAdminOuPresident) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM jeu");
        $statsDashboard["jeux_total"] = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'en stock'");
        $stmt->execute();
        $statsDashboard["jeux_en_stock"] = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'emprunté'");
        $stmt->execute();
        $statsDashboard["jeux_empruntes"] = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'loué'");
        $stmt->execute();
        $statsDashboard["jeux_loues"] = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM demande WHERE LOWER(statut) = 'en attente'");
        $stmt->execute();
        $statsDashboard["demandes_en_attente"] = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM evenement WHERE date_evenement >= CURDATE()");
        $stmt->execute();
        $statsDashboard["evenements_a_venir"] = (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        $dashboardError = true;
    }
}

/*
    Bureau / rôles internes pour le président
*/
$bureau = [];
$bureauError = false;

if ($estPresident) {
    try {
        $stmtBureau = $pdo->query("
            SELECT u.nom, u.prenom, rb.libelle_role, o.date_debut_mandat, o.date_fin_mandat
            FROM occuper o
            INNER JOIN utilisateur u ON o.id_utilisateur = u.id_utilisateur
            INNER JOIN role_bureau rb ON o.id_role = rb.id_role
            ORDER BY rb.libelle_role ASC
        ");
        $bureau = $stmtBureau->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $bureauError = true;
    }
}

include "includes/header.php";
?>

<main class="account-page">
    <div class="container">

        <section class="account-hero">
            <div class="account-hero-left">
                <h1>Mon compte</h1>
                <p>
                    Bienvenue <?= htmlspecialchars($utilisateur["prenom"]) ?>.
                    Consultez vos informations personnelles, vos activités,
                    vos demandes, et vos accès avancés selon votre profil.
                </p>
            </div>

            <div class="account-hero-badges">
                <?php if (!$estMembre): ?>
                    <div class="account-hero-badge">
                        <span class="badge-label">Statut</span>
                        <span class="badge-value badge-non-member">Non membre</span>
                    </div>
                <?php elseif ($estAdmin): ?>
                    <div class="account-hero-badge">
                        <span class="badge-label">Rôle dans l'association</span>
                        <span class="badge-value badge-role">Administrateur</span>
                    </div>
                <?php elseif ($estPresident): ?>
                    <div class="account-hero-badge">
                        <span class="badge-label">Rôle dans l'association</span>
                        <span class="badge-value badge-role">Président</span>
                    </div>
                <?php else: ?>
                    <div class="account-hero-badge">
                        <span class="badge-label">Statut</span>
                        <span class="badge-value badge-member">Membre</span>
                    </div>
                <?php endif; ?>
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
                        <span class="info-label">Inscription</span>
                        <span class="info-value">
                            <?= !empty($utilisateur["date_inscription"]) ? date("d/m/Y", strtotime($utilisateur["date_inscription"])) : "Non renseignée" ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="account-card">
                <h2>Mes droits</h2>

                <?php if (!$estMembre): ?>
                    <p class="account-text">
                        Vous êtes actuellement <strong>non-membre</strong>.
                    </p>
                    <p class="account-subtext">
                        Vous pouvez effectuer des demandes de <strong>location</strong> de jeux.
                    </p>
                    <p class="account-subtext">
                        L’<strong>emprunt gratuit</strong> n’est pas accessible tant que votre statut membre n’a pas été validé.
                    </p>
                <?php else: ?>
                    <p class="account-text">
                        Vous êtes actuellement <strong>membre</strong> de l’association.
                    </p>
                    <p class="account-subtext">
                        Vous pouvez effectuer des demandes d’<strong>emprunt</strong>.
                    </p>
                    <p class="account-subtext">
                        Vous pouvez également suivre vos <strong>réservations</strong> et vos demandes validées.
                    </p>
                <?php endif; ?>

                <?php if ($estAdmin): ?>
                    <div class="account-note">
                        Vous disposez d’un <strong>accès administrateur</strong> pour gérer les jeux,
                        les événements et le traitement des demandes.
                    </div>
                <?php endif; ?>

                <?php if ($estPresident): ?>
                    <div class="account-note">
                        Vous disposez d’un <strong>accès président</strong> avec la gestion du bureau,
                        des administrateurs et des rôles internes.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="account-section-block">
            <div class="account-history-card">
                <h2>Activités en cours</h2>

                <?php if ($demandeError): ?>
                    <p class="account-text">
                        Les activités en cours ne sont pas encore disponibles.
                    </p>
                <?php elseif (count($activitesEnCours) === 0): ?>
                    <p class="account-text">
                        Aucune activité en cours pour le moment.
                    </p>
                    <p class="account-subtext">
                        Cette section affichera vos emprunts, locations et réservations validés.
                    </p>
                <?php else: ?>
                    <div class="request-list">
                        <?php foreach ($activitesEnCours as $activite): ?>
                            <div class="request-item">
                                <div class="request-main">
                                    <div class="request-title-row">
                                        <h3><?= htmlspecialchars($activite["nom_jeu"]) ?></h3>
                                        <span class="request-status request-status-approved">
                                            <?= htmlspecialchars($activite["statut"]) ?>
                                        </span>
                                    </div>

                                    <p class="request-meta">
                                        Type : <strong><?= htmlspecialchars($activite["type_demande"]) ?></strong>
                                    </p>

                                    <p class="request-meta">
                                        Du <strong><?= !empty($activite["date_debut"]) ? date("d/m/Y", strtotime($activite["date_debut"])) : "—" ?></strong>
                                        au <strong><?= !empty($activite["date_fin"]) ? date("d/m/Y", strtotime($activite["date_fin"])) : "—" ?></strong>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="account-section-block">
            <div class="account-history-card">
                <h2>Mes demandes</h2>

                <?php if ($demandeError): ?>
                    <p class="account-text">
                        L’historique des demandes sera affiché ici une fois la gestion complète finalisée.
                    </p>
                <?php elseif (count($demandes) === 0): ?>
                    <p class="account-text">
                        Vous n’avez encore effectué aucune demande.
                    </p>
                    <p class="account-subtext">
                        Explorez la ludothèque pour faire votre première demande.
                    </p>
                <?php else: ?>
                    <div class="summary-stats summary-stats-account">
                        <div class="summary-box">
                            <span class="summary-number"><?= count($demandes) ?></span>
                            <span class="summary-label">Total</span>
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

                    <div class="request-list request-list-top-space">
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

        <?php if ($estAdminOuPresident): ?>
            <section class="account-section-block">
                <div class="account-history-card">
                    <h2>Tableau de bord</h2>

                    <?php if ($dashboardError): ?>
                        <p class="account-text">
                            Les statistiques globales ne sont pas encore disponibles.
                        </p>
                    <?php else: ?>
                        <div class="summary-stats summary-stats-dashboard">
                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["jeux_total"] ?></span>
                                <span class="summary-label">Jeux total</span>
                            </div>

                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["jeux_en_stock"] ?></span>
                                <span class="summary-label">En stock</span>
                            </div>

                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["jeux_empruntes"] ?></span>
                                <span class="summary-label">Empruntés</span>
                            </div>

                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["jeux_loues"] ?></span>
                                <span class="summary-label">Loués</span>
                            </div>

                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["demandes_en_attente"] ?></span>
                                <span class="summary-label">Demandes en attente</span>
                            </div>

                            <div class="summary-box">
                                <span class="summary-number"><?= $statsDashboard["evenements_a_venir"] ?></span>
                                <span class="summary-label">Événements à venir</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="account-grid">
                <div class="account-card">
                    <h2>Gestion des jeux</h2>
                    <p class="account-text">
                        Ajouter, modifier, supprimer des jeux et mettre à jour leur statut.
                    </p>
                    <div class="account-role-actions">
                        <a href="admin/jeux.php" class="btn-main account-action-btn">Gérer les jeux</a>
                    </div>
                </div>

                <div class="account-card">
                    <h2>Gestion des événements</h2>
                    <p class="account-text">
                        Créer, modifier et supprimer les événements de l’association.
                    </p>
                    <div class="account-role-actions">
                        <a href="admin/evenements.php" class="btn-main account-action-btn">Gérer les événements</a>
                    </div>
                </div>

                <div class="account-card">
                    <h2>Traitement des demandes</h2>
                    <p class="account-text">
                        Accepter ou refuser les demandes et mettre à jour automatiquement les statuts.
                    </p>
                    <div class="account-role-actions">
                        <a href="admin/demandes.php" class="btn-main account-action-btn">Traiter les demandes</a>
                    </div>
                </div>

                <div class="account-card">
                    <h2>Accès rapide</h2>

                    <div class="quick-links">
                        <a href="ludotheque.php" class="quick-link-card">
                            <span class="quick-link-icon">🎲</span>
                            <span class="quick-link-text">Accéder à la ludothèque</span>
                        </a>

                        <a href="contact.php" class="quick-link-card">
                            <span class="quick-link-icon">✉️</span>
                            <span class="quick-link-text">Contacter l’association</span>
                        </a>

                        <a href="apropos.php" class="quick-link-card">
                            <span class="quick-link-icon">🏛️</span>
                            <span class="quick-link-text">À propos</span>
                        </a>

                        <a href="admin/dashboard.php" class="quick-link-card">
                            <span class="quick-link-icon">🛠️</span>
                            <span class="quick-link-text">Dashboard admin</span>
                        </a>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <section class="account-section-block">
                <div class="account-card">
                    <h2>Accès rapide</h2>

                    <div class="quick-links">
                        <a href="ludotheque.php" class="quick-link-card">
                            <span class="quick-link-icon">🎲</span>
                            <span class="quick-link-text">Accéder à la ludothèque</span>
                        </a>

                        <a href="contact.php" class="quick-link-card">
                            <span class="quick-link-icon">✉️</span>
                            <span class="quick-link-text">Contacter l’association</span>
                        </a>

                        <a href="apropos.php" class="quick-link-card">
                            <span class="quick-link-icon">🏛️</span>
                            <span class="quick-link-text">À propos</span>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($estPresident): ?>
            <section class="account-section-block">
                <div class="account-history-card">
                    <h2>Gestion du bureau</h2>

                    <?php if ($bureauError): ?>
                        <p class="account-text">
                            Les informations internes du bureau ne sont pas encore disponibles.
                        </p>
                    <?php elseif (count($bureau) === 0): ?>
                        <p class="account-text">
                            Aucun rôle de bureau n’est encore enregistré.
                        </p>
                    <?php else: ?>
                        <div class="bureau-list">
                            <?php foreach ($bureau as $membreBureau): ?>
                                <div class="bureau-item">
                                    <div class="bureau-main">
                                        <h3><?= htmlspecialchars($membreBureau["prenom"] . " " . $membreBureau["nom"]) ?></h3>
                                        <p class="bureau-role">
                                            <?= htmlspecialchars($membreBureau["libelle_role"]) ?>
                                        </p>
                                        <p class="request-meta">
                                            Début :
                                            <strong><?= !empty($membreBureau["date_debut_mandat"]) ? date("d/m/Y", strtotime($membreBureau["date_debut_mandat"])) : "—" ?></strong>
                                            |
                                            Fin :
                                            <strong><?= !empty($membreBureau["date_fin_mandat"]) ? date("d/m/Y", strtotime($membreBureau["date_fin_mandat"])) : "—" ?></strong>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="account-role-actions president-actions-top">
                        <a href="admin/bureau.php" class="btn-main account-action-btn">Gérer le bureau</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="account-actions">
            <a href="ludotheque.php" class="btn-main account-action-btn">Accéder à la ludothèque</a>

            <?php if ($estAdminOuPresident): ?>
                <a href="admin/dashboard.php" class="btn-secondary">Accéder à l’administration</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-danger">Se déconnecter</a>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>