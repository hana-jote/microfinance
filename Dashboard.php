<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name']) || !isset($_SESSION['balance'])) {
    header("Location: login.php");
    exit();
}
$full_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
?>


<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>

<body>
<div id="container">
    <h1>Welcome, <?php echo $_SESSION['first_name']; ?>!</h1><br />
    <p>Your balance is: $<?php echo number_format($_SESSION['balance'], 2); ?></p>
</div>  
    <br />
    <div class="interface">
        <div class="box">
            <h1>Save in to the account</h1>
            <p> wale come to save money to our microfinance</p>
            <a href="save_money.php">Save Money</a>
        </div>
        <div class="box">
            <h1>withdraw balance</h1>
            <p> saving is important for our life. don't forget saving</p>
            <a href="give_credit.php">withdraw your balance</a>
        </div>
        <div class="box">
            <h1>
                Get our credit services 
            </h1>
            <p>Your trusted partner in managing your credit effectively.</p>
            <a href="get_credit.php">Get Credit</a>
        </div>

        <div class="box">
            <h1>Pay your Credit or Debit</h1>
            <p>we are thank you to return your credit </p>
            <a href="credit_return_schedule.php">Credit Return Schedule</a>
        </div>
      

    </div>
    <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
</body>

</html>