<?php
session_start();
$_SESSION = [];
session_destroy();
setcookie('admin_id', '', time()-3600, '/');
header('location:../admin/login.php');
exit;
