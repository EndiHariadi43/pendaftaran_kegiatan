<?php
include 'config.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("location: login");
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Profile</h2>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <p><?php echo $user['username']; ?></p>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <p><?php echo $user['email']; ?></p>
    </div>
    <div class="mb-3">
        <label class="form-label">Role</label>
        <p><?php echo $user['role']; ?></p>
    </div>
</div>
</body>
</html>
