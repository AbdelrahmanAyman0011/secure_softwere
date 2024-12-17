<?php // Ensures that only authenticated users (those who have logged in) can access the MFA page.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// File to store the MFA code
$mfa_file = 'mfa_code.txt';
// : Prevents the MFA code from changing on every page reload, which avoids confusion and ensures reliability.

// Check if the MFA code already exists; if not, generate it
if (!file_exists($mfa_file)) {
    $mfa_code = rand(100000, 999999); // Generate a random 6-digit code
    file_put_contents($mfa_file, $mfa_code); // Save the code to the file  Provides a simple, secure, and persistent storage mechanism for small-scale applications.
} else {
    $mfa_code = file_get_contents($mfa_file); // Retrieve the existing code
}

// Display instructions to the user
echo "An MFA code has been generated. Check the file 'mfa_code.txt' for the code.<br>";

// Process the submitted MFA code
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_code = $_POST['mfa_code'];
    $saved_code = trim(file_get_contents($mfa_file)); // Read the saved code

    if ($input_code == $saved_code) {
        unlink($mfa_file); // Delete the file after successful verification
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid MFA Code! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MFA Verification</title>
</head>
<body>
    <h2>Multi-Factor Authentication</h2>
    <form method="POST" action="">
        Enter MFA Code: <input type="text" name="mfa_code" required><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
