<?php
include 'db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'error' => 'User not logged in']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $car_name = $_POST['car'];
    $pickup_date = $_POST['pickupDate'];
    $return_date = $_POST['returnDate'];
    $price_per_day = $_POST['price'];

    $days = (strtotime($return_date) - strtotime($pickup_date)) / (60 * 60 * 24);
    $total_price = $days * $price_per_day;

    $sql = "INSERT INTO bookings (user_id, car_name, pickup_date, return_date, days, total_price)
            VALUES ('$user_id', '$car_name', '$pickup_date', '$return_date', '$days', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

?>
