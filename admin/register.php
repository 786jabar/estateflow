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
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Admin &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
   <form action="" method="POST" class="auth-form">
      <h3>Add a new admin</h3>
      <p class="muted">Create another administrator for the EstateFlow panel.</p>

      <label>Username</label>
      <input type="text" name="name" required maxlength="30"
             oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="e.g. jane">

      <label>Password <span class="muted">(min. 8 characters)</span></label>
      <input type="password" name="pass" required minlength="8" maxlength="80"
             oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="Choose a strong password">

      <label>Confirm password</label>
      <input type="password" name="c_pass" required minlength="8" maxlength="80"
             oninput="this.value=this.value.replace(/\s/g,'')" class="box" placeholder="Re-enter password">

      <button type="submit" name="submit" class="btn-primary block">Create Admin</button>
   </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/message.php'; ?>
</body>
</html>
