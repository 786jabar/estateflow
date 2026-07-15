<?php
include 'components/connect.php';
$user_id = ef_user_id();
include 'components/save_send.php';

$pid = $_GET['get_id'] ?? '';
$sel = $conn->prepare("SELECT * FROM `property` WHERE id = ? LIMIT 1");
$sel->execute([$pid]);
$p = $sel->fetch();

if (!$p) { header("Location: listings.php"); exit; }

$u = $conn->prepare("SELECT name, email, number FROM `users` WHERE id = ?");
$u->execute([$p['user_id']]);
$seller = $u->fetch();

$is_saved = false;
if ($user_id) {
   $sv = $conn->prepare("SELECT id FROM `saved` WHERE property_id = ? AND user_id = ?");
   $sv->execute([$p['id'], $user_id]);
   $is_saved = $sv->rowCount() > 0;
}

$images = array_filter([$p['image_01'], $p['image_02'], $p['image_03'], $p['image_04'], $p['image_05']]);

$amenities = [];
foreach (['lift'=>'Lift','security_guard'=>'Security','play_ground'=>'Playground','garden'=>'Garden','water_supply'=>'Water Supply','power_backup'=>'Power Backup','parking_area'=>'Parking','gym'=>'Gym','shopping_mall'=>'Shopping','hospital'=>'Hospital','school'=>'School','market_area'=>'Market'] as $k => $label) {
   if (($p[$k] ?? 'no') === 'yes') $amenities[] = $label;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($p['property_name']); ?> &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="property-detail">
   <div class="container">

      <div class="property-gallery">
         <img src="uploaded_files/<?= htmlspecialchars($p['image_01'] ?? ''); ?>" alt="" class="gallery-main">
         <div class="gallery-thumbs">
         <?php foreach ($images as $img): ?>
            <img src="uploaded_files/<?= htmlspecialchars($img); ?>" alt="" onclick="document.querySelector('.gallery-main').src=this.src">
         <?php endforeach; ?>
         </div>
      </div>

      <div class="property-info">
         <span class="prop-badge"><?= ucfirst(htmlspecialchars($p['offer'])); ?></span>
         <h1><?= htmlspecialchars($p['property_name']); ?></h1>
         <p class="prop-loc"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['address']); ?></p>
         <div class="prop-price" style="font-size:2.5rem;">A$<?= number_format((float)$p['price']); ?></div>
         <?php if ((int)$p['deposite'] > 0): ?>
            <p class="muted">Deposit: A$<?= number_format((float)$p['deposite']); ?></p>
         <?php endif; ?>

         <div class="prop-meta">
            <span><i class="fas fa-bed"></i> <?= (int)$p['bedroom']; ?> bed</span>
            <span><i class="fas fa-bath"></i> <?= (int)$p['bathroom']; ?> bath</span>
            <span><i class="fas fa-door-open"></i> <?= htmlspecialchars($p['bhk']); ?> BHK</span>
            <span><i class="fas fa-ruler-combined"></i> <?= (int)$p['carpet']; ?> m²</span>
            <span><i class="fas fa-couch"></i> <?= htmlspecialchars($p['furnished']); ?></span>
            <span><i class="fas fa-building"></i> Floor <?= (int)$p['room_floor']; ?>/<?= (int)$p['total_floors']; ?></span>
            <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($p['age']); ?> old</span>
         </div>

         <h2 class="section-heading">Description</h2>
         <p><?= nl2br(htmlspecialchars($p['description'] ?? '')); ?></p>

         <?php if (!empty($amenities)): ?>
         <h2 class="section-heading">Amenities</h2>
         <div class="amenity-grid">
            <?php foreach ($amenities as $a): ?>
               <span class="amenity"><i class="fas fa-check"></i> <?= $a; ?></span>
            <?php endforeach; ?>
         </div>
         <?php endif; ?>

         <h2 class="section-heading">Seller</h2>
         <div class="seller-card">
            <span class="avatar"><?= strtoupper(substr($seller['name'] ?? '?', 0, 1)); ?></span>
            <div>
               <strong><?= htmlspecialchars($seller['name'] ?? 'Seller'); ?></strong><br>
               <?php if ($user_id): ?>
                  <small><i class="fas fa-envelope"></i> <?= htmlspecialchars($seller['email'] ?? ''); ?></small><br>
                  <small><i class="fas fa-phone"></i> <?= htmlspecialchars($seller['number'] ?? ''); ?></small>
               <?php else: ?>
                  <small><a href="login.php">Log in</a> to see contact details</small>
               <?php endif; ?>
            </div>
         </div>

         <form method="POST" class="action-form">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
            <button type="submit" name="save" class="btn-outline lg">
               <i class="<?= $is_saved?'fas':'far'; ?> fa-heart"></i> <?= $is_saved ? 'Saved' : 'Save'; ?>
            </button>
            <button type="submit" name="send" class="btn-primary lg">
               <i class="fas fa-paper-plane"></i> Send Enquiry
            </button>
         </form>
      </div>

   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<?php include 'components/message.php'; ?>
</body>
</html>
