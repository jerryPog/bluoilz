<?php
/**
 * Product Management - Edit Product
 * Pre-fills existing data, validates server-side inputs, handles optional image replacement, and updates via PDO.
 */
$pageTitle = 'Edit Product';
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$productId) {
    $_SESSION['flash_error'] = 'Invalid product ID specified.';
    header('Location: products.php');
    exit;
}

$pdo = getDBConnection();

// Fetch existing product
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['flash_error'] = 'Product not found.';
    header('Location: products.php');
    exit;
}

$errors = [];
$name = $product['name'];
$price = number_format((float)$product['price'], 2, '.', '');
$stock = (int)$product['stock'];
$description = $product['description'] ?? '';
$currentImage = $product['image_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF Token
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $errors['csrf'] = 'Invalid or expired security token. Please resubmit the form.';
    }

    // 2. Sanitize and Validate Inputs Server-Side
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_DEFAULT) ?? '');
    $priceRaw = trim($_POST['price'] ?? '');
    $stockRaw = trim($_POST['stock'] ?? '');
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_DEFAULT) ?? '');

    // Validate Name
    if ($name === '') {
        $errors['name'] = 'Product name is required.';
    } elseif (mb_strlen($name) < 2) {
        $errors['name'] = 'Product name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 255) {
        $errors['name'] = 'Product name must not exceed 255 characters.';
    }

    // Validate Price
    if ($priceRaw === '') {
        $errors['price'] = 'Price is required.';
    } elseif (!is_numeric($priceRaw)) {
        $errors['price'] = 'Price must be a valid numeric amount.';
    } elseif ((float)$priceRaw <= 0) {
        $errors['price'] = 'Price must be a valid positive amount greater than ₹0.00.';
    } elseif ((float)$priceRaw > 999999.99) {
        $errors['price'] = 'Price cannot exceed ₹999,999.99.';
    } else {
        $price = number_format((float)$priceRaw, 2, '.', '');
    }

    // Validate Stock
    if ($stockRaw === '') {
        $errors['stock'] = 'Stock quantity is required.';
    } else {
        $stockVal = filter_var($stockRaw, FILTER_VALIDATE_INT);
        if ($stockVal === false) {
            $errors['stock'] = 'Stock must be a valid whole number (no decimals).';
        } elseif ($stockVal < 0) {
            $errors['stock'] = 'Stock cannot be negative (must be 0 or greater).';
        } elseif ($stockVal > 1000000) {
            $errors['stock'] = 'Stock quantity cannot exceed 1,000,000 units.';
        } else {
            $stock = $stockVal;
        }
    }

    // Validate Description length
    if (mb_strlen($description) > 5000) {
        $errors['description'] = 'Description must not exceed 5,000 characters.';
    }

    // 3. Handle Optional Image Replacement Upload
    $newImagePath = null;
    $shouldDeleteOldImage = false;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = 'An error occurred during file upload (Code: ' . $file['error'] . ').';
        } else {
            // Check file size (max 5MB)
            $maxBytes = 5 * 1024 * 1024;
            if ($file['size'] > $maxBytes) {
                $errors['image'] = 'Image size exceeds maximum limit of 5 MB.';
            }

            // Verify MIME type
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif'
            ];

            if (!array_key_exists($mimeType, $allowedMimes)) {
                $errors['image'] = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, GIF.';
            } else {
                $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = $allowedMimes[$mimeType];
                $uniqueFilename = 'prod_' . bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension;
                $targetFile = $uploadDir . DIRECTORY_SEPARATOR . $uniqueFilename;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    $newImagePath = 'uploads/' . $uniqueFilename;
                    $shouldDeleteOldImage = true;
                } else {
                    $errors['image'] = 'Failed to save uploaded image.';
                }
            }
        }
    }

    // 4. Update Database via Prepared Statement
    if (empty($errors)) {
        try {
            $finalImagePath = $newImagePath !== null ? $newImagePath : $currentImage;

            $updateStmt = $pdo->prepare('
                UPDATE products 
                SET name = :name, 
                    price = :price, 
                    stock = :stock, 
                    image_path = :image_path, 
                    description = :description 
                WHERE id = :id
            ');

            $updateStmt->execute([
                ':name'        => $name,
                ':price'       => $price,
                ':stock'       => $stock,
                ':image_path'  => $finalImagePath,
                ':description' => $description !== '' ? $description : null,
                ':id'          => $productId
            ]);

            // If a new image was saved, safely clean up the replaced image if it was in /uploads/
            if ($shouldDeleteOldImage && !empty($currentImage)) {
                if (str_starts_with($currentImage, 'uploads/')) {
                    $oldFullPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . $currentImage;
                    if (file_exists($oldFullPath) && is_file($oldFullPath)) {
                        @unlink($oldFullPath);
                    }
                }
            }

            $_SESSION['flash_success'] = "Product #{$productId} ('{$name}') was updated successfully.";
            header('Location: products.php');
            exit;
        } catch (Exception $e) {
            error_log('Product Update Error: ' . $e->getMessage());
            $errors['db'] = 'Database update failed: ' . $e->getMessage();

            // Clean up newly uploaded file if DB update failed
            if ($newImagePath && file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . $newImagePath)) {
                @unlink(dirname(__DIR__) . DIRECTORY_SEPARATOR . $newImagePath);
            }
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-8 col-xl-7">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="page-title mb-1">Edit Product</h1>
        <p class="text-muted mb-0">Modify formulation details, pricing, stock levels, or product imagery.</p>
      </div>
      <a href="products.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
      </a>
    </div>

    <?php if (!empty($errors['csrf'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($errors['csrf']) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors['db'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($errors['db']) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <span class="text-muted small">Editing Product <strong>#<?= (int)$product['id'] ?></strong></span>
        <span class="badge bg-light text-secondary border">Created <?= date('d M Y, H:i', strtotime($product['created_at'])) ?></span>
      </div>

      <div class="card-body p-4">
        <form method="POST" action="product_edit.php?id=<?= (int)$productId ?>" enctype="multipart/form-data" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

          <!-- Product Name -->
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold">
              Product Name <span class="text-danger">*</span>
            </label>
            <input 
              type="text" 
              class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
              id="name" 
              name="name" 
              value="<?= htmlspecialchars($name) ?>" 
              required 
              minlength="2"
              maxlength="255"
              autofocus
            >
            <?php if (isset($errors['name'])): ?>
              <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Price & Stock in Row -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="price" class="form-label fw-semibold">
                Price (₹) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light">₹</span>
                <input 
                  type="number" 
                  step="0.01" 
                  min="0.01" 
                  max="999999.99"
                  class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                  id="price" 
                  name="price" 
                  value="<?= htmlspecialchars($price) ?>" 
                  required
                >
                <?php if (isset($errors['price'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['price']) ?></div>
                <?php endif; ?>
              </div>
              <div class="form-text">Must be a positive amount greater than ₹0.00.</div>
            </div>

            <div class="col-md-6">
              <label for="stock" class="form-label fw-semibold">
                Available Stock Units <span class="text-danger">*</span>
              </label>
              <input 
                type="number" 
                min="0" 
                max="1000000"
                step="1" 
                class="form-control <?= isset($errors['stock']) ? 'is-invalid' : '' ?>" 
                id="stock" 
                name="stock" 
                value="<?= htmlspecialchars((string)$stock) ?>" 
                required
              >
              <?php if (isset($errors['stock'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['stock']) ?></div>
              <?php endif; ?>
              <div class="form-text">Whole number 0 or greater. Set to 0 to mark out of stock.</div>
            </div>
          </div>

          <!-- Existing & New Image Management -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Product Image</label>
            
            <?php if (!empty($currentImage)): ?>
              <div class="d-flex align-items-center gap-3 p-3 bg-light border rounded mb-2">
                <img 
                  src="../<?= htmlspecialchars($currentImage) ?>" 
                  alt="Current image" 
                  class="rounded border" 
                  style="width: 60px; height: 60px; object-fit: cover;"
                  onerror="this.onerror=null; this.src='../assets/hero.jpg';"
                >
                <div class="small">
                  <div class="fw-semibold text-dark">Current Image File</div>
                  <code class="text-muted"><?= htmlspecialchars($currentImage) ?></code>
                </div>
              </div>
            <?php endif; ?>

            <label for="image" class="form-label text-muted small mb-1">
              Upload New Replacement Image (Leave blank to keep existing)
            </label>
            <input 
              type="file" 
              class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" 
              id="image" 
              name="image" 
              accept="image/jpeg,image/png,image/webp,image/gif"
              onchange="previewSelectedImage(this)"
            >
            <div class="form-text">
              Allowed: JPG, PNG, WEBP, GIF (Max 5MB). New uploads replace the current file in <code>/uploads</code>.
            </div>
            <?php if (isset($errors['image'])): ?>
              <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>

            <!-- Live Image Preview Container -->
            <div id="imagePreviewWrap" class="mt-2 text-center p-2 border rounded bg-light" style="display: none;">
              <span class="badge bg-secondary mb-1">New Image Selected</span><br>
              <img id="imagePreview" src="" alt="Selected Preview" style="max-height: 130px; border-radius: 8px;">
              <div class="small text-muted mt-1" id="imagePreviewName"></div>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Description & Botanical Notes</label>
            <textarea 
              class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
              id="description" 
              name="description" 
              rows="4"
              maxlength="5000"
            ><?= htmlspecialchars($description) ?></textarea>
            <?php if (isset($errors['description'])): ?>
              <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Form Actions -->
          <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-blu-accent px-4">
              <i class="bi bi-save me-1"></i> Update Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function previewSelectedImage(input) {
    const wrap = document.getElementById('imagePreviewWrap');
    const preview = document.getElementById('imagePreview');
    const nameEl = document.getElementById('imagePreviewName');

    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        nameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        wrap.style.display = 'block';
      };

      reader.readAsDataURL(file);
    } else {
      wrap.style.display = 'none';
      preview.src = '';
    }
  }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
