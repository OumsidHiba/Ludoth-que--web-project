<?php
require_once "../config/db.php";
require_once "../includes/session.php";

/*
    Sécurité : admin ou président uniquement
*/
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

/*
    Traitement acceptation / refus
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $idDemande = isset($_POST["id_demande"]) ? (int) $_POST["id_demande"] : 0;

    if ($idDemande > 0 && in_array($action, ["accepter", "refuser"], true)) {
        try {
            $pdo->beginTransaction();

            /*
                On récupère la demande concernée
            */
            $stmt = $pdo->prepare("
                SELECT d.id_demande, d.id_jeu, d.type_demande, d.statut, j.nom AS nom_jeu
                FROM demande d
                INNER JOIN jeu j ON d.id_jeu = j.id_jeu
                WHERE d.id_demande = ?
                LIMIT 1
            ");
            $stmt->execute([$idDemande]);
            $demande = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demande) {
                throw new Exception("Demande introuvable.");
            }

            if (strtolower($demande["statut"]) !== "en attente") {
                throw new Exception("Cette demande a déjà été traitée.");
            }

            if ($action === "accepter") {
                /*
                    On valide la demande
                */
                $stmt = $pdo->prepare("
                    UPDATE demande
                    SET statut = 'validée'
                    WHERE id_demande = ?
                ");
                $stmt->execute([$idDemande]);

                /*
                    On met à jour le statut du jeu selon le type de demande
                */
                $nouveauStatutJeu = "indisponible";

                if (strtolower($demande["type_demande"]) === "emprunt") {
                    $nouveauStatutJeu = "emprunté";
                } elseif (strtolower($demande["type_demande"]) === "location") {
                    $nouveauStatutJeu = "loué";
                } elseif (strtolower($demande["type_demande"]) === "reservation") {
                    $nouveauStatutJeu = "indisponible";
                }

                $stmt = $pdo->prepare("
                    UPDATE jeu
                    SET statut = ?
                    WHERE id_jeu = ?
                ");
                $stmt->execute([$nouveauStatutJeu, $demande["id_jeu"]]);

                $message = "La demande a été acceptée et le statut du jeu a été mis à jour.";
                $messageType = "success";
            }

            if ($action === "refuser") {
                /*
                    On refuse la demande
                    Le jeu reste inchangé
                */
                $stmt = $pdo->prepare("
                    UPDATE demande
                    SET statut = 'refusée'
                    WHERE id_demande = ?
                ");
                $stmt->execute([$idDemande]);

                $message = "La demande a été refusée.";
                $messageType = "error";
            }

            $pdo->commit();
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $message = $e->getMessage();
            $messageType = "error";
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $message = "Une erreur SQL est survenue lors du traitement de la demande.";
            $messageType = "error";
        }
    }
}

/*
    Filtres
*/
$filtreStatut = $_GET["statut"] ?? "";
$filtreType = $_GET["type"] ?? "";

$sql = "
    SELECT 
        d.id_demande,
        d.type_demande,
        d.statut,
        d.date_demande,
        d.date_debut,
        d.date_fin,
        u.nom,
        u.prenom,
        u.email,
        j.nom AS nom_jeu,
        j.statut AS statut_jeu
    FROM demande d
    INNER JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
    INNER JOIN jeu j ON d.id_jeu = j.id_jeu
    WHERE 1=1
";

$params = [];

if ($filtreStatut !== "") {
    $sql .= " AND LOWER(d.statut) = :statut";
    $params["statut"] = strtolower($filtreStatut);
}

if ($filtreType !== "") {
    $sql .= " AND LOWER(d.type_demande) = :type_demande";
    $params["type_demande"] = strtolower($filtreType);
}

$sql .= " ORDER BY 
            CASE 
                WHEN LOWER(d.statut) = 'en attente' THEN 1
                WHEN LOWER(d.statut) = 'validée' THEN 2
                WHEN LOWER(d.statut) = 'refusée' THEN 3
                ELSE 4
            END,
            d.date_demande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<main class="admin-page">
    <div class="container">

        <h1 class="admin-title">Traitement des demandes</h1>
        <p class="admin-subtitle">
            Consultez les demandes d’emprunt, de location et de réservation,
            puis acceptez-les ou refusez-les.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <section class="admin-filter-card">
            <form method="GET" class="admin-filter-form">
                <div class="admin-filter-group">
                    <label for="statut">Filtrer par statut</label>
                    <select name="statut" id="statut">
                        <option value="">Tous</option>
                        <option value="en attente" <?= $filtreStatut === "en attente" ? "selected" : "" ?>>En attente</option>
                        <option value="validée" <?= $filtreStatut === "validée" ? "selected" : "" ?>>Validée</option>
                        <option value="refusée" <?= $filtreStatut === "refusée" ? "selected" : "" ?>>Refusée</option>
                    </select>
                </div>

                <div class="admin-filter-group">
                    <label for="type">Filtrer par type</label>
                    <select name="type" id="type">
                        <option value="">Tous</option>
                        <option value="emprunt" <?= $filtreType === "emprunt" ? "selected" : "" ?>>Emprunt</option>
                        <option value="location" <?= $filtreType === "location" ? "selected" : "" ?>>Location</option>
                        <option value="reservation" <?= $filtreType === "reservation" ? "selected" : "" ?>>Réservation</option>
                    </select>
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="btn-main admin-inline-btn">Filtrer</button>
                    <a href="demandes.php" class="btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </section>

        <section class="admin-table-section">
            <?php if (count($demandes) === 0): ?>
                <div class="admin-empty-card">
                    <h2>Aucune demande trouvée</h2>
                    <p>Aucune demande ne correspond aux filtres sélectionnés.</p>
                </div>
            <?php else: ?>
                <div class="admin-demandes-list">
                    <?php foreach ($demandes as $demande): ?>
                        <?php
                        $statut = strtolower($demande["statut"]);
                        $badgeClass = "request-status-default";

                        if ($statut === "en attente") {
                            $badgeClass = "request-status-pending";
                        } elseif ($statut === "validée") {
                            $badgeClass = "request-status-approved";
                        } elseif ($statut === "refusée") {
                            $badgeClass = "request-status-refused";
                        }
                        ?>

                        <article class="admin-demande-card">
                            <div class="admin-demande-top">
                                <div>
                                    <h2><?= htmlspecialchars($demande["nom_jeu"]) ?></h2>
                                    <p class="admin-demande-user">
                                        <?= htmlspecialchars($demande["prenom"] . " " . $demande["nom"]) ?> —
                                        <?= htmlspecialchars($demande["email"]) ?>
                                    </p>
                                </div>

                                <span class="request-status <?= $badgeClass ?>">
                                    <?= htmlspecialchars($demande["statut"]) ?>
                                </span>
                            </div>

                            <div class="admin-demande-grid">
                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Type</span>
                                    <span class="admin-demande-value"><?= htmlspecialchars($demande["type_demande"]) ?></span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Date de demande</span>
                                    <span class="admin-demande-value">
                                        <?= !empty($demande["date_demande"]) ? date("d/m/Y H:i", strtotime($demande["date_demande"])) : "—" ?>
                                    </span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Début</span>
                                    <span class="admin-demande-value">
                                        <?= !empty($demande["date_debut"]) ? date("d/m/Y", strtotime($demande["date_debut"])) : "—" ?>
                                    </span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Fin</span>
                                    <span class="admin-demande-value">
                                        <?= !empty($demande["date_fin"]) ? date("d/m/Y", strtotime($demande["date_fin"])) : "—" ?>
                                    </span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Statut actuel du jeu</span>
                                    <span class="admin-demande-value">
                                        <?= htmlspecialchars($demande["statut_jeu"]) ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($statut === "en attente"): ?>
                                <div class="admin-demande-actions">
                                    <form method="POST" class="admin-inline-form">
                                        <input type="hidden" name="id_demande" value="<?= (int)$demande["id_demande"] ?>">
                                        <input type="hidden" name="action" value="accepter">
                                        <button type="submit" class="btn-main admin-inline-btn">
                                            Accepter
                                        </button>
                                    </form>

                                    <form method="POST" class="admin-inline-form">
                                        <input type="hidden" name="id_demande" value="<?= (int)$demande["id_demande"] ?>">
                                        <input type="hidden" name="action" value="refuser">
                                        <button type="submit" class="btn-danger admin-inline-btn">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="admin-demande-finished">
                                    Cette demande a déjà été traitée.
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php include "../includes/footer.php"; ?>