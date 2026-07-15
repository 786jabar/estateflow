<?php
session_start();
ini_set('display_errors',1); error_reporting(E_ALL);
include '../components/connect.php';
$admin_id = $_SESSION['admin_id'] ?? ($_COOKIE['admin_id'] ?? '');
if($admin_id===''){ header('location:login.php'); exit; }
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
try{ $a=$conn->prepare("SELECT name FROM admins WHERE id=? LIMIT 1"); $a->execute([$admin_id]);
     $r=$a->fetch(PDO::FETCH_ASSOC); if($r) $admin_name=$r['name']; }catch(Exception $e){}

$ok=''; $err='';
if(isset($_GET['delete'])){
   if($_GET['delete']==$admin_id){ $err="You can't delete yourself."; }
   else{ try{ $conn->prepare("DELETE FROM admins WHERE id=?")->execute([$_GET['delete']]); $ok='Admin deleted.'; }catch(Exception $e){ $err=$e->getMessage(); } }
}

$rows=[]; try{ $s=$conn->prepare("SELECT id, name FROM admins ORDER BY name"); $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC); }catch(Exception $e){ $err=$e->getMessage(); }

$ef_page_title='Admins'; include '_layout_top.php';
?>
<div class="card">
   <h2>All Admins (<?= count($rows) ?>) &nbsp; <a href="register.php" class="btn btn-sm" style="float:right;"><i class="fas fa-plus"></i> Add Admin</a></h2>
   <?php if($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
   <?php if(!$rows): ?>
      <div class="empty"><i class="fas fa-user-shield"></i><p>No admins.</p></div>
   <?php else: ?>
   <table>
      <thead><tr><th>Username</th><th>ID</th><th style="text-align:right;">Action</th></tr></thead>
      <tbody>
      <?php foreach($rows as $r): ?>
         <tr>
            <td><strong><?= htmlspecialchars($r['name']) ?></strong> <?= $r['id']==$admin_id?'<span class="badge">You</span>':'' ?></td>
            <td style="color:#999;font-family:monospace;font-size:.85rem;"><?= htmlspecialchars($r['id']) ?></td>
            <td style="text-align:right;">
               <?php if($r['id']!=$admin_id): ?>
                  <a href="?delete=<?= urlencode($r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete admin <?= htmlspecialchars($r['name']) ?>?');"><i class="fas fa-trash"></i> Delete</a>
               <?php else: ?>
                  <a href="update.php" class="btn btn-sm btn-dark"><i class="fas fa-pen"></i> Edit Profile</a>
               <?php endif; ?>
            </td>
         </tr>
      <?php endforeach; ?>
      </tbody>
   </table>
   <?php endif; ?>
</div>
</main></div></body></html>
