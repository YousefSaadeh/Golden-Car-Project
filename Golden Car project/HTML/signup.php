<?php
include 'db.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email is already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {
                $success_message = "Account created successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="container">
        <div class="project-name">Golden Car</div>
        <div class="box">
          <h2>Create an Account</h2>
          <?php if ($error_message): ?>
              <div class="error-message"><?php echo $error_message; ?></div>
          <?php elseif ($success_message): ?>
              <div class="success-message"><?php echo $success_message; ?></div>
          <?php endif; ?>
          <form method="POST" action="">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-field">
              <input type="password" name="password" placeholder="Password" required>
              <button type="button" class="toggle-password">👁️</button>
            </div>
            <div class="password-field">
              <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <p>Already have an account? <a href="index.php">Sign in</a></p>
            <button type="submit" class="sign-up-btn">SIGN UP</button>
          </form>
        </div>
      </div>
      <script src="../JavaScript/script.js"></script>
</body>
</html>
