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
if(isset($_POST['delete_id'])){
   if(ef_csrf_check()){
      try{ $conn->prepare("DELETE FROM `saved` WHERE id=?")->execute([$_POST['delete_id']]); $ok='Saved entry removed.'; }catch(Exception $e){ $err='Could not remove the entry. Please try again.'; }
   } else { $err='Security check failed. Please try again.'; }
}

$rows=[];
try{
   $s=$conn->prepare("SELECT s.id,
         p.property_name AS property_title, p.price AS property_price,
         u.name AS user_name, u.email AS user_email
      FROM `saved` s
      LEFT JOIN `property` p ON p.id = s.property_id
      LEFT JOIN `users` u ON u.id = s.user_id
      ORDER BY s.id DESC");
   $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC);
}catch(Exception $e){ $err=$e->getMessage(); }

$ef_page_title='Saved Listings'; include '_layout_top.php';
?>
<div class="card">
   <h2>Saved by Users (<?= count($rows) ?>)</h2>
   <?php if($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
   <?php if(!$rows): ?>
      <div class="empty"><i class="fas fa-heart"></i><p>No saved listings yet. When a signed-in user clicks "Save" on a property, it will appear here.</p></div>
   <?php else: ?>
      <table>
         <tr><th>Property</th><th>Price</th><th>Saved by</th><th></th></tr>
         <?php foreach($rows as $r): ?>
         <tr>
            <td><strong><?= htmlspecialchars($r['property_title'] ?? 'Deleted listing') ?></strong></td>
            <td>A$<?= number_format((float)($r['property_price'] ?? 0)) ?></td>
            <td><?= htmlspecialchars($r['user_name'] ?? 'Unknown') ?><br><small style="color:#888;"><?= htmlspecialchars($r['user_email'] ?? '') ?></small></td>
            <td>
               <form method="post" style="display:inline;" onsubmit="return confirm('Remove this saved entry?');">
                  <?= ef_csrf_token() ?>
                  <input type="hidden" name="delete_id" value="<?= htmlspecialchars($r['id']) ?>">
                  <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
               </form>
            </td>
         </tr>
         <?php endforeach; ?>
      </table>
   <?php endif; ?>
</div>
</main></div></body></html>
