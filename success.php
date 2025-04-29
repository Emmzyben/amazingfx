<?php
session_start();
require_once './database/db_config.php';

if (!isset($_GET['data'])) {
    echo "Invalid request.";
    exit;
}

// Decode and validate user data
$userData = json_decode(base64_decode($_GET['data']), true);

if (!$userData || !isset($userData['email'])) {
    echo "Invalid or corrupted user data.";
    exit;
}

$name = htmlspecialchars(trim($userData['name']));
$email = htmlspecialchars(trim($userData['email']));
$location = htmlspecialchars(trim($userData['location']));
$plan = htmlspecialchars(trim($userData['plan']));

try {
    // Ensure hashed password security

    $insertQuery = "INSERT INTO users (name, email,  location, plan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssss", $name, $email, $location, $plan);

    if ($stmt->execute()) {
        echo "<script>alert('We have receive your application and will get back to you shortly. thank you')</script>";

        // Redirect to the dashboard on success
        header("Location: index.html");
        exit;
    } else {
        echo "Failed to register user. Error: " . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    echo "Error processing the request: " . $e->getMessage();
}
?>
