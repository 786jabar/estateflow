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
if(isset($_POST['submit'])){
   $new_name = trim($_POST['name'] ?? '');
   $old_pass = $_POST['old_pass'] ?? '';
   $new_pass = $_POST['new_pass'] ?? '';
   $conf_pass = $_POST['conf_pass'] ?? '';
   try{
      // verify old password
      $s=$conn->prepare("SELECT password FROM admins WHERE id=?"); $s->execute([$admin_id]);
      $row=$s->fetch(PDO::FETCH_ASSOC);
      if(!$row){ $err='Account not found.'; }
      elseif(sha1($old_pass) !== $row['password']){ $err='Current password is incorrect.'; }
      else{
         if($new_name!=='' && $new_name!==$admin_name){
            $conn->prepare("UPDATE admins SET name=? WHERE id=?")->execute([$new_name, $admin_id]);
            $_SESSION['admin_name'] = $new_name; $admin_name=$new_name;
            $ok='Username updated.';
         }
         if($new_pass!==''){
            if($new_pass!==$conf_pass){ $err='New passwords do not match.'; }
            else{
               $conn->prepare("UPDATE admins SET password=? WHERE id=?")->execute([sha1($new_pass), $admin_id]);
               $ok=trim($ok.' Password updated.');
            }
         }
         if($ok==='' && $err==='') $ok='Nothing to update.';
      }
   }catch(Exception $e){ $err=$e->getMessage(); }
}

$ef_page_title='Update Account'; include '_layout_top.php';
?>
<div class="card" style="max-width:560px;">
   <h2>Update Your Admin Account</h2>
   <?php if($ok): ?><div class="msg ok"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ok) ?></div><?php endif; ?>
   <?php if($err): ?><div class="msg err"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div><?php endif; ?>

   <form method="POST">
      <div class="field"><label>Username</label>
         <input type="text" name="name" value="<?= htmlspecialchars($admin_name) ?>" class="box" maxlength="20" required oninput="this.value=this.value.replace(/\s/g,'')"></div>
      <div class="field"><label>Current Password <span style="color:#dc3545;">*</span></label>
         <input type="password" name="old_pass" class="box" placeholder="Required to save changes" maxlength="20" required></div>
      <div class="field"><label>New Password <small style="color:#999;">(leave blank to keep current)</small></label>
         <input type="password" name="new_pass" class="box" maxlength="20" oninput="this.value=this.value.replace(/\s/g,'')"></div>
      <div class="field"><label>Confirm New Password</label>
         <input type="password" name="conf_pass" class="box" maxlength="20" oninput="this.value=this.value.replace(/\s/g,'')"></div>
      <button type="submit" name="submit" class="btn"><i class="fas fa-save"></i> Save Changes</button>
   </form>
</div>
</main></div></body></html>
