<?php
require_once "config/db.php";
require_once "includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth.php?mode=login");
    exit;
}

$idJeu = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare("SELECT * FROM jeu WHERE id_jeu = :id_jeu");
$stmt->execute(["id_jeu" => $idJeu]);
$jeu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jeu) {
    include "includes/header.php";
    echo '<main class="section"><div class="message error">Jeu introuvable.</div></main>';
    include "includes/footer.php";
    exit;
}

$statutJeu = strtolower(trim($jeu["statut"]));
$estMembre = !empty($_SESSION["statut_membre"]);
$erreurs = [];
$succes = "";

if (!$estMembre) {
    $erreurs[] = "La réservation est réservée aux membres.";
}

if ($statutJeu === "en stock") {
    $erreurs[] = "Ce jeu est actuellement disponible. Vous pouvez l'emprunter ou le louer directement.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($erreurs)) {
    $dateReservation = $_POST["date_reservation"] ?? "";

    if ($dateReservation === "") {
        $erreurs[] = "Merci de renseigner une date de réservation.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO reservation (
                id_utilisateur,
                id_jeu,
                id_evenement,
                date_reservation,
                statut
            ) VALUES (
                :id_utilisateur,
                :id_jeu,
                NULL,
                :date_reservation,
                :statut
            )
        ");

        $stmt->execute([
            "id_utilisateur" => $_SESSION["user_id"],
            "id_jeu" => $idJeu,
            "date_reservation" => $dateReservation,
            "statut" => "en attente"
        ]);

        $succes = "Votre demande de réservation a bien été envoyée.";
    }
}

include "includes/header.php";
?>

<main class="section">
    <h1 class="section-title">Réservation</h1>
    <p class="section-subtitle"><?= htmlspecialchars($jeu["nom"]) ?></p>

    <?php foreach ($erreurs as $erreur): ?>
        <div class="message error"><?= htmlspecialchars($erreur) ?></div>
    <?php endforeach; ?>

    <?php if ($succes): ?>
        <div class="message success"><?= htmlspecialchars($succes) ?></div>
        <div class="section-center">
            <a href="ludotheque.php" class="btn-main">Retour à la ludothèque</a>
        </div>
    <?php elseif (empty($erreurs) || $_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <div class="account-card" style="max-width:700px;margin:0 auto;">
            <p class="account-text">
                Vous allez envoyer une demande de réservation pour
                <strong><?= htmlspecialchars($jeu["nom"]) ?></strong>.
            </p>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="date_reservation">Date souhaitée</label>
                    <input type="date" class="form-input" name="date_reservation" id="date_reservation" required>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn-main">Confirmer la réservation</button>
                    <a href="ludotheque.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include "includes/footer.php"; ?>