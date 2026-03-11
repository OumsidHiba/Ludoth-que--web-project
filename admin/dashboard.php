<?php
require_once "../config/db.php";
require_once "../includes/session.php";

/*
    Sécurité : accès admin uniquement
*/

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth.php?mode=login");
    exit;
}

$role = $_SESSION["role"];

if ($role !== "admin" && $role !== "president") {
    header("Location: ../index.php");
    exit;
}

/*
    Statistiques
*/

$stats = [
    "jeux_total" => 0,
    "jeux_stock" => 0,
    "jeux_empruntes" => 0,
    "jeux_loues" => 0,
    "demandes_attente" => 0,
    "evenements" => 0
];

try {

    $stmt = $pdo->query("SELECT COUNT(*) FROM jeu");
    $stats["jeux_total"] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'en stock'");
    $stats["jeux_stock"] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'emprunté'");
    $stats["jeux_empruntes"] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM jeu WHERE LOWER(statut) = 'loué'");
    $stats["jeux_loues"] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM demande WHERE LOWER(statut) = 'en attente'");
    $stats["demandes_attente"] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM evenement WHERE date_evenement >= CURDATE()");
    $stats["evenements"] = $stmt->fetchColumn();

} catch(PDOException $e) {

}

include "../includes/header.php";
?>

<main class="admin-page">

<div class="container">

<h1 class="admin-title">Tableau de bord</h1>

<p class="admin-subtitle">
Vue globale de la ludothèque et des activités en cours.
</p>


<div class="admin-stats">

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["jeux_total"] ?></div>
<div class="admin-stat-label">Jeux total</div>
</div>

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["jeux_stock"] ?></div>
<div class="admin-stat-label">En stock</div>
</div>

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["jeux_empruntes"] ?></div>
<div class="admin-stat-label">Empruntés</div>
</div>

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["jeux_loues"] ?></div>
<div class="admin-stat-label">Loués</div>
</div>

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["demandes_attente"] ?></div>
<div class="admin-stat-label">Demandes en attente</div>
</div>

<div class="admin-stat-card">
<div class="admin-stat-number"><?= $stats["evenements"] ?></div>
<div class="admin-stat-label">Événements à venir</div>
</div>

</div>


<h2 class="admin-section-title">Administration</h2>

<div class="admin-actions">

<a href="jeux.php" class="admin-action-card">
🎲
<span>Gestion des jeux</span>
</a>

<a href="evenements.php" class="admin-action-card">
📅
<span>Gestion des événements</span>
</a>

<a href="demandes.php" class="admin-action-card">
📨
<span>Traitement des demandes</span>
</a>

<?php if($role === "president"): ?>

<a href="bureau.php" class="admin-action-card">
👥
<span>Gestion du bureau</span>
</a>

<?php endif; ?>

</div>

</div>

</main>

<?php include "../includes/footer.php"; ?>