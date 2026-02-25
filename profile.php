<?php
session_start();
require_once 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch user data
$stmt = $conn->prepare("SELECT user_id, username, email, full_name, bio, created_at FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle bio update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bio'])) {
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    
    if (strlen($bio) > 500) {
        $error = "Deskripsi tidak boleh lebih dari 500 karakter";
    } else {
        $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE user_id = ?");
        $stmt->bind_param("si", $bio, $user_id);
        
        if ($stmt->execute()) {
            $message = "Deskripsi berhasil diperbarui";
            $user['bio'] = $bio;
        } else {
            $error = "Gagal memperbarui deskripsi";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - ReCloth</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
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
                    <span class="cart-count"><?php echo get_cart_count(); ?></span>
                </a>
                <a href="profile.php" class="icon-btn"><i class="fas fa-user"></i></a>
                <a href="logout.php" class="icon-btn"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </nav>
    </header>

    <!-- PROFILE SECTION -->
    <section class="profile-container">
        <div class="profile-wrapper">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-header-content">
                    <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
                    <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
                    <p class="joined-date">
                        <i class="fas fa-calendar-alt"></i>
                        Bergabung sejak <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                    </p>
                </div>
            </div>

            <!-- Messages -->
            <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Left Column -->
                <div class="profile-left">
                    <!-- User Info Card -->
                    <div class="info-card">
                        <h2>Informasi Pribadi</h2>
                        
                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <p class="info-value"><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <p class="info-value"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-id-card"></i> Nama Lengkap
                            </label>
                            <p class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></p>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="stats-card">
                        <h2>Statistik</h2>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Pesanan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Pembelian</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Ulasan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="profile-right">
                    <!-- Bio/Description Card -->
                    <div class="bio-card">
                        <h2>Deskripsi Profil</h2>
                        
                        <form method="POST" class="bio-form">
                            <div class="form-group">
                                <label for="bio" class="form-label">Ceritakan Tentang Dirimu</label>
                                <textarea 
                                    id="bio" 
                                    name="bio" 
                                    class="bio-textarea" 
                                    placeholder="Bagikan deskripsi atau biografi singkat tentang dirimu..." 
                                    maxlength="500"
                                ><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                <small class="char-count">
                                    <span id="charCount"><?php echo strlen($user['bio'] ?? ''); ?></span>/500
                                </small>
                            </div>

                            <button type="submit" name="update_bio" class="btn-update">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </form>

                        <!-- Display Bio -->
                        <?php if ($user['bio']): ?>
                        <div class="bio-display">
                            <h3>Deskripsi Saat Ini</h3>
                            <p><?php echo htmlspecialchars($user['bio']); ?></p>
                        </div>
                        <?php else: ?>
                        <div class="bio-empty">
                            <p>Belum ada deskripsi. Mulai tambahkan untuk membuat profil Anda lebih menarik!</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Actions -->
                    <div class="actions-card">
                        <h2>Aksi Cepat</h2>
                        <div class="actions-list">
                            <a href="cart.php" class="action-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Lihat Keranjang</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="men.php" class="action-link">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Lanjutkan Belanja</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="logout.php" class="action-link logout-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
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

    <script>
        // Update character count
        const bioTextarea = document.getElementById('bio');
        const charCount = document.getElementById('charCount');

        bioTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Auto-hide alerts after 4 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            }, 4000);
        });
    </script>
</body>
</html>