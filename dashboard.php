<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        Secure Login System - Dashboard
    </header>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You have successfully logged in and completed Multi-Factor Authentication.</p>
        <p>This is your secure dashboard.</p>
        <a href="index.php" style="display: inline-block; margin-top: 20px;">
            <button>Logout</button>
        </a>
    </div>

    <footer>
        &copy; 2024 Secure Login System. All Rights Reserved.
    </footer>
</body>
</html>