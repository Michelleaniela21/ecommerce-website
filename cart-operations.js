// Cart Operations Script

// Update cart item quantity
function updateQuantity(cartId, newQuantity) {
    // Validate quantity
    if (newQuantity < 1) {
        showNotification('Quantity must be at least 1', 'error');
        return;
    }
    
    if (newQuantity > 10) {
        showNotification('Maximum quantity is 10 items', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQuantity);
    
    // Send request to update cart
    fetch('update_cart_quantity.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity display
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const qtyDisplay = cartItem.querySelector('.qty-display');
            qtyDisplay.textContent = newQuantity;
            
            // Update cart totals
            updateCartDisplay(data);
            showNotification('Quantity updated', 'success');
        } else {
            showNotification(data.message || 'Failed to update quantity', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Remove item from cart
function removeFromCart(cartId) {
    if (!confirm('Are you sure you want to remove this item from cart?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    
    // Send request to delete from cart
    fetch('delete_from_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove cart item from DOM with animation
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            cartItem.style.animation = 'slideOut 0.3s ease-out forwards';
            
            setTimeout(() => {
                cartItem.remove();
                
                // Check if cart is now empty
                if (data.is_empty) {
                    // Reload page to show empty cart message
                    window.location.reload();
                } else {
                    // Update cart totals
                    updateCartDisplay(data);
                    showNotification('Item removed from cart', 'success');
                }
            }, 300);
        } else {
            showNotification(data.message || 'Failed to remove item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Update cart display (totals, counts, etc)
function updateCartDisplay(data) {
    // Update cart count in all headers
    const cartCounts = document.querySelectorAll('.cart-count');
    cartCounts.forEach(count => {
        count.textContent = data.total_items;
    });
    
    // Update order summary
    const summaryRows = document.querySelectorAll('.summary-row');
    if (summaryRows.length >= 2) {
        // Update Subtotal
        summaryRows[0].querySelector('span:last-child').textContent = 
            'Rp ' + formatNumber(data.subtotal);
        
        // Update Shipping
        summaryRows[1].querySelector('span:last-child').textContent = 
            'Rp ' + formatNumber(data.shipping);
        
        // Update Total (in the third summary row with border-top)
        const totalRow = Array.from(summaryRows).find(row => 
            row.style.borderTop && row.style.borderTop !== ''
        );
        if (totalRow) {
            totalRow.querySelector('span:last-child').textContent = 
                'Rp ' + formatNumber(data.total);
        }
    }
}

// Format number to Indonesian format (Rp)
function formatNumber(num) {
    return Number(num).toLocaleString('id-ID');
}

// Show notification
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

// Add CSS animation for removing cart items
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(style);
