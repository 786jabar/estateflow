<?php
include 'components/connect.php';

/* If already signed in, go straight to the dashboard. */
if (!empty($_SESSION['user_id'])) {
   header('location:dashboard.php');
   exit;
}

$errors = [];

if (isset($_POST['submit'])) {
   $email = trim((string)($_POST['email'] ?? ''));
   $pass  = (string)($_POST['pass'] ?? '');

   if ($email === '' || $pass === '') {
      $errors[] = 'Please enter your email and password.';
   } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address.';
   } else {
      try {
         $sel = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
         $sel->execute([$email]);
         $user = $sel->fetch();

         if ($user && ef_verify_password($pass, $user['password'])) {
            /* Upgrade old (legacy) password hashes to bcrypt on a good login. */
            if (ef_needs_rehash($user['password'])) {
               $upd = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
               $upd->execute([ef_hash_password($pass), $user['id']]);
            }
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('location:dashboard.php');
            exit;
         } else {
            $errors[] = 'Incorrect email or password. Please try again.';
         }
      } catch (PDOException $e) {
         $msg = $e->getMessage();
         if (stripos($msg, "doesn't exist") !== false || stripos($msg, "Base table or view not found") !== false) {
            $errors[] = 'Database error: the "users" table does not exist. Please import your database SQL in phpMyAdmin.';
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
   <title>Sign in &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
     .ef-alert{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #c0392b;
       background:#fdecea;color:#7a1f17;font-size:1.35rem;line-height:1.5;}
     .ef-alert ul{margin:0;padding-left:1.6rem;}
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="auth-section">
   <div class="auth-card">
      <h2>Welcome back</h2>
      <p class="muted">Sign in to your EstateFlow account to list properties, save favourites and message agents.</p>

      <?php if (!empty($errors)): ?>
        <div class="ef-alert">
          <strong>Could not sign you in:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="" method="post" class="auth-form">
         <label>Email</label>
         <input type="email" name="email" required maxlength="80" placeholder="you@example.com"
            value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">

         <label>Password</label>
         <input type="password" name="pass" required maxlength="80" placeholder="Enter your password">

         <button type="submit" name="submit" class="btn-primary block">Sign In</button>
         <p class="auth-foot" style="margin-top:1.6rem;"><a href="forgot_password.php">Forgot your password?</a></p>
         <p class="auth-foot" style="margin-top:0.6rem;">New to EstateFlow? <a href="register.php">Create an account</a></p>
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>
</body>
</html>
