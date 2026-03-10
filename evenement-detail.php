<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ludothèque — Tournoi de Catan</title>
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
    <a href="evenements.html" style="font-size:13px;color:var(--teal);font-weight:600;display:inline-block;margin-bottom:20px">← Retour aux événements</a>
    <div class="detail-grid">
      <div>
        <div class="detail-img-main" style="height:320px">🎲</div>
      </div>
      <div class="detail-info">
        <div>
          <span class="badge badge-blue" style="margin-bottom:8px;display:inline-block">Jeu du jeudi</span>
          <h1 style="font-size:28px;font-weight:800;color:var(--navy)">Tournoi de Catan</h1>
        </div>
        <div class="detail-row"><span class="detail-label">📅 Date</span><span class="detail-value">Jeudi 20 mars 2026</span></div>
        <div class="detail-row"><span class="detail-label">🕐 Heure</span><span class="detail-value">18h00 – 21h00</span></div>
        <div class="detail-row"><span class="detail-label">📍 Lieu</span><span class="detail-value">Salle A3, Campus ECE Paris</span></div>
        <div class="detail-row"><span class="detail-label">👥 Participation</span><span class="detail-value">Ouverte à tous les étudiants</span></div>
        <div style="margin-top:16px">
          <h3 style="font-size:16px;font-weight:700;color:var(--navy);margin-bottom:8px">Description</h3>
          <p style="color:var(--text-light);line-height:1.8">
            Venez participer à notre tournoi amical de Catan ! Que vous soyez un joueur expérimenté ou un débutant, cette soirée est l'occasion parfaite de vous affronter dans une ambiance conviviale. Les règles seront expliquées en début de soirée pour les novices.
          </p>
          <p style="color:var(--text-light);line-height:1.8;margin-top:12px">
            Inscriptions sur place. Des collations et boissons seront disponibles. Le gagnant repartira avec un badge d'honneur et le respect éternel de la communauté !
          </p>
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
