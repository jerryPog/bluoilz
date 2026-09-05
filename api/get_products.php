<?php
/**
 * API Endpoint: Get Products
 * Returns the product catalog as a JSON array for the frontend.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow local development and cross-origin if needed

// Include database connection
require_once __DIR__ . '/../admin/db.php';

try {
    $pdo = getDBConnection();
    
    // Fetch all products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $products = $stmt->fetchAll();
    
    // Format data to match frontend JS expectations
    $formattedProducts = [];
    foreach ($products as $row) {
        $formattedProducts[] = [
            'id' => $row['slug'], // Frontend expects 'id' to be the slug string
            'title' => $row['name'],
            'category' => $row['category'],
            'categoryLabel' => $row['categoryLabel'],
            'concern' => $row['concern'],
            'price' => (float)$row['price'],
            'originalPrice' => $row['originalPrice'] ? (float)$row['originalPrice'] : null,
            'rating' => (float)$row['rating'],
            'reviewCount' => (int)$row['reviewCount'],
            'badge' => $row['badge'],
            'curation' => $row['curation'],
            'weight' => $row['weight'],
            'image' => $row['image_path'],
            'description' => $row['description'],
            // Split keyBenefits by newline into an array
            'keyBenefits' => $row['keyBenefits'] ? array_map('trim', explode("\n", $row['keyBenefits'])) : [],
            'ingredients' => $row['ingredients']
        ];
    }
    
    echo json_encode($formattedProducts);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch products.']);
}
