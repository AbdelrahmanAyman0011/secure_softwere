<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Sanitizes user input using filter_input() to remove harmful characters.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING); // Input validation
    $password = $_POST['password'];

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Uses password_hash() with bcrypt to securely hash passwords.

    try { //  Uses prepared statements with placeholders (:username, :password).
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        echo "Registration successful!";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        Secure Login System
    </header>

    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>

    <footer>
        &copy; 2024 Secure Login System. All Rights Reserved.
    </footer>
</body>
</html>
