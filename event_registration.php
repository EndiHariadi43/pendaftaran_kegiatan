<?php
include 'config.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("location: login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $event_name = $_POST['event_name'];

    $sql = "INSERT INTO event_registrations (username, event_name) VALUES ('$username', '$event_name')";

    if ($conn->query($sql) === TRUE) {
        echo "You have successfully registered for the event.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Register for an Event</h2>
    <form method="post">
        <div class="mb-3">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
