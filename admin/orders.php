<?php
/**
 * Order Management Page
 * 
 * Lists orders using a JOIN across orders and order_items with product details.
 * Supports status filtering, search, inline status updates, and modal status updates.
 * Sorted by most recent first by default.
 */
$pageTitle = 'Order Management';
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = getDBConnection();
$allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

// ----------------------------------------------------------------------------
// 1. Handle Order Status Update (Inline & Modal submissions)
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $_SESSION['flash_error'] = 'Security validation failed (invalid CSRF token).';
    } else {
        $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
        $newStatus = trim($_POST['new_status'] ?? '');

        if (!$orderId || !in_array($newStatus, $allowedStatuses, true)) {
            $_SESSION['flash_error'] = 'Invalid order ID or unrecognized status selected.';
        } else {
            try {
                // Verify order exists
                $checkStmt = $pdo->prepare('SELECT id, status FROM orders WHERE id = :id LIMIT 1');
                $checkStmt->execute([':id' => $orderId]);
                $existingOrder = $checkStmt->fetch();

                if (!$existingOrder) {
                    $_SESSION['flash_error'] = "Order #{$orderId} does not exist in the database.";
                } elseif ($existingOrder['status'] === $newStatus) {
                    $_SESSION['flash_success'] = "Order #{$orderId} was already set to '" . ucfirst($newStatus) . "'.";
                } else {
                    $updateStmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
                    $updateStmt->execute([
                        ':status' => $newStatus,
                        ':id'     => $orderId
                    ]);

                    $_SESSION['flash_success'] = "Order #{$orderId} status updated from '" . ucfirst($existingOrder['status']) . "' to '" . ucfirst($newStatus) . "'.";
                }
            } catch (PDOException $e) {
                error_log('Order Status Update Error: ' . $e->getMessage());
                $_SESSION['flash_error'] = 'Database error: Could not update order status.';
            }
        }
    }

    // Preserve filter and search query parameters in redirect
    $redirectParams = [];
    if (!empty($_POST['return_status'])) {
        $redirectParams['status'] = $_POST['return_status'];
    }
    if (!empty($_POST['return_search'])) {
        $redirectParams['search'] = $_POST['return_search'];
    }
    $queryStr = !empty($redirectParams) ? '?' . http_build_query($redirectParams) : '';

    header('Location: orders.php' . $queryStr);
    exit;
}

// ----------------------------------------------------------------------------
// 2. Filter & Search Parameters
// ----------------------------------------------------------------------------
$selectedStatus = trim($_GET['status'] ?? 'all');
$search = trim($_GET['search'] ?? '');

// Fetch status counts for filter navigation pills
$statusCounts = ['all' => 0];
foreach ($allowedStatuses as $st) {
    $statusCounts[$st] = 0;
}

try {
    $countStmt = $pdo->query('SELECT status, COUNT(*) AS count FROM orders GROUP BY status');
    while ($row = $countStmt->fetch()) {
        if (isset($statusCounts[$row['status']])) {
            $statusCounts[$row['status']] = (int)$row['count'];
        }
        $statusCounts['all'] += (int)$row['count'];
    }
} catch (Exception $e) {
    error_log('Status Count Error: ' . $e->getMessage());
}

// ----------------------------------------------------------------------------
// 3. Query Orders with JOIN across order_items and products
// ----------------------------------------------------------------------------
$orders = [];
$error = '';

try {
    $sql = '
        SELECT 
            o.id AS order_id,
            o.customer_name,
            o.address,
            o.status,
            o.total,
            o.created_at,
            oi.id AS order_item_id,
            oi.quantity,
            oi.price AS item_price,
            p.id AS product_id,
            p.name AS product_name,
            p.image_path AS product_image
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE 1=1
    ';

    $params = [];

    // Filter by status
    if ($selectedStatus !== 'all' && in_array($selectedStatus, $allowedStatuses, true)) {
        $sql .= ' AND o.status = :status';
        $params[':status'] = $selectedStatus;
    }

    // Search by customer name, address, or order ID
    if ($search !== '') {
        $sql .= ' AND (o.customer_name LIKE :search_name OR o.address LIKE :search_addr OR o.id = :search_id)';
        $params[':search_name'] = '%' . $search . '%';
        $params[':search_addr'] = '%' . $search . '%';
        $params[':search_id']   = is_numeric($search) ? (int)$search : 0;
    }

    // Sort by most recent first by default
    $sql .= ' ORDER BY o.created_at DESC, o.id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // Group items under each parent order
    foreach ($rows as $row) {
        $id = (int)$row['order_id'];
        if (!isset($orders[$id])) {
            $orders[$id] = [
                'id'            => $id,
                'customer_name' => $row['customer_name'],
                'address'       => $row['address'],
                'status'        => $row['status'],
                'total'         => (float)$row['total'],
                'created_at'    => $row['created_at'],
                'items'         => []
            ];
        }

        if (!empty($row['order_item_id'])) {
            $orders[$id]['items'][] = [
                'id'           => (int)$row['order_item_id'],
                'product_id'   => $row['product_id'],
                'product_name' => $row['product_name'] ?? 'Custom Formulation',
                'image_path'   => $row['product_image'],
                'quantity'     => (int)$row['quantity'],
                'price'        => (float)$row['item_price'],
                'subtotal'     => (int)$row['quantity'] * (float)$row['item_price']
            ];
        }
    }
} catch (Exception $e) {
    error_log('Orders Query Error: ' . $e->getMessage());
    $error = 'Failed to load order records. Please check the database.';
}

require_once __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
  <div>
    <h1 class="page-title mb-1">Order Management</h1>
    <p class="text-muted mb-0">Track bookings, itemized formulations, delivery details, and fulfillment statuses.</p>
  </div>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Status Filter Pills & Search Toolbar -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-3 bg-white">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <!-- Status Pills -->
      <div class="nav nav-pills gap-1">
        <a 
          href="orders.php?status=all<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'all' ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill"
        >
          All Orders <span class="badge bg-secondary-subtle text-secondary ms-1"><?= $statusCounts['all'] ?></span>
        </a>
        <a 
          href="orders.php?status=pending<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'pending' ? 'btn-warning' : 'btn-outline-secondary' ?> rounded-pill"
        >
          Pending <span class="badge bg-warning-subtle text-warning-emphasis ms-1"><?= $statusCounts['pending'] ?></span>
        </a>
        <a 
          href="orders.php?status=processing<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'processing' ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill"
        >
          Processing <span class="badge bg-primary-subtle text-primary ms-1"><?= $statusCounts['processing'] ?></span>
        </a>
        <a 
          href="orders.php?status=shipped<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'shipped' ? 'btn-info' : 'btn-outline-secondary' ?> rounded-pill"
        >
          Shipped <span class="badge bg-info-subtle text-info-emphasis ms-1"><?= $statusCounts['shipped'] ?></span>
        </a>
        <a 
          href="orders.php?status=delivered<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'delivered' ? 'btn-success' : 'btn-outline-secondary' ?> rounded-pill"
        >
          Delivered <span class="badge bg-success-subtle text-success ms-1"><?= $statusCounts['delivered'] ?></span>
        </a>
        <a 
          href="orders.php?status=cancelled<?= $search !== '' ? '&search=' . urlencode($search) : '' ?>" 
          class="btn btn-sm <?= $selectedStatus === 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' ?> rounded-pill"
        >
          Cancelled <span class="badge bg-danger-subtle text-danger ms-1"><?= $statusCounts['cancelled'] ?></span>
        </a>
      </div>

      <!-- Search Box & Filter Dropdown Form -->
      <form method="GET" action="orders.php" class="d-flex align-items-center gap-2">
        <!-- Status Dropdown for Quick Switching -->
        <select name="status" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
          <option value="all" <?= $selectedStatus === 'all' ? 'selected' : '' ?>>Filter Status: All</option>
          <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="processing" <?= $selectedStatus === 'processing' ? 'selected' : '' ?>>Processing</option>
          <option value="shipped" <?= $selectedStatus === 'shipped' ? 'selected' : '' ?>>Shipped</option>
          <option value="delivered" <?= $selectedStatus === 'delivered' ? 'selected' : '' ?>>Delivered</option>
          <option value="cancelled" <?= $selectedStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <div class="input-group input-group-sm" style="width: 260px;">
          <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Customer or Order #..." 
            value="<?= htmlspecialchars($search) ?>"
          >
          <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
          <?php if ($search !== '' || $selectedStatus !== 'all'): ?>
            <a href="orders.php" class="btn btn-outline-secondary" title="Reset Filters"><i class="bi bi-x-lg"></i></a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Orders Table -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
    <div>
      <span class="fw-bold text-dark">Order Records</span>
      <span class="text-muted small ms-2">(Showing <?= count($orders) ?> order<?= count($orders) !== 1 ? 's' : '' ?> &bull; sorted newest first)</span>
    </div>
    <span class="text-muted small">
      <i class="bi bi-info-circle me-1"></i> Use dropdown in row to update status inline, or click details.
    </span>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 90px;">Order ID</th>
          <th style="width: 160px;">Date Placed</th>
          <th style="width: 220px;">Customer & Delivery Address</th>
          <th>Purchased Items (JOIN Details)</th>
          <th style="width: 120px;">Total Amount</th>
          <th style="width: 190px;">Current Status</th>
          <th style="width: 100px;" class="text-end">Details</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <?php if ($statusCounts['all'] === 0): ?>
                <div class="py-4">
                  <span class="d-inline-flex align-items-center justify-content-center bg-light text-navy rounded-circle p-4 border mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-cart-x fs-1 text-gold"></i>
                  </span>
                  <h5 class="fw-bold text-navy mb-1">No Orders Placed Yet</h5>
                  <p class="text-muted small mx-auto mb-3" style="max-width: 420px;">
                    Your store has not received any customer orders yet. Once a client completes the checkout process on the storefront, it will appear here in real time.
                  </p>
                  <a href="../index.html" target="_blank" class="btn btn-navy btn-sm">
                    <i class="bi bi-shop me-1 text-gold"></i> Visit Storefront
                  </a>
                </div>
              <?php else: ?>
                <div class="py-3">
                  <i class="bi bi-funnel fs-2 d-block mb-2 text-secondary"></i>
                  <h6 class="fw-bold text-dark mb-1">No orders matched your filter</h6>
                  <p class="small text-muted mb-2">
                    <?php if ($search !== ''): ?>
                      No orders found matching search query: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                    <?php endif; ?>
                    <?php if ($selectedStatus !== 'all'): ?>
                      <?= $search !== '' ? '&bull;' : '' ?> Status filter: <strong><?= htmlspecialchars(ucfirst($selectedStatus)) ?></strong> (0 orders)
                    <?php endif; ?>
                  </p>
                  <a href="orders.php" class="btn btn-sm btn-outline-navy">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All Filters
                  </a>
                </div>
              <?php endif; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <?php
              $statusBadgeClass = match($order['status']) {
                  'pending'    => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                  'processing' => 'bg-primary-subtle text-primary border border-primary-subtle',
                  'shipped'    => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                  'delivered'  => 'bg-success-subtle text-success border border-success-subtle',
                  'cancelled'  => 'bg-danger-subtle text-danger border border-danger-subtle',
                  default      => 'bg-secondary-subtle text-secondary'
              };

              // Prepare JSON payload for the modal
              $modalDataJson = htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8');
            ?>
            <tr>
              <!-- Order ID -->
              <td>
                <span class="fw-bold text-dark font-monospace fs-6">#<?= $order['id'] ?></span>
              </td>

              <!-- Date Placed -->
              <td class="text-muted small">
                <div><?= date('d M Y', strtotime($order['created_at'])) ?></div>
                <div class="text-secondary"><?= date('H:i A', strtotime($order['created_at'])) ?></div>
              </td>

              <!-- Customer & Address -->
              <td>
                <div class="fw-semibold text-dark"><?= htmlspecialchars($order['customer_name']) ?></div>
                <div class="text-muted small text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($order['address']) ?>">
                  <i class="bi bi-geo-alt text-secondary me-1"></i><?= htmlspecialchars($order['address']) ?>
                </div>
              </td>

              <!-- Item Details (from order_items JOIN) -->
              <td>
                <div class="d-flex flex-column gap-1">
                  <?php if (empty($order['items'])): ?>
                    <span class="text-muted small fst-italic">No line items recorded</span>
                  <?php else: ?>
                    <?php foreach ($order['items'] as $item): ?>
                      <div class="d-flex align-items-center gap-2 small">
                        <?php if (!empty($item['image_path'])): ?>
                          <img 
                            src="../<?= htmlspecialchars($item['image_path']) ?>" 
                            alt="" 
                            class="rounded border" 
                            style="width: 28px; height: 28px; object-fit: cover;"
                            onerror="this.onerror=null; this.src='../assets/hero.jpg';"
                          >
                        <?php else: ?>
                          <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bi bi-box" style="font-size: 12px;"></i>
                          </div>
                        <?php endif; ?>

                        <div class="text-dark fw-medium text-truncate" style="max-width: 220px;" title="<?= htmlspecialchars($item['product_name']) ?>">
                          <?= htmlspecialchars($item['product_name']) ?>
                        </div>

                        <span class="badge bg-light text-dark border">
                          &times;<?= $item['quantity'] ?>
                        </span>

                        <span class="text-muted ms-auto small font-monospace">
                          ₹<?= number_format($item['price'], 2) ?>
                        </span>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </td>

              <!-- Total Amount -->
              <td>
                <span class="fw-bold text-dark fs-6">
                  ₹<?= number_format($order['total'], 2) ?>
                </span>
              </td>

              <!-- Inline Status Update Form -->
              <td>
                <form method="POST" action="orders.php" class="d-flex align-items-center gap-1">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                  <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                  <input type="hidden" name="return_status" value="<?= htmlspecialchars($selectedStatus) ?>">
                  <input type="hidden" name="return_search" value="<?= htmlspecialchars($search) ?>">

                  <select 
                    name="new_status" 
                    class="form-select form-select-sm <?= $statusBadgeClass ?> fw-semibold" 
                    onchange="this.form.submit()" 
                    title="Change status inline"
                  >
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                  </select>
                </form>
              </td>

              <!-- Modal Trigger Button -->
              <td class="text-end">
                <button 
                  type="button" 
                  class="btn btn-sm btn-outline-dark" 
                  data-bs-toggle="modal" 
                  data-bs-target="#orderDetailModal" 
                  data-order='<?= $modalDataJson ?>'
                  title="View complete breakdown & update status"
                >
                  <i class="bi bi-eye"></i> Details
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Order Detail & Status Update Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="orderModalLabel">
          <i class="bi bi-receipt me-2"></i> Order Breakdown &bull; <span id="mOrderId"></span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <!-- Customer & Date Summary -->
        <div class="row g-3 mb-4 p-3 bg-light rounded border">
          <div class="col-sm-6">
            <span class="text-muted text-uppercase small fw-bold d-block mb-1">Recipient / Customer</span>
            <h6 class="fw-bold mb-1" id="mCustomerName"></h6>
            <div class="text-muted small" id="mCustomerAddress"></div>
          </div>
          <div class="col-sm-6 text-sm-end">
            <span class="text-muted text-uppercase small fw-bold d-block mb-1">Booking Timestamp</span>
            <div class="fw-semibold text-dark" id="mOrderDate"></div>
            <div class="mt-2" id="mStatusBadgeWrap"></div>
          </div>
        </div>

        <!-- Line Items Table -->
        <h6 class="fw-bold mb-2 text-uppercase small text-muted">Itemized Formulation Basket</h6>
        <div class="table-responsive border rounded mb-3">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th class="text-center" style="width: 80px;">Qty</th>
                <th class="text-end" style="width: 110px;">Unit Price</th>
                <th class="text-end" style="width: 120px;">Subtotal</th>
              </tr>
            </thead>
            <tbody id="mItemsTbody">
              <!-- Dynamically populated via JS -->
            </tbody>
            <tfoot class="table-light">
              <tr>
                <th colspan="3" class="text-end">Final Order Total:</th>
                <th class="text-end fs-6 text-dark" id="mOrderTotal"></th>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Status Update Form inside Modal -->
        <form method="POST" action="orders.php" id="modalStatusForm" class="p-3 bg-white border rounded">
          <input type="hidden" name="action" value="update_status">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="order_id" id="mInputOrderId" value="">
          <input type="hidden" name="return_status" value="<?= htmlspecialchars($selectedStatus) ?>">
          <input type="hidden" name="return_search" value="<?= htmlspecialchars($search) ?>">

          <div class="row g-2 align-items-center">
            <div class="col-md-5">
              <label for="mSelectStatus" class="form-label mb-0 fw-semibold">
                <i class="bi bi-arrow-repeat me-1"></i> Change Fulfillment Status:
              </label>
            </div>
            <div class="col-md-4">
              <select name="new_status" id="mSelectStatus" class="form-select form-select-sm">
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-sm btn-blu-accent w-100">
                <i class="bi bi-check2 me-1"></i> Save Status
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-light py-2">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Populate the Order Details Modal with item breakdown and current status
  document.addEventListener('DOMContentLoaded', function () {
    const detailModal = document.getElementById('orderDetailModal');
    if (detailModal) {
      detailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const orderData = JSON.parse(button.getAttribute('data-order'));

        document.getElementById('mOrderId').textContent = '#' + orderData.id;
        document.getElementById('mCustomerName').textContent = orderData.customer_name;
        document.getElementById('mCustomerAddress').textContent = orderData.address;
        document.getElementById('mOrderDate').textContent = orderData.created_at;
        document.getElementById('mOrderTotal').textContent = '₹' + parseFloat(orderData.total).toFixed(2);
        document.getElementById('mInputOrderId').value = orderData.id;
        document.getElementById('mSelectStatus').value = orderData.status;

        // Render Status Badge
        const badgeColors = {
          'pending': 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
          'processing': 'bg-primary-subtle text-primary border border-primary-subtle',
          'shipped': 'bg-info-subtle text-info-emphasis border border-info-subtle',
          'delivered': 'bg-success-subtle text-success border border-success-subtle',
          'cancelled': 'bg-danger-subtle text-danger border border-danger-subtle'
        };
        const colorClass = badgeColors[orderData.status] || 'bg-secondary-subtle';
        document.getElementById('mStatusBadgeWrap').innerHTML = `
          <span class="badge ${colorClass} px-3 py-2 text-uppercase">
            Status: ${orderData.status}
          </span>
        `;

        // Render Order Items
        const tbody = document.getElementById('mItemsTbody');
        tbody.innerHTML = '';

        if (!orderData.items || orderData.items.length === 0) {
          tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No items linked to this order.</td></tr>';
        } else {
          orderData.items.forEach(function (item) {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>
                <div class="fw-semibold text-dark">${escapeHtml(item.product_name)}</div>
                ${item.product_id ? '<small class="text-muted">Product ID #' + item.product_id + '</small>' : ''}
              </td>
              <td class="text-center font-monospace">${item.quantity}</td>
              <td class="text-end font-monospace">₹${parseFloat(item.price).toFixed(2)}</td>
              <td class="text-end font-monospace fw-bold">₹${parseFloat(item.subtotal).toFixed(2)}</td>
            `;
            tbody.appendChild(row);
          });
        }
      });
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
  });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
