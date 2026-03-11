<?php
require_once "../config/db.php";
require_once "../includes/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth.php?mode=login");
    exit;
}

$roleSession = $_SESSION["role"] ?? "";

if ($roleSession !== "president") {
    header("Location: ../index.php");
    exit;
}

$message = "";
$messageType = "";

/*
    AJOUTER UN ROLE AU BUREAU
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "add") {
    $idUtilisateur = (int)($_POST["id_utilisateur"] ?? 0);
    $idRole = (int)($_POST["id_role"] ?? 0);
    $dateDebut = trim($_POST["date_debut"] ?? "");
    $dateFin = trim($_POST["date_fin"] ?? "");

    if ($idUtilisateur <= 0 || $idRole <= 0 || $dateDebut === "" || $dateFin === "") {
        $message = "Tous les champs sont obligatoires.";
        $messageType = "error";
    } elseif ($dateDebut > $dateFin) {
        $message = "La date de début ne peut pas être postérieure à la date de fin.";
        $messageType = "error";
    } else {
        try {
            $pdo->beginTransaction();

            /*
                Vérifier si l'utilisateur a déjà un rôle dans occuper
            */
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM occuper WHERE id_utilisateur = ?");
            $stmt->execute([$idUtilisateur]);
            $existeDeja = (int)$stmt->fetchColumn() > 0;

            if ($existeDeja) {
                throw new Exception("Cet utilisateur a déjà un rôle dans le bureau.");
            }

            /*
                Récupérer le libellé du rôle de bureau
            */
            $stmt = $pdo->prepare("SELECT libelle_role FROM role_bureau WHERE id_role = ?");
            $stmt->execute([$idRole]);
            $roleBureau = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$roleBureau) {
                throw new Exception("Rôle de bureau introuvable.");
            }

            $libelleRole = strtolower(trim($roleBureau["libelle_role"]));

            /*
                Déterminer le role technique à écrire dans utilisateur
            */
            $roleUtilisateur = "utilisateur";

            if ($libelleRole === "président" || $libelleRole === "president") {
                $roleUtilisateur = "president";
            } elseif (
                $libelleRole === "administrateur" ||
                $libelleRole === "admin" ||
                $libelleRole === "vice-président" ||
                $libelleRole === "vice president" ||
                $libelleRole === "vice-président(e)"
            ) {
                $roleUtilisateur = "admin";
            }

            /*
                Insérer dans occuper
            */
            $stmt = $pdo->prepare("
                INSERT INTO occuper (id_utilisateur, id_role, date_debut_mandat, date_fin_mandat)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$idUtilisateur, $idRole, $dateDebut, $dateFin]);

            /*
                Mettre à jour la table utilisateur
                Toute personne ajoutée par le président devient membre
            */
            $stmt = $pdo->prepare("
                UPDATE utilisateur
                SET role = ?, statut_membre = 1
                WHERE id_utilisateur = ?
            ");
            $stmt->execute([$roleUtilisateur, $idUtilisateur]);

            $pdo->commit();

            $message = "Le rôle a bien été attribué et l'utilisateur est désormais membre.";
            $messageType = "success";
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

            $message = "Une erreur SQL est survenue.";
            $messageType = "error";
        }
    }
}

/*
    SUPPRIMER UN ROLE DU BUREAU
*/
if (isset($_GET["delete"])) {
    $idUtilisateur = (int)$_GET["delete"];

    if ($idUtilisateur > 0) {
        try {
            $pdo->beginTransaction();

            /*
                Supprimer l'entrée du bureau
            */
            $stmt = $pdo->prepare("DELETE FROM occuper WHERE id_utilisateur = ?");
            $stmt->execute([$idUtilisateur]);

            /*
                Remettre l'utilisateur à un rôle standard
                et membre = 1 par défaut
            */
            $stmt = $pdo->prepare("
                UPDATE utilisateur
                SET role = 'utilisateur', statut_membre = 1
                WHERE id_utilisateur = ?
            ");
            $stmt->execute([$idUtilisateur]);

            $pdo->commit();

            $message = "Le rôle a été retiré du bureau.";
            $messageType = "success";
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $message = "Impossible de retirer ce rôle.";
            $messageType = "error";
        }
    }
}

/*
    LISTE DU BUREAU
*/
$stmt = $pdo->query("
    SELECT 
        u.id_utilisateur,
        u.nom,
        u.prenom,
        u.email,
        u.role AS role_utilisateur,
        r.libelle_role,
        o.date_debut_mandat,
        o.date_fin_mandat
    FROM occuper o
    JOIN utilisateur u ON u.id_utilisateur = o.id_utilisateur
    JOIN role_bureau r ON r.id_role = o.id_role
    ORDER BY r.libelle_role ASC, u.nom ASC
");
$bureau = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
    UTILISATEURS NON DEJA DANS LE BUREAU
*/
$users = $pdo->query("
    SELECT u.id_utilisateur, u.nom, u.prenom, u.role, u.statut_membre
    FROM utilisateur u
    WHERE u.id_utilisateur NOT IN (
        SELECT id_utilisateur FROM occuper
    )
    ORDER BY u.nom ASC, u.prenom ASC
")->fetchAll(PDO::FETCH_ASSOC);

/*
    ROLES DE BUREAU
*/
$roles = $pdo->query("
    SELECT id_role, libelle_role
    FROM role_bureau
    ORDER BY libelle_role ASC
")->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<main class="admin-page">
    <div class="container">

        <h1 class="admin-title">Gestion du bureau</h1>
        <p class="admin-subtitle">
            Attribuez les rôles du bureau et mettez à jour automatiquement les droits utilisateurs.
        </p>

        <?php if ($message): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <section class="admin-form-card">
            <h2>Ajouter un membre au bureau</h2>

            <form method="POST" class="admin-jeu-form">
                <input type="hidden" name="action" value="add">

                <div class="admin-form-grid">
                    <div class="form-group">
                        <label class="form-label">Utilisateur</label>
                        <select name="id_utilisateur" class="form-input" required>
                            <option value="">Choisir</option>

                            <?php foreach ($users as $user): ?>
                                <option value="<?= (int)$user["id_utilisateur"] ?>">
                                    <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?>
                                    — <?= htmlspecialchars($user["role"]) ?>
                                    — <?= ((int)$user["statut_membre"] === 1) ? "membre" : "non membre" ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rôle du bureau</label>
                        <select name="id_role" class="form-input" required>
                            <option value="">Choisir</option>

                            <?php foreach ($roles as $role): ?>
                                <option value="<?= (int)$role["id_role"] ?>">
                                    <?= htmlspecialchars($role["libelle_role"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Début mandat</label>
                        <input type="date" name="date_debut" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Fin mandat</label>
                        <input type="date" name="date_fin" class="form-input" required>
                    </div>
                </div>

                <button type="submit" class="btn-main admin-inline-btn">
                    Ajouter au bureau
                </button>
            </form>
        </section>

        <section class="admin-table-section">
            <h2 class="admin-section-title">Membres du bureau</h2>

            <div class="admin-demandes-list">
                <?php if (count($bureau) === 0): ?>
                    <div class="admin-empty-card">
                        <h2>Aucun membre dans le bureau</h2>
                        <p>Aucun rôle n’est encore attribué.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($bureau as $membre): ?>
                        <article class="admin-demande-card">
                            <div class="admin-demande-top">
                                <div>
                                    <h2><?= htmlspecialchars($membre["prenom"] . " " . $membre["nom"]) ?></h2>
                                    <p class="admin-demande-user">
                                        <?= htmlspecialchars($membre["email"]) ?>
                                    </p>
                                </div>

                                <span class="request-status request-status-default">
                                    <?= htmlspecialchars($membre["libelle_role"]) ?>
                                </span>
                            </div>

                            <div class="admin-demande-grid">
                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Rôle utilisateur</span>
                                    <span class="admin-demande-value">
                                        <?= htmlspecialchars($membre["role_utilisateur"]) ?>
                                    </span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Début mandat</span>
                                    <span class="admin-demande-value">
                                        <?= !empty($membre["date_debut_mandat"]) ? date("d/m/Y", strtotime($membre["date_debut_mandat"])) : "—" ?>
                                    </span>
                                </div>

                                <div class="admin-demande-box">
                                    <span class="admin-demande-label">Fin mandat</span>
                                    <span class="admin-demande-value">
                                        <?= !empty($membre["date_fin_mandat"]) ? date("d/m/Y", strtotime($membre["date_fin_mandat"])) : "—" ?>
                                    </span>
                                </div>
                            </div>

                            <div class="admin-demande-actions">
                                <a href="bureau.php?delete=<?= (int)$membre["id_utilisateur"] ?>"
                                   class="btn-danger admin-inline-btn"
                                   onclick="return confirm('Retirer ce rôle du bureau ?');">
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