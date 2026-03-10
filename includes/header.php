<?php
require_once __DIR__ . "/session.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>La Ludothèque</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
<div class="container navbar-content">

<a href="index.php" class="logo">Ludo<span>thèque</span></a>

<div class="nav-links">

<a href="index.php">Accueil</a>
<a href="evenements.php">Événements</a>
<a href="ludotheque.php">Ludothèque</a>
<a href="a-propos.php">À propos</a>
<a href="contact.php">Contact</a>

<?php if(isset($_SESSION["user_id"])): ?>

<?php if($_SESSION["role"] === "admin" || $_SESSION["role"] === "president"): ?>
<a href="admin/dashboard.php">Admin</a>
<?php endif; ?>

<a href="compte.php" class="btn-nav">Mon Compte</a>

<?php else: ?>

<a href="auth.php?mode=login" class="btn-nav">Connexion</a>

<?php endif; ?>

</div>
</div>
</nav>