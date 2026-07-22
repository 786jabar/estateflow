<?php
include 'components/connect.php';

$user_id = ef_user_id();
$errors = [];
$ok_msg = '';

if (isset($_POST['submit'])) {
   $id     = create_unique_id();
   $name   = trim((string)($_POST['name']   ?? ''));
   $number = trim((string)($_POST['number'] ?? ''));
   $email  = trim((string)($_POST['email']  ?? ''));
   $pass   = (string)($_POST['pass']   ?? '');
   $c_pass = (string)($_POST['c_pass'] ?? '');

   if ($name === '' || $email === '' || $pass === '') {
      $errors[] = 'Please fill in all required fields.';
   } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address.';
   } elseif (strlen($pass) < 8) {
      $errors[] = 'Password must be at least 8 characters.';
   } elseif ($pass !== $c_pass) {
      $errors[] = 'Passwords do not match.';
   } else {
      try {
         $check = $conn->prepare("SELECT id FROM `users` WHERE email = ? LIMIT 1");
         $check->execute([$email]);
         if ($check->fetch()) {
            $errors[] = 'This email is already registered. Try logging in instead.';
         } else {
            $hash = ef_hash_password($pass);
            $ins  = $conn->prepare(
               "INSERT INTO `users`(id, name, number, email, password) VALUES (?,?,?,?,?)"
            );
            $ins->execute([$id, $name, $number, $email, $hash]);

            session_regenerate_id(true);
            $_SESSION['user_id']   = $id;
            $_SESSION['user_name'] = $name;

            header('location:dashboard.php');
            exit;
         }
      } catch (PDOException $e) {
         $msg = $e->getMessage();
         if (stripos($msg, "Base table or view not found") !== false || stripos($msg, "doesn't exist") !== false) {
            $errors[] = 'Database error: the "users" table does not exist. Please import EstateFlow_FRESH_DATABASE.sql in phpMyAdmin.';
         } elseif (stripos($msg, 'Data too long') !== false) {
            $errors[] = 'Database error: a column is too small. Run this SQL in phpMyAdmin: ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;';
         } elseif (stripos($msg, 'Access denied') !== false) {
            $errors[] = 'Database error: wrong username or password in components/connect.php.';
         } else {
            $errors[] = 'Database error: ' . $msg;
         }
      } catch (Throwable $e) {
         $errors[] = 'Unexpected error: ' . $e->getMessage();
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Create account &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
     .ef-alert{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #c0392b;
       background:#fdecea;color:#7a1f17;font-size:1.35rem;line-height:1.5;}
     .ef-alert.success{border-left-color:#2e8b57;background:#eaf7ef;color:#1e5b39;}
     .ef-alert ul{margin:0;padding-left:1.6rem;}
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="auth-section">
   <div class="auth-card">
      <h2>Create your account</h2>
      <p class="muted">Join EstateFlow to list properties, save favourites and message agents.</p>

      <?php if (!empty($errors)): ?>
        <div class="ef-alert">
          <strong>Could not create your account:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="" method="post" class="auth-form">
         <label>Full name</label>
         <input type="text" name="name" required maxlength="50" placeholder="Jane Smith"
            value="<?= htmlspecialchars($_POST['name'] ?? ''); ?>">

         <label>Email</label>
         <input type="email" name="email" required maxlength="80" placeholder="you@example.com"
            value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">

         <label>Phone</label>
         <input type="tel" name="number" maxlength="20" placeholder="+61 412 345 678"
            value="<?= htmlspecialchars($_POST['number'] ?? ''); ?>">

         <label>Password <span class="muted">(min. 8 characters)</span></label>
         <input type="password" name="pass" required minlength="8" maxlength="80" placeholder="Choose a strong password">

         <label>Confirm password</label>
         <input type="password" name="c_pass" required minlength="8" maxlength="80" placeholder="Re-enter your password">

         <button type="submit" name="submit" class="btn-primary block">Create Account</button>
         <p class="auth-foot" style="margin-top:1.6rem;">Already a member? <a href="login.php">Sign in</a></p>
         <p class="legal-tiny" style="font-size:1.1rem;color:#6b7280;margin-top:1rem;">By creating an account you agree to our
            <a href="terms.php">Terms</a> &amp; <a href="privacy.php">Privacy Policy</a>.</p>
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>
</body>
</html>
