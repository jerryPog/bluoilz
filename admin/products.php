<?php
/**
 * Product Management - List Page
 * Displays all products in a responsive Bootstrap table with search, edit, and delete confirmation.
 * Handles edge cases: empty inventory, zero search results, and products linked to existing orders.
 */
$pageTitle = 'Manage Products';
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

// Generate CSRF token for delete actions
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$search = trim($_GET['search'] ?? '');
$products = [];
$totalProductsCount = 0;
$error = '';

try {
    $pdo = getDBConnection();

    // Check total catalog count in database
    $totalProductsCount = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();

    if ($search !== '') {
        $stmt = $pdo->prepare('
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.stock, 
                p.image_path, 
                p.description, 
                p.created_at,
                COALESCE((SELECT COUNT(*) FROM order_items oi WHERE oi.product_id = p.id), 0) AS order_count
            FROM products p
            WHERE p.name LIKE :query OR p.id = :exact_id 
            ORDER BY p.id DESC
        ');
        $stmt->execute([
            ':query'    => '%' . $search . '%',
            ':exact_id' => is_numeric($search) ? (int)$search : 0
        ]);
    } else {
        $stmt = $pdo->query('
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.stock, 
                p.image_path, 
                p.description, 
                p.created_at,
                COALESCE((SELECT COUNT(*) FROM order_items oi WHERE oi.product_id = p.id), 0) AS order_count
            FROM products p
            ORDER BY p.id DESC
        ');
    }

    $products = $stmt->fetchAll();
} catch (Exception $e) {
    error_log('Products Fetch Error: ' . $e->getMessage());
    $error = 'Unable to load products. Database query failed.';
}

require_once __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
  <div>
    <h1 class="page-title mb-1">Product Inventory</h1>
    <p class="text-muted mb-0">View, update, and manage all therapeutic skincare formulations.</p>
  </div>
  <a href="product_add.php" class="btn btn-blu-accent">
    <i class="bi bi-plus-lg me-1"></i> Add New Product
  </a>
</div>

<!-- Flash Notifications -->
<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger shadow-sm"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Empty Database State (Catalog has 0 products total) -->
<?php if ($totalProductsCount === 0 && $search === ''): ?>
  <div class="card shadow-sm border-0 text-center py-5 px-4 mb-4">
    <div class="card-body py-4">
      <div class="mb-3">
        <span class="d-inline-flex align-items-center justify-content-center bg-light text-navy rounded-circle p-4 border" style="width: 80px; height: 80px;">
          <i class="bi bi-boxes fs-1 text-gold"></i>
        </span>
      </div>
      <h3 class="fw-bold text-navy mb-2">No Formulations in Inventory</h3>
      <p class="text-muted mx-auto mb-4" style="max-width: 480px;">
        Your skincare product catalog is currently empty. Add your first botanical product to populate the public storefront and inventory portal.
      </p>
      <a href="product_add.php" class="btn btn-blu-accent px-4 py-2">
        <i class="bi bi-plus-circle me-1"></i> Create First Formulation
      </a>
    </div>
  </div>
<?php else: ?>

  <!-- Search and Inventory Summary Toolbar -->
  <div class="card shadow-sm mb-4">
    <div class="card-body p-3 bg-white border-bottom">
      <form method="GET" action="products.php" class="row g-2 align-items-center">
        <div class="col-sm-6 col-md-5 col-lg-4">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input 
              type="text" 
              name="search" 
              class="form-control border-start-0" 
              placeholder="Search by name or product ID..." 
              value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit" class="btn btn-navy btn-sm">Search</button>
            <?php if ($search !== ''): ?>
              <a href="products.php" class="btn btn-outline-secondary btn-sm" title="Clear search">Clear</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col text-end text-muted small">
          Showing <strong><?= count($products) ?></strong> of <strong><?= $totalProductsCount ?></strong> formulation<?= $totalProductsCount !== 1 ? 's' : '' ?>
        </div>
      </form>
    </div>

    <!-- Empty Search State -->
    <?php if (empty($products) && $search !== ''): ?>
      <div class="card-body text-center py-5">
        <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
        <h5 class="fw-bold text-dark mb-1">No formulations found</h5>
        <p class="text-muted small mb-3">No products match your search query: <strong>"<?= htmlspecialchars($search) ?>"</strong></p>
        <a href="products.php" class="btn btn-sm btn-outline-navy">
          <i class="bi bi-x-circle me-1"></i> Clear Search Filter
        </a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 70px;">Image</th>
              <th style="width: 60px;">ID</th>
              <th>Product Name</th>
              <th style="width: 120px;">Price</th>
              <th style="width: 130px;">Stock Status</th>
              <th style="width: 130px;">Order History</th>
              <th>Description</th>
              <th style="width: 120px;">Created</th>
              <th style="width: 150px;" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <?php $orderCount = (int)$p['order_count']; ?>
              <tr>
                <!-- Product Thumbnail -->
                <td>
                  <?php if (!empty($p['image_path'])): ?>
                    <img 
                      src="../<?= htmlspecialchars($p['image_path']) ?>" 
                      alt="<?= htmlspecialchars($p['name']) ?>" 
                      class="product-thumb"
                      onerror="this.onerror=null; this.src='../assets/hero.jpg';"
                    >
                  <?php else: ?>
                    <div class="product-thumb d-flex align-items-center justify-content-center text-muted">
                      <i class="bi bi-image"></i>
                    </div>
                  <?php endif; ?>
                </td>

                <!-- ID -->
                <td class="fw-bold text-muted font-monospace">#<?= (int)$p['id'] ?></td>

                <!-- Name -->
                <td>
                  <div class="fw-semibold text-dark"><?= htmlspecialchars($p['name']) ?></div>
                </td>

                <!-- Price -->
                <td class="fw-bold text-dark font-monospace">
                  ₹<?= number_format((float)$p['price'], 2) ?>
                </td>

                <!-- Stock Badge -->
                <td>
                  <?php 
                    $stock = (int)$p['stock'];
                    if ($stock <= 0) {
                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>';
                    } elseif ($stock < 5) {
                        echo '<span class="badge bg-danger text-white"><i class="bi bi-exclamation-octagon me-1"></i>Critical (' . $stock . ')</span>';
                    } elseif ($stock <= 10) {
                        echo '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="bi bi-exclamation-triangle me-1"></i>Low (' . $stock . ')</span>';
                    } else {
                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2 me-1"></i>' . $stock . ' in stock</span>';
                    }
                  ?>
                </td>

                <!-- Order Count Badge (Edge case visibility) -->
                <td>
                  <?php if ($orderCount > 0): ?>
                    <span class="badge bg-light text-navy border" title="Linked to <?= $orderCount ?> customer order line items">
                      <i class="bi bi-receipt me-1 text-gold"></i><?= $orderCount ?> item<?= $orderCount !== 1 ? 's' : '' ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted small" title="No orders linked yet">
                      <i class="bi bi-dash"></i> None
                    </span>
                  <?php endif; ?>
                </td>

                <!-- Description -->
                <td class="text-muted small" style="max-width: 240px;">
                  <span class="d-inline-block text-truncate" style="max-width: 230px;" title="<?= htmlspecialchars($p['description'] ?? '') ?>">
                    <?= htmlspecialchars($p['description'] ?: 'No description provided.') ?>
                  </span>
                </td>

                <!-- Created Date -->
                <td class="text-muted small">
                  <?= date('d M Y', strtotime($p['created_at'])) ?>
                </td>

                <!-- Actions -->
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <a 
                      href="product_edit.php?id=<?= (int)$p['id'] ?>" 
                      class="btn btn-outline-secondary" 
                      title="Edit product"
                    >
                      <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <button 
                      type="button" 
                      class="btn btn-outline-danger" 
                      data-bs-toggle="modal" 
                      data-bs-target="#deleteConfirmModal" 
                      data-product-id="<?= (int)$p['id'] ?>" 
                      data-product-name="<?= htmlspecialchars($p['name']) ?>"
                      data-order-count="<?= $orderCount ?>"
                      title="Delete product"
                    >
                      <i class="bi bi-trash3"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>

<!-- ========================================================================
     REUSABLE DELETE CONFIRMATION MODAL WITH FOREIGN KEY ADVISORY
     ======================================================================== -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Product Action
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        
        <!-- Target Product Summary -->
        <div class="p-3 bg-light rounded border mb-3">
          <strong id="deleteModalProductName" class="text-dark fs-6"></strong> 
          <span class="text-muted small ms-2 font-monospace" id="deleteModalProductId"></span>
        </div>

        <!-- Case 1: Product HAS Existing Orders (Edge Case Protection) -->
        <div id="deleteModalBlockedNotice" style="display: none;">
          <div class="alert alert-warning border-warning d-flex align-items-start gap-2 mb-3">
            <i class="bi bi-shield-exclamation fs-4 text-warning-emphasis flex-shrink-0 mt-1"></i>
            <div>
              <strong class="text-dark">Cannot Delete This Formulation</strong>
              <div class="small text-muted mt-1">
                This product is referenced in <strong id="deleteModalOrderCountText"></strong> customer order line item(s).
                Relational integrity requires preserving customer invoice records.
              </div>
            </div>
          </div>
          <p class="small text-muted mb-0">
            <strong>Recommended Solution:</strong> To discontinue this product from sale without breaking order history, edit the product and set its <strong>Stock to 0</strong> (or mark it Out of Stock).
          </p>
        </div>

        <!-- Case 2: Product Has ZERO Orders (Safe to Delete) -->
        <div id="deleteModalAllowedNotice">
          <p class="mb-2 text-dark">Are you sure you want to permanently delete this product?</p>
          <div class="alert alert-danger-subtle text-danger border border-danger-subtle small mb-0">
            <i class="bi bi-info-circle me-1"></i> This action cannot be undone. Associated uploaded media will also be removed from the server.
          </div>
        </div>

      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        
        <!-- Alternate Action for Blocked Products: Direct Edit Button -->
        <a href="#" id="deleteModalEditBtn" class="btn btn-warning btn-sm" style="display: none;">
          <i class="bi bi-pencil-square me-1"></i> Edit & Set Stock to 0
        </a>

        <!-- Form for Safe Products -->
        <form method="POST" action="product_delete.php" id="deleteProductForm" class="d-inline">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="id" id="deleteModalInputId" value="">
          <button type="submit" class="btn btn-danger btn-sm" id="deleteModalSubmitBtn">
            <i class="bi bi-trash3 me-1"></i> Yes, Permanently Delete
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  // Wire dynamic product details and order relation checks into the Modal
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
      deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');
        const orderCount = parseInt(button.getAttribute('data-order-count') || '0', 10);

        document.getElementById('deleteModalProductName').textContent = productName;
        document.getElementById('deleteModalProductId').textContent = '(ID #' + productId + ')';
        document.getElementById('deleteModalInputId').value = productId;

        const blockedNotice = document.getElementById('deleteModalBlockedNotice');
        const allowedNotice = document.getElementById('deleteModalAllowedNotice');
        const editBtn = document.getElementById('deleteModalEditBtn');
        const submitBtn = document.getElementById('deleteModalSubmitBtn');
        const countText = document.getElementById('deleteModalOrderCountText');

        if (orderCount > 0) {
          // Product is linked to orders - block deletion gracefully
          blockedNotice.style.display = 'block';
          allowedNotice.style.display = 'none';
          countText.textContent = orderCount;
          editBtn.style.display = 'inline-flex';
          editBtn.href = 'product_edit.php?id=' + productId;
          submitBtn.style.display = 'none';
        } else {
          // Safe to delete
          blockedNotice.style.display = 'none';
          allowedNotice.style.display = 'block';
          editBtn.style.display = 'none';
          submitBtn.style.display = 'inline-flex';
        }
      });
    }
  });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
