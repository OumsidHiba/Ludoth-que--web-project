<?php
require_once "includes/session.php";
include "includes/header.php";
?>

<main class="about-page">
    <div class="container">

        <section class="about-hero">
            <div class="about-hero-content">
                <h1>À propos de la Ludothèque</h1>
                <p>
                    La Ludothèque est une association étudiante dédiée à la découverte,
                    au partage et à la valorisation du jeu de société sur le campus.
                    Elle permet aux étudiants de se retrouver autour d’activités conviviales,
                    d’événements ludiques et d’un catalogue de jeux accessible toute l’année.
                </p>

                <div class="about-hero-actions">
                    <?php if (!isset($_SESSION["user_id"])): ?>
                        <a href="auth.php?mode=register" class="btn-main about-btn-main">Créer un compte</a>
                        <a href="contact.php" class="btn-secondary">Nous contacter</a>
                    <?php else: ?>
                        <a href="compte.php" class="btn-main about-btn-main">Accéder à mon compte</a>
                        <a href="contact.php" class="btn-secondary">Contacter l’association</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-section-header">
                <h2>Notre mission</h2>
                <p>
                    Favoriser le lien social, la détente et la vie étudiante à travers
                    le jeu de société, dans un cadre accessible, chaleureux et organisé.
                </p>
            </div>

            <div class="about-mission-grid">

            <div class="about-card">
                <div class="about-icon">
                    <img src="assets/img/icons/partager.png" alt="Partager">
                </div>
                <h3>Partager</h3>
                <p>
                    Faire découvrir des jeux variés à tous les profils,
                    des débutants aux passionnés.
                </p>
            </div>

            <div class="about-card">
                <div class="about-icon">
                    <img src="assets/img/icons/rassembler.png" alt="Rassembler">
                </div>
                <h3>Rassembler</h3>
                <p>
                    Créer des moments d’échange et de convivialité
                    entre les étudiants du campus.
                </p>
            </div>

            <div class="about-card">
                <div class="about-icon">
                    <img src="assets/img/icons/animer.png" alt="Animer">
                </div>
                <h3>Animer</h3>
                <p>
                    Proposer des événements réguliers qui dynamisent
                    la vie associative et étudiante.
                </p>
            </div>

</div>  
        </section>

        <section class="about-section">
            <div class="about-section-header">
                <h2>Ce que propose l’association</h2>
                <p>
                    Un fonctionnement simple, pensé pour permettre à chacun
                    de profiter facilement de la ludothèque.
                </p>
            </div>

            <div class="about-offers-grid">
                <div class="about-offer-card">
                    <h3>Catalogue de jeux</h3>
                    <p>
                        Une sélection de jeux de société consultable en ligne avec leurs
                        caractéristiques principales : nombre de joueurs, durée, difficulté,
                        description et statut de disponibilité.
                    </p>
                </div>

                <div class="about-offer-card">
                    <h3>Emprunts et locations</h3>
                    <p>
                        Les membres de l’association peuvent emprunter des jeux gratuitement
                        selon les règles en vigueur. Les non-membres peuvent effectuer des
                        demandes de location.
                    </p>
                </div>

                <div class="about-offer-card">
                    <h3>Événements ludiques</h3>
                    <p>
                        L’association organise différents temps forts comme la Salle du jeudi,
                        le Jeu du jeudi, les soirées jeux et des événements occasionnels.
                    </p>
                </div>

                <div class="about-offer-card">
                    <h3>Espace personnel</h3>
                    <p>
                        Chaque utilisateur connecté dispose d’un espace personnel pour suivre
                        ses informations, son statut et l’historique de ses demandes.
                    </p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-section-header">
                <h2>Nos valeurs</h2>
                <p>
                    L’association repose sur des principes simples qui guident
                    son fonctionnement au quotidien.
                </p>
            </div>

            <div class="about-values-list">
                <div class="about-value-item">
                    <span class="about-value-title">Accessibilité</span>
                    <p>
                        Le jeu doit être un espace ouvert à tous, quel que soit le niveau
                        de connaissance ou l’expérience.
                    </p>
                </div>

                <div class="about-value-item">
                    <span class="about-value-title">Respect</span>
                    <p>
                        Le matériel, les autres participants et l’organisation de l’association
                        doivent être respectés par tous les utilisateurs.
                    </p>
                </div>

                <div class="about-value-item">
                    <span class="about-value-title">Convivialité</span>
                    <p>
                        La Ludothèque veut offrir un environnement accueillant, bienveillant
                        et agréable pour tous.
                    </p>
                </div>

                <div class="about-value-item">
                    <span class="about-value-title">Responsabilité</span>
                    <p>
                        Chaque utilisateur s’engage à prendre soin des jeux empruntés ou loués
                        et à respecter les délais prévus.
                    </p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-section-header">
                <h2>Règles générales d’utilisation</h2>
                <p>
                    Voici les grands principes à respecter pour garantir le bon fonctionnement
                    de la ludothèque.
                </p>
            </div>

            <div class="about-rules">
                <div class="about-rule">
                    <span class="about-rule-number">1</span>
                    <div>
                        <h3>Respect du matériel</h3>
                        <p>
                            Les jeux doivent être rendus dans un état correct, complets
                            et avec l’ensemble de leurs éléments.
                        </p>
                    </div>
                </div>

                <div class="about-rule">
                    <span class="about-rule-number">2</span>
                    <div>
                        <h3>Respect des délais</h3>
                        <p>
                            Les dates de retour indiquées dans l’espace personnel doivent
                            être respectées afin de garantir la disponibilité des jeux.
                        </p>
                    </div>
                </div>

                <div class="about-rule">
                    <span class="about-rule-number">3</span>
                    <div>
                        <h3>Demandes soumises à validation</h3>
                        <p>
                            Les demandes d’emprunt, de location ou de réservation ne sont
                            effectives qu’après validation par l’administration.
                        </p>
                    </div>
                </div>

                <div class="about-rule">
                    <span class="about-rule-number">4</span>
                    <div>
                        <h3>Utilisation conforme</h3>
                        <p>
                            Les jeux et services proposés par l’association doivent être
                            utilisés dans le respect du règlement interne et de la vie du campus.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-cta">
            <div class="about-cta-card">
                <h2>Envie d’en savoir plus ?</h2>
                <p>
                    Consultez la ludothèque, contactez l’association ou connectez-vous
                    à votre espace personnel pour profiter des services disponibles.
                </p>

                <div class="about-cta-actions">
                    <a href="ludotheque.php" class="btn-main about-btn-main">Voir la ludothèque</a>
                    <a href="contact.php" class="btn-secondary">Nous contacter</a>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>
