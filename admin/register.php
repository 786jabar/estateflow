<?php
include '../components/connect.php';

/* Allow creating the FIRST admin when the admins table is empty.
   After that, only a logged-in admin can add more admins. */
$admin_count = (int)$conn->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
if ($admin_count > 0 && empty($_SESSION['admin_id'])) { header('location:login.php'); exit; }
$admin_id = $_SESSION['admin_id'] ?? '';

if (isset($_POST['submit'])) {
   $id     = create_unique_id();
   $name   = trim((string)($_POST['name']   ?? ''));
   $pass   = (string)($_POST['pass']   ?? '');
   $c_pass = (string)($_POST['c_pass'] ?? '');

   if ($name === '' || $pass === '') {
      $warning_msg[] = 'Please fill in all fields.';
   } elseif (strlen($pass) < 8) {
      $warning_msg[] = 'Password must be at least 8 characters.';
   } elseif ($pass !== $c_pass) {
      $warning_msg[] = 'Passwords do not match.';
   } else {
      $chk = $conn->prepare("SELECT id FROM `admins` WHERE name = ? LIMIT 1");
      $chk->execute([$name]);
      if ($chk->fetch()) {
         $warning_msg[] = 'Username already taken.';
      } else {
         $ins = $conn->prepare("INSERT INTO `admins`(id, name, password) VALUES (?,?,?)");
         $ins->execute([$id, $name, ef_hash_password($pass)]);
         $success_msg[] = 'New admin registered successfully.';
      }
   }
}
$admin_name = 'Admin';
try {
   $a = $conn->prepare("SELECT name FROM `admins` WHERE id = ? LIMIT 1");
   $a->execute([$admin_id]);
   $r = $a->fetch(PDO::FETCH_ASSOC);
   if ($r) $admin_name = $r['name'];
} catch (Exception $e) {}

$ef_page_title = 'Add Admin'; include '_layout_top.php';
?>
<div class="card form-narrow">
   <h2>Add a new admin</h2>
   <p class="muted-note" style="margin:0 0 20px;">Create another administrator for the EstateFlow panel.</p>

   <form action="" method="POST">
      <div class="field">
         <label>Username</label>
         <input type="text" name="name" required maxlength="30"
                oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="e.g. jane">
      </div>
      <div class="field">
         <label>Password (min. 8 characters)</label>
         <input type="password" name="pass" required minlength="8" maxlength="80"
                oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="Choose a strong password">
      </div>
      <div class="field">
         <label>Confirm password</label>
         <input type="password" name="c_pass" required minlength="8" maxlength="80"
                oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="Re-enter password">
      </div>
      <button type="submit" name="submit" class="btn btn-dark">Create Admin</button>
   </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../components/message.php'; ?>
</main></div></body></html>
