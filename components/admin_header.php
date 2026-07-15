<?php $current = basename($_SERVER['PHP_SELF']); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
html,body{padding:0!important;margin:0!important;background:linear-gradient(135deg,#f1f2f6 0%,#e9ecf2 100%);min-height:100vh;}
body{font-family:'Inter',sans-serif;color:#0A1628;}
.ef-admin-shell{display:flex;min-height:100vh;}

/* === SIDEBAR === */
.ef-sidebar{
  width:280px;background:linear-gradient(180deg,#0A1628 0%,#06101e 100%);color:#fff;
  padding:32px 20px;position:fixed;top:0;left:0;bottom:0;overflow-y:auto;z-index:100;
  box-shadow:4px 0 30px rgba(0,0,0,.18);
}
.ef-sidebar::before{content:'';position:absolute;top:0;right:0;width:2px;height:100%;
  background:linear-gradient(180deg,transparent,#B8935A 40%,#B8935A 60%,transparent);opacity:.5;}
.ef-sidebar::after{content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;
  background:radial-gradient(circle,rgba(184,147,90,.12) 0%,transparent 70%);pointer-events:none;}

.ef-brand{font-family:'Playfair Display',serif;font-size:1.85rem;font-weight:700;
  color:#fff;text-decoration:none;display:flex;flex-direction:column;align-items:center;
  padding:8px 0 24px;border-bottom:1px solid rgba(184,147,90,.25);margin-bottom:28px;position:relative;}
.ef-brand .ef-gem{width:60px;height:60px;border:2px solid #B8935A;border-radius:14px;
  display:inline-flex;align-items:center;justify-content:center;color:#B8935A;font-size:1.7rem;
  margin-bottom:14px;background:rgba(184,147,90,.06);
  box-shadow:0 0 30px rgba(184,147,90,.2),inset 0 0 20px rgba(184,147,90,.05);}
.ef-brand .ef-name span{color:#B8935A;}
.ef-brand small{display:block;font-family:'Inter',sans-serif;font-size:.66rem;
  letter-spacing:5px;color:#B8935A;margin-top:8px;font-weight:600;}

.ef-nav-section{font-size:.65rem;letter-spacing:3px;color:rgba(184,147,90,.6);
  text-transform:uppercase;font-weight:700;padding:0 16px 10px;margin-top:8px;}

.ef-nav a{display:flex;align-items:center;gap:14px;padding:13px 16px;color:#a5adba;
  text-decoration:none;border-radius:10px;margin-bottom:3px;transition:all .25s;
  font-size:.93rem;font-weight:500;position:relative;overflow:hidden;}
.ef-nav a i{width:20px;font-size:1rem;color:#B8935A;transition:transform .25s;}
.ef-nav a:hover{background:rgba(184,147,90,.08);color:#fff;padding-left:20px;}
.ef-nav a:hover i{transform:scale(1.15);}
.ef-nav a.active{background:linear-gradient(135deg,#B8935A,#d4af71);color:#0A1628;font-weight:700;
  box-shadow:0 4px 18px rgba(184,147,90,.45);}
.ef-nav a.active i{color:#0A1628;}
.ef-nav a.active::after{content:'';position:absolute;right:14px;top:50%;transform:translateY(-50%);
  width:6px;height:6px;background:#0A1628;border-radius:50%;}

.ef-logout{margin-top:26px;padding-top:22px;border-top:1px solid rgba(184,147,90,.2);}
.ef-logout a{display:flex;align-items:center;gap:12px;padding:13px 16px;color:#ff8b94;
  text-decoration:none;border-radius:10px;font-size:.9rem;font-weight:600;
  border:1px solid rgba(255,139,148,.3);transition:.2s;background:rgba(255,139,148,.04);}
.ef-logout a:hover{background:rgba(255,139,148,.12);transform:translateX(3px);}

/* === MAIN === */
.ef-main{margin-left:280px;flex:1;padding:32px 40px;}
.ef-topbar{background:#fff;padding:22px 28px;border-radius:18px;display:flex;
  justify-content:space-between;align-items:center;margin-bottom:28px;
  box-shadow:0 4px 24px rgba(10,22,40,.06);position:relative;overflow:hidden;}
.ef-topbar::before{content:'';position:absolute;left:0;top:0;bottom:0;width:5px;
  background:linear-gradient(180deg,#B8935A,#d4af71);}
.ef-topbar h1{font-family:'Playfair Display',serif;font-size:1.7rem;color:#0A1628;
  margin:0;font-weight:700;letter-spacing:-.3px;}
.ef-topbar h1 small{display:block;font-family:'Inter',sans-serif;font-size:.72rem;
  color:#B8935A;letter-spacing:3px;text-transform:uppercase;font-weight:600;margin-top:4px;}
.ef-topbar .user{display:flex;align-items:center;gap:12px;color:#666;font-size:.9rem;
  background:linear-gradient(135deg,#fafaf7,#f5f3ed);padding:8px 16px 8px 8px;border-radius:50px;
  border:1px solid rgba(184,147,90,.18);}
.ef-topbar .user i{width:40px;height:40px;background:linear-gradient(135deg,#B8935A,#d4af71);
  color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;
  font-size:1rem;box-shadow:0 4px 12px rgba(184,147,90,.35);}

.ef-menu-btn{display:none;position:fixed;top:18px;left:18px;z-index:200;
  background:#0A1628;color:#B8935A;border:1px solid rgba(184,147,90,.4);width:46px;height:46px;
  border-radius:10px;font-size:1.2rem;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.2);}
@media(max-width:900px){
  .ef-sidebar{transform:translateX(-100%);transition:transform .3s;}
  .ef-sidebar.open{transform:translateX(0);}
  .ef-main{margin-left:0;padding:75px 18px 24px;}
  .ef-menu-btn{display:block;}
}
</style>
<button class="ef-menu-btn" onclick="document.querySelector('.ef-sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
<aside class="ef-sidebar">
   <a href="dashboard.php" class="ef-brand">
     <span class="ef-gem"><i class="fas fa-gem"></i></span>
     <span class="ef-name">Estate<span>Flow</span></span>
     <small>ADMIN PANEL</small>
   </a>
   <div class="ef-nav-section">Overview</div>
   <nav class="ef-nav">
      <a href="dashboard.php" class="<?= $current=='dashboard.php'?'active':'' ?>"><i class="fas fa-gauge-high"></i> Dashboard</a>
      <a href="listings.php" class="<?= $current=='listings.php'?'active':'' ?>"><i class="fas fa-building"></i> Listings</a>
      <a href="messages.php" class="<?= $current=='messages.php'?'active':'' ?>"><i class="fas fa-envelope"></i> Messages</a>
   </nav>
   <div class="ef-nav-section">People</div>
   <nav class="ef-nav">
      <a href="users.php" class="<?= $current=='users.php'?'active':'' ?>"><i class="fas fa-users"></i> Users</a>
      <a href="add_user.php" class="<?= $current=='add_user.php'?'active':'' ?>"><i class="fas fa-user-plus"></i> Add User</a>
      <a href="admins.php" class="<?= $current=='admins.php'?'active':'' ?>"><i class="fas fa-user-shield"></i> Admins</a>
      <a href="register.php" class="<?= $current=='register.php'?'active':'' ?>"><i class="fas fa-user-tie"></i> Add Admin</a>
   </nav>
   <div class="ef-nav-section">Account</div>
   <nav class="ef-nav">
      <a href="update.php" class="<?= $current=='update.php'?'active':'' ?>"><i class="fas fa-user-gear"></i> My Profile</a>
   </nav>
   <div class="ef-logout">
      <a href="../components/admin_logout.php" onclick="return confirm('Log out of the admin panel?');">
         <i class="fas fa-right-from-bracket"></i> Logout
      </a>
   </div>
</aside>
