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
    $erreurs[] = "L'emprunt est réservé aux membres.";
}

if ($statutJeu !== "en stock") {
    $erreurs[] = "Ce jeu n'est pas disponible à l'emprunt.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($erreurs)) {
    $dateDebut = $_POST["date_debut"] ?? "";
    $dateFin = $_POST["date_fin"] ?? "";

    if ($dateDebut === "" || $dateFin === "") {
        $erreurs[] = "Merci de renseigner une date de début et une date de fin.";
    } elseif ($dateFin < $dateDebut) {
        $erreurs[] = "La date de fin doit être après la date de début.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO demande (
                id_utilisateur,
                id_jeu,
                type_demande,
                statut,
                date_demande,
                date_debut,
                date_fin
            ) VALUES (
                :id_utilisateur,
                :id_jeu,
                :type_demande,
                :statut,
                CURDATE(),
                :date_debut,
                :date_fin
            )
        ");

        $stmt->execute([
            "id_utilisateur" => $_SESSION["user_id"],
            "id_jeu" => $idJeu,
            "type_demande" => "emprunt",
            "statut" => "en attente",
            "date_debut" => $dateDebut,
            "date_fin" => $dateFin
        ]);

        $succes = "Votre demande d'emprunt a bien été envoyée.";
    }
}

include "includes/header.php";
?>

<main class="section">
    <h1 class="section-title">Demande d'emprunt</h1>
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
                Vous allez envoyer une demande d'emprunt pour
                <strong><?= htmlspecialchars($jeu["nom"]) ?></strong>.
            </p>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="date_debut">Date de début</label>
                    <input type="date" class="form-input" name="date_debut" id="date_debut" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="date_fin">Date de fin</label>
                    <input type="date" class="form-input" name="date_fin" id="date_fin" required>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn-main">Confirmer la demande</button>
                    <a href="ludotheque.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include "includes/footer.php"; ?>