<?php
include 'components/connect.php';
$user_id = ef_user_id();
$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_send'])) {
   $sent = true; // For now just confirm receipt; integrate email later
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">GET IN TOUCH</span>
      <h1>Contact <em>Us</em></h1>
      <p>Questions, enquiries, partnerships &mdash; we're here Monday to Saturday, 9:00&ndash;19:00 AEST.</p>
   </div>
</section>

<section class="listings">
   <div class="container contact-grid">

      <div>
         <h2 class="section-heading">Reach Us</h2>
         <ul class="contact-list">
            <li><i class="fas fa-phone"></i> <a href="tel:+61279460123">+61 2 7946 0123</a></li>
            <li><i class="fas fa-envelope"></i> <a href="mailto:hello@estateflow.it.com">hello@estateflow.it.com</a></li>
            <li><i class="fas fa-map-marker-alt"></i> 24 Harbour Lane, Sydney NSW 2000, Australia</li>
            <li><i class="fas fa-clock"></i> Mon&ndash;Sat, 9:00&ndash;19:00 AEST</li>
         </ul>

         <h2 class="section-heading" id="faq" style="margin-top:2rem;">FAQ</h2>
         <details class="faq"><summary>Is posting a property free?</summary><p>Yes &mdash; basic listings are free for verified users. Premium placement is available.</p></details>
         <details class="faq"><summary>How do I contact a seller?</summary><p>Open any listing and tap <strong>Enquire</strong>. The seller receives your details in their dashboard.</p></details>
         <details class="faq"><summary>Are listings verified?</summary><p>Every listing is reviewed before going live. We never tolerate fake or duplicate posts.</p></details>
      </div>

      <div>
         <h2 class="section-heading">Send a Message</h2>
         <?php if ($sent): ?>
            <div class="alert-success">Thanks &mdash; we'll be in touch within one business day.</div>
         <?php endif; ?>
         <form method="POST" class="contact-form">
            <div class="field"><label>Your name</label><input type="text" name="c_name" required></div>
            <div class="field"><label>Email</label><input type="email" name="c_email" required></div>
            <div class="field"><label>Phone</label><input type="text" name="c_phone"></div>
            <div class="field"><label>Message</label><textarea name="c_message" rows="5" required></textarea></div>
            <button type="submit" name="contact_send" class="btn-primary"><i class="fas fa-paper-plane"></i>&nbsp; Send Message</button>
         </form>
      </div>

   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
</body>
</html>
