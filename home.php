<?php
include 'components/connect.php';
$user_id = ef_user_id();
include 'components/save_send.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>EstateFlow &mdash; Luxury Real Estate in Australia</title>
   <meta name="description" content="EstateFlow: Australia's most curated luxury real estate platform. Sydney, Melbourne, Brisbane.">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap">
   <link rel="stylesheet" href="css/style.css">
<style>
/* ============ CRAZY UNIQUE LUXURY HOME ============ */
*{box-sizing:border-box;}
body{font-family:'Inter',sans-serif;margin:0;background:#0a1628;color:#fff;overflow-x:hidden;}

/* ===== HERO ===== */
.ef-hero{position:relative;min-height:92vh;display:flex;align-items:center;justify-content:center;
  overflow:hidden;background:#0a1628;}
.ef-hero-bg{position:absolute;inset:0;background:
   linear-gradient(135deg,rgba(10,22,40,.85) 0%,rgba(10,22,40,.6) 50%,rgba(10,22,40,.92) 100%),
   url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1920') center/cover;
   z-index:1;}
.ef-hero-bg::after{content:'';position:absolute;inset:0;background:
   radial-gradient(circle at 20% 30%,rgba(184,147,90,.15),transparent 50%),
   radial-gradient(circle at 80% 70%,rgba(184,147,90,.1),transparent 50%);}
.ef-orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.5;z-index:2;animation:float 8s ease-in-out infinite;}
.ef-orb.o1{width:400px;height:400px;background:#B8935A;top:-100px;left:-100px;}
.ef-orb.o2{width:500px;height:500px;background:#1a3a5c;bottom:-150px;right:-150px;animation-delay:-4s;}
@keyframes float{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-30px) scale(1.05);}}

.ef-hero-in{position:relative;z-index:5;max-width:1300px;width:100%;padding:60px 30px;text-align:center;}
.ef-eyebrow-pill{display:inline-flex;align-items:center;gap:10px;padding:10px 22px;
  background:rgba(184,147,90,.1);border:1px solid rgba(184,147,90,.35);border-radius:50px;
  color:#B8935A;font-size:.78rem;letter-spacing:4px;font-weight:600;text-transform:uppercase;
  margin-bottom:30px;backdrop-filter:blur(10px);animation:fadeUp .8s .1s both;}
.ef-eyebrow-pill .dot{width:8px;height:8px;background:#B8935A;border-radius:50%;
  box-shadow:0 0 12px #B8935A;animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}
@keyframes fadeUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}

.ef-hero h1{font-family:'Playfair Display',serif;font-size:clamp(2.5rem,6vw,5.5rem);
  font-weight:700;line-height:1.05;margin:0 0 24px;letter-spacing:-2px;color:#fff;
  animation:fadeUp .9s .25s both;}
.ef-hero h1 em{font-style:italic;background:linear-gradient(135deg,#B8935A 0%,#e8c98a 50%,#B8935A 100%);
  -webkit-background-clip:text;background-clip:text;color:transparent;font-weight:600;}
.ef-hero-sub{font-size:1.2rem;color:#cdd5e0;max-width:680px;margin:0 auto 44px;line-height:1.6;
  animation:fadeUp 1s .4s both;}

.ef-search{background:rgba(255,255,255,.07);backdrop-filter:blur(20px);
  border:1px solid rgba(184,147,90,.25);border-radius:24px;padding:18px;
  max-width:1150px;margin:0 auto;box-shadow:0 30px 80px rgba(0,0,0,.4);animation:fadeUp 1.1s .55s both;}
.ef-search-row{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr 1fr auto;gap:6px;align-items:end;}
.ef-fld{padding:10px 16px;border-radius:14px;transition:.2s;}
.ef-fld:hover{background:rgba(184,147,90,.08);}
.ef-fld label{display:block;font-size:.68rem;letter-spacing:2.5px;color:#B8935A;
  text-transform:uppercase;font-weight:700;margin-bottom:6px;}
.ef-fld input,.ef-fld select{width:100%;background:transparent;border:none;color:#fff;
  font-size:.98rem;font-family:inherit;outline:none;font-weight:500;padding:2px 0;}
.ef-fld select option{background:#0a1628;color:#fff;}
.ef-fld input::placeholder{color:rgba(255,255,255,.45);}
.ef-search-btn{padding:16px 34px;background:linear-gradient(135deg,#B8935A,#d4af71);
  color:#0a1628;border:none;border-radius:16px;font-weight:800;font-size:.95rem;
  letter-spacing:1.5px;text-transform:uppercase;cursor:pointer;
  display:inline-flex;align-items:center;gap:10px;
  box-shadow:0 10px 30px rgba(184,147,90,.4);transition:.25s;}
.ef-search-btn:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(184,147,90,.55);}
@media(max-width:980px){.ef-search-row{grid-template-columns:1fr 1fr;}.ef-search-btn{grid-column:1/-1;justify-content:center;}}

.ef-scroll-cue{position:absolute;bottom:30px;left:50%;transform:translateX(-50%);z-index:10;
  color:#B8935A;font-size:.7rem;letter-spacing:3px;text-transform:uppercase;
  display:flex;flex-direction:column;align-items:center;gap:8px;font-weight:600;}
.ef-scroll-cue .line{width:1px;height:40px;background:linear-gradient(180deg,#B8935A,transparent);animation:scroll 2s infinite;}
@keyframes scroll{0%{transform:scaleY(0);transform-origin:top;}50%{transform:scaleY(1);transform-origin:top;}51%{transform-origin:bottom;}100%{transform:scaleY(0);transform-origin:bottom;}}

/* ===== STATS ===== */
.ef-stats-band{background:linear-gradient(180deg,#0a1628 0%,#0d1c34 100%);padding:80px 30px;position:relative;}
.ef-stats-band::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
  width:60%;height:1px;background:linear-gradient(90deg,transparent,#B8935A,transparent);}
.ef-stats-grid{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:30px;text-align:center;}
@media(max-width:780px){.ef-stats-grid{grid-template-columns:repeat(2,1fr);}}
.ef-stat-item .n{font-family:'Playfair Display',serif;font-size:3.5rem;font-weight:700;
  background:linear-gradient(135deg,#B8935A,#e8c98a);-webkit-background-clip:text;
  background-clip:text;color:transparent;line-height:1;letter-spacing:-2px;}
.ef-stat-item .l{color:#9aa3b2;font-size:.78rem;letter-spacing:3px;text-transform:uppercase;
  margin-top:10px;font-weight:600;}

/* ===== NEIGHBOURHOODS ===== */
.ef-section{padding:100px 30px;max-width:1340px;margin:0 auto;}
.ef-section-head{text-align:center;margin-bottom:60px;}
.ef-section-head .ey{display:inline-block;font-size:.75rem;letter-spacing:5px;color:#B8935A;
  font-weight:700;padding:6px 18px;border:1px solid rgba(184,147,90,.35);border-radius:30px;
  text-transform:uppercase;margin-bottom:18px;}
.ef-section-head h2{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3.4rem);
  font-weight:700;color:#fff;line-height:1.15;letter-spacing:-1px;margin:0 0 16px;}
.ef-section-head h2 em{font-style:italic;color:#B8935A;}
.ef-section-head p{color:#9aa3b2;max-width:600px;margin:0 auto;font-size:1.05rem;line-height:1.7;}

.ef-hood-grid{display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:280px 280px;gap:18px;}
@media(max-width:900px){.ef-hood-grid{grid-template-columns:1fr;grid-template-rows:auto;}}
.ef-hood{position:relative;border-radius:20px;overflow:hidden;cursor:pointer;
  background-size:cover;background-position:center;transition:.4s;min-height:280px;}
.ef-hood::before{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,22,40,.95));z-index:1;}
.ef-hood:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.5);}
.ef-hood-info{position:absolute;bottom:0;left:0;right:0;padding:24px;z-index:2;}
.ef-hood-info h3{font-family:'Playfair Display',serif;font-size:1.6rem;color:#fff;margin:0 0 6px;}
.ef-hood-info .meta{color:#B8935A;font-size:.75rem;letter-spacing:2px;text-transform:uppercase;font-weight:600;}
.ef-hood.big{grid-row:span 2;}
.ef-hood.h1{background-image:linear-gradient(180deg,transparent,rgba(10,22,40,.4)),url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=900');}
.ef-hood.h2{background-image:url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600');}
.ef-hood.h3{background-image:url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600');}
.ef-hood.h4{background-image:url('https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600');}
.ef-hood.h5{background-image:url('https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600');}

/* ===== SERVICES ===== */
.ef-services-band{background:linear-gradient(180deg,#0d1c34,#0a1628);padding:100px 30px;}
.ef-svc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1300px;margin:0 auto;}
@media(max-width:900px){.ef-svc-grid{grid-template-columns:1fr;}}
.ef-svc{background:rgba(255,255,255,.03);border:1px solid rgba(184,147,90,.15);
  border-radius:20px;padding:38px 30px;transition:.35s;position:relative;overflow:hidden;}
.ef-svc::before{content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(184,147,90,.12),transparent);opacity:0;transition:.35s;}
.ef-svc:hover{transform:translateY(-8px);border-color:#B8935A;box-shadow:0 20px 50px rgba(184,147,90,.18);}
.ef-svc:hover::before{opacity:1;}
.ef-svc > *{position:relative;z-index:1;}
.ef-svc-ic{width:64px;height:64px;border-radius:18px;
  background:linear-gradient(135deg,#B8935A,#d4af71);color:#0a1628;
  display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:20px;
  box-shadow:0 8px 24px rgba(184,147,90,.4);}
.ef-svc h3{font-family:'Playfair Display',serif;font-size:1.45rem;color:#fff;margin:0 0 12px;font-weight:600;}
.ef-svc p{color:#9aa3b2;font-size:.95rem;line-height:1.7;}

/* ===== LISTINGS ===== */
.ef-listings-band{background:#0a1628;padding:100px 30px;}
.ef-prop-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:26px;max-width:1340px;margin:0 auto;}
.ef-prop{background:#fff;border-radius:20px;overflow:hidden;color:#0a1628;
  box-shadow:0 10px 40px rgba(0,0,0,.3);transition:.35s;border:none;}
.ef-prop:hover{transform:translateY(-10px);box-shadow:0 25px 60px rgba(184,147,90,.25);}
.ef-prop-media{position:relative;height:240px;overflow:hidden;}
.ef-prop-media img{width:100%;height:100%;object-fit:cover;transition:.6s;}
.ef-prop:hover .ef-prop-media img{transform:scale(1.08);}
.ef-prop-badge{position:absolute;top:16px;left:16px;background:linear-gradient(135deg,#B8935A,#d4af71);
  color:#0a1628;padding:7px 16px;border-radius:30px;font-size:.72rem;font-weight:700;
  letter-spacing:1.5px;text-transform:uppercase;box-shadow:0 4px 12px rgba(0,0,0,.2);}
.ef-prop-save{position:absolute;top:16px;right:16px;width:42px;height:42px;
  background:rgba(255,255,255,.95);border:none;border-radius:50%;cursor:pointer;
  color:#0a1628;font-size:1.05rem;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:.2s;}
.ef-prop-save:hover{background:#B8935A;color:#fff;transform:scale(1.1);}
.ef-prop-save i.fas{color:#e74c3c;}
.ef-prop-body{padding:24px;}
.ef-prop-price{font-family:'Playfair Display',serif;font-size:1.7rem;color:#0a1628;font-weight:700;line-height:1;}
.ef-prop-price small{font-size:.78rem;color:#B8935A;letter-spacing:2px;text-transform:uppercase;font-weight:600;margin-left:6px;}
.ef-prop-name{font-family:'Playfair Display',serif;font-size:1.25rem;color:#0a1628;margin:10px 0 6px;font-weight:600;}
.ef-prop-loc{color:#7a8290;font-size:.88rem;margin-bottom:16px;display:flex;align-items:center;gap:6px;}
.ef-prop-loc i{color:#B8935A;}
.ef-prop-meta{display:flex;gap:14px;padding:14px 0;border-top:1px solid #f1ede3;border-bottom:1px solid #f1ede3;
  margin-bottom:16px;color:#5a6573;font-size:.82rem;flex-wrap:wrap;}
.ef-prop-meta span{display:inline-flex;align-items:center;gap:6px;}
.ef-prop-meta i{color:#B8935A;}
.ef-prop-actions{display:flex;gap:10px;}
.ef-prop-btn-outline{flex:1;padding:12px;border:1.5px solid #0a1628;color:#0a1628;text-decoration:none;
  text-align:center;border-radius:10px;font-weight:600;font-size:.88rem;transition:.2s;background:#fff;cursor:pointer;}
.ef-prop-btn-outline:hover{background:#0a1628;color:#fff;}
.ef-prop-btn-primary{flex:1;padding:12px;background:linear-gradient(135deg,#B8935A,#d4af71);
  color:#0a1628;border:none;border-radius:10px;font-weight:700;font-size:.88rem;cursor:pointer;transition:.2s;}
.ef-prop-btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(184,147,90,.4);}
.ef-view-all{text-align:center;margin-top:50px;}
.ef-view-all a{display:inline-flex;align-items:center;gap:12px;padding:16px 36px;
  border:1.5px solid #B8935A;color:#B8935A;text-decoration:none;border-radius:50px;
  font-weight:700;letter-spacing:1.5px;text-transform:uppercase;font-size:.85rem;transition:.25s;}
.ef-view-all a:hover{background:#B8935A;color:#0a1628;box-shadow:0 10px 30px rgba(184,147,90,.4);}

/* ===== TESTIMONIAL ===== */
.ef-testi{background:linear-gradient(180deg,#0a1628,#0d1c34);padding:100px 30px;position:relative;}
.ef-testi-box{max-width:900px;margin:0 auto;text-align:center;position:relative;}
.ef-testi-box .quote-mark{font-family:'Playfair Display',serif;font-size:8rem;color:#B8935A;
  line-height:.5;opacity:.3;}
.ef-testi-box blockquote{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;
  font-style:italic;line-height:1.5;margin:0 0 30px;font-weight:400;}
.ef-testi-box .author{color:#B8935A;font-size:.85rem;letter-spacing:3px;text-transform:uppercase;font-weight:600;}
.ef-testi-box .author strong{display:block;color:#fff;font-family:'Playfair Display',serif;font-size:1.15rem;
  letter-spacing:0;text-transform:none;margin-bottom:4px;font-weight:600;font-style:italic;}

/* ===== CTA ===== */
.ef-cta{padding:80px 30px;}
.ef-cta-box{max-width:1200px;margin:0 auto;
  background:linear-gradient(135deg,#B8935A 0%,#d4af71 50%,#B8935A 100%);
  border-radius:30px;padding:60px;display:grid;grid-template-columns:1.5fr auto;gap:30px;align-items:center;
  box-shadow:0 30px 80px rgba(184,147,90,.35);position:relative;overflow:hidden;}
.ef-cta-box::before{content:'\f3a5';font-family:'Font Awesome 6 Free';font-weight:900;
  position:absolute;right:-30px;bottom:-80px;font-size:18rem;color:rgba(10,22,40,.08);}
@media(max-width:800px){.ef-cta-box{grid-template-columns:1fr;text-align:center;padding:40px 28px;}}
.ef-cta-box h2{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3.5vw,2.6rem);
  color:#0a1628;margin:0 0 10px;font-weight:700;letter-spacing:-.5px;}
.ef-cta-box p{color:rgba(10,22,40,.75);font-size:1.05rem;margin:0;}
.ef-cta-btn{padding:18px 36px;background:#0a1628;color:#B8935A;border:none;border-radius:50px;
  font-weight:700;font-size:.95rem;letter-spacing:2px;text-transform:uppercase;
  text-decoration:none;display:inline-flex;align-items:center;gap:12px;
  box-shadow:0 10px 30px rgba(10,22,40,.3);transition:.25s;white-space:nowrap;}
.ef-cta-btn:hover{transform:translateY(-3px);box-shadow:0 14px 40px rgba(10,22,40,.45);}

.ef-empty{text-align:center;padding:60px;color:#9aa3b2;grid-column:1/-1;}
.ef-empty a{color:#B8935A;font-weight:600;}
</style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- HERO -->
<section class="ef-hero">
   <div class="ef-hero-bg"></div>
   <div class="ef-orb o1"></div>
   <div class="ef-orb o2"></div>
   <div class="ef-hero-in">
      <span class="ef-eyebrow-pill"><span class="dot"></span>Luxury Real Estate · Est. 2026 · Australia</span>
      <h1>Discover <em>Extraordinary</em><br>Homes &amp; Estates</h1>
      <p class="ef-hero-sub">A curated marketplace connecting discerning buyers, renters and independent brokers across Australia's finest postcodes — from Sydney's harbour to Melbourne's leafy lanes.</p>

      <form action="search.php" method="post" class="ef-search">
         <div class="ef-search-row">
            <div class="ef-fld">
               <label>Location</label>
               <input type="text" name="h_location" required placeholder="Bondi, Mosman, Sydney…">
            </div>
            <div class="ef-fld">
               <label>Type</label>
               <select name="h_type" required>
                  <option value="house">House</option>
                  <option value="flat">Apartment</option>
                  <option value="shop">Commercial</option>
               </select>
            </div>
            <div class="ef-fld">
               <label>Offer</label>
               <select name="h_offer" required>
                  <option value="sale">For Sale</option>
                  <option value="rent">For Rent</option>
                  <option value="resale">Resale</option>
               </select>
            </div>
            <div class="ef-fld">
               <label>Min Budget</label>
               <select name="h_min" required>
                  <option value="0">No min</option>
                  <option value="500000">A$500k</option>
                  <option value="1000000">A$1M</option>
                  <option value="2000000">A$2M</option>
                  <option value="5000000">A$5M</option>
               </select>
            </div>
            <div class="ef-fld">
               <label>Max Budget</label>
               <select name="h_max" required>
                  <option value="1000000">A$1M</option>
                  <option value="2500000">A$2.5M</option>
                  <option value="5000000">A$5M</option>
                  <option value="10000000">A$10M</option>
                  <option value="50000000">A$50M+</option>
               </select>
            </div>
            <button type="submit" name="h_search" class="ef-search-btn">
               <i class="fas fa-search"></i> Search
            </button>
         </div>
      </form>
   </div>
   <div class="ef-scroll-cue">Scroll<div class="line"></div></div>
</section>

<!-- STATS -->
<section class="ef-stats-band">
   <div class="ef-stats-grid">
      <div class="ef-stat-item"><div class="n">1,200<span style="color:#B8935A;">+</span></div><div class="l">Active Listings</div></div>
      <div class="ef-stat-item"><div class="n">A$850<span style="color:#B8935A;">M</span></div><div class="l">Property Sold</div></div>
      <div class="ef-stat-item"><div class="n">40<span style="color:#B8935A;">+</span></div><div class="l">Expert Brokers</div></div>
      <div class="ef-stat-item"><div class="n">98<span style="color:#B8935A;">%</span></div><div class="l">Client Satisfaction</div></div>
   </div>
</section>

<!-- NEIGHBOURHOODS -->
<section class="ef-section">
   <div class="ef-section-head">
      <span class="ey">Premium Locations</span>
      <h2>Australia's most <em>desirable</em> neighbourhoods</h2>
      <p>From harbour-view sanctuaries to leafy heritage streets — explore the postcodes that define luxury living.</p>
   </div>
   <div class="ef-hood-grid">
      <a class="ef-hood big h1" href="search.php?h_location=Bondi"><div class="ef-hood-info"><div class="meta">NSW · Eastern Suburbs</div><h3>Bondi &amp; Tamarama</h3></div></a>
      <a class="ef-hood h2" href="search.php?h_location=Mosman"><div class="ef-hood-info"><div class="meta">NSW · Lower North Shore</div><h3>Mosman</h3></div></a>
      <a class="ef-hood h3" href="search.php?h_location=Sydney"><div class="ef-hood-info"><div class="meta">NSW · CBD</div><h3>Sydney Harbour</h3></div></a>
      <a class="ef-hood h4" href="search.php?h_location=Toorak"><div class="ef-hood-info"><div class="meta">VIC · Inner East</div><h3>Toorak, Melbourne</h3></div></a>
      <a class="ef-hood h5" href="search.php?h_location=New+Farm"><div class="ef-hood-info"><div class="meta">QLD · River District</div><h3>New Farm, Brisbane</h3></div></a>
   </div>
</section>

<!-- SERVICES -->
<section class="ef-services-band">
   <div class="ef-section-head">
      <span class="ey">What We Do</span>
      <h2>An end-to-end <em>luxury</em> experience</h2>
      <p>From first-time buyers to seasoned investors — every detail handled with precision.</p>
   </div>
   <div class="ef-svc-grid">
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-key"></i></div><h3>Buy a Home</h3><p>Hand-picked residences across Australia's most desirable postcodes, each verified by our team.</p></div>
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-house-user"></i></div><h3>Rent a Home</h3><p>Quality rentals from short-let apartments to long-term family homes with flexible filters.</p></div>
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-tag"></i></div><h3>Sell Your Property</h3><p>Reach thousands of qualified buyers with free professional photography and dedicated agents.</p></div>
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-building"></i></div><h3>Apartments</h3><p>Modern apartments and entire residential blocks — perfect for living or portfolio growth.</p></div>
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-store"></i></div><h3>Commercial</h3><p>Prime shops, offices and retail units in Australia's highest-footfall locations.</p></div>
      <div class="ef-svc"><div class="ef-svc-ic"><i class="fas fa-headset"></i></div><h3>24/7 Concierge</h3><p>A dedicated specialist is always one call away — before, during and after your purchase.</p></div>
   </div>
</section>

<!-- LISTINGS -->
<section class="ef-listings-band">
   <div class="ef-section-head">
      <span class="ey">Featured Properties</span>
      <h2>Latest <em>handpicked</em> listings</h2>
      <p>Freshly added by our verified sellers and brokers across Australia.</p>
   </div>
   <div class="ef-prop-grid">
   <?php
      $sel = $conn->prepare("SELECT * FROM `property` ORDER BY date DESC LIMIT 6");
      $sel->execute();
      if ($sel->rowCount() > 0) {
         while ($p = $sel->fetch()) {
            $u = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $u->execute([$p['user_id']]);
            $seller = $u->fetch();
            $saved = $conn->prepare("SELECT id FROM `saved` WHERE property_id = ? AND user_id = ?");
            $saved->execute([$p['id'], $user_id]);
            $is_saved = $saved->rowCount() > 0;
   ?>
      <form action="" method="POST" class="ef-prop">
         <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
         <div class="ef-prop-media">
            <button type="submit" name="save" class="ef-prop-save" title="<?= $is_saved?'Saved':'Save'; ?>">
               <i class="<?= $is_saved?'fas':'far'; ?> fa-heart"></i>
            </button>
            <span class="ef-prop-badge"><?= ucfirst(htmlspecialchars($p['offer'])); ?></span>
            <img src="uploaded_files/<?= htmlspecialchars($p['image_01']); ?>" alt="<?= htmlspecialchars($p['property_name']); ?>">
         </div>
         <div class="ef-prop-body">
            <div class="ef-prop-price">A$<?= number_format((float)$p['price']); ?><small><?= $p['offer']==='rent'?'/mo':''; ?></small></div>
            <h3 class="ef-prop-name"><?= htmlspecialchars($p['property_name']); ?></h3>
            <p class="ef-prop-loc"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['address']); ?></p>
            <div class="ef-prop-meta">
               <span><i class="fas fa-bed"></i> <?= htmlspecialchars($p['bedroom']); ?> bed</span>
               <span><i class="fas fa-bath"></i> <?= htmlspecialchars($p['bathroom']); ?> bath</span>
               <span><i class="fas fa-ruler-combined"></i> <?= htmlspecialchars($p['carpet']); ?> sqft</span>
            </div>
            <div class="ef-prop-actions">
               <a href="view_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="ef-prop-btn-outline">View</a>
               <button type="submit" name="send" class="ef-prop-btn-primary">Enquire</button>
            </div>
         </div>
      </form>
   <?php } } else { ?>
      <div class="ef-empty">No properties listed yet. <a href="post_property.php">Be the first to list</a></div>
   <?php } ?>
   </div>
   <div class="ef-view-all">
      <a href="listings.php">View All Listings <i class="fas fa-arrow-right"></i></a>
   </div>
</section>

<!-- TESTIMONIAL -->
<section class="ef-testi">
   <div class="ef-testi-box">
      <div class="quote-mark">"</div>
      <blockquote>EstateFlow made selling our Mosman home effortless. Within two weeks we had three qualified offers above asking. Truly the most refined property platform in Australia.</blockquote>
      <div class="author"><strong>Charlotte &amp; James Whitmore</strong>Mosman, Sydney</div>
   </div>
</section>

<!-- CTA -->
<section class="ef-cta">
   <div class="ef-cta-box">
      <div>
         <h2>Ready to list your property?</h2>
         <p>Reach thousands of verified buyers and renters across Australia — free to post.</p>
      </div>
      <a href="post_property.php" class="ef-cta-btn"><i class="fas fa-paper-plane"></i> Post Your Property</a>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>
</body>
</html>
