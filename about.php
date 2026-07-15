<?php
include 'components/connect.php';
$user_id = ef_user_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">SINCE 2026 &middot; AUSTRALIA</span>
      <h1>About <em>EstateFlow</em></h1>
      <p>A curated luxury real estate platform built for Australia's most discerning buyers, sellers and tenants.</p>
   </div>
</section>

<section class="listings">
   <div class="container about-grid">

      <div class="about-text">
         <h2 class="section-heading">Our Story</h2>
         <p>Founded in 2026, EstateFlow brings together Australia's finest homes &mdash; from harbour-front residences in Sydney's Mosman and Bondi to heritage homes in Melbourne's Toorak and riverside apartments in Brisbane. We believe property should be discovered, not just listed.</p>

         <h2 class="section-heading" style="margin-top:2rem;">What We Do</h2>
         <ul class="feature-list">
            <li><i class="fas fa-gem"></i> <strong>Curated Listings</strong> &mdash; every property is vetted before going live.</li>
            <li><i class="fas fa-handshake"></i> <strong>Direct Connections</strong> &mdash; buyers and sellers talk directly, no middlemen.</li>
            <li><i class="fas fa-shield-alt"></i> <strong>Verified Profiles</strong> &mdash; sellers and agents are authenticated.</li>
            <li><i class="fas fa-dollar-sign"></i> <strong>Transparent Pricing</strong> &mdash; no hidden fees, no commission on enquiries.</li>
         </ul>

         <h2 class="section-heading" style="margin-top:2rem;">By the Numbers</h2>
         <div class="stat-row">
            <div class="stat-card"><div class="stat-num">500+</div><div>Verified listings</div></div>
            <div class="stat-card"><div class="stat-num">1,200+</div><div>Happy clients</div></div>
            <div class="stat-card"><div class="stat-num">24</div><div>Australian suburbs</div></div>
            <div class="stat-card"><div class="stat-num">2026</div><div>Since</div></div>
         </div>
      </div>

   </div>
</section>

<section class="cta-band">
   <div class="container cta-inner">
      <div><h2>Ready to find your next home?</h2><p>Start exploring Australia's most sought-after addresses.</p></div>
      <a href="listings.php" class="btn-accent lg"><i class="fas fa-search"></i>&nbsp; Browse Listings</a>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
</body>
</html>
