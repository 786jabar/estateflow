<?php
require __DIR__ . '/components/connect.php';
$_SESSION = [];
if (ini_get('session.use_cookies')) {
   $p = session_get_cookie_params();
   setcookie(session_name(), '', time() - 42000, $p['path'] ?? '/', $p['domain'] ?? '', $p['secure'] ?? false, $p['httponly'] ?? true);
}
@session_destroy();
setcookie('user_id',  '', time() - 3600, '/');
setcookie('admin_id', '', time() - 3600, '/');
setcookie('PHPSESSID','', time() - 3600, '/');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Location: home.php?logged_out=1');
exit;
