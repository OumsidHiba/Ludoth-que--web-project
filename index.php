<?php
include "includes/header.php";
?>
<main>
    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue à la <span>Ludothèque</span></h1>
            <p>
                Découvrez notre collection de jeux de société, participez à nos événements
                et rejoignez notre communauté étudiante passionnée de jeux !
            </p>
            <a href="ludotheque.php" class="btn btn-primary">Découvrir les jeux</a>
            <a href="evenement.php" class="btn btn-outline hero-second-btn">Voir les événements</a>
        </div>
    </section>

    <!-- PRÉSENTATION -->
    <section class="section">
        <div class="section-divider"></div>
        <h2 class="section-title">Notre association</h2>
        <p class="section-subtitle">
            L'association étudiante qui anime la vie du campus à travers les jeux de société
        </p>

        <div class="presentation-grid">
            <div>
                <p class="presentation-text">
                    Notre ludothèque rassemble une collection variée de jeux de société accessibles
                    à tous les étudiants du campus. Que vous soyez un joueur expérimenté ou un
                    débutant curieux, vous trouverez le jeu qu'il vous faut.
                </p>
                <p class="presentation-text">
                    Membres de l'association, vous pouvez emprunter gratuitement des jeux.
                    Les non-membres peuvent les louer à un tarif avantageux. Rejoignez-nous
                    pour des soirées jeux inoubliables !
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card stat-card-teal">
                    <div class="stat-number stat-number-teal">50+</div>
                    <div class="stat-label">Jeux disponibles</div>
                </div>

                <div class="stat-card stat-card-navy">
                    <div class="stat-number stat-number-navy">200+</div>
                    <div class="stat-label">Membres actifs</div>
                </div>

                <div class="stat-card stat-card-navy">
                    <div class="stat-number stat-number-navy">30+</div>
                    <div class="stat-label">Événements / an</div>
                </div>

                <div class="stat-card stat-card-teal">
                    <div class="stat-number stat-number-teal">4</div>
                    <div class="stat-label">Types d'événements</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ÉVÉNEMENTS -->
    <section class="section section-white">
        <div class="section-inner">
            <div class="section-divider"></div>
            <h2 class="section-title">Nos événements</h2>
            <p class="section-subtitle">
                Retrouvez les prochains événements organisés par l'association
            </p>

            <div class="grid grid-4">
                <div class="card">
                    <div class="card-img-placeholder">
                        <img src="assets/img/Salles.jpeg" alt="">
                    </div>
                    <div class="card-body">
                        <span class="badge badge-teal">Salle du jeudi</span>
                        <h3 class="card-title card-title-space">Salle ouverte</h3>
                        <p class="card-meta">📅 Jeudi 13 mars 2026</p>
                        <p class="card-meta">📍 Salle B12</p>
                        <p class="card-meta card-meta-space">
                            La salle de jeux est ouverte à tous, venez jouer librement !
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-img-placeholder">
                        <img src="assets/img/catan.jpg" alt="">

                    </div>
                    <div class="card-body">
                        <span class="badge badge-blue">Jeu du jeudi</span>
                        <h3 class="card-title card-title-space">Tournoi de Catan</h3>
                        <p class="card-meta">📅 Jeudi 20 mars 2026</p>
                        <p class="card-meta">📍 Salle A3</p>
                        <p class="card-meta card-meta-space">
                            Tournoi amical ouvert à tous les étudiants du campus.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-img-placeholder">
                        <img src="assets/img/Soire_loup_garou.jpeg" alt="">

                    </div>
                    <div class="card-body">
                        <span class="badge badge-green">Soirée jeux</span>
                        <h3 class="card-title card-title-space">Soirée Loup-Garou</h3>
                        <p class="card-meta">📅 Vendredi 21 mars 2026</p>
                        <p class="card-meta">📍 Hall principal</p>
                        <p class="card-meta card-meta-space">
                            Grande soirée Loup-Garou avec ambiance garantie !
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-img-placeholder">
                        <img src="assets/img/Escape_game_nuit.jpeg" alt="">

                    </div>
                    <div class="card-body">
                        <span class="badge badge-purple">Occasionnel</span>
                        <h3 class="card-title card-title-space">Escape Game géant</h3>
                        <p class="card-meta">📅 Samedi 22 mars 2026</p>
                        <p class="card-meta">📍 Amphithéâtre</p>
                        <p class="card-meta card-meta-space">
                            Escape Game grandeur nature sur le campus !
                        </p>
                    </div>
                </div>
            </div>

            <div class="section-center">
                <a href="evenement.php" class="btn btn-outline">Voir tous les événements →</a>
            </div>
        </div>
    </section>

    <!-- APERÇU LUDOTHÈQUE -->
    <section class="section">
        <div class="section-divider"></div>
        <h2 class="section-title">Aperçu de la ludothèque</h2>
        <p class="section-subtitle">Quelques jeux populaires à découvrir</p>

        <div class="game-grid">
            <div class="game-card">
                <div class="game-image">
                    <img src="assets/img/stratégique.jpeg" alt="">
                </div>
                <h3>Jeu stratégique</h3>
                <p>2 à 4 joueurs • 45 min</p>
            </div>

            <div class="game-card">
                <div class="game-image">
                    <img src="assets/img/ambiance.jpeg" alt="">
                </div>
                <h3>Jeu d’ambiance</h3>
                <p>4 à 8 joueurs • 20 min</p>
            </div>

            <div class="game-card">
                <div class="game-image">
                    <img src="assets/img/cooperatif.jpeg" alt="">
                </div>
                <h3>Jeu coopératif</h3>
                <p>2 à 6 joueurs • 60 min</p>
            </div>

            <div class="game-card">
                <div class="game-image">
                    <img src="assets/img/Famille_.jpeg" alt="">
                </div>
                <h3>Classique familial</h3>
                <p>2 à 5 joueurs • 30 min</p>
            </div>
        </div>

        <div class="section-action">
            <a href="ludotheque.php" class="btn-main">Explorer la ludothèque</a>
        </div>
    </section>

    <!-- CONTACT RAPIDE -->
    <section class="section">
        <div class="contact-quick">
            <div>
                <div class="section-divider"></div>
                <h2 class="section-title">Contactez-nous</h2>
                <p class="contact-mail">📧 ludotheque@ece.fr</p>
            </div>

            <div class="footer-social">
                <a href="#" class="social-instagram">IG</a>
                <a href="#" class="social-discord">DC</a>
                <a href="#" class="social-twitter">TW</a>
            </div>
        </div>
    </section>
</main>

<?php include "includes/footer.php"; ?>