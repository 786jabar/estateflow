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
      try{ $conn->prepare("DELETE FROM `requests` WHERE id=?")->execute([$_POST['delete_id']]); $ok='Enquiry deleted.'; }catch(Exception $e){ $err='Could not delete the enquiry. Please try again.'; }
   } else { $err='Security check failed. Please try again.'; }
}

$rows=[];
try{
   $s=$conn->prepare("SELECT r.id, r.date,
         p.property_name AS property_title,
         su.name AS sender_name, su.email AS sender_email,
         ru.name AS receiver_name, ru.email AS receiver_email
      FROM `requests` r
      LEFT JOIN `property` p ON p.id = r.property_id
      LEFT JOIN `users` su ON su.id = r.sender
      LEFT JOIN `users` ru ON ru.id = r.receiver
      ORDER BY r.date DESC");
   $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC);
}catch(Exception $e){ $err=$e->getMessage(); }

$ef_page_title='Enquiries'; include '_layout_top.php';
?>
<div class="card">
   <h2>Property Enquiries (<?= count($rows) ?>)</h2>
   <?php if($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
   <?php if(!$rows): ?>
      <div class="empty"><i class="fas fa-paper-plane"></i><p>No enquiries yet. When a signed-in user clicks "Send Enquiry" on a listing, it will appear here.</p></div>
   <?php else: ?>
      <table>
         <tr><th>Property</th><th>From (buyer)</th><th>To (seller)</th><th>Date</th><th></th></tr>
         <?php foreach($rows as $r): ?>
         <tr>
            <td><strong><?= htmlspecialchars($r['property_title'] ?? 'Deleted listing') ?></strong></td>
            <td><?= htmlspecialchars($r['sender_name'] ?? 'Unknown') ?><br><small style="color:#888;"><?= htmlspecialchars($r['sender_email'] ?? '') ?></small></td>
            <td><?= htmlspecialchars($r['receiver_name'] ?? 'Unknown') ?><br><small style="color:#888;"><?= htmlspecialchars($r['receiver_email'] ?? '') ?></small></td>
            <td><?= htmlspecialchars($r['date'] ?? '') ?></td>
            <td>
               <form method="post" style="display:inline;" onsubmit="return confirm('Delete this enquiry?');">
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
