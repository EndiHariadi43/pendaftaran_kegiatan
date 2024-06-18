<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Cek apakah email ada dalam database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Email not found.";
        exit;
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $username = $user['username'];

    // Generate token unik
    $token = bin2hex(random_bytes(32));

    // Simpan token ke dalam database bersamaan dengan waktu kadaluwarsa
    $expiry_date = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE id=?");
    $stmt->bind_param("ssi", $token, $expiry_date, $user_id);
    $stmt->execute();
    $stmt->close();

    // Kirim email reset password ke pengguna
    $reset_link = "http://example.com/reset_password.php?token=" . $token;
    $to = $email;
    $subject = "Reset your password";
    $message = "Hello $username,\n\nYou have requested to reset your password. Please click the link below to reset your password:\n\n$reset_link\n\nIf you did not request this, please ignore this email.";
    $headers = "From: your_email@example.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Failed to send reset email. Please try again later.";
    }
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
    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
