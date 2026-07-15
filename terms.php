<?php include 'components/connect.php'; $user_id = ef_user_id(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terms of Service &mdash; EstateFlow</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="legal-section">
   <div class="container legal-content">
      <h1>Terms of Service</h1>
      <p class="muted">Last updated: <?= date('F Y'); ?></p>

      <h3>1. About EstateFlow</h3>
      <p>EstateFlow ("we", "us") is an online property listings platform that connects buyers,
      sellers, landlords and tenants of residential and commercial property in Australia.</p>

      <h3>2. Your account</h3>
      <p>You must be 18 or over and provide accurate information when registering. You are responsible
      for keeping your password secure and for any activity carried out from your account.</p>

      <h3>3. Listings</h3>
      <p>You may only list property that you own or are legally authorised to market. All listings
      must be accurate, lawful and free of misleading information. EstateFlow reserves the right
      to edit, hide or remove any listing at our sole discretion.</p>

      <h3>4. Fees</h3>
      <p>Basic listings are free. Optional paid services (such as Featured placement) are billed
      in advance and are non-refundable once the placement has begun.</p>

      <h3>5. Transactions</h3>
      <p>EstateFlow is a listings platform only. We are not party to any sale, lease or tenancy
      agreement formed between users. Buyers and sellers are responsible for completing any
      transaction through their own solicitors and in accordance with applicable law.</p>

      <h3>6. Prohibited use</h3>
      <p>You must not use the platform to post fraudulent, unlawful, abusive or infringing content,
      to attempt to gain unauthorised access to other accounts, or to scrape or resell our data.</p>

      <h3>7. Liability</h3>
      <p>To the maximum extent permitted by law, EstateFlow is not liable for any indirect or
      consequential loss arising from your use of the platform.</p>

      <h3>8. Changes</h3>
      <p>We may update these Terms from time to time. Material changes will be notified by email
      or via a notice on the site.</p>

      <h3>9. Contact</h3>
      <p>Questions? Email <a href="mailto:hello@estateflow.it.com">hello@estateflow.it.com</a>.</p>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
