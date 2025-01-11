<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user_id']; // استرجاع معرف المستخدم من الجلسة

$sql = "SELECT car_name, pickup_date, return_date, total_price, days FROM bookings WHERE user_id = ? ORDER BY pickup_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Booking History - Golden Car</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/home.css">
    <link rel="stylesheet" href="../CSS/history.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo">Golden Car</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="home.html">Home</a></li>
                    <li><a href="carslist.html">Cars</a></li>
                    <li><a href="booking.html">Booking</a></li>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="history.php">History</a></li>
                </ul>
            </nav>
            <button class="logout-button" onclick="logout()">Log Out</button>
        </div>
    </header>

    <main>
        <section class="history-section">
            <div class="container">
                <h2>Your Booking History</h2>

                <?php if ($result->num_rows > 0): ?>
                    <table class="booking-history-table">
                        <thead>
                            <tr>
                                <th>Car Name</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Total Price</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['car_name']; ?></td>
                                    <td><?php echo $row['pickup_date']; ?></td>
                                    <td><?php echo $row['return_date']; ?></td>
                                    <td><?php echo $row['total_price']; ?> USD</td>
                                    <td><?php echo $row['days']; ?> days</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-booking">You have no booking history yet.</p>
                <?php endif; ?>

            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2024 Golden Car. All rights reserved.</p>
        </div>
    </footer>

    <script src="../JavaScript/script.js"></script>
</body>
</html>
