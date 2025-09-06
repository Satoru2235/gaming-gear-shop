<?php
// unified login for admin + customer
session_start();
require_once 'config.php';

// ถ้าล็อกอินค้างอยู่แล้ว เด้งตามสิทธิ์
if (isset($_SESSION['admin_id'])) { header('Location: admin/dashboard.php'); exit; }
if (isset($_SESSION['customer_id'])) { header('Location: index.php'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username !== '' && $password !== '') {
    // ตรวจสิทธิ์แอดมินก่อน
    if ($stmt = $conn->prepare('SELECT id_admin, username FROM admin WHERE username=? AND password=? LIMIT 1')) {
      $stmt->bind_param('ss', $username, $password);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res && $res->num_rows === 1) {
        $adm = $res->fetch_assoc();
        $_SESSION['admin_id'] = (int)$adm['id_admin'];
        $_SESSION['admin_username'] = $adm['username'];
        header('Location: admin/dashboard.php'); exit;
      }
      $stmt->close();
    }
    // ไม่ใช่แอดมิน → ลองเป็นลูกค้า
    if ($stmt = $conn->prepare('SELECT id_customer, username FROM customer WHERE username=? AND password=? LIMIT 1')) {
      $stmt->bind_param('ss', $username, $password);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res && $res->num_rows === 1) {
        $u = $res->fetch_assoc();
        $_SESSION['customer_id'] = (int)$u['id_customer'];
        $_SESSION['customer_username'] = $u['username'];
        header('Location: index.php'); exit;
      } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
      }
      $stmt->close();
    }
  } else {
    $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน';
  }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <?php include $_SERVER['DOCUMENT_ROOT'].'/gaming_gear_shop/partials/favicon.php'; ?>

    <!-- Favicon (แนะนำครบชุด) -->
    <link rel="icon" href="images/gaming_logo_v2.svg" type="image/svg+xml">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16.png">
    <link rel="shortcut icon" href="favicon.ico"> <!-- มีค่อยใส่ -->

    <!-- (สวยขึ้นบนมือถือ) -->
    <link rel="apple-touch-icon" href="images/favicon-32.png">
    <meta name="theme-color" content="#0e0e10">

    <meta charset="UTF-8" />
    <title>เข้าสู่ระบบ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
    :root {
        --bg: #0e0e10;
        --panel: #1f1f23;
        --card: #2c2c33;
        --brand: #00d26a
    }

    * {
        box-sizing: border-box
    }

    body {
        font-family: Inter, Segoe UI, Arial, sans-serif;
        background: var(--bg);
        color: #fff;
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .top {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        background: #1f1f23;
        border-bottom: 1px solid #333;
        padding: 10px 16px
    }

    .top a {
        color: var(--brand);
        text-decoration: none;
        font-weight: 700
    }

    .spacer {
        height: 44px
    }

    .box {
        width: 360px;
        background: var(--card);
        padding: 26px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
    }

    h2 {
        margin: 0 0 16px
    }

    label {
        display: block;
        margin: 8px 0 6px
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 6px
    }

    .btn {
        width: 100%;
        margin-top: 12px;
        padding: 12px;
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-weight: 600;
        cursor: pointer;
    }

    .btn:hover {
        opacity: .95
    }

    .error {
        color: #ff6b6b;
        margin-bottom: 10px
    }

    .links {
        margin-top: 12px;
        text-align: center
    }

    .links a {
        color: var(--brand);
        text-decoration: none;
        margin: 0 6px;
        font-weight: 600
    }

    .links a:hover {
        opacity: .9
    }
    </style>
</head>

<body>
    <div class="top"><a href="index.php">← กลับหน้าหลัก</a></div>
    <div class="spacer"></div>

    <div class="box">
        <h2>เข้าสู่ระบบ</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

        <form method="post" action="">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn">เข้าสู่ระบบ</button>
        </form>

        <div class="links">
            <a href="index.php">← กลับหน้าหลัก</a>
            <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>
</body>

</html>