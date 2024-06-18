<?php
session_start();
include 'config.php';

// Ambil foto profil pengguna jika sudah login
$user_photo = '';
if (isset($_SESSION['loggedin'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_photo = $row['photo'];
    }
    $stmt->close();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><img src="path/to/your/logo.png" alt="Logo" height="30"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Events</a>
                </li>
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION['loggedin']) && !empty($user_photo)) : ?>
                <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="Profile Photo" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
            <?php endif; ?>
        </div>
    </div>
</nav>
