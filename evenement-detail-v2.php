<?php include "includes/header.php"; ?>
<main>
  <section class="section">
    <a href="evenement.php" style="font-size:13px;color:var(--teal);font-weight:600;display:inline-block;margin-bottom:20px">
      ← Retour aux événements
    </a>

    <div class="detail-grid">
      <div>
        <div class="detail-img-main" id="event-emoji"></div>
      </div>

      <div class="detail-info">
        <div>
          <span id="event-type" class="badge" style="margin-bottom:8px;display:inline-block"></span>
          <h1 id="event-title" style="font-size:28px;font-weight:800;color:var(--navy)"></h1>
        </div>

        <div class="detail-row">
          <span class="detail-label">📅 Date</span>
          <span class="detail-value" id="event-date"></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">🕐 Heure</span>
          <span class="detail-value" id="event-time"></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">📍 Lieu</span>
          <span class="detail-value" id="event-location"></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">👥 Participation</span>
          <span class="detail-value" id="event-participation"></span>
        </div>

        <div style="margin-top:16px">
          <h3 style="font-size:16px;font-weight:700;color:var(--navy);margin-bottom:8px">Description</h3>
          <div id="event-description"></div>
        </div>
      </div>
    </div>
  </section>
</main>

<script src="assets/js/evenement-detail.js"></script>
<?php include "includes/footer.php"; ?>