/**
 * Bluoilz Skincare Website Data & Interactive Engine
 * Exclusively Therapeutic Segment & Ancient Curation (We Prepare As You Book)
 */

const PRODUCTS = [
  {
    id: "anti-pigmentation-cream",
    title: "Anti Pigmentation Cream",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "pigmentation",
    price: 599,
    originalPrice: 749,
    rating: 4.9,
    reviewCount: 128,
    badge: "Bestseller",
    curation: "Ancient Method • Small-Batch Botanical Distillation",
    weight: "50 g",
    image: "assets/anti_pigmentation.jpg",
    description: "A clinically potent therapeutic formulation crafted using ancient botanical alchemy for tropical and humidity-exposed skin. Prepared fresh upon booking with zero storage stabilizers to reduce hyperpigmentation and melasma patches without barrier irritation.",
    keyBenefits: [
      "We prepare fresh as you book — zero warehouse shelf life",
      "Fades stubborn blemishes, UV spots & melasma patches",
      "Ancient botanical alchemy using cold-pressed herbal lipids",
      "Free from hydroquinone, parabens & synthetic dyes"
    ],
    ingredients: "Kojic Dipalmitate, Alpha Arbutin, Licorice Root Extract, Niacinamide, Cold-Pressed Jojoba Oil, Aloe Vera Leaf Juice, Vitamin E."
  },
  {
    id: "anti-fungal-cream",
    title: "Anti Fungal Climate Cream",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "fungal",
    price: 499,
    originalPrice: 620,
    rating: 4.8,
    reviewCount: 94,
    badge: "Climate Shield",
    curation: "Ancient Method • Herbal Microflora Defense",
    weight: "50 g",
    image: "assets/anti_fungal.png",
    description: "Engineered specifically to counter humidity-induced fungal irritation, sweat rashes, and chafing. Freshly prepared as you book using ancient Ayurvedic extracts like Karanja and Neem to cool inflamed, itchy skin.",
    keyBenefits: [
      "Freshly prepared upon your booking for peak herbal potency",
      "Rapidly alleviates sweat rash, redness & chafing",
      "Reinforces dermal microflora in high-humidity zones",
      "100% breathable formulation suitable for active wear"
    ],
    ingredients: "Neem Seed Oil, Organic Tea Tree Leaf Extract, Karanja Oil, Turmeric Rhizome Extract, Zinc PCA, Beeswax, Calendula Infusion."
  },
  {
    id: "anti-allergy-cream",
    title: "Anti Allergy SOS Cream",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "sensitive",
    price: 399,
    originalPrice: 499,
    rating: 4.9,
    reviewCount: 156,
    badge: "Barrier SOS",
    curation: "Ancient Method • Colloidal Barrier SOS",
    weight: "50 g",
    image: "assets/anti_allergy.jpg",
    description: "An SOS therapeutic shield designed for hyper-reactive, allergic skin. Freshly compounded as you book using ancient colloidal oat distillation to soothe histamine flares, contact redness, and compromised barrier tissue.",
    keyBenefits: [
      "Handcrafted upon booking — uncompromised therapeutic freshness",
      "Instant relief from allergic hives, itching & irritation",
      "Reconstructs compromised skin lipid matrix",
      "Steroid-free comfort for daily preventative use"
    ],
    ingredients: "Colloidal Oatmeal, Centella Asiatica (Gotu Kola), Chamomile Flower Extract, Shea Butter, Evening Primrose Oil, Squalane."
  },
  {
    id: "psoriasis-support-cream",
    title: "Psoriasis Support Cream",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "psoriasis",
    price: 599,
    originalPrice: 750,
    rating: 4.9,
    reviewCount: 88,
    badge: "Intensive Relief",
    curation: "Ancient Method • Wrightia Tinctoria Alchemy",
    weight: "60 g",
    image: "assets/psoriasis_cream.jpg",
    description: "Deeply restorative lipid-replenishing emollient formulated using ancient Wrightia Tinctoria distillation. Prepared fresh as you book to soften thick, scaly plaques and relieve severe xerosis without synthetic occlusives.",
    keyBenefits: [
      "Prepared upon booking — biologically active plant phytosterols",
      "Softens tough epidermal flakes & rough patches",
      "Sustained 24-hour barrier hydration shield",
      "Reduces scaling, cracking, and stinging sensations"
    ],
    ingredients: "Mahonia Aquifolium Extract, Wrightia Tinctoria Leaf Oil, Shea Butter, Virgin Coconut Oil, Borage Seed Oil, Beeswax."
  },
  {
    id: "migraine-relief-oil",
    title: "Migraine & Tension Roll-on",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "stress-pain",
    price: 149,
    originalPrice: 199,
    rating: 4.9,
    reviewCount: 312,
    badge: "Pocket Healer",
    curation: "Ancient Method • Pure Herbal Distillate",
    weight: "10 ml",
    image: "assets/migraine_oil.jpg",
    description: "An aromatherapeutic fast-acting roll-on infused with pure therapeutic-grade wintergreen, peppermint, and lavender distillates. Hand-bottled as you book to dissolve forehead tension, sinus pressure, and headaches in minutes.",
    keyBenefits: [
      "Freshly bottled upon booking — active volatile aromatherapeutics",
      "Instant cooling pressure release upon temple application",
      "Eases stress-induced neck tension & migraine throbbing",
      "Portable spill-proof roll-on applicator"
    ],
    ingredients: "Mentha Piperita (Peppermint) Oil, Gaultheria Procumbens (Wintergreen) Oil, Lavandula Angustifolia Oil, Eucalyptus Globulus, Sweet Almond Carrier Oil."
  }
];

// State Management
let cart = [];
try {
  cart = JSON.parse(localStorage.getItem('bluoilz_cart')) || [];
} catch (e) {
  cart = [];
}

let activeFilter = 'all';
let appliedPromo = null;
try {
  appliedPromo = JSON.parse(sessionStorage.getItem('bluoilz_promo')) || null;
} catch (e) {
  appliedPromo = null;
}
let currentRecommendedBundle = [];

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  // If page is loaded without a hash, ensure viewport starts at the top
  if (!window.location.hash) {
    if ('scrollRestoration' in history) {
      history.scrollRestoration = 'manual';
    }
    window.scrollTo(0, 0);
  }

  renderProducts();
  setupFilterTabs();
  setupModalEvents();
  setupCartDrawer();
  updateCartBadge();
  setupNewsletter();
  setupMobileNav();
  setupDiagnosticQuiz();
  setupLiveSearch();
  setupCheckoutModal();
  setupHeaderScrollEffect();
  setupScrollReveal();
  setupBackToTop();
  setupHeroTiltEffect();
});

// Render Products
function renderProducts(filter = 'all') {
  activeFilter = filter;
  const tabs = document.querySelectorAll('.filter-tab');
  tabs.forEach(t => {
    if (t.getAttribute('data-filter') === filter) {
      t.classList.add('active');
    } else {
      t.classList.remove('active');
    }
  });

  const container = document.getElementById('productsGrid');
  if (!container) return;

  const filtered = filter === 'all' 
    ? PRODUCTS 
    : PRODUCTS.filter(p => p.concern === filter || p.category === filter);

  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 48px 20px; background: #ffffff; border: 1px solid var(--color-border); border-radius: var(--radius-md); margin: 20px 0;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--color-accent); margin-bottom: 12px;">
          <circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        <p style="font-size: 1.1rem; color: var(--color-text-main); margin-bottom: 16px;">No formulations found under this specific concern.</p>
        <button class="btn btn-primary" onclick="resetFilter()">View All Formulations</button>
      </div>
    `;
    return;
  }

  container.innerHTML = filtered.map(product => `
    <div class="product-card reveal-init" data-id="${product.id}">
      <div class="product-media" onclick="openQuickView('${product.id}')" role="button" tabindex="0" aria-label="Quick view ${product.title}">
        ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
        <button class="product-wishlist-btn" onclick="event.stopPropagation(); toggleWishlist('${product.id}', this)" aria-label="Save to wishlist" title="Save to wishlist">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
        </button>
        <img src="${product.image}" alt="${product.title}" loading="lazy" class="product-img" onerror="this.onerror=null; this.src='assets/anti_pigmentation.jpg';">
        <span class="quick-view-hint">Quick View</span>
      </div>
      <div class="product-content">
        <div class="product-meta-row">
          <span class="product-category-text">${product.categoryLabel}</span>
          <span class="product-weight-text">${product.weight}</span>
        </div>
        <div class="product-fresh-status">
          <span class="status-pulse-dot"></span>
          <span>Prepared Fresh As You Book</span>
        </div>
        <h3 class="product-title">
          <a href="${product.id}.html" style="color: inherit; text-decoration: none;">${product.title}</a>
        </h3>
        
        <div class="product-rating">
          <div class="stars" aria-label="${product.rating} stars">
            ${'★'.repeat(Math.floor(product.rating))}
            <span class="star-empty">${product.rating % 1 !== 0 ? '½' : ''}</span>
          </div>
          <span class="rating-text">${product.rating} <span class="rating-count">(${product.reviewCount})</span></span>
        </div>

        <div class="product-price-row">
          <div class="price-wrap">
            <span class="price-current">₹${product.price.toFixed(2)}</span>
            <span class="price-original">₹${product.originalPrice.toFixed(2)}</span>
          </div>
          <button class="btn-add-cart" onclick="addToCart('${product.id}')" aria-label="Add ${product.title} to bag">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span>Add to Bag</span>
          </button>
        </div>
        <div style="margin-top: 8px; text-align: center;">
          <a href="${product.id}.html" style="font-size: 0.78rem; color: var(--color-accent); font-weight: 600; text-decoration: none;">View Full Details & Benefits &rarr;</a>
        </div>
      </div>
    </div>
  `).join('');

  setupScrollReveal();
}

// Filter Tabs
function setupFilterTabs() {
  const tabs = document.querySelectorAll('.filter-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      activeFilter = tab.getAttribute('data-filter');
      renderProducts(activeFilter);
    });
  });
}

function resetFilter() {
  const allTab = document.querySelector('.filter-tab[data-filter="all"]');
  if (allTab) allTab.click();
}

// Quick View Modal
function openQuickView(productId) {
  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;

  const modal = document.getElementById('quickViewModal');
  const body = document.getElementById('quickViewContent');
  
  // Sample realistic verified reviews per product
  const reviews = [
    { name: "Meera Sen", rating: 5, time: "Verified Buyer • 2 weeks ago", text: "Truly non-comedogenic! Most creams feel suffocating in humid weather, but this formula absorbs instantly and brought visible relief." },
    { name: "Dr. Anirudh K.", rating: 5, time: "Verified Buyer • 1 month ago", text: "Clean botanical alchemical formulation. Remarkable ingredient integrity without artificial silicones or heavy occlusives." },
    { name: "Priya V.", rating: 5, time: "Verified Buyer • 3 weeks ago", text: "Transformed my daily skin routine. My skin barrier feels restored and no longer flares red from temperature changes." }
  ];

  body.innerHTML = `
    <div class="modal-grid">
      <div class="modal-gallery">
        <span class="product-badge modal-badge">${product.badge}</span>
        <img src="${product.image}" alt="${product.title}" class="modal-img" onerror="this.onerror=null; this.src='assets/anti_pigmentation.jpg';">
      </div>
      <div class="modal-details">
        <span class="modal-category">${product.categoryLabel} &bull; ${product.weight}</span>
        <h2 class="modal-title">${product.title}</h2>
        
        <div class="product-rating modal-rating">
          <div class="stars">${'★'.repeat(Math.floor(product.rating))}</div>
          <span class="rating-text">${product.rating} (${product.reviewCount} customer reviews)</span>
        </div>

        <div class="modal-price-row">
          <span class="modal-price">₹${product.price.toFixed(2)}</span>
          <span class="modal-orig-price">₹${product.originalPrice.toFixed(2)}</span>
          <span class="modal-discount-tag">Save ₹${(product.originalPrice - product.price).toFixed(2)}</span>
        </div>

        <p class="modal-desc">${product.description}</p>

        <!-- Tabbed Information -->
        <div class="modal-tabs-header">
          <button type="button" class="modal-tab-btn active" onclick="switchModalTab(event, 'tab-benefits')">Key Benefits</button>
          <button type="button" class="modal-tab-btn" onclick="switchModalTab(event, 'tab-ingredients')">Full Ingredients</button>
          <button type="button" class="modal-tab-btn" onclick="switchModalTab(event, 'tab-reviews')">Customer Reviews (${product.reviewCount})</button>
        </div>

        <div class="modal-tab-pane active" id="tab-benefits">
          <div class="modal-key-benefits" style="margin-top: 0;">
            <ul>
              ${product.keyBenefits.map(b => `<li><span class="bullet">✔</span> ${b}</li>`).join('')}
            </ul>
          </div>
        </div>

        <div class="modal-tab-pane" id="tab-ingredients">
          <div class="modal-ingredients" style="margin-top: 0;">
            <p>${product.ingredients}</p>
            <small style="color: var(--color-text-muted); display: block; margin-top: 8px;">100% free from harsh petroleum mineral oils, synthetic perfumes, and parabens.</small>
          </div>
        </div>

        <div class="modal-tab-pane" id="tab-reviews">
          <div class="modal-reviews-list">
            ${reviews.map(r => `
              <div class="modal-review-item">
                <div class="modal-review-author">
                  <span>${r.name}</span>
                  <small>✔ ${r.time}</small>
                </div>
                <div class="stars" style="font-size: 0.8rem; margin: 2px 0;">★★★★★</div>
                <p class="modal-review-text">"${r.text}"</p>
              </div>
            `).join('')}
          </div>
        </div>

        <div class="modal-actions" style="margin-top: 24px;">
          <div class="qty-selector">
            <button type="button" class="qty-btn" onclick="adjustModalQty(-1)">-</button>
            <input type="number" id="modalQty" value="1" min="1" max="10">
            <button type="button" class="qty-btn" onclick="adjustModalQty(1)">+</button>
          </div>
          <button class="btn btn-primary btn-modal-add" onclick="addModalToCart('${product.id}')">
            Add to Cart &bull; ₹<span id="modalBtnPrice">${product.price.toFixed(2)}</span>
          </button>
        </div>

        <div class="modal-trust-points">
          <span>🌿 100% Pure Botanical Actives</span>
          <span>🧪 Microbiome Friendly</span>
          <span>🚚 Dispatches within 24h</span>
        </div>

        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--color-border); display: flex; flex-direction: column; gap: 6px;">
          <a href="${product.id}.html" class="btn btn-secondary" style="width: 100%; justify-content: center; text-decoration: none; padding: 10px 16px; margin-bottom: 6px; font-size: 0.88rem;">View Complete Formulation Page &rarr;</a>
          <a href="#quiz" onclick="closeQuickView()" class="modal-link-hint">✨ Not sure? Find your barrier match in our 60-Sec Skin Diagnostic &rarr;</a>
          <a href="#curation" onclick="closeQuickView()" class="modal-link-hint">🌿 Learn how this remedy is prepared fresh upon your booking &rarr;</a>
          <a href="waitlist.html" class="modal-link-hint">⏳ Check private batch waitlist schedule &rarr;</a>
        </div>
      </div>
    </div>
  `;

  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function switchModalTab(event, paneId) {
  const container = event.target.closest('.modal-details');
  if (!container) return;
  
  container.querySelectorAll('.modal-tab-btn').forEach(btn => btn.classList.remove('active'));
  container.querySelectorAll('.modal-tab-pane').forEach(pane => pane.classList.remove('active'));
  
  event.target.classList.add('active');
  const targetPane = document.getElementById(paneId);
  if (targetPane) targetPane.classList.add('active');
}

function adjustModalQty(delta) {
  const input = document.getElementById('modalQty');
  if (!input) return;
  let val = parseInt(input.value, 10) + delta;
  if (val < 1) val = 1;
  if (val > 10) val = 10;
  input.value = val;

  const modalPrice = document.querySelector('.modal-price');
  const btnPrice = document.getElementById('modalBtnPrice');
  if (modalPrice && btnPrice) {
    const unitPrice = parseFloat(modalPrice.textContent.replace('₹', ''));
    btnPrice.textContent = (unitPrice * val).toFixed(2);
  }
}

function addModalToCart(productId) {
  const input = document.getElementById('modalQty');
  const qty = input ? parseInt(input.value, 10) : 1;
  addToCart(productId, qty);
  closeQuickView();
}

function setupModalEvents() {
  const modal = document.getElementById('quickViewModal');
  const closeBtn = document.getElementById('closeModalBtn');
  const backdrop = document.getElementById('modalBackdrop');

  if (closeBtn) closeBtn.addEventListener('click', closeQuickView);
  if (backdrop) backdrop.addEventListener('click', closeQuickView);

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeQuickView();
      closeCart();
      if (typeof closeSearchModal === 'function') closeSearchModal();
      if (typeof closeCheckoutModal === 'function') closeCheckoutModal();
      const mobileNav = document.getElementById('mobileNavMenu');
      const mobileBackdrop = document.getElementById('mobileNavBackdrop');
      if (mobileNav) mobileNav.classList.remove('active');
      if (mobileBackdrop) mobileBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    }
  });
}

function closeQuickView() {
  const modal = document.getElementById('quickViewModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// Cart Drawer Management
function addToCart(productId, quantity = 1) {
  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;

  quantity = parseInt(quantity, 10);
  if (isNaN(quantity) || quantity <= 0) quantity = 1;
  if (quantity > 50) quantity = 50;

  const existingItem = cart.find(item => item.id === productId);
  if (existingItem) {
    existingItem.quantity = Math.min(50, Math.max(1, existingItem.quantity + quantity));
  } else {
    cart.push({
      id: product.id,
      title: product.title,
      price: product.price,
      weight: product.weight,
      image: product.image,
      quantity: quantity
    });
  }

  saveCart();
  updateCartBadge();
  renderCartDrawer();
  openCart();
  showToast(`Added ${quantity}x "${product.title.split('–')[0].trim()}" to bag`);
}

function updateCartItemQty(productId, delta) {
  const item = cart.find(i => i.id === productId);
  if (!item) return;

  delta = parseInt(delta, 10);
  if (isNaN(delta)) delta = 0;

  item.quantity += delta;
  if (isNaN(item.quantity) || item.quantity <= 0) {
    cart = cart.filter(i => i.id !== productId);
  } else if (item.quantity > 50) {
    item.quantity = 50;
    showToast('Maximum 50 units per formulation allowed.');
  }

  saveCart();
  updateCartBadge();
  renderCartDrawer();
}

function removeCartItem(productId) {
  cart = cart.filter(i => i.id !== productId);
  saveCart();
  updateCartBadge();
  renderCartDrawer();
  showToast('Item removed from cart');
}

function saveCart() {
  try {
    localStorage.setItem('bluoilz_cart', JSON.stringify(cart));
  } catch (e) {
    console.warn('LocalStorage unavailable:', e);
  }
}

function updateCartBadge() {
  const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
  const badges = document.querySelectorAll('.cart-badge');
  badges.forEach(b => {
    b.textContent = totalCount;
    b.style.display = totalCount > 0 ? 'flex' : 'none';
  });
}

function renderCartDrawer() {
  const itemsContainer = document.getElementById('cartDrawerItems');
  const subtotalElem = document.getElementById('cartDrawerSubtotal');
  const discountRow = document.getElementById('cartDiscountRow');
  const discountElem = document.getElementById('cartDrawerDiscount');
  const freeShippingElem = document.getElementById('shippingProgress');
  const upsellBox = document.getElementById('cartUpsellBox');
  if (!itemsContainer) return;

  if (cart.length === 0) {
    itemsContainer.innerHTML = `
      <div class="cart-empty-view">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <h4>Your Bag is Empty</h4>
        <p>Explore our therapeutic skincare formulas freshly compounded upon your booking.</p>
        <div style="display: flex; flex-direction: column; gap: 8px; width: 100%; max-width: 260px; margin: 16px auto 0;">
          <a href="#products" class="btn btn-primary" onclick="closeCart()" style="font-size: 0.85rem; padding: 11px 18px; justify-content: center;">Explore Therapeutic Care &rarr;</a>
          <a href="#quiz" class="btn btn-secondary" onclick="closeCart()" style="font-size: 0.85rem; padding: 10px 18px; justify-content: center;">Take Skin Diagnostic Quiz</a>
          <a href="waitlist.html" class="btn-ghost-pill" onclick="closeCart()" style="font-size: 0.8rem; padding: 8px 14px; justify-content: center;">🌿 Join Next Batch Waitlist</a>
        </div>
      </div>
    `;
    if (subtotalElem) subtotalElem.textContent = '₹0.00';
    if (freeShippingElem) freeShippingElem.style.width = '0%';
    if (discountRow) discountRow.style.display = 'none';
    if (upsellBox) upsellBox.style.display = 'none';
    return;
  }

  const baseSubtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  
  // Calculate discount if promo applied
  let discountAmount = 0;
  if (appliedPromo) {
    if (appliedPromo.type === 'percent') {
      discountAmount = (baseSubtotal * appliedPromo.value) / 100;
    } else if (appliedPromo.type === 'flat') {
      discountAmount = appliedPromo.value;
    }
  }
  const finalSubtotal = Math.max(0, baseSubtotal - discountAmount);

  if (discountRow && discountElem) {
    if (discountAmount > 0) {
      discountRow.style.display = 'flex';
      discountElem.textContent = `-₹${discountAmount.toFixed(2)} (${appliedPromo.code})`;
    } else {
      discountRow.style.display = 'none';
    }
  }

  if (subtotalElem) subtotalElem.textContent = `₹${finalSubtotal.toFixed(2)}`;

  // Free shipping threshold at ₹999
  const threshold = 999;
  const progress = Math.min(100, (baseSubtotal / threshold) * 100);
  const shippingMsg = document.getElementById('shippingNotice');
  if (shippingMsg) {
    if (baseSubtotal >= threshold) {
      shippingMsg.innerHTML = `🎉 <strong>Congratulations!</strong> You qualify for <strong>FREE Shipping</strong>!`;
    } else {
      const remaining = threshold - baseSubtotal;
      shippingMsg.innerHTML = `Add <strong>₹${remaining.toFixed(2)}</strong> more to unlock <strong>FREE Shipping</strong>!`;
    }
  }
  if (freeShippingElem) {
    freeShippingElem.style.width = `${progress}%`;
  }

  // Quick Upsell: If subtotal < 999 and migraine roll-on isn't in cart, offer it
  const hasRollon = cart.some(i => i.id === 'migraine-relief-oil');
  if (upsellBox) {
    if (baseSubtotal < threshold && !hasRollon) {
      upsellBox.style.display = 'flex';
      upsellBox.innerHTML = `
        <div class="upsell-info">
          <a href="migraine-relief-oil.html"><img src="assets/migraine_oil.jpg" alt="Migraine Roll On"></a>
          <div>
            <strong><a href="migraine-relief-oil.html" style="color:inherit; text-decoration:none;">Quick Add: Migraine Roll-On</a></strong>
            <p>Pocket tension healer &bull; ₹149.00</p>
          </div>
        </div>
        <button class="btn-add-upsell" onclick="addToCart('migraine-relief-oil')">+ Add ₹149</button>
      `;
    } else {
      upsellBox.style.display = 'none';
    }
  }

  itemsContainer.innerHTML = cart.map(item => `
    <div class="cart-item">
      <a href="${item.id}.html"><img src="${item.image}" alt="${item.title}" class="cart-item-img"></a>
      <div class="cart-item-info">
        <h5 class="cart-item-title"><a href="${item.id}.html" style="color:inherit; text-decoration:none;">${item.title}</a></h5>
        <div class="cart-item-meta">${item.weight} &bull; ₹${item.price.toFixed(2)}</div>
        <div class="cart-item-controls">
          <div class="qty-selector small">
            <button class="qty-btn" onclick="updateCartItemQty('${item.id}', -1)">-</button>
            <span>${item.quantity}</span>
            <button class="qty-btn" onclick="updateCartItemQty('${item.id}', 1)">+</button>
          </div>
          <button class="cart-item-remove" onclick="removeCartItem('${item.id}')">Remove</button>
        </div>
      </div>
      <div class="cart-item-price">
        ₹${(item.price * item.quantity).toFixed(2)}
      </div>
    </div>
  `).join('');
}

// Promo Code Management
function applyPromoCode() {
  const input = document.getElementById('cartPromoInput');
  const msgElem = document.getElementById('promoMessage');
  if (!input || !msgElem) return;

  const code = input.value.trim().toUpperCase();
  if (!code) {
    msgElem.textContent = 'Please enter a valid promo code.';
    msgElem.className = 'promo-message error';
    return;
  }

  if (code === 'BLUOILZ10') {
    appliedPromo = { code: 'BLUOILZ10', type: 'percent', value: 10 };
    sessionStorage.setItem('bluoilz_promo', JSON.stringify(appliedPromo));
    msgElem.textContent = '✨ 10% Botanical Privilege discount applied!';
    msgElem.className = 'promo-message success';
    renderCartDrawer();
  } else if (code === 'TROPICAL' || code === 'FREESHIP') {
    appliedPromo = { code: code, type: 'flat', value: 100 };
    sessionStorage.setItem('bluoilz_promo', JSON.stringify(appliedPromo));
    msgElem.textContent = '✨ ₹100 Tropical Climate credit applied!';
    msgElem.className = 'promo-message success';
    renderCartDrawer();
  } else {
    msgElem.textContent = 'Invalid promo code. Try "BLUOILZ10" for 10% off.';
    msgElem.className = 'promo-message error';
  }
}

function setupCartDrawer() {
  const openButtons = document.querySelectorAll('.open-cart-btn');
  const closeButton = document.getElementById('closeCartBtn');
  const backdrop = document.getElementById('cartBackdrop');

  openButtons.forEach(btn => btn.addEventListener('click', (e) => {
    e.preventDefault();
    renderCartDrawer();
    openCart();
  }));

  if (closeButton) closeButton.addEventListener('click', closeCart);
  if (backdrop) backdrop.addEventListener('click', closeCart);
}

function openCart() {
  const drawer = document.getElementById('cartDrawer');
  if (drawer) {
    drawer.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeCart() {
  const drawer = document.getElementById('cartDrawer');
  if (drawer) {
    drawer.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// ==========================================================================
// Interactive Skin Diagnostic Quiz Engine
// ==========================================================================
const QUIZ_QUESTIONS = [
  {
    step: 1,
    title: "How does your skin behave under tropical heat or humidity?",
    desc: "Select the profile that best reflects your barrier balance during daily exposure.",
    options: [
      { id: "oily-sweat", icon: "💧", label: "Oily & Sweat-Prone", detail: "Excess sebum shine, sweat rash, congested pores, and humidity breakouts." },
      { id: "reactive-allergy", icon: "🌿", label: "Sensitive & Reactive", detail: "Flushes red easily, chemical sensitivity, allergic chafing, or heat hives." },
      { id: "uneven-sun", icon: "☀️", label: "Pigmented & Sun-Damaged", detail: "Stubborn dark patches, melasma, uneven dermal tone, and dullness." },
      { id: "flaky-dry", icon: "🍃", label: "Dry, Scaly or Plaque", detail: "Persistent flaking, tight parched patches, or mild psoriasis discomfort." }
    ]
  },
  {
    step: 2,
    title: "What is your primary clinical skin goal?",
    desc: "Pinpoint the primary restorative action you seek.",
    options: [
      { id: "fade-spots", icon: "🌸", label: "Fade Pigmentation & Melasma", detail: "Even tone, fade UV damage, and restore unadulterated botanical clarity." },
      { id: "calm-flare", icon: "🛡️", label: "Soothe Itch, Sweat Rash & Allergy", detail: "Cool burning sensations, monsoon chafe, and histamine flare-ups." },
      { id: "flaky-dry", icon: "🌿", label: "Psoriasis & Deep Flaking", detail: "Softens thick rough plaques, relieves scaling, and replenishes lipids." },
      { id: "headache-calm", icon: "✨", label: "Ease Tension & Migraine", detail: "Rapid cooling temple relief from heavy mental tension and sinus pressure." }
    ]
  },
  {
    step: 3,
    title: "What is your preferred daily therapeutic commitment?",
    desc: "How comprehensive would you like your ancient botanical formulation to be?",
    options: [
      { id: "targeted-sos", icon: "⚡", label: "Targeted SOS Solution", detail: "A singular, potent active therapeutic formula freshly prepared for your exact symptom." },
      { id: "complete-barrier", icon: "🌿", label: "Dual Therapeutic Synergy", detail: "Two complementary therapeutic formulations working together for lasting equilibrium." }
    ]
  }
];

let quizAnswers = { 1: null, 2: null, 3: null };
let currentQuizStep = 1;

function setupDiagnosticQuiz() {
  renderQuizStep(1);
}

function renderQuizStep(step) {
  const container = document.getElementById('quizContainer');
  if (!container) return;

  currentQuizStep = step;
  const q = QUIZ_QUESTIONS.find(item => item.step === step);
  if (!q) return;

  container.innerHTML = `
    <div class="quiz-card">
      <div class="quiz-progress-bar">
        <span class="quiz-step-indicator">Question ${step} of 3</span>
        <div class="quiz-dots">
          <div class="quiz-dot ${step === 1 ? 'active' : ''}"></div>
          <div class="quiz-dot ${step === 2 ? 'active' : ''}"></div>
          <div class="quiz-dot ${step === 3 ? 'active' : ''}"></div>
        </div>
      </div>

      <h3 class="quiz-question-title">${q.title}</h3>
      <p class="quiz-question-desc">${q.desc}</p>

      <div class="quiz-options-grid">
        ${q.options.map(opt => `
          <button type="button" 
                  class="quiz-option-btn ${quizAnswers[step] === opt.id ? 'selected' : ''}" 
                  onclick="selectQuizOption(${step}, '${opt.id}')">
            <span class="option-icon">${opt.icon}</span>
            <span class="option-label">${opt.label}</span>
            <span class="option-detail">${opt.detail}</span>
          </button>
        `).join('')}
      </div>

      <div class="quiz-nav-row">
        ${step > 1 ? `<button type="button" class="btn btn-secondary" onclick="renderQuizStep(${step - 1})">&larr; Previous</button>` : `<div></div>`}
        <button type="button" 
                class="btn btn-primary" 
                id="quizNextBtn" 
                ${!quizAnswers[step] ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''} 
                onclick="nextQuizStep()">
          ${step === 3 ? 'Reveal My Regimen ✨' : 'Continue &rarr;'}
        </button>
      </div>
    </div>
  `;
}

function selectQuizOption(step, optionId) {
  quizAnswers[step] = optionId;
  renderQuizStep(step);
}

function nextQuizStep() {
  if (!quizAnswers[currentQuizStep]) return;
  if (currentQuizStep < 3) {
    renderQuizStep(currentQuizStep + 1);
  } else {
    calculateQuizRecommendation();
  }
}

function calculateQuizRecommendation() {
  const container = document.getElementById('quizContainer');
  if (!container) return;

  let recommendedIds = [];
  let title = "Therapeutic Dermal Balance Regimen";
  let description = "Prepared fresh as you book using ancient botanical alchemy to deliver maximum bioactive healing without storage stabilizers.";

  if (quizAnswers[2] === 'calm-flare' || quizAnswers[1] === 'oily-sweat') {
    title = "Anti-Fungal & Humidity Rash Prescription";
    description = "Ancient Neem and Karanja distillates that neutralize sweat-induced fungal irritation and bacterial inflammation without stripping moisture.";
    recommendedIds = quizAnswers[3] === 'targeted-sos'
      ? ['anti-fungal-cream']
      : ['anti-fungal-cream', 'anti-allergy-cream'];
  } else if (quizAnswers[2] === 'fade-spots' || quizAnswers[1] === 'uneven-sun') {
    title = "Radiant Melanin-Balance Prescription";
    description = "Fades stubborn UV damage and hyperpigmentation with Kojic Dipalmitate and Alpha Arbutin in a lightweight breathable base.";
    recommendedIds = ['anti-pigmentation-cream'];
  } else if (quizAnswers[2] === 'headache-calm') {
    title = "Neuro-Aromatic Tension Relief";
    description = "Rapid temple relief using pure wintergreen, cooling menthol, and lavender distillates to dissolve headache pressure in minutes.";
    recommendedIds = ['migraine-relief-oil'];
  } else if (quizAnswers[1] === 'flaky-dry' || quizAnswers[2] === 'flaky-dry') {
    title = "Intensive Lipid Repair & Plaque Calming";
    description = "Restorative Wrightia Tinctoria and phytosterol cold-pressed emollients to soften dry flakes and rebuild lipid matrices.";
    recommendedIds = ['psoriasis-support-cream'];
  } else {
    title = "Anti-Allergic Barrier Shield Prescription";
    description = "Colloidal oat and Centella Asiatica barrier defense to soothe monsoon rash, chafing, and histamine skin reactions.";
    recommendedIds = quizAnswers[3] === 'targeted-sos'
      ? ['anti-allergy-cream']
      : ['anti-allergy-cream', 'anti-fungal-cream'];
  }

  const recProducts = PRODUCTS.filter(p => recommendedIds.includes(p.id));
  const bundleTotal = recProducts.reduce((sum, p) => sum + p.price, 0);

  currentRecommendedBundle = recommendedIds;

  container.innerHTML = `
    <div class="quiz-result-box">
      <span class="result-badge">Botanical Prescription Ready</span>
      <h3 class="result-heading">${title}</h3>
      <p class="result-prescription-text">${description}</p>

      <div class="routine-products-list">
        ${recProducts.map(p => `
          <div class="routine-product-card">
            <a href="${p.id}.html">
              <img src="${p.image}" alt="${p.title}" class="routine-prod-img" onerror="this.onerror=null; this.src='assets/anti_pigmentation.jpg';">
            </a>
            <div class="routine-prod-info">
              <h5><a href="${p.id}.html" style="color:inherit; text-decoration:none;">${p.title}</a></h5>
              <span>₹${p.price.toFixed(2)}</span>
              <a href="${p.id}.html" style="display:block; font-size:0.75rem; color:var(--color-accent); font-weight:600; text-decoration:none; margin-top:3px;">View Product Page &rarr;</a>
            </div>
          </div>
        `).join('')}
      </div>

      <div class="routine-actions">
        <button class="btn btn-primary" onclick="addCurrentBundleToCart()">
          Add Complete Prescription to Bag &bull; ₹${bundleTotal.toFixed(2)}
        </button>
        <button class="btn btn-secondary" onclick="restartQuiz()">
          Retake Diagnostic
        </button>
      </div>

      <div style="margin-top: 20px; padding-top: 16px; border-top: 1px dashed rgba(74, 53, 58, 0.15); display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;">
        <a href="#products" class="card-internal-link" style="margin-top:0;">Browse Full Catalog &rarr;</a>
        <a href="#curation" class="card-internal-link" style="margin-top:0;">How We Prepare As You Book &rarr;</a>
        <a href="waitlist.html" class="card-internal-link" style="margin-top:0;">Check Batch Waitlist &rarr;</a>
      </div>
    </div>
  `;
}

function addCurrentBundleToCart() {
  if (currentRecommendedBundle && currentRecommendedBundle.length > 0) {
    addBundleToCart(currentRecommendedBundle);
  }
}

function addBundleToCart(ids) {
  ids.forEach(id => {
    const product = PRODUCTS.find(p => p.id === id);
    if (product) {
      const existing = cart.find(i => i.id === id);
      if (existing) {
        existing.quantity += 1;
      } else {
        cart.push({
          id: product.id,
          title: product.title,
          price: product.price,
          weight: product.weight,
          image: product.image,
          quantity: 1
        });
      }
    }
  });
  saveCart();
  updateCartBadge();
  renderCartDrawer();
  openCart();
  showToast('Prescription regimen added to your bag!');
}

function restartQuiz() {
  quizAnswers = { 1: null, 2: null, 3: null };
  renderQuizStep(1);
}

// ==========================================================================
// Interactive Live Search Modal Engine
// ==========================================================================
function setupLiveSearch() {
  const input = document.getElementById('liveSearchInput');
  const clearBtn = document.getElementById('clearSearchBtn');
  const closeBtn = document.getElementById('closeSearchBtn');
  const backdrop = document.getElementById('searchBackdrop');

  if (input) {
    input.addEventListener('input', (e) => {
      const query = e.target.value.trim();
      if (clearBtn) clearBtn.style.display = query.length > 0 ? 'block' : 'none';
      executeSearch(query);
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (input) {
        input.value = '';
        clearBtn.style.display = 'none';
        executeSearch('');
        input.focus();
      }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', closeSearchModal);
  if (backdrop) backdrop.addEventListener('click', closeSearchModal);
}

function openSearchModal() {
  const modal = document.getElementById('searchModal');
  const input = document.getElementById('liveSearchInput');
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (input) {
      setTimeout(() => input.focus(), 150);
      executeSearch(input.value.trim());
    }
  }
}

function closeSearchModal() {
  const modal = document.getElementById('searchModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

function quickTagSearch(term) {
  const input = document.getElementById('liveSearchInput');
  const clearBtn = document.getElementById('clearSearchBtn');
  if (input) {
    input.value = term;
    if (clearBtn) clearBtn.style.display = 'block';
    executeSearch(term);
  }
}

function executeSearch(query) {
  const container = document.getElementById('searchResultsContainer');
  if (!container) return;

  const q = query.toLowerCase();
  const results = q === '' 
    ? PRODUCTS.slice(0, 4) // Show 4 popular defaults
    : PRODUCTS.filter(p => 
        p.title.toLowerCase().includes(q) ||
        p.description.toLowerCase().includes(q) ||
        p.ingredients.toLowerCase().includes(q) ||
        p.concern.toLowerCase().includes(q) ||
        p.category.toLowerCase().includes(q)
      );

  if (results.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 40px 20px; color: var(--color-text-muted);">
        <p>No formulations matched "<strong>${query}</strong>".</p>
        <p style="font-size: 0.85rem; margin-top: 8px;">Try searching for active ingredients like <em>Niacinamide, Oat, Tea Tree, Wintergreen</em> or concerns like <em>Pigmentation, Rash</em>.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = `
    <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 6px;">
      ${q === '' ? 'Popular Formulations:' : `Found ${results.length} Formulation${results.length > 1 ? 's' : ''}:`}
    </div>
    ${results.map(product => `
      <div class="search-result-item">
        <a href="${product.id}.html" class="search-item-media" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 12px; flex: 1;">
          <img src="${product.image}" alt="${product.title}" class="search-item-thumb">
          <div class="search-item-details">
            <h4>${product.title}</h4>
            <p>${product.description}</p>
          </div>
        </a>
        <div class="search-item-price">₹${product.price.toFixed(2)}</div>
        <div class="search-item-actions">
          <button class="btn-search-add" onclick="closeSearchModal(); addToCart('${product.id}');">Add</button>
        </div>
      </div>
    `).join('')}
  `;
}

// ==========================================================================
// Interactive Checkout Modal System
// ==========================================================================
function setupCheckoutModal() {
  const closeBtn = document.getElementById('closeCheckoutBtn');
  const backdrop = document.getElementById('checkoutBackdrop');

  if (closeBtn) closeBtn.addEventListener('click', closeCheckoutModal);
  if (backdrop) backdrop.addEventListener('click', closeCheckoutModal);
}

function openCheckoutModal() {
  if (cart.length === 0) {
    showToast('Your bag is empty! Add products before checkout.');
    return;
  }
  closeCart();
  const modal = document.getElementById('checkoutModal');
  const content = document.getElementById('checkoutContent');
  if (!modal || !content) return;

  const baseSubtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  let discountAmount = 0;
  if (appliedPromo) {
    if (appliedPromo.type === 'percent') {
      discountAmount = (baseSubtotal * appliedPromo.value) / 100;
    } else if (appliedPromo.type === 'flat') {
      discountAmount = appliedPromo.value;
    }
  }
  const shippingFee = baseSubtotal >= 999 ? 0 : 70;
  const finalTotal = Math.max(0, baseSubtotal - discountAmount) + shippingFee;

  content.innerHTML = `
    <div class="checkout-header">
      <h2>Complete Your Botanical Order</h2>
      <p>Express dispatch within 24 hours across India & Worldwide</p>
    </div>

    <form id="checkoutForm" onsubmit="submitCheckoutOrder(event, ${finalTotal})">
      <div class="checkout-form-grid">
        <div class="form-group">
          <label for="cName">Full Name *</label>
          <input type="text" id="cName" required minlength="2" maxlength="100" placeholder="e.g. Aditi Sharma">
        </div>
        <div class="form-group">
          <label for="cPhone">Phone (for Tracking SMS) *</label>
          <input type="tel" id="cPhone" required pattern="[0-9+\s\-]{10,15}" maxlength="16" placeholder="+91 9876543210">
        </div>
        <div class="form-group full-span">
          <label for="cEmail">Email Address *</label>
          <input type="email" id="cEmail" required maxlength="150" placeholder="aditi@example.com">
        </div>
        <div class="form-group full-span">
          <label for="cAddress">Shipping Address *</label>
          <input type="text" id="cAddress" required minlength="5" maxlength="250" placeholder="Flat / Street / Apartment details">
        </div>
        <div class="form-group">
          <label for="cCity">City *</label>
          <input type="text" id="cCity" required minlength="2" maxlength="100" placeholder="e.g. Mumbai">
        </div>
        <div class="form-group">
          <label for="cPincode">PIN / Postal Code *</label>
          <input type="text" id="cPincode" required pattern="[0-9]{6}" maxlength="6" placeholder="400001">
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 12px;">
        <label>Select Payment Mode</label>
        <div class="payment-methods-grid">
          <div class="payment-option selected" onclick="selectPaymentMethod(this)">
            <span>UPI / QR</span>
            <small>GPay, PhonePe, Paytm</small>
          </div>
          <div class="payment-option" onclick="selectPaymentMethod(this)">
            <span>Debit / Credit</span>
            <small>Visa, Mastercard, RuPay</small>
          </div>
          <div class="payment-option" onclick="selectPaymentMethod(this)">
            <span>Cash on Delivery</span>
            <small>Pay on arrival</small>
          </div>
        </div>
      </div>

      <div class="checkout-summary-box">
        <div class="checkout-summary-row">
          <span>Items in Bag (${cart.reduce((s, i) => s + i.quantity, 0)}):</span>
          <span>₹${baseSubtotal.toFixed(2)}</span>
        </div>
        ${discountAmount > 0 ? `
          <div class="checkout-summary-row" style="color: var(--color-accent);">
            <span>Coupon Discount (${appliedPromo.code}):</span>
            <span>-₹${discountAmount.toFixed(2)}</span>
          </div>
        ` : ''}
        <div class="checkout-summary-row">
          <span>Shipping:</span>
          <span>${shippingFee === 0 ? '<strong style="color: var(--color-accent)">FREE</strong>' : `₹${shippingFee.toFixed(2)}`}</span>
        </div>
        <div class="checkout-summary-row bold">
          <span>Total Payable:</span>
          <span>₹${finalTotal.toFixed(2)}</span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;">
        Confirm & Place Order &bull; ₹${finalTotal.toFixed(2)}
      </button>
    </form>
  `;

  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function selectPaymentMethod(elem) {
  document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
  elem.classList.add('selected');
}

function submitCheckoutOrder(event, total) {
  event.preventDefault();

  const nameInput = document.getElementById('cName');
  const phoneInput = document.getElementById('cPhone');
  const emailInput = document.getElementById('cEmail');
  const addressInput = document.getElementById('cAddress');
  const cityInput = document.getElementById('cCity');
  const pincodeInput = document.getElementById('cPincode');

  const name = nameInput ? nameInput.value.trim() : '';
  const phone = phoneInput ? phoneInput.value.trim() : '';
  const email = emailInput ? emailInput.value.trim() : '';
  const address = addressInput ? addressInput.value.trim() : '';
  const city = cityInput ? cityInput.value.trim() : '';
  const pincode = pincodeInput ? pincodeInput.value.trim() : '';

  // Validate inputs defensively
  if (name.length < 2) {
    showToast('Please provide your full name (at least 2 characters).');
    if (nameInput) nameInput.focus();
    return;
  }

  const phoneDigits = phone.replace(/\D/g, '');
  if (phoneDigits.length < 10 || phoneDigits.length > 15) {
    showToast('Please provide a valid 10-digit mobile number for dispatch updates.');
    if (phoneInput) phoneInput.focus();
    return;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showToast('Please provide a valid email address for your order confirmation.');
    if (emailInput) emailInput.focus();
    return;
  }

  if (address.length < 5) {
    showToast('Please provide a complete shipping address (at least 5 characters).');
    if (addressInput) addressInput.focus();
    return;
  }

  if (!city || city.length < 2) {
    showToast('Please enter your delivery city name.');
    if (cityInput) cityInput.focus();
    return;
  }

  const pincodeRegex = /^[0-9]{6}$/;
  if (!pincodeRegex.test(pincode)) {
    showToast('Please enter a valid 6-digit postal PIN code.');
    if (pincodeInput) pincodeInput.focus();
    return;
  }

  if (!cart || cart.length === 0) {
    showToast('Your bag is empty! Add products before placing order.');
    closeCheckoutModal();
    return;
  }

  const orderId = 'BLU-' + Math.floor(100000 + Math.random() * 900000);

  const content = document.getElementById('checkoutContent');
  if (!content) return;

  // Clear cart
  cart = [];
  appliedPromo = null;
  sessionStorage.removeItem('bluoilz_promo');
  saveCart();
  updateCartBadge();

  content.innerHTML = `
    <div class="checkout-success-view">
      <div class="success-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </div>
      <h2 style="font-size: 2rem; color: var(--color-primary); margin-bottom: 8px;">Order Confirmed!</h2>
      <p style="font-size: 1.05rem; color: var(--color-text-main); margin-bottom: 16px;">
        Thank you, <strong>${name}</strong>. Your botanical skincare formulation is being freshly prepared for dispatch to <strong>${city}</strong>.
      </p>
      <div style="background: var(--color-sand); border: 1px solid var(--color-border); padding: 14px; border-radius: var(--radius-sm); margin-bottom: 24px; display: inline-block;">
        <span style="font-size: 0.85rem; color: var(--color-text-muted); display: block;">Order Reference ID:</span>
        <strong style="font-size: 1.2rem; color: var(--color-primary); letter-spacing: 0.05em;">#${orderId}</strong>
      </div>
      <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 24px;">
        A dispatch tracking link will be sent via SMS to <strong>${phone}</strong> and email to <strong>${email}</strong>.
      </p>
      <button class="btn btn-primary" onclick="closeCheckoutModal()">
        Return to Bluoilz Formulations
      </button>
    </div>
  `;
}

function closeCheckoutModal() {
  const modal = document.getElementById('checkoutModal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// Toast notification
function showToast(message) {
  let toast = document.getElementById('appToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'appToast';
    toast.className = 'toast-notification';
    document.body.appendChild(toast);
  }

  toast.innerHTML = `
    <div class="toast-content">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      <span>${message}</span>
    </div>
  `;
  toast.classList.add('visible');

  setTimeout(() => {
    toast.classList.remove('visible');
  }, 3200);
}

// Mobile navigation
function setupMobileNav() {
  const burger = document.getElementById('mobileNavToggle');
  const mobileMenu = document.getElementById('mobileNavMenu');
  const closeBtn = document.getElementById('closeMobileNav');
  const backdrop = document.getElementById('mobileNavBackdrop');

  const closeMenu = () => {
    if (mobileMenu) mobileMenu.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.style.overflow = '';
  };

  const openMenu = () => {
    if (mobileMenu) mobileMenu.classList.add('active');
    if (backdrop) backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  if (burger) burger.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (backdrop) backdrop.addEventListener('click', closeMenu);

  if (mobileMenu) {
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', closeMenu);
    });
  }
}

// Newsletter
function setupNewsletter() {
  const form = document.getElementById('newsletterForm');
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const input = form.querySelector('input[type="email"]');
      const val = input ? input.value.trim() : '';
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!val || !emailRegex.test(val)) {
        showToast('Please provide a valid email address to subscribe.');
        if (input) input.focus();
        return;
      }

      showToast('Thank you for subscribing to Skin Intelligence Journal!');
      input.value = '';
    });
  }
}

// Wishlist interaction with heartPop animation
const userWishlist = new Set();
function toggleWishlist(productId, btn) {
  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;

  const heartIcon = btn ? btn.querySelector('svg') : null;

  if (userWishlist.has(productId)) {
    userWishlist.delete(productId);
    if (btn) {
      btn.classList.remove('active');
      if (heartIcon) heartIcon.setAttribute('fill', 'none');
    }
    showToast(`Removed "${product.title.split('–')[0].trim()}" from wishlist`);
  } else {
    userWishlist.add(productId);
    if (btn) {
      btn.classList.add('active');
      if (heartIcon) heartIcon.setAttribute('fill', 'currentColor');
    }
    showToast(`Added "${product.title.split('–')[0].trim()}" to your wishlist! ✨`);
  }
}

// Back-to-Top Button Handler
function setupBackToTop() {
  const btn = document.getElementById('backToTopBtn');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 350) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// Header elevation on scroll
function setupHeaderScrollEffect() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });
}

// Scroll reveal animations for sections and cards
function setupScrollReveal() {
  const elementsToReveal = document.querySelectorAll(
    '.product-card, .ethos-card, .review-card, .journal-card, .stat-item, .badge-pill, .quiz-card, .reveal-on-scroll'
  );

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -40px 0px'
    });

    elementsToReveal.forEach((el, idx) => {
      el.classList.add('reveal-init');
      el.style.transitionDelay = `${(idx % 4) * 0.08}s`;
      observer.observe(el);
    });
  } else {
    elementsToReveal.forEach(el => el.classList.add('is-revealed'));
  }
}

// ==========================================================================
// In-Catalog Live Filter Search (#products)
// ==========================================================================
function handleCatalogFilter(query) {
  const term = query.toLowerCase().trim();
  const clearBtn = document.getElementById('catalogSearchClear');
  if (clearBtn) clearBtn.style.display = term ? 'block' : 'none';

  const container = document.getElementById('productsGrid');
  if (!container) return;

  const filtered = PRODUCTS.filter(p => {
    const matchesCategory = activeFilter === 'all' || p.concern === activeFilter;
    if (!term) return matchesCategory;
    const matchesSearch = p.title.toLowerCase().includes(term) ||
      p.description.toLowerCase().includes(term) ||
      p.ingredients.toLowerCase().includes(term) ||
      p.concern.toLowerCase().includes(term) ||
      p.keyBenefits.some(b => b.toLowerCase().includes(term));
    return matchesSearch && (activeFilter === 'all' || p.concern === activeFilter);
  });

  if (filtered.length === 0) {
    container.innerHTML = `
      <div style="grid-column: 1 / -1; text-align: center; padding: 48px 20px; background: #ffffff; border-radius: var(--radius-md); border: 1px dashed rgba(74, 53, 58, 0.2);">
        <p style="font-size: 1.1rem; color: var(--color-primary); font-weight: 600; margin-bottom: 6px;">No therapeutic formulations match "${query}"</p>
        <p style="font-size: 0.88rem; color: var(--color-text-muted); margin-bottom: 16px;">Try searching for symptoms like "fungal", "spots", "oatmeal", "migraine", or reset filters.</p>
        <button class="btn btn-secondary" onclick="clearCatalogFilter()" style="padding: 8px 20px; font-size: 0.82rem;">Clear Search</button>
      </div>
    `;
    return;
  }

  container.innerHTML = filtered.map(product => `
    <div class="product-card reveal-init" data-id="${product.id}">
      <div class="product-media" onclick="openQuickView('${product.id}')" role="button" tabindex="0" aria-label="Quick view ${product.title}">
        ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
        <button class="product-wishlist-btn" onclick="event.stopPropagation(); toggleWishlist('${product.id}', this)" aria-label="Save to wishlist" title="Save to wishlist">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
        </button>
        <img src="${product.image}" alt="${product.title}" loading="lazy" class="product-img" onerror="this.onerror=null; this.src='assets/anti_pigmentation.jpg';">
        <span class="quick-view-hint">Quick View</span>
      </div>
      <div class="product-content">
        <div class="product-meta-row">
          <span class="product-category-text">${product.categoryLabel}</span>
          <span class="product-weight-text">${product.weight}</span>
        </div>
        <div class="product-fresh-status">
          <span class="status-pulse-dot"></span>
          <span>Prepared Fresh As You Book</span>
        </div>
        <h3 class="product-title">
          <a href="${product.id}.html" style="color: inherit; text-decoration: none;">${product.title}</a>
        </h3>
        
        <div class="product-rating">
          <div class="stars" aria-label="${product.rating} stars">
            ${'★'.repeat(Math.floor(product.rating))}
            <span class="star-empty">${product.rating % 1 !== 0 ? '½' : ''}</span>
          </div>
          <span class="rating-text">${product.rating} <span class="rating-count">(${product.reviewCount})</span></span>
        </div>

        <div class="product-price-row">
          <div class="price-wrap">
            <span class="price-current">₹${product.price.toFixed(2)}</span>
            <span class="price-original">₹${product.originalPrice.toFixed(2)}</span>
          </div>
          <button class="btn-add-cart" onclick="addToCart('${product.id}')" aria-label="Add ${product.title} to bag">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span>Add to Bag</span>
          </button>
        </div>
        <div style="margin-top: 8px; text-align: center;">
          <a href="${product.id}.html" style="font-size: 0.78rem; color: var(--color-accent); font-weight: 600; text-decoration: none;">View Full Details & Benefits &rarr;</a>
        </div>
      </div>
    </div>
  `).join('');

  setupScrollReveal();
}

function clearCatalogFilter() {
  const input = document.getElementById('catalogSearchInput');
  if (input) input.value = '';
  const clearBtn = document.getElementById('catalogSearchClear');
  if (clearBtn) clearBtn.style.display = 'none';
  renderProducts();
}

// Global Keyboard Shortcut: '/' or 'Cmd/Ctrl + K' opens search
document.addEventListener('keydown', (e) => {
  if (
    (e.key === '/' || ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k')) &&
    !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)
  ) {
    e.preventDefault();
    openSearchModal();
  }
});

function toggleAssistantMenu() {
  const popover = document.getElementById('assistantMenuPopover');
  if (!popover) return;
  const isShown = popover.style.display === 'flex';
  popover.style.display = isShown ? 'none' : 'flex';
}

// Click outside to close floating assistant menu
document.addEventListener('click', (e) => {
  const wrap = document.getElementById('floatingAssistantWrap');
  const popover = document.getElementById('assistantMenuPopover');
  if (wrap && popover && popover.style.display === 'flex') {
    if (!wrap.contains(e.target)) {
      popover.style.display = 'none';
    }
  }
});

// ==========================================================================
// Botanical Concierge Chatbox Assistant
// ==========================================================================
function toggleConciergeChat() {
  const box = document.getElementById('conciergeChatbox');
  if (!box) return;
  const isVisible = box.style.display === 'flex';
  box.style.display = isVisible ? 'none' : 'flex';
  if (!isVisible) {
    const input = document.getElementById('conciergeInput');
    if (input) input.focus();
  }
}

const CONCIERGE_KNOWLEDGE = [
  {
    triggers: ['pigment', 'melasma', 'dark spot', 'blemish', 'tan', 'uneven', 'brighten', 'kojic', 'glow'],
    reply: "For uneven skin tone, stubborn melasma, or humidity-induced tanning, we formulate our <strong>Anti Pigmentation Cream</strong> fresh upon booking. It features Kojic Dipalmitate, Alpha Arbutin, and cold-pressed licorice root without barrier-stripping harsh chemicals.",
    productId: 'anti-pigmentation-cream'
  },
  {
    triggers: ['fungal', 'sweat', 'rash', 'itch', 'chaf', 'humid', 'monsoon', 'thigh', 'neem'],
    reply: "Tropical heat and perspiration often disturb dermal microflora. Our <strong>Anti Fungal Cream</strong> utilizes cold-extracted Organic Neem, Karanja Oil, and Tea Tree to rapidly neutralize sweat rashes and restore dermal defense without suffocating pores.",
    productId: 'anti-fungal-cream'
  },
  {
    triggers: ['allergy', 'sensitive', 'red', 'inflam', 'hive', 'histamine', 'react', 'oat', 'itchy'],
    reply: "If your barrier is flared, stinging, or reacting to cosmetics, our <strong>Anti Allergy SOS Cream</strong> provides immediate soothing. Compounded with ultra-pure colloidal oatmeal, Centella Asiatica, and plant squalane to rebuild fragile tissue.",
    productId: 'anti-allergy-cream'
  },
  {
    triggers: ['psoriasis', 'flake', 'plaque', 'dry', 'scaly', 'crack', 'rough', 'scaling', 'ashy'],
    reply: "For thick, stubborn, flaky plaques and persistent dryness, we prepare our <strong>Psoriasis Support Cream</strong> using ancient Wrightia Tinctoria distillation and cold-pressed borage seed oil to soften scales and deeply replenish dermal lipids.",
    productId: 'psoriasis-support-cream'
  },
  {
    triggers: ['migraine', 'headache', 'tension', 'stress', 'temple', 'forehead', 'sinus', 'head'],
    reply: "For throbbing headaches, temple tension, or sinus tightness, our <strong>Migraine Relief Roll-on Oil</strong> combines volatile active wintergreen, French lavender, and crystalline peppermint. Apply gently along temples and nape of neck for cooling relief in minutes.",
    productId: 'migraine-relief-oil'
  },
  {
    triggers: ['book', 'how', 'fresh', 'shelf', 'ship', 'delivery', 'compound', 'ancient', 'prepare'],
    reply: "<strong>We Prepare As You Book:</strong> Unlike mass-market cosmetics that sit in warehouses for up to 2 years with chemical preservatives, we compound each therapeutic formula small-batch when your booking is placed. Orders ship directly at peak biological vitality!",
    productId: null
  },
  {
    triggers: ['upcoming', 'new', 'more', '15', 'future'],
    reply: "We focus exclusively on the therapeutic category with ancient methods of curation. We are expanding to a curated collection of 15 targeted remedies, freshly compounded as you book.",
    productId: null
  }
];

function conciergeQuickAsk(topic) {
  const map = {
    'pigmentation': 'Tell me about treating dark spots and melasma',
    'fungal': 'I have sweat rash and humidity fungal irritation',
    'allergy': 'My skin is flared, reactive and allergic',
    'psoriasis': 'What do you recommend for psoriasis and scaly plaques?',
    'migraine': 'How does the migraine roll-on oil work?',
    'booking': 'Explain how the We Prepare As You Book system works'
  };
  const text = map[topic] || topic;
  processConciergeMessage(text);
}

function handleConciergeMessage(e) {
  e.preventDefault();
  const input = document.getElementById('conciergeInput');
  if (!input) return;
  const text = input.value.trim();
  if (!text) return;
  input.value = '';
  processConciergeMessage(text);
}

function processConciergeMessage(userText) {
  const body = document.getElementById('conciergeChatBody');
  if (!body) return;

  // Append user message
  const userMsgEl = document.createElement('div');
  userMsgEl.className = 'chat-message user-msg';
  userMsgEl.innerHTML = `<div class="msg-bubble">${escapeHtml(userText)}</div>`;
  body.appendChild(userMsgEl);

  // Match response
  const lower = userText.toLowerCase();
  let match = CONCIERGE_KNOWLEDGE.find(k => k.triggers.some(t => lower.includes(t)));

  // Fallback response
  if (!match) {
    match = {
      reply: "Thank you for sharing. For personalized Ayurvedic diagnosis and compounding guidance, explore our 5 active therapeutic remedies or take our 30-second Skin Diagnostic.",
      productId: 'anti-pigmentation-cream'
    };
  }

  // Show animated typing indicator
  const typingEl = document.createElement('div');
  typingEl.className = 'chat-message concierge-msg typing-indicator-msg';
  typingEl.innerHTML = `
    <div class="msg-bubble typing-bubble">
      <span class="typing-dot"></span>
      <span class="typing-dot"></span>
      <span class="typing-dot"></span>
    </div>
  `;
  body.appendChild(typingEl);
  body.scrollTop = body.scrollHeight;

  // Simulate alchemical compounding / typing delay
  setTimeout(() => {
    if (typingEl && typingEl.parentNode) {
      typingEl.remove();
    }

    const botMsgEl = document.createElement('div');
    botMsgEl.className = 'chat-message concierge-msg';

    let productCardHtml = '';
    if (match.productId) {
      const prod = PRODUCTS.find(p => p.id === match.productId);
      if (prod) {
        productCardHtml = `
          <div class="chat-product-card">
            <img src="${prod.image}" alt="${prod.title}" onerror="this.onerror=null; this.src='assets/anti_pigmentation.jpg';">
            <div class="chat-product-info">
              <h5>${prod.title}</h5>
              <div class="chat-product-price">₹${prod.price.toFixed(2)}</div>
            </div>
            <button type="button" class="chat-product-add-btn" onclick="addToCart('${prod.id}')">+ Add</button>
          </div>
        `;
      }
    }

    botMsgEl.innerHTML = `
      <div class="msg-bubble">
        ${match.reply}
        ${productCardHtml}
      </div>
    `;
    body.appendChild(botMsgEl);
    body.scrollTop = body.scrollHeight;
  }, 450);

  body.scrollTop = body.scrollHeight;
}

function escapeHtml(string) {
  const div = document.createElement('div');
  div.textContent = string;
  return div.innerHTML;
}

// ==========================================================================
// High-Performance Smooth Scroll Background Sequence (180 Botanical Frames)
// Optimized for 60fps Silkiness, Mobile Responsiveness & Low-End Devices
// ==========================================================================
function setupScrollSequenceBackground() {
  const canvas = document.getElementById('scrollSequenceCanvas');
  if (!canvas) return;

  // alpha: false tells the browser the canvas is fully opaque, bypassing compositor blending
  const ctx = canvas.getContext('2d', { alpha: false, desynchronized: true }) || canvas.getContext('2d');
  if (!ctx) return;

  const TOTAL_FRAMES = 180;
  const frames = new Array(TOTAL_FRAMES);
  let targetFrame = 0;
  let currentFrame = 0;
  let isTicking = false;
  let animationFrameId = null;

  // Frame URL constructor (WebP format with 96% compression reduction)
  const getFrameUrl = (idx) => {
    const padded = String(idx + 1).padStart(3, '0');
    return `assets/sequence/frame_${padded}.webp`;
  };

  // Device pixel ratio clamped to max 1.5 to protect mobile memory and avoid GPU fill-rate exhaustion
  const getDpr = () => Math.min(window.devicePixelRatio || 1, 1.5);

  function resizeCanvas() {
    const dpr = getDpr();
    const w = window.innerWidth;
    const h = window.innerHeight;
    canvas.width = Math.round(w * dpr);
    canvas.height = Math.round(h * dpr);
    renderCurrentFrame();
  }

  window.addEventListener('resize', resizeCanvas, { passive: true });
  resizeCanvas();

  // Cover aspect-ratio centering algorithm
  function drawCoverImage(img) {
    if (!img || !img.complete || img.naturalWidth === 0) return;

    const cw = canvas.width;
    const ch = canvas.height;
    const iw = img.naturalWidth;
    const ih = img.naturalHeight;

    const scale = Math.max(cw / iw, ch / ih);
    const nw = iw * scale;
    const nh = ih * scale;
    const cx = (cw - nw) / 2;
    const cy = (ch - nh) / 2;

    ctx.drawImage(img, cx, cy, nw, nh);
  }

  // Graceful Fallback: Find nearest loaded frame if current frame is still buffering
  function findNearestLoadedFrame(index) {
    if (frames[index] && frames[index].complete && frames[index].naturalWidth > 0) {
      return frames[index];
    }
    for (let offset = 1; offset < TOTAL_FRAMES; offset++) {
      const prev = index - offset;
      if (prev >= 0 && frames[prev] && frames[prev].complete && frames[prev].naturalWidth > 0) {
        return frames[prev];
      }
      const next = index + offset;
      if (next < TOTAL_FRAMES && frames[next] && frames[next].complete && frames[next].naturalWidth > 0) {
        return frames[next];
      }
    }
    return null;
  }

  let currentFrameFloat = 0;
  let lastTimestamp = performance.now();
  let scrollVelocity = 0;
  let lastScrollY = window.scrollY || 0;
  let lastScrollTime = performance.now();

  // Ambient speed: 20 frames per second = steady, hypnotic, continuous botanical flow
  const AMBIENT_FPS = 20;

  function renderCurrentFrame() {
    let idx = Math.floor(currentFrameFloat) % TOTAL_FRAMES;
    if (idx < 0) idx += TOTAL_FRAMES;
    const img = findNearestLoadedFrame(idx);
    if (img) {
      drawCoverImage(img);
    }
  }

  // Continuous 60fps Ambient & Scroll Momentum Loop (Always Moving)
  function animationLoop(timestamp) {
    const dt = Math.min((timestamp - lastTimestamp) / 1000, 0.1);
    lastTimestamp = timestamp;

    // Decay scroll boost smoothly with damping friction
    scrollVelocity *= 0.94;
    if (Math.abs(scrollVelocity) < 0.05) scrollVelocity = 0;

    // Total speed = continuous ambient forward motion + scroll velocity boost
    const effectiveSpeed = AMBIENT_FPS + scrollVelocity;

    // Advance frame continuously
    currentFrameFloat += effectiveSpeed * dt;

    // Seamless wrap-around for infinite continuous playback
    if (currentFrameFloat >= TOTAL_FRAMES) {
      currentFrameFloat %= TOTAL_FRAMES;
    } else if (currentFrameFloat < 0) {
      currentFrameFloat = (currentFrameFloat % TOTAL_FRAMES) + TOTAL_FRAMES;
    }

    renderCurrentFrame();
    requestAnimationFrame(animationLoop);
  }

  // Responsive scroll velocity listener
  function onScroll() {
    const currentScrollY = window.scrollY || window.pageYOffset || 0;
    const now = performance.now();
    const dt = Math.max(now - lastScrollTime, 8);
    const dy = currentScrollY - lastScrollY;

    // Add scroll velocity to boost animation forward or reverse gently
    const velocity = (dy / dt) * 16;
    scrollVelocity = Math.max(-50, Math.min(60, scrollVelocity + velocity * 0.45));

    lastScrollY = currentScrollY;
    lastScrollTime = now;
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  requestAnimationFrame(animationLoop);

  // 3-Stage Progressive Priority Loading:
  // Stage 1: Load frame 0 immediately and paint it
  const firstImg = new Image();
  firstImg.src = getFrameUrl(0);
  frames[0] = firstImg;
  firstImg.onload = () => {
    renderCurrentFrame();
    // Stage 2: Load keyframes across entire range (every 6th frame) for instant responsive scrubbing
    loadPriorityKeyframes();
  };

  function loadPriorityKeyframes() {
    const step = 6;
    for (let i = 0; i < TOTAL_FRAMES; i += step) {
      if (frames[i]) continue;
      const img = new Image();
      img.src = getFrameUrl(i);
      frames[i] = img;
      img.onload = () => {
        if (Math.abs(currentFrame - i) < step) {
          renderCurrentFrame();
        }
      };
    }

    // Stage 3: Background streaming buffer for intermediate frames
    setTimeout(loadRemainingFrames, 250);
  }

  function loadRemainingFrames() {
    let index = 1;
    function loadNextBatch() {
      const batchSize = 8;
      let loadedInBatch = 0;
      while (index < TOTAL_FRAMES && loadedInBatch < batchSize) {
        if (!frames[index]) {
          const img = new Image();
          img.src = getFrameUrl(index);
          frames[index] = img;
          img.onload = () => {
            if (Math.abs(currentFrame - index) <= 1) {
              renderCurrentFrame();
            }
          };
          loadedInBatch++;
        }
        index++;
      }
      if (index < TOTAL_FRAMES) {
        if (window.requestIdleCallback) {
          window.requestIdleCallback(loadNextBatch, { timeout: 800 });
        } else {
          setTimeout(loadNextBatch, 40);
        }
      }
    }
    loadNextBatch();
  }
}

// 3D Hero Tilt Effect
function setupHeroTiltEffect() {
  const wrapper = document.querySelector('.hero-img-wrapper');
  if (!wrapper) return;

  wrapper.addEventListener('mousemove', (e) => {
    const rect = wrapper.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    const centerX = rect.width / 2;
    const centerY = rect.height / 2;

    const percentX = (x - centerX) / centerX;
    const percentY = (y - centerY) / centerY;

    const maxTilt = 10;
    const tiltX = -(percentY * maxTilt);
    const tiltY = percentX * maxTilt;

    wrapper.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale3d(1.02, 1.02, 1.02)`;
  });

  wrapper.addEventListener('mouseleave', () => {
    wrapper.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
  });
}

