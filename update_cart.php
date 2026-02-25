<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $change = isset($_POST['change']) ? intval($_POST['change']) : 0;
    $user_id = $_SESSION['user_id'];
    
    try {
        // Get current quantity
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Cart item not found']);
            exit;
        }
        
        $cart_item = $result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $change;
        
        if ($new_quantity <= 0) {
            // Remove item if quantity is 0 or less
            $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
            $stmt->bind_param("i", $cart_id);
            $stmt->execute();
        } else {
            // Update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
            $stmt->bind_param("ii", $new_quantity, $cart_id);
            $stmt->execute();
        }
        
        echo json_encode(['success' => true, 'message' => 'Cart updated']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>