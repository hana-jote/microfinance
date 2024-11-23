<?php
include 'config.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_phone = $_POST['recipient_phone'];
    $amount = $_POST['amount'];
    
    // Validate amount and recipient phone number (basic validation)
    if ($amount <= 0 || !is_numeric($amount)) {
        $error = "Invalid amount. Please enter a valid positive number.";
    } else {
        // Fetch recipient's user id and balance
        $sql = "SELECT id, balance FROM users WHERE phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $recipient_phone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $recipient = $result->fetch_assoc();
            $recipient_id = $recipient['id'];
            $recipient_balance = $recipient['balance'];
            
            // Check if sender has sufficient balance
            if ($_SESSION['balance'] >= $amount) {
                // Start transaction
                $conn->begin_transaction();

                try {
                    // Update sender's balance
                    $sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("di", $amount, $_SESSION['id']);
                    $stmt->execute();

                    // Update recipient's balance
                    $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("di", $amount, $recipient_id);
                    $stmt->execute();

                    // Commit transaction
                    $conn->commit();

                    // Update session balance
                    $_SESSION['balance'] -= $amount;
                    $success = "Credit given successfully!";
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $conn->rollback();
                    $error = "Transaction failed: " . $e->getMessage();
                }
            } else {
                $error = "Insufficient balance.";
            }
        } else {
            $error = "Recipient not found!";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Balance</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="container">
        <h1>Withdraw Balance</h1>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='success'>$success</div>"; } ?>
        <form method="post" action="">
            <label>Recipient Phone Number:</label>
            <input type="text" name="recipient_phone" required><br>
            <label>Amount:</label>
            <input type="number" name="amount" step="0.01" required><br>
            <button type="submit">Withdraw Balance</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
