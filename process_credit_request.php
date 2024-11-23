<?php
include 'config.php'; // Include your database connection file
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $lender_id = $_SESSION['id']; // The current user is the lender
    
    // Validate amount (positive number)
    if ($amount > 0 && strtotime($due_date)) {
        // Insert credit request into database
        $sql = "INSERT INTO credits (lender_id, amount, due_date, status) VALUES (?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ids", $lender_id, $amount, $due_date);
        
        if ($stmt->execute()) {
            $success = "Credit request submitted successfully!";
        } else {
            $error = "Error submitting credit request: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $error = "Invalid amount or due date. Please enter a positive amount and a valid date.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Credit Request Result</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Add your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Credit Request Result</h1>
        
        <?php
        if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (!empty($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>
        
        <a href="get_credit.php">Back to Credit Request</a>
    </div>
</body>
</html>
