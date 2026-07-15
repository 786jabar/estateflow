<?php
include 'components/connect.php';
ef_require_login();
$user_id = ef_user_id();
include 'components/save_send.php';

$sel = $conn->prepare("SELECT p.* FROM `saved` s JOIN `property` p ON s.property_id = p.id WHERE s.user_id = ? ORDER BY p.date DESC");
$sel->execute([$user_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Saved Properties &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">YOUR FAVOURITES</span>
      <h1>Saved Properties</h1>
      <p>Listings you've bookmarked &mdash; ready when you are.</p>
   </div>
</section>

<section class="listings">
   <div class="container">
      <div class="prop-grid">
      <?php if ($sel->rowCount() > 0):
         while ($p = $sel->fetch()): ?>
         <form action="" method="POST" class="prop-card">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
            <div class="prop-media">
               <button type="submit" name="save" class="save-btn" title="Remove"><i class="fas fa-heart"></i></button>
               <img src="uploaded_files/<?= htmlspecialchars($p['image_01'] ?? ''); ?>" alt="<?= htmlspecialchars($p['property_name']); ?>">
               <span class="prop-badge"><?= ucfirst(htmlspecialchars($p['offer'])); ?></span>
            </div>
            <div class="prop-body">
               <div class="prop-price">A$<?= number_format((float)$p['price']); ?></div>
               <h3 class="prop-name"><?= htmlspecialchars($p['property_name']); ?></h3>
               <p class="prop-loc"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['address']); ?></p>
               <div class="prop-actions">
                  <a href="view_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="btn-outline">View</a>
                  <button type="submit" name="send" class="btn-primary">Enquire</button>
               </div>
            </div>
         </form>
      <?php endwhile; else: ?>
         <p class="empty-state">No saved properties yet. <a href="listings.php" class="btn-primary">Browse listings &rarr;</a></p>
      <?php endif; ?>
      </div>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<?php include 'components/message.php'; ?>
</body>
</html>
