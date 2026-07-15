<!-- ===== EstateFlow luxury footer ===== -->
<style>
.ef-footer{background:#0A1628;color:#cdd5e0;padding:60px 24px 0;font-family:'Inter',sans-serif;border-top:3px solid #B8935A;}
.ef-footer *{box-sizing:border-box;}
.ef-footer-grid{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:50px;padding-bottom:40px;}
.ef-footer .ef-col h4{font-family:'Playfair Display',serif;color:#fff;font-size:1.05rem;font-weight:600;margin:0 0 18px;letter-spacing:.5px;position:relative;padding-bottom:10px;}
.ef-footer .ef-col h4::after{content:'';position:absolute;left:0;bottom:0;width:32px;height:2px;background:#B8935A;}
.ef-footer .ef-logo{display:inline-flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.55rem;font-weight:700;color:#fff!important;text-decoration:none!important;margin-bottom:14px;}
.ef-footer .ef-logo i{color:#B8935A;}
.ef-footer .ef-tag{color:#9aa3b2;font-size:.9rem;line-height:1.65;margin-bottom:20px;}
.ef-footer .ef-col a,.ef-footer .ef-col span{display:flex;align-items:center;gap:9px;color:#9aa3b2!important;text-decoration:none!important;padding:7px 0;font-size:.9rem;transition:color .2s,padding-left .2s;border:none!important;}
.ef-footer .ef-col a:hover{color:#B8935A!important;padding-left:5px;}
.ef-footer .ef-col a i,.ef-footer .ef-col span i{color:#B8935A;width:14px;font-size:.85rem;}
.ef-footer .ef-socials{display:flex;gap:10px;margin-top:8px;}
.ef-footer .ef-socials a{width:38px;height:38px;background:rgba(184,147,90,.12);color:#B8935A!important;border-radius:50%;display:inline-flex!important;align-items:center;justify-content:center;padding:0!important;text-decoration:none!important;transition:.2s;}
.ef-footer .ef-socials a:hover{background:#B8935A;color:#0A1628!important;transform:translateY(-3px);}
.ef-footer .ef-credit{max-width:1200px;margin:0 auto;border-top:1px solid rgba(184,147,90,.18);padding:22px 0;text-align:center;color:#7a8190;font-size:.82rem;letter-spacing:.5px;}
.ef-footer .ef-credit strong{color:#B8935A;font-weight:600;}
@media(max-width:900px){.ef-footer-grid{grid-template-columns:1fr 1fr;gap:36px;}}
@media(max-width:560px){.ef-footer-grid{grid-template-columns:1fr;}.ef-footer{padding:40px 18px 0;}}
</style>
<footer class="ef-footer">
   <div class="ef-footer-grid">
      <div class="ef-col">
         <a href="home.php" class="ef-logo"><i class="fas fa-gem"></i> EstateFlow</a>
         <p class="ef-tag">Luxury real estate across Australia &mdash; curated homes, estates and rentals in the country's most desirable neighbourhoods, from Sydney's harbour to Melbourne's leafy lanes.</p>
         <div class="ef-socials">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
         </div>
      </div>
      <div class="ef-col">
         <h4>Explore</h4>
         <a href="home.php">Home</a>
         <a href="listings.php">All Listings</a>
         <a href="search.php">Advanced Search</a>
         <a href="saved.php">Saved Properties</a>
         <a href="post_property.php">Post a Property</a>
      </div>
      <div class="ef-col">
         <h4>Company</h4>
         <a href="about.php">About Us</a>
         <a href="contact.php">Contact</a>
         <a href="contact.php#faq">FAQ</a>
         <a href="terms.php">Terms of Service</a>
         <a href="privacy.php">Privacy Policy</a>
      </div>
      <div class="ef-col">
         <h4>Contact</h4>
         <a href="tel:+61298765432"><i class="fas fa-phone"></i> +61 2 9876 5432</a>
         <a href="mailto:hello@estateflow.it.com"><i class="fas fa-envelope"></i> hello@estateflow.it.com</a>
         <span><i class="fas fa-map-marker-alt"></i> Level 12, 1 Martin Place, Sydney NSW 2000</span>
         <span><i class="fas fa-clock"></i> Mon&ndash;Sat, 9:00&ndash;19:00</span>
      </div>
   </div>
   <div class="ef-credit">
      &copy; <?= date('Y'); ?> <strong>EstateFlow</strong> &middot; Est. 2026 &middot; All rights reserved
   </div>
</footer>
<?php @include __DIR__ . '/cookie_banner.php'; ?>
