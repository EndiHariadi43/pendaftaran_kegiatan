<?php
session_start();
include 'config.php';

// Fungsi untuk mendapatkan jumlah donasi
function getTotalDonation($event_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT SUM(amount) AS total_donation FROM donations WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_donation'] ? $row['total_donation'] : 0;
}

// Fungsi untuk mendapatkan status donasi pengguna pada suatu kegiatan
function getUserDonationStatus($event_id, $user_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM donations WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Fungsi untuk mendapatkan status pendaftaran pengguna pada suatu kegiatan
function getUserRegistrationStatus($event_id, $user_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Proses pendaftaran kegiatan
if (isset($_POST['register_event'])) {
    if (!isset($_SESSION['loggedin'])) {
        header("location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];

    // Periksa apakah pengguna sudah terdaftar pada kegiatan
    if (getUserRegistrationStatus($event_id, $user_id)) {
        echo "You are already registered for this event.";
        exit;
    }

    // Tambahkan pendaftaran kegiatan ke database
    $stmt = $conn->prepare("INSERT INTO event_registrations (event_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $event_id, $user_id);
    if ($stmt->execute()) {
        echo "Event registration successful.";
    } else {
        echo "Failed to register for event.";
    }
    $stmt->close();
}

// Proses donasi kegiatan
if (isset($_POST['donate'])) {
    if (!isset($_SESSION['loggedin'])) {
        header("location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $amount = $_POST['amount'];

    // Validasi jumlah donasi (misalnya, minimal donasi positif)
    if ($amount <= 0) {
        echo "Please enter a valid donation amount.";
        exit;
    }

    // Periksa apakah pengguna sudah melakukan donasi pada kegiatan ini
    if (getUserDonationStatus($event_id, $user_id)) {
        echo "You have already donated to this event.";
        exit;
    }

    // Tambahkan donasi ke database
    $stmt = $conn->prepare("INSERT INTO donations (event_id, user_id, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $event_id, $user_id, $amount);
    if ($stmt->execute()) {
        echo "Donation successful.";
    } else {
        echo "Failed to donate.";
    }
    $stmt->close();
}

// Ambil daftar kegiatan
$stmt = $conn->prepare("SELECT * FROM events");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Events</h1>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="card-text">Venue: <?php echo htmlspecialchars($row['venue']); ?></p>
                    <p class="card-text">Start Date: <?php echo htmlspecialchars($row['start_date']); ?></p>
                    <p class="card-text">End Date: <?php echo htmlspecialchars($row['end_date']); ?></p>
                    <p class="card-text">Total Donation: <?php echo getTotalDonation($row['id']); ?></p>
                    
                    <?php if (isset($_SESSION['loggedin'])) : ?>
                        <?php if (!getUserRegistrationStatus($row['id'], $_SESSION['user_id'])) : ?>
                            <form method="post">
                                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="register_event" class="btn btn-primary">Register</button>
                            </form>
                        <?php else : ?>
                            <p>You are already registered for this event.</p>
                        <?php endif; ?>

                        <?php if (!getUserDonationStatus($row['id'], $_SESSION['user_id'])) : ?>
                            <form method="post">
                                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Enter donation amount:</label>
                                    <input type="number" min="1" step="any" class="form-control" id="amount" name="amount" required>
                                </div>
                                <button type="submit" name="donate" class="btn btn-success">Donate</button>
                            </form>
                        <?php else : ?>
                            <p>You have already donated to this event.</p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p><a href="login.php">Login</a> to register and donate.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
