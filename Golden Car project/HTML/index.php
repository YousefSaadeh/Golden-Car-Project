<?php
include 'db.php';
session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.html");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="container">
        <div class="project-name">Golden Car</div>
        <div class="box">
            <h2>Sign In</h2>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="index.php">
                <input type="email" name="email" placeholder="Email" required>
                <div class="password-field">
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="button" class="toggle-password">👁️</button>
                </div>
                <p>Don't have an account ? <a href="signup.php">Sign up</a></p>
                <button type="submit" class="sign-in-btn">SIGN IN</button>
            </form>
        </div>
    </div>      
    <script src="../JavaScript/script.js"></script>
</body>
</html>

