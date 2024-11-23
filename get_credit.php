<?php
// Include database connection and session start
include 'config.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $due_date = $_POST['due_date'];
    
    // Validate amount (positive number)
    if ($amount > 0 && strtotime($due_date)) {
        // Get current user ID
        $recipient_id = $_SESSION['id'];
        $lender_id = 1; // Example: Assuming a specific lender ID or logic to determine lender

        // Check if lender_id exists in the users table
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->bind_param("i", $lender_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Insert credit request into database
            $stmt->close();
            $sql = "INSERT INTO credits (lender_id, recipient_id, amount, due_date, status) VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iids", $lender_id, $recipient_id, $amount, $due_date);
                $stmt->execute();
            
                // Check if insert was successful
                if ($stmt->affected_rows > 0) {
                    $success = "Credit request submitted successfully!";
                } else {
                    $error = "Error submitting credit request.";
                }
            
                $stmt->close();
            } else {
                $error = "Database error: " . $conn->error;
            }
        } else {
            $error = "Invalid lender ID.";
        }
    } else {
        $error = "Invalid amount or due date. Please enter a positive amount and a valid date.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credit Request</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<div id="container">
    <h1>Credit Request</h1>
    
    <?php
    if (!empty($success)) {
        echo "<p class='success'>$success</p>";
    }
    
    if (!empty($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    
    <form method="post" action="">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required>
        
        <label for="due_date">Due Date:</label>
        <input type="date" id="due_date" name="due_date" required>
        
        <button type="submit">Submit Request</button>
    </form>
    
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
