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

// Fetch credits to be paid from database
$sql = "SELECT c.id, u.first_name, u.last_name AS recipient_name, c.amount, c.due_date, c.status
        FROM credits c
        JOIN users u ON c.recipient_id = u.id
        WHERE c.lender_id = ? AND c.status = 'pending'"; // Fetching pending credits to be paid
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']); // Assuming logged in user is the lender
$stmt->execute();
$result = $stmt->get_result();
$credits_to_pay = $result->fetch_all(MYSQLI_ASSOC);

if ($stmt->num_rows === 0) {
    $error = "No pending credits found for the current user.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Credits</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Pay Credits</h1>
        
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        
        <?php if (isset($credits_to_pay) && !empty($credits_to_pay)) { ?>
            <form method="post" action="process_payment.php">
                <label for="credit_id">Select Credit to Pay:</label>
                <select id="credit_id" name="credit_id" required>
                    <?php foreach ($credits_to_pay as $credit) { ?>
                        <option value="<?php echo $credit['id']; ?>">
                            <?php echo $credit['recipient_name'] . ' - $' . number_format($credit['amount'], 2); ?>
                        </option>
                    <?php } ?>
                </select>
                
                <button type="submit">Pay Credit</button>
            </form>
        <?php } else { ?>
            <p>No pending credits found for the current user.</p>
        <?php } ?>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
