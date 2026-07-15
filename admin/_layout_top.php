<?php
$ef_current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $ef_page_title ?? 'Admin' ?> — EstateFlow</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
html,body{padding:0!important;margin:0!important;background:#f4f6f8;font-family:'Inter',sans-serif;color:#0A1628;}
.shell{display:flex;min-height:100vh;}
.side{width:260px;background:#0A1628;color:#fff;padding:28px 18px;position:fixed;top:0;left:0;bottom:0;overflow-y:auto;z-index:50;}
.brand{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#fff;text-decoration:none;display:block;text-align:center;padding-bottom:20px;border-bottom:1px solid rgba(184,147,90,.25);margin-bottom:22px;}
.brand i{color:#B8935A;margin-right:8px;}
.brand small{display:block;font-family:'Inter',sans-serif;font-size:.7rem;letter-spacing:3px;color:#B8935A;margin-top:4px;}
.nav a{display:flex;align-items:center;gap:14px;padding:12px 16px;color:#cbd1d9;text-decoration:none;border-radius:8px;margin-bottom:4px;font-size:.95rem;}
.nav a i{width:18px;color:#B8935A;}.nav a:hover{background:rgba(184,147,90,.1);color:#fff;}
.nav a.active{background:#B8935A;color:#0A1628;font-weight:600;}.nav a.active i{color:#0A1628;}
.logout{margin-top:24px;padding-top:20px;border-top:1px solid rgba(184,147,90,.25);}
.logout a{display:flex;align-items:center;gap:10px;padding:12px 16px;color:#ff8b94;text-decoration:none;border-radius:8px;font-size:.9rem;border:1px solid rgba(255,139,148,.3);}
.main{margin-left:260px;flex:1;padding:32px 40px;}
.topbar{background:#fff;padding:18px 24px;border-radius:12px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.04);}
.topbar h1{font-family:'Playfair Display',serif;font-size:1.5rem;color:#0A1628;}
.topbar .u{display:flex;align-items:center;gap:10px;color:#666;font-size:.9rem;}
.topbar .u i{width:36px;height:36px;background:#B8935A;color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;}
.card{background:#fff;padding:28px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.04);margin-bottom:24px;}
.card h2{font-family:'Playfair Display',serif;color:#0A1628;font-size:1.3rem;margin-bottom:18px;}
table{width:100%;border-collapse:collapse;}
th{text-align:left;padding:12px 14px;background:#f9f7f3;color:#0A1628;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #B8935A;}
td{padding:14px;border-bottom:1px solid #eee;color:#333;font-size:.92rem;}
tr:hover td{background:#fafbfc;}
.btn{display:inline-block;padding:10px 18px;background:#B8935A;color:#0A1628;border:none;border-radius:8px;text-decoration:none;font-weight:600;cursor:pointer;font-size:.9rem;}
.btn:hover{background:#c9a961;}
.btn-dark{background:#0A1628;color:#fff;}.btn-dark:hover{background:#152841;}
.btn-danger{background:#dc3545;color:#fff;}.btn-danger:hover{background:#c82333;}
.btn-sm{padding:6px 14px;font-size:.82rem;}
.field{margin-bottom:18px;}.field label{display:block;font-size:.85rem;color:#0A1628;margin-bottom:6px;font-weight:500;}
.box{width:100%;padding:12px 14px;border:1px solid #ddd;border-radius:8px;font-size:.95rem;outline:none;}
.box:focus{border-color:#B8935A;}
.msg{padding:12px 14px;border-radius:8px;margin-bottom:18px;font-size:.9rem;}
.msg.ok{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.msg.err{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
.empty{text-align:center;padding:50px;color:#999;}.empty i{font-size:3rem;color:#B8935A;margin-bottom:14px;display:block;}
.badge{display:inline-block;padding:4px 10px;border-radius:20px;font-size:.75rem;background:#B8935A;color:#0A1628;font-weight:600;}
@media(max-width:900px){.side{width:220px;}.main{margin-left:220px;padding:18px;}}
</style></head><body><div class="shell">
<aside class="side">
   <a href="dashboard.php" class="brand"><i class="fas fa-gem"></i>EstateFlow<small>ADMIN PANEL</small></a>
   <nav class="nav">
      <a href="dashboard.php" class="<?= $ef_current=='dashboard.php'?'active':'' ?>"><i class="fas fa-gauge-high"></i> Dashboard</a>
      <a href="listings.php" class="<?= $ef_current=='listings.php'?'active':'' ?>"><i class="fas fa-building"></i> Listings</a>
      <a href="users.php" class="<?= $ef_current=='users.php'?'active':'' ?>"><i class="fas fa-users"></i> Users</a>
      <a href="add_user.php" class="<?= $ef_current=='add_user.php'?'active':'' ?>"><i class="fas fa-user-plus"></i> Add User</a>
      <a href="admins.php" class="<?= $ef_current=='admins.php'?'active':'' ?>"><i class="fas fa-user-shield"></i> Admins</a>
      <a href="messages.php" class="<?= $ef_current=='messages.php'?'active':'' ?>"><i class="fas fa-envelope"></i> Messages</a>
      <a href="register.php" class="<?= $ef_current=='register.php'?'active':'' ?>"><i class="fas fa-user-plus"></i> Add Admin</a>
      <a href="update.php" class="<?= $ef_current=='update.php'?'active':'' ?>"><i class="fas fa-user-gear"></i> Update</a>
   </nav>
   <div class="logout"><a href="../components/admin_logout.php" onclick="return confirm('Log out?');"><i class="fas fa-right-from-bracket"></i> Logout</a></div>
</aside>
<main class="main">
<div class="topbar">
   <h1><?= $ef_page_title ?? 'Admin' ?></h1>
   <div class="u"><i class="fas fa-user-shield"></i> <strong style="color:#0A1628;margin-left:4px;"><?= htmlspecialchars($admin_name ?? 'Admin') ?></strong></div>
</div>
