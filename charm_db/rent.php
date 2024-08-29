<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to rent a costume.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $costume_id = $_POST['costume_id']; // Ensure this ID exists in the Costumes table
    $delivery_method = $_POST['delivery_method'];
    $delivery_address = $_POST['delivery_address'];
    $user_id = $_SESSION['user_id'];
    $status = 'pending';
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];

    $stmt = $pdo->prepare('INSERT INTO Rentals (user_id, costume_id, delivery_method, delivery_address, status, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$user_id, $costume_id, $delivery_method, $delivery_address, $status, $payment_method, $total_amount]);

    echo "Rental successful!";
}
?>
<form method="POST">
    <input type="hidden" name="costume_id" value="1"> <!-- Replace with an actual costume ID -->
    <select name="delivery_method">
        <option value="pickup">Pick up</option>
        <option value="delivery">Delivery</option>
    </select>
    <input type="text" name="delivery_address" placeholder="Delivery Address">
    <input type="text" name="payment_method" placeholder="Payment Method">
    <input type="text" name="total_amount" placeholder="Total Amount">
    <button type="submit">Rent</button>
</form>
