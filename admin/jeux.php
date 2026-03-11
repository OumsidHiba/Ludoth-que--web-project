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
$jeuEdit = null;

$formData = [
    "nom" => "",
    "temps_jeu_moyen" => "",
    "nb_joueurs_min" => "",
    "nb_joueurs_max" => "",
    "difficulte_apprentissage" => "",
    "difficulte_jeu" => "",
    "statut" => "en stock",
    "regles" => "",
    "description" => "",
    "image" => ""
];

/*
    Charger un jeu à modifier
*/
if (isset($_GET["edit"])) {
    $idEdit = (int) $_GET["edit"];

    if ($idEdit > 0) {
        $stmt = $pdo->prepare("SELECT * FROM jeu WHERE id_jeu = ?");
        $stmt->execute([$idEdit]);
        $jeuEdit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jeuEdit) {
            $modeForm = "edit";
            $formData = $jeuEdit;
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
            $stmt = $pdo->prepare("DELETE FROM jeu WHERE id_jeu = ?");
            $stmt->execute([$idDelete]);

            $message = "Le jeu a bien été supprimé.";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Impossible de supprimer ce jeu. Il est peut-être lié à des demandes.";
            $messageType = "error";
        }
    }
}

/*
    Fonction upload image
*/
function handleImageUpload(): string|false {
    if (empty($_FILES["image"]["name"])) {
        return false; // pas de fichier envoyé
    }

    $file = $_FILES["image"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Erreur lors de l'upload de l'image.");
    }

    $allowedTypes = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file["tmp_name"]);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Format d'image non autorisé. Utilisez JPG, PNG, WEBP ou GIF.");
    }

    if ($file["size"] > 2 * 1024 * 1024) {
        throw new Exception("L'image ne doit pas dépasser 2 Mo.");
    }

    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $nomFichier = uniqid("jeu_") . "." . strtolower($ext);
    $destination = "../assets/img/" . $nomFichier;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        throw new Exception("Impossible de sauvegarder l'image.");
    }

    return $nomFichier;
}

/*
    Ajout / modification
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    $formData["nom"] = trim($_POST["nom"] ?? "");
    $formData["temps_jeu_moyen"] = trim($_POST["temps_jeu_moyen"] ?? "");
    $formData["nb_joueurs_min"] = trim($_POST["nb_joueurs_min"] ?? "");
    $formData["nb_joueurs_max"] = trim($_POST["nb_joueurs_max"] ?? "");
    $formData["difficulte_apprentissage"] = trim($_POST["difficulte_apprentissage"] ?? "");
    $formData["difficulte_jeu"] = trim($_POST["difficulte_jeu"] ?? "");
    $formData["statut"] = trim($_POST["statut"] ?? "en stock");
    $formData["regles"] = trim($_POST["regles"] ?? "");
    $formData["description"] = trim($_POST["description"] ?? "");
    $formData["image"] = trim($_POST["image_actuelle"] ?? "");

    if (
        $formData["nom"] === "" ||
        $formData["temps_jeu_moyen"] === "" ||
        $formData["nb_joueurs_min"] === "" ||
        $formData["nb_joueurs_max"] === "" ||
        $formData["difficulte_apprentissage"] === "" ||
        $formData["difficulte_jeu"] === "" ||
        $formData["statut"] === ""
    ) {
        $message = "Tous les champs obligatoires doivent être remplis.";
        $messageType = "error";
    } elseif (!is_numeric($formData["temps_jeu_moyen"]) || (int)$formData["temps_jeu_moyen"] <= 0) {
        $message = "Le temps de jeu doit être un nombre positif.";
        $messageType = "error";
    } elseif (!is_numeric($formData["nb_joueurs_min"]) || !is_numeric($formData["nb_joueurs_max"])) {
        $message = "Le nombre de joueurs doit être numérique.";
        $messageType = "error";
    } elseif ((int)$formData["nb_joueurs_min"] <= 0 || (int)$formData["nb_joueurs_max"] <= 0) {
        $message = "Le nombre de joueurs doit être positif.";
        $messageType = "error";
    } elseif ((int)$formData["nb_joueurs_min"] > (int)$formData["nb_joueurs_max"]) {
        $message = "Le nombre minimum de joueurs ne peut pas être supérieur au maximum.";
        $messageType = "error";
    } elseif (
        !in_array((int)$formData["difficulte_apprentissage"], [1, 2, 3, 4, 5], true) ||
        !in_array((int)$formData["difficulte_jeu"], [1, 2, 3, 4, 5], true)
    ) {
        $message = "Les difficultés doivent être comprises entre 1 et 5.";
        $messageType = "error";
    } else {
        try {
            // Gestion de l'image
            $nouvelleImage = handleImageUpload();
            if ($nouvelleImage !== false) {
                $formData["image"] = $nouvelleImage;
            }

            if ($action === "add") {
                $stmt = $pdo->prepare("
                    INSERT INTO jeu (
                        nom, temps_jeu_moyen, nb_joueurs_min, nb_joueurs_max,
                        difficulte_apprentissage, difficulte_jeu, statut,
                        regles, description, image
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $formData["nom"],
                    (int)$formData["temps_jeu_moyen"],
                    (int)$formData["nb_joueurs_min"],
                    (int)$formData["nb_joueurs_max"],
                    (int)$formData["difficulte_apprentissage"],
                    (int)$formData["difficulte_jeu"],
                    $formData["statut"],
                    $formData["regles"],
                    $formData["description"],
                    $formData["image"]
                ]);

                $message = "Le jeu a bien été ajouté.";
                $messageType = "success";

                $formData = [
                    "nom" => "", "temps_jeu_moyen" => "", "nb_joueurs_min" => "",
                    "nb_joueurs_max" => "", "difficulte_apprentissage" => "",
                    "difficulte_jeu" => "", "statut" => "en stock",
                    "regles" => "", "description" => "", "image" => ""
                ];
            }

            if ($action === "edit") {
                $idJeu = (int) ($_POST["id_jeu"] ?? 0);

                if ($idJeu <= 0) {
                    throw new Exception("Jeu invalide.");
                }

                $stmt = $pdo->prepare("
                    UPDATE jeu
                    SET nom = ?, temps_jeu_moyen = ?, nb_joueurs_min = ?,
                        nb_joueurs_max = ?, difficulte_apprentissage = ?,
                        difficulte_jeu = ?, statut = ?, regles = ?,
                        description = ?, image = ?
                    WHERE id_jeu = ?
                ");
                $stmt->execute([
                    $formData["nom"],
                    (int)$formData["temps_jeu_moyen"],
                    (int)$formData["nb_joueurs_min"],
                    (int)$formData["nb_joueurs_max"],
                    (int)$formData["difficulte_apprentissage"],
                    (int)$formData["difficulte_jeu"],
                    $formData["statut"],
                    $formData["regles"],
                    $formData["description"],
                    $formData["image"],
                    $idJeu
                ]);

                $message = "Le jeu a bien été modifié.";
                $messageType = "success";

                $modeForm = "add";
                $jeuEdit = null;

                $formData = [
                    "nom" => "", "temps_jeu_moyen" => "", "nb_joueurs_min" => "",
                    "nb_joueurs_max" => "", "difficulte_apprentissage" => "",
                    "difficulte_jeu" => "", "statut" => "en stock",
                    "regles" => "", "description" => "", "image" => ""
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
    Liste des jeux
*/
$stmt = $pdo->query("SELECT * FROM jeu ORDER BY nom ASC");
$jeux = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<main class="admin-page">
    <div class="container">

        <h1 class="admin-title">Gestion des jeux</h1>
        <p class="admin-subtitle">
            Ajoutez, modifiez ou supprimez les jeux de la ludothèque.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <section class="admin-form-card">
            <h2><?= $modeForm === "edit" ? "Modifier un jeu" : "Ajouter un jeu" ?></h2>

            <!-- enctype obligatoire pour l'upload -->
            <form method="POST" enctype="multipart/form-data" class="admin-jeu-form">
                <input type="hidden" name="action" value="<?= $modeForm ?>">
                <?php if ($modeForm === "edit" && !empty($jeuEdit["id_jeu"])): ?>
                    <input type="hidden" name="id_jeu" value="<?= (int)$jeuEdit["id_jeu"] ?>">
                    <input type="hidden" name="image_actuelle" value="<?= htmlspecialchars($formData["image"] ?? "") ?>">
                <?php endif; ?>

                <div class="admin-form-grid">
                    <div class="form-group">
                        <label class="form-label">Nom du jeu</label>
                        <input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($formData["nom"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Temps moyen (min)</label>
                        <input type="number" name="temps_jeu_moyen" class="form-input" value="<?= htmlspecialchars((string)$formData["temps_jeu_moyen"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Joueurs min</label>
                        <input type="number" name="nb_joueurs_min" class="form-input" value="<?= htmlspecialchars((string)$formData["nb_joueurs_min"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Joueurs max</label>
                        <input type="number" name="nb_joueurs_max" class="form-input" value="<?= htmlspecialchars((string)$formData["nb_joueurs_max"]) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Difficulté apprentissage</label>
                        <select name="difficulte_apprentissage" class="form-input" required>
                            <option value="">Choisir</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= ((string)$formData["difficulte_apprentissage"] === (string)$i) ? "selected" : "" ?>>
                                    <?= $i ?>/5
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Difficulté du jeu</label>
                        <select name="difficulte_jeu" class="form-input" required>
                            <option value="">Choisir</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= ((string)$formData["difficulte_jeu"] === (string)$i) ? "selected" : "" ?>>
                                    <?= $i ?>/5
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-input" required>
                            <?php
                            $statuts = ["en stock", "emprunté", "loué", "indisponible"];
                            foreach ($statuts as $statut):
                            ?>
                                <option value="<?= $statut ?>" <?= ($formData["statut"] === $statut) ? "selected" : "" ?>>
                                    <?= ucfirst($statut) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Champ image -->
                    <div class="form-group">
                        <label class="form-label">Image du jeu</label>
                        <?php if (!empty($formData["image"])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../assets/img/<?= htmlspecialchars($formData["image"]) ?>"
                                     alt="Image actuelle"
                                     style="height: 80px; border-radius: 10px; object-fit: cover;">
                                <span style="display:block; font-size:12px; color:#7a7f96; margin-top:4px;">
                                    Image actuelle — uploadez-en une nouvelle pour la remplacer
                                </span>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-input" accept="image/jpeg,image/png,image/webp,image/gif">
                        <span style="display:block; font-size:12px; color:#7a7f96; margin-top:6px;">
                            JPG, PNG, WEBP ou GIF — 2 Mo max
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Règles</label>
                    <textarea name="regles" class="form-textarea"><?= htmlspecialchars($formData["regles"]) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"><?= htmlspecialchars($formData["description"]) ?></textarea>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="btn-main admin-inline-btn">
                        <?= $modeForm === "edit" ? "Enregistrer les modifications" : "Ajouter le jeu" ?>
                    </button>

                    <?php if ($modeForm === "edit"): ?>
                        <a href="jeux.php" class="btn-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="admin-table-section">
            <div class="admin-demandes-list">
                <?php foreach ($jeux as $jeu): ?>
                    <article class="admin-demande-card">
                        <div class="admin-demande-top">
                            <div style="display:flex; align-items:center; gap:16px;">
                                <!-- Miniature image -->
                                <div style="width:60px; height:60px; border-radius:10px; overflow:hidden; background:#eef8f8; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:28px;">
                                    <?php if (!empty($jeu["image"])): ?>
                                        <img src="../assets/img/<?= htmlspecialchars($jeu["image"]) ?>"
                                             alt="<?= htmlspecialchars($jeu["nom"]) ?>"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    <?php else: ?>
                                        🎲
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h2><?= htmlspecialchars($jeu["nom"]) ?></h2>
                                    <p class="admin-demande-user">
                                        <?= htmlspecialchars($jeu["nb_joueurs_min"]) ?> à <?= htmlspecialchars($jeu["nb_joueurs_max"]) ?> joueurs
                                        — <?= htmlspecialchars($jeu["temps_jeu_moyen"]) ?> min
                                    </p>
                                </div>
                            </div>

                            <span class="request-status request-status-default">
                                <?= htmlspecialchars($jeu["statut"]) ?>
                            </span>
                        </div>

                        <div class="admin-demande-grid">
                            <div class="admin-demande-box">
                                <span class="admin-demande-label">Apprentissage</span>
                                <span class="admin-demande-value"><?= htmlspecialchars($jeu["difficulte_apprentissage"]) ?>/5</span>
                            </div>

                            <div class="admin-demande-box">
                                <span class="admin-demande-label">Difficulté</span>
                                <span class="admin-demande-value"><?= htmlspecialchars($jeu["difficulte_jeu"]) ?>/5</span>
                            </div>

                            <div class="admin-demande-box">
                                <span class="admin-demande-label">Règles</span>
                                <span class="admin-demande-value">
                                    <?= htmlspecialchars($jeu["regles"] ?: "Aucune règle renseignée") ?>
                                </span>
                            </div>

                            <div class="admin-demande-box">
                                <span class="admin-demande-label">Description</span>
                                <span class="admin-demande-value">
                                    <?= htmlspecialchars($jeu["description"] ?: "Aucune description") ?>
                                </span>
                            </div>
                        </div>

                        <div class="admin-demande-actions">
                            <a href="jeux.php?edit=<?= (int)$jeu["id_jeu"] ?>" class="btn-main admin-inline-btn">
                                Modifier
                            </a>

                            <a href="jeux.php?delete=<?= (int)$jeu["id_jeu"] ?>"
                               class="btn-danger admin-inline-btn"
                               onclick="return confirm('Supprimer ce jeu ?');">
                                Supprimer
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</main>

<?php include "../includes/footer.php"; ?>