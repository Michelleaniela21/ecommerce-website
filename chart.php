<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Size Chart - ReCloth</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .chart-section {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 3rem;
        }
        
        .chart-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #eee;
        }
        
        .chart-tab {
            padding: 1rem 2rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            color: gray;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .chart-tab.active {
            color: var(--dark-blue);
            border-bottom-color: var(--dark-blue);
        }
        
        .chart-content {
            background-color: white;
            border-radius: 15px;
            padding: 2rem;
            display: none;
        }
        
        .chart-content.active {
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #eee;
        }
        
        th {
            background-color: var(--dark-blue);
            color: white;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .guide-box {
            background-color: var(--cream);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
    </style>
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
                <li><a href="chart.php" class="active">Chart</a></li>
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

    <!-- SIZE CHART SECTION -->
    <section class="chart-section">
        <h2 class="section-title">Size Guide</h2>
        
        <div class="chart-tabs">
            <button class="chart-tab active" onclick="showChart('men')">Men</button>
            <button class="chart-tab" onclick="showChart('women')">Women</button>
            <button class="chart-tab" onclick="showChart('kids')">Kids</button>
        </div>

        <!-- MEN'S CHART -->
        <div id="men-chart" class="chart-content active">
            <h3 style="color: var(--dark-blue); margin-bottom: 1rem;">Men's Apparel Size Chart</h3>
            
            <h4 style="margin-top: 2rem;">Tops (T-Shirts, Hoodies, Jackets)</h4>
            <table>
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Chest (cm)</th>
                        <th>Length (cm)</th>
                        <th>Shoulder (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>S</strong></td>
                        <td>92-96</td>
                        <td>68-70</td>
                        <td>42-44</td>
                    </tr>
                    <tr>
                        <td><strong>M</strong></td>
                        <td>96-100</td>
                        <td>70-72</td>
                        <td>44-46</td>
                    </tr>
                    <tr>
                        <td><strong>L</strong></td>
                        <td>100-104</td>
                        <td>72-74</td>
                        <td>46-48</td>
                    </tr>
                    <tr>
                        <td><strong>XL</strong></td>
                        <td>104-108</td>
                        <td>74-76</td>
                        <td>48-50</td>
                    </tr>
                    <tr>
                        <td><strong>XXL</strong></td>
                        <td>108-112</td>
                        <td>76-78</td>
                        <td>50-52</td>
                    </tr>
                </tbody>
            </table>

            <h4 style="margin-top: 2rem;">Bottoms (Pants, Jeans)</h4>
            <table>
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Waist (cm)</th>
                        <th>Hip (cm)</th>
                        <th>Length (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>28</strong></td>
                        <td>71-74</td>
                        <td>88-91</td>
                        <td>100-102</td>
                    </tr>
                    <tr>
                        <td><strong>30</strong></td>
                        <td>76-79</td>
                        <td>93-96</td>
                        <td>102-104</td>
                    </tr>
                    <tr>
                        <td><strong>32</strong></td>
                        <td>81-84</td>
                        <td>98-101</td>
                        <td>104-106</td>
                    </tr>
                    <tr>
                        <td><strong>34</strong></td>
                        <td>86-89</td>
                        <td>103-106</td>
                        <td>106-108</td>
                    </tr>
                    <tr>
                        <td><strong>36</strong></td>
                        <td>91-94</td>
                        <td>108-111</td>
                        <td>108-110</td>
                    </tr>
                </tbody>
            </table>

            <div class="guide-box">
                <h4 style="color: var(--dark-blue); margin-bottom: 1rem;"><i class="fas fa-ruler"></i> How to Measure</h4>
                <p><strong>Chest:</strong> Measure around the fullest part of your chest, keeping the tape horizontal.</p>
                <p><strong>Waist:</strong> Measure around your natural waistline, keeping the tape comfortably loose.</p>
                <p><strong>Hip:</strong> Measure around the fullest part of your hips.</p>
                <p><strong>Length:</strong> Measure from the top of your shoulder to the desired hemline.</p>
            </div>
        </div>

        <!-- WOMEN'S CHART -->
        <div id="women-chart" class="chart-content">
            <h3 style="color: var(--dark-blue); margin-bottom: 1rem;">Women's Apparel Size Chart</h3>
            
            <h4 style="margin-top: 2rem;">Tops</h4>
            <table>
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Bust (cm)</th>
                        <th>Waist (cm)</th>
                        <th>Hip (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>XS</strong></td>
                        <td>78-82</td>
                        <td>60-64</td>
                        <td>86-90</td>
                    </tr>
                    <tr>
                        <td><strong>S</strong></td>
                        <td>82-86</td>
                        <td>64-68</td>
                        <td>90-94</td>
                    </tr>
                    <tr>
                        <td><strong>M</strong></td>
                        <td>86-90</td>
                        <td>68-72</td>
                        <td>94-98</td>
                    </tr>
                    <tr>
                        <td><strong>L</strong></td>
                        <td>90-94</td>
                        <td>72-76</td>
                        <td>98-102</td>
                    </tr>
                    <tr>
                        <td><strong>XL</strong></td>
                        <td>94-98</td>
                        <td>76-80</td>
                        <td>102-106</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- KIDS CHART -->
        <div id="kids-chart" class="chart-content">
            <h3 style="color: var(--dark-blue); margin-bottom: 1rem;">Kids' Apparel Size Chart</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Age</th>
                        <th>Size</th>
                        <th>Height (cm)</th>
                        <th>Chest (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2-3 years</td>
                        <td><strong>2-3Y</strong></td>
                        <td>92-98</td>
                        <td>52-54</td>
                    </tr>
                    <tr>
                        <td>4-5 years</td>
                        <td><strong>4-5Y</strong></td>
                        <td>104-110</td>
                        <td>56-58</td>
                    </tr>
                    <tr>
                        <td>6-7 years</td>
                        <td><strong>6-7Y</strong></td>
                        <td>116-122</td>
                        <td>60-62</td>
                    </tr>
                    <tr>
                        <td>8-9 years</td>
                        <td><strong>8-9Y</strong></td>
                        <td>128-134</td>
                        <td>64-66</td>
                    </tr>
                    <tr>
                        <td>10-12 years</td>
                        <td><strong>10-12Y</strong></td>
                        <td>140-152</td>
                        <td>68-72</td>
                    </tr>
                </tbody>
            </table>
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
        function showChart(category) {
            // Hide all charts
            document.querySelectorAll('.chart-content').forEach(chart => {
                chart.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.chart-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected chart
            document.getElementById(category + '-chart').classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>