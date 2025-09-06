<?php
// Customer registration page
session_start();
require_once 'config.php';

// Redirect to home if already logged in
if (isset($_SESSION['customer_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    // ตรวจสอบเบอร์โทร
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'กรุณากรอกเบอร์โทรให้ถูกต้อง (10 หลัก ตัวเลขเท่านั้น)';
    }

    if ($username && $password) {
        // Check for existing username
        $stmt = $conn->prepare('SELECT id_customer FROM customer WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $error = 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว กรุณาเลือกชื่ออื่น';
        } else {
            // Insert new customer
            $stmtIns = $conn->prepare('INSERT INTO customer (username, password, full_name, email, address, phone) VALUES (?, ?, ?, ?, ?, ?)');
            $stmtIns->bind_param('ssssss', $username, $password, $full_name, $email, $address, $phone);
            if ($stmtIns->execute()) {
                $success = 'สมัครสมาชิกสำเร็จ! คุณสามารถเข้าสู่ระบบได้แล้ว';
            } else {
                $error = 'เกิดข้อผิดพลาดในการสมัครสมาชิก กรุณาลองใหม่อีกครั้ง';
            }
            $stmtIns->close();
        }
        $stmt->close();
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

    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <style>
    /* เดิมอาจไม่มี type="tel" อยู่ในลิสต์ */
    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="tel"],
    textarea {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        box-sizing: border-box;
        /* กันล้น */
        display: block;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #0e0e10;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 0;
    }

    .register-box {
        background-color: #2c2c33;
        padding: 30px;
        border-radius: 8px;
        width: 450px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: none;
        border-radius: 4px;
    }

    textarea {
        resize: vertical;
    }

    input[type="submit"] {
        width: 100%;
        background-color: #00d26a;
        border: none;
        padding: 12px;
        color: #fff;
        cursor: pointer;
        border-radius: 4px;
        font-size: 1em;
    }

    input[type="submit"]:hover {
        background-color: #00b359;
    }

    .message {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 4px;
    }

    .error {
        background-color: #f44336;
    }

    .success {
        background-color: #4CAF50;
    }

    .link {
        text-align: center;
        margin-top: 10px;
    }

    .link a {
        color: #00d26a;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="register-box">
        <h2>สมัครสมาชิก</h2>
        <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">ชื่อผู้ใช้ (username):</label>
            <input type="text" id="username" name="username"
                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>

            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>

            <label for="full_name">ชื่อ-นามสกุล:</label>
            <input type="text" id="full_name" name="full_name"
                value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">

            <label for="email">อีเมล:</label>
            <input type="email" id="email" name="email" required placeholder="name@example.com"
                pattern="^[^\s@]+@[^\s@]+\.[^\s@]{2,}$" oninvalid="this.setCustomValidity('กรุณากรอกอีเมลให้ถูกต้อง')"
                oninput="this.setCustomValidity('')" />


            <label for="address">ที่อยู่:</label>
            <textarea id="address" name="address"
                rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

            <label for="phone">เบอร์โทร:</label>
            <input type="tel" id="phone" name="phone" required maxlength="10" pattern="^[0-9]{10}$"
                placeholder="เช่น 0812345678"
                oninvalid="this.setCustomValidity('กรุณากรอกเบอร์โทร 10 หลัก (ตัวเลขเท่านั้น)')"
                oninput="this.setCustomValidity('')" />


            <input type="submit" value="สมัครสมาชิก">
        </form>
        <div class="link">
            <span>เป็นสมาชิกแล้ว? </span><a href="login_user.php">เข้าสู่ระบบ</a>
        </div>
        <div class="link" style="margin-top:8px;">
            <a href="index.php">กลับหน้าหลัก</a>
        </div>
    </div>
</body>

</html>