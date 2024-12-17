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
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
