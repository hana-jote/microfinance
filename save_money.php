<?php
include 'config.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    if ($amount > 0) {
        $userId = $_SESSION['id'];
        $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $amount, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['balance'] += $amount;
            $success = "Money saved successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Invalid amount. Please enter a positive value.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Save Money</title>
    <style>
                body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            position: absolute;
            left:40%;
            margin: 0;
            padding: 0;
            align-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            display: none; /* Hidden by default */
        }
        .success {
            color: green;
            margin-bottom: 10px;
            display: none; /* Hidden by default */
        }
        form {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        label {
            font-size: 1.2rem;
            color: #333;
        }
        input[type="number"] {

            width:80%;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
                input[type="number"]:hover {
                    width: 90%;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);}
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 15px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }
        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
  
    <h1>Save Money</h1><br/>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class='success'>$success</div>"; } ?>
    <form method="post" action="">
        <label>Amount:</label>
        <input type="number" name="amount" step="0.01" required><br>
        <button type="submit">Save Money</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>