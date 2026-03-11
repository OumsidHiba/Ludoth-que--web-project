<?php
require_once "../config/db.php";
require_once "../includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth.php?mode=login");
    exit;
}

$role = $_SESSION["role"] ?? "";

if ($role !== "admin" && $role !== "president") {
    header("Location: ../index.php");
    exit;
}

$message = "";
$messageType = "";

$modeForm = "add";
$eventEdit = null;

$formData = [
    "titre" => "",
    "date_evenement" => "",
    "heure_evenement" => "",
    "lieu" => "",
    "description" => "",
    "categorie" => ""
];

/*
    Charger un événement à modifier
*/
if (isset($_GET["edit"])) {
    $idEdit = (int) $_GET["edit"];

    if ($idEdit > 0) {
        $stmt = $pdo->prepare("SELECT * FROM evenement WHERE id_evenement = ?");
        $stmt->execute([$idEdit]);
        $eventEdit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($eventEdit) {
            $modeForm = "edit";
            $formData = $eventEdit;
        }
    }
}

/*
    Suppression
*/
if (isset($_GET["delete"])) {
    $idDelete = (int) $_GET["delete"];

    if ($idDelete > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM evenement WHERE id_evenement = ?");
            $stmt->execute([$idDelete]);

            $message = "L'événement a bien été supprimé.";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Impossible de supprimer cet événement.";
            $messageType = "error";
        }
    }
}

/*
    Ajout / modification
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    $formData["titre"] = trim($_POST["titre"] ?? "");
    $formData["date_evenement"] = trim($_POST["date_evenement"] ?? "");
    $formData["heure_evenement"] = trim($_POST["heure_evenement"] ?? "");
    $formData["lieu"] = trim($_POST["lieu"] ?? "");
    $formData["description"] = trim($_POST["description"] ?? "");
    $formData["categorie"] = trim($_POST["categorie"] ?? "");

    $categoriesAutorisees = [
        "Salle du jeudi",
        "Jeu du jeudi",
        "Soirée jeux",
        "Événement occasionnel"
    ];

    if (
        $formData["titre"] === "" ||
        $formData["date_evenement"] === "" ||
        $formData["heure_evenement"] === "" ||
        $formData["lieu"] === "" ||
        $formData["description"] === "" ||
        $formData["categorie"] === ""
    ) {
        $message = "Tous les champs sont obligatoires.";
        $messageType = "error";
    } elseif (!in_array($formData["categorie"], $categoriesAutorisees, true)) {
        $message = "Catégorie invalide.";
        $messageType = "error";
    } else {
        try {
            if ($action === "add") {
                $stmt = $pdo->prepare("
                    INSERT INTO evenement (
                        titre,
                        date_evenement,
                        heure_evenement,
                        lieu,
                        description,
                        categorie
                    ) VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $formData["titre"],
                    $formData["date_evenement"],
                    $formData["heure_evenement"],
                    $formData["lieu"],
                    $formData["description"],
                    $formData["categorie"]
                ]);

                $message = "L'événement a bien été ajouté.";
                $messageType = "success";

                $formData = [
                    "titre" => "",
                    "date_evenement" => "",
                    "heure_evenement" => "",
                    "lieu" => "",
                    "description" => "",
                    "categorie" => ""
                ];
            }

            if ($action === "edit") {
                $idEvenement = (int) ($_POST["id_evenement"] ?? 0);

                if ($idEvenement <= 0) {
                    throw new Exception("Événement invalide.");
                }

                $stmt = $pdo->prepare("
                    UPDATE evenement
                    SET
                        titre = ?,
                        date_evenement = ?,
                        heure_evenement = ?,
                        lieu = ?,
                        description = ?,
                        categorie = ?
                    WHERE id_evenement = ?
                ");
                $stmt->execute([
                    $formData["titre"],
                    $formData["date_evenement"],
                    $formData["heure_evenement"],
                    $formData["lieu"],
                    $formData["description"],
                    $formData["categorie"],
                    $idEvenement
                ]);

                $message = "L'événement a bien été modifié.";
                $messageType = "success";

                $modeForm = "add";
                $eventEdit = null;

                $formData = [
                    "titre" => "",
                    "date_evenement" => "",
                    "heure_evenement" => "",
                    "lieu" => "",
                    "description" => "",
                    "categorie" => ""
                ];
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            $messageType = "error";
        } catch (PDOException $e) {
            $message = "Une erreur SQL est survenue.";
            $messageType = "error";
        }
    }
}

/*
    Liste des événements
*/
$stmt = $pdo->query("SELECT * FROM evenement ORDER BY date_evenement ASC, heure_evenement ASC");
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<main class="admin-page">
    <div class="container">

        <h1 class="admin-title">Gestion des événements</h1>
        <p class="admin-subtitle">
            Créez, modifiez et supprimez les événements de la ludothèque.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <section class="admin-form-card">
            <h2><?= $modeForm === "edit" ? "Modifier un événement" : "Ajouter un événement" ?></h2>

            <form method="POST" class="admin-jeu-form">
                <input type="hidden" name="action" value="<?= $modeForm ?>">
                <?php if ($modeForm === "edit" && !empty($eventEdit["id_evenement"])): ?>
                    <input type="hidden" name="id_evenement" value="<?= (int)$eventEdit["id_evenement"] ?>">
                <?php endif; ?>

                <div class="admin-form-grid">
                    <div class="form-group">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-input" value="<?= htmlspecialchars($formData["titre"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date_evenement" class="form-input" value="<?= htmlspecialchars($formData["date_evenement"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Heure</label>
                        <input type="time" name="heure_evenement" class="form-input" value="<?= htmlspecialchars($formData["heure_evenement"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lieu</label>
                        <input type="text" name="lieu" class="form-input" value="<?= htmlspecialchars($formData["lieu"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie" class="form-input" required>
                            <option value="">Choisir</option>
                            <?php
                            $categories = [
                                "Salle du jeudi",
                                "Jeu du jeudi",
                                "Soirée jeux",
                                "Événement occasionnel"
                            ];
                            foreach ($categories as $categorie):
                            ?>
                                <option value="<?= $categorie ?>" <?= ($formData["categorie"] === $categorie) ? "selected" : "" ?>>
                                    <?= $categorie ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" required><?= htmlspecialchars($formData["description"]) ?></textarea>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="btn-main admin-inline-btn">
                        <?= $modeForm === "edit" ? "Enregistrer les modifications" : "Ajouter l'événement" ?>
                    </button>

                    <?php if ($modeForm === "edit"): ?>
                        <a href="evenements.php" class="btn-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="admin-table-section">
            <div class="admin-demandes-list">
                <?php if (count($evenements) === 0): ?>
                    <div class="admin-empty-card">
                        <h2>Aucun événement</h2>
                        <p>Aucun événement n'est encore enregistré.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($evenements as $evenement): ?>
                        <article class="admin-demande-card">
                            <div class="admin-demande-top">
                                <div>
                                    <h2><?= htmlspecialchars($evenement["titre"]) ?></h2>
                                    <p class="admin-demande-user">
                                        <?= !empty($evenement["date_evenement"]) ? date("d/m/Y", strtotime($evenement["date_evenement"])) : "—" ?>
                                        — <?= htmlspecialchars(substr($evenement["heure_evenement"], 0, 5)) ?>
                                        — <?= htmlspecialchars($evenement["lieu"]) ?>
                                    </p>
                                </div>

                                <span class="request-status request-status-default">
                                    <?= htmlspecialchars($evenement["categorie"]) ?>
                                </span>
                            </div>

                            <div class="admin-demande-grid admin-evenement-grid">
                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Catégorie</span>
                                    <span class="admin-demande-value"><?= htmlspecialchars($evenement["categorie"]) ?></span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Description</span>
                                    <span class="admin-demande-value">
                                        <?= htmlspecialchars($evenement["description"]) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="admin-demande-actions">
                                <a href="evenements.php?edit=<?= (int)$evenement["id_evenement"] ?>" class="btn-main admin-inline-btn">
                                    Modifier
                                </a>

                                <a href="evenements.php?delete=<?= (int)$evenement["id_evenement"] ?>"
                                   class="btn-danger admin-inline-btn"
                                   onclick="return confirm('Supprimer cet événement ?');">
                                    Supprimer
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php include "../includes/footer.php"; ?>