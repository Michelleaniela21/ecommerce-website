<?php
session_start();
require_once 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$shipping_cost = 25000;

// Ambil data cart dari database
$stmt = $conn->prepare("
    SELECT c.cart_id, c.product_id, c.quantity, c.size, p.product_name, p.price, p.image_url
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
    ORDER BY c.cart_id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Hitung total
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + ($subtotal > 0 ? $shipping_cost : 0);
$total_items = array_sum(array_column($cart_items, 'quantity'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ReCloth</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cart-modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">ReCloth</a>
            
            <ul class="nav-links">
                <li><a href="men.php">Men</a></li>
                <li><a href="women.php">Women</a></li>
                <li><a href="kids.php">Kids</a></li>
                <li><a href="chart.php">Chart</a></li>
            </ul>
            
            <div class="nav-icons">
                <a href="cart.php" class="icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $total_items; ?></span>
                </a>
                <a href="profile.php" class="icon-btn"><i class="fas fa-user"></i></a>
                <a href="logout.php" class="icon-btn"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </nav>
    </header>

    <!-- CART SECTION -->
    <div class="cart-container">
        <h2 class="section-title">Shopping Cart</h2>
        
        <?php if (empty($cart_items)): ?>
        <!-- Empty Cart Message -->
        <div class="empty-cart">
            <div class="empty-cart-content">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your Cart is Empty</h3>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="men.php" class="btn-primary" style="display: inline-block; margin-top: 1.5rem;">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item" data-cart-id="<?php echo $item['cart_id']; ?>">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="cart-item-image">
                    <div class="cart-item-info">
                        <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                        <p style="color: var(--dark-blue); font-weight: 600;">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                        <p style="color: gray;">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
                            <button class="qty-btn-minus" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                            <span class="qty-display" style="font-weight: 600; min-width: 30px; text-align: center;"><?php echo $item['quantity']; ?></span>
                            <button class="qty-btn-plus" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                            <button class="btn-remove" onclick="removeFromCart(<?php echo $item['cart_id']; ?>)">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3 style="margin-bottom: 1.5rem; color: var(--dark-blue);">Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span style="font-weight: 600;">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span style="font-weight: 600;">Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></span>
                </div>
                
                <div class="summary-row" style="padding-top: 1rem; border-top: 2px solid #eee; margin-top: 1rem;">
                    <span style="font-size: 1.2rem; font-weight: 600;">Total</span>
                    <span style="font-size: 1.3rem; font-weight: 700; color: var(--dark-blue);">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
                
                <button class="btn-checkout">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </button>
                
                <a href="men.php" style="display: block; text-align: center; margin-top: 1rem; color: var(--dark-blue); text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About ReCloth</h3>
                <p>Your destination for premium fashion. We believe style should be accessible to everyone.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="men.php">Men</a></li>
                    <li><a href="women.php">Women</a></li>
                    <li><a href="kids.php">Kids</a></li>
                    <li><a href="chart.php">Size Chart</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul class="footer-links">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Shipping Info</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div style="display: flex; gap: 1rem; font-size: 1.5rem;">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #333;">
            <p>&copy; 2024 ReCloth. All rights reserved.</p>
        </div>
    </footer>

    <script src="cart-operations.js"></script>
</body>
</html>