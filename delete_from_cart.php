<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    
    if ($cart_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item']);
        exit;
    }
    
    try {
        // Check if cart item belongs to user
        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Cart item not found']);
            exit;
        }
        
        // Delete from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        
        // Get updated cart totals
        $stmt = $conn->prepare("
            SELECT 
                COUNT(c.cart_id) as total_items,
                SUM(c.quantity) as total_quantity,
                SUM(c.quantity * p.price) as subtotal
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $totals = $result->fetch_assoc();
        
        $shipping_cost = 25000;
        $subtotal = $totals['subtotal'] ?? 0;
        $total = $subtotal > 0 ? $subtotal + $shipping_cost : 0;
        
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart',
            'total_items' => $totals['total_quantity'] ?? 0,
            'cart_items_count' => $totals['total_items'] ?? 0,
            'subtotal' => $subtotal,
            'total' => $total,
            'shipping' => $subtotal > 0 ? $shipping_cost : 0,
            'is_empty' => $totals['total_items'] == 0
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
