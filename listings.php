<?php
include 'components/connect.php';
$user_id = ef_user_id();
include 'components/save_send.php';

$filter_offer = $_GET['offer'] ?? '';
$filter_type  = $_GET['type'] ?? '';

$sql = "SELECT * FROM `property` WHERE 1=1";
$params = [];
if ($filter_offer !== '') { $sql .= " AND offer = ?"; $params[] = $filter_offer; }
if ($filter_type !== '')  { $sql .= " AND type = ?";  $params[] = $filter_type; }
$sql .= " ORDER BY date DESC";
$sel = $conn->prepare($sql);
$sel->execute($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>All Listings &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">CURATED PORTFOLIO</span>
      <h1>All Listings</h1>
      <p>Browse every property on EstateFlow &mdash; filter by sale, rent or type.</p>
   </div>
</section>

<section class="listings">
   <div class="container">

      <form method="GET" class="filter-bar">
         <select name="offer">
            <option value="">All offers</option>
            <option value="sale"   <?= $filter_offer==='sale'?'selected':''; ?>>For Sale</option>
            <option value="rent"   <?= $filter_offer==='rent'?'selected':''; ?>>For Rent</option>
            <option value="resale" <?= $filter_offer==='resale'?'selected':''; ?>>Resale</option>
         </select>
         <select name="type">
            <option value="">All types</option>
            <option value="house" <?= $filter_type==='house'?'selected':''; ?>>House</option>
            <option value="flat"  <?= $filter_type==='flat'?'selected':''; ?>>Flat</option>
            <option value="shop"  <?= $filter_type==='shop'?'selected':''; ?>>Shop / Commercial</option>
         </select>
         <button type="submit" class="btn-primary"><i class="fas fa-filter"></i>&nbsp; Apply Filters</button>
         <a href="search.php" class="btn-outline" style="margin-left:.8rem;"><i class="fas fa-sliders-h"></i>&nbsp; Advanced Search</a>
         <a href="listings.php" class="btn-outline">Reset</a>
      </form>

      <div class="prop-grid">
      <?php if ($sel->rowCount() > 0):
         while ($p = $sel->fetch()):
            $u = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $u->execute([$p['user_id']]);
            $seller = $u->fetch();
            $is_saved = false;
            if ($user_id) {
               $sv = $conn->prepare("SELECT id FROM `saved` WHERE property_id = ? AND user_id = ?");
               $sv->execute([$p['id'], $user_id]);
               $is_saved = $sv->rowCount() > 0;
            }
      ?>
         <form action="" method="POST" class="prop-card">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
            <div class="prop-media">
               <button type="submit" name="save" class="save-btn" title="<?= $is_saved?'Saved':'Save'; ?>">
                  <i class="<?= $is_saved?'fas':'far'; ?> fa-heart"></i>
               </button>
               <img src="uploaded_files/<?= htmlspecialchars($p['image_01'] ?? ''); ?>" alt="<?= htmlspecialchars($p['property_name']); ?>">
               <span class="prop-badge"><?= ucfirst(htmlspecialchars($p['offer'])); ?></span>
            </div>
            <div class="prop-body">
               <div class="prop-price">A$<?= number_format((float)$p['price']); ?></div>
               <h3 class="prop-name"><?= htmlspecialchars($p['property_name']); ?></h3>
               <p class="prop-loc"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['address']); ?></p>
               <div class="prop-meta">
                  <span><i class="fas fa-bed"></i> <?= (int)$p['bedroom']; ?> bed</span>
                  <span><i class="fas fa-bath"></i> <?= (int)$p['bathroom']; ?> bath</span>
                  <span><i class="fas fa-ruler-combined"></i> <?= (int)$p['carpet']; ?> m²</span>
               </div>
               <div class="prop-actions">
                  <a href="view_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="btn-outline">View</a>
                  <button type="submit" name="send" class="btn-primary">Enquire</button>
               </div>
            </div>
         </form>
      <?php endwhile; else: ?>
         <p class="empty-state">No properties match your filters. <a href="listings.php">Show all &rarr;</a></p>
      <?php endif; ?>
      </div>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<?php include 'components/message.php'; ?>
</body>
</html>
