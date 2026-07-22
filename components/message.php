<?php
// Flash message handler — shows a SweetAlert popup if a session message was set
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!empty($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    $text = htmlspecialchars(is_array($msg) ? ($msg['text'] ?? '') : $msg, ENT_QUOTES);
    $icon = is_array($msg) ? ($msg['icon'] ?? 'info') : 'info';
    if (!in_array($icon, ['success','warning','info','error'], true)) { $icon = 'info'; }
    echo "<script>if(window.swal)swal({title:'".ucfirst($icon)."',text:'".$text."',icon:'".$icon."'});</script>";
}

/* Page-level messages set by save_send.php (Save / Send Enquiry buttons).
   Before this fix these were never displayed, so the buttons appeared
   to "do nothing and refresh the page". */
if (!empty($success_msg)) {
   foreach ((array)$success_msg as $m1) {
      echo "<script>if(window.swal)swal({title:'Done!',text:'".htmlspecialchars($m1,ENT_QUOTES)."',icon:'success'});</script>";
   }
}
if (!empty($warning_msg)) {
   foreach ((array)$warning_msg as $m1) {
      echo "<script>if(window.swal)swal({title:'Notice',text:'".htmlspecialchars($m1,ENT_QUOTES)."',icon:'warning'});</script>";
   }
}
if ((!empty($success_msg) || !empty($warning_msg)) && false) {
   echo '<div id="ef-flash" style="position:fixed;top:90px;left:50%;transform:translateX(-50%);z-index:9999;max-width:520px;width:92%;">';
   foreach ((array)($success_msg ?? []) as $m) {
      echo '<div style="background:#e8f7ee;border-left:4px solid #27ae60;color:#14532d;padding:1.2rem 1.6rem;margin-bottom:.8rem;border-radius:6px;font-size:1.4rem;box-shadow:0 6px 24px rgba(0,0,0,.12);">'
           . htmlspecialchars($m) . '</div>';
   }
   foreach ((array)($warning_msg ?? []) as $m) {
      echo '<div style="background:#fdecea;border-left:4px solid #c0392b;color:#7a1f17;padding:1.2rem 1.6rem;margin-bottom:.8rem;border-radius:6px;font-size:1.4rem;box-shadow:0 6px 24px rgba(0,0,0,.12);">'
           . htmlspecialchars($m) . '</div>';
   }
   echo '</div>';
   echo '<script>setTimeout(function(){var b=document.getElementById("ef-flash");if(b)b.style.display="none";},5000);</script>';
}
?>
