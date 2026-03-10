<?php
require_once "config/db.php";
require_once "includes/session.php";

$nbJoueurs = $_GET["joueurs"] ?? "";
$difficulteApprentissage = $_GET["difficulte_apprentissage"] ?? "";
$difficulteJeu = $_GET["difficulte_jeu"] ?? "";
$tempsJeu = $_GET["temps"] ?? "";

$sql = "SELECT * FROM jeu WHERE 1=1";
$params = [];

/*
    joueurs = on vérifie que la valeur est entre min et max
*/
if ($nbJoueurs !== "") {
    $sql .= " AND nb_joueurs_min <= :joueurs AND nb_joueurs_max >= :joueurs";
    $params["joueurs"] = (int)$nbJoueurs;
}

if ($difficulteApprentissage !== "") {
    $sql .= " AND difficulte_apprentissage = :difficulte_apprentissage";
    $params["difficulte_apprentissage"] = (int)$difficulteApprentissage;
}

if ($difficulteJeu !== "") {
    $sql .= " AND difficulte_jeu = :difficulte_jeu";
    $params["difficulte_jeu"] = (int)$difficulteJeu;
}

/*
    temps :
    - court = <= 30
    - moyen = 31 à 60
    - long = > 60
*/
if ($tempsJeu === "court") {
    $sql .= " AND temps_jeu_moyen <= 30";
} elseif ($tempsJeu === "moyen") {
    $sql .= " AND temps_jeu_moyen BETWEEN 31 AND 60";
} elseif ($tempsJeu === "long") {
    $sql .= " AND temps_jeu_moyen > 60";
}

$sql .= " ORDER BY nom ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jeux = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "includes/header.php";
?>

<main class="ludo-page">
    <div class="container">

        <section class="ludo-hero">
            <div class="ludo-hero-left">
                <h1>La ludothèque</h1>
                <p>
                    Parcourez notre catalogue de jeux, filtrez selon vos envies
                    et découvrez les titres disponibles à l’emprunt, à la location
                    ou à la réservation.
                </p>
            </div>

            <div class="ludo-hero-right">
                <div class="ludo-badge">
                    <?= count($jeux) ?> jeu<?= count($jeux) > 1 ? "x" : "" ?> trouvé<?= count($jeux) > 1 ? "s" : "" ?>
                </div>
            </div>
        </section>

        <section class="filter-card">
            <h2>Filtrer les jeux</h2>

            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="joueurs">Nombre de joueurs</label>
                    <select name="joueurs" id="joueurs">
                        <option value="">Tous</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= ($nbJoueurs == $i) ? "selected" : "" ?>>
                                <?= $i ?> joueur<?= $i > 1 ? "s" : "" ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="difficulte_apprentissage">Difficulté d’apprentissage</label>
                    <select name="difficulte_apprentissage" id="difficulte_apprentissage">
                        <option value="">Toutes</option>
                        <option value="1" <?= ($difficulteApprentissage === "1") ? "selected" : "" ?>>1 - Très facile</option>
                        <option value="2" <?= ($difficulteApprentissage === "2") ? "selected" : "" ?>>2 - Facile</option>
                        <option value="3" <?= ($difficulteApprentissage === "3") ? "selected" : "" ?>>3 - Moyen</option>
                        <option value="4" <?= ($difficulteApprentissage === "4") ? "selected" : "" ?>>4 - Difficile</option>
                        <option value="5" <?= ($difficulteApprentissage === "5") ? "selected" : "" ?>>5 - Très difficile</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="difficulte_jeu">Difficulté du jeu</label>
                    <select name="difficulte_jeu" id="difficulte_jeu">
                        <option value="">Toutes</option>
                        <option value="1" <?= ($difficulteJeu === "1") ? "selected" : "" ?>>1 - Très facile</option>
                        <option value="2" <?= ($difficulteJeu === "2") ? "selected" : "" ?>>2 - Facile</option>
                        <option value="3" <?= ($difficulteJeu === "3") ? "selected" : "" ?>>3 - Moyen</option>
                        <option value="4" <?= ($difficulteJeu === "4") ? "selected" : "" ?>>4 - Difficile</option>
                        <option value="5" <?= ($difficulteJeu === "5") ? "selected" : "" ?>>5 - Très difficile</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="temps">Temps de jeu</label>
                    <select name="temps" id="temps">
                        <option value="">Tous</option>
                        <option value="court" <?= ($tempsJeu === "court") ? "selected" : "" ?>>Court (≤ 30 min)</option>
                        <option value="moyen" <?= ($tempsJeu === "moyen") ? "selected" : "" ?>>Moyen (31 à 60 min)</option>
                        <option value="long" <?= ($tempsJeu === "long") ? "selected" : "" ?>>Long (&gt; 60 min)</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-main">Filtrer</button>
                    <a href="ludotheque.php" class="btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </section>

        <section class="games-list-section">
            <?php if (count($jeux) > 0): ?>
                <div class="games-list">
                    <?php foreach ($jeux as $jeu): ?>
                        <article class="game-card-full">
                            <div class="game-card-image">
                                🎲
                            </div>

                            <div class="game-card-content">
                                <div class="game-card-top">
                                    <h3><?= htmlspecialchars($jeu["nom"]) ?></h3>

                                    <?php
                                    $statut = strtolower(trim($jeu["statut"]));
                                    $statutClass = "status-default";

                                    if ($statut === "en stock") {
                                        $statutClass = "status-stock";
                                    } elseif ($statut === "emprunté") {
                                        $statutClass = "status-borrowed";
                                    } elseif ($statut === "loué") {
                                        $statutClass = "status-rented";
                                    } elseif ($statut === "indisponible") {
                                        $statutClass = "status-unavailable";
                                    }
                                    ?>

                                    <span class="status-badge <?= $statutClass ?>">
                                        <?= htmlspecialchars($jeu["statut"]) ?>
                                    </span>
                                </div>

                                <p class="game-description">
                                    <?= htmlspecialchars($jeu["description"] ?: "Aucune description disponible pour ce jeu.") ?>
                                </p>

                                <div class="game-infos">
                                    <div class="game-info-box">
                                        <span class="game-info-label">Joueurs</span>
                                        <span class="game-info-value">
                                            <?= htmlspecialchars($jeu["nb_joueurs_min"]) ?> à <?= htmlspecialchars($jeu["nb_joueurs_max"]) ?>
                                        </span>
                                    </div>

                                    <div class="game-info-box">
                                        <span class="game-info-label">Temps</span>
                                        <span class="game-info-value">
                                            <?= htmlspecialchars($jeu["temps_jeu_moyen"]) ?> min
                                        </span>
                                    </div>

                                    <div class="game-info-box">
                                        <span class="game-info-label">Apprentissage</span>
                                        <span class="game-info-value">
                                            <?= htmlspecialchars($jeu["difficulte_apprentissage"]) ?>/5
                                        </span>
                                    </div>

                                    <div class="game-info-box">
                                        <span class="game-info-label">Difficulté</span>
                                        <span class="game-info-value">
                                            <?= htmlspecialchars($jeu["difficulte_jeu"]) ?>/5
                                        </span>
                                    </div>
                                </div>

                                <div class="game-actions">
                                    <a href="#" class="btn-secondary">Voir la fiche</a>

                                    <?php if (!isset($_SESSION["user_id"])): ?>
                                        <a href="auth.php?mode=login" class="btn-main">Se connecter</a>

                                    <?php else: ?>
                                        <?php if ($statut === "en stock"): ?>
                                            <?php if (!empty($_SESSION["statut_membre"])): ?>
                                                <a href="#" class="btn-main">Demander un emprunt</a>
                                            <?php else: ?>
                                                <a href="#" class="btn-main">Demander une location</a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button class="btn-disabled" disabled>Indisponible actuellement</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>Aucun jeu trouvé</h3>
                    <p>
                        Aucun jeu ne correspond aux filtres sélectionnés.
                        Essayez avec d’autres critères.
                    </p>
                    <a href="ludotheque.php" class="btn-main">Voir tous les jeux</a>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php include "includes/footer.php"; ?>