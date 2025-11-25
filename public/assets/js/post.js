// Helper function to escape HTML for dynamic content
function htmlspecialchars(str) {
  if (typeof str !== 'string') return str;
  const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
  };
  return str.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Custom Alert function (defined globally for use in PHP-triggered scripts)
function showCustomAlert(message, callback = null) {
  const alertBox = document.getElementById('customAlertDialog');
  if (!alertBox) { // Fallback if custom alert not found
      alert(message);
      if (callback) callback();
      return;
  }
  document.getElementById('customAlertMessage').textContent = message;
  alertBox.style.display = 'flex';

  const okButton = alertBox.querySelector('button');
  const newOkButton = okButton.cloneNode(true);
  okButton.parentNode.replaceChild(newOkButton, okButton);

  newOkButton.addEventListener('click', function() {
      alertBox.style.display = 'none';
      if (callback && typeof callback === 'function') {
          callback();
      }
  });
}

// Modal functions
function openModal() {
  document.getElementById('productModal').style.display = 'flex';
  document.getElementById('productSearchInput').value = '';
  filterProducts();
}

function closeModal() {
  document.getElementById('productModal').style.display = 'none';
}

// Close modal if click outside content
window.onclick = function(event) {
  const modal = document.getElementById('productModal');
  if (event.target === modal) {
      closeModal();
  }
}

// Product search filter
function filterProducts() {
  const input = document.getElementById('productSearchInput');
  const filter = input.value.toLowerCase();
  const productGrid = document.querySelector('#productModal .product-grid-modal');
  const productItems = productGrid.getElementsByClassName('product-item-modal');

  for (let i = 0; i < productItems.length; i++) {
      const span = productItems[i].getElementsByClassName('product-name-modal')[0];
      if (span) {
          if (span.textContent.toLowerCase().indexOf(filter) > -1) {
              productItems[i].style.display = "";
          } else {
              productItems[i].style.display = "none";
          }
      }
  }
}

const addedProductIds = new Set();

function addProductToPost(id, name) {
  if (addedProductIds.has(id)) {
      showCustomAlert('Produk ini sudah ditambahkan!');
      return;
  }

  const container = document.getElementById('selectedProductsDisplay');
  const entry = document.createElement('div');
  entry.className = 'selected-product-entry item';
  entry.dataset.id = id;

  entry.innerHTML = `
      <span>${htmlspecialchars(name)}</span>
      <button type="button" class="remove-selected-product-btn delete-btn">&#8722;</button>
      <input type="hidden" name="selected_product_ids[]" value="${htmlspecialchars(id)}">
      `;
  container.appendChild(entry);
  addedProductIds.add(id);

  entry.querySelector('.remove-selected-product-btn').addEventListener('click', function() {
      entry.remove();
      addedProductIds.delete(id);
  });
}

document.addEventListener('DOMContentLoaded', function () {
  // ---- EVENT LISTENERS LAINNYA (Terkait "Add New Product" dan "Mood Buttons" yang saya komentari di HTML sebelumnya) ----
  // Pastikan elemen dengan ID 'productContainer' dan 'addBtn' ada di HTML jika bagian ini ingin berfungsi.
  const productContainer = document.getElementById('productContainer');
  const addBtn = document.getElementById('addBtn');

  function createProductField() {
      const productDiv = document.createElement('div');
      productDiv.className = 'product-entry item';
      productDiv.innerHTML = `
          <div class="item-links">
              <input type="text" placeholder="Product Name" class="item-input" name="new_produk_name[]" required>
              <button type="button" class="removeBtn delete-btn">&#8722;</button>
          </div>
          <div class="item-links">
              <details>
                  <summary>Input Links</summary>
                  <div>
                      <input type="text" placeholder="Shopee" class="item-input" name="new_produk_shopee[]">
                      <input type="text" placeholder="Tokopedia" class="item-input" name="new_produk_tokopedia[]">
                      <input type="text" placeholder="Satria Bandung Jaya" class="item-input" name="new_produk_sbj[]">
                  </div>
              </details>
          </div>
          <div class="item-links">
              <input type="text" placeholder="Put the product link here..." class="item-input" name="new_produk_link[]">
          </div>
      `;
      productDiv.querySelector('.removeBtn').addEventListener('click', function () {
          productDiv.remove();
      });
      return productDiv;
  }

  if (addBtn) { // Hanya tambahkan event listener jika tombol 'addBtn' ada
      addBtn.addEventListener('click', function () {
          const newProduct = createProductField();
          if (productContainer) {
              productContainer.appendChild(newProduct);
          }
      });
  }

  if (productContainer) { // Hanya tambahkan event listener jika productContainer ada
      productContainer.querySelectorAll('.removeBtn').forEach(function (btn) {
          btn.addEventListener('click', function () {
              btn.closest('.product-entry').remove();
          });
      });
  }

  // ==== FUNGSI PILIH MOOD (jika Afa menggunakan tombol mood dan input hidden) ====
  const moodButtons = document.querySelectorAll('.mood-option');
  if (moodButtons.length > 0) {
      moodButtons.forEach(button => {
          button.addEventListener('click', function () {
              moodButtons.forEach(btn => btn.classList.remove('active'));
              this.classList.add('active');
              const selectedMoodInput = document.getElementById('selectedMoodInput');
              if (selectedMoodInput) {
                  selectedMoodInput.value = this.dataset.mood;
              }
          });
      });
  }

  // Event listener untuk search input modal produk
  document.getElementById('productSearchInput').addEventListener('keyup', filterProducts);

  // Event listener untuk tombol buka modal produk
  document.getElementById('openProductModalBtn').addEventListener('click', openModal);

  // Event listener untuk klik pada produk di dalam modal (untuk menambahkan ke post)
  document.querySelector('#productModal .product-grid-modal').addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('add-to-post-btn')) {
          const productItem = e.target.closest('.product-item-modal');
          const productId = productItem.dataset.id;
          const productName = productItem.dataset.name;
          addProductToPost(productId, productName);
          closeModal();
      }
  });

  // Bagian ini (untuk tab switching) kemungkinan tidak diperlukan di halaman post.php
  // jika Afa tidak memiliki fungsi tab di halaman ini, baris ini bisa dihapus atau dikomentari.
  const defaultTab = document.querySelector('.tabs .tab.active');
  if (defaultTab) {
      // showTab('myStyle', { currentTarget: defaultTab }); 
  } else {
      // showTab('myStyle', { currentTarget: document.querySelector('.tabs .tab') });
  }
});