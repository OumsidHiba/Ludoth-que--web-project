<?php
require_once "includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

include "includes/header.php";
?>

<main class="account-page">
    <div class="container">

        <section class="account-hero">
            <div class="account-hero-left">
                <h1>Mon compte</h1>
                <p>
                    Bienvenue <?= htmlspecialchars($_SESSION["prenom"]) ?>.
                    Retrouvez ici vos informations personnelles, votre statut
                    et bientôt vos demandes d’emprunt, de location et de réservation.
                </p>
            </div>

            <div class="account-hero-badge">
                <span class="badge-label">Statut</span>
                <span class="badge-value <?= $_SESSION["statut_membre"] ? 'badge-member' : 'badge-non-member' ?>">
                    <?= $_SESSION["statut_membre"] ? "Membre" : "Non membre" ?>
                </span>
            </div>
        </section>

        <section class="account-grid">
            <div class="account-card">
                <h2>Informations personnelles</h2>

                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Nom</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION["nom"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Prénom</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION["prenom"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION["email"]) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Rôle</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION["role"]) ?></span>
                    </div>
                </div>
            </div>

            <div class="account-card">
                <h2>Vos droits actuels</h2>

                <?php if ($_SESSION["statut_membre"]): ?>
                    <p class="account-text">
                        En tant que <strong>membre</strong>, vous pouvez faire des demandes
                        d’emprunt de jeux et réserver pour le Jeu du jeudi.
                    </p>
                <?php else: ?>
                    <p class="account-text">
                        En tant que <strong>non-membre</strong>, vous pouvez faire des demandes
                        de location de jeux et certaines réservations prévues par la plateforme.
                    </p>
                <?php endif; ?>

                <?php if ($_SESSION["role"] === "admin"): ?>
                    <p class="account-note">
                        Vous disposez également d’un accès administrateur pour gérer les jeux,
                        les événements et les demandes.
                    </p>
                <?php elseif ($_SESSION["role"] === "president"): ?>
                    <p class="account-note">
                        Vous disposez de tous les droits administratifs, y compris la gestion
                        du bureau et des administrateurs.
                    </p>
                <?php endif; ?>
            </div>

            <div class="account-card">
                <h2>Demandes en cours</h2>
                <p class="account-text">
                    Aucune demande affichée pour le moment.
                </p>
                <p class="account-subtext">
                    Cette section affichera bientôt vos emprunts, locations ou réservations en cours.
                </p>
            </div>

            <div class="account-card">
                <h2>Historique</h2>
                <p class="account-text">
                    Aucun historique disponible pour le moment.
                </p>
                <p class="account-subtext">
                    Vous verrez ici le suivi de vos demandes : en attente, validées ou refusées.
                </p>
            </div>
        </section>

        <section class="account-actions">
            <a href="ludotheque.php" class="btn-main">Accéder à la ludothèque</a>

            <?php if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "president"): ?>
                <a href="admin/dashboard.php" class="btn-secondary">Accéder à l’administration</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-danger">Se déconnecter</a>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>