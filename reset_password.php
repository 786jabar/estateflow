<?php
include 'components/connect.php';

if (!empty($_SESSION['user_id'])) {
   header('location:dashboard.php');
   exit;
}

$errors  = [];
$success = '';
/* Token comes from the emailed link (GET) or the hidden form field (POST). */
$token   = trim((string)($_POST['token'] ?? $_GET['token'] ?? ''));
$valid   = false;
$reset_row = null;

/* 1) Check the token is real, unused and not expired. */
if ($token !== '' && ctype_xdigit($token) && strlen($token) === 64) {
   try {
      $sel = $conn->prepare(
         "SELECT * FROM `password_resets`
          WHERE token_hash = ? AND used = 0 AND expires_at > NOW()
          ORDER BY created_at DESC LIMIT 1");
      $sel->execute([hash('sha256', $token)]);
      $reset_row = $sel->fetch();
      if ($reset_row) $valid = true;
   } catch (PDOException $e) {
      $errors[] = 'Database error: ' . $e->getMessage();
   }
}

if (!$valid && empty($errors) && $success === '') {
   $errors[] = 'This reset link is invalid or has expired. Please request a new one.';
}

/* 2) Handle the new password form. */
if ($valid && isset($_POST['submit'])) {
   $pass  = (string)($_POST['pass'] ?? '');
   $cpass = (string)($_POST['cpass'] ?? '');

   if (strlen($pass) < 8) {
      $errors[] = 'Password must be at least 8 characters long.';
   } elseif ($pass !== $cpass) {
      $errors[] = 'The two passwords do not match.';
   } else {
      try {
         $upd = $conn->prepare("UPDATE `users` SET password = ? WHERE email = ?");
         $upd->execute([ef_hash_password($pass), $reset_row['email']]);

         /* Mark every outstanding token for this email as used. */
         $done = $conn->prepare("UPDATE `password_resets` SET used = 1 WHERE email = ?");
         $done->execute([$reset_row['email']]);

         $success = 'Your password has been changed. You can now sign in with your new password.';
         $valid   = false; // hide the form
         $errors  = [];
      } catch (PDOException $e) {
         $errors[] = 'Database error: ' . $e->getMessage();
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset password &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
     .ef-alert{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #c0392b;
       background:#fdecea;color:#7a1f17;font-size:1.35rem;line-height:1.5;}
     .ef-alert ul{margin:0;padding-left:1.6rem;}
     .ef-success{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #27ae60;
       background:#eafaf1;color:#14532d;font-size:1.35rem;line-height:1.5;}
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="auth-section">
   <div class="auth-card">
      <h2>Choose a new password</h2>

      <?php if (!empty($errors)): ?>
        <div class="ef-alert">
          <strong>Could not reset your password:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php if (!$valid && $success === ''): ?>
          <p class="auth-foot"><a href="forgot_password.php">Request a new reset link</a></p>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="ef-success"><?= htmlspecialchars($success); ?></div>
        <p class="auth-foot"><a href="login.php" class="btn-primary block" style="text-align:center;">Go to Sign In</a></p>
      <?php endif; ?>

      <?php if ($valid): ?>
      <form action="" method="post" class="auth-form">
         <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">

         <label>New password</label>
         <input type="password" name="pass" required minlength="8" maxlength="80" placeholder="At least 8 characters">

         <label>Confirm new password</label>
         <input type="password" name="cpass" required minlength="8" maxlength="80" placeholder="Type it again">

         <button type="submit" name="submit" class="btn-primary block">Change Password</button>
      </form>
      <?php endif; ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>
</body>
</html>
