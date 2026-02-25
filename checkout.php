<?php
// BARIS PENTING: Supaya kalau ada error, muncul teksnya (bukan layar putih)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php'; 

// 1. CEK LOGIN (Standar)
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Login dulu boss!'); window.location.href='login.php';</script>";
    exit;
}

$user_name = $_SESSION['username'];
$user_id   = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// 2. PROSES PESANAN (Hanya jalan kalau tombol ditekan)
if (isset($_POST['buat_pesanan'])) {
    
    // Ambil inputan
    $nama     = $_POST['nama_penerima'];
    $hp       = $_POST['no_hp'];
    $alamat   = $_POST['alamat'];
    $metode   = $_POST['pembayaran'];
    
    $full_address = "Penerima: $nama. Alamat: $alamat";
    $tanggal      = date('Y-m-d H:i:s');
    $status       = 'Pending';

    // === BAGIAN PERBAIKAN MATEMATIKA (SUPAYA TIDAK 0) ===
    $total_bayar = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // Ambil harga, hapus "Rp" dan titik "."
            $harga_mentah = isset($item['price']) ? $item['price'] : 0;
            $harga_bersih = preg_replace("/[^0-9]/", "", $harga_mentah);
            
            $qty = isset($item['quantity']) ? $item['quantity'] : 1;
            
            // Hitung
            $total_bayar += (int)$harga_bersih * (int)$qty;
        }
    }

    // Masukin ke Database ORDERS
    $sql_order = "INSERT INTO orders (user_id, total_amount, shipping_address, status, order_date, phone_number, payment_method) 
                  VALUES ('$user_id', '$total_bayar', '$full_address', '$status', '$tanggal', '$hp', '$metode')";
    
    if (mysqli_query($conn, $sql_order)) {
        $order_id_baru = mysqli_insert_id($conn);

        // Masukin ke Database ORDER_ITEMS
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $pid = isset($item['id']) ? $item['id'] : (isset($item['product_id']) ? $item['product_id'] : 0);
                $qty = isset($item['quantity']) ? $item['quantity'] : 1;
                $sz  = isset($item['size']) ? $item['size'] : '-';
                
                // Bersihin harga lagi buat database
                $h_raw = isset($item['price']) ? $item['price'] : 0;
                $h_db  = preg_replace("/[^0-9]/", "", $h_raw);

                $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, size, price)
                             VALUES ('$order_id_baru', '$pid', '$qty', '$sz', '$h_db')";
                mysqli_query($conn, $sql_item);
            }
        }
        
        // Hapus Keranjang & Selesai
        unset($_SESSION['cart']);
        echo "<script>alert('Pesanan Berhasil!'); window.location.href='index.php';</script>";
    } else {
        echo "Gagal simpan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Tambahan biar rapi dikit */
        .box { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-grup { margin-bottom: 15px; }
        .form-grup label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-grup input, .form-grup textarea, .form-grup select { width: 100%; padding: 8px; }
        .tombol { background: #333; color: #fff; padding: 10px 20px; border: none; cursor: pointer; width: 100%; }
    </style>
</head>
<body>

    <header class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">ReCloth</a>
        </nav>
    </header>

    <div class="box">
        <h2>Checkout Pengiriman</h2>
        <br>

        <form action="" method="POST">
            <div class="form-grup">
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" value="<?php echo $user_name; ?>" required>
            </div>
            
            <div class="form-grup">
                <label>Nomor HP / WA</label>
                <input type="text" name="no_hp" required>
            </div>

            <div class="form-grup">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required></textarea>
            </div>

            <div class="form-grup">
                <label>Metode Pembayaran</label>
                <select name="pembayaran">
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="COD">COD</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>

            <hr><br>
            
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 20px;">
                Total Bayar: 
                <?php 
                // Hitung lagi cuma buat tampilan
                $tampil_total = 0;
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $itm){
                        $pr = isset($itm['price']) ? $itm['price'] : 0;
                        $qt = isset($itm['quantity']) ? $itm['quantity'] : 1;
                        $pr_clean = preg_replace("/[^0-9]/", "", $pr);
                        $tampil_total += (int)$pr_clean * (int)$qt;
                    }
                }
                echo "Rp " . number_format($tampil_total, 0, ',', '.');
                ?>
            </div>

            <button type="submit" name="buat_pesanan" class="tombol">Buat Pesanan</button>
            <br><br>
            <a href="index.php">Batal</a>
        </form>
    </div>

</body>
</html>