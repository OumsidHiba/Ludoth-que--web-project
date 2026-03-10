<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ludothèque — Catan</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.html" class="navbar-logo">Ludo<span>thèque</span></a>
    <button class="navbar-toggle" onclick="document.querySelector('.navbar-links').classList.toggle('open')"><span></span><span></span><span></span></button>
    <div class="navbar-links">
      <a href="index.html">Accueil</a>
      <a href="evenements.html">Événements</a>
      <a href="ludotheque.html" class="active">Ludothèque</a>
      <a href="apropos.html">À propos</a>
      <a href="contact.html">Contact</a>
      <a href="connexion.html" class="cta">Connexion</a>
    </div>
  </nav>

  <section class="section">
    <a href="ludotheque.html" style="font-size:13px;color:var(--teal);font-weight:600;display:inline-block;margin-bottom:20px">← Retour au catalogue</a>
    <div class="detail-grid">
      <div class="detail-img">
        <div class="detail-img-main">🏝️</div>
        <div class="detail-thumbs">
          <div class="detail-thumb" style="background:linear-gradient(135deg,#6BBBBB,#4A90C4)"></div>
          <div class="detail-thumb" style="background:linear-gradient(135deg,#E8963C,#D07828)"></div>
          <div class="detail-thumb" style="background:linear-gradient(135deg,#007A78,#005F5D)"></div>
        </div>
      </div>
      <div class="detail-info">
        <div>
          <h1 style="font-size:28px;font-weight:800;color:var(--navy);margin-bottom:8px">Catan</h1>
          <span class="badge badge-green" style="font-size:13px;padding:6px 16px">En stock</span>
        </div>
        <p style="color:var(--text-light);line-height:1.7">Jeu de stratégie et de commerce sur une île. Collectez des ressources, construisez des routes et des colonies, et échangez avec les autres joueurs pour dominer l'île de Catan !</p>
        <div class="detail-row"><span class="detail-label">Nombre de joueurs</span><span class="detail-value">3 – 4</span></div>
        <div class="detail-row"><span class="detail-label">Temps de jeu moyen</span><span class="detail-value">60 minutes</span></div>
        <div class="detail-row"><span class="detail-label">Difficulté d'apprentissage</span><span class="detail-value">★★☆ Moyenne</span></div>
        <div class="detail-row"><span class="detail-label">Difficulté de jeu</span><span class="detail-value">★★☆ Moyenne</span></div>
        <div class="detail-row"><span class="detail-label">Règles</span><span class="detail-value"><a href="#" style="color:var(--teal);text-decoration:underline">Consulter les règles ↗</a></span></div>

        <div class="detail-actions">
          <button class="btn btn-primary">Emprunter</button>
          <button class="btn btn-secondary">Louer</button>
          <button class="btn btn-outline">Réserver (Jeu du jeudi)</button>
        </div>
        <p style="font-size:12px;color:var(--text-light);font-style:italic;margin-top:4px">
          💡 Emprunter = membres uniquement · Louer = non-membres · Réserver = tous les connectés
        </p>

        <!-- Message si non connecté (à afficher dynamiquement avec PHP) -->
        <div style="margin-top:12px;padding:14px;background:#FFF9E6;border:1px solid #F5E6A8;border-radius:8px;display:none">
          <p style="font-size:13px;color:#8A6A20;font-weight:600">🔒 Connectez-vous pour emprunter, louer ou réserver ce jeu.</p>
          <a href="connexion.html" class="btn btn-primary btn-sm" style="margin-top:8px">Se connecter</a>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div>© 2026 Ludothèque — Association étudiante ECE Paris</div>
    <div class="footer-links"><a href="apropos.html">À propos</a><a href="contact.html">Contact</a></div>
  </footer>
</body>
</html>
