<?php
/**
 * Product Management - Delete Controller
 * Handles secure POST-only deletion of products, cleans up uploaded image, and respects foreign key integrity.
 */
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Invalid request method. Deletions must be performed via POST.';
    header('Location: products.php');
    exit;
}

// 1. Verify CSRF Token
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    $_SESSION['flash_error'] = 'Security validation failed (invalid CSRF token). Product was not deleted.';
    header('Location: products.php');
    exit;
}

// 2. Validate Product ID
$productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$productId) {
    $_SESSION['flash_error'] = 'Invalid product ID specified for deletion.';
    header('Location: products.php');
    exit;
}

try {
    $pdo = getDBConnection();

    // Fetch product to retrieve image path and verify existence
    $stmt = $pdo->prepare('SELECT id, name, image_path FROM products WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['flash_error'] = 'Product not found or already deleted.';
        header('Location: products.php');
        exit;
    }

    // 3. Foreign Key Integrity Check (order_items table)
    $orderCheckStmt = $pdo->prepare('SELECT COUNT(*) AS order_count FROM order_items WHERE product_id = :product_id');
    $orderCheckStmt->execute([':product_id' => $productId]);
    $orderCount = (int)$orderCheckStmt->fetchColumn();

    if ($orderCount > 0) {
        $_SESSION['flash_error'] = sprintf(
            'Cannot delete "%s" because it is linked to %d customer order line item(s). To discontinue it, update its stock to 0 instead.',
            $product['name'],
            $orderCount
        );
        header('Location: products.php');
        exit;
    }

    // 4. Delete Record from Database
    $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $deleteStmt->execute([':id' => $productId]);

    // 5. Clean Up Image File if located in /uploads/
    $imagePath = $product['image_path'] ?? '';
    if (!empty($imagePath) && str_starts_with($imagePath, 'uploads/')) {
        $fullImagePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . $imagePath;
        if (file_exists($fullImagePath) && is_file($fullImagePath)) {
            @unlink($fullImagePath);
        }
    }

    $_SESSION['flash_success'] = sprintf('Product #%d ("%s") was successfully deleted.', $productId, $product['name']);
} catch (PDOException $e) {
    error_log('Product Delete Error: ' . $e->getMessage());

    if ($e->getCode() == '23000') {
        $_SESSION['flash_error'] = 'Cannot delete this product because it is referenced by existing orders.';
    } else {
        $_SESSION['flash_error'] = 'A database error occurred while deleting the product.';
    }
} catch (Exception $e) {
    error_log('Product Delete General Error: ' . $e->getMessage());
    $_SESSION['flash_error'] = 'An unexpected error occurred during product deletion.';
}

header('Location: products.php');
exit;
