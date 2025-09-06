<?php
// Main page for customers
session_start();
require_once 'config.php';

// Fetch all products from the database
$result = $conn->query("SELECT * FROM product");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <?php include $_SERVER['DOCUMENT_ROOT'].'/gaming_gear_shop/partials/favicon.php'; ?>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ร้านขายอุปกรณ์เกมมิ่งเกียร์</title>

  <!-- styles -->
  <link rel="stylesheet" href="css/style.css?v=8" />

  <!-- FAVICON (ตัว G ในฟันเฟือง) -->
  <link rel="icon" type="image/png" sizes="32x32" href="/gaming_gear_shop/images/logo_main_32.png?v=8">
  <link rel="icon" type="image/png" sizes="16x16" href="/gaming_gear_shop/images/logo_main_16.png?v=8">
  <link rel="apple-touch-icon" href="/gaming_gear_shop/images/logo_main_32.png?v=8">
  <meta name="theme-color" content="#0e0e10">

  <style>
    :root{
      --bg:#0e0e10; --panel:#1f1f23; --card:#2c2c33; --text:#e6e6e6;
      --muted:#bbbbc2; --brand:#00d26a; --link:#9BE1B0;
    }
    *{box-sizing:border-box}
    body{font-family:Inter,Segoe UI,Arial,sans-serif;background:var(--bg);color:#fff;margin:0}

    /* Topbar */
    .topbar{position:sticky;top:0;z-index:10;background:var(--panel);border-bottom:1px solid #2a2a30}
    .nav{max-width:1200px;margin:0 auto;padding:10px 20px;display:flex;align-items:center;justify-content:space-between}
    .brand a{display:flex;align-items:center;gap:10px;text-decoration:none;color:#fff}
    .logo{height:28px;width:auto;display:block}
    .brand .brand-text{font-weight:800;letter-spacing:.3px}
    .nav-right a{color:var(--brand);text-decoration:none;margin-left:16px;font-weight:600}
    .nav-right a:hover{opacity:.85}
    @media (max-width:560px){.brand .brand-text{display:none}}

    /* Hero */
    .hero{max-width:980px;margin:0 auto;padding:36px 20px 8px;text-align:center}
    .hero-title{margin:0 0 8px;font-size:40px;line-height:1.2;letter-spacing:.3px}
    .hero-sub{margin:0 auto;max-width:760px;color:#c9c9cf;font-size:18px;line-height:1.6}
    .hero-accent{width:88px;height:4px;background:var(--brand);border-radius:999px;margin:14px auto 0;opacity:.9}
    @media (max-width:560px){.hero-title{font-size:30px}.hero-sub{font-size:16px}}

    /* Layout */
    .container{max-width:1200px;margin:0 auto;padding:10px 20px 40px}
    h2.section-title{margin:16px 0 18px;font-size:22px}

    /* Product grid */
    .product-grid{display:grid;gap:24px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
    .product{background:var(--card);border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.25);transition:transform .2s ease,box-shadow .2s ease}
    .product:hover{transform:translateY(-4px);box-shadow:0 6px 18px rgba(0,0,0,.35)}
    .product img{width:100%;height:180px;object-fit:cover;border-radius:8px;display:block}
    .product h3{margin:12px 0 6px;color:var(--text);font-size:18px}
    .product p{margin:0;font-size:14px;color:var(--muted);min-height:56px}
    .price{margin-top:10px;font-weight:800;color:var(--brand)}
    .actions{margin-top:12px}
    .btn{display:inline-block;background:#0d6efd;color:#fff;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:600}
    .btn:hover{opacity:.95}

    footer{text-align:center;padding:24px 20px;background:var(--panel);border-top:1px solid #2a2a30;color:#c9c9cf}
  </style>
</head>

<body>
  <!-- Topbar -->
  <div class="topbar">
    <div class="nav">
      <div class="brand">
        <a href="index.php" aria-label="กลับหน้าหลัก">
          <!-- โลโก้หลัก: SVG พร้อม fallback เป็น PNG -->
          <picture>
            <source srcset="/gaming_gear_shop/images/gaming_logo_v2.svg?v=8" type="image/svg+xml">
            <img src="/gaming_gear_shop/images/logo_main_32.png?v=8" alt="gaming_logo_v2" class="logo" loading="eager" decoding="sync">
          </picture>
          <span class="brand-text">SS GAMINGEARS</span>
        </a>
      </div>
      <div class="nav-right">
        <?php if (isset($_SESSION['customer_id'])): ?>
          <span style="color:#c9c9cf;margin-right:8px;">สวัสดี, <?php echo htmlspecialchars($_SESSION['customer_username']); ?></span>
          <a href="logout.php">ออกจากระบบ</a>
        <?php else: ?>
          <a href="login.php">เข้าสู่ระบบ</a>
          <a href="register.php">สมัครสมาชิก</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Hero -->
  <section class="hero">
    <h1 class="hero-title">ร้านขายอุปกรณ์เกมมิ่งเกียร์</h1>
    <p class="hero-sub">ยินดีต้อนรับสู่ร้านของเรา! คัดสรรสินค้าคุณภาพเพื่อคอเกมโดยเฉพาะ</p>
    <div class="hero-accent"></div>
  </section>

  <div class="container">
    <h2 class="section-title">สินค้าแนะนำ</h2>
    <div class="product-grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="product">
            <a href="product.php?id=<?php echo (int)$row['id_product']; ?>">
              <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            </a>
            <h3>
              <a href="product.php?id=<?php echo (int)$row['id_product']; ?>" style="color:inherit;text-decoration:none;">
                <?php echo htmlspecialchars($row['name']); ?>
              </a>
            </h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <div class="price">ราคา: ฿<?php echo number_format($row['price'], 2); ?></div>
            <div class="actions">
              <a class="btn" href="product.php?id=<?php echo (int)$row['id_product']; ?>">ดูรายละเอียด</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>ไม่มีสินค้าในขณะนี้</p>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> ร้านขายอุปกรณ์เกมมิ่งเกียร์. สงวนลิขสิทธิ์</p>
  </footer>
</body>
</html>
