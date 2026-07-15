<?php
include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   header('location:login.php');exit;
}

$admin_name = 'Admin';
try {
   $a = $conn->prepare("SELECT name FROM `admins` WHERE id = ? LIMIT 1");
   $a->execute([$admin_id]);
   $r = $a->fetch(PDO::FETCH_ASSOC);
   if ($r) $admin_name = $r['name'];
} catch (Exception $e) {}

function safe_count($conn, $sql){
   try { $s = $conn->prepare($sql); $s->execute(); return $s->fetchColumn(); }
   catch (Exception $e) { return 0; }
}

$total_users    = safe_count($conn, "SELECT COUNT(*) FROM `users`");
$total_admins   = safe_count($conn, "SELECT COUNT(*) FROM `admins`");
$total_props    = safe_count($conn, "SELECT COUNT(*) FROM `property`");
if (!$total_props) $total_props = safe_count($conn, "SELECT COUNT(*) FROM `properties`");
$total_msgs     = safe_count($conn, "SELECT COUNT(*) FROM `messages`");
$total_requests = safe_count($conn, "SELECT COUNT(*) FROM `requests`");
$total_saved    = safe_count($conn, "SELECT COUNT(*) FROM `saved`");
$today = date('l, j F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard — EstateFlow Admin</title>
</head>
<body>
<div class="ef-admin-shell">
<?php include '../components/admin_header.php'; ?>

<main class="ef-main">
   <div class="ef-topbar">
      <h1>Dashboard Overview<small><?= $today ?></small></h1>
      <div class="user"><i class="fas fa-user-shield"></i>
        <span>Welcome back,<br><strong style="color:#0A1628;font-size:.98rem;"><?= htmlspecialchars($admin_name) ?></strong></span>
      </div>
   </div>

   <style>
   .ef-hero-card{background:linear-gradient(135deg,#0A1628 0%,#152841 60%,#1f3050 100%);
     color:#fff;padding:36px 40px;border-radius:20px;margin-bottom:30px;position:relative;overflow:hidden;
     box-shadow:0 10px 40px rgba(10,22,40,.18);}
   .ef-hero-card::before{content:'';position:absolute;top:-100px;right:-100px;width:300px;height:300px;
     background:radial-gradient(circle,rgba(184,147,90,.18) 0%,transparent 70%);}
   .ef-hero-card::after{content:'\f3a5';font-family:'Font Awesome 6 Free';font-weight:900;
     position:absolute;right:30px;bottom:-20px;font-size:11rem;color:rgba(184,147,90,.08);}
   .ef-hero-card .eyebrow{display:inline-block;font-size:.7rem;letter-spacing:4px;color:#B8935A;
     font-weight:700;padding:5px 14px;border:1px solid rgba(184,147,90,.4);border-radius:30px;margin-bottom:14px;}
   .ef-hero-card h2{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;
     margin-bottom:8px;letter-spacing:-.5px;}
   .ef-hero-card h2 span{color:#B8935A;font-style:italic;}
   .ef-hero-card p{color:#a5adba;font-size:.98rem;max-width:580px;line-height:1.6;}

   .ef-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:20px;margin-bottom:34px;}
   .ef-stat{background:#fff;padding:26px;border-radius:18px;
     box-shadow:0 4px 20px rgba(10,22,40,.05);position:relative;overflow:hidden;
     border:1px solid rgba(184,147,90,.08);transition:all .3s;}
   .ef-stat::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;
     background:linear-gradient(90deg,#B8935A,#d4af71);transform:scaleX(0);transform-origin:left;transition:transform .35s;}
   .ef-stat:hover{transform:translateY(-6px);box-shadow:0 14px 36px rgba(10,22,40,.13);}
   .ef-stat:hover::before{transform:scaleX(1);}
   .ef-stat:hover .ef-stat-ic{transform:rotate(-8deg) scale(1.08);}
   .ef-stat-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px;}
   .ef-stat-ic{width:58px;height:58px;border-radius:14px;display:inline-flex;align-items:center;
     justify-content:center;font-size:1.4rem;color:#fff;transition:all .3s;
     background:linear-gradient(135deg,#0A1628,#1f3050);box-shadow:0 6px 18px rgba(10,22,40,.18);}
   .ef-stat.gold .ef-stat-ic{background:linear-gradient(135deg,#B8935A,#d4af71);box-shadow:0 6px 18px rgba(184,147,90,.35);color:#0A1628;}
   .ef-stat.green .ef-stat-ic{background:linear-gradient(135deg,#1d8a5b,#27a973);box-shadow:0 6px 18px rgba(29,138,91,.3);}
   .ef-stat.rose .ef-stat-ic{background:linear-gradient(135deg,#c0392b,#e74c3c);box-shadow:0 6px 18px rgba(192,57,43,.3);}
   .ef-stat.violet .ef-stat-ic{background:linear-gradient(135deg,#6c3a8b,#8e44ad);box-shadow:0 6px 18px rgba(108,58,139,.3);}
   .ef-stat.teal .ef-stat-ic{background:linear-gradient(135deg,#0e6e7d,#16a085);box-shadow:0 6px 18px rgba(14,110,125,.3);}
   .ef-stat-trend{font-size:.72rem;color:#1d8a5b;font-weight:700;background:rgba(29,138,91,.1);
     padding:4px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;}
   .ef-stat .num{font-family:'Playfair Display',serif;font-size:2.6rem;color:#0A1628;
     font-weight:700;line-height:1;letter-spacing:-1.5px;}
   .ef-stat .lbl{color:#6b7280;font-size:.72rem;margin-top:8px;text-transform:uppercase;
     letter-spacing:2.5px;font-weight:600;}
   .ef-stat .sub{color:#94a3b8;font-size:.78rem;margin-top:6px;}

   .ef-grid-2{display:grid;grid-template-columns:1.6fr 1fr;gap:24px;margin-bottom:24px;}
   @media(max-width:1100px){.ef-grid-2{grid-template-columns:1fr;}}

   .ef-quick{background:#fff;padding:30px;border-radius:18px;box-shadow:0 4px 20px rgba(10,22,40,.05);
     border:1px solid rgba(184,147,90,.08);}
   .ef-quick-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;
     padding-bottom:18px;border-bottom:1px solid rgba(184,147,90,.15);}
   .ef-quick h2{font-family:'Playfair Display',serif;color:#0A1628;font-size:1.45rem;margin:0;font-weight:700;}
   .ef-quick h2 small{display:block;font-family:'Inter',sans-serif;font-size:.7rem;
     letter-spacing:2.5px;color:#B8935A;text-transform:uppercase;font-weight:600;margin-top:4px;}
   .ef-quick-badge{font-size:.7rem;color:#B8935A;padding:5px 12px;border:1px solid rgba(184,147,90,.4);
     border-radius:30px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;}
   .ef-actions{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;}
   .ef-act{padding:22px;border:1.5px solid #ece8df;border-radius:14px;text-decoration:none;color:#0A1628;
     transition:all .25s;display:block;position:relative;overflow:hidden;background:#fafaf7;}
   .ef-act::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;
     background:linear-gradient(135deg,#B8935A,#d4af71);opacity:0;transition:opacity .25s;z-index:0;}
   .ef-act:hover{border-color:#B8935A;transform:translateY(-3px);box-shadow:0 10px 24px rgba(184,147,90,.22);}
   .ef-act:hover::before{opacity:.05;}
   .ef-act > *{position:relative;z-index:1;}
   .ef-act-ic{width:44px;height:44px;background:linear-gradient(135deg,#B8935A,#d4af71);color:#0A1628;
     border-radius:11px;display:inline-flex;align-items:center;justify-content:center;
     font-size:1.15rem;margin-bottom:14px;box-shadow:0 4px 12px rgba(184,147,90,.3);}
   .ef-act strong{display:block;font-size:1rem;margin-bottom:4px;color:#0A1628;font-weight:700;}
   .ef-act span{color:#7a8290;font-size:.83rem;}

   .ef-insights{background:linear-gradient(160deg,#0A1628,#152841);color:#fff;padding:30px;
     border-radius:18px;box-shadow:0 4px 20px rgba(10,22,40,.12);position:relative;overflow:hidden;}
   .ef-insights::before{content:'';position:absolute;top:-60px;right:-60px;width:200px;height:200px;
     background:radial-gradient(circle,rgba(184,147,90,.15) 0%,transparent 70%);}
   .ef-insights h2{font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:6px;font-weight:700;}
   .ef-insights .sub{color:#B8935A;font-size:.7rem;letter-spacing:2.5px;text-transform:uppercase;font-weight:600;margin-bottom:24px;}
   .ef-ins-item{display:flex;justify-content:space-between;align-items:center;padding:14px 0;
     border-bottom:1px solid rgba(184,147,90,.15);position:relative;z-index:1;}
   .ef-ins-item:last-child{border-bottom:none;}
   .ef-ins-item .l{display:flex;align-items:center;gap:14px;color:#cdd5e0;font-size:.93rem;}
   .ef-ins-item .l i{width:36px;height:36px;background:rgba(184,147,90,.15);color:#B8935A;
     border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:.95rem;}
   .ef-ins-item .v{color:#B8935A;font-weight:700;font-family:'Playfair Display',serif;font-size:1.3rem;}
   </style>

   <div class="ef-hero-card">
     <span class="eyebrow">LUXURY REAL ESTATE · ADMIN</span>
     <h2>Welcome to your <span>EstateFlow</span> command centre</h2>
     <p>Manage your curated portfolio of Australian luxury properties, oversee user accounts and respond to enquiries — all from one elegant dashboard.</p>
   </div>

   <div class="ef-stats">
      <div class="ef-stat gold">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-building"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-arrow-up"></i> Active</span>
        </div>
        <div class="num"><?= number_format($total_props) ?></div>
        <div class="lbl">Properties</div>
        <div class="sub">Total listings across portfolio</div>
      </div>

      <div class="ef-stat">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-users"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-arrow-up"></i> Live</span>
        </div>
        <div class="num"><?= number_format($total_users) ?></div>
        <div class="lbl">Registered Users</div>
        <div class="sub">Buyers, renters &amp; sellers</div>
      </div>

      <div class="ef-stat violet">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-user-shield"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-shield"></i> Secure</span>
        </div>
        <div class="num"><?= number_format($total_admins) ?></div>
        <div class="lbl">Administrators</div>
        <div class="sub">Team members with access</div>
      </div>

      <div class="ef-stat teal">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-envelope"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-inbox"></i> Inbox</span>
        </div>
        <div class="num"><?= number_format($total_msgs) ?></div>
        <div class="lbl">Messages</div>
        <div class="sub">Enquiries from prospects</div>
      </div>

      <div class="ef-stat green">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-paper-plane"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-bell"></i> Pending</span>
        </div>
        <div class="num"><?= number_format($total_requests) ?></div>
        <div class="lbl">Requests</div>
        <div class="sub">Viewing &amp; tour requests</div>
      </div>

      <div class="ef-stat rose">
        <div class="ef-stat-top">
          <div class="ef-stat-ic"><i class="fas fa-heart"></i></div>
          <span class="ef-stat-trend"><i class="fas fa-bookmark"></i> Saved</span>
        </div>
        <div class="num"><?= number_format($total_saved) ?></div>
        <div class="lbl">Favourites</div>
        <div class="sub">Properties saved by users</div>
      </div>
   </div>

   <div class="ef-grid-2">
     <div class="ef-quick">
        <div class="ef-quick-head">
          <h2>Quick Actions<small>SHORTCUTS</small></h2>
          <span class="ef-quick-badge">⚡ Fast Access</span>
        </div>
        <div class="ef-actions">
           <a href="listings.php" class="ef-act">
             <div class="ef-act-ic"><i class="fas fa-building"></i></div>
             <strong>Manage Listings</strong><span>View &amp; edit properties</span>
           </a>
           <a href="users.php" class="ef-act">
             <div class="ef-act-ic"><i class="fas fa-users"></i></div>
             <strong>Manage Users</strong><span>View user accounts</span>
           </a>
           <a href="messages.php" class="ef-act">
             <div class="ef-act-ic"><i class="fas fa-envelope"></i></div>
             <strong>Inbox</strong><span>Read enquiries</span>
           </a>
           <a href="register.php" class="ef-act">
             <div class="ef-act-ic"><i class="fas fa-user-tie"></i></div>
             <strong>Add Admin</strong><span>Invite team member</span>
           </a>
           <a href="add_user.php" class="ef-act">
             <div class="ef-act-ic"><i class="fas fa-user-plus"></i></div>
             <strong>Add User</strong><span>Create new account</span>
           </a>
           <a href="../home.php" class="ef-act" target="_blank">
             <div class="ef-act-ic"><i class="fas fa-globe"></i></div>
             <strong>View Site</strong><span>Open public site</span>
           </a>
        </div>
     </div>

     <div class="ef-insights">
       <h2>Portfolio Insights</h2>
       <div class="sub">AT A GLANCE</div>
       <div class="ef-ins-item">
         <div class="l"><i class="fas fa-chart-line"></i> Total Portfolio</div>
         <div class="v"><?= number_format($total_props) ?></div>
       </div>
       <div class="ef-ins-item">
         <div class="l"><i class="fas fa-user-check"></i> Active Members</div>
         <div class="v"><?= number_format($total_users + $total_admins) ?></div>
       </div>
       <div class="ef-ins-item">
         <div class="l"><i class="fas fa-comments"></i> Open Threads</div>
         <div class="v"><?= number_format($total_msgs + $total_requests) ?></div>
       </div>
       <div class="ef-ins-item">
         <div class="l"><i class="fas fa-star"></i> Saved Items</div>
         <div class="v"><?= number_format($total_saved) ?></div>
       </div>
       <div class="ef-ins-item">
         <div class="l"><i class="fas fa-location-dot"></i> Market</div>
         <div class="v" style="font-size:.95rem;">Sydney, AU</div>
       </div>
     </div>
   </div>

</main>
</div>
</body>
</html>
