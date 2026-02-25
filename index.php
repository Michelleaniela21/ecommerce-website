<?php
session_start();
require_once 'config.php';

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReCloth - Modern Fashion E-Commerce</title>
    <link rel="stylesheet" href="style.css">
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
                <?php if(is_logged_in()): ?>
                    <span style="margin-right: 1rem; font-weight: 500;">Hi, <?php echo htmlspecialchars($user_name); ?></span>
                    <a href="cart.php" class="icon-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo get_cart_count(); ?></span>
                    </a>
                    <a href="profile.php" class="icon-btn"><i class="fas fa-user"></i></a>
                    <a href="logout.php" class="icon-btn"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="login.php" class="icon-btn"><i class="fas fa-user"></i></a>
                    <a href="register.php" class="icon-btn"><i class="fas fa-user-plus"></i></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <h1>Redefine Your Style</h1>
        <p>Discover Premium Fashion for Everyone</p>
        <button class="btn-primary" onclick="window.location.href='men.php'">Shop Now</button>
    </section>

    <!-- CATEGORIES -->
    <section class="categories">
        <h2 class="section-title">Shop by Category</h2>
        <div class="category-grid">
            <div class="category-card" onclick="window.location.href='men.php'">
                <img src="uploads\menmode.jpg" alt="Men Fashion" class="category-image">
                <div class="category-overlay">
                    <h3>Men</h3>
                    <p>Urban Streetwear</p>
                </div>
            </div>
            
            <div class="category-card" onclick="window.location.href='women.php'">
                <img src="uploads\womenmodel.jpg" alt="Women Fashion" class="category-image">
                <div class="category-overlay">
                    <h3>Women</h3>
                    <p>Elegant & Trendy</p>
                </div>
            </div>
            
            <div class="category-card" onclick="window.location.href='kids.php'">
                <img src="uploads\KIDS\modelkids.jpeg" alt="Kids Fashion" class="category-image">
                <div class="category-overlay">
                    <h3>Kids</h3>
                    <p>Cute & Comfortable</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="products">
        <h2 class="section-title">Featured Products</h2>
        <div class="product-grid">
            <!-- Product 1 -->
            <div class="product-card">
                <img src="uploads\bajusweetsummer.jpg" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">Urban Streetwear Tee</h3>
                    <p class="product-price">Rp 40.000</p>
                    <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card">
                <img src="uploads\jaketcrop.jpg" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">Jacket Racing Crop</h3>
                    <p class="product-price">Rp 80.000</p>
                    <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card">
                <img src="uploads\redy2k.jpeg" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">Hoodie Red Y2K</h3>
                    <p class="product-price">Rp 75.000</p>
                    <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card">
                <img src="uploads\baggyjeans.jpg" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">Baggy Jeans</h3>
                    <p class="product-price">Rp 150.000</p>
                    <button class="btn-add-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
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
</body>
</html>