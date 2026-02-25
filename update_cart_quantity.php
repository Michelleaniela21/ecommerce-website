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
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    if ($cart_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart item']);
        exit;
    }
    
    // Validate quantity
    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
        exit;
    }
    
    if ($quantity > 10) {
        echo json_encode(['success' => false, 'message' => 'Maximum quantity is 10']);
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
        
        // Update quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
        $stmt->execute();
        
        // Get updated cart totals
        $stmt = $conn->prepare("
            SELECT 
                SUM(c.quantity) as total_items,
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
        $total = ($totals['subtotal'] ?? 0) + $shipping_cost;
        
        echo json_encode([
            'success' => true,
            'message' => 'Quantity updated',
            'quantity' => $quantity,
            'total_items' => $totals['total_items'] ?? 0,
            'subtotal' => $totals['subtotal'] ?? 0,
            'total' => $total,
            'shipping' => $shipping_cost
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
