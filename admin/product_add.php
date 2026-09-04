<?php
/**
 * Product Management - Add New Product
 * Handles input validation, sanitization, image file upload to /uploads, and PDO insertion.
 */
$pageTitle = 'Add New Product';
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$name = '';
$price = '';
$stock = '';
$description = '';

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

    // 3. Handle File Upload (/uploads folder)
    $imagePath = null;
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

            // Verify MIME type using finfo
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif'
            ];

            if (!array_key_exists($mimeType, $allowedMimes)) {
                $errors['image'] = 'Invalid image type. Only JPG, PNG, WEBP, and GIF formats are allowed.';
            } else {
                // Ensure /uploads directory exists
                $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Generate secure random filename
                $extension = $allowedMimes[$mimeType];
                $uniqueFilename = 'prod_' . bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension;
                $targetFile = $uploadDir . DIRECTORY_SEPARATOR . $uniqueFilename;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    // Store relative path in database
                    $imagePath = 'uploads/' . $uniqueFilename;
                } else {
                    $errors['image'] = 'Failed to move uploaded image to storage directory.';
                }
            }
        }
    }

    // 4. Save to Database using PDO Prepared Statement
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare('
                INSERT INTO products (name, price, stock, image_path, description, created_at)
                VALUES (:name, :price, :stock, :image_path, :description, NOW())
            ');

            $stmt->execute([
                ':name'        => $name,
                ':price'       => $price,
                ':stock'       => $stock,
                ':image_path'  => $imagePath,
                ':description' => $description !== '' ? $description : null
            ]);

            $_SESSION['flash_success'] = "Product '{$name}' was created successfully.";
            header('Location: products.php');
            exit;
        } catch (Exception $e) {
            error_log('Product Insert Error: ' . $e->getMessage());
            $errors['db'] = 'Database error: Could not save product. Please try again.';
            
            // Clean up uploaded file if DB insert fails
            if ($imagePath && file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . $imagePath)) {
                unlink(dirname(__DIR__) . DIRECTORY_SEPARATOR . $imagePath);
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
        <h1 class="page-title mb-1">Add New Product</h1>
        <p class="text-muted mb-0">Create and register a new therapeutic formulation in your inventory.</p>
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
      <div class="card-body p-4">
        <form method="POST" action="product_add.php" enctype="multipart/form-data" novalidate>
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
              placeholder="e.g. Anti Pigmentation Cream"
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
                  placeholder="599.00"
                  required
                >
                <?php if (isset($errors['price'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['price']) ?></div>
                <?php endif; ?>
              </div>
              <div class="form-text">Must be a valid positive amount (e.g. 599.00).</div>
            </div>

            <div class="col-md-6">
              <label for="stock" class="form-label fw-semibold">
                Initial Stock Units <span class="text-danger">*</span>
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
                placeholder="50"
                required
              >
              <?php if (isset($errors['stock'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['stock']) ?></div>
              <?php endif; ?>
              <div class="form-text">Non-negative integer (0 or greater).</div>
            </div>
          </div>

          <!-- Product Image Upload -->
          <div class="mb-3">
            <label for="image" class="form-label fw-semibold">Product Image</label>
            <input 
              type="file" 
              class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" 
              id="image" 
              name="image" 
              accept="image/jpeg,image/png,image/webp,image/gif"
              onchange="previewSelectedImage(this)"
            >
            <div class="form-text">
              Recommended: JPG, PNG, or WEBP (Max 5MB). Files are safely stored in <code>/uploads</code>.
            </div>
            <?php if (isset($errors['image'])): ?>
              <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>

            <!-- Live Image Preview Container -->
            <div id="imagePreviewWrap" class="mt-2 text-center p-2 border rounded bg-light" style="display: none;">
              <img id="imagePreview" src="" alt="Selected Preview" style="max-height: 140px; border-radius: 8px;">
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
              placeholder="Detail the formulation benefits, herbal ingredients, and skin target concerns..."
            ><?= htmlspecialchars($description) ?></textarea>
            <?php if (isset($errors['description'])): ?>
              <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Form Actions -->
          <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-blu-accent px-4">
              <i class="bi bi-check2-circle me-1"></i> Save Product
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
