<?php
include 'components/connect.php';
if(session_status()===PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'] ?? '';
$admin_id = $_SESSION['admin_id'] ?? ($_COOKIE['admin_id'] ?? '');
if($user_id===''){
   if($admin_id!==''){ $user_id = $admin_id; }
   else { header('location:login.php'); exit; }
}

$pid = $_GET['get_id'] ?? $_POST['property_id'] ?? '';
$sel = $conn->prepare("SELECT * FROM `property` WHERE id = ? AND user_id = ?");
$sel->execute([$pid, $user_id]);
$prop = $sel->fetch();
if(!$prop){ header('location:dashboard.php'); exit; }

$ok=''; $err='';
if(isset($_POST['update'])){
   try{
      $property_name = trim($_POST['property_name'] ?? '');
      $price         = trim($_POST['price'] ?? '');
      $deposite      = trim($_POST['deposite'] ?? '0');
      $address       = trim($_POST['address'] ?? '');
      $offer         = $_POST['offer'] ?? 'sale';
      $type          = $_POST['type'] ?? 'house';
      $status        = $_POST['status'] ?? 'ready to move';
      $furnished     = $_POST['furnished'] ?? 'furnished';
      $bhk           = $_POST['bhk'] ?? '2';
      $bedroom       = (int)($_POST['bedroom'] ?? 1);
      $bathroom      = (int)($_POST['bathroom'] ?? 1);
      $balcony       = (int)($_POST['balcony'] ?? 0);
      $carpet        = (int)($_POST['carpet'] ?? 100);
      $age           = $_POST['age'] ?? '0';
      $total_floors  = (int)($_POST['total_floors'] ?? 1);
      $room_floor    = (int)($_POST['room_floor'] ?? 1);
      $loan          = $_POST['loan'] ?? 'available';
      $description   = trim($_POST['description'] ?? '');

      if($property_name===''||$price===''||$address===''||$description===''){
         throw new Exception('Please fill: property name, price, address, and description.');
      }

      $folder = __DIR__.'/uploaded_files';
      if(!is_dir($folder)) @mkdir($folder, 0755, true);

      // Keep existing images unless a new one is uploaded
      $imgs = [$prop['image_01'],$prop['image_02'],$prop['image_03'],$prop['image_04'],$prop['image_05']];
      $fields = ['image_01','image_02','image_03','image_04','image_05'];
      foreach($fields as $i=>$f){
         if(!empty($_FILES[$f]['name']) && ($_FILES[$f]['error']??4)===0){
            $ext = strtolower(pathinfo($_FILES[$f]['name'], PATHINFO_EXTENSION));
            if(!in_array($ext,['jpg','jpeg','png','webp','gif'])) continue;
            if($_FILES[$f]['size']>5000000) continue;
            $name = create_unique_id().'.'.$ext;
            if(move_uploaded_file($_FILES[$f]['tmp_name'], $folder.'/'.$name)){
               $old = $imgs[$i];
               $imgs[$i] = $name;
               if($old!=='' && is_file($folder.'/'.$old)) @unlink($folder.'/'.$old);
            }
         }
      }

      $features = [];
      foreach(['lift','security_guard','play_ground','garden','water_supply','power_backup',
               'parking_area','gym','shopping_mall','hospital','school','market_area'] as $f){
         $features[$f] = isset($_POST[$f]) ? 'yes' : 'no';
      }

      $stmt = $conn->prepare(
         "UPDATE `property` SET
            property_name=?, address=?, price=?, type=?, offer=?, status=?, furnished=?, bhk=?, deposite=?,
            bedroom=?, bathroom=?, balcony=?, carpet=?, age=?, total_floors=?, room_floor=?, loan=?,
            lift=?, security_guard=?, play_ground=?, garden=?, water_supply=?, power_backup=?,
            parking_area=?, gym=?, shopping_mall=?, hospital=?, school=?, market_area=?,
            image_01=?, image_02=?, image_03=?, image_04=?, image_05=?, description=?
          WHERE id=? AND user_id=?"
      );
      $stmt->execute([
         $property_name,$address,$price,$type,$offer,$status,$furnished,$bhk,$deposite,
         $bedroom,$bathroom,$balcony,$carpet,$age,$total_floors,$room_floor,$loan,
         $features['lift'],$features['security_guard'],$features['play_ground'],$features['garden'],
         $features['water_supply'],$features['power_backup'],$features['parking_area'],$features['gym'],
         $features['shopping_mall'],$features['hospital'],$features['school'],$features['market_area'],
         $imgs[0],$imgs[1],$imgs[2],$imgs[3],$imgs[4],$description,
         $pid,$user_id
      ]);
      $ok = 'Listing updated successfully!';
      // reload fresh values
      $sel->execute([$pid, $user_id]);
      $prop = $sel->fetch();
   }catch(Exception $e){
      $err = $e->getMessage();
   }
}

function sel_if($a,$b){ return $a==$b ? 'selected' : ''; }
function chk_if($v){ return $v==='yes' ? 'checked' : ''; }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Listing — EstateFlow</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
html,body{font-family:'Inter',sans-serif;background:#f5f3ef;color:#0A1628;line-height:1.5;}
.ef-hero{background:linear-gradient(135deg,#0A1628 0%,#152841 70%,#0A1628 100%);color:#fff;padding:70px 20px 110px;position:relative;overflow:hidden;}
.ef-hero::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:radial-gradient(circle,rgba(184,147,90,.18),transparent 70%);border-radius:50%;}
.ef-hero::after{content:'';position:absolute;bottom:-150px;left:-100px;width:450px;height:450px;background:radial-gradient(circle,rgba(184,147,90,.12),transparent 70%);border-radius:50%;}
.ef-hero-in{max-width:980px;margin:0 auto;position:relative;z-index:2;text-align:center;}
.ef-eyebrow{display:inline-block;font-size:.78rem;letter-spacing:5px;color:#B8935A;font-weight:600;text-transform:uppercase;padding:6px 18px;border:1px solid rgba(184,147,90,.4);border-radius:30px;margin-bottom:22px;}
.ef-hero h1{font-family:'Playfair Display',serif;font-size:3.2rem;font-weight:600;margin-bottom:14px;letter-spacing:-.5px;}
.ef-hero h1 em{font-style:italic;color:#B8935A;font-weight:400;}
.ef-hero p{color:#cdd5e0;font-size:1.05rem;max-width:600px;margin:0 auto;}
.ef-shell{max-width:980px;margin:-70px auto 60px;padding:0 20px;position:relative;z-index:5;}
.ef-msg{padding:18px 22px;border-radius:12px;margin-bottom:24px;font-weight:500;display:flex;align-items:center;gap:14px;box-shadow:0 4px 20px rgba(0,0,0,.06);}
.ef-msg.ok{background:#fff;border-left:5px solid #1e7e34;color:#155724;}
.ef-msg.err{background:#fff;border-left:5px solid #dc3545;color:#721c24;}
.ef-msg i{font-size:1.4rem;}
.ef-msg .ef-link{margin-left:auto;color:#B8935A;text-decoration:none;font-weight:600;font-size:.92rem;}
.ef-card{background:#fff;border-radius:18px;padding:42px 44px;box-shadow:0 10px 40px rgba(10,22,40,.08);margin-bottom:28px;border:1px solid rgba(184,147,90,.12);}
.ef-section-head{display:flex;align-items:center;gap:14px;margin-bottom:26px;padding-bottom:18px;border-bottom:2px solid #f0ece4;}
.ef-section-head .ic{width:48px;height:48px;background:linear-gradient(135deg,#B8935A,#d4af71);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#0A1628;font-size:1.2rem;box-shadow:0 4px 12px rgba(184,147,90,.25);}
.ef-section-head h2{font-family:'Playfair Display',serif;font-size:1.5rem;color:#0A1628;font-weight:600;}
.ef-section-head h2 small{display:block;font-family:'Inter',sans-serif;font-size:.78rem;color:#888;font-weight:400;letter-spacing:1px;text-transform:uppercase;margin-top:2px;}
.ef-row{display:grid;gap:18px;}
.ef-row.c2{grid-template-columns:1fr 1fr;}
.ef-row.c3{grid-template-columns:1fr 1fr 1fr;}
.ef-field{display:flex;flex-direction:column;gap:7px;}
.ef-field label{font-size:.85rem;font-weight:600;color:#0A1628;letter-spacing:.3px;}
.ef-field label .req{color:#dc3545;}
.ef-field .hint{font-size:.75rem;color:#999;font-weight:400;}
.ef-input,.ef-input:focus,select.ef-input{width:100%;padding:13px 16px;border:1.5px solid #e5e0d6;border-radius:10px;font-size:.95rem;font-family:inherit;background:#fafaf7;color:#0A1628;outline:none;transition:.2s;}
.ef-input:focus{border-color:#B8935A;background:#fff;box-shadow:0 0 0 4px rgba(184,147,90,.1);}
textarea.ef-input{min-height:130px;resize:vertical;line-height:1.6;}
select.ef-input{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path fill='%23B8935A' d='M6 8L0 0h12z'/></svg>");background-repeat:no-repeat;background-position:right 16px center;padding-right:38px;}
.ef-price-wrap{position:relative;}
.ef-price-wrap::before{content:'A$';position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#B8935A;font-weight:600;font-size:.95rem;z-index:2;}
.ef-price-wrap .ef-input{padding-left:42px;}
.ef-chks{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
.ef-chk{position:relative;cursor:pointer;}
.ef-chk input{position:absolute;opacity:0;}
.ef-chk span{display:flex;align-items:center;gap:10px;padding:14px 16px;background:#fafaf7;border:1.5px solid #e5e0d6;border-radius:10px;font-size:.9rem;color:#555;font-weight:500;transition:.2s;}
.ef-chk span::before{content:'';width:18px;height:18px;border:1.5px solid #c9c4b8;border-radius:5px;background:#fff;display:inline-block;flex-shrink:0;}
.ef-chk input:checked + span{background:#fff;border-color:#B8935A;color:#0A1628;box-shadow:0 2px 10px rgba(184,147,90,.15);}
.ef-chk input:checked + span::before{background:#B8935A;border-color:#B8935A;content:'✓';color:#0A1628;font-weight:700;text-align:center;line-height:18px;font-size:.78rem;}
.ef-photo{display:flex;align-items:center;gap:16px;background:#fafaf7;border:1.5px solid #e5e0d6;border-radius:12px;padding:14px 16px;margin-bottom:14px;}
.ef-photo img{width:90px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e5e0d6;background:#eee;}
.ef-photo .ph{width:90px;height:64px;border-radius:8px;border:1.5px dashed #c9c4b8;display:flex;align-items:center;justify-content:center;color:#c9c4b8;font-size:1.3rem;}
.ef-photo .meta{flex:1;}
.ef-photo .meta .ttl{font-weight:600;font-size:.9rem;}
.ef-photo .meta .sub{font-size:.76rem;color:#999;}
.ef-photo input[type=file]{font-size:.8rem;max-width:230px;}
.ef-actions{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;}
.ef-back{color:#666;text-decoration:none;font-size:.92rem;display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:10px;}
.ef-back:hover{background:#f5f3ef;color:#B8935A;}
.ef-submit{background:linear-gradient(135deg,#B8935A,#d4af71);color:#0A1628;border:none;padding:16px 38px;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;display:inline-flex;align-items:center;gap:10px;letter-spacing:.3px;box-shadow:0 6px 20px rgba(184,147,90,.35);transition:.2s;}
.ef-submit:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(184,147,90,.45);}
@media(max-width:760px){
   .ef-hero{padding:50px 18px 90px;}.ef-hero h1{font-size:2.1rem;}
   .ef-card{padding:26px 22px;}.ef-row.c2,.ef-row.c3{grid-template-columns:1fr;}
   .ef-chks{grid-template-columns:1fr 1fr;}.ef-section-head{flex-direction:column;text-align:center;gap:10px;}
   .ef-photo{flex-direction:column;align-items:flex-start;}
}
</style></head><body>
<?php include 'components/user_header.php'; ?>

<section class="ef-hero">
   <div class="ef-hero-in">
      <span class="ef-eyebrow">MANAGE YOUR LISTING</span>
      <h1>Edit <em>Property</em></h1>
      <p>Update details, prices and photos — changes appear on EstateFlow instantly.</p>
   </div>
</section>

<div class="ef-shell">

   <?php if($ok): ?>
      <div class="ef-msg ok"><i class="fas fa-check-circle" style="color:#1e7e34;"></i>
         <div><strong>Saved!</strong><br><?= htmlspecialchars($ok) ?></div>
         <a href="view_property.php?get_id=<?= htmlspecialchars($pid) ?>" class="ef-link">View listing →</a>
      </div>
   <?php endif; ?>
   <?php if($err): ?>
      <div class="ef-msg err"><i class="fas fa-exclamation-circle" style="color:#dc3545;"></i>
         <div><strong>Couldn't save:</strong><br><?= htmlspecialchars($err) ?></div>
      </div>
   <?php endif; ?>

   <form method="POST" enctype="multipart/form-data">
   <input type="hidden" name="property_id" value="<?= htmlspecialchars($pid) ?>">

   <!-- BASICS -->
   <div class="ef-card">
      <div class="ef-section-head">
         <div class="ic"><i class="fas fa-circle-info"></i></div>
         <h2>Basic Information<small>Property name, price, and location</small></h2>
      </div>
      <div class="ef-field" style="margin-bottom:18px;">
         <label>Property Name <span class="req">*</span></label>
         <input type="text" name="property_name" class="ef-input" required maxlength="50" value="<?= htmlspecialchars($prop['property_name']) ?>">
      </div>
      <div class="ef-row c2" style="margin-bottom:18px;">
         <div class="ef-field"><label>Price <span class="req">*</span> <span class="hint">in Australian dollars</span></label>
            <div class="ef-price-wrap"><input type="number" name="price" class="ef-input" required min="0" value="<?= htmlspecialchars($prop['price']) ?>"></div></div>
         <div class="ef-field"><label>Deposit <span class="hint">if applicable</span></label>
            <div class="ef-price-wrap"><input type="number" name="deposite" class="ef-input" min="0" value="<?= htmlspecialchars($prop['deposite']) ?>"></div></div>
      </div>
      <div class="ef-field" style="margin-bottom:18px;">
         <label>Full Address <span class="req">*</span></label>
         <input type="text" name="address" class="ef-input" required maxlength="100" value="<?= htmlspecialchars($prop['address']) ?>">
      </div>
      <div class="ef-row c3">
         <div class="ef-field"><label>Offer Type</label>
            <select name="offer" class="ef-input">
               <option value="sale" <?= sel_if($prop['offer'],'sale') ?>>For Sale</option>
               <option value="rent" <?= sel_if($prop['offer'],'rent') ?>>For Rent</option>
            </select></div>
         <div class="ef-field"><label>Property Type</label>
            <select name="type" class="ef-input">
               <option value="house" <?= sel_if($prop['type'],'house') ?>>House</option>
               <option value="flat" <?= sel_if($prop['type'],'flat') ?>>Apartment</option>
               <option value="shop" <?= sel_if($prop['type'],'shop') ?>>Commercial</option>
            </select></div>
         <div class="ef-field"><label>Status</label>
            <select name="status" class="ef-input">
               <option value="ready to move" <?= sel_if($prop['status'],'ready to move') ?>>Ready to move</option>
               <option value="under construction" <?= sel_if($prop['status'],'under construction') ?>>Under construction</option>
            </select></div>
      </div>
   </div>

   <!-- ROOMS -->
   <div class="ef-card">
      <div class="ef-section-head">
         <div class="ic"><i class="fas fa-bed"></i></div>
         <h2>Rooms & Specifications<small>Bedrooms, bathrooms, and size</small></h2>
      </div>
      <div class="ef-row c3" style="margin-bottom:18px;">
         <div class="ef-field"><label>Bedrooms</label>
            <select name="bedroom" class="ef-input"><?php for($i=0;$i<=9;$i++)echo "<option ".sel_if($prop['bedroom'],$i).">$i</option>";?></select></div>
         <div class="ef-field"><label>Bathrooms</label>
            <select name="bathroom" class="ef-input"><?php for($i=1;$i<=9;$i++)echo "<option ".sel_if($prop['bathroom'],$i).">$i</option>";?></select></div>
         <div class="ef-field"><label>Balconies</label>
            <select name="balcony" class="ef-input"><?php for($i=0;$i<=9;$i++)echo "<option ".sel_if($prop['balcony'],$i).">$i</option>";?></select></div>
      </div>
      <div class="ef-row c3" style="margin-bottom:18px;">
         <div class="ef-field"><label>BHK</label>
            <select name="bhk" class="ef-input"><?php for($i=1;$i<=9;$i++)echo "<option ".sel_if($prop['bhk'],$i).">$i</option>";?></select></div>
         <div class="ef-field"><label>Carpet Area <span class="hint">(m²)</span></label>
            <input type="number" name="carpet" class="ef-input" min="1" value="<?= htmlspecialchars($prop['carpet']) ?>"></div>
         <div class="ef-field"><label>Furnishing</label>
            <select name="furnished" class="ef-input">
               <option value="furnished" <?= sel_if($prop['furnished'],'furnished') ?>>Furnished</option>
               <option value="semi-furnished" <?= sel_if($prop['furnished'],'semi-furnished') ?>>Semi-furnished</option>
               <option value="unfurnished" <?= sel_if($prop['furnished'],'unfurnished') ?>>Unfurnished</option>
            </select></div>
      </div>
      <div class="ef-row c3">
         <div class="ef-field"><label>Property Age <span class="hint">(years)</span></label>
            <input type="text" name="age" class="ef-input" value="<?= htmlspecialchars($prop['age']) ?>"></div>
         <div class="ef-field"><label>Total Floors</label>
            <input type="number" name="total_floors" class="ef-input" min="0" max="99" value="<?= htmlspecialchars($prop['total_floors']) ?>"></div>
         <div class="ef-field"><label>This Floor</label>
            <input type="number" name="room_floor" class="ef-input" min="0" max="99" value="<?= htmlspecialchars($prop['room_floor']) ?>"></div>
      </div>
   </div>

   <!-- DESCRIPTION -->
   <div class="ef-card">
      <div class="ef-section-head">
         <div class="ic"><i class="fas fa-pen-fancy"></i></div>
         <h2>Description<small>Tell the story of this property</small></h2>
      </div>
      <div class="ef-field">
         <label>Description <span class="req">*</span></label>
         <textarea name="description" class="ef-input" required maxlength="1000"><?= htmlspecialchars($prop['description']) ?></textarea>
      </div>
   </div>

   <!-- FEATURES -->
   <div class="ef-card">
      <div class="ef-section-head">
         <div class="ic"><i class="fas fa-star"></i></div>
         <h2>Features & Amenities<small>Tick everything this property offers</small></h2>
      </div>
      <div class="ef-chks">
         <?php foreach([
            'lift'=>'Lift','security_guard'=>'Security','play_ground'=>'Play ground',
            'garden'=>'Garden','water_supply'=>'Water supply','power_backup'=>'Power backup',
            'parking_area'=>'Parking','gym'=>'Gym','shopping_mall'=>'Shopping nearby',
            'hospital'=>'Hospital nearby','school'=>'School nearby','market_area'=>'Market nearby'
         ] as $k=>$lbl): ?>
            <label class="ef-chk"><input type="checkbox" name="<?= $k ?>" value="yes" <?= chk_if($prop[$k]) ?>><span><?= $lbl ?></span></label>
         <?php endforeach; ?>
      </div>
   </div>

   <!-- PHOTOS -->
   <div class="ef-card">
      <div class="ef-section-head">
         <div class="ic"><i class="fas fa-camera"></i></div>
         <h2>Property Photos<small>Leave empty to keep the current photo · JPG / PNG · max 5 MB</small></h2>
      </div>
      <?php
      $labels = ['Main Photo','Photo 2','Photo 3','Photo 4','Photo 5'];
      foreach(['image_01','image_02','image_03','image_04','image_05'] as $i=>$f):
         $cur = $prop[$f]; ?>
         <div class="ef-photo">
            <?php if($cur!=='' && $cur!==null): ?>
               <img src="uploaded_files/<?= htmlspecialchars($cur) ?>" alt="">
            <?php else: ?>
               <div class="ph"><i class="far fa-image"></i></div>
            <?php endif; ?>
            <div class="meta">
               <div class="ttl"><?= $labels[$i] ?></div>
               <div class="sub"><?= ($cur!==''&&$cur!==null) ? 'Current photo shown — choose a file to replace it' : 'No photo yet — choose a file to add one' ?></div>
            </div>
            <input type="file" name="<?= $f ?>" accept="image/*">
         </div>
      <?php endforeach; ?>
   </div>

   <!-- ACTIONS -->
   <div class="ef-card" style="padding:24px 32px;">
      <div class="ef-actions">
         <a href="dashboard.php" class="ef-back"><i class="fas fa-arrow-left"></i> Back to dashboard</a>
         <button type="submit" name="update" class="ef-submit"><i class="fas fa-floppy-disk"></i> Save Changes</button>
      </div>
   </div>

   </form>
</div>

<?php include 'components/footer.php'; ?>
</body></html>
