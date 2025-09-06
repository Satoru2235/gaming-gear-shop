<?php
session_start();
require_once 'config.php';

// อ่าน id สินค้า
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(400);
  $product = null;
} else {
  $stmt = $conn->prepare("SELECT * FROM product WHERE id_product = ? LIMIT 1");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $product = $res && $res->num_rows === 1 ? $res->fetch_assoc() : null;
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
<?php include $_SERVER['DOCUMENT_ROOT'].'/gaming_gear_shop/partials/favicon.php'; ?>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ร้านขายอุปกรณ์เกมมิ่งเกียร์</title>

  <!-- styles ของคุณ -->
  <link rel="stylesheet" href="css/style.css?v=5" />

  <!-- FAVICON: ตัว G ในฟันเฟือง -->
  <link rel="icon" type="image/png" sizes="32x32" href="images/gaming_logo_gear_g_32.png?v=5">
  <link rel="icon" type="image/png" sizes="16x16" href="images/gaming_logo_gear_g_16.png?v=5">
  <!-- (ถ้ามีไฟล์ .ico ให้ใส่ด้วย) -->
  <!-- <link rel="shortcut icon" href="favicon_g.ico?v=5"> -->

  <meta name="theme-color" content="#0e0e10">


    <meta charset="UTF-8">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'ไม่พบสินค้า'; ?> | Gaming Gear Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    :root {
        --bg: #0e0e10;
        --panel: #1f1f23;
        --card: #2c2c33;
        --text: #e6e6e6;
        --muted: #bbbbc2;
        --brand: #00d26a
    }

    * {
        box-sizing: border-box
    }

    body {
        font-family: Inter, Segoe UI, Arial, sans-serif;
        background: var(--bg);
        color: #fff;
        margin: 0
    }

    .topbar {
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--panel);
        border-bottom: 1px solid #2a2a30
    }

    .nav {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center
    }

    .nav a {
        color: var(--brand);
        text-decoration: none;
        font-weight: 700
    }

    .wrap {
        max-width: 1100px;
        margin: 24px auto 40px;
        padding: 0 20px
    }

    .card {
        background: var(--card);
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 22px;
    }

    .img {
        width: 100%;
        height: 380px;
        object-fit: cover;
        border-radius: 10px;
        background: #111;
    }

    .title {
        margin: 0 0 8px;
        font-size: 26px;
        color: var(--text)
    }

    .desc {
        margin: 0 0 12px;
        color: var(--muted);
        line-height: 1.6
    }

    .price {
        font-weight: 800;
        color: var(--brand);
        font-size: 20px;
        margin: 10px 0
    }

    .meta {
        color: #c9c9cf;
        font-size: 14px
    }

    .actions {
        margin-top: 16px
    }

    .btn {
        display: inline-block;
        background: #0d6efd;
        color: #fff;
        padding: 10px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700
    }

    .btn-outline {
        display: inline-block;
        color: #fff;
        padding: 10px 14px;
        border: 1px solid #3a3a42;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        margin-left: 8px
    }

    .notfound {
        max-width: 800px;
        margin: 60px auto;
        text-align: center;
        color: #c9c9cf
    }

    @media (max-width:860px) {
        .card {
            grid-template-columns: 1fr
        }

        .img {
            height: 260px
        }
    }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="nav">
            <a href="index.php">← กลับหน้าหลัก</a>
            <div>
                <?php if (isset($_SESSION['customer_id'])): ?>
                <span style="color:#c9c9cf; margin-right:8px;">สวัสดี,
                    <?php echo htmlspecialchars($_SESSION['customer_username']); ?></span>
                <a href="logout_user.php">ออกจากระบบ</a>
                <?php else: ?>
                <a href="login_user.php" style="margin-right:12px;">เข้าสู่ระบบ</a>
                <a href="register.php">สมัครสมาชิก</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="wrap">
        <?php if (!$product): ?>
        <div class="notfound">
            <h2>ไม่พบสินค้า</h2>
            <p>อาจถูกลบหรือรหัสไม่ถูกต้อง</p>
            <p><a href="index.php" class="btn">กลับหน้าหลัก</a></p>
        </div>
        <?php else: ?>
        <div class="card">
            <img class="img" src="<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div>
                <h1 class="title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <div class="price">ราคา: ฿<?php echo number_format($product['price'], 2); ?></div>
                <div class="meta">คงเหลือในสต็อก: <?php echo (int)$product['stock']; ?></div>

                <div class="actions">
                    <!-- ปุ่มตะกร้า (วางไว้ก่อน เผื่อต่อระบบในอนาคต) -->
                    <a href="#" class="btn"
                        onclick="alert('ฟีเจอร์ตะกร้าจะถูกเพิ่มภายหลัง'); return false;">เพิ่มลงตะกร้า</a>
                    <a href="index.php" class="btn-outline">กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>