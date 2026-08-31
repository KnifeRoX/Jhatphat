// ─── NAVBAR SCROLL ───
const navbar = document.querySelector('.navbar');
if (navbar) {
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 50);
  });
}

// ─── SCROLL ANIMATIONS ───
const fadeEls = document.querySelectorAll('.fade-up');
const observer = new IntersectionObserver((entries) => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      setTimeout(() => e.target.classList.add('visible'), i * 80);
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.1 });
fadeEls.forEach(el => observer.observe(el));

// ─── CART BADGE UPDATE ───
async function updateCartBadge() {
  try {
    const res = await fetch('php/cart_action.php?action=ajax_cart_count');
    const data = await res.json();
    document.querySelectorAll('.cart-badge').forEach(b => {
      b.textContent = data.count;
      b.style.display = data.count > 0 ? 'flex' : 'none';
    });
  } catch(e) {}
}

// ─── QTY CONTROLS (menu page inline) ───
document.querySelectorAll('.qty-control').forEach(ctrl => {
  const minus = ctrl.querySelector('.qty-minus');
  const plus = ctrl.querySelector('.qty-plus');
  const num = ctrl.querySelector('.qty-num');
  const hiddenInput = ctrl.closest('.qty-form')?.querySelector('input[name="qty"]');
  let qty = parseInt(num?.textContent || 1);

  minus?.addEventListener('click', () => {
    if (qty > 1) { qty--; num.textContent = qty; if(hiddenInput) hiddenInput.value = qty; }
  });
  plus?.addEventListener('click', () => {
    qty++; num.textContent = qty; if(hiddenInput) hiddenInput.value = qty;
  });
});

// ─── CART PAGE QTY ───
document.querySelectorAll('.cart-qty-control').forEach(ctrl => {
  const minus = ctrl.querySelector('.qty-minus');
  const plus  = ctrl.querySelector('.qty-plus');
  const num   = ctrl.querySelector('.qty-num');
  const form  = ctrl.closest('form');
  let qty = parseInt(num?.textContent || 1);

  minus?.addEventListener('click', () => {
    qty = Math.max(1, qty - 1);
    num.textContent = qty;
    form?.querySelector('input[name="qty"]') && (form.querySelector('input[name="qty"]').value = qty);
    updateCartItem(form);
  });
  plus?.addEventListener('click', () => {
    qty++;
    num.textContent = qty;
    form?.querySelector('input[name="qty"]') && (form.querySelector('input[name="qty"]').value = qty);
    updateCartItem(form);
  });
});

async function updateCartItem(form) {
  if (!form) return;
  const fd = new FormData(form);
  fd.set('action', 'update');
  try {
    await fetch('php/cart_action.php', { method: 'POST', body: fd });
    location.reload();
  } catch(e) {}
}

// ─── PAYMENT METHOD TOGGLE ───
const paymentMethods = document.querySelectorAll('.payment-method');
paymentMethods.forEach(m => {
  m.addEventListener('click', () => {
    paymentMethods.forEach(x => x.classList.remove('active'));
    m.classList.add('active');
    const method = m.dataset.method;
    document.querySelectorAll('.payment-detail').forEach(d => d.style.display = 'none');
    const target = document.getElementById('detail-' + method);
    if (target) target.style.display = 'block';
    const radio = m.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
  });
});

// ─── CARD NUMBER FORMAT ───
const cardInput = document.getElementById('card-number');
if (cardInput) {
  cardInput.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g,'').substring(0,16);
    e.target.value = v.match(/.{1,4}/g)?.join(' ') || v;
  });
}
const cardExpiry = document.getElementById('card-expiry');
if (cardExpiry) {
  cardExpiry.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g,'').substring(0,4);
    if (v.length >= 2) v = v.substring(0,2) + '/' + v.substring(2);
    e.target.value = v;
  });
}

// ─── SUCCESS MODAL ───
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('success') === '1') {
  const modal = document.getElementById('success-modal');
  if (modal) {
    modal.classList.add('show');
    const orderId = urlParams.get('order');
    const orderIdEl = document.getElementById('order-id-display');
    if (orderIdEl && orderId) orderIdEl.textContent = orderId;
    launchConfetti();
  }
}

function closeModal(id) {
  document.getElementById(id)?.classList.remove('show');
}

// ─── CONFETTI ───
function launchConfetti() {
  const canvas = document.getElementById('confetti-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  const pieces = Array.from({length: 120}, () => ({
    x: Math.random() * canvas.width,
    y: Math.random() * -canvas.height,
    r: 4 + Math.random() * 8,
    d: 2 + Math.random() * 4,
    color: ['#E8431A','#FFD700','#FF6B35','#22c55e','#60a5fa'][Math.floor(Math.random()*5)],
    vx: (Math.random()-0.5)*2,
  }));
  let frame;
  function draw() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    pieces.forEach(p => {
      ctx.fillStyle = p.color;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
      ctx.fill();
      p.y += p.d; p.x += p.vx;
      if (p.y > canvas.height) { p.y = -10; p.x = Math.random()*canvas.width; }
    });
    frame = requestAnimationFrame(draw);
  }
  draw();
  setTimeout(() => { cancelAnimationFrame(frame); ctx.clearRect(0,0,canvas.width,canvas.height); }, 4000);
}

// ─── MENU SEARCH FILTER ───
const searchInput = document.getElementById('menu-search');
if (searchInput) {
  searchInput.addEventListener('input', filterMenu);
}
document.querySelectorAll('.filter-check').forEach(cb => {
  cb.addEventListener('change', filterMenu);
});

function filterMenu() {
  const q = (searchInput?.value || '').toLowerCase();
  const activeCats = [...document.querySelectorAll('.cat-filter:checked')].map(c => c.value);
  const vegOnly = document.getElementById('filter-veg')?.checked;
  const nonVegOnly = document.getElementById('filter-nonveg')?.checked;

  document.querySelectorAll('.menu-card-wrap').forEach(card => {
    const name = card.dataset.name?.toLowerCase() || '';
    const cat  = card.dataset.cat || '';
    const veg  = card.dataset.veg === '1';

    const matchQ   = !q || name.includes(q);
    const matchCat = activeCats.length === 0 || activeCats.includes(cat);
    const matchVeg = (!vegOnly && !nonVegOnly) || (vegOnly && veg) || (nonVegOnly && !veg);

    card.style.display = (matchQ && matchCat && matchVeg) ? 'block' : 'none';
  });
}

// ─── MINI CART TOGGLE ───
const miniCartToggle = document.getElementById('mini-cart-toggle');
const miniCart = document.getElementById('mini-cart');
if (miniCartToggle && miniCart) {
  miniCartToggle.addEventListener('click', () => miniCart.classList.toggle('open'));
}

// ─── LOGIN TABS ───
document.querySelectorAll('.auth-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset.tab;
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.auth-panel').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById('panel-' + target)?.classList.add('active');
    history.replaceState(null, '', '?tab=' + target);
  });
});

// Activate tab from URL
const tabParam = new URLSearchParams(window.location.search).get('tab');
if (tabParam) {
  document.querySelector(`.auth-tab[data-tab="${tabParam}"]`)?.click();
}

// ─── HERO PARTICLES ───
function initParticles() {
  const canvas = document.getElementById('particles-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
  resize();
  window.addEventListener('resize', resize);
  const dots = Array.from({length: 60}, () => ({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    r: Math.random() * 2 + 0.5,
    vx: (Math.random()-.5)*.4,
    vy: (Math.random()-.5)*.4,
    alpha: Math.random()*.5+.2,
  }));
  function draw() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    dots.forEach(d => {
      ctx.beginPath();
      ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
      ctx.fillStyle = `rgba(232,107,53,${d.alpha})`;
      ctx.fill();
      d.x += d.vx; d.y += d.vy;
      if(d.x<0) d.x=canvas.width;
      if(d.x>canvas.width) d.x=0;
      if(d.y<0) d.y=canvas.height;
      if(d.y>canvas.height) d.y=0;
    });
    requestAnimationFrame(draw);
  }
  draw();
}
initParticles();
