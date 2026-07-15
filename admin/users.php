<?php
include '../components/connect.php';

if (empty($_SESSION['admin_id'])) { header('location:login.php'); exit; }
$admin_id = $_SESSION['admin_id'];

/* ---------- Delete user ---------- */
if (isset($_POST['delete'])) {
   $delete_id = trim((string)($_POST['delete_id'] ?? ''));

   if ($delete_id !== '') {
      $verify = $conn->prepare("SELECT id FROM `users` WHERE id = ?");
      $verify->execute([$delete_id]);

      if ($verify->rowCount() > 0) {
         // Remove their property images first
         $imgs = $conn->prepare("SELECT image_01, image_02, image_03, image_04, image_05 FROM `property` WHERE user_id = ?");
         $imgs->execute([$delete_id]);
         while ($row = $imgs->fetch()) {
            foreach (['image_01','image_02','image_03','image_04','image_05'] as $k) {
               if (!empty($row[$k]) && file_exists('../uploaded_files/'.$row[$k])) {
                  @unlink('../uploaded_files/'.$row[$k]);
               }
            }
         }
         $conn->prepare("DELETE FROM `property`  WHERE user_id = ?")->execute([$delete_id]);
         $conn->prepare("DELETE FROM `requests` WHERE sender = ? OR receiver = ?")->execute([$delete_id, $delete_id]);
         $conn->prepare("DELETE FROM `saved`    WHERE user_id = ?")->execute([$delete_id]);
         $conn->prepare("DELETE FROM `users`    WHERE id = ?")->execute([$delete_id]);
         $success_msg[] = 'User deleted.';
      } else {
         $warning_msg[] = 'User already deleted.';
      }
   }
}

/* ---------- Search (parameterised — was SQL-injectable before) ---------- */
$search = '';
if (isset($_POST['search_btn']) || isset($_POST['search_box'])) {
   $search = trim((string)($_POST['search_box'] ?? ''));
}
if ($search !== '') {
   $like = '%'.$search.'%';
   $select_users = $conn->prepare(
      "SELECT * FROM `users` WHERE name LIKE ? OR number LIKE ? OR email LIKE ? ORDER BY name ASC"
   );
   $select_users->execute([$like, $like, $like]);
} else {
   $select_users = $conn->prepare("SELECT * FROM `users` ORDER BY name ASC");
   $select_users->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users &mdash; EstateFlow Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="grid dashboard">

   <div class="users-toolbar">
      <h1 class="heading" style="text-align:left;margin:0;">Users</h1>
      <a href="add_user.php" class="btn-accent">
         <i class="fas fa-user-plus"></i>&nbsp; Add New User
      </a>
   </div>

   <form action="" method="POST" class="search-form filters" style="margin-top:2rem;">
      <input type="text" name="search_box" placeholder="Search users by name, email or phone…"
             maxlength="100" value="<?= htmlspecialchars($search); ?>" style="flex:1;min-width:240px;padding:1.2rem;border:1px solid var(--line);">
      <button type="submit" class="btn-primary" name="search_btn">
         <i class="fas fa-search"></i>&nbsp; Search
      </button>
   </form>

   <div class="box-container">
   <?php
      if ($select_users->rowCount() > 0) {
         while ($u = $select_users->fetch()) {
            $cnt = $conn->prepare("SELECT id FROM `property` WHERE user_id = ?");
            $cnt->execute([$u['id']]);
            $total = $cnt->rowCount();
   ?>
      <div class="box">
         <p><strong>Name:</strong> <span><?= htmlspecialchars($u['name']); ?></span></p>
         <p><strong>Phone:</strong>
            <a href="tel:<?= htmlspecialchars($u['number']); ?>"><?= htmlspecialchars($u['number']); ?></a></p>
         <p><strong>Email:</strong>
            <a href="mailto:<?= htmlspecialchars($u['email']); ?>"><?= htmlspecialchars($u['email']); ?></a></p>
         <p><strong>Properties listed:</strong> <span><?= (int)$total; ?></span></p>
         <form action="" method="POST" style="margin-top:1rem;">
            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($u['id']); ?>">
            <button type="submit" name="delete" class="btn-outline"
               onclick="return confirm('Delete this user and all their listings?');"
               style="border-color:var(--danger);color:var(--danger);">
               <i class="fas fa-trash"></i>&nbsp; Delete user
            </button>
         </form>
      </div>
   <?php
         }
      } elseif ($search !== '') {
         echo '<p class="empty">No users match your search.</p>';
      } else {
         echo '<p class="empty">No user accounts yet. <a href="add_user.php" class="btn-primary" style="margin-left:1rem;">Add the first user</a></p>';
      }
   ?>
   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/message.php'; ?>
</body>
</html>
