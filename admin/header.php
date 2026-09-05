<?php
/**
 * Shared Admin Navigation Header
 * 
 * Reusable layout built with Bootstrap 5 (via CDN).
 * Features:
 * - Collapsible responsive left sidebar (collapses to hamburger offcanvas on mobile)
 * - Navigation links: Dashboard, Products, Orders, Logout
 * - Sticky topbar displaying the logged-in admin's name
 * - Navy-and-Gold brand palette applied to buttons, active nav states, and table headers
 * - Consistent main content canvas for tables and forms
 */
require_once __DIR__ . '/session_check.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin Portal') ?> &bull; Bluoilz</title>
  <meta name="robots" content="noindex, nofollow">
  
  <!-- Bootstrap 5 CSS via CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Bootstrap Icons via CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Google Fonts: Plus Jakarta Sans & Cormorant Garamond -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    :root,
    html,
    body {
      /* Force light color-scheme across OS themes */
      color-scheme: light;

      /* Typography */
      --font-serif: 'Cormorant Garamond', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

      /* Navy & Gold Brand Palette */
      --navy-900: #070f1e;
      --navy-800: #0d1a33;
      --navy-700: #142548;
      --navy-600: #1d3360;
      --navy-50:  #eef3f9;

      --gold-500: #d4af37;
      --gold-600: #c19b28;
      --gold-400: #e5c365;
      --gold-100: #fbf6e8;
      --gold-subtle: rgba(212, 175, 55, 0.12);

      --bg-canvas: #f4f6fa;
      --border-light: #e2e8f0;

      /* Legacy compatibility mappings */
      --blu-primary: var(--navy-800);
      --blu-accent: var(--gold-500);
      --blu-accent-hover: var(--gold-600);
      --blu-canvas: var(--bg-canvas);
      --blu-border: var(--border-light);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-sans);
      background-color: var(--bg-canvas);
      color: #1e293b;
      min-height: 100vh;
      margin: 0;
      overflow-x: hidden;
    }

    /* --------------------------------------------------------------------------
       Sidebar Navigation (Navy & Gold Theme)
       -------------------------------------------------------------------------- */
    .admin-layout {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    .admin-sidebar {
      width: 270px;
      min-width: 270px;
      background-color: var(--navy-900);
      background-image: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-800) 100%);
      color: #e2e8f0;
      border-right: 1px solid rgba(212, 175, 55, 0.2);
      z-index: 1045;
      transition: transform 0.3s ease-in-out;
    }

    @media (min-width: 992px) {
      .admin-sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
      }
    }

    .admin-sidebar::-webkit-scrollbar {
      width: 5px;
    }
    .admin-sidebar::-webkit-scrollbar-thumb {
      background: rgba(212, 175, 55, 0.3);
      border-radius: 4px;
    }

    .sidebar-brand {
      text-decoration: none;
    }
    .brand-crest {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--gold-500) 0%, var(--gold-600) 100%);
      color: var(--navy-900);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      font-weight: 800;
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.35);
    }
    .brand-title {
      font-family: var(--font-serif);
      font-size: 1.55rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: #ffffff;
      line-height: 1;
    }
    .brand-badge {
      font-size: 0.65rem;
      letter-spacing: 0.18em;
      font-weight: 800;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      background-color: var(--gold-500) !important;
      color: var(--navy-900) !important;
    }

    .sidebar-section-heading {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--gold-400);
      opacity: 0.85;
      padding: 0.9rem 1.25rem 0.35rem;
    }

    .admin-sidebar .nav-link {
      color: #cbd5e1;
      padding: 0.72rem 1.1rem;
      margin: 0.18rem 0.75rem;
      border-radius: 8px;
      font-size: 0.92rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
      text-decoration: none;
    }
    .admin-sidebar .nav-link i {
      font-size: 1.15rem;
      margin-right: 0.85rem;
      color: #94a3b8;
      transition: color 0.2s ease;
    }
    .admin-sidebar .nav-link:hover {
      color: #ffffff;
      background-color: rgba(212, 175, 55, 0.09);
      border-left-color: rgba(212, 175, 55, 0.5);
    }
    .admin-sidebar .nav-link:hover i {
      color: var(--gold-400);
    }

    /* Active nav state with Navy & Gold brand palette */
    .admin-sidebar .nav-link.active {
      background: linear-gradient(90deg, rgba(212, 175, 55, 0.22) 0%, rgba(212, 175, 55, 0.05) 100%) !important;
      color: #ffffff !important;
      font-weight: 600;
      border-left: 4px solid var(--gold-500) !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .admin-sidebar .nav-link.active i {
      color: var(--gold-400) !important;
      filter: drop-shadow(0 0 6px rgba(212, 175, 55, 0.4));
    }

    .admin-sidebar .nav-link-logout {
      color: #f87171 !important;
    }
    .admin-sidebar .nav-link-logout i {
      color: #f87171 !important;
    }
    .admin-sidebar .nav-link-logout:hover {
      background-color: rgba(239, 68, 68, 0.12) !important;
      border-left-color: #ef4444 !important;
      color: #ffffff !important;
    }

    .sidebar-divider {
      border-color: rgba(212, 175, 55, 0.2);
      margin: 0.75rem 1rem;
    }

    .sidebar-user-widget {
      background: rgba(13, 26, 51, 0.7);
      border: 1px solid rgba(212, 175, 55, 0.25);
      border-radius: 10px;
      margin: 0.75rem;
      padding: 0.85rem;
    }
    .avatar-crest {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--gold-500) 0%, var(--gold-600) 100%);
      color: var(--navy-900);
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
      flex-shrink: 0;
    }

    /* --------------------------------------------------------------------------
       Main Wrapper & Sticky Topbar
       -------------------------------------------------------------------------- */
    .admin-main-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      background-color: var(--bg-canvas);
    }

    .admin-topbar {
      height: 68px;
      background-color: #ffffff;
      border-bottom: 1px solid var(--border-light);
      z-index: 1020;
    }

    .btn-topbar-toggler {
      border: 1px solid var(--border-light);
      background-color: #ffffff;
      color: var(--navy-800);
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: all 0.2s;
    }
    .btn-topbar-toggler:hover {
      background-color: var(--navy-50);
      border-color: var(--navy-800);
      color: var(--navy-800);
    }

    .topbar-admin-badge {
      background-color: #ffffff;
      border: 1px solid var(--border-light);
      border-radius: 30px;
      padding: 0.35rem 0.85rem 0.35rem 0.45rem;
      display: inline-flex;
      align-items: center;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .topbar-admin-badge:hover {
      border-color: var(--gold-500);
      box-shadow: 0 2px 10px rgba(212, 175, 55, 0.15);
    }

    .avatar-badge-sm {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--gold-500) 0%, var(--gold-600) 100%);
      color: var(--navy-900);
      font-weight: 700;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid #ffffff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      margin-right: 0.5rem;
    }

    /* --------------------------------------------------------------------------
       Navy & Gold Buttons
       -------------------------------------------------------------------------- */
    .btn-navy,
    .btn-dark,
    .btn-primary,
    .btn-blu-primary {
      background-color: var(--navy-800) !important;
      border-color: var(--navy-800) !important;
      color: #ffffff !important;
      font-weight: 600;
      border-radius: 8px;
      padding: 0.5rem 1.15rem;
      transition: all 0.2s ease;
    }
    .btn-navy:hover,
    .btn-dark:hover,
    .btn-primary:hover,
    .btn-blu-primary:hover {
      background-color: var(--navy-700) !important;
      border-color: var(--gold-500) !important;
      color: var(--gold-400) !important;
      box-shadow: 0 4px 14px rgba(13, 26, 51, 0.25);
    }

    .btn-gold,
    .btn-accent,
    .btn-blu-accent {
      background: linear-gradient(135deg, var(--gold-500) 0%, var(--gold-600) 100%) !important;
      border-color: var(--gold-500) !important;
      color: var(--navy-900) !important;
      font-weight: 700;
      border-radius: 8px;
      padding: 0.5rem 1.15rem;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(212, 175, 55, 0.2);
    }
    .btn-gold:hover,
    .btn-accent:hover,
    .btn-blu-accent:hover {
      background: linear-gradient(135deg, var(--gold-600) 0%, #ab821f 100%) !important;
      border-color: var(--gold-600) !important;
      color: #ffffff !important;
      box-shadow: 0 4px 14px rgba(212, 175, 55, 0.4);
    }

    .btn-outline-navy {
      border: 1.5px solid var(--navy-800) !important;
      color: var(--navy-800) !important;
      background-color: transparent !important;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .btn-outline-navy:hover {
      background-color: var(--navy-800) !important;
      color: #ffffff !important;
    }

    .btn-outline-gold {
      border: 1.5px solid var(--gold-500) !important;
      color: var(--gold-600) !important;
      background-color: transparent !important;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .btn-outline-gold:hover {
      background-color: var(--gold-500) !important;
      color: var(--navy-900) !important;
    }

    /* --------------------------------------------------------------------------
       Table Headers (Navy with Radiant Gold Accent Border)
       -------------------------------------------------------------------------- */
    .table thead th,
    .table thead.table-light th,
    .table > thead > tr > th {
      background-color: var(--navy-800) !important;
      color: #ffffff !important;
      border-bottom: 2.5px solid var(--gold-500) !important;
      font-size: 0.78rem !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.06em !important;
      padding: 0.85rem 1rem !important;
      vertical-align: middle !important;
      white-space: nowrap;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(212, 175, 55, 0.04);
    }

    /* --------------------------------------------------------------------------
       Content Area, Cards & Form Controls
       -------------------------------------------------------------------------- */
    .admin-content-area {
      flex: 1;
      padding: 1.5rem;
    }

    .card {
      border: 1px solid var(--border-light);
      border-radius: 12px;
      box-shadow: 0 4px 18px rgba(13, 26, 51, 0.04);
      background-color: #ffffff;
    }

    .page-title {
      font-family: var(--font-serif);
      font-size: 2rem;
      font-weight: 700;
      color: var(--navy-900);
    }

    .product-thumb {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid var(--border-light);
      background-color: #faf6f2;
    }

    input,
    textarea,
    select,
    button {
      color-scheme: light !important;
    }

    .form-control,
    .form-select,
    .modal-content,
    .dropdown-menu {
      color-scheme: light !important;
      background-color: #ffffff;
      color: #1e293b;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--gold-500);
      box-shadow: 0 0 0 0.22rem rgba(212, 175, 55, 0.22);
      color: #0f172a;
      background-color: #ffffff;
    }

    .text-navy {
      color: var(--navy-900) !important;
    }
    .text-gold {
      color: var(--gold-500) !important;
    }
    .bg-navy {
      background-color: var(--navy-800) !important;
      color: #ffffff !important;
    }
    .bg-gold {
      background-color: var(--gold-500) !important;
      color: var(--navy-900) !important;
    }
    .badge-gold {
      background-color: var(--gold-100);
      color: var(--gold-600);
      border: 1px solid rgba(212, 175, 55, 0.3);
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="admin-layout">
    
    <!-- ========================================================================
         COLLAPSIBLE LEFT SIDEBAR (Bootstrap 5 Offcanvas-lg)
         - Collapses to offcanvas drawer on mobile (< 992px)
         - Always visible as fixed/sticky left sidebar on desktop (>= 992px)
         ======================================================================== -->
    <aside class="offcanvas-lg offcanvas-start admin-sidebar d-flex flex-column" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
      
      <!-- Sidebar Brand Header -->
      <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-secondary border-opacity-25">
        <a class="sidebar-brand d-flex align-items-center text-decoration-none" href="dashboard.php" id="adminSidebarLabel">
          <span class="brand-crest me-2"><i class="bi bi-droplet-half"></i></span>
          <div class="d-flex flex-column">
            <div class="d-flex align-items-center">
              <span class="brand-title">BLUOILZ</span>
              <span class="badge brand-badge ms-1">ADMIN</span>
            </div>
            <span class="text-white-50" style="font-size: 0.68rem; letter-spacing: 0.1em;">BOTANICAL PORTAL</span>
          </div>
        </a>
        
        <!-- Mobile close button -->
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
      </div>

      <!-- Navigation Links -->
      <div class="offcanvas-body d-flex flex-column flex-grow-1 px-0 py-2">
        
        <div class="sidebar-section-heading">Navigation</div>
        <ul class="nav nav-pills flex-column mb-1 px-1">
          <!-- 1. Dashboard -->
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <!-- 2. Products -->
          <li class="nav-item">
            <a href="products.php" class="nav-link <?= in_array($currentPage, ['products.php', 'product_add.php', 'product_edit.php']) ? 'active' : '' ?>">
              <i class="bi bi-box-seam"></i>
              <span>Products</span>
            </a>
          </li>

          <!-- 3. Orders -->
          <li class="nav-item">
            <a href="orders.php" class="nav-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>">
              <i class="bi bi-receipt-cutoff"></i>
              <span>Orders</span>
            </a>
          </li>
        </ul>

        <div class="sidebar-section-heading">Formulations</div>
        <ul class="nav nav-pills flex-column mb-auto px-1">
          <li class="nav-item">
            <a href="product_add.php" class="nav-link <?= $currentPage === 'product_add.php' ? 'active' : '' ?>">
              <i class="bi bi-plus-circle"></i>
              <span>Add Product</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="../index.html" target="_blank" class="nav-link">
              <i class="bi bi-shop"></i>
              <span>View Storefront</span>
              <i class="bi bi-box-arrow-up-right ms-auto small text-muted" style="font-size: 0.75rem;"></i>
            </a>
          </li>
        </ul>

        <hr class="sidebar-divider">

        <!-- 4. Logout Link in Sidebar -->
        <ul class="nav nav-pills flex-column px-1 mb-2">
          <li class="nav-item">
            <a href="logout.php" class="nav-link nav-link-logout" onclick="return confirm('Are you sure you want to sign out?');">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>

        <!-- Logged-in Admin Card in Sidebar Bottom -->
        <div class="sidebar-user-widget">
          <div class="d-flex align-items-center">
            <div class="avatar-crest me-2">
              <?= strtoupper(substr($adminUsername, 0, 1)) ?>
            </div>
            <div class="overflow-hidden">
              <div class="fw-bold text-white text-truncate small"><?= htmlspecialchars($adminUsername) ?></div>
              <div class="text-gold extra-small" style="font-size: 0.72rem;">
                <i class="bi bi-shield-check me-1"></i>Verified Admin
              </div>
            </div>
          </div>
        </div>

      </div>
    </aside>

    <!-- ========================================================================
         MAIN CONTENT WRAPPER (Topbar + Page Content + Footer)
         ======================================================================== -->
    <div class="admin-main-wrapper">
      
      <!-- Sticky Topbar -->
      <header class="admin-topbar sticky-top d-flex align-items-center justify-content-between px-3 px-lg-4 shadow-xs">
        
        <div class="d-flex align-items-center">
          <!-- Mobile Hamburger Menu Button (collapses/expands sidebar on mobile) -->
          <button 
            class="btn btn-topbar-toggler d-lg-none me-3" 
            type="button" 
            data-bs-toggle="offcanvas" 
            data-bs-target="#adminSidebar" 
            aria-controls="adminSidebar" 
            aria-label="Toggle Navigation Menu"
          >
            <i class="bi bi-list fs-4"></i>
          </button>

          <!-- Breadcrumb / Section Context -->
          <div class="d-none d-sm-block">
            <span class="text-muted small">Bluoilz Admin <i class="bi bi-chevron-right mx-1 small text-muted"></i></span>
            <span class="fw-bold text-navy"><?= htmlspecialchars($pageTitle ?? 'Overview') ?></span>
          </div>
        </div>

        <!-- Topbar Right: Storefront shortcut and Logged-in Admin Name -->
        <div class="d-flex align-items-center gap-2 gap-md-3">
          
          <a href="../index.html" target="_blank" class="btn btn-sm btn-outline-navy d-none d-md-inline-flex align-items-center">
            <i class="bi bi-shop me-1 text-gold"></i> Live Storefront
          </a>

          <!-- Topbar Logged-in Admin's Name & Profile Dropdown -->
          <div class="dropdown">
            <button 
              class="topbar-admin-badge dropdown-toggle" 
              type="button" 
              id="topbarAdminMenu" 
              data-bs-toggle="dropdown" 
              aria-expanded="false"
            >
              <div class="avatar-badge-sm">
                <?= strtoupper(substr($adminUsername, 0, 1)) ?>
              </div>
              <div class="text-start me-1">
                <div class="extra-small text-muted" style="font-size: 0.65rem; line-height: 1;">ADMINISTRATOR</div>
                <div class="fw-bold text-navy small text-truncate" style="max-width: 140px; line-height: 1.2;">
                  <?= htmlspecialchars($adminUsername) ?>
                </div>
              </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="topbarAdminMenu" style="min-width: 220px;">
              <li class="px-3 py-2 bg-light border-bottom rounded-top">
                <div class="small text-muted">Signed in as</div>
                <div class="fw-bold text-navy"><?= htmlspecialchars($adminUsername) ?></div>
              </li>
              <li>
                <a class="dropdown-item py-2" href="dashboard.php">
                  <i class="bi bi-speedometer2 me-2 text-gold"></i>Dashboard
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2" href="products.php">
                  <i class="bi bi-box-seam me-2 text-gold"></i>Product Formulations
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2" href="orders.php">
                  <i class="bi bi-receipt-cutoff me-2 text-gold"></i>Orders Ledger
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item py-2 text-danger" href="logout.php" onclick="return confirm('Are you sure you want to sign out?');">
                  <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                </a>
              </li>
            </ul>
          </div>

        </div>
      </header>

      <!-- Consistent Content Canvas for Tables and Forms -->
      <main class="admin-content-area flex-grow-1 p-3 p-lg-4">
