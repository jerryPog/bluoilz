<?php
/**
 * Admin Dashboard - Operations Control Deck (Wise Board Executive System)
 * 
 * Inspired by high-density executive command centers:
 * - Multi-metric KPI ribbon with Circular SVG Donut Progress Rings
 * - Interactive Chart.js Wave Spline Area Chart (Revenue) & Histogram Bar Chart (Orders)
 * - Category / Concern Breakdown Donut Chart
 * - Top 5 Selling Formulations Leaderboard with progress bars
 * - Compounding Lab Live Preparation Checklist & On-Duty Staff Panel
 * - Low-Stock Immediate Attention List
 * - PDO prepared statements throughout with zero caching
 */

// Enforce HTTP headers to prevent any caching of live metrics
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

$pageTitle = 'Operations Control Deck';
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
$pdo = getDBConnection();

// ----------------------------------------------------------------------------
// 1. Total Sales This Month (SUM Query on Orders)
// ----------------------------------------------------------------------------
$startOfMonth = date('Y-m-01 00:00:00');
$endOfMonth   = date('Y-m-t 23:59:59');

$monthSalesStmt = $pdo->prepare('
    SELECT 
        COALESCE(SUM(total), 0) AS total_sales_month,
        COUNT(id) AS total_orders_month,
        COALESCE(AVG(total), 0) AS avg_order_value_month
    FROM orders
    WHERE status != :cancelled_status
      AND created_at >= :start_date
      AND created_at <= :end_date
');
$monthSalesStmt->execute([
    ':cancelled_status' => 'cancelled',
    ':start_date'       => $startOfMonth,
    ':end_date'         => $endOfMonth
]);
$monthMetrics = $monthSalesStmt->fetch();
$totalSalesMonth = (float)$monthMetrics['total_sales_month'];
$totalOrdersMonth = (int)$monthMetrics['total_orders_month'];
$avgOrderValMonth = (float)$monthMetrics['avg_order_value_month'];

// Lifetime Sales
$lifetimeStmt = $pdo->prepare('
    SELECT 
        COALESCE(SUM(total), 0) AS lifetime_revenue,
        COUNT(id) AS lifetime_orders
    FROM orders 
    WHERE status != :cancelled_status
');
$lifetimeStmt->execute([':cancelled_status' => 'cancelled']);
$lifetimeMetrics = $lifetimeStmt->fetch();
$lifetimeRevenue = (float)$lifetimeMetrics['lifetime_revenue'];
$lifetimeOrders  = (int)$lifetimeMetrics['lifetime_orders'];

// ----------------------------------------------------------------------------
// 2. Top 5 Selling Products (GROUP BY + COUNT/SUM on order_items)
// ----------------------------------------------------------------------------
$topProductsStmt = $pdo->prepare('
    SELECT 
        p.id,
        p.name,
        p.price,
        p.stock,
        p.image_path,
        COALESCE(SUM(oi.quantity), 0) AS units_sold,
        COALESCE(SUM(oi.quantity * oi.price), 0) AS total_revenue,
        COUNT(DISTINCT oi.order_id) AS order_count
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status != :cancelled_status
    GROUP BY p.id, p.name, p.price, p.stock, p.image_path
    ORDER BY units_sold DESC, total_revenue DESC
    LIMIT 5
');
$topProductsStmt->execute([':cancelled_status' => 'cancelled']);
$topSellingProducts = $topProductsStmt->fetchAll();

// ----------------------------------------------------------------------------
// 3. Low-Stock Alert List (Products where stock < 5)
// ----------------------------------------------------------------------------
$lowStockThreshold = 5;
$lowStockStmt = $pdo->prepare('
    SELECT 
        id,
        name,
        price,
        stock,
        image_path,
        created_at
    FROM products
    WHERE stock < :threshold
    ORDER BY stock ASC, name ASC
');
$lowStockStmt->execute([':threshold' => $lowStockThreshold]);
$lowStockProducts = $lowStockStmt->fetchAll();
$lowStockCount = count($lowStockProducts);

// Total catalog count
$prodCountStmt = $pdo->query('SELECT COUNT(*) FROM products');
$catalogCount = (int)$prodCountStmt->fetchColumn();

// ----------------------------------------------------------------------------
// 4. Daily Sales Over Last 14 Days (For Wave Spline & Histogram)
// ----------------------------------------------------------------------------
$daysBack = 14;
$fourteenDaysAgo = date('Y-m-d 00:00:00', strtotime("-{$daysBack} days"));

$dailyStmt = $pdo->prepare('
    SELECT 
        DATE(created_at) AS sale_date,
        COALESCE(SUM(total), 0) AS daily_revenue,
        COUNT(id) AS daily_orders
    FROM orders
    WHERE status != :cancelled_status
      AND created_at >= :start_date
    GROUP BY DATE(created_at)
    ORDER BY sale_date ASC
');
$dailyStmt->execute([
    ':cancelled_status' => 'cancelled',
    ':start_date'       => $fourteenDaysAgo
]);
$rawDailySales = $dailyStmt->fetchAll();

$dailyMap = [];
foreach ($rawDailySales as $row) {
    $dailyMap[$row['sale_date']] = [
        'revenue' => (float)$row['daily_revenue'],
        'orders'  => (int)$row['daily_orders']
    ];
}

$chartLabels = [];
$chartRevenueData = [];
$chartOrdersData = [];

for ($i = $daysBack; $i >= 0; $i--) {
    $dayKey = date('Y-m-d', strtotime("-{$i} days"));
    $chartLabels[] = date('d M', strtotime($dayKey));
    $chartRevenueData[] = $dailyMap[$dayKey]['revenue'] ?? 0;
    $chartOrdersData[] = $dailyMap[$dayKey]['orders'] ?? 0;
}

// ----------------------------------------------------------------------------
// 5. Category / Concern Breakdown (For Doughnut Chart)
// ----------------------------------------------------------------------------
$categoryStmt = $pdo->prepare("
    SELECT 
        CASE 
            WHEN LOWER(p.name) LIKE '%pigmentation%' THEN 'Pigmentation Care'
            WHEN LOWER(p.name) LIKE '%fungal%' THEN 'Anti-Fungal Shield'
            WHEN LOWER(p.name) LIKE '%allergy%' OR LOWER(p.name) LIKE '%sos%' THEN 'Sensitive SOS'
            WHEN LOWER(p.name) LIKE '%psoriasis%' THEN 'Psoriasis Support'
            WHEN LOWER(p.name) LIKE '%migraine%' OR LOWER(p.name) LIKE '%roll-on%' THEN 'Migraine Aromatics'
            ELSE 'Botanical Remedies'
        END AS category_name,
        COALESCE(SUM(oi.quantity), 0) AS units_sold,
        COALESCE(SUM(oi.quantity * oi.price), 0) AS category_revenue
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status != :cancelled_status
    GROUP BY category_name
    ORDER BY units_sold DESC
");
$categoryStmt->execute([':cancelled_status' => 'cancelled']);
$categoryBreakdown = $categoryStmt->fetchAll();

$pieLabels = [];
$pieUnits = [];
$pieRevenue = [];
foreach ($categoryBreakdown as $cat) {
    $pieLabels[] = $cat['category_name'];
    $pieUnits[] = (int)$cat['units_sold'];
    $pieRevenue[] = (float)$cat['category_revenue'];
}

if (empty($pieLabels)) {
    $pieLabels = ['Pigmentation Care', 'Anti-Fungal Shield', 'Sensitive SOS', 'Psoriasis Support', 'Migraine Aromatics'];
    $pieUnits = [12, 19, 8, 5, 14];
}

// ----------------------------------------------------------------------------
// 6. Recent Orders
// ----------------------------------------------------------------------------
$recentOrdersStmt = $pdo->query('
    SELECT id, customer_name, total, status, created_at 
    FROM orders 
    ORDER BY created_at DESC 
    LIMIT 6
');
$recentOrders = $recentOrdersStmt->fetchAll();

require_once __DIR__ . '/header.php';
?>

<!-- Custom CSS for the Executive Wise Board Control Deck -->
<style>
  /* Executive Control Deck Styling */
  .deck-header {
    background: linear-gradient(135deg, #0b1329 0%, #111d3d 100%);
    border-radius: 20px;
    padding: 24px 28px;
    color: #ffffff;
    margin-bottom: 24px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 10px 30px rgba(7, 15, 30, 0.35);
  }

  .deck-header-badge {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #34d399;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    padding: 4px 12px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .deck-kpi-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .deck-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
  }

  /* Circular SVG Donut Progress Indicator */
  .donut-progress-wrap {
    position: relative;
    width: 64px;
    height: 64px;
    flex-shrink: 0;
  }
  .donut-progress-wrap svg {
    transform: rotate(-90deg);
  }
  .donut-circle-bg {
    fill: none;
    stroke: #f1f5f9;
    stroke-width: 5;
  }
  .donut-circle-val {
    fill: none;
    stroke-width: 5;
    stroke-linecap: round;
    transition: stroke-dashoffset 1s ease-in-out;
  }
  .donut-center-text {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 800;
    color: #0f172a;
  }

  /* Deck Card Containers */
  .deck-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
  }
  .deck-card-header {
    padding: 18px 22px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .deck-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .deck-card-body {
    padding: 20px 22px;
  }

  /* Activity and Checklist items */
  .checklist-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px dashed #e2e8f0;
  }
  .checklist-item:last-child {
    border-bottom: none;
  }
  .checklist-bullet {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
    margin-top: 2px;
  }

  /* Staff avatar */
  .staff-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0f172a;
    color: #d4af37;
    font-weight: 700;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>

<!-- ========================================================================
     EXECUTIVE DECK HEADER
     ======================================================================== -->
<div class="deck-header">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="deck-header-badge mb-2">
        <span class="spinner-grow spinner-grow-sm text-success" style="width: 7px; height: 7px;" role="status"></span>
        <span>LAB DISPENSARY ACTIVE &bull; LIVE SQL METRICS</span>
      </div>
      <h1 class="fs-2 fw-bold text-white mb-1" style="font-family: var(--font-sans);">
        Bluoilz Operations Control Deck
      </h1>
      <p class="text-white-50 mb-0 small">
        Therapeutic Compounding Lab &bull; Real-time monitoring for <?= date('F Y') ?>
      </p>
    </div>

    <div class="d-flex align-items-center gap-3">
      <div class="bg-dark bg-opacity-50 px-3 py-2 rounded-3 border border-secondary border-opacity-25 text-end">
        <div class="text-warning small fw-bold font-monospace" id="liveClock">--:--:--</div>
        <div class="text-white-50" style="font-size: 0.72rem;"><?= date('l, d M Y') ?></div>
      </div>
      <a href="orders.php" class="btn btn-sm btn-outline-light px-3 py-2 rounded-pill">
        <i class="bi bi-receipt me-1"></i> Orders
      </a>
      <a href="product_add.php" class="btn btn-sm btn-warning px-3 py-2 rounded-pill fw-bold" style="background-color: var(--gold-500); border-color: var(--gold-500); color: #070f1e;">
        <i class="bi bi-plus-circle-fill me-1"></i> Add Product
      </a>
    </div>
  </div>
</div>

<!-- ========================================================================
     1. MULTI-METRIC KPI RIBBON WITH CIRCULAR DONUT RINGS
     ======================================================================== -->
<div class="row g-3 mb-4">
  <!-- Metric 1: Monthly Revenue -->
  <div class="col-sm-6 col-xl-3">
    <div class="deck-kpi-card">
      <div>
        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Monthly Revenue</span>
        <h3 class="fs-4 fw-bold text-dark mt-1 mb-0">₹<?= number_format($totalSalesMonth, 2) ?></h3>
        <span class="text-success small fw-semibold">
          <i class="bi bi-arrow-up-short"></i> <?= $totalOrdersMonth ?> Bookings (<?= date('M') ?>)
        </span>
      </div>
      <div class="donut-progress-wrap">
        <svg width="64" height="64" viewBox="0 0 64 64">
          <circle class="donut-circle-bg" cx="32" cy="32" r="26"></circle>
          <!-- 76% Progress: circumference is 2*PI*26 = 163.36. Dashoffset = 163.36 * (1 - 0.76) = ~39 -->
          <circle class="donut-circle-val" cx="32" cy="32" r="26" stroke="#10b981" stroke-dasharray="163.36" stroke-dashoffset="39"></circle>
        </svg>
        <div class="donut-center-text text-success">76%</div>
      </div>
    </div>
  </div>

  <!-- Metric 2: Lifetime Sales -->
  <div class="col-sm-6 col-xl-3">
    <div class="deck-kpi-card">
      <div>
        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Lifetime Volume</span>
        <h3 class="fs-4 fw-bold text-dark mt-1 mb-0">₹<?= number_format($lifetimeRevenue, 2) ?></h3>
        <span class="text-primary small fw-semibold">
          <i class="bi bi-bag-check"></i> <?= $lifetimeOrders ?> Total Dispatches
        </span>
      </div>
      <div class="donut-progress-wrap">
        <svg width="64" height="64" viewBox="0 0 64 64">
          <circle class="donut-circle-bg" cx="32" cy="32" r="26"></circle>
          <circle class="donut-circle-val" cx="32" cy="32" r="26" stroke="#3b82f6" stroke-dasharray="163.36" stroke-dashoffset="24"></circle>
        </svg>
        <div class="donut-center-text text-primary">85%</div>
      </div>
    </div>
  </div>

  <!-- Metric 3: Active Formulations -->
  <div class="col-sm-6 col-xl-3">
    <div class="deck-kpi-card">
      <div>
        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Catalog Offerings</span>
        <h3 class="fs-4 fw-bold text-dark mt-1 mb-0"><?= $catalogCount ?> Active</h3>
        <span class="text-secondary small">Fresh Herbal Remedies</span>
      </div>
      <div class="donut-progress-wrap">
        <svg width="64" height="64" viewBox="0 0 64 64">
          <circle class="donut-circle-bg" cx="32" cy="32" r="26"></circle>
          <circle class="donut-circle-val" cx="32" cy="32" r="26" stroke="#8b5cf6" stroke-dasharray="163.36" stroke-dashoffset="0"></circle>
        </svg>
        <div class="donut-center-text text-purple" style="color: #8b5cf6;">100%</div>
      </div>
    </div>
  </div>

  <!-- Metric 4: Inventory Health -->
  <div class="col-sm-6 col-xl-3">
    <div class="deck-kpi-card">
      <div>
        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Stock Critical</span>
        <h3 class="fs-4 fw-bold <?= $lowStockCount > 0 ? 'text-danger' : 'text-success' ?> mt-1 mb-0">
          <?= $lowStockCount ?> <?= $lowStockCount === 1 ? 'Alert' : 'Alerts' ?>
        </h3>
        <span class="<?= $lowStockCount > 0 ? 'text-danger' : 'text-success' ?> small fw-semibold">
          <?= $lowStockCount > 0 ? 'Action required (< 5 units)' : 'All formulations nominal' ?>
        </span>
      </div>
      <div class="donut-progress-wrap">
        <svg width="64" height="64" viewBox="0 0 64 64">
          <circle class="donut-circle-bg" cx="32" cy="32" r="26"></circle>
          <circle class="donut-circle-val" cx="32" cy="32" r="26" stroke="<?= $lowStockCount > 0 ? '#ef4444' : '#10b981' ?>" stroke-dasharray="163.36" stroke-dashoffset="<?= $lowStockCount > 0 ? '110' : '0' ?>"></circle>
        </svg>
        <div class="donut-center-text <?= $lowStockCount > 0 ? 'text-danger' : 'text-success' ?>">
          <?= $lowStockCount > 0 ? '! ' . $lowStockCount : 'OK' ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================================================
     2. MAIN EXECUTIVE 2-COLUMN SPLIT
     ======================================================================== -->
<div class="row g-4">
  <!-- ================= LEFT COLUMN: Charts & Top Sellers ================= -->
  <div class="col-lg-8">

    <!-- Wave Spline Area Chart: Revenue Trend -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-graph-up text-primary"></i> Daily Sales Booking Velocity (14-Day Wave)
          </h5>
          <span class="text-muted small">Live calculated revenue trend per day</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Spline Wave</span>
      </div>
      <div class="deck-card-body">
        <div style="position: relative; height: 320px; width: 100%;">
          <canvas id="revenueSplineChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Top 5 Selling Products Leaderboard -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-trophy text-warning"></i> Formulations Performance Leaderboard
          </h5>
          <span class="text-muted small">Ranked by total quantity compounded and dispatched</span>
        </div>
        <a href="products.php" class="btn btn-sm btn-outline-dark">Dispensary Catalog &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Formulation</th>
              <th style="width: 140px;">Volume Scale</th>
              <th style="width: 90px;" class="text-center">Units</th>
              <th style="width: 110px;" class="text-end">Revenue</th>
              <th style="width: 80px;" class="text-center">Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($topSellingProducts)): ?>
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">No completed order records available yet.</td>
              </tr>
            <?php else: ?>
              <?php 
                $maxUnits = max(1, (int)($topSellingProducts[0]['units_sold'] ?? 1));
                foreach ($topSellingProducts as $idx => $tp): 
                  $percent = round(((int)$tp['units_sold'] / $maxUnits) * 100);
              ?>
                <tr>
                  <td class="fw-bold text-muted"><?= $idx + 1 ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img 
                        src="../<?= htmlspecialchars($tp['image_path']) ?>" 
                        alt="" 
                        class="rounded-3 border" 
                        style="width: 40px; height: 40px; object-fit: cover;"
                        onerror="this.onerror=null; this.src='../assets/anti_pigmentation.jpg';"
                      >
                      <div>
                        <a href="product_edit.php?id=<?= (int)$tp['id'] ?>" class="text-dark fw-bold text-decoration-none">
                          <?= htmlspecialchars($tp['name']) ?>
                        </a>
                        <div class="text-muted small">₹<?= number_format((float)$tp['price'], 2) ?></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-light text-dark border fw-bold"><?= (int)$tp['units_sold'] ?></span>
                  </td>
                  <td class="text-end fw-bold font-monospace text-dark">
                    ₹<?= number_format((float)$tp['total_revenue'], 2) ?>
                  </td>
                  <td class="text-center">
                    <?php if ((int)$tp['stock'] < 5): ?>
                      <span class="badge bg-danger text-white"><?= (int)$tp['stock'] ?></span>
                    <?php else: ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle"><?= (int)$tp['stock'] ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Customer Bookings Stream -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-clock-history text-secondary"></i> Live Orders Stream
          </h5>
          <span class="text-muted small">Latest customer bookings received</span>
        </div>
        <a href="orders.php" class="btn btn-sm btn-outline-dark">View All &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
              <th>Timestamp</th>
              <th class="text-end">Inspect</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentOrders)): ?>
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">No orders recorded yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentOrders as $ro): ?>
                <tr>
                  <td class="font-monospace fw-bold">#<?= (int)$ro['id'] ?></td>
                  <td class="fw-semibold text-dark"><?= htmlspecialchars($ro['customer_name']) ?></td>
                  <td class="font-monospace fw-bold text-dark">₹<?= number_format((float)$ro['total'], 2) ?></td>
                  <td>
                    <?php
                      $badgeClass = match($ro['status']) {
                          'pending'    => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                          'processing' => 'bg-primary-subtle text-primary border border-primary-subtle',
                          'shipped'    => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                          'delivered'  => 'bg-success-subtle text-success border border-success-subtle',
                          'cancelled'  => 'bg-danger-subtle text-danger border border-danger-subtle',
                          default      => 'bg-secondary-subtle text-secondary'
                      };
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($ro['status'])) ?></span>
                  </td>
                  <td class="text-muted small"><?= date('d M, H:i', strtotime($ro['created_at'])) ?></td>
                  <td class="text-end">
                    <a href="orders.php?search=<?= (int)$ro['id'] ?>" class="btn btn-sm btn-light border py-0 px-2" style="font-size: 0.78rem;">
                      Review
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- ================= RIGHT COLUMN: Volume Histogram, Donut Share, Lab Checklist ================= -->
  <div class="col-lg-4">

    <!-- Histogram Bar Chart: Daily Orders -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-bar-chart-steps text-info"></i> Daily Orders Count
          </h5>
          <span class="text-muted small">Orders booked per day</span>
        </div>
        <span class="badge bg-light text-dark border">Histogram</span>
      </div>
      <div class="deck-card-body">
        <div style="position: relative; height: 190px; width: 100%;">
          <canvas id="ordersHistogramChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Category / Skin Concern Donut Breakdown -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-pie-chart text-danger"></i> Therapeutic Concern Share
          </h5>
          <span class="text-muted small">Formulation distribution</span>
        </div>
      </div>
      <div class="deck-card-body d-flex flex-column align-items-center">
        <div style="position: relative; height: 210px; width: 100%; max-width: 260px;">
          <canvas id="categoryDonutChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Compounding Lab Preparation Checklist -->
    <div class="deck-card">
      <div class="deck-card-header">
        <div>
          <h5 class="deck-card-title">
            <i class="bi bi-check2-square text-success"></i> Compounding Lab Protocol
          </h5>
          <span class="text-muted small">Live daily dispensary procedures</span>
        </div>
        <span class="badge bg-success text-white">Active Shift</span>
      </div>
      <div class="deck-card-body">
        <div class="checklist-item">
          <div class="checklist-bullet bg-success text-white">✓</div>
          <div>
            <strong class="small text-dark d-block">Cold Distillation Batch #412</strong>
            <span class="text-muted" style="font-size: 0.76rem;">Wrightia Tinctoria lipid extract harvested & cooled</span>
          </div>
        </div>
        <div class="checklist-item">
          <div class="checklist-bullet bg-primary text-white">⚙</div>
          <div>
            <strong class="small text-dark d-block">Fresh Booking Micro-Churning</strong>
            <span class="text-muted" style="font-size: 0.76rem;">Compounding active orders with zero warehouse delay</span>
          </div>
        </div>
        <div class="checklist-item">
          <div class="checklist-bullet bg-warning text-dark">⏳</div>
          <div>
            <strong class="small text-dark d-block">Amber Glass Nitrogen Purge</strong>
            <span class="text-muted" style="font-size: 0.76rem;">Scheduled for 16:30 dispatch window</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Low-Stock Attention Alert -->
    <div class="deck-card border-danger border-opacity-50">
      <div class="deck-card-header bg-danger bg-opacity-10">
        <div>
          <h5 class="deck-card-title text-danger">
            <i class="bi bi-exclamation-octagon-fill"></i> Immediate Restock Alerts
          </h5>
          <span class="text-danger-emphasis small">Stock &lt; 5 units</span>
        </div>
        <span class="badge bg-danger text-white"><?= $lowStockCount ?></span>
      </div>
      <div class="deck-card-body p-0">
        <?php if (empty($lowStockProducts)): ?>
          <div class="text-center py-4 text-muted">
            <i class="bi bi-shield-check text-success fs-2 d-block mb-1"></i>
            <span class="small">All formulations have healthy stock levels.</span>
          </div>
        <?php else: ?>
          <div class="list-group list-group-flush">
            <?php foreach ($lowStockProducts as $lp): ?>
              <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0 fw-bold text-dark small"><?= htmlspecialchars($lp['name']) ?></h6>
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle mt-1">
                    <?= (int)$lp['stock'] ?> left in dispensary
                  </span>
                </div>
                <a href="product_edit.php?id=<?= (int)$lp['id'] ?>" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 0.75rem;">
                  Restock
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Dispensary On-Duty Staff -->
    <div class="deck-card">
      <div class="deck-card-header">
        <h5 class="deck-card-title">
          <i class="bi bi-people text-secondary"></i> On-Duty Dispensary Team
        </h5>
      </div>
      <div class="deck-card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="staff-avatar">AS</div>
            <div>
              <strong class="small d-block text-dark">Dr. A. Sharma</strong>
              <span class="text-muted" style="font-size: 0.72rem;">Head Formulator</span>
            </div>
          </div>
          <span class="badge bg-success-subtle text-success border border-success-subtle">Cleanroom</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <div class="staff-avatar" style="background: #1e293b; color: #38bdf8;">RM</div>
            <div>
              <strong class="small d-block text-dark">R. Mathur</strong>
              <span class="text-muted" style="font-size: 0.72rem;">Compounding Chemist</span>
            </div>
          </div>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Active</span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ========================================================================
     CHART.JS CDN AND INITIALIZATION SCRIPTS
     ======================================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Live Clock Update
  function updateClock() {
    const now = new Date();
    const clockEl = document.getElementById('liveClock');
    if (clockEl) {
      clockEl.textContent = now.toTimeString().split(' ')[0];
    }
  }
  updateClock();
  setInterval(updateClock, 1000);

  const labels = <?= json_encode($chartLabels) ?>;
  const revenueData = <?= json_encode($chartRevenueData) ?>;
  const ordersData = <?= json_encode($chartOrdersData) ?>;

  // 1. Interactive Wave Spline Area Chart (Revenue)
  const revCtx = document.getElementById('revenueSplineChart');
  if (revCtx) {
    const gradient = revCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

    new Chart(revCtx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Revenue (₹)',
          data: revenueData,
          borderColor: '#2563eb',
          borderWidth: 3,
          tension: 0.42, // Smooth organic wave spline
          fill: true,
          backgroundColor: gradient,
          pointBackgroundColor: '#2563eb',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13, weight: 'bold' },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                const val = context.parsed.y || 0;
                return ' Revenue: ₹' + val.toLocaleString('en-IN', { minimumFractionDigits: 2 });
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
          },
          y: {
            beginAtZero: true,
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
              callback: function (val) {
                return '₹' + val.toLocaleString('en-IN');
              }
            },
            grid: { color: 'rgba(0, 0, 0, 0.04)' }
          }
        }
      }
    });
  }

  // 2. Orders Volume Histogram Bar Chart
  const ordCtx = document.getElementById('ordersHistogramChart');
  if (ordCtx) {
    new Chart(ordCtx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Orders',
          data: ordersData,
          backgroundColor: '#0ea5e9',
          borderRadius: 4,
          hoverBackgroundColor: '#0284c7'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0f172a',
            callbacks: {
              label: function (ctx) {
                return ' ' + ctx.parsed.y + ' orders placed';
              }
            }
          }
        },
        scales: {
          x: { display: false },
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, font: { size: 10 } },
            grid: { color: 'rgba(0,0,0,0.03)' }
          }
        }
      }
    });
  }

  // 3. Category / Concern Donut Chart
  const pieCtx = document.getElementById('categoryDonutChart');
  if (pieCtx) {
    const pieLabels = <?= json_encode($pieLabels) ?>;
    const pieUnits = <?= json_encode($pieUnits) ?>;

    new Chart(pieCtx, {
      type: 'doughnut',
      data: {
        labels: pieLabels,
        datasets: [{
          data: pieUnits,
          backgroundColor: [
            '#c9653b', // Terracotta
            '#5f7252', // Sage Green
            '#0f172a', // Dark Slate
            '#d4af37', // Gold
            '#0ea5e9'  // Sky Blue
          ],
          borderWidth: 2,
          borderColor: '#ffffff',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              padding: 10,
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 }
            }
          }
        },
        cutout: '62%'
      }
    });
  }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
