<?php
include 'components/connect.php';
ef_require_login();
$user_id = ef_user_id();

$me = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$me->execute([$user_id]);
$user = $me->fetch();

$myProps = $conn->prepare("SELECT * FROM `property` WHERE user_id = ? ORDER BY date DESC");
$myProps->execute([$user_id]);
$myCount = $myProps->rowCount();

$savedCount = $conn->prepare("SELECT COUNT(*) c FROM `saved` WHERE user_id = ?");
$savedCount->execute([$user_id]);
$saved_n = (int)$savedCount->fetch()['c'];

$enq = $conn->prepare("SELECT r.*, p.property_name FROM `requests` r JOIN `property` p ON r.property_id = p.id WHERE r.receiver = ? ORDER BY r.date DESC LIMIT 20");
$enq->execute([$user_id]);

if (isset($_POST['delete_property'])) {
   $pid = $_POST['property_id'] ?? '';
   $del = $conn->prepare("DELETE FROM `property` WHERE id = ? AND user_id = ?");
   $del->execute([$pid, $user_id]);
   header("Location: dashboard.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">WELCOME BACK</span>
      <h1>Hello, <em><?= htmlspecialchars($user['name'] ?? 'there'); ?></em></h1>
      <p><?= htmlspecialchars($user['email'] ?? ''); ?> &middot; <?= htmlspecialchars($user['number'] ?? ''); ?></p>
   </div>
</section>

<section class="listings">
   <div class="container">

      <div class="stat-row">
         <a href="#my-listings" class="stat-card" style="text-decoration:none;color:inherit;cursor:pointer;"><div class="stat-num"><?= $myCount; ?></div><div>Your listings &rarr;</div></a>
         <a href="saved.php" class="stat-card" style="text-decoration:none;color:inherit;cursor:pointer;"><div class="stat-num"><?= $saved_n; ?></div><div>Saved &rarr;</div></a>
         <a href="#my-enquiries" class="stat-card" style="text-decoration:none;color:inherit;cursor:pointer;"><div class="stat-num"><?= $enq->rowCount(); ?></div><div>Enquiries received &rarr;</div></a>
      </div>

      <div style="margin:2rem 0;">
         <a href="post_property.php" class="btn-accent"><i class="fas fa-paper-plane"></i> Post New Property</a>
         <a href="saved.php" class="btn-outline">View Saved</a>
      </div>

      <h2 class="section-heading" id="my-listings">Your Listings</h2>
      <div class="prop-grid">
      <?php if ($myCount > 0):
         while ($p = $myProps->fetch()): ?>
         <div class="prop-card">
            <div class="prop-media">
               <img src="uploaded_files/<?= htmlspecialchars($p['image_01'] ?? ''); ?>" alt="">
               <span class="prop-badge"><?= ucfirst(htmlspecialchars($p['offer'])); ?></span>
            </div>
            <div class="prop-body">
               <div class="prop-price">A$<?= number_format((float)$p['price']); ?></div>
               <h3 class="prop-name"><?= htmlspecialchars($p['property_name']); ?></h3>
               <p class="prop-loc"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['address']); ?></p>
               <div class="prop-actions">
                  <a href="view_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="btn-outline">View</a>
                  <a href="edit_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="btn-accent"><i class="fas fa-pen"></i> Edit</a>
                  <form method="POST" style="display:inline" onsubmit="return confirm('Delete this listing permanently?');">
                     <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
                     <button type="submit" name="delete_property" class="btn-primary" style="background:#b00020">Delete</button>
                  </form>
               </div>
            </div>
         </div>
      <?php endwhile; else: ?>
         <p class="empty-state">You haven't posted any properties yet. <a href="post_property.php" class="btn-primary">Post your first &rarr;</a></p>
      <?php endif; ?>
      </div>

      <h2 class="section-heading" id="my-enquiries" style="margin-top:3rem;">Recent Enquiries</h2>
      <?php if ($enq->rowCount() > 0): ?>
      <table class="data-table">
         <thead><tr><th>Property</th><th>From buyer</th><th>Date</th></tr></thead>
         <tbody>
         <?php while ($r = $enq->fetch()):
            $b = $conn->prepare("SELECT name, email, number FROM `users` WHERE id = ?");
            $b->execute([$r['sender']]); $buyer = $b->fetch(); ?>
            <tr>
               <td><?= htmlspecialchars($r['property_name']); ?></td>
               <td>
                  <strong><?= htmlspecialchars($buyer['name'] ?? 'Unknown'); ?></strong><br>
                  <small><?= htmlspecialchars($buyer['email'] ?? ''); ?> &middot; <?= htmlspecialchars($buyer['number'] ?? ''); ?></small>
               </td>
               <td><?= htmlspecialchars($r['date']); ?></td>
            </tr>
         <?php endwhile; ?>
         </tbody>
      </table>
      <?php else: ?>
         <p class="empty-state">No enquiries yet.</p>
      <?php endif; ?>

   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<?php include 'components/message.php'; ?>
</body>
</html>
