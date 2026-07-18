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
      try{ $conn->prepare("DELETE FROM messages WHERE id=?")->execute([$_POST['delete_id']]); $ok='Message deleted.'; }catch(Exception $e){ $err='Could not delete the message. Please try again.'; }
   } else { $err='Security check failed. Please try again.'; }
}

$rows=[]; $cols=[];
try{ $s=$conn->prepare("SELECT * FROM messages ORDER BY id DESC"); $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC); }catch(Exception $e){ $err=$e->getMessage(); }
if($rows) $cols=array_keys($rows[0]);

$ef_page_title='Messages'; include '_layout_top.php';
?>
<div class="card">
   <h2>Inbox (<?= count($rows) ?>)</h2>
   <?php if($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
   <?php if(!$rows): ?>
      <div class="empty"><i class="fas fa-envelope-open"></i><p>No messages yet.</p></div>
   <?php else: ?>
      <?php foreach($rows as $r): ?>
         <div style="border:1px solid #eee;border-radius:10px;padding:18px;margin-bottom:14px;border-left:4px solid #B8935A;">
            <div style="display:flex;justify-content:space-between;align-items:start;gap:10px;flex-wrap:wrap;">
               <div>
                  <?php foreach($cols as $c): if($c=='id'||$c=='message') continue; ?>
                     <div style="font-size:.88rem;color:#555;"><strong style="color:#0A1628;"><?= htmlspecialchars($c) ?>:</strong> <?= htmlspecialchars($r[$c] ?? '') ?></div>
                  <?php endforeach; ?>
               </div>
               <form method="post" style="display:inline;" onsubmit="return confirm('Delete message?');">
                  <?= ef_csrf_token() ?>
                  <input type="hidden" name="delete_id" value="<?= htmlspecialchars($r['id']) ?>">
                  <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
               </form>
            </div>
            <?php if(!empty($r['message'])): ?>
               <div style="margin-top:12px;padding:12px;background:#fafbfc;border-radius:8px;color:#333;line-height:1.6;"><?= nl2br(htmlspecialchars($r['message'])) ?></div>
            <?php endif; ?>
         </div>
      <?php endforeach; ?>
   <?php endif; ?>
</div>
</main></div></body></html>
