<?php
include 'components/connect.php';
$user_id = ef_user_id();
include 'components/save_send.php';

$loc   = trim($_POST['h_location'] ?? $_GET['h_location'] ?? '');
$kw    = trim($_POST['h_keyword']  ?? $_GET['h_keyword']  ?? '');
$type  = trim($_POST['h_type']     ?? $_GET['h_type']     ?? '');
$offer = trim($_POST['h_offer']    ?? $_GET['h_offer']    ?? '');
$min   = (int)($_POST['h_min']     ?? $_GET['h_min']     ?? 0);
$max   = (int)($_POST['h_max']     ?? $_GET['h_max']     ?? 0);
$beds  = (int)($_POST['h_beds']    ?? $_GET['h_beds']    ?? 0);
$baths = (int)($_POST['h_baths']   ?? $_GET['h_baths']   ?? 0);
$furn  = trim($_POST['h_furnished'] ?? $_GET['h_furnished'] ?? '');
$sort  = trim($_POST['h_sort']     ?? $_GET['h_sort']     ?? 'newest');

$sql = "SELECT * FROM `property` WHERE 1=1";
$params = [];
if ($loc !== '')   { $sql .= " AND address LIKE ?"; $params[] = "%$loc%"; }
if ($kw !== '')    { $sql .= " AND (property_name LIKE ? OR description LIKE ? OR address LIKE ?)";
                     $params[] = "%$kw%"; $params[] = "%$kw%"; $params[] = "%$kw%"; }
if ($type !== '')  { $sql .= " AND type = ?";       $params[] = $type; }
if ($offer !== '') { $sql .= " AND offer = ?";      $params[] = $offer; }
if ($min > 0)      { $sql .= " AND price >= ?";     $params[] = $min; }
if ($max > 0)      { $sql .= " AND price <= ?";     $params[] = $max; }
if ($beds > 0)     { $sql .= " AND bedroom >= ?";   $params[] = $beds; }
if ($baths > 0)    { $sql .= " AND bathroom >= ?";  $params[] = $baths; }
if ($furn !== '')  { $sql .= " AND furnished = ?";  $params[] = $furn; }
switch ($sort) {
   case 'price_low':  $sql .= " ORDER BY price ASC";  break;
   case 'price_high': $sql .= " ORDER BY price DESC"; break;
   case 'beds':       $sql .= " ORDER BY bedroom DESC, date DESC"; break;
   default:           $sort = 'newest'; $sql .= " ORDER BY date DESC";
}
$sel = $conn->prepare($sql);
$sel->execute($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search &mdash; EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="page-hero">
   <div class="container">
      <span class="eyebrow">FIND YOUR HOME</span>
      <h1>Advanced Search</h1>
   </div>
</section>

<section class="listings">
   <div class="container">

      <form method="POST" class="search-card" style="margin-bottom:2.5rem;">
         <div class="search-row" style="margin-bottom:1rem;">
            <div class="field" style="flex:2;"><label>Keyword</label>
               <input type="text" name="h_keyword" value="<?= htmlspecialchars($kw); ?>" placeholder="e.g. pool, penthouse, riverside">
            </div>
            <div class="field" style="flex:2;"><label>Location</label>
               <input type="text" name="h_location" value="<?= htmlspecialchars($loc); ?>" placeholder="e.g. Bondi, Toorak">
            </div>
            <div class="field"><label>Type</label>
               <select name="h_type">
                  <option value="">Any</option>
                  <option value="house" <?= $type==='house'?'selected':''; ?>>House</option>
                  <option value="flat"  <?= $type==='flat'?'selected':''; ?>>Flat</option>
                  <option value="shop"  <?= $type==='shop'?'selected':''; ?>>Shop</option>
               </select>
            </div>
            <div class="field"><label>Offer</label>
               <select name="h_offer">
                  <option value="">Any</option>
                  <option value="sale" <?= $offer==='sale'?'selected':''; ?>>For Sale</option>
                  <option value="rent" <?= $offer==='rent'?'selected':''; ?>>For Rent</option>
                  <option value="resale" <?= $offer==='resale'?'selected':''; ?>>Resale</option>
               </select>
            </div>
         </div>
         <div class="search-row">
            <div class="field"><label>Min A$</label>
               <input type="number" name="h_min" value="<?= $min ?: ''; ?>" placeholder="0">
            </div>
            <div class="field"><label>Max A$</label>
               <input type="number" name="h_max" value="<?= $max ?: ''; ?>" placeholder="Any">
            </div>
            <div class="field"><label>Beds (min)</label>
               <select name="h_beds">
                  <option value="0">Any</option>
                  <?php for($i=1;$i<=5;$i++): ?>
                     <option value="<?= $i ?>" <?= $beds===$i?'selected':''; ?>><?= $i ?>+</option>
                  <?php endfor; ?>
               </select>
            </div>
            <div class="field"><label>Baths (min)</label>
               <select name="h_baths">
                  <option value="0">Any</option>
                  <?php for($i=1;$i<=4;$i++): ?>
                     <option value="<?= $i ?>" <?= $baths===$i?'selected':''; ?>><?= $i ?>+</option>
                  <?php endfor; ?>
               </select>
            </div>
            <div class="field"><label>Furnishing</label>
               <select name="h_furnished">
                  <option value="">Any</option>
                  <option value="furnished" <?= $furn==='furnished'?'selected':''; ?>>Furnished</option>
                  <option value="semi-furnished" <?= $furn==='semi-furnished'?'selected':''; ?>>Semi-furnished</option>
                  <option value="unfurnished" <?= $furn==='unfurnished'?'selected':''; ?>>Unfurnished</option>
               </select>
            </div>
            <div class="field"><label>Sort by</label>
               <select name="h_sort">
                  <option value="newest" <?= $sort==='newest'?'selected':''; ?>>Newest first</option>
                  <option value="price_low" <?= $sort==='price_low'?'selected':''; ?>>Price: low to high</option>
                  <option value="price_high" <?= $sort==='price_high'?'selected':''; ?>>Price: high to low</option>
                  <option value="beds" <?= $sort==='beds'?'selected':''; ?>>Most bedrooms</option>
               </select>
            </div>
            <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
         </div>
      </form>

      <h2 class="section-heading"><?= $sel->rowCount(); ?> result<?= $sel->rowCount()==1?'':'s'; ?></h2>

      <div class="prop-grid">
      <?php if ($sel->rowCount() > 0):
         while ($p = $sel->fetch()):
            $is_saved = false;
            if ($user_id) {
               $sv = $conn->prepare("SELECT id FROM `saved` WHERE property_id = ? AND user_id = ?");
               $sv->execute([$p['id'], $user_id]);
               $is_saved = $sv->rowCount() > 0;
            }
      ?>
         <form action="" method="POST" class="prop-card">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($p['id']); ?>">
            <input type="hidden" name="h_location" value="<?= htmlspecialchars($loc); ?>">
            <input type="hidden" name="h_keyword" value="<?= htmlspecialchars($kw); ?>">
            <input type="hidden" name="h_type" value="<?= htmlspecialchars($type); ?>">
            <input type="hidden" name="h_offer" value="<?= htmlspecialchars($offer); ?>">
            <input type="hidden" name="h_min" value="<?= $min ?: ''; ?>">
            <input type="hidden" name="h_max" value="<?= $max ?: ''; ?>">
            <input type="hidden" name="h_beds" value="<?= $beds ?: ''; ?>">
            <input type="hidden" name="h_baths" value="<?= $baths ?: ''; ?>">
            <input type="hidden" name="h_furnished" value="<?= htmlspecialchars($furn); ?>">
            <input type="hidden" name="h_sort" value="<?= htmlspecialchars($sort); ?>">
            <div class="prop-media">
               <button type="submit" name="save" class="save-btn"><i class="<?= $is_saved?'fas':'far'; ?> fa-heart"></i></button>
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
               </div>
               <div class="prop-actions">
                  <a href="view_property.php?get_id=<?= htmlspecialchars($p['id']); ?>" class="btn-outline">View</a>
                  <button type="submit" name="send" class="btn-primary">Enquire</button>
               </div>
            </div>
         </form>
      <?php endwhile; else: ?>
         <p class="empty-state">No properties match your search. <a href="listings.php">Browse all &rarr;</a></p>
      <?php endif; ?>
      </div>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<?php include 'components/message.php'; ?>
</body>
</html>
