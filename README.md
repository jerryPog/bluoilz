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
├── index.html        # Main semantic HTML markup with meta tags & SEO
├── styles.css        # Luxury styling system, keyframe animations & responsive queries
├── app.js            # Interactive catalog, cart engine, quiz, search & modals
├── assets/           # High-resolution imagery for hero & journal features
│   ├── hero.jpg
│   ├── ingredients.jpg
│   └── glowing_portrait.jpg
└── README.md
```

---

## 📄 License
All rights reserved © Bluoilz The Skin Alchemist.
