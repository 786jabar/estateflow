<?php
$ef_current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $ef_page_title ?? 'Admin' ?> — EstateFlow</title>
<style>
/* Content styles shared by admin pages (cards, tables, forms, alerts) */
.card{background:#fff;padding:28px;border-radius:18px;box-shadow:0 4px 20px rgba(10,22,40,.05);border:1px solid rgba(184,147,90,.08);margin-bottom:24px;}
.card h2{font-family:'Playfair Display',serif;color:#0A1628;font-size:1.3rem;margin-bottom:18px;}
table{width:100%;border-collapse:collapse;}
th{text-align:left;padding:12px 14px;background:#f9f7f3;color:#0A1628;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #B8935A;}
td{padding:14px;border-bottom:1px solid #eee;color:#333;font-size:.92rem;}
tr:hover td{background:#fafbfc;}
.btn{display:inline-block;padding:10px 18px;background:#B8935A;color:#0A1628;border:none;border-radius:8px;text-decoration:none;font-weight:600;cursor:pointer;font-size:.9rem;font-family:inherit;}
.btn:hover{background:#c9a961;}
.btn-dark{background:#0A1628;color:#fff;}.btn-dark:hover{background:#152841;}
.btn-danger{background:#dc3545;color:#fff;}.btn-danger:hover{background:#c82333;}
.btn-sm{padding:6px 14px;font-size:.82rem;}
.field{margin-bottom:18px;}.field label{display:block;font-size:.85rem;color:#0A1628;margin-bottom:6px;font-weight:500;}
.box{width:100%;padding:12px 14px;border:1px solid #ddd;border-radius:8px;font-size:.95rem;outline:none;font-family:inherit;}
.box:focus{border-color:#B8935A;}
.msg{padding:12px 14px;border-radius:8px;margin-bottom:18px;font-size:.9rem;}
.msg.ok{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.msg.err{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
.empty{text-align:center;padding:50px;color:#999;}.empty i{font-size:3rem;color:#B8935A;margin-bottom:14px;display:block;}
.badge{display:inline-block;padding:4px 10px;border-radius:20px;font-size:.75rem;background:#B8935A;color:#0A1628;font-weight:600;}
.form-narrow{max-width:560px;}
.muted-note{color:#8a8f98;font-size:.85rem;line-height:1.6;margin-top:14px;}
.toolbar-flex{display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:20px;}
.search-inline{display:flex;gap:10px;flex-wrap:wrap;}
.search-inline .box{flex:1;min-width:240px;width:auto;}
</style></head><body><div class="ef-admin-shell">
<?php include '../components/admin_header.php'; ?>
<main class="ef-main">
<div class="ef-topbar">
   <h1><?= $ef_page_title ?? 'Admin' ?><small>EstateFlow Admin</small></h1>
   <div class="user"><i class="fas fa-user-shield"></i>
      <span>Welcome back,<br><strong style="color:#0A1628;font-size:.98rem;"><?= htmlspecialchars($admin_name ?? 'Admin') ?></strong></span>
   </div>
</div>
