// Cart Management Script
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get product card and product ID
            const productCard = this.closest('.product-card');
            if (!productCard) {
                console.error('Product card not found');
                return;
            }
            
            const productId = productCard.getAttribute('data-product-id');
            if (!productId) {
                alert('Please refresh the page and try again.');
                return;
            }
            
            // Check if user is logged in
            if (!isUserLoggedIn()) {
                alert('Please login first to add items to cart');
                window.location.href = 'login.php';
                return;
            }
            
            // Show size selection modal
            showSizeModal(productId);
        });
    });
});

// Check if user is logged in by checking if cart icon exists
function isUserLoggedIn() {
    const cartIcon = document.querySelector('.icon-btn i.fa-shopping-cart');
    return cartIcon !== null;
}

// Show modal for size selection
function showSizeModal(productId) {
    // Get product details
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    const productName = productCard.querySelector('.product-name').textContent;
    const productPrice = productCard.querySelector('.product-price').textContent;
    
    // Create modal HTML
    const modalHTML = `
        <div class="cart-modal-overlay" id="cartModal">
            <div class="cart-modal">
                <button class="modal-close" onclick="closeSizeModal()">
                    <i class="fas fa-times"></i>
                </button>
                
                <div class="modal-content">
                    <h2>Add to Cart</h2>
                    
                    <div class="modal-product-info">
                        <h3>${productName}</h3>
                        <p>${productPrice}</p>
                    </div>
                    
                    <div class="modal-form">
                        <div class="form-group">
                            <label for="sizeSelect">Select Size:</label>
                            <select id="sizeSelect" class="size-select">
                                <option value="">-- Choose Size --</option>
                                <option value="XS">XS (Extra Small)</option>
                                <option value="S">S (Small)</option>
                                <option value="M">M (Medium)</option>
                                <option value="L">L (Large)</option>
                                <option value="XL">XL (Extra Large)</option>
                                <option value="XXL">XXL (2XL)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantityInput">Quantity:</label>
                            <div class="quantity-control">
                                <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantityInput" class="qty-input" value="1" min="1" max="10">
                                <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        
                        <button class="btn-confirm-add" onclick="confirmAddToCart(${productId})">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('cartModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Focus on size select
    setTimeout(() => {
        document.getElementById('sizeSelect').focus();
    }, 100);
}

// Close size modal
function closeSizeModal() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.remove();
    }
}

// Increase quantity
function increaseQuantity() {
    const input = document.getElementById('quantityInput');
    if (input) {
        const current = parseInt(input.value) || 1;
        if (current < 10) {
            input.value = current + 1;
        }
    }
}

// Decrease quantity
function decreaseQuantity() {
    const input = document.getElementById('quantityInput');
    if (input) {
        const current = parseInt(input.value) || 1;
        if (current > 1) {
            input.value = current - 1;
        }
    }
}

// Confirm and add product to cart
function confirmAddToCart(productId) {
    const sizeSelect = document.getElementById('sizeSelect');
    const quantityInput = document.getElementById('quantityInput');
    
    const size = sizeSelect.value;
    const quantity = parseInt(quantityInput.value) || 1;
    
    // Validation
    if (!size) {
        alert('Please select a size');
        return;
    }
    
    if (quantity <= 0) {
        alert('Please enter valid quantity');
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('size', size);
    formData.append('quantity', quantity);
    
    // Show loading state
    const button = document.querySelector('.btn-confirm-add');
    const originalText = button.textContent;
    button.textContent = 'Adding...';
    button.disabled = true;
    
    // Send to server
    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        button.textContent = originalText;
        button.disabled = false;
        
        if (data.success) {
            // Close modal
            closeSizeModal();
            
            // Update cart count
            updateCartCount(data.cart_count);
            
            // Show success message
            showNotification('Product added to cart!', 'success');
            
            // Optional: Redirect to cart after 2 seconds
            // setTimeout(() => {
            //     window.location.href = 'cart.php';
            // }, 2000);
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.textContent = originalText;
        button.disabled = false;
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Update cart count in header
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

// Show notification message
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.insertAdjacentElement('afterbegin', notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.remove();
        }
    }, 4000);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('cartModal');
    if (modal && event.target === modal) {
        closeSizeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSizeModal();
    }
});
