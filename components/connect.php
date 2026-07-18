<?php
/*
   database connection for estateflow
   change the 4 values below to match the cpanel database
   (everything else on the site includes this file)
*/

$DB_HOST = 'localhost';
$DB_NAME = 'estauequ_estateflow';
$DB_USER = 'estauequ_estateflow';
$DB_PASS = 'CHANGE_ME';

/* XAMPP on my laptop uses different settings than the live cpanel server.
   I detect localhost automatically so the same file works in both places. */
$ef_host = $_SERVER['HTTP_HOST'] ?? '';
$ef_is_xampp = ($ef_host === 'localhost' || str_starts_with($ef_host, 'localhost:')
                || str_starts_with($ef_host, '127.0.0.1'));

if (getenv('ESTATEFLOW_LOCAL')) {
   /* local test environment */
   $dsn = "mysql:unix_socket=/tmp/mysql_run/mysqld.sock;dbname=estateflow;charset=utf8mb4";
   $DB_USER = 'root';
   $DB_PASS = '';
} elseif ($ef_is_xampp) {
   /* XAMPP local testing - change this name if your phpMyAdmin database
      is called something different */
   $DB_NAME_LOCAL = 'estateflow';
   $dsn = "mysql:host=localhost;dbname={$DB_NAME_LOCAL};charset=utf8mb4";
   $DB_USER = 'root';
   $DB_PASS = '';
} else {
   /* live cpanel server */
   $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
}

try {
   $conn = new PDO(
      $dsn,
      $DB_USER,
      $DB_PASS,
      [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES   => false,
      ]
   );
} catch (PDOException $e) {
   die('Database connection failed. Please try again later.');
}

/* ---------- Sessions (replaces insecure cookies) ---------- */
if (session_status() === PHP_SESSION_NONE) {
   $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
              || ($_SERVER['SERVER_PORT'] ?? '') == 443;
   session_set_cookie_params([
      'lifetime' => 60 * 60 * 24 * 30,
      'path'     => '/',
      'secure'   => $isHttps,
      'httponly' => true,
      'samesite' => 'Lax',
   ]);
   session_start();
}

/* some of my older pages still check $_COOKIE['user_id'] from before
   I switched to sessions. the session is the real source of truth here -
   if there is no session I clear the cookie value so a fake cookie
   can't get past the old pages, and if there is a session I copy it
   into $_COOKIE so the old pages keep working without rewriting them */
if (!empty($_SESSION['user_id'])) {
   $_COOKIE['user_id'] = $_SESSION['user_id'];
} else {
   unset($_COOKIE['user_id']);
   if (isset($_SERVER['HTTP_COOKIE']) && strpos($_SERVER['HTTP_COOKIE'], 'user_id=') !== false) {
      setcookie('user_id', '', time() - 3600, '/');
   }
}
if (!empty($_SESSION['admin_id'])) {
   $_COOKIE['admin_id'] = $_SESSION['admin_id'];
} else {
   unset($_COOKIE['admin_id']);
   if (isset($_SERVER['HTTP_COOKIE']) && strpos($_SERVER['HTTP_COOKIE'], 'admin_id=') !== false) {
      setcookie('admin_id', '', time() - 3600, '/');
   }
}

/* ---------- Helpers ---------- */

/* CSRF protection: every form that changes data must send this token.
   ef_csrf_token() prints a hidden input, ef_csrf_check() validates it. */
function ef_csrf_token() {
   if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }
   return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}
function ef_csrf_check() {
   return $_SERVER['REQUEST_METHOD'] === 'POST'
      && !empty($_SESSION['csrf_token'])
      && hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''));
}

function create_unique_id() {
   return bin2hex(random_bytes(10)); // 20 hex chars
}

/* Returns a bcrypt hash suitable for the users.password column.       */
function ef_hash_password($plain) {
   return password_hash($plain, PASSWORD_BCRYPT);
}

/* Verifies a password against either a modern bcrypt hash OR a
   legacy 40-char SHA1 hash created by the older version of the site.
   Returns true/false.                                                 */
function ef_verify_password($plain, $stored) {
   if (!$stored) return false;
   // Modern bcrypt hashes always start with $2y$
   if (strlen($stored) === 60 && str_starts_with($stored, '$2')) {
      return password_verify($plain, $stored);
   }
   // Legacy SHA1 fallback
   if (strlen($stored) === 40 && ctype_xdigit($stored)) {
      return hash_equals($stored, sha1($plain));
   }
   return false;
}

/* Returns true if the stored hash is legacy and should be upgraded.   */
function ef_needs_rehash($stored) {
   return !(strlen($stored) === 60 && str_starts_with($stored, '$2'));
}

/* Logged-in check                                                     */
function ef_user_id() {
   return $_SESSION['user_id'] ?? '';
}
function ef_require_login() {
   if (empty($_SESSION['user_id'])) {
      header('location:login.php');
      exit;
   }
}
