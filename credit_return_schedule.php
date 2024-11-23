<?php
// Include database connection and session start
include 'config.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch credit return schedule
$sql = "SELECT c.id, u.first_name, u.last_name, c.amount, c.due_date, c.paid, c.status
        FROM credits c
        JOIN users u ON c.recipient_id = u.id
        WHERE c.lender_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']); // Assuming logged in user is the lender
$stmt->execute();
$result = $stmt->get_result();
$credits = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credit Return Schedule</title>
    <link rel="stylesheet" type="text/css" href="credit_return_schedule.css">
</head>
<body>
    <div class="container">
        <h1>Credit Return Schedule</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Recipient</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Paid</th>
                <th>Status</th>
            </tr>
            <?php foreach ($credits as $credit) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($credit['id']); ?></td>
                    <td><?php echo htmlspecialchars($credit['first_name'] . ' ' . htmlspecialchars($credit['last_name'])); ?></td>
                    <td><?php echo number_format($credit['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($credit['due_date']); ?></td>
                    <td><?php echo $credit['paid'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($credit['status'])); ?></td>
                </tr>
            <?php } ?>
        </table>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
