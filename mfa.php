<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// File to store the MFA code and expiration time
$mfa_file = 'mfa_code.txt';
$expiry_file = 'mfa_expiry.txt';

// Check if the MFA code and expiration time already exist; if not, generate them
if (!file_exists($mfa_file) || !file_exists($expiry_file)) {
    $mfa_code = rand(100000, 999999); // Generate a random 6-digit code
    $expiry_time = time() + 30; // Set the expiration time (30 seconds from now)
    
    // Save the code and expiration time to files
    file_put_contents($mfa_file, $mfa_code);
    file_put_contents($expiry_file, $expiry_time);
} else {
    // Check if the MFA code file exists and handle error if it doesn't
    if (file_exists($mfa_file)) {
        $mfa_code = file_get_contents($mfa_file); // Retrieve the existing code
    } else {
        $error = "MFA code file does not exist. Please generate a new code.";
    }

    // Check if the expiration time file exists and handle error if it doesn't
    if (file_exists($expiry_file)) {
        $expiry_time = file_get_contents($expiry_file); // Retrieve the expiration time
    } else {
        $error = "MFA expiration file does not exist. Please generate a new code.";
    }
}

// Check if the MFA code has expired
if (isset($expiry_time) && time() > $expiry_time) {
    unlink($mfa_file); // Delete the expired code file
    unlink($expiry_file); // Delete the expiration file
    $error = "The MFA code has expired. Please request a new code.";
}

// Process the submitted MFA code
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the MFA code file exists before reading it
    if (file_exists($mfa_file)) {
        $saved_code = trim(file_get_contents($mfa_file)); // Read the saved code
    } else {
        $error = "MFA code file does not exist. Please generate a new code.";
    }

    // Check if $saved_code is set before proceeding
    if (isset($saved_code)) {
        $input_code = $_POST['mfa_code'];

        if ($input_code == $saved_code && time() <= $expiry_time) {
            unlink($mfa_file); // Delete the file after successful verification
            unlink($expiry_file); // Delete the expiration file
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid MFA Code or Code Expired! Please try again.";
        }
    } else {
        $error = "MFA code is missing. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MFA Verification</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Timer countdown for MFA expiration
        let countdown = <?php echo isset($expiry_time) ? $expiry_time - time() : 0; ?>;
        function updateTimer() {
            if (countdown > 0) {
                countdown--;
                document.getElementById('timer').textContent = "Time left: " + countdown + " seconds";
            } else {
                document.getElementById('timer').textContent = "MFA Code Expired!";
            }
        }
        setInterval(updateTimer, 1000); // Update the timer every second
    </script>
</head>
<body>
    <header>
        Secure Login System - Multi-Factor Authentication
    </header>

    <div class="container">
        <h2>Multi-Factor Authentication</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="mfa_code" placeholder="Enter MFA Code" required>
            <button type="submit">Verify</button>
        </form>
        <p>Please check the file <strong>'mfa_code.txt'</strong> for your MFA code.</p>
        <p id="timer">Time left: 30 seconds</p>
    </div>

    <footer>
        &copy; 2024 Secure Login System. All Rights Reserved.
    </footer>
</body>
</html>
