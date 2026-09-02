/**
 * Bluoilz Skincare Website Data & Interactive Engine
 */

const PRODUCTS = [
  {
    id: "anti-pigmentation-cream",
    title: "Anti Pigmentation Cream – For Uneven & Dull Skin",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "pigmentation",
    price: 599,
    originalPrice: 749,
    rating: 4.9,
    reviewCount: 128,
    badge: "Bestseller",
    weight: "50 gms",
    image: "https://images.unsplash.com/photo-1608248597359-299f187ec982?auto=format&fit=crop&w=800&q=80",
    description: "A clinically potent yet gentle botanical formula tailored for tropical and humidity-exposed skin. Reduces hyperpigmentation, stubborn sun spots, and uneven tone without irritating sensitive skin barriers.",
    keyBenefits: [
      "Fades stubborn blemishes & melasma patches",
      "Restores natural skin radiance & moisture balance",
      "Non-greasy, fast-absorbing breathable emulsion",
      "Free from hydroquinone, parabens & synthetic dyes"
    ],
    ingredients: "Kojic Dipalmitate, Alpha Arbutin, Licorice Root Extract, Niacinamide, Cold-Pressed Jojoba Oil, Aloe Vera Leaf Juice, Vitamin E."
  },
  {
    id: "anti-fungal-cream",
    title: "Anti Fungal Cream – For Tropical & Humid Conditions",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "fungal",
    price: 499,
    originalPrice: 620,
    rating: 4.8,
    reviewCount: 94,
    badge: "Climate Defense",
    weight: "50 gms",
    image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=800&q=80",
    description: "Engineered specifically to counter humidity-induced fungal irritation, sweat rashes, and chafing. Provides lasting antifungal botanical protection while cooling inflamed, itchy skin.",
    keyBenefits: [
      "Rapidly alleviates sweat rash, redness & chafing",
      "Reinforces dermal microflora in high-humidity zones",
      "Soothes intense itchiness and burning sensation",
      "100% breathable formulation suitable for active wear"
    ],
    ingredients: "Neem Seed Oil, Organic Tea Tree Leaf Extract, Karanja Oil, Turmeric Rhizome Extract, Zinc PCA, Beeswax, Calendula Infusion."
  },
  {
    id: "anti-allergy-cream",
    title: "Anti Allergy Cream – For Sensitive & Reactive Skin",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "sensitive",
    price: 399,
    originalPrice: 499,
    rating: 4.9,
    reviewCount: 156,
    badge: "Barrier Repair",
    weight: "50 gms",
    image: "https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=80",
    description: "An SOS therapeutic shield designed for hyper-reactive skin. Instantly calms allergic flares, contact dermatitis symptoms, redness, and environmental histamine triggers.",
    keyBenefits: [
      "Instant relief from allergic hives & redness",
      "Repairs compromised skin lipid barrier",
      "Hypoallergenic, dermatologist verified formula",
      "Steroid-free comfort for daily preventative use"
    ],
    ingredients: "Colloidal Oatmeal, Centella Asiatica (Gotu Kola), Chamomile Flower Extract, Shea Butter, Evening Primrose Oil, Squalane."
  },
  {
    id: "clear-skin-anti-acne-cream",
    title: "Clear Skin Anti-Acne Cream (50 gms)",
    category: "face-care",
    categoryLabel: "Face Care",
    concern: "acne",
    price: 399,
    originalPrice: 520,
    rating: 4.7,
    reviewCount: 210,
    badge: "Popular",
    weight: "50 gms",
    image: "https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=800&q=80",
    description: "Clears inflammatory acne, cystic bumps, and hormonal breakouts without stripping natural moisture. Balances sebum production under extreme tropical humidity.",
    keyBenefits: [
      "Unclogs pores & dissolves acne-causing bacteria",
      "Minimizes post-acne dark marks and scarring",
      "Controls excess T-zone shine for up to 10 hours",
      "Calms acute red breakouts overnight"
    ],
    ingredients: "Salicylic Acid (Botanical Wintergreen), Tea Tree Oil, Basil Leaf Extract, Niacinamide 4%, Rosemary Water, Willow Bark Extract."
  },
  {
    id: "green-tea-face-wash",
    title: "Green Tea Face Wash – For Oily & Combination Skin",
    category: "face-care",
    categoryLabel: "Face Care",
    concern: "oil-control",
    price: 399,
    originalPrice: 480,
    rating: 4.8,
    reviewCount: 175,
    badge: "Daily Essential",
    weight: "120 ml",
    image: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80",
    description: "A purifying, non-stripping gel cleanser packed with organic green tea polyphenols and cooling botanical extracts. Leaves skin refreshed, detoxified, and completely balanced.",
    keyBenefits: [
      "Removes excess sebum, dust, and waterproof grime",
      "Provides rich antioxidant protection against pollution",
      "Sulphate-free gentle foaming action",
      "Maintains optimal pH 5.5 skin mantle"
    ],
    ingredients: "Camellia Sinensis (Green Tea) Infusion, Aloe Barbadensis, Glycerin, Decyl Glucoside, Cucumber Extract, Spearmint Oil."
  },
  {
    id: "psoriasis-support-cream",
    title: "Psoriasis Support Cream – For Dry & Reactive Skin",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "psoriasis",
    price: 599,
    originalPrice: 750,
    rating: 4.9,
    reviewCount: 88,
    badge: "Intensive Relief",
    weight: "60 gms",
    image: "https://images.unsplash.com/photo-1556228852-80b6e5eeff06?auto=format&fit=crop&w=800&q=80",
    description: "Deeply restorative lipid-replenishing emollient that softens thick, scaly plaques and relieves deep dryness associated with psoriasis, eczema, and severe xerosis.",
    keyBenefits: [
      "Softens tough epidermal flakes & rough patches",
      "Sustained 24-hour barrier hydration shield",
      "Reduces scaling, cracking, and stinging sensations",
      "Rich in natural phytosterols and omega fatty acids"
    ],
    ingredients: "Mahonia Aquifolium Extract, Wrightia Tinctoria Leaf Oil, Shea Butter, Virgin Coconut Oil, Borage Seed Oil, Beeswax."
  },
  {
    id: "migraine-relief-oil",
    title: "Migraine & Headache Relief Roll-on Oil (10 ml)",
    category: "therapeutic",
    categoryLabel: "Therapeutic Care",
    concern: "stress-pain",
    price: 149,
    originalPrice: 199,
    rating: 4.9,
    reviewCount: 312,
    badge: "Pocket Healer",
    weight: "10 ml",
    image: "https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=800&q=80",
    description: "An aromatherapeutic fast-acting roll-on infused with pure therapeutic-grade wintergreen, peppermint, and lavender oils. Relieves sinus pressure, tension headaches, and midday fatigue in minutes.",
    keyBenefits: [
      "Instant cooling pressure release upon temple application",
      "Eases stress-induced neck tension & migraine throbbing",
      "Portable spill-proof roll-on applicator",
      "Pure botanical distillate without artificial solvents"
    ],
    ingredients: "Mentha Piperita (Peppermint) Oil, Gaultheria Procumbens (Wintergreen) Oil, Lavandula Angustifolia Oil, Eucalyptus Globulus, Sweet Almond Carrier Oil."
  },
  {
    id: "radiance-face-serum",
    title: "Botanical Youth Radiance Face Serum",
    category: "face-care",
    categoryLabel: "Face Care",
    concern: "anti-aging",
    price: 699,
    originalPrice: 899,
    rating: 4.8,
    reviewCount: 142,
    badge: "Elixir",
    weight: "30 ml",
    image: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80",
    description: "Ultra-lightweight micro-droplet elixir featuring concentrated bakuchiol and plant squalane. Plumps fine lines, boosts collagen, and restores velvety softness.",
    keyBenefits: [
      "Gentle natural alternative to retinol for sensitive skin",
      "Firms texture and enhances skin bounce",
      "Absorbs in seconds under humid climate conditions",
      "Shields dermal matrix from free-radical damage"
    ],
    ingredients: "Bakuchiol 2%, Olive Squalane, Rosehip Seed Oil, Hyaluronic Acid, Frankincense Oil, Sea Buckthorn Berry Extract."
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
      <div class="empty-state">
        <p>No formulations found under this specific concern.</p>
        <button class="btn btn-secondary" onclick="resetFilter()">View All Formulations</button>
      </div>
    `;
    return;
  }

  container.innerHTML = filtered.map(product => `
    <div class="product-card reveal-init" data-id="${product.id}">
      <div class="product-media">
        <span class="product-badge">${product.badge}</span>
        <button class="product-wishlist-btn" onclick="toggleWishlist('${product.id}', this)" aria-label="Save to wishlist" title="Save to wishlist">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
        </button>
        <img src="${product.image}" alt="${product.title}" loading="lazy" class="product-img">
        <button class="quick-view-btn" onclick="openQuickView('${product.id}')">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          Quick View
        </button>
      </div>
      <div class="product-content">
        <div class="product-category">${product.categoryLabel} &bull; ${product.weight}</div>
        <h3 class="product-title" onclick="openQuickView('${product.id}')">${product.title}</h3>
        
        <div class="product-rating">
          <div class="stars">
            ${'★'.repeat(Math.floor(product.rating))}
            <span class="star-empty">${product.rating % 1 !== 0 ? '½' : ''}</span>
          </div>
          <span class="rating-text">${product.rating} (${product.reviewCount})</span>
        </div>

        <div class="product-price-row">
          <div class="price-wrap">
            <span class="price-current">₹${product.price.toFixed(2)}</span>
            <span class="price-original">₹${product.originalPrice.toFixed(2)}</span>
          </div>
          <button class="btn-add-cart" onclick="addToCart('${product.id}')" aria-label="Add to cart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            Add
          </button>
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
        <img src="${product.image}" alt="${product.title}" class="modal-img">
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

  const existingItem = cart.find(item => item.id === productId);
  if (existingItem) {
    existingItem.quantity += quantity;
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
  showToast(`Added ${quantity}x "${product.title}" to cart`);
}

function updateCartItemQty(productId, delta) {
  const item = cart.find(i => i.id === productId);
  if (!item) return;

  item.quantity += delta;
  if (item.quantity <= 0) {
    cart = cart.filter(i => i.id !== productId);
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
        <h4>Your Cart is Empty</h4>
        <p>Explore our therapeutic skincare formulas to soothe and balance your skin.</p>
        <button class="btn btn-primary" onclick="closeCart()">Explore Formulations</button>
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
          <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=120&q=80" alt="Roll on">
          <div>
            <strong>Quick Add: Migraine Roll-On</strong>
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
      <img src="${item.image}" alt="${item.title}" class="cart-item-img">
      <div class="cart-item-info">
        <h5 class="cart-item-title">${item.title}</h5>
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
    desc: "Select the option that best reflects your barrier balance during daily exposure.",
    options: [
      { id: "oily-sweat", icon: "💧", label: "Oily & Sweat-Prone", detail: "Excess sebum shine, sweat rash, congested pores, and frequent humidity breakouts." },
      { id: "reactive-allergy", icon: "🌿", label: "Sensitive & Reactive", detail: "Flushes red easily, stings from chemicals, experiences allergic chafing or heat hives." },
      { id: "uneven-sun", icon: "☀️", label: "Pigmented & Sun-Damaged", detail: "Stubborn dark patches, melasma, uneven dermal tone, and dull post-sun appearance." },
      { id: "flaky-dry", icon: "❄️", label: "Dry, Scaly or Plaque", detail: "Flaking, persistent tight patches, barrier cracking, or mild psoriasis discomfort." }
    ]
  },
  {
    step: 2,
    title: "What is your primary clinical skin goal?",
    desc: "Pinpoint the primary restorative action you seek.",
    options: [
      { id: "clear-acne", icon: "✨", label: "Clear Active Breakouts", detail: "Antibacterial healing, sebum regulation, and soothing active cysts." },
      { id: "fade-spots", icon: "🌸", label: "Fade Pigmentation & Brighten", detail: "Even skin tone, restore botanical luminosity, and soften hyperpigmentation." },
      { id: "calm-flare", icon: "🛡️", label: "Soothe Itch, Allergy & Rash", detail: "Reinforce acid mantle, ease fungal discomfort, and cool histamine flare-ups." },
      { id: "headache-calm", icon: "💆", label: "Soothe Work & Midday Stress", detail: "Relieve tension headaches, heavy forehead pressure, and fatigue." }
    ]
  },
  {
    step: 3,
    title: "What is your preferred daily routine commitment?",
    desc: "How comprehensive would you like your therapeutic routine to be?",
    options: [
      { id: "targeted-sos", icon: "⚡", label: "Targeted SOS Solution", detail: "A singular, potent active formula for immediate relief." },
      { id: "complete-barrier", icon: "🌿", label: "Complete 2-Step Synergy", detail: "Cleanse/prepare and clinically treat for lasting dermal harmony." }
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

  // Determine prescribed products based on quizAnswers
  let recommendedIds = [];
  let title = "Tropical Dermal Balance Regimen";
  let description = "Designed specifically to counter humid weather disruption, soothe barrier microflora, and deliver bioavailable botanical repair.";

  if (quizAnswers[2] === 'clear-acne' || quizAnswers[1] === 'oily-sweat') {
    title = "Purifying & Anti-Acne Clarity Prescription";
    description = "Non-stripping organic green tea polyphenols combined with wintergreen salicylic actives clear clogged sebum without drying your barrier.";
    recommendedIds = quizAnswers[3] === 'targeted-sos' 
      ? ['clear-skin-anti-acne-cream'] 
      : ['green-tea-face-wash', 'clear-skin-anti-acne-cream'];
  } else if (quizAnswers[2] === 'fade-spots' || quizAnswers[1] === 'uneven-sun') {
    title = "Radiant Melanin-Balance Prescription";
    description = "Fades stubborn UV damage and hyperpigmentation with Kojic Dipalmitate and Alpha Arbutin in a lightweight breathable base.";
    recommendedIds = quizAnswers[3] === 'targeted-sos'
      ? ['anti-pigmentation-cream']
      : ['anti-pigmentation-cream', 'radiance-face-serum'];
  } else if (quizAnswers[2] === 'headache-calm') {
    title = "Neuro-Aromatic Tension Relief";
    description = "Rapid temple relief using pure wintergreen, cooling menthol, and lavender distillates to dissolve headache pressure in minutes.";
    recommendedIds = ['migraine-relief-oil'];
  } else if (quizAnswers[1] === 'flaky-dry') {
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
            <img src="${p.image}" alt="${p.title}" class="routine-prod-img">
            <div class="routine-prod-info">
              <h5>${p.title}</h5>
              <span>₹${p.price.toFixed(2)}</span>
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
        <div class="search-item-media" onclick="closeSearchModal(); openQuickView('${product.id}');" style="cursor: pointer;">
          <img src="${product.image}" alt="${product.title}" class="search-item-thumb">
          <div class="search-item-details">
            <h4>${product.title}</h4>
            <p>${product.description}</p>
          </div>
        </div>
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
          <input type="text" id="cName" required placeholder="e.g. Aditi Sharma">
        </div>
        <div class="form-group">
          <label for="cPhone">Phone (for Tracking SMS) *</label>
          <input type="tel" id="cPhone" required placeholder="+91 9876543210">
        </div>
        <div class="form-group full-span">
          <label for="cEmail">Email Address *</label>
          <input type="email" id="cEmail" required placeholder="aditi@example.com">
        </div>
        <div class="form-group full-span">
          <label for="cAddress">Shipping Address *</label>
          <input type="text" id="cAddress" required placeholder="Flat / Street / Apartment details">
        </div>
        <div class="form-group">
          <label for="cCity">City *</label>
          <input type="text" id="cCity" required placeholder="e.g. Mumbai">
        </div>
        <div class="form-group">
          <label for="cPincode">PIN / Postal Code *</label>
          <input type="text" id="cPincode" required placeholder="400001">
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
  const name = document.getElementById('cName').value;
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
        Thank you, <strong>${name}</strong>. Your botanical skincare formulation is being freshly prepared for dispatch.
      </p>
      <div style="background: var(--color-sand); border: 1px solid var(--color-border); padding: 14px; border-radius: var(--radius-sm); margin-bottom: 24px; display: inline-block;">
        <span style="font-size: 0.85rem; color: var(--color-text-muted); display: block;">Order Reference ID:</span>
        <strong style="font-size: 1.2rem; color: var(--color-primary); letter-spacing: 0.05em;">#${orderId}</strong>
      </div>
      <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 24px;">
        A dispatch tracking link will be sent to your mobile number within 24 hours.
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
      if (input && input.value) {
        showToast('Thank you for subscribing to Skin Intelligence Journal!');
        input.value = '';
      }
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
    '.product-card, .ethos-card, .review-card, .journal-card, .stat-item, .badge-pill, .quiz-card'
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

