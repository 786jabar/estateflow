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
$admin_name = 'Admin';
try {
   $a = $conn->prepare("SELECT name FROM `admins` WHERE id = ? LIMIT 1");
   $a->execute([$admin_id]);
   $r = $a->fetch(PDO::FETCH_ASSOC);
   if ($r) $admin_name = $r['name'];
} catch (Exception $e) {}

$ef_page_title = 'Users'; include '_layout_top.php';
?>
<div class="card">
   <div class="toolbar-flex">
      <h2 style="margin:0;">All Users (<?= $select_users->rowCount(); ?>)</h2>
      <a href="add_user.php" class="btn"><i class="fas fa-user-plus"></i>&nbsp; Add New User</a>
   </div>

   <form action="" method="POST" class="search-inline" style="margin-bottom:20px;">
      <input type="text" name="search_box" class="box" placeholder="Search users by name, email or phone…"
             maxlength="100" value="<?= htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-dark" name="search_btn"><i class="fas fa-search"></i>&nbsp; Search</button>
      <?php if ($search !== ''): ?><a href="users.php" class="btn">Reset</a><?php endif; ?>
   </form>

   <?php if ($select_users->rowCount() > 0): ?>
   <div style="overflow-x:auto;"><table>
      <thead><tr>
         <th>Name</th><th>Email</th><th>Phone</th><th>Properties</th><th>Action</th>
      </tr></thead>
      <tbody>
      <?php while ($u = $select_users->fetch()):
            $cnt = $conn->prepare("SELECT id FROM `property` WHERE user_id = ?");
            $cnt->execute([$u['id']]);
            $total = $cnt->rowCount();
      ?>
         <tr>
            <td><strong><?= htmlspecialchars($u['name']); ?></strong></td>
            <td><a href="mailto:<?= htmlspecialchars($u['email']); ?>"><?= htmlspecialchars($u['email']); ?></a></td>
            <td><a href="tel:<?= htmlspecialchars($u['number']); ?>"><?= htmlspecialchars($u['number']); ?></a></td>
            <td><span class="badge"><?= (int)$total; ?></span></td>
            <td>
               <form action="" method="POST" style="display:inline;">
                  <input type="hidden" name="delete_id" value="<?= htmlspecialchars($u['id']); ?>">
                  <button type="submit" name="delete" class="btn btn-danger btn-sm"
                     onclick="return confirm('Delete this user and all their listings?');">
                     <i class="fas fa-trash"></i>
                  </button>
               </form>
            </td>
         </tr>
      <?php endwhile; ?>
      </tbody></table></div>
   <?php elseif ($search !== ''): ?>
      <div class="empty"><i class="fas fa-search"></i><p>No users match your search.</p></div>
   <?php else: ?>
      <div class="empty"><i class="fas fa-users"></i><p>No user accounts yet. <a href="add_user.php">Add the first user</a></p></div>
   <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../components/message.php'; ?>
</main></div></body></html>
