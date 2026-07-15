<?php include 'components/connect.php'; $user_id = ef_user_id(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy &mdash; EstateFlow</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="legal-section">
   <div class="container legal-content">
      <h1>Privacy Policy</h1>
      <p class="muted">Last updated: <?= date('F Y'); ?></p>

      <h3>1. Who we are</h3>
      <p>EstateFlow is the data controller for personal data you provide to estateflow.it.com.
      You can contact us at <a href="mailto:hello@estateflow.it.com">hello@estateflow.it.com</a>.</p>

      <h3>2. What we collect</h3>
      <ul>
         <li>Account details: name, email, phone number, password (stored hashed).</li>
         <li>Listing details and photos you upload.</li>
         <li>Enquiries you send to other users.</li>
         <li>Basic technical data (IP address, browser, pages visited) for security and analytics.</li>
      </ul>

      <h3>3. Why we use it</h3>
      <ul>
         <li>To create and maintain your account.</li>
         <li>To display your listings and enable enquiries.</li>
         <li>To send service emails (account, enquiry alerts, security).</li>
         <li>To detect fraud and abuse.</li>
      </ul>

      <h3>4. Sharing</h3>
      <p>We do not sell your data. We share it only with: (a) other users you explicitly contact
      via the platform, (b) trusted service providers (hosting, email), and (c) authorities when
      legally required.</p>

      <h3>5. Cookies</h3>
      <p>We use a session cookie to keep you signed in and a single preference cookie to remember
      that you have dismissed the cookie banner. No third-party tracking cookies are set by default.</p>

      <h3>6. Your rights (Australian Privacy Act)</h3>
      <p>You may request access to, correction of, or deletion of your personal data at any time
      by emailing <a href="mailto:hello@estateflow.it.com">hello@estateflow.it.com</a>. You may also
      lodge a complaint with the Office of the Australian Information Commissioner (oaic.gov.au).</p>

      <h3>7. Retention</h3>
      <p>We retain your account data while your account is active and for up to 12 months after
      closure for legal and fraud-prevention purposes.</p>

      <h3>8. Security</h3>
      <p>Passwords are stored using bcrypt hashing. Sessions are protected with secure, HttpOnly
      cookies. We strongly recommend you only access the site over HTTPS.</p>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
