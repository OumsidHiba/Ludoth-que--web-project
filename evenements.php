<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ludothèque — Événements</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.html" class="navbar-logo">Ludo<span>thèque</span></a>
    <button class="navbar-toggle" onclick="document.querySelector('.navbar-links').classList.toggle('open')"><span></span><span></span><span></span></button>
    <div class="navbar-links">
      <a href="index.html">Accueil</a>
      <a href="evenements.html" class="active">Événements</a>
      <a href="ludotheque.html">Ludothèque</a>
      <a href="apropos.html">À propos</a>
      <a href="contact.html">Contact</a>
      <a href="connexion.html" class="cta">Connexion</a>
    </div>
  </nav>

  <section class="section">
    <div class="section-divider"></div>
    <h2 class="section-title">Tous les événements</h2>
    <p class="section-subtitle">Découvrez les événements passés et à venir organisés par l'association</p>

    <div class="filters">
      <select class="filter-select">
        <option>Type : Tous</option>
        <option>Salle du jeudi</option>
        <option>Jeu du jeudi</option>
        <option>Soirée jeux</option>
        <option>Événement occasionnel</option>
      </select>
      <select class="filter-select">
        <option>Date : Plus récent</option>
        <option>Date : Plus ancien</option>
      </select>
      <button class="btn btn-primary btn-sm">À venir</button>
      <button class="btn btn-outline btn-sm">Passés</button>
    </div>

    <div class="grid grid-3">
      <div class="card">
        <div class="card-img-placeholder">🎲</div>
        <div class="card-body">
          <span class="badge badge-blue">Jeu du jeudi</span>
          <h3 class="card-title" style="margin-top:8px">Tournoi de Catan</h3>
          <p class="card-meta">📅 Jeudi 20 mars 2026 · 18h00</p>
          <p class="card-meta">📍 Salle A3</p>
          <p class="card-meta" style="margin-top:8px">Tournoi amical ouvert à tous les étudiants.</p>
          <a href="evenement-detail.html" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
        </div>
      </div>
      <div class="card">
        <div class="card-img-placeholder">🌙</div>
        <div class="card-body">
          <span class="badge badge-green">Soirée jeux</span>
          <h3 class="card-title" style="margin-top:8px">Soirée Loup-Garou</h3>
          <p class="card-meta">📅 Vendredi 21 mars 2026 · 20h00</p>
          <p class="card-meta">📍 Hall principal</p>
          <p class="card-meta" style="margin-top:8px">Grande soirée avec ambiance garantie !</p>
          <a href="#" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
        </div>
      </div>
      <div class="card">
        <div class="card-img-placeholder">⭐</div>
        <div class="card-body">
          <span class="badge badge-purple">Occasionnel</span>
          <h3 class="card-title" style="margin-top:8px">Escape Game géant</h3>
          <p class="card-meta">📅 Samedi 22 mars 2026 · 14h00</p>
          <p class="card-meta">📍 Amphithéâtre</p>
          <p class="card-meta" style="margin-top:8px">Escape Game grandeur nature sur le campus.</p>
          <a href="#" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
        </div>
      </div>
      <div class="card">
        <div class="card-img-placeholder">🏠</div>
        <div class="card-body">
          <span class="badge badge-teal">Salle du jeudi</span>
          <h3 class="card-title" style="margin-top:8px">Salle ouverte</h3>
          <p class="card-meta">📅 Jeudi 13 mars 2026 · 12h00</p>
          <p class="card-meta">📍 Salle B12</p>
          <p class="card-meta" style="margin-top:8px">Venez jouer librement pendant la pause.</p>
          <a href="#" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
        </div>
      </div>
      <div class="card">
        <div class="card-img-placeholder">🎲</div>
        <div class="card-body">
          <span class="badge badge-blue">Jeu du jeudi</span>
          <h3 class="card-title" style="margin-top:8px">Découverte : Azul</h3>
          <p class="card-meta">📅 Jeudi 27 mars 2026 · 18h00</p>
          <p class="card-meta">📍 Salle A3</p>
          <p class="card-meta" style="margin-top:8px">Venez découvrir ce jeu primé !</p>
          <a href="#" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
        </div>
      </div>
      <div class="card">
        <div class="card-img-placeholder">🌙</div>
        <div class="card-body">
          <span class="badge badge-green">Soirée jeux</span>
          <h3 class="card-title" style="margin-top:8px">Nuit du jeu</h3>
          <p class="card-meta">📅 Vendredi 4 avril 2026 · 20h00</p>
          <p class="card-meta">📍 Hall principal</p>
          <p class="card-meta" style="margin-top:8px">Marathon gaming toute la nuit !</p>
          <a href="#" class="btn btn-outline btn-sm" style="margin-top:12px">Voir détails →</a>
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
