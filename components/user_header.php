<?php if(session_status()===PHP_SESSION_NONE)session_start(); $cur=basename($_SERVER['PHP_SELF']); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
html,body{margin:0!important;padding:0!important;overflow-x:hidden!important;max-width:100vw!important;}
*{box-sizing:border-box;}
body{font-family:'Inter',sans-serif;}
.ef-header{width:100%;font-family:'Inter',sans-serif;}
.ef-topbar{background:#061020;color:#9aa3b2;padding:15px 32px;font-size:1.02rem;border-bottom:1px solid rgba(184,147,90,.12);}
.ef-topbar-in{max-width:1340px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;}
.ef-topbar-l,.ef-topbar-r{display:flex;gap:32px;align-items:center;flex-wrap:wrap;}
.ef-topbar span{display:inline-flex;align-items:center;gap:9px;color:#9aa3b2;}
.ef-topbar i{color:#B8935A;font-size:1.05rem;}
.ef-nav{background:#0A1628;padding:20px 28px;border-bottom:1px solid rgba(184,147,90,.18);}
.ef-nav-in{max-width:1340px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:nowrap;}
.ef-logo{display:inline-flex;align-items:center;gap:13px;text-decoration:none!important;color:#fff!important;flex-shrink:0;}
.ef-logo-box{width:56px;height:56px;border:2px solid #B8935A;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;color:#B8935A;font-size:1.6rem;flex-shrink:0;}
.ef-logo-text{display:flex;flex-direction:column;line-height:1;}
.ef-logo-name{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#fff;line-height:1;}
.ef-logo-name span{color:#B8935A;}
.ef-logo-sub{font-size:.72rem;letter-spacing:2.5px;color:#B8935A;margin-top:6px;font-weight:500;line-height:1;}
.ef-menu{list-style:none;display:flex;gap:2px;margin:0;padding:0;flex-wrap:nowrap;}
.ef-menu li{list-style:none;}
.ef-menu a{display:inline-block;padding:10px 14px;color:#cdd5e0!important;text-decoration:none!important;font-size:.88rem;font-weight:600;letter-spacing:1.3px;text-transform:uppercase;transition:.2s;border-bottom:2px solid transparent;border-radius:0!important;white-space:nowrap;line-height:1.2;}
.ef-menu a:hover{color:#B8935A!important;}
.ef-menu a.active{color:#B8935A!important;border-bottom-color:#B8935A;}
.ef-cta{display:flex;align-items:center;gap:10px;flex-wrap:nowrap;flex-shrink:0;}
.ef-toggle{width:52px;height:28px;background:rgba(184,147,90,.25);border-radius:14px;position:relative;cursor:pointer;border:1px solid rgba(184,147,90,.4);flex-shrink:0;}
.ef-toggle::after{content:'\f186';font-family:'Font Awesome 6 Free';font-weight:900;font-size:11px;color:#0A1628;line-height:22px;text-align:center;position:absolute;top:2px;left:2px;width:22px;height:22px;background:#B8935A;border-radius:50%;transition:.25s;}
body.ef-dark .ef-toggle::after{left:26px;background:#fff;content:'\f185';color:#B8935A;}
.ef-btn-outline{padding:10px 20px;border:1.5px solid #B8935A;color:#B8935A!important;border-radius:7px;text-decoration:none!important;font-weight:600;font-size:.88rem;letter-spacing:1.3px;text-transform:uppercase;transition:.2s;white-space:nowrap;line-height:1.2;}
.ef-btn-outline:hover{background:#B8935A;color:#0A1628!important;}
.ef-btn-primary{padding:10px 20px;background:#B8935A;color:#0A1628!important;border-radius:7px;text-decoration:none!important;font-weight:700;font-size:.88rem;letter-spacing:1.3px;text-transform:uppercase;transition:.2s;border:1.5px solid #B8935A;white-space:nowrap;line-height:1.2;}
.ef-btn-primary:hover{background:#c9a961;border-color:#c9a961;}
body.ef-dark{background:#050d18!important;color:#cdd5e0;}
body.ef-dark .ef-nav,body.ef-dark .ef-topbar{background:#040a14;}
/* Dark mode: darken white cards so light text stays readable */
body.ef-dark .property-detail,body.ef-dark .listings,body.ef-dark .services{background:#050d18;}
body.ef-dark .property-info,body.ef-dark .seller-card,body.ef-dark .amenity,
body.ef-dark .prop-card,body.ef-dark .form-container form,body.ef-dark .auth-card,
body.ef-dark .search-card,body.ef-dark .filter-bar,body.ef-dark .action-form,
body.ef-dark .dashboard .box,body.ef-dark .service-card,body.ef-dark .legal-content{
  background:#0b1626;border-color:#22304a;color:#cdd5e0;}
body.ef-dark .property-info h1,body.ef-dark .section-heading,body.ef-dark .prop-title,
body.ef-dark .view-property h3.name,body.ef-dark .service-card h3,
body.ef-dark .prop-seller strong,body.ef-dark .seller-card strong{color:#e8ecf2;}
body.ef-dark .section-heading{border-bottom-color:#22304a;}
body.ef-dark .property-info .prop-price,body.ef-dark .prop-price{color:#c9a961;}
body.ef-dark .amenity{background:#0b1626;}
body.ef-dark .amenity:hover{background:#12203a;}
body.ef-dark input,body.ef-dark select,body.ef-dark textarea{
  background:#0b1626;border-color:#22304a;color:#cdd5e0;}
.ef-menu-toggle{display:none;background:transparent;color:#B8935A;border:1px solid rgba(184,147,90,.3);padding:8px 12px;border-radius:6px;cursor:pointer;font-size:1.1rem;}
@media(max-width:980px){
  .ef-menu-toggle{display:inline-flex;}
  .ef-menu{display:none;width:100%;flex-direction:column;background:#0A1628;padding:14px;border-radius:8px;margin-top:14px;}
  .ef-menu.open{display:flex;}
  .ef-menu a{width:100%;padding:14px;}
  .ef-cta{margin-left:auto;}
}
</style>
<header class="ef-header">
  <div class="ef-topbar">
    <div class="ef-topbar-in">
      <div class="ef-topbar-l">
        <span><i class="fa-solid fa-location-dot"></i> Sydney, Australia</span>
        <span><i class="fa-solid fa-phone"></i> +61 2 9876 5432</span>
      </div>
      <div class="ef-topbar-r">
        <span>Est. 2026</span>
        <span><i class="fa-solid fa-envelope"></i> hello@estateflow.it.com</span>
      </div>
    </div>
  </div>
  <nav class="ef-nav">
    <div class="ef-nav-in">
      <a href="home.php" class="ef-logo">
        <span class="ef-logo-box"><i class="fa-solid fa-gem"></i></span>
        <span class="ef-logo-text">
          <span class="ef-logo-name">Estate<span>Flow</span></span>
          <span class="ef-logo-sub">SYDNEY · SINCE 2026</span>
        </span>
      </a>
      <button class="ef-menu-toggle" onclick="document.getElementById('efMenu').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
      <ul class="ef-menu" id="efMenu">
        <li><a href="home.php" class="<?= $cur=='home.php'?'active':'' ?>">Home</a></li>
        <li><a href="listings.php" class="<?= $cur=='listings.php'?'active':'' ?>">Listings</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
        <li><a href="post_property.php" class="<?= $cur=='post_property.php'?'active':'' ?>">Post Property</a></li>
        <li><a href="dashboard.php" class="<?= $cur=='dashboard.php'?'active':'' ?>">Dashboard</a></li>
        <?php endif; ?>
        <li><a href="about.php" class="<?= $cur=='about.php'?'active':'' ?>">About</a></li>
        <li><a href="contact.php" class="<?= $cur=='contact.php'?'active':'' ?>">Contact</a></li>
      </ul>
      <div class="ef-cta">
        <div class="ef-toggle" title="Switch light / dark mode" onclick="document.body.classList.toggle('ef-dark');localStorage.setItem('efDark',document.body.classList.contains('ef-dark')?'1':'0');"></div>
        <?php if(isset($_SESSION['user_id'])): ?>
          <a href="logout.php" class="ef-btn-outline">Logout</a>
        <?php else: ?>
          <a href="login.php" class="ef-btn-outline">Sign In</a>
          <a href="register.php" class="ef-btn-primary">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>
<script>if(localStorage.getItem('efDark')==='1')document.body.classList.add('ef-dark');</script>
