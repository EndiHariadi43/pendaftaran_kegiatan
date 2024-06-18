<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = bin2hex(random_bytes(4));
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        // send email with new password (here we just display it)
        echo "New password is: $new_password";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <form method="post" action="forgot_password.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
</body>
</html>
