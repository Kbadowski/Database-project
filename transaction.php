<?php
//Initialize the session
session_start();

//Take user to login page if not logged in

//Constants
$servername = 'localhost';
$username = 'kbadowsk'; // Flashline username
$password = 'Gx0Vf7bb'; // phpMyAdmin password
$dbname = 'kbadowsk'; // Flashline username

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_ID'];
    $serial_num = $_POST['serial_num'];
    $address_ID = $_POST['address_ID'];
    $payment_ID = $_POST['payment_ID'];

    //Create connection
    $conn = new mysqli($servername, $email, $password, $dbname);

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Insert transaction into database
    $stmt = $conn->prepare("INSERT INTO transactions (customer_ID, serial_num, address_ID, payment_ID VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isidd", $customer_ID, $serial_num, $address_ID, $payment_ID);
    if ($stmt->execute()) {
        $message = "Transaction recorded successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; text-align: center; }
        table, th, td { border: 1px solid black; }
        input[type=text], input[type=number] {
            width: 20%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #04AA6D;
            border: none;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h2>Transaction Tracker</h2>
<p>Enter details of the transaction below:</p>
<?php
if (isset($message)) {
    echo "<p><strong>$message</strong></p>";
}
?>
<form action="transaction.php" method="post">
    <p>Customer ID: <input type="number" name="customer_id" required></p>
    <p>Serial Number: <input type="text" name="serial_num" required></p>
    <p><input type="submit" value="Record Transaction"></p>
</form>
</body>
</html>
