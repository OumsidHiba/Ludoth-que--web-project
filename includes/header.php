<?php
require_once __DIR__ . "/session.php";

$currentPage = basename($_SERVER['PHP_SELF']);

/*
    Détecte si on est dans /admin
*/
$isAdminPage = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$basePath = $isAdminPage ? '../' : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>La Ludothèque</title>
<link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css">

<?php if ($currentPage === 'index.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/accueil.css">
<?php endif; ?>

<?php if ($currentPage === 'evenement.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/evenement.css">
<?php endif; ?>

<?php if ($currentPage === 'ludotheque.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/ludotheque.css">
<?php endif; ?>

<?php if ($currentPage === 'jeu-detail.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/jeu-detail.css">
<?php endif; ?>

<?php if ($currentPage === 'events-detail.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/events-detail.css">
<?php endif; ?>

<?php if ($currentPage === 'demande-emprunt.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/demande-emprunt.css">
<?php endif; ?>

<?php if ($currentPage === 'demande-location.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/demande-location.css">
<?php endif; ?>

<?php if ($currentPage === 'demande-reservation.php'): ?>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/demande-reservation.css">
<?php endif; ?>

</head>
<body>

<nav class="navbar">
<div class="container navbar-content">

<a href="<?= $basePath ?>index.php" class="logo">Ludo<span>thèque</span></a>

<div class="nav-links">

<a href="<?= $basePath ?>index.php"
class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">
Accueil
</a>

<a href="<?= $basePath ?>evenement.php"
class="<?= ($currentPage == 'evenement.php') ? 'active' : '' ?>">
Événements
</a>

<a href="<?= $basePath ?>ludotheque.php"
class="<?= ($currentPage == 'ludotheque.php') ? 'active' : '' ?>">
Ludothèque
</a>

<a href="<?= $basePath ?>apropos.php"
class="<?= ($currentPage == 'apropos.php') ? 'active' : '' ?>">
À propos
</a>

<a href="<?= $basePath ?>contact.php"
class="<?= ($currentPage == 'contact.php') ? 'active' : '' ?>">
Contact
</a>

<?php if (isset($_SESSION["user_id"])): ?>

    <?php if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "president"): ?>
        <a href="<?= $basePath ?>admin/dashboard.php"
        class="<?= in_array($currentPage, ['dashboard.php', 'jeux.php', 'evenements.php', 'demandes.php', 'bureau.php']) ? 'active' : '' ?>">
        Admin
        </a>
    <?php endif; ?>

    <a href="<?= $basePath ?>compte.php"
    class="btn-nav <?= ($currentPage == 'compte.php') ? 'active' : '' ?>">
    Mon Compte
    </a>

<?php else: ?>

    <a href="<?= $basePath ?>auth.php?mode=login"
    class="btn-nav <?= ($currentPage == 'auth.php') ? 'active' : '' ?>">
    Connexion
    </a>

<?php endif; ?>

</div>
</div>
</nav>