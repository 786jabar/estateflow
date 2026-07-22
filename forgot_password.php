<?php
include 'components/connect.php';
include 'components/mailer.php';

/* If already signed in, no need to reset a password here. */
if (!empty($_SESSION['user_id'])) {
   header('location:dashboard.php');
   exit;
}

$errors  = [];
$success = '';
$demo_link = ''; // shown only when the server cannot send email (e.g. localhost)

if (isset($_POST['submit'])) {
   $email = trim((string)($_POST['email'] ?? ''));

   if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address.';
   } else {
      try {
         $sel = $conn->prepare("SELECT id, name FROM `users` WHERE email = ? LIMIT 1");
         $sel->execute([$email]);
         $user = $sel->fetch();

         if ($user) {
            /* Create a one-time token valid for 30 minutes.
               Only the SHA-256 hash of the token is stored, never the token itself. */
            $token = bin2hex(random_bytes(32));
            $ins = $conn->prepare(
               "INSERT INTO `password_resets` (id, email, token_hash, expires_at, used)
                VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), 0)");
            $ins->execute([create_unique_id(), $email, hash('sha256', $token)]);

            /* Local/dev detection: either the ESTATEFLOW_LOCAL env variable is
               set, or the SERVER itself is running on 127.0.0.1 (XAMPP).
               SERVER_ADDR is set by the web server, not by the visitor,
               so it cannot be faked from outside. */
            $is_local = (bool) getenv('ESTATEFLOW_LOCAL')
                     || in_array($_SERVER['SERVER_ADDR'] ?? '', ['127.0.0.1', '::1'], true);

            /* Build the reset link. On the LIVE site we always use a FIXED
               address, never request headers (prevents host-header
               poisoning attacks). Locally we build the XAMPP-style URL. */
            if ($is_local) {
               $base = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
                     . rtrim(dirname($_SERVER['PHP_SELF']), '/');
            } else {
               $base = 'https://estateflow.it.com/project_realstate';
            }
            $link = $base . '/reset_password.php?token=' . $token;

            $subject = 'EstateFlow - Reset your password';
            $body    = "Hi " . $user['name'] . ",\n\n"
                     . "Someone (hopefully you) asked to reset the password for your EstateFlow account.\n\n"
                     . "Click the link below to choose a new password. The link works for 30 minutes:\n\n"
                     . $link . "\n\n"
                     . "If you did not ask for this, you can safely ignore this email.\n\n"
                     . "EstateFlow - Luxury Real Estate, Australia";
            $sent = ef_send_mail($email, $user['name'], $subject, $body);

            /* SECURITY: the reset link is only ever shown on screen when
               running on the local test machine (ESTATEFLOW_LOCAL=1).
               On the live server the link goes out by email ONLY -
               otherwise anyone could type a victim's email and take
               over their account. */
            if (!$sent && $is_local) {
               $demo_link = $link;
            }
         }

         /* Always show the same message so nobody can use this page
            to find out which emails are registered. */
         $success = 'If that email address is registered, a password reset link has been sent to it. Please check your inbox (and spam folder).';
      } catch (PDOException $e) {
         $msg = $e->getMessage();
         if (stripos($msg, "doesn't exist") !== false || stripos($msg, "Base table or view not found") !== false) {
            $errors[] = 'Database error: the "password_resets" table does not exist. Please run the CREATE TABLE statement from DATABASE_SCHEMA.sql in phpMyAdmin.';
         } else {
            error_log('EstateFlow forgot_password error: ' . $msg);
            $errors[] = 'Sorry, something went wrong on our side. Please try again in a few minutes.';
         }
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot password &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
     .ef-alert{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #c0392b;
       background:#fdecea;color:#7a1f17;font-size:1.35rem;line-height:1.5;}
     .ef-alert ul{margin:0;padding-left:1.6rem;}
     .ef-success{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #27ae60;
       background:#eafaf1;color:#14532d;font-size:1.35rem;line-height:1.5;}
     .ef-demo{padding:1.4rem 1.6rem;margin-bottom:1.6rem;border-left:4px solid #b8935a;
       background:#fbf3e4;color:#5b4324;font-size:1.3rem;line-height:1.5;word-break:break-all;}
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="auth-section">
   <div class="auth-card">
      <h2>Forgot your password?</h2>
      <p class="muted">Enter the email address you registered with and we will send you a link to choose a new password.</p>

      <?php if (!empty($errors)): ?>
        <div class="ef-alert">
          <strong>Something went wrong:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="ef-success"><?= htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <?php if ($demo_link !== ''): ?>
        <div class="ef-demo">
          <strong>Email could not be sent from this server</strong> (this usually happens on a local
          test machine). You can still continue by opening the reset link directly:<br><br>
          <a href="<?= htmlspecialchars($demo_link); ?>"><?= htmlspecialchars($demo_link); ?></a>
        </div>
      <?php endif; ?>

      <form action="" method="post" class="auth-form">
         <label>Email</label>
         <input type="email" name="email" required maxlength="80" placeholder="you@example.com"
            value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">

         <button type="submit" name="submit" class="btn-primary block">Send Reset Link</button>
         <p class="auth-foot" style="margin-top:1.6rem;">Remembered it? <a href="login.php">Back to sign in</a></p>
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>
</body>
</html>
