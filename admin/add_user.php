<?php
/* =================================================================
   EstateFlow Admin — Create a new USER PROFILE on someone's behalf
   ================================================================= */
include '../components/connect.php';

if (empty($_SESSION['admin_id'])) { header('location:login.php'); exit; }
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['submit'])) {
   $id     = create_unique_id();
   $name   = trim((string)($_POST['name']   ?? ''));
   $number = trim((string)($_POST['number'] ?? ''));
   $email  = trim((string)($_POST['email']  ?? ''));
   $pass   = (string)($_POST['pass']   ?? '');
   $c_pass = (string)($_POST['c_pass'] ?? '');

   if ($name === '' || $email === '' || $pass === '') {
      $warning_msg[] = 'Please fill in name, email and password.';
   } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $warning_msg[] = 'Please enter a valid email address.';
   } elseif (strlen($pass) < 8) {
      $warning_msg[] = 'Password must be at least 8 characters.';
   } elseif ($pass !== $c_pass) {
      $warning_msg[] = 'Passwords do not match.';
   } else {
      $check = $conn->prepare("SELECT id FROM `users` WHERE email = ? LIMIT 1");
      $check->execute([$email]);
      if ($check->fetch()) {
         $warning_msg[] = 'A user with that email already exists.';
      } else {
         $ins = $conn->prepare(
            "INSERT INTO `users`(id, name, number, email, password) VALUES (?,?,?,?,?)"
         );
         $ins->execute([$id, $name, $number, $email, ef_hash_password($pass)]);
         $success_msg[] = 'New user profile created successfully!';
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

$ef_page_title = 'Add User'; include '_layout_top.php';
?>
<div class="card form-narrow">
   <h2>Add a new user profile</h2>
   <p class="muted-note" style="margin:0 0 20px;">Create an EstateFlow account on behalf of a buyer, seller, agent or tenant.
      Share the email and password with them so they can sign in.</p>

   <form action="" method="POST" autocomplete="off">
      <div class="field">
         <label>Full name *</label>
         <input type="text" name="name" required maxlength="50" class="box" placeholder="Jane Smith">
      </div>
      <div class="field">
         <label>Email *</label>
         <input type="email" name="email" required maxlength="80" class="box" placeholder="jane@example.com">
      </div>
      <div class="field">
         <label>Phone</label>
         <input type="tel" name="number" maxlength="15" class="box" placeholder="+61 412 345 678">
      </div>
      <div class="field">
         <label>Password * (min. 8 characters)</label>
         <input type="text" name="pass" required minlength="8" maxlength="80" class="box"
                placeholder="Set an initial password">
      </div>
      <div class="field">
         <label>Confirm password *</label>
         <input type="text" name="c_pass" required minlength="8" maxlength="80" class="box"
                placeholder="Re-enter the password">
      </div>

      <button type="submit" name="submit" class="btn btn-dark">
         <i class="fas fa-user-plus"></i>&nbsp; Create User Profile
      </button>
      <a href="users.php" class="btn" style="margin-left:8px;">&laquo; Back to user list</a>

      <p class="muted-note">Tip: passwords are shown in plain text on this admin form so you can copy
         them. They are stored hashed in the database (bcrypt) and never visible again.</p>
   </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../components/message.php'; ?>
</main></div></body></html>
