<?php
session_start();
require_once 'config.php';

// Fetch products from database
$sql = "SELECT * FROM products WHERE category_id = 2 AND is_active = 1 ORDER BY product_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Collection - ReCloth</title>
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
                <li><a href="women.php" class="active">Women</a></li>
                <li><a href="kids.php">Kids</a></li>
                <li><a href="chart.php">Chart</a></li>
            </ul>
            
            <div class="nav-icons">
                <?php if(is_logged_in()): ?>
                    <a href="cart.php" class="icon-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo get_cart_count(); ?></span>
                    </a>
                    <a href="profile.php" class="icon-btn"><i class="fas fa-user"></i></a>
                    <a href="logout.php" class="icon-btn"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="login.php" class="icon-btn"><i class="fas fa-user"></i></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- PRODUCTS SECTION -->
    <section class="products">
        <h2 class="section-title">Women's Collection</h2>
        
        <div class="filter-bar">
            <div>
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Tops</button>
                <button class="filter-btn">Dresses</button>
                <button class="filter-btn">Jackets</button>
            </div>
            <select class="filter-btn">
                <option>Sort by: Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest</option>
            </select>
        </div>

        <div class="product-grid">
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="product-card" data-product-id="<?php echo $product['product_id']; ?>">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                <div class="product-info">
                    <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

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

    <script src="cart.js"></script>
</body>
</html>