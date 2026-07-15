<?php
session_start();
include '../components/connect.php';

$warning_msg = [];

if(isset($_POST['submit'])){
   $name = trim((string)($_POST['name'] ?? ''));
   $pass = (string)($_POST['pass'] ?? '');

   $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name = ? LIMIT 1");
   $select_admins->execute([$name]);
   $row = $select_admins->fetch(PDO::FETCH_ASSOC);

   if($row && ef_verify_password($pass, $row['password'])){
      $_SESSION['admin_id'] = $row['id'];
      $_SESSION['admin_name'] = $row['name'];
      setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
      header('location:dashboard.php');
      exit;
   } else {
      $warning_msg[] = 'Incorrect username or password!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login — EstateFlow</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
   <style>
      *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
      html,body{padding:0 !important;margin:0 !important;}
      body{min-height:100vh;background:#0A1628;color:#fff;display:flex;align-items:center;justify-content:center;padding:20px;
         background-image:radial-gradient(circle at 20% 30%,rgba(184,147,90,0.15) 0%,transparent 50%),
            radial-gradient(circle at 80% 70%,rgba(184,147,90,0.1) 0%,transparent 50%),
            linear-gradient(135deg,#0A1628 0%,#152841 100%);}
      .shell{width:100%;max-width:440px;}
      .brand{text-align:center;margin-bottom:30px;}
      .brand a{color:#fff;text-decoration:none;font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;}
      .brand a i{color:#B8935A;margin-right:8px;}
      .brand p{color:#9aa3af;font-size:.85rem;margin-top:6px;letter-spacing:3px;text-transform:uppercase;}
      .card{background:rgba(255,255,255,0.04);backdrop-filter:blur(20px);border:1px solid rgba(184,147,90,0.25);
         border-radius:16px;padding:40px 35px;box-shadow:0 25px 60px rgba(0,0,0,0.4);}
      .card h3{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;text-align:center;margin-bottom:8px;font-weight:500;}
      .card .sub{text-align:center;color:#9aa3af;font-size:.9rem;margin-bottom:28px;}
      .field{margin-bottom:18px;position:relative;}
      .field i{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#B8935A;}
      .box{width:100%;padding:14px 16px 14px 44px;border-radius:8px;border:1px solid rgba(184,147,90,0.3);
         background:rgba(255,255,255,0.06);color:#fff;font-size:.95rem;outline:none;}
      .box:focus{border-color:#B8935A;background:rgba(255,255,255,0.1);}
      .box::placeholder{color:#9aa3af;}
      .btn{width:100%;padding:14px;background:#B8935A;color:#0A1628;border:none;border-radius:8px;
         font-weight:600;font-size:1rem;letter-spacing:1px;text-transform:uppercase;cursor:pointer;margin-top:8px;}
      .btn:hover{background:#c9a961;}
      .back-link{text-align:center;margin-top:22px;}
      .back-link a{color:#9aa3af;text-decoration:none;font-size:.85rem;}
      .msg{padding:12px 14px;border-radius:8px;margin-bottom:18px;font-size:.9rem;
         background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.4);color:#ffb3b9;
         display:flex;align-items:center;gap:10px;}
      .badge{display:inline-block;background:rgba(184,147,90,0.15);color:#B8935A;
         border:1px solid rgba(184,147,90,0.3);padding:4px 12px;border-radius:20px;
         font-size:.7rem;letter-spacing:2px;text-transform:uppercase;margin-bottom:12px;}
   </style>
</head>
<body>
<div class="shell">
   <div class="brand">
      <a href="../home.php"><i class="fas fa-gem"></i>EstateFlow</a>
      <p>Luxury Real Estate · Australia</p>
   </div>
   <div class="card">
      <div style="text-align:center;"><span class="badge"><i class="fas fa-shield-alt"></i> Admin Portal</span></div>
      <h3>Welcome Back</h3>
      <p class="sub">Sign in to manage your portfolio</p>
      <?php foreach($warning_msg as $m){ ?>
         <div class="msg"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($m) ?></div>
      <?php } ?>
      <form action="" method="POST">
         <div class="field"><i class="fas fa-user"></i>
            <input type="text" name="name" placeholder="Username" maxlength="20" class="box" required oninput="this.value=this.value.replace(/\s/g,'')"></div>
         <div class="field"><i class="fas fa-lock"></i>
            <input type="password" name="pass" placeholder="Password" maxlength="20" class="box" required oninput="this.value=this.value.replace(/\s/g,'')"></div>
         <input type="submit" value="Sign In" name="submit" class="btn">
      </form>
      <div class="back-link"><a href="../home.php"><i class="fas fa-arrow-left"></i> Back to EstateFlow</a></div>
   </div>
</div>
</body>
</html>
