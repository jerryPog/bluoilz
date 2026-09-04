# Bluoilz Skincare — Therapeutic Natural Formulations

A luxury, modern, and responsive e-commerce web application for **Bluoilz The Skin Alchemist**, showcasing therapeutic botanical skincare formulations curated for tropical and humid climates.

Inspired by aesthetic, high-end editorial visual design with interactive features, micro-animations, and full cross-device compatibility.

---

## ✨ Features

- **Lumina-Inspired Visual Aesthetic**: Soft blush rose palette, elegant serif typography (*Cormorant Garamond*), and clean modern sans-serif (*Plus Jakarta Sans*).
- **Responsive on All Devices**: Optimized across mobile phones (320px–480px), tablets (768px–1024px), laptops, and desktop screens.
- **Micro-Animations & Micro-Interactions**:
  - Floating badges with gentle breathing keyframes
  - Subtle button gleams and hover elevation
  - Interactive wishlist hearts with bounce animations
  - Scroll-triggered reveal animations via `IntersectionObserver`
  - Sticky frosted-glass header with elevation on scroll
- **Dynamic Product Catalog**:
  - Filter products by skin concern (Pigmentation, Fungal, Sensitive, Acne, Psoriasis, Stress/Pain)
  - Quick View modal with multi-tab details and customer reviews
  - Staggered animations and instant responsive category navigation
- **Interactive 3-Step Skin Diagnostic**:
  - Tailored skin barrier assessment
  - Custom botanical prescription with 1-click bundle add-to-bag
- **Slide-out Cart & Checkout Experience**:
  - Real-time subtotal calculations and free-shipping progress tracker
  - Coupon code integration (`BLUOILZ10`, `TROPICAL`)
  - Full simulated checkout workflow with order ID generation and confirmation
- **Live Search Modal**:
  - Instant live filtering by symptoms, concerns, or botanical actives
  - Fast quick-tag suggestions

---

## 🚀 Getting Started

### Prerequisites
- Any modern web browser (Chrome, Safari, Firefox, Edge)
- Python (or Node.js) for serving locally

### Run Locally

```bash
# Clone the repository
git clone https://github.com/jerryPog/bluoilz.git
cd bluoilz

# Serve with Python
python -m http.server 3000

# OR serve with Node
npx serve -l 3000
```

Open your browser at **`http://localhost:3000`**.

---

## 📁 Project Structure

```
bluoilz/
├── index.html        # Main boutique storefront with full section deep links
├── waitlist.html     # Private batch access & gated reservation page
├── 404.html          # Custom formulation alignment 404 error page
├── sitemap.xml       # XML Sitemap with image metadata for SEO
├── robots.txt        # Web crawler directives & sitemap reference
├── styles.css        # Luxury styling system, animations & responsive queries
├── app.js            # Interactive catalog, cart engine, quiz, search & modals
├── assets/           # High-resolution botanical imagery & assets
│   ├── hero.jpg
│   ├── ingredients.jpg
│   ├── glowing_portrait.jpg
│   ├── anti_pigmentation.jpg
│   ├── anti_fungal.png
│   ├── anti_allergy.jpg
│   ├── psoriasis_cream.jpg
├── admin/            # Secure PHP administration portal
│   ├── db.php            # PDO database connection configuration
│   ├── session_check.php # Protected page session guard include
│   ├── login.php         # Admin authentication (password_verify, CSRF)
│   ├── logout.php        # Session destruction script
│   ├── dashboard.php     # Protected admin operations dashboard
│   ├── header.php        # Shared Bootstrap 5 navigation layout
│   ├── footer.php        # Shared Bootstrap 5 footer layout
│   ├── products.php      # Full product management list with Bootstrap table
│   ├── product_add.php   # Add product form (validation, /uploads image storage)
│   ├── product_edit.php  # Edit product form pre-filled with data
│   ├── product_delete.php# Delete controller with CSRF & integrity protection
│   ├── orders.php        # Order management (JOIN query, status filters & updates)
│   ├── index.php         # Admin route guard
│   └── create_admin.php  # Admin account creation helper (password_hash)
├── uploads/          # Secure product uploaded images directory
├── schema.sql        # MySQL relational database schema (phpMyAdmin ready)
└── README.md
```

---

## 📄 License
All rights reserved © Bluoilz The Skin Alchemist.
