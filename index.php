<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index">Event Platform</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['loggedin'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="event_registration">Register for Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="forgot_password">Forgot Password</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="donation">Donate</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center">Welcome to Event Platform</h1>
    <p class="text-center">Your one-stop platform for event registration and donations.</p>

    <div class="row">
        <div class="col-md-6">
            <h3>Upcoming Events</h3>
            <p>Join our exciting events and be a part of something amazing!</p>
            <ul>
                <li>Event 1: Date, Location</li>
                <li>Event 2: Date, Location</li>
                <li>Event 3: Date, Location</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Features</h3>
            <ul>
                <li>User Registration and Login</li>
                <li>Event Registration</li>
                <li>Forgot Password</li>
                <li>Donation Integration with Midtrans</li>
            </ul>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <a href="donation" class="btn btn-primary">Donate Now</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
