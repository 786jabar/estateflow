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
   try{ $conn->prepare("DELETE FROM property WHERE id=?")->execute([$_GET['delete']]); $ok='Listing deleted.'; }
   catch(Exception $e){ try{ $conn->prepare("DELETE FROM properties WHERE id=?")->execute([$_GET['delete']]); $ok='Listing deleted.'; }catch(Exception $e2){ $err=$e2->getMessage(); } }
}

$rows=[]; $cols=[];
try{ $s=$conn->prepare("SELECT * FROM property ORDER BY id DESC"); $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC); }catch(Exception $e){}
if(!$rows){ try{ $s=$conn->prepare("SELECT * FROM properties ORDER BY id DESC"); $s->execute(); $rows=$s->fetchAll(PDO::FETCH_ASSOC); }catch(Exception $e){} }
if($rows) $cols=array_keys($rows[0]);

$ef_page_title='Listings'; include '_layout_top.php';
?>
<div class="card">
   <h2>All Properties (<?= count($rows) ?>)</h2>
   <?php if($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
   <?php if(!$rows): ?>
      <div class="empty"><i class="fas fa-building"></i><p>No properties yet.</p></div>
   <?php else: ?>
   <div style="overflow-x:auto;"><table>
      <thead><tr>
         <?php $show=array_slice($cols,0,6); foreach($show as $c): ?><th><?= htmlspecialchars($c) ?></th><?php endforeach; ?>
         <th>Action</th>
      </tr></thead>
      <tbody>
      <?php foreach($rows as $row): ?>
         <tr>
            <?php foreach($show as $c): $v=$row[$c]; $v=is_string($v)?(strlen($v)>40?substr($v,0,40).'…':$v):$v; ?>
               <td><?= htmlspecialchars($v ?? '') ?></td>
            <?php endforeach; ?>
            <td><a href="?delete=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?');"><i class="fas fa-trash"></i></a></td>
         </tr>
      <?php endforeach; ?>
      </tbody></table></div>
   <?php endif; ?>
</div>
</main></div></body></html>
